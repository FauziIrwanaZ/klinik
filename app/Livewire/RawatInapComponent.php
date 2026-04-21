<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{RawatInap, Pasien, Dokter, Kamar, Transaksi};

class RawatInapComponent extends Component
{
    use WithPagination;

    // ─────────────────────────────────────────
    // PROPERTI FORM — data rawat inap
    // ─────────────────────────────────────────
    public ?int   $rawatInapId   = null;
    public string $pasienId      = '';
    public string $dokterId      = '';
    public string $kamarId       = '';
    public string $tanggalMasuk  = '';
    public string $tanggalKeluar = '';
    public string $keluhan       = '';
    public string $diagnosa      = '';
    public string $catatanDokter = '';
    public string $tindakanMedis = '';
    public string $status        = 'dirawat';

    // Properti UI
    public bool   $tampilForm   = false;
    public bool   $modeEdit     = false;
    public bool   $modeDiagnosa = false;
    public string $cari         = '';
    public string $filterStatus = '';
    public string $rolePengguna = '';

    // ─────────────────────────────────────────
    // MOUNT
    // ─────────────────────────────────────────
    public function mount(): void
    {
        $role = auth()->user()->role;

        if (!in_array($role, ['petugas', 'dokter', 'admin'])) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Petugas, Dokter, dan Admin.');
        }

