<?php
 
namespace App\Livewire;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Transaksi, RawatInap};
 
class TransaksiComponent extends Component
{
    use WithPagination;
 
    // Properti Form
    public ?int   $transaksiId  = null;
    public string $rawatInapId  = '';
    public string $biayaLain    = '0';
    public string $caraBayar    = 'tunai';
    public string $statusBayar  = 'belum_bayar';
    public string $tanggalBayar = '';
    public string $catatan      = '';
 
    // Hasil kalkulasi (readonly)
    public float $biayaKamar  = 0;
    public float $biayaDokter = 0;
    public float $totalBiaya  = 0;
 
    // Properti UI
    public bool   $tampilForm        = false;
    public bool   $modeEdit          = false;
    public bool   $hanyaLihat        = false;  // true jika role = pasien
    public string $rolePengguna      = '';
    public string $cari              = '';
    public string $filterStatusBayar = '';
 
    // ─────────────────────────────────────────
    // MOUNT — cek role & atur mode tampilan
    // ─────────────────────────────────────────
    public function mount(): void
    {
        $role = auth()->user()->role;
 
        // Hanya admin, petugas, dan pasien yang boleh akses
        if (!in_array($role, ['admin', 'petugas', 'pasien'])) {
            abort(403, 'Akses ditolak untuk halaman transaksi.');
        }
 
        $this->rolePengguna = $role;
 
        // Pasien hanya boleh melihat, tidak bisa edit/hapus
        $this->hanyaLihat = ($role === 'pasien');
    }
 
    protected function rules(): array
    {
        return [
            'rawatInapId'  => 'required|exists:rawat_inaps,id',
            'biayaLain'    => 'required|numeric|min:0',
            'caraBayar'    => 'required|in:tunai,bpjs,asuransi,transfer',
            'statusBayar'  => 'required|in:belum_bayar,lunas,cicilan',
            'tanggalBayar' => 'nullable|date',
            'catatan'      => 'nullable|string|max:500',
        ];
    }
 
    protected function messages(): array
    {
        return [
            'rawatInapId.required' => 'Data rawat inap wajib dipilih.',
            'biayaLain.numeric'    => 'Biaya tambahan harus berupa angka.',
            'caraBayar.required'   => 'Metode pembayaran wajib dipilih.',
            'statusBayar.required' => 'Status pembayaran wajib dipilih.',
        ];
    }
 
    // Hitung ulang total biaya saat rawat inap dipilih
    public function updatedRawatInapId(): void
    {
        $this->hitungBiaya();
    }
 
    // Hitung ulang total saat biaya lain berubah
    public function updatedBiayaLain(): void
    {
        $this->totalBiaya = $this->biayaKamar + $this->biayaDokter + (float)$this->biayaLain;
    }
 
    // ─────────────────────────────────────────
    // HITUNG BIAYA OTOMATIS
    // ─────────────────────────────────────────
    private function hitungBiaya(): void
    {
        if (!$this->rawatInapId) return;
 
        $ri = RawatInap::with(['kamar', 'dokter'])->find($this->rawatInapId);
        if (!$ri) return;
 
        // Hitung biaya kamar: harga_malam × lama dirawat
        $this->biayaKamar  = $ri->lama_dirawat * $ri->kamar->harga_malam;
        $this->biayaDokter = $ri->dokter->tarif_konsultasi;
        $this->totalBiaya  = $this->biayaKamar + $this->biayaDokter + (float)$this->biayaLain;
    }
 
    public function bukaFormTambah(): void
    {
        // Pasien tidak bisa membuat tagihan
        if ($this->hanyaLihat) {
            session()->flash('error', 'Anda tidak memiliki izin untuk membuat tagihan.');
            return;
        }
        $this->resetForm();
        $this->tampilForm = true;
        $this->modeEdit   = false;
    }
 
    // ─────────────────────────────────────────
    // SIMPAN TAGIHAN BARU
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        if ($this->hanyaLihat) { abort(403); }
        $this->validate();
 
        $ri = RawatInap::with(['kamar', 'dokter'])->findOrFail($this->rawatInapId);
 
