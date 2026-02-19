<?php
 
namespace App\Livewire;
 
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pasien;
use App\Models\Pengguna;
 
class PasienComponent extends Component
{
    use WithPagination;
 
    // ─────────────────────────────────────────
    // PROPERTI FORM
    // ─────────────────────────────────────────
    public ?int   $pasienId            = null;
    public string $nama                = '';
    public string $email               = '';
    public string $password            = '';
    public string $nik                 = '';
    public string $jenisKelamin        = 'L';
    public string $tanggalLahir        = '';
    public string $golonganDarah       = '';
    public string $alamat              = '';
    public string $noTelepon           = '';
    public string $namaPenjamin        = '';
    public string $noTeleponPenjamin   = '';
 
    // ─────────────────────────────────────────
    // PROPERTI UI
    // ─────────────────────────────────────────
    public bool   $tampilForm = false;
    public bool   $modeEdit   = false;
    public string $cari       = '';
    public string $filterJK   = '';
 
    protected $queryString = [
        'cari'     => ['except' => ''],
        'filterJK' => ['except' => ''],
    ];
 
    // ─────────────────────────────────────────
    // ATURAN VALIDASI
    // ─────────────────────────────────────────
    protected function rules(): array
    {
        // Validasi unik email & NIK dikecualikan untuk data yang sedang diedit
        $penggunaId = $this->pasienId
            ? optional(Pasien::find($this->pasienId)?->pengguna)->id
            : null;
 
        return [
            'nama'          => 'required|string|max:100',
            'email'         => 'required|email|unique:penggunas,email'.($penggunaId ? ','.$penggunaId : ''),
            'password'      => $this->modeEdit ? 'nullable|min:8' : 'required|min:8',
            'nik'           => 'nullable|digits:16|unique:pasiens,nik'.($this->pasienId ? ','.$this->pasienId : ''),
            'jenisKelamin'  => 'required|in:L,P',
            'tanggalLahir'  => 'nullable|date|before:today',
            'golonganDarah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,A,B,AB,O',
            'alamat'        => 'nullable|string',
            'noTelepon'     => 'nullable|digits_between:9,15',
        ];
    }
 
    // Pesan error validasi dalam Bahasa Indonesia
    protected function messages(): array
    {
        return [
            'nama.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan oleh akun lain.',
            'password.required'     => 'Password wajib diisi untuk data baru.',
            'password.min'          => 'Password minimal 8 karakter.',
            'nik.digits'            => 'NIK harus tepat 16 digit angka.',
            'nik.unique'            => 'NIK sudah terdaftar untuk pasien lain.',
            'jenisKelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggalLahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'noTelepon.digits_between' => 'Nomor telepon harus 9–15 angka.',
        ];
    }
 
