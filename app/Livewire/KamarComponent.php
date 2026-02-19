<?php
 
namespace App\Livewire;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kamar;
 
class KamarComponent extends Component
{
    use WithPagination;
 
    // ─────────────────────────────────────────
    // PROPERTI FORM
    // ─────────────────────────────────────────
    public ?int   $kamarId    = null;
    public string $nomorKamar = '';
    public string $tipeKamar  = 'Kelas 3';
    public int    $kapasitas  = 1;
    public string $hargaMalam = '';
    public string $fasilitas  = '';
    public string $status     = 'tersedia';
 
    // Properti UI
    public bool   $tampilForm    = false;
    public bool   $modeEdit      = false;
    public string $cari          = '';
    public string $filterStatus  = '';
    public string $filterTipe    = '';
 
    protected $queryString = [
        'cari'         => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTipe'   => ['except' => ''],
    ];
 
    // ─────────────────────────────────────────
    // VALIDASI
    // ─────────────────────────────────────────
    protected function rules(): array
    {
        $unikNomor = 'unique:kamars,nomor_kamar'
            . ($this->kamarId ? ','.$this->kamarId : '');
 
        return [
            'nomorKamar' => 'required|string|max:10|'.$unikNomor,
            'tipeKamar'  => 'required|in:VIP,Kelas 1,Kelas 2,Kelas 3',
            'kapasitas'  => 'required|integer|min:1|max:20',
            'hargaMalam' => 'required|numeric|min:0',
            'fasilitas'  => 'nullable|string|max:500',
            'status'     => 'required|in:tersedia,terisi,maintenance',
        ];
    }
 
    protected function messages(): array
    {
        return [
            'nomorKamar.required' => 'Nomor kamar wajib diisi.',
            'nomorKamar.unique'   => 'Nomor kamar sudah digunakan.',
            'tipeKamar.required'  => 'Tipe kamar wajib dipilih.',
            'hargaMalam.required' => 'Harga per malam wajib diisi.',
            'hargaMalam.numeric'  => 'Harga harus berupa angka.',
            'kapasitas.min'       => 'Kapasitas minimal 1 tempat tidur.',
        ];
    }
 
    // ─────────────────────────────────────────
    // MOUNT — cek role
    // ─────────────────────────────────────────
    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            abort(403, 'Fitur manajemen kamar hanya tersedia untuk Admin dan Petugas.');
        }
    }
 
    public function updatingCari(): void        { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterTipe(): void   { $this->resetPage(); }
 
    public function bukaFormTambah(): void
    {
        $this->resetForm();
        $this->tampilForm = true;
        $this->modeEdit   = false;
    }
 
    // ─────────────────────────────────────────
    // SIMPAN DATA KAMAR BARU
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        $this->validate();
 
        Kamar::create([
            'nomor_kamar' => strtoupper($this->nomorKamar),
            'tipe_kamar'  => $this->tipeKamar,
            'kapasitas'   => $this->kapasitas,
            'harga_malam' => $this->hargaMalam,
            'fasilitas'   => $this->fasilitas ?: null,
            'status'      => $this->status,
        ]);
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Data kamar berhasil ditambahkan!');
    }
 
    public function bukaEdit(int $id): void
    {
        $kamar = Kamar::findOrFail($id);
        $this->kamarId    = $kamar->id;
        $this->nomorKamar = $kamar->nomor_kamar;
        $this->tipeKamar  = $kamar->tipe_kamar;
        $this->kapasitas  = $kamar->kapasitas;
        $this->hargaMalam = $kamar->harga_malam;
        $this->fasilitas  = $kamar->fasilitas ?? '';
        $this->status     = $kamar->status;
        $this->tampilForm = true;
        $this->modeEdit   = true;
    }
 
    public function update(): void
    {
        $this->validate();
 
        Kamar::findOrFail($this->kamarId)->update([
            'nomor_kamar' => strtoupper($this->nomorKamar),
            'tipe_kamar'  => $this->tipeKamar,
            'kapasitas'   => $this->kapasitas,
            'harga_malam' => $this->hargaMalam,
            'fasilitas'   => $this->fasilitas ?: null,
            'status'      => $this->status,
        ]);
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Data kamar berhasil diperbarui!');
    }
 
    // ─────────────────────────────────────────
    // HAPUS KAMAR
    // ─────────────────────────────────────────
    public function hapus(int $id): void
    {
        $kamar = Kamar::withCount([
            'rawatInaps as aktif' => fn($q) => $q->where('status', 'dirawat')
        ])->findOrFail($id);
 
        // Kamar tidak bisa dihapus jika masih ada pasien aktif
        if ($kamar->aktif > 0) {
            session()->flash('error', '❌ Kamar tidak dapat dihapus karena masih ada pasien!');
            return;
        }
 
        $kamar->delete();
        session()->flash('pesan', '✅ Data kamar berhasil dihapus.');
    }
 
    // ─────────────────────────────────────────
    // UBAH STATUS KAMAR SECARA CEPAT
    // (tanpa membuka form edit penuh)
    // ─────────────────────────────────────────
    public function ubahStatus(int $id, string $statusBaru): void
    {
        $kamar = Kamar::findOrFail($id);
 
        // Tidak bisa ubah ke "tersedia" jika masih ada pasien aktif di kamar ini
        if ($statusBaru === 'tersedia'
            && $kamar->rawatInaps()->where('status', 'dirawat')->exists())
        {
            session()->flash('error', '❌ Kamar masih terisi pasien, tidak bisa diubah ke tersedia!');
            return;
        }
 
        $kamar->update(['status' => $statusBaru]);
        session()->flash('pesan', '✅ Status kamar diubah menjadi: '.ucfirst($statusBaru));
    }
 
    public function resetForm(): void
    {
        $this->reset(['kamarId', 'nomorKamar', 'hargaMalam', 'fasilitas']);
        $this->tipeKamar = 'Kelas 3';
        $this->kapasitas = 1;
        $this->status    = 'tersedia';
        $this->resetErrorBag();
    }
 
    public function render()
    {
        $kamar = Kamar::query()
            ->when($this->cari,         fn($q) => $q->where('nomor_kamar', 'like', '%'.$this->cari.'%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterTipe,   fn($q) => $q->where('tipe_kamar', $this->filterTipe))
            ->orderBy('nomor_kamar')
            ->paginate(12);
 
        // Hitung ringkasan statistik kamar
        $statistikKamar = [
            'tersedia'    => Kamar::where('status', 'tersedia')->count(),
            'terisi'      => Kamar::where('status', 'terisi')->count(),
            'maintenance' => Kamar::where('status', 'maintenance')->count(),
        ];
 
        return view('livewire.kamar-component',
            compact('kamar', 'statistikKamar')
        );
    }
}
