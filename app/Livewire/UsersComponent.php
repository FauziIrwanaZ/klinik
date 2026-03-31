<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersComponent extends Component
{
    use WithPagination;

    // ─────────────────────────────────────────────────────────────────
    // PROPERTI FORM
    // ─────────────────────────────────────────────────────────────────

    public ?int   $penggunaId = null;
    public string $nama       = '';
    public string $email      = '';
    public string $password   = '';
    public string $role       = 'pasien';
    public bool   $aktif      = true;

    // ─────────────────────────────────────────────────────────────────
    // PROPERTI UI
    // ─────────────────────────────────────────────────────────────────

    public bool   $tampilForm      = false;
    public bool   $modeEdit        = false;
    public bool   $tampilPassword  = false;
    public bool   $TampilkonfirmasiHapus = false;
    public ?int   $idHapus         = null;
    public string $namaHapus       = '';

    // ─────────────────────────────────────────────────────────────────
    // PROPERTI FILTER
    // ─────────────────────────────────────────────────────────────────

    public string $cari       = '';
    public string $filterRole = '';

    protected array $queryString = [
        'cari'       => ['except' => ''],
        'filterRole' => ['except' => ''],
    ];

    // ─────────────────────────────────────────────────────────────────
    // MOUNT — hanya admin
    // ─────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Administrator.');
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // RESET PAGINATION SAAT FILTER BERUBAH
    // ─────────────────────────────────────────────────────────────────

    public function updatingCari(): void       { $this->resetPage(); }
    public function updatingFilterRole(): void { $this->resetPage(); }

    // ─────────────────────────────────────────────────────────────────
    // VALIDASI
    // ─────────────────────────────────────────────────────────────────

    protected function rules(): array
    {
        return [
            'nama'     => 'required|string|max:100',
            'email'    => [
                'required', 'email', 'max:100',
                Rule::unique('pengguna', 'email')->ignore($this->penggunaId),
            ],
            'password' => $this->modeEdit
                ? 'nullable|string|min:6'
                : 'required|string|min:6',
            'role'     => 'required|in:admin,petugas,dokter,pasien',
            'aktif'    => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan pengguna lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Role wajib dipilih.',
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    // BUKA FORM TAMBAH
    // ─────────────────────────────────────────────────────────────────

    public function bukaFormTambah(): void
    {
        $this->resetForm();
        $this->tampilForm = true;
        $this->modeEdit   = false;
    }

    // ─────────────────────────────────────────────────────────────────
    // SIMPAN (CREATE)
    // ─────────────────────────────────────────────────────────────────

    public function simpan(): void
    {
        $this->validate();

        Pengguna::create([
            'nama'     => $this->nama,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
            'aktif'    => $this->aktif,
        ]);

        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', 'Pengguna baru berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────────────
    // BUKA FORM EDIT
    // ─────────────────────────────────────────────────────────────────

    public function bukaEdit(int $id): void
    {
        $p = Pengguna::findOrFail($id);

        $this->penggunaId = $p->id;
        $this->nama       = $p->nama;
        $this->email      = $p->email;
        $this->password   = '';
        $this->role       = $p->role;
        $this->aktif      = $p->aktif;

        $this->tampilForm   = true;
        $this->modeEdit     = true;
        $this->tampilPassword = false;
    }

    // ─────────────────────────────────────────────────────────────────
    // UPDATE (EDIT)
    // ─────────────────────────────────────────────────────────────────

    public function update(): void
    {
        $this->validate();

        $p    = Pengguna::findOrFail($this->penggunaId);
        $data = [
            'nama'  => $this->nama,
            'email' => $this->email,
            'role'  => $this->role,
            'aktif' => $this->aktif,
        ];

        // Password hanya diubah jika diisi
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $p->update($data);

        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', 'Data pengguna berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────────
    // KONFIRMASI & HAPUS
    // ─────────────────────────────────────────────────────────────────

    public function konfirmasiHapus(int $id): void
    {
        $p = Pengguna::findOrFail($id);

        if ($p->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }

    // Cegah hapus jika pasien punya riwayat rawat inap
    if ($p->pasien && $p->pasien->rawatInaps()->exists()) {
        session()->flash('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat rawat inap sebagai pasien.');
        return;
    }

    // Cegah hapus jika dokter punya riwayat rawat inap
    if ($p->dokter && $p->dokter->rawatInaps()->exists()) {
        session()->flash('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat rawat inap sebagai dokter.');
        return;
    }

        $this->idHapus         = $id;
        $this->namaHapus       = $p->nama;
        $this->TampilkonfirmasiHapus = true;
    }

    public function hapus(): void
    {
        if (!$this->idHapus) return;

        $p    = Pengguna::findOrFail($this->idHapus);
        $nama = $p->nama;

          // Hapus relasi pasien dulu jika ada (tanpa riwayat rawat inap)
    if ($p->pasien) {
        $p->pasien->delete();
    }

     // Hapus relasi dokter jika ada
    if ($p->dokter) {
        $p->dokter->delete();
    }

    $p->delete();


        $this->batalHapus();
        session()->flash('pesan', "Pengguna \"{$nama}\" berhasil dihapus.");
    }

    public function batalHapus(): void
    {
        $this->TampilkonfirmasiHapus = false;
        $this->idHapus         = null;
        $this->namaHapus       = '';
    }

    // ─────────────────────────────────────────────────────────────────
    // TOGGLE AKTIF / NONAKTIF
    // ─────────────────────────────────────────────────────────────────

    public function toggleAktif(int $id): void
    {
        $p = Pengguna::findOrFail($id);

        if ($p->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            return;
        }

        $statusBaru = !$p->aktif;
        $p->update(['aktif' => $statusBaru]);

        $label = $statusBaru ? 'Aktif' : 'Nonaktif';
        session()->flash('pesan', "Status \"{$p->nama}\" diubah menjadi {$label}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────────────────────────────

    public function resetForm(): void
    {
        $this->reset(['penggunaId', 'nama', 'email', 'password']);
        $this->role          = 'pasien';
        $this->aktif         = true;
        $this->tampilPassword = false;
        $this->resetErrorBag();
    }

    // ─────────────────────────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────────────────────────

    public function render()
    {
        $pengguna = Pengguna::when($this->cari, fn ($q) =>
                $q->where(fn ($q2) =>
                    $q2->where('nama',  'like', '%'.$this->cari.'%')
                       ->orWhere('email', 'like', '%'.$this->cari.'%')
                )
            )
            ->when($this->filterRole, fn ($q) => $q->where('role', $this->filterRole))
            ->latest()
            ->paginate(10);

        $statistik = [
            'total'    => Pengguna::count(),
            'aktif'    => Pengguna::where('aktif', true)->count(),
            'nonaktif' => Pengguna::where('aktif', false)->count(),
            'admin'    => Pengguna::where('role', 'admin')->count(),
            'petugas'  => Pengguna::where('role', 'petugas')->count(),
            'dokter'   => Pengguna::where('role', 'dokter')->count(),
            'pasien'   => Pengguna::where('role', 'pasien')->count(),
        ];

        return view('livewire.users-component', compact('pengguna', 'statistik'));
    }
}