        // Pastikan rawat inap belum punya tagihan
        if ($ri->transaksi()->exists()) {
            $this->addError('rawatInapId', 'Rawat inap ini sudah memiliki tagihan!');
            return;
        }
 
        $this->hitungBiaya();
 
        Transaksi::create([
            'rawat_inap_id' => $this->rawatInapId,
            'biaya_kamar'   => $this->biayaKamar,
            'biaya_dokter'  => $this->biayaDokter,
            'biaya_lain'    => (float)$this->biayaLain,
            'total_biaya'   => $this->totalBiaya,
            'cara_bayar'    => $this->caraBayar,
            'status_bayar'  => $this->statusBayar,
            'tanggal_bayar' => $this->tanggalBayar ?: null,
            'catatan'       => $this->catatan ?: null,
        ]);
 
        // Jika langsung lunas: tandai rawat inap selesai & bebaskan kamar
        if ($this->statusBayar === 'lunas') {
            $ri->update(['status' => 'selesai', 'tanggal_keluar' => now()]);
            $ri->kamar->update(['status' => 'tersedia']);
        }
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Tagihan berhasil dibuat!');
    }
 
    // ─────────────────────────────────────────
    // KONFIRMASI PEMBAYARAN LUNAS (1 klik)
    // ─────────────────────────────────────────
    public function tandaiLunas(int $id): void
    {
        if ($this->hanyaLihat) { abort(403); }
 
        $transaksi = Transaksi::with(['rawatInap.kamar'])->findOrFail($id);
 
        $transaksi->update([
            'status_bayar'   => 'lunas',
            'tanggal_bayar'  => now()->toDateString(),
        ]);
 
        // Otomatis: tandai rawat inap selesai + bebaskan kamar
        $transaksi->rawatInap->update([
            'status'         => 'selesai',
            'tanggal_keluar' => now()->toDateString(),
        ]);
        $transaksi->rawatInap->kamar->update(['status' => 'tersedia']);
 
        session()->flash('pesan', '✅ Pembayaran dikonfirmasi. Pasien telah dinyatakan pulang.');
    }
 
    public function hapus(int $id): void
    {
        if ($this->hanyaLihat) { abort(403); }
        Transaksi::findOrFail($id)->delete();
        session()->flash('pesan', '✅ Data tagihan berhasil dihapus.');
    }
 
    public function resetForm(): void
    {
        $this->reset(['transaksiId','rawatInapId','catatan','tanggalBayar']);
        $this->biayaLain    = '0';
        $this->caraBayar    = 'tunai';
        $this->statusBayar  = 'belum_bayar';
        $this->biayaKamar   = $this->biayaDokter = $this->totalBiaya = 0;
        $this->resetErrorBag();
    }
 
    public function render()
    {
        $query = Transaksi::with(['rawatInap.pasien.pengguna', 'rawatInap.kamar']);
 
        // Pasien hanya melihat tagihan miliknya sendiri
        if ($this->rolePengguna === 'pasien') {
            $pasienId = auth()->user()->pasien?->id;
            $query->whereHas('rawatInap', fn($q) => $q->where('pasien_id', $pasienId));
        }
 
        $transaksi = $query
            ->when($this->cari, fn($q) =>
                $q->where('nomor_invoice', 'like', '%'.$this->cari.'%')
                  ->orWhereHas('rawatInap.pasien.pengguna', fn($q2) =>
                      $q2->where('nama', 'like', '%'.$this->cari.'%')
                  )
            )
            ->when($this->filterStatusBayar,
                fn($q) => $q->where('status_bayar', $this->filterStatusBayar)
            )
            ->latest()->paginate(10);
 
        // Rawat inap yang belum memiliki tagihan (untuk dropdown)
        $rawatInapBelumTagih = RawatInap::with(['pasien.pengguna','kamar'])
            ->doesntHave('transaksi')
            ->where('status', 'dirawat')
            ->get();
 
        return view('livewire.transaksi-component',
            compact('transaksi', 'rawatInapBelumTagih')
        );
    }
}
 
