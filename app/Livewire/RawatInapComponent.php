<?php
 
namespace App\Livewire;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{RawatInap, Pasien, Dokter, Kamar};
 
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
    public string $diagnosa      = '';   // Hanya diisi dokter
    public string $catatanDokter = '';   // Hanya diisi dokter
    public string $tindakanMedis = '';   // Hanya diisi dokter
    public string $status        = 'dirawat';
 
    // Properti UI
    public bool   $tampilForm       = false;
    public bool   $modeEdit         = false;
    public bool   $modeDiagnosa     = false;  // Form khusus input diagnosa dokter
    public string $cari             = '';
    public string $filterStatus     = '';
 
    // Role pengguna yang login (disimpan agar cepat diakses di view)
    public string $rolePengguna = '';
 
    // ─────────────────────────────────────────
    // MOUNT — cek role & simpan role ke properti
    // ─────────────────────────────────────────
    public function mount(): void
    {
        $role = auth()->user()->role;
 
        // Hanya petugas dan dokter yang boleh akses komponen ini
        if (!in_array($role, ['petugas', 'dokter'])) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Petugas dan Dokter.');
        }
 
        // Simpan role untuk digunakan di view (tampilkan/sembunyikan tombol)
        $this->rolePengguna = $role;
    }
 
    // ─────────────────────────────────────────
    // VALIDASI (berbeda untuk petugas vs dokter)
    // ─────────────────────────────────────────
    protected function rules(): array
    {
        // Mode diagnosa: hanya validasi field medis
        if ($this->modeDiagnosa) {
            return [
                'diagnosa'      => 'required|string',
                'catatanDokter' => 'nullable|string',
                'tindakanMedis' => 'nullable|string',
                'status'        => 'required|in:dirawat,selesai,dirujuk,meninggal',
            ];
        }
 
        // Mode CRUD penuh (petugas)
        return [
            'pasienId'      => 'required|exists:pasiens,id',
            'dokterId'      => 'required|exists:dokters,id',
            'kamarId'       => 'required|exists:kamars,id',
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
 
    public function updatingCari(): void        { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
 
    public function bukaFormTambah(): void
    {
        // Hanya petugas yang bisa tambah rawat inap baru
        if ($this->rolePengguna !== 'petugas') {
            session()->flash('error', 'Hanya petugas yang dapat mendaftarkan rawat inap baru.');
            return;
        }
        $this->resetForm();
        $this->tanggalMasuk = now()->format('Y-m-d');
        $this->tampilForm   = true;
        $this->modeEdit     = false;
        $this->modeDiagnosa = false;
    }
 
    // ─────────────────────────────────────────
    // SIMPAN RAWAT INAP BARU (khusus petugas)
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        $this->validate();
 
        // Cek ketersediaan kamar yang dipilih
        $kamar = Kamar::findOrFail($this->kamarId);
        if (!$kamar->isTersedia()) {
            $this->addError("kamarId", "Kamar " . $kamar->nomor_kamar . " tidak tersedia.");
            return;
        }
 
        // Cek apakah pasien sudah aktif dirawat di tempat lain
        $sedangDirawat = RawatInap::where('pasien_id', $this->pasienId)
                                   ->where('status', 'dirawat')
                                   ->exists();
        if ($sedangDirawat) {
            $this->addError('pasienId', 'Pasien ini sedang dalam rawat inap aktif di kamar lain!');
            return;
        }
 
        // Buat data rawat inap
        RawatInap::create([
            'pasien_id'     => $this->pasienId,
            'dokter_id'     => $this->dokterId,
            'kamar_id'      => $this->kamarId,
            'tanggal_masuk' => $this->tanggalMasuk,
            'keluhan'       => $this->keluhan ?: null,
            'status'        => 'dirawat',
        ]);
 
        // Tandai kamar sebagai terisi
        $kamar->update(['status' => 'terisi']);
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Pasien berhasil didaftarkan rawat inap!');
    }
 
    // ─────────────────────────────────────────
    // BUKA FORM INPUT DIAGNOSA (khusus dokter)
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
    // SIMPAN DIAGNOSA (khusus dokter)
    // ─────────────────────────────────────────
    public function simpanDiagnosa(): void
    {
        $this->validate();
 
        $ri = RawatInap::findOrFail($this->rawatInapId);
        $ri->update([
            'diagnosa'       => $this->diagnosa,
            'catatan_dokter' => $this->catatanDokter ?: null,
            'tindakan_medis' => $this->tindakanMedis ?: null,
            'status'         => $this->status,
        ]);
 
        // Jika dokter menandai selesai, bebaskan kamar
        if (in_array($this->status, ['selesai', 'dirujuk', 'meninggal'])) {
            $ri->kamar->update(['status' => 'tersedia']);
            if (!$ri->tanggal_keluar) {
                $ri->update(['tanggal_keluar' => now()->toDateString()]);
            }
        }
 
        $this->tampilForm   = false;
        $this->modeDiagnosa = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Diagnosa dan catatan medis berhasil disimpan!');
    }
 
    public function bukaEdit(int $id): void
    {
        if ($this->rolePengguna !== 'petugas') {
            session()->flash('error', 'Hanya petugas yang dapat mengubah data rawat inap.');
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
        $this->tampilForm    = true;
        $this->modeEdit      = true;
        $this->modeDiagnosa  = false;
    }
 
    public function update(): void
    {
        $this->validate();
        $ri = RawatInap::findOrFail($this->rawatInapId);
        $kamarLamaId = $ri->kamar_id;
 
        $ri->update([
            'dokter_id'      => $this->dokterId,
            'kamar_id'       => $this->kamarId,
            'tanggal_masuk'  => $this->tanggalMasuk,
            'tanggal_keluar' => $this->tanggalKeluar ?: null,
            'keluhan'        => $this->keluhan ?: null,
            'status'         => $this->status,
        ]);
 
        // Jika kamar berubah, bebaskan kamar lama & tandai kamar baru terisi
        if ($kamarLamaId != $this->kamarId) {
            Kamar::find($kamarLamaId)?->update(['status' => 'tersedia']);
            Kamar::find($this->kamarId)?->update(['status' => 'terisi']);
        }
 
        // Jika status berubah ke selesai, bebaskan kamar
        if (in_array($this->status, ['selesai', 'dirujuk', 'meninggal'])) {
            Kamar::find($this->kamarId)?->update(['status' => 'tersedia']);
        }
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Data rawat inap berhasil diperbarui!');
    }
 
    public function hapus(int $id): void
    {
        if ($this->rolePengguna !== 'petugas') {
            session()->flash('error', 'Hanya petugas yang dapat menghapus data rawat inap.');
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
 
    public function resetForm(): void
    {
        $this->reset([
            'rawatInapId','pasienId','dokterId','kamarId',
            'tanggalMasuk','tanggalKeluar','keluhan',
            'diagnosa','catatanDokter','tindakanMedis',
        ]);
        $this->status       = 'dirawat';
        $this->modeDiagnosa = false;
        $this->resetErrorBag();
    }
 
    public function render()
    {
        // Jika dokter, hanya tampilkan rawat inap pasiennya
        $query = RawatInap::with(['pasien.pengguna', 'dokter.pengguna', 'kamar']);
 
        if ($this->rolePengguna === 'dokter') {
            // Dokter hanya melihat pasien yang ditanganinya
            $dokterId = auth()->user()->dokter?->id;
            $query->where('dokter_id', $dokterId);
        }
 
        $rawatInap= $query
            ->when($this->cari, fn($q) =>
                $q->whereHas('pasien.pengguna', fn($q2) =>
                    $q2->where('nama', 'like', '%'.$this->cari.'%')
                )
            )
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
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
