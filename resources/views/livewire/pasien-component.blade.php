
<div>
  <div class="space-y-5">
 
    {{-- ════════════════════════════════════════ --}}
    {{-- FLASH MESSAGE — sukses & error           --}}
    {{-- ════════════════════════════════════════ --}}
 
    @if (session()->has('sukses'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0"
            class="flex items-center gap-3 bg-green-50 border border-green-300
                   text-green-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">✅</span>
            <span class="flex-1 font-medium text-sm">{{ session('sukses') }}</span>
            <button @click="tampil = false" class="text-green-400 hover:text-green-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif
 
    @if (session()->has('error'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 5000)"
            x-transition
            class="flex items-center gap-3 bg-red-50 border border-red-300
                   text-red-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">❌</span>
            <span class="flex-1 font-medium text-sm">{{ session('error') }}</span>
            <button @click="tampil = false" class="text-red-400 hover:text-red-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif
 
    {{-- ════════════════════════════════════════ --}}
    {{-- KARTU UTAMA                              --}}
    {{-- ════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-green-900">👥 Data Pasien</h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Kelola data pasien klinik rawat inap
                </p>
            </div>
            <button
                wire:click="bukaFormTambah"
                class="flex items-center gap-2 bg-green-700 hover:bg-green-800
                       active:bg-green-900 text-white text-sm font-semibold
                       px-5 py-2.5 rounded-xl shadow-sm shadow-green-200
                       hover:shadow-md hover:shadow-green-300
                       transition-all duration-200 active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pasien
            </button>
        </div>
 
        {{-- ─────────────────────────────────── --}}
        {{-- TOOLBAR: CARI + FILTER              --}}
        {{-- ─────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 bg-slate-50/50 border-b border-slate-100">
 
            {{-- Input Pencarian dengan ikon --}}
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="cari"
                    type="text"
                    placeholder="Cari nama, email, No. RM, atau NIK..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all duration-200"
                >
            </div>
 
            {{-- Filter Jenis Kelamin --}}
            <select
                wire:model.live="filterJK"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all duration-200 min-w-[160px]"
            >
                <option value="">Semua Jenis Kelamin</option>
                <option value="L">👨 Laki-laki</option>
                <option value="P">👩 Perempuan</option>
            </select>
 
            {{-- Indikator Loading --}}
            <div wire:loading wire:target="cari,filterJK"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Mencari...
            </div>
        </div>

                <div class="overflow-x-auto" wire:loading.class="opacity-50" wire:target="cari,filterJK">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold rounded-tl-none">No. RM</th>
                        <th class="px-5 py-4 text-left font-semibold">Nama Pasien</th>
                        <th class="px-5 py-4 text-left font-semibold">J/K</th>
                        <th class="px-5 py-4 text-left font-semibold">Tgl. Lahir</th>
                        <th class="px-5 py-4 text-left font-semibold">Umur</th>
                        <th class="px-5 py-4 text-left font-semibold">No. Telepon</th>
                        <th class="px-5 py-4 text-left font-semibold">Alamat</th>
                        <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
 
                    @forelse ($pasien as $p)
                        <tr class="hover:bg-green-50/70 transition-colors duration-150 group">
 
                            {{-- Nomor RM --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs font-semibold bg-green-100
                                             text-green-800 px-2.5 py-1 rounded-lg">
                                    {{ $p->nomor_rm }}
                                </span>
                            </td>
 
                            {{-- Nama + Email --}}
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $p->nama }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $p->pengguna->email }}</div>
                            </td>
 
                            {{-- Jenis Kelamin dengan Badge --}}
                            <td class="px-5 py-3.5">
                                @if($p->jenis_kelamin === 'L')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1
                                                 rounded-full text-xs font-semibold
                                                 bg-blue-100 text-blue-700">
                                        👨 L
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1
                                                 rounded-full text-xs font-semibold
                                                 bg-pink-100 text-pink-700">
                                        👩 P
                                    </span>
                                @endif
                            </td>
 
                            {{-- Tanggal Lahir --}}
                            <td class="px-5 py-3.5 text-slate-600">
                                @if($p->tanggal_lahir)
                                    {{ $p->tanggal_lahir->format('d/m/Y') }}
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
 
                            {{-- Umur (dari accessor) --}}
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $p->umur }}
                            </td>
 
                            {{-- No. Telepon --}}
                            <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">
                                {{ $p->no_telepon ?? '—' }}
                            </td>
 
                            {{-- Alamat (potong 30 karakter) --}}
                            <td class="px-5 py-3.5 text-slate-500 max-w-[180px]">
                                <span title="{{ $p->alamat }}">
                                    {{ $p->alamat ? Str::limit($p->alamat, 30) : '—' }}
                                </span>
                            </td>
 
                            {{-- Tombol Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
 
                                    {{-- Tombol Edit --}}
                                    <button
                                        wire:click="bukaEdit({{ $p->id }})"
                                        title="Edit data {{ $p->nama }}"
                                        class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100
                                               border border-amber-200 text-amber-700 font-semibold
                                               text-xs px-3 py-1.5 rounded-lg transition-all duration-150
                                               active:scale-95"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
 
                                    {{-- Tombol Hapus --}}
                                    <button
                                        wire:click="konfirmasiHapus({{ $p->id }})"
                                        title="Hapus data {{ $p->nama }}"
                                        class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100
                                               border border-red-200 text-red-600 font-semibold
                                               text-xs px-3 py-1.5 rounded-lg transition-all duration-150
                                               active:scale-95"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- State kosong --}}
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            @if($cari)
                                                Tidak ada hasil untuk "{{ $cari }}"
                                            @else
                                                Belum ada data pasien
                                            @endif
                                        </p>
                                        <p class="text-sm mt-1">
                                            @if($cari)
                                                Coba kata kunci yang berbeda
                                            @else
                                                Klik tombol "Tambah Pasien" untuk mulai
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
 
                </tbody>
            </table>
        </div>
 
        {{-- FOOTER: Info jumlah + Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $pasien->count() }} dari {{ $pasien->total() }} pasien
            </p>
            <div>
                {{ $pasien->links() }}
            </div>
        </div>
 
    </div> {{-- akhir kartu utama --}}

        @if ($tampilForm)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false)"
    >
        {{-- Overlay gelap --}}
        <div
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
            wire:click="$set('tampilForm', false)"
        ></div>
 
        {{-- Kontainer modal —  di tengah layar --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl
                       max-h-[90vh] overflow-y-auto"
                x-on:click.stop
            >
 
                {{-- Header Modal --}}
                <div class="sticky top-0 z-10 flex items-center justify-between
                            px-7 py-5 bg-green-800 rounded-t-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center
                                    justify-center text-xl">
                            {{ $modeEdit ? '✏️' : '➕' }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $modeEdit ? 'Edit Data Pasien' : 'Tambah Pasien Baru' }}
                            </h3>
                            <p class="text-xs text-green-300">
                                {{ $modeEdit ? 'Perbarui informasi pasien' : 'Isi formulir di bawah ini' }}
                            </p>
                        </div>
                    </div>
                    <button
                        wire:click="$set('tampilForm', false)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-green-200 hover:text-white hover:bg-green-700
                               transition-all duration-150 text-2xl font-bold leading-none"
                    >×</button>
                </div>
 
                {{-- BODY FORM --}}
                <div class="px-7 py-6 space-y-5">
 
                    {{-- ── SEKSI: DATA AKUN LOGIN ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-green-100 text-green-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">1</span>
                            <p class="text-xs font-bold text-green-700 uppercase tracking-widest">
                                Data Akun Login
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-4">
 
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="nama"
                                    type="text"
                                    placeholder="Masukkan nama lengkap..."
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none transition-all
                                           focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           @error('nama') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>
 
                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    wire:model="email"
                                    type="email"
                                    placeholder="contoh@email.com"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none transition-all
                                           focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           @error('email') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>
 
                            {{-- Password --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Password
                                    @if ($modeEdit)
                                        <span class="text-slate-400 font-normal text-xs">
                                            (Kosongkan jika tidak ingin mengubah password)
                                        </span>
                                    @else
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input
                                    wire:model="password"
                                    type="password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none transition-all
                                           focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           @error('password') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
 
                    {{-- ── SEKSI: DATA MEDIS ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">2</span>
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-widest">
                                Data Medis
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-4">
 
                            {{-- NIK --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    NIK
                                    <span class="text-slate-400 font-normal text-xs">(opsional, 16 digit)</span>
                                </label>
                                <input
                                    wire:model="nik"
                                    type="text"
                                    maxlength="16"
                                    placeholder="1234567890123456"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm font-mono outline-none transition-all
                                           focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           @error('nik') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                @error('nik')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>
 
                            {{-- Jenis Kelamin + Golongan Darah (2 kolom) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="jenisKelamin"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                                               bg-white outline-none focus:ring-2 focus:ring-green-500
                                               focus:border-transparent transition-all
                                               @error('jenisKelamin') border-red-400 @enderror"
                                    >
                                        <option value="L">👨 Laki-laki</option>
                                        <option value="P">👩 Perempuan</option>
                                    </select>
                                    @error('jenisKelamin')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Golongan Darah
                                    </label>
                                    <select
                                        wire:model="golonganDarah"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                                               bg-white outline-none focus:ring-2 focus:ring-green-500
                                               focus:border-transparent transition-all"
                                    >
                                        <option value="">— Pilih —</option>
                                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gd)
                                            <option value="{{ $gd }}">{{ $gd }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
 
                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Tanggal Lahir
                                </label>
                                <input
                                    wire:model="tanggalLahir"
                                    type="date"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                                           bg-white outline-none focus:ring-2 focus:ring-green-500
                                           focus:border-transparent transition-all
                                           @error('tanggalLahir') border-red-400 @enderror"
                                >
                                @error('tanggalLahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
 
                            {{-- Alamat --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Alamat
                                </label>
                                <textarea
                                    wire:model="alamat"
                                    rows="2"
                                    placeholder="Masukkan alamat lengkap..."
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm
                                           bg-white outline-none focus:ring-2 focus:ring-green-500
                                           focus:border-transparent transition-all resize-none"
                                ></textarea>
                            </div>
 
                            {{-- No. Telepon --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    No. Telepon
                                </label>
                                <input
                                    wire:model="noTelepon"
                                    type="text"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm font-mono outline-none transition-all
                                           focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           @error('noTelepon') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                @error('noTelepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
 
                    {{-- ── SEKSI: DATA PENJAMIN ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-amber-100 text-amber-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">3</span>
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-widest">
                                Data Penjamin / Wali
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Nama Penjamin
                                </label>
                                <input
                                    wire:model="namaPenjamin"
                                    type="text"
                                    placeholder="Nama wali/keluarga"
                                    class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5
                                           text-sm outline-none focus:ring-2 focus:ring-green-500 transition-all"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    No. Telepon Penjamin
                                </label>
                                <input
                                    wire:model="noTeleponPenjamin"
                                    type="text"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5
                                           text-sm font-mono outline-none focus:ring-2 focus:ring-green-500 transition-all"
                                >
                            </div>
                        </div>
                    </div>
 
                    {{-- ── TOMBOL AKSI FORM ── --}}
                    <div class="flex gap-3 pt-2">
                        {{-- Tombol Simpan --}}
                        <button
                            wire:click="{{ $modeEdit ? 'update' : 'simpan' }}"
                            wire:loading.attr="disabled"
                            wire:target="simpan"
                            class="flex-1 flex items-center justify-center gap-2
                                   bg-green-700 hover:bg-green-800 disabled:opacity-60 disabled:cursor-not-allowed
                                   text-white font-bold py-3 rounded-xl transition-all duration-200
                                   active:scale-[0.98] shadow-md shadow-green-200"
                        >
                            <span wire:loading.remove wire:target="simpan">
                                {{ $modeEdit ? '💾  Simpan Perubahan' : '✅  Tambah Pasien' }}
                            </span>
                            <span wire:loading wire:target="simpan"
                                  class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
 
                        {{-- Tombol Batal --}}
                        <button
                            wire:click="$set('tampilForm', false)"
                            type="button"
                            class="px-7 bg-slate-100 hover:bg-slate-200 text-slate-700
                                   font-semibold py-3 rounded-xl transition-all duration-200"
                        >
                            Batal
                        </button>
                    </div>
 
                </div> {{-- akhir body form --}}
            </div> {{-- akhir modal card --}}
        </div> {{-- akhir modal container --}}
    </div> {{-- akhir modal overlay --}}
    @endif

        @if ($tampilKonfirmasiHapus)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-on:keydown.escape.window="$wire.batalHapus()">
 
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="batalHapus"></div>
 
        {{-- Card Konfirmasi --}}
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-7 text-center"
             x-on:click.stop>
 
            {{-- Ikon Peringatan --}}
            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center
                        text-3xl mx-auto mb-4">
                🗑️
            </div>
 
            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Data Pasien?</h3>
            <p class="text-sm text-slate-500 mb-1">Anda akan menghapus data:</p>
            <p class="font-bold text-slate-800 text-base mb-4">{{ $namaHapus }}</p>
 
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-left">
                <p class="text-xs text-amber-800">
                    ⚠️ <strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan.
                    Akun login pasien ini juga akan dihapus.
                </p>
            </div>
 
            <div class="flex gap-3">
                {{-- Tombol Hapus (merah) --}}
                <button
                    wire:click="hapus"
                    wire:loading.attr="disabled"
                    wire:target="hapus"
                    class="flex-1 flex items-center justify-center gap-2
                           bg-red-600 hover:bg-red-700 disabled:opacity-60
                           text-white font-bold py-3 rounded-xl transition-all active:scale-95"
                >
                    <span wire:loading.remove wire:target="hapus">Ya, Hapus</span>
                    <span wire:loading wire:target="hapus"
                          class="flex items-center gap-1.5">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Menghapus...
                    </span>
                </button>
 
                {{-- Tombol Batal --}}
                <button
                    wire:click="batalHapus"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700
                           font-semibold py-3 rounded-xl transition-all"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
 
</div> {{-- akhir komponen utama --}}



</div>