    // ─────────────────────────────────────────
    // MOUNT — cek role saat komponen dimuat
    // ─────────────────────────────────────────
    public function mount(): void
    {
        // Hanya admin dan petugas yang boleh mengakses komponen ini
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Admin dan Petugas.');
        }
    }
 
    // ─────────────────────────────────────────
    // RESET HALAMAN SAAT FILTER BERUBAH
    // ─────────────────────────────────────────
    public function updatingCari(): void    { $this->resetPage(); }
    public function updatingFilterJK(): void { $this->resetPage(); }
 
    // ─────────────────────────────────────────
    // BUKA FORM TAMBAH DATA BARU
    // ─────────────────────────────────────────
    public function bukaFormTambah(): void
    {
        $this->resetForm();
        $this->tampilForm = true;
        $this->modeEdit   = false;
    }
 
    // ─────────────────────────────────────────
    // SIMPAN DATA PASIEN BARU
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        $this->validate();
 
        // Buat akun pengguna dengan role pasien
        $pengguna = Pengguna::create([
            'nama'     => $this->nama,
            'email'    => $this->email,
            'password' => bcrypt($this->password),
            'role'     => 'pasien',
        ]);
 
        // Buat profil pasien yang terhubung ke akun
        Pasien::create([
            'pengguna_id'          => $pengguna->id,
            'nik'                  => $this->nik ?: null,
            'jenis_kelamin'        => $this->jenisKelamin,
            'tanggal_lahir'        => $this->tanggalLahir ?: null,
            'golongan_darah'       => $this->golonganDarah ?: null,
            'alamat'               => $this->alamat ?: null,
            'no_telepon'           => $this->noTelepon ?: null,
            'nama_penjamin'        => $this->namaPenjamin ?: null,
            'no_telepon_penjamin'  => $this->noTeleponPenjamin ?: null,
        ]);
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Data pasien berhasil ditambahkan!');
    }
 
    // ─────────────────────────────────────────
    // BUKA FORM EDIT — isi dengan data yang ada
    // ─────────────────────────────────────────
    public function bukaEdit(int $id): void
    {
        $pasien = Pasien::with('pengguna')->findOrFail($id);
 
        $this->pasienId            = $pasien->id;
        $this->nama                = $pasien->pengguna->nama;
        $this->email               = $pasien->pengguna->email;
        $this->nik                 = $pasien->nik ?? '';
        $this->jenisKelamin        = $pasien->jenis_kelamin;
        $this->tanggalLahir        = $pasien->tanggal_lahir?->format('Y-m-d') ?? '';
        $this->golonganDarah       = $pasien->golongan_darah ?? '';
        $this->alamat              = $pasien->alamat ?? '';
        $this->noTelepon           = $pasien->no_telepon ?? '';
        $this->namaPenjamin        = $pasien->nama_penjamin ?? '';
        $this->noTeleponPenjamin   = $pasien->no_telepon_penjamin ?? '';
 
        $this->tampilForm = true;
        $this->modeEdit   = true;
    }
 
    // ─────────────────────────────────────────
    // UPDATE — simpan perubahan data
    // ─────────────────────────────────────────
    public function update(): void
    {
        $this->validate();
 
        $pasien = Pasien::with('pengguna')->findOrFail($this->pasienId);
 
        // Update data akun login
        $updateAkun = ['nama' => $this->nama, 'email' => $this->email];
        if ($this->password) {
            $updateAkun['password'] = bcrypt($this->password);
        }
        $pasien->pengguna->update($updateAkun);
 
        // Update profil pasien
        $pasien->update([
            'nik'                  => $this->nik ?: null,
            'jenis_kelamin'        => $this->jenisKelamin,
            'tanggal_lahir'        => $this->tanggalLahir ?: null,
            'golongan_darah'       => $this->golonganDarah ?: null,
            'alamat'               => $this->alamat ?: null,
            'no_telepon'           => $this->noTelepon ?: null,
            'nama_penjamin'        => $this->namaPenjamin ?: null,
            'no_telepon_penjamin'  => $this->noTeleponPenjamin ?: null,
        ]);
 
        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('pesan', '✅ Data pasien berhasil diperbarui!');
    }
 
    // ─────────────────────────────────────────
    // HAPUS DATA PASIEN
    // ─────────────────────────────────────────
    public function hapus(int $id): void
    {
        $pasien = Pasien::with(['pengguna', 'rawatInaps'])->findOrFail($id);
 
        // Cegah penghapusan jika pasien masih aktif dirawat
        if ($pasien->rawatInaps()->where('status', 'dirawat')->exists()) {
            session()->flash('error', '❌ Tidak bisa dihapus! Pasien masih dalam perawatan.');
            return;
        }
 
        // Hapus akun pengguna — profil pasien ikut terhapus (cascade)
        $pasien->pengguna->delete();
        session()->flash('pesan', '✅ Data pasien berhasil dihapus.');
    }
 
    // ─────────────────────────────────────────
    // RESET SEMUA PROPERTI FORM
    // ─────────────────────────────────────────
    public function resetForm(): void
    {
        $this->reset([
            'pasienId', 'nama', 'email', 'password', 'nik',
            'jenisKelamin', 'tanggalLahir', 'golonganDarah',
            'alamat', 'noTelepon', 'namaPenjamin', 'noTeleponPenjamin',
        ]);
        $this->jenisKelamin = 'L'; // kembalikan ke nilai default
        $this->resetErrorBag();
    }
 
    // ─────────────────────────────────────────
    // RENDER — ambil data dan tampilkan view
    // ─────────────────────────────────────────
    public function render()
    {
        $pasien = Pasien::with('pengguna')
            ->whereHas('pengguna', fn($q) =>
                $q->where('nama',  'like', '%'.$this->cari.'%')
                  ->orWhere('email','like', '%'.$this->cari.'%')
            )
            ->when($this->filterJK, fn($q) => $q->where('jenis_kelamin', $this->filterJK))
            ->latest()
            ->paginate(10);
 
        return view('livewire.pasien-component', compact('pasien'));
    }
}
