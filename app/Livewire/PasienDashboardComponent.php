<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RawatInap;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class PasienDashboardComponent extends Component
{
    use WithPagination;

    // ─────────────────────────────────────────────────────────────────
    // PROPERTI FILTER & PENCARIAN
    // ─────────────────────────────────────────────────────────────────

    /** Kata kunci pencarian nama dokter */
    public string $cari          = '';

    /** Filter status rawat inap: dirawat | selesai | dirujuk | meninggal */
    public string $filterStatus  = '';

    /** Simpan role pengguna agar mudah diakses di view */
    public string $rolePengguna  = '';

    /** Simpan ID pasien yang login — dipakai di setiap query */
    public int $pasienId         = 0;

    // Simpan filter di URL agar bisa dibookmark / di-share
    protected array $queryString = [
        'cari'         => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    // ─────────────────────────────────────────────────────────────────
    // MOUNT — validasi role & ambil ID pasien
    // ─────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        // Keamanan: hanya role pasien yang diizinkan
        if (auth()->user()->role !== 'pasien') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Pasien.');
        }

        $this->rolePengguna = auth()->user()->role;

        // Simpan pasienId ke properti — hindari query berulang
        $this->pasienId = auth()->user()->pasien?->id
            ?? abort(403, 'Profil pasien tidak ditemukan. Hubungi administrator.');
    }

    // ─────────────────────────────────────────────────────────────────
    // RESET PAGINATION SAAT FILTER BERUBAH
    // ─────────────────────────────────────────────────────────────────

    public function updatingCari(): void        { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    // ─────────────────────────────────────────────────────────────────
    // HELPER PRIVATE — hitung statistik pasien
    // Dipisah agar render() tetap bersih & mudah dibaca
    // ─────────────────────────────────────────────────────────────────

    /**
     * Hitung semua statistik kesehatan & keuangan pasien dalam
     * satu query aggregasi — lebih efisien daripada query terpisah.
     */
    private function hitungStatistik(): array
    {
        // Query dasar rawat inap milik pasien ini
        $base = RawatInap::where('pasien_id', $this->pasienId);

        // ── Statistik Rawat Inap ──
        $totalRawatInap   = (clone $base)->count();
        $rawatInapAktif   = (clone $base)->where('status', 'dirawat')->count();
        $rawatInapSelesai = (clone $base)->where('status', 'selesai')->count();
        $rawatInapDirujuk = (clone $base)->where('status', 'dirujuk')->count();

        // ── Statistik Tagihan (via relasi transaksi) ──
        // Ambil semua rawat_inap_id milik pasien ini
        $rawatInapIds = (clone $base)->pluck('id');

        $totalTagihan = Transaksi::whereIn('rawat_inap_id', $rawatInapIds)
            ->sum('total_biaya');

        $tagihanLunas = Transaksi::whereIn('rawat_inap_id', $rawatInapIds)
            ->where('status_bayar', 'lunas')
            ->sum('total_biaya');

        $tagihanBelumBayar = Transaksi::whereIn('rawat_inap_id', $rawatInapIds)
            ->where('status_bayar', 'belum_bayar')
            ->count();

        $tagihanCicilan = Transaksi::whereIn('rawat_inap_id', $rawatInapIds)
            ->where('status_bayar', 'cicilan')
            ->count();

        // ── BONUS: Hitung total lama dirawat pasien (semua riwayat) ──
        $totalHariDirawat = RawatInap::where('pasien_id', $this->pasienId)
            ->where('status', '!=', 'dirawat') // sudah keluar
            ->whereNotNull('tanggal_keluar')
            ->get()
            ->sum(fn ($ri) => $ri->tanggal_masuk->diffInDays($ri->tanggal_keluar));

        return compact(
            'totalRawatInap',
            'rawatInapAktif',
            'rawatInapSelesai',
            'rawatInapDirujuk',
            'totalTagihan',
            'tagihanLunas',
            'tagihanBelumBayar',
            'tagihanCicilan',
            'totalHariDirawat',
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // RENDER — kirim semua data ke view
    // ─────────────────────────────────────────────────────────────────

    public function render()
    {
      
        // Eager load relasi pengguna agar tidak ada N+1 di view
        $pasien = auth()->user()->pasien()->with('pengguna')->first();


        $riwayatRawatInap = RawatInap::with([
                'dokter.pengguna',   // Nama dokter
                'kamar',             // Data kamar (nomor, tipe, harga)
                'transaksi',         // Detail tagihan
            ])
            ->where('pasien_id', $this->pasienId)

            // Filter pencarian nama dokter
            ->when($this->cari, function ($query) {
                $query->whereHas('dokter.pengguna', fn ($q) =>
                    $q->where('nama', 'like', '%' . $this->cari . '%')
                );
            })

            // Filter berdasarkan status rawat inap
            ->when($this->filterStatus, fn ($query) =>
                $query->where('status', $this->filterStatus)
            )

            ->latest()       // Terbaru di atas
            ->paginate(10);  // 10 data per halaman


        $statistik = $this->hitungStatistik();

        // Flatten statistik agar mudah di-compact (opsional — bisa juga
        // kirim sebagai array $statistik, pilih mana yang lebih nyaman)
        extract($statistik);

   
        // Ambil data rawat inap yang sedang aktif (status = dirawat)
        // untuk ditampilkan sebagai highlight di atas dashboard
        $rawatInapAktifDetail = RawatInap::with(['dokter.pengguna', 'kamar'])
            ->where('pasien_id', $this->pasienId)
            ->where('status', 'dirawat')
            ->latest()
            ->first(); // Biasanya hanya 1 rawat inap aktif per pasien

        return view('livewire.pasien-dashboard-component', compact(
            // Profil
            'pasien',

            // Riwayat (paginated)
            'riwayatRawatInap',

            // Statistik rawat inap
            'totalRawatInap',
            'rawatInapAktif',
            'rawatInapSelesai',
            'rawatInapDirujuk',

            // Statistik tagihan
            'totalTagihan',
            'tagihanLunas',
            'tagihanBelumBayar',
            'tagihanCicilan',

            // Bonus
            'totalHariDirawat',
            'rawatInapAktifDetail',
        ));
    }
}