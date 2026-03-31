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
    // PROPERTI FORM PASIEN
    // ─────────────────────────────────────────

    public ?int $pasienId = null;
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $nik = '';
    public string $jenisKelamin = 'L';
    public string $tanggalLahir = '';
    public string $golonganDarah = '';
    public string $alamat = '';
    public string $noTelepon = '';
    public string $namaPenjamin = '';
    public string $noTeleponPenjamin = '';

    // ─────────────────────────────────────────
    // PROPERTI UI (STATE)
    // ─────────────────────────────────────────
    public bool $tampilForm = false;
    public bool $modeEdit = false;
    public string $cari = '';
    public string $filterJK = '';
    protected $paginationTheme = 'tailwind';
    // Modal konfirmasi hapus
    public bool $tampilKonfirmasiHapus = false;
    public ?int $hapusId = null;
    public string $namaHapus = '';

    protected $queryString = [
        'cari' => ['except' => ''],
        'filterJK' => ['except' => ''],
    ];

    // ─────────────────────────────────────────
    // VALIDASI
    // ─────────────────────────────────────────
    protected function rules(): array
    {
        $penggunaId = $this->pasienId
            ? optional(Pasien::find($this->pasienId)?->pengguna)->id
            : null;

        return [
            'nama' => 'required|string|max:100',
           'email' => 'required|email|unique:pengguna,email' . ($penggunaId ? ',' . $penggunaId . ',id' : ''),
            'password' => $this->modeEdit ? 'nullable|min:8' : 'required|min:8',
           'nik' => 'nullable|digits:16|unique:pasien,nik' . ($this->pasienId ? ',' . $this->pasienId . ',id' : ''),
            'jenisKelamin' => 'required|in:L,P',
            'tanggalLahir' => 'nullable|date|before:today',
            'golonganDarah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,A,B,AB,O',
            'alamat' => 'nullable|string',
            'noTelepon' => 'nullable|digits_between:9,15',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi untuk data baru.',
            'password.min' => 'Password minimal 8 karakter.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar untuk pasien lain.',
            'jenisKelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggalLahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'noTelepon.digits_between' => 'Nomor telepon harus 9–15 angka.',
        ];
    }

    // ─────────────────────────────────────────
    // AKSES KOMPONEN
    // ─────────────────────────────────────────
    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Admin dan Petugas.');
        }
    }

    // ─────────────────────────────────────────
    // FILTER DAN PAGINATION
    // ─────────────────────────────────────────
    public function updatingCari(): void { $this->resetPage(); }
    public function updatingFilterJK(): void { $this->resetPage(); }

    // ─────────────────────────────────────────
    // FORM TAMBAH / EDIT
    // ─────────────────────────────────────────
    public function bukaFormTambah(): void
    {
        $this->resetForm();
        $this->tampilForm = true;
        $this->modeEdit = false;
    }

    public function bukaEdit(int $id): void
    {
        $pasien = Pasien::with('pengguna')->findOrFail($id);

        $this->pasienId = $pasien->id;
        $this->nama = $pasien->pengguna->nama;
        $this->email = $pasien->pengguna->email;
        $this->nik = $pasien->nik ?? '';
        $this->jenisKelamin = $pasien->jenis_kelamin;
        $this->tanggalLahir = $pasien->tanggal_lahir?->format('Y-m-d') ?? '';
        $this->golonganDarah = $pasien->golongan_darah ?? '';
        $this->alamat = $pasien->alamat ?? '';
        $this->noTelepon = $pasien->no_telepon ?? '';
        $this->namaPenjamin = $pasien->nama_penjamin ?? '';
        $this->noTeleponPenjamin = $pasien->no_telepon_penjamin ?? '';

        $this->tampilForm = true;
        $this->modeEdit = true;
    }

    // ─────────────────────────────────────────
    // SIMPAN & UPDATE DATA
    // ─────────────────────────────────────────
    public function simpan(): void
    {
        $this->validate();

        $pengguna = Pengguna::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => 'pasien',
        ]);

        Pasien::create([
            'pengguna_id' => $pengguna->id,
            'nik' => $this->nik ?: null,
            'jenis_kelamin' => $this->jenisKelamin,
            'tanggal_lahir' => $this->tanggalLahir ?: null,
            'golongan_darah' => $this->golonganDarah ?: null,
            'alamat' => $this->alamat ?: null,
            'no_telepon' => $this->noTelepon ?: null,
            'nama_penjamin' => $this->namaPenjamin ?: null,
            'no_telepon_penjamin' => $this->noTeleponPenjamin ?: null,
        ]);

        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('sukses', '✅ Data pasien berhasil ditambahkan!');
    }

    public function update(): void
    {
        $this->validate();

        $pasien = Pasien::with('pengguna')->findOrFail($this->pasienId);

        $updateAkun = ['nama' => $this->nama, 'email' => $this->email];
        if ($this->password) {
            $updateAkun['password'] = bcrypt($this->password);
        }
        $pasien->pengguna->update($updateAkun);

        $pasien->update([
            'nik' => $this->nik ?: null,
            'jenis_kelamin' => $this->jenisKelamin,
            'tanggal_lahir' => $this->tanggalLahir ?: null,
            'golongan_darah' => $this->golonganDarah ?: null,
            'alamat' => $this->alamat ?: null,
            'no_telepon' => $this->noTelepon ?: null,
            'nama_penjamin' => $this->namaPenjamin ?: null,
            'no_telepon_penjamin' => $this->noTeleponPenjamin ?: null,
        ]);

        $this->tampilForm = false;
        $this->resetForm();
        session()->flash('sukses', '✅ Data pasien berhasil diperbarui!');
    }

    // ─────────────────────────────────────────
    // KONFIRMASI & HAPUS DATA PASIEN
    // ─────────────────────────────────────────
    public function konfirmasiHapus(int $id): void
    {
        $pasien = Pasien::with('pengguna')->findOrFail($id);
        $this->hapusId = $id;
        $this->namaHapus = $pasien->pengguna->nama ?? 'Pasien';
        $this->tampilKonfirmasiHapus = true;
    }

    public function batalHapus(): void
    {
        $this->hapusId = null;
        $this->namaHapus = '';
        $this->tampilKonfirmasiHapus = false;
    }

    public function hapus(): void
    {
        if (!$this->hapusId) return;

        $pasien = Pasien::with(['pengguna', 'rawatInaps'])->findOrFail($this->hapusId);

        // Cegah hapus jika masih ada pasien aktif dirawat
    if ($pasien->rawatInaps()->where('status', 'dirawat')->exists()) {
        session()->flash('error', 'Tidak bisa dihapus! Pasien masih dalam perawatan.');
        $this->batalHapus();
        return;
    }

    // Hapus riwayat rawat inap beserta transaksinya dulu
    $pasien->rawatInaps()->each(function ($ri) {
        $ri->transaksi()->delete(); // hapus transaksi jika ada
        $ri->delete();
    });

    // Hapus akun pengguna (akan ikut menghapus pasien via cascade atau manual)
    if ($pasien->pengguna) {
        $pasien->pengguna->delete();
    } else {
        $pasien->delete();
    }
        $this->batalHapus();
        session()->flash('sukses', '✅ Data pasien berhasil dihapus.');
        $this->resetPage();
    }

    // ─────────────────────────────────────────
    // RESET FORM
    // ─────────────────────────────────────────
    public function resetForm(): void
    {
        $this->reset([
            'pasienId', 'nama', 'email', 'password', 'nik',
            'jenisKelamin', 'tanggalLahir', 'golonganDarah',
            'alamat', 'noTelepon', 'namaPenjamin', 'noTeleponPenjamin'
        ]);
        $this->jenisKelamin = 'L';
        $this->resetErrorBag();
    }

    // ─────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────
    public function render()
    {
        $pasien = Pasien::with('pengguna')
            ->whereHas('pengguna', fn($q) =>
                $q->where('nama', 'like', '%' . $this->cari . '%')
                  ->orWhere('email', 'like', '%' . $this->cari . '%')
            )
            ->when($this->filterJK, fn($q) => $q->where('jenis_kelamin', $this->filterJK))
            ->latest()
            ->paginate(10);

        return view('livewire.pasien-component', compact('pasien'));
    }
}