        $this->rolePengguna = $role;
    }

    // ─────────────────────────────────────────
    // VALIDASI
    // ─────────────────────────────────────────
    protected function rules(): array
    {
        if ($this->modeDiagnosa) {
            return [
                'diagnosa'      => 'required|string',
                'catatanDokter' => 'nullable|string',
                'tindakanMedis' => 'nullable|string',
                'status'        => 'required|in:dirawat,selesai,dirujuk,meninggal',
            ];
        }

        return [
            'pasienId'      => 'required|exists:pasien,id',
            'dokterId'      => 'required|exists:dokter,id',
            'kamarId'       => 'required|exists:kamar,id',
            'tanggalMasuk'  => 'required|date',
            'tanggalKeluar' => 'nullable|date|after_or_equal:tanggalMasuk',
            'keluhan'       => 'nullable|string',
            'status'        => 'required|in:dirawat,selesai,dirujuk,meninggal',
        ];
    }

    protected function messages(): array
    {
        return [
            'pasienId.required'            => 'Pasien wajib dipilih.',
            'pasienId.exists'              => 'Pasien tidak ditemukan.',
            'dokterId.required'            => 'Dokter penanggung jawab wajib dipilih.',
            'kamarId.required'             => 'Kamar rawat inap wajib dipilih.',
            'tanggalMasuk.required'        => 'Tanggal masuk wajib diisi.',
            'tanggalKeluar.after_or_equal' => 'Tanggal keluar harus setelah tanggal masuk.',
            'diagnosa.required'            => 'Diagnosa medis wajib diisi oleh dokter.',
        ];
    }

    public function updatingCari(): void         { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    // ─────────────────────────────────────────
    // BUKA FORM TAMBAH
    // ─────────────────────────────────────────
    public function bukaFormTambah(): void
    {
        if (!in_array($this->rolePengguna, ['petugas', 'admin'])) {
            session()->flash('error', 'Hanya petugas dan admin yang dapat mendaftarkan rawat inap baru.');
            return;
        }
        $this->resetForm();
        $this->tanggalMasuk = now()->format('Y-m-d');
        $this->tampilForm   = true;
        $this->modeEdit     = false;
        $this->modeDiagnosa = false;
    }

    // ─────────────────────────────────────────
    // SIMPAN RAWAT INAP BARU
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        $this->validate();

        $kamar = Kamar::findOrFail($this->kamarId);
        if (!$kamar->isTersedia()) {
            $this->addError('kamarId', 'Kamar ' . $kamar->nomor_kamar . ' tidak tersedia.');
            return;
        }

        $sedangDirawat = RawatInap::where('pasien_id', $this->pasienId)
                                   ->where('status', 'dirawat')
                                   ->exists();
        if ($sedangDirawat) {
            $this->addError('pasienId', 'Pasien ini sedang dalam rawat inap aktif di kamar lain!');
            return;
        }

        RawatInap::create([
            'pasien_id'     => $this->pasienId,
            'dokter_id'     => $this->dokterId,
            'kamar_id'      => $this->kamarId,
            'tanggal_masuk' => $this->tanggalMasuk,
            'keluhan'       => $this->keluhan ?: null,
            'status'        => 'dirawat',
        ]);

        $kamar->update(['status' => 'terisi']);

        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Pasien berhasil didaftarkan rawat inap!');
    }

    // ─────────────────────────────────────────
    // BUKA FORM DIAGNOSA (dokter)
    // ─────────────────────────────────────────
    public function bukaDiagnosa(int $id): void
    {
        $ri = RawatInap::findOrFail($id);

        $this->rawatInapId   = $ri->id;
        $this->diagnosa      = $ri->diagnosa ?? '';
        $this->catatanDokter = $ri->catatan_dokter ?? '';
        $this->tindakanMedis = $ri->tindakan_medis ?? '';
        $this->status        = $ri->status;

        $this->tampilForm   = true;
        $this->modeDiagnosa = true;
        $this->modeEdit     = false;
    }

    // ─────────────────────────────────────────
    // SIMPAN DIAGNOSA (dokter)
    // ─────────────────────────────────────────
    public function simpanDiagnosa(): void
    {
        $this->validate();

        $ri         = RawatInap::findOrFail($this->rawatInapId);
        $statusLama = $ri->status;

        $ri->update([
            'diagnosa'       => $this->diagnosa,
            'catatan_dokter' => $this->catatanDokter ?: null,
            'tindakan_medis' => $this->tindakanMedis ?: null,
            'status'         => $this->status,
        ]);

        if (in_array($this->status, ['selesai', 'dirujuk', 'meninggal'])) {
            $ri->kamar->update(['status' => 'tersedia']);

            if (!$ri->tanggal_keluar) {
                $ri->update(['tanggal_keluar' => now()->toDateString()]);
            }

            if ($this->status === 'selesai' && $statusLama !== 'selesai') {
                $ri->refresh();
                $this->buatTagihanOtomatis($ri);
            }
        }

        $this->tampilForm   = false;
        $this->modeDiagnosa = false;
        $this->resetForm();

        $pesanTagihan = ($this->status === 'selesai' && $statusLama !== 'selesai')
            ? ' Tagihan pasien telah dibuat otomatis di fitur Transaksi.'
            : '';

        session()->flash('pesan', '✅ Diagnosa dan catatan medis berhasil disimpan!' . $pesanTagihan);
    }

    // ─────────────────────────────────────────
    // BUKA FORM EDIT (petugas)
    // ─────────────────────────────────────────
    public function bukaEdit(int $id): void
    {
        if (!in_array($this->rolePengguna, ['petugas', 'admin'])) {
            session()->flash('error', 'Hanya petugas dan admin yang dapat mengubah data rawat inap.');
            return;
        }

        $ri = RawatInap::findOrFail($id);

        $this->rawatInapId   = $ri->id;
        $this->pasienId      = $ri->pasien_id;
        $this->dokterId      = $ri->dokter_id;
        $this->kamarId       = $ri->kamar_id;
        $this->tanggalMasuk  = $ri->tanggal_masuk->format('Y-m-d');
        $this->tanggalKeluar = $ri->tanggal_keluar?->format('Y-m-d') ?? '';
        $this->keluhan       = $ri->keluhan ?? '';
        $this->status        = $ri->status;

        $this->tampilForm   = true;
        $this->modeEdit     = true;
        $this->modeDiagnosa = false;
    }

    // ─────────────────────────────────────────
    // UPDATE RAWAT INAP (petugas)
    // ─────────────────────────────────────────
    public function update(): void
    {
        $this->validate();

        $ri          = RawatInap::findOrFail($this->rawatInapId);
        $kamarLamaId = $ri->kamar_id;
        $statusLama  = $ri->status;

        $ri->update([
            'dokter_id'      => $this->dokterId,
            'kamar_id'       => $this->kamarId,
            'tanggal_masuk'  => $this->tanggalMasuk,
            'tanggal_keluar' => $this->tanggalKeluar ?: null,
            'keluhan'        => $this->keluhan ?: null,
            'status'         => $this->status,
        ]);

        if ($kamarLamaId != $this->kamarId) {
            Kamar::find($kamarLamaId)?->update(['status' => 'tersedia']);
            Kamar::find($this->kamarId)?->update(['status' => 'terisi']);
        }

        if (in_array($this->status, ['selesai', 'dirujuk', 'meninggal'])) {
            Kamar::find($this->kamarId)?->update(['status' => 'tersedia']);

            if (!$ri->tanggal_keluar) {
                $ri->update(['tanggal_keluar' => now()->toDateString()]);
            }

            if ($this->status === 'selesai' && $statusLama !== 'selesai') {
                $ri->refresh();
                $this->buatTagihanOtomatis($ri);
            }
        }

        $this->tampilForm = false;
        $this->resetForm();

        $pesanTagihan = ($this->status === 'selesai' && $statusLama !== 'selesai')
            ? ' Tagihan pasien telah dibuat otomatis di fitur Transaksi.'
            : '';

        session()->flash('pesan', '✅ Data rawat inap berhasil diperbarui!' . $pesanTagihan);
    }

    // ─────────────────────────────────────────
    // HAPUS RAWAT INAP
    // ─────────────────────────────────────────
    public function hapus(int $id): void
    {
        if (!in_array($this->rolePengguna, ['petugas', 'admin'])) {
            session()->flash('error', 'Hanya petugas dan admin yang dapat menghapus data rawat inap.');
            return;
        }

        $ri = RawatInap::findOrFail($id);

        if ($ri->transaksi()->exists()) {
            session()->flash('error', '❌ Tidak bisa dihapus karena sudah ada tagihan transaksi!');
            return;
        }

        if ($ri->status === 'dirawat') {
            $ri->kamar->update(['status' => 'tersedia']);
        }

        $ri->delete();
        session()->flash('pesan', '✅ Data rawat inap berhasil dihapus.');
    }

    // ─────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────
    public function resetForm(): void
    {
        $this->reset([
            'rawatInapId', 'pasienId', 'dokterId', 'kamarId',
            'tanggalMasuk', 'tanggalKeluar', 'keluhan',
            'diagnosa', 'catatanDokter', 'tindakanMedis',
        ]);
        $this->status       = 'dirawat';
        $this->modeDiagnosa = false;
        $this->resetErrorBag();
    }

    // ─────────────────────────────────────────
    // HELPER: BUAT TAGIHAN OTOMATIS
    // Dipanggil saat status berubah menjadi "selesai"
    // ─────────────────────────────────────────
    private function buatTagihanOtomatis(RawatInap $ri): void
    {
        $tanggalKeluar = $ri->tanggal_keluar ?? now();
        $jumlahHari    = max(1, (int) $ri->tanggal_masuk->diffInDays($tanggalKeluar));
        $hargaMalam    = $ri->kamar->harga_malam ?? 0;
        $biayaKamar    = $hargaMalam * $jumlahHari;
        $biayaDokter   = $ri->dokter->tarif ?? 0;
        $totalBiaya    = $biayaKamar + $biayaDokter;
        $nomorInvoice  = 'INV-' . now()->format('Ymd') . '-' . str_pad($ri->id, 4, '0', STR_PAD_LEFT);

        // Cek duplikat — jika sudah ada, update total saja
        $ada = Transaksi::where('rawat_inap_id', $ri->id)->first();

        if ($ada) {
            $ada->update([
                'biaya_kamar'  => $biayaKamar,
                'biaya_dokter' => $biayaDokter,
                'total_biaya'  => $totalBiaya,
            ]);
            return;
        }

        Transaksi::create([
            'rawat_inap_id' => $ri->id,
            'nomor_invoice'  => $nomorInvoice,
            'biaya_kamar'    => $biayaKamar,
            'biaya_dokter'   => $biayaDokter,
            'biaya_lain'     => 0,
            'total_biaya'    => $totalBiaya,
            'cara_bayar'     => 'tunai',
            'status_bayar'   => 'belum_bayar',
            'tanggal_bayar'  => null,
            'catatan'        => 'Tagihan dibuat otomatis saat pasien dinyatakan selesai rawat inap.',
        ]);
    }

    // ─────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────
    public function render()
    {
        $query = RawatInap::with(['pasien.pengguna', 'dokter.pengguna', 'kamar']);

        if ($this->rolePengguna === 'dokter') {
            $dokterId = auth()->user()->dokter?->id;
            $query->where('dokter_id', $dokterId);
        }

        $rawatInap = $query
            ->when($this->cari, fn ($q) =>
                $q->whereHas('pasien.pengguna', fn ($q2) =>
                    $q2->where('nama', 'like', '%' . $this->cari . '%')
                )
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        $daftarPasien = Pasien::with('pengguna')->get();
        $daftarDokter = Dokter::with('pengguna')->tersedia()->get();
        $daftarKamar  = Kamar::tersedia()->get();

        return view('livewire.rawat-inap-component',
            compact('rawatInap', 'daftarPasien', 'daftarDokter', 'daftarKamar')
        );
    }
}