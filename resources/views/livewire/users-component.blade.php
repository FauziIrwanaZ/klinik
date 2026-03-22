{{-- ══════════════════════════════════════════════════════
     resources/views/livewire/user-component.blade.php
     Model  : Pengguna  |  Role  : admin only
     ══════════════════════════════════════════════════════ --}}

<div class="space-y-5">

    {{-- ─────────────────────────────────────────────────
         FLASH MESSAGE
    ───────────────────────────────────────────────────── --}}
    @if (session()->has('pesan'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="flex items-center gap-3 bg-green-50 border border-green-200
                    text-green-800 px-5 py-3 rounded-xl shadow-sm">
            <span class="text-lg">&#10003;</span>
            <span class="flex-1 text-sm font-medium">{{ session('pesan') }}</span>
            <button @click="show = false"
                    class="text-green-400 hover:text-green-700 font-bold text-xl leading-none">&times;</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="flex items-center gap-3 bg-red-50 border border-red-200
                    text-red-800 px-5 py-3 rounded-xl shadow-sm">
            <span class="text-lg">&#10007;</span>
            <span class="flex-1 text-sm font-medium">{{ session('error') }}</span>
            <button @click="show = false"
                    class="text-red-400 hover:text-red-700 font-bold text-xl leading-none">&times;</button>
        </div>
    @endif


    {{-- ─────────────────────────────────────────────────
         STATISTIK — 4 kartu ringkas
    ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">

        @php
            $kartu = [
                ['label' => 'Total',    'nilai' => $statistik['total'],    'warna' => 'border-l-slate-400',  'teks' => 'text-slate-700'],
                ['label' => 'Aktif',    'nilai' => $statistik['aktif'],    'warna' => 'border-l-green-500',  'teks' => 'text-green-700'],
                ['label' => 'Nonaktif', 'nilai' => $statistik['nonaktif'], 'warna' => 'border-l-red-400',    'teks' => 'text-red-600'],
                ['label' => 'Admin',    'nilai' => $statistik['admin'],    'warna' => 'border-l-yellow-400', 'teks' => 'text-yellow-700'],
                ['label' => 'Petugas',  'nilai' => $statistik['petugas'],  'warna' => 'border-l-blue-400',   'teks' => 'text-blue-700'],
                ['label' => 'Dokter',   'nilai' => $statistik['dokter'],   'warna' => 'border-l-purple-400', 'teks' => 'text-purple-700'],
                ['label' => 'Pasien',   'nilai' => $statistik['pasien'],   'warna' => 'border-l-teal-400',   'teks' => 'text-teal-700'],
            ];
        @endphp

        @foreach ($kartu as $k)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 py-3
                        border-l-4 {{ $k['warna'] }} hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">{{ $k['label'] }}</p>
                <p class="text-2xl font-bold mt-1 {{ $k['teks'] }}">{{ $k['nilai'] }}</p>
            </div>
        @endforeach

    </div>


    {{-- ─────────────────────────────────────────────────
         KARTU UTAMA — TABEL
    ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Header + Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-green-900">
                    Manajemen Pengguna
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola akun pengguna sistem klinik</p>
            </div>
            <button
                wire:click="bukaFormTambah"
                class="flex items-center gap-2 bg-green-700 hover:bg-green-800 active:bg-green-900
                       text-white text-sm font-semibold px-5 py-2.5 rounded-xl
                       shadow-sm shadow-green-200 hover:shadow-md hover:shadow-green-300
                       transition-all duration-200 active:scale-95 shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pengguna
            </button>
        </div>

        {{-- Toolbar Filter --}}
        <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 bg-slate-50/60 border-b border-slate-100">

            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="cari"
                    type="text"
                    placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all"
                >
            </div>

            <select
                wire:model.live="filterRole"
                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all min-w-[160px]"
            >
                <option value="">Semua Role</option>
                <option value="admin">Administrator</option>
                <option value="petugas">Petugas</option>
                <option value="dokter">Dokter</option>
                <option value="pasien">Pasien</option>
            </select>

            <div wire:loading wire:target="cari,filterRole"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Mencari...
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto"
             wire:loading.class="opacity-50 pointer-events-none"
             wire:target="cari,filterRole">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold">#</th>
                        <th class="px-5 py-4 text-left font-semibold">Nama / Email</th>
                        <th class="px-5 py-4 text-center font-semibold">Role</th>
                        <th class="px-5 py-4 text-center font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Terdaftar</th>
                        <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse ($pengguna as $p)
                        <tr class="transition-colors duration-150
                            {{ $p->id === auth()->id()
                                ? 'bg-green-50/40 hover:bg-green-50'
                                : 'hover:bg-slate-50' }}">

                            {{-- Nomor urut --}}
                            <td class="px-5 py-3.5">
                                <span class="text-xs text-slate-400 font-mono">
                                    {{ $pengguna->firstItem() + $loop->index }}
                                </span>
                            </td>

                            {{-- Nama + Email --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar inisial --}}
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-100 to-green-200
                                                flex items-center justify-center text-green-700 font-bold
                                                text-sm shrink-0">
                                        {{ mb_strtoupper(mb_substr($p->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-semibold text-slate-800">{{ $p->nama }}</p>
                                            @if ($p->id === auth()->id())
                                                <span class="text-xs bg-green-100 text-green-700 px-1.5
                                                             py-0.5 rounded font-semibold">Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $p->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Badge Role — menggunakan accessor label_role dari model --}}
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $roleBadge = match($p->role) {
                                        'admin'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'petugas' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'dokter'  => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'pasien'  => 'bg-teal-100 text-teal-700 border-teal-200',
                                        default   => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                             text-xs font-semibold border {{ $roleBadge }}">
                                    {{ $p->label_role }}
                                </span>
                            </td>

                            {{-- Badge Status + Toggle --}}
                            <td class="px-5 py-3.5 text-center">
                                <button
                                    wire:click="toggleAktif({{ $p->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="toggleAktif({{ $p->id }})"
                                    title="Klik untuk ubah status"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                           text-xs font-semibold border cursor-pointer transition-all
                                           hover:opacity-75 active:scale-95
                                           {{ $p->aktif
                                               ? 'bg-green-100 text-green-700 border-green-200'
                                               : 'bg-red-100 text-red-600 border-red-200' }}"
                                >
                                    <span wire:loading.remove wire:target="toggleAktif({{ $p->id }})">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block
                                                     {{ $p->aktif ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                        {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span wire:loading wire:target="toggleAktif({{ $p->id }})">
                                        <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                    </span>
                                </button>
                            </td>

                            {{-- Tanggal daftar --}}
                            <td class="px-5 py-3.5">
                                <p class="text-xs text-slate-600 font-medium">
                                    {{ $p->created_at->format('d M Y') }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $p->created_at->diffForHumans() }}
                                </p>
                            </td>

                            {{-- Tombol Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Edit --}}
                                    <button
                                        wire:click="bukaEdit({{ $p->id }})"
                                        title="Edit pengguna"
                                        class="inline-flex items-center gap-1 bg-amber-50 hover:bg-amber-100
                                               border border-amber-200 text-amber-700 font-semibold
                                               text-xs px-2.5 py-1.5 rounded-lg transition-all active:scale-95"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Hapus — tersembunyi untuk akun sendiri --}}
                                    @if ($p->id !== auth()->id())
                                        <button
                                            wire:click="konfirmasiHapus({{ $p->id }})"
                                            title="Hapus pengguna"
                                            class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100
                                                   border border-red-200 text-red-600 font-semibold
                                                   text-xs px-2.5 py-1.5 rounded-lg transition-all active:scale-95"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300 italic px-2 py-1.5">—</span>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-14 h-14 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            {{ ($cari || $filterRole) ? 'Tidak ada pengguna yang sesuai' : 'Belum ada data pengguna' }}
                                        </p>
                                        <p class="text-sm mt-1">
                                            {{ ($cari || $filterRole) ? 'Coba ubah kata kunci atau filter' : 'Klik "Tambah Pengguna" untuk memulai' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Footer + Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $pengguna->count() }} dari {{ $pengguna->total() }} pengguna
            </p>
            {{ $pengguna->links() }}
        </div>

    </div>{{-- akhir kartu utama --}}


    {{-- ─────────────────────────────────────────────────
         MODAL FORM TAMBAH / EDIT
    ───────────────────────────────────────────────────── --}}
    @if ($tampilForm)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false)"
    >
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="$set('tampilForm', false)"></div>

        <div class="relative flex min-h-screen items-start justify-center p-4 pt-10">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-on:click.stop
            >
                {{-- Header Modal --}}
                <div class="sticky top-0 z-10 flex items-center justify-between
                            px-7 py-5 bg-green-800 rounded-t-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center
                                    justify-center text-xl">
                            {{ $modeEdit ? '&#9998;' : '&#43;' }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $modeEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                            </h3>
                            <p class="text-xs text-green-300">
                                {{ $modeEdit ? 'Perbarui data akun' : 'Isi data pengguna baru' }}
                            </p>
                        </div>
                    </div>
                    <button
                        wire:click="$set('tampilForm', false)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-green-200 hover:text-white hover:bg-green-700
                               transition-all text-2xl font-bold leading-none"
                    >&times;</button>
                </div>

                {{-- Body Form --}}
                <div class="px-7 py-6 space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="nama"
                            type="text"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                   outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   transition-all @error('nama') border-red-400 bg-red-50 @else border-slate-200 @enderror"
                        >
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                            class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                   outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   transition-all @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror"
                        >
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password dengan toggle show/hide --}}
                    <div x-data="{ lihat: false }">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Password
                            @if (!$modeEdit)
                                <span class="text-red-500">*</span>
                            @else
                                <span class="text-slate-400 font-normal text-xs ml-1">
                                    (kosongkan jika tidak diubah)
                                </span>
                            @endif
                        </label>
                        <div class="relative">
                            <input
                                wire:model="password"
                                :type="lihat ? 'text' : 'password'"
                                placeholder="{{ $modeEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' }}"
                                class="w-full border rounded-xl px-4 py-2.5 pr-10 text-sm bg-white
                                       outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                       transition-all @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror"
                            >
                            {{-- Tombol toggle --}}
                            <button
                                type="button"
                                @click="lihat = !lihat"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-slate-400 hover:text-slate-600 transition-colors"
                            >
                                <svg x-show="!lihat" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="lihat" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach ([
                                ['admin',   'bg-yellow-600', 'Administrator'],
                                ['petugas', 'bg-blue-600',   'Petugas'],
                                ['dokter',  'bg-purple-700', 'Dokter'],
                                ['pasien',  'bg-teal-600',   'Pasien'],
                            ] as [$val, $clr, $lbl])
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="role" value="{{ $val }}" class="sr-only peer">
                                    <div class="flex items-center justify-center py-2.5 px-2 rounded-xl border-2
                                                text-xs font-semibold transition-all text-center
                                                border-slate-200 text-slate-500 bg-white
                                                peer-checked:border-transparent peer-checked:text-white peer-checked:{{ $clr }}
                                                hover:border-slate-300">
                                        {{ $lbl }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Status Akun
                        </label>
                        <div class="grid grid-cols-2 gap-2 max-w-xs">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="aktif" value="1" class="sr-only peer">
                                <div class="flex items-center justify-center py-2.5 rounded-xl border-2
                                            text-xs font-semibold transition-all
                                            border-slate-200 text-slate-500 bg-white
                                            peer-checked:border-transparent peer-checked:bg-green-700
                                            peer-checked:text-white hover:border-slate-300">
                                    Aktif
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="aktif" value="0" class="sr-only peer">
                                <div class="flex items-center justify-center py-2.5 rounded-xl border-2
                                            text-xs font-semibold transition-all
                                            border-slate-200 text-slate-500 bg-white
                                            peer-checked:border-transparent peer-checked:bg-red-600
                                            peer-checked:text-white hover:border-slate-300">
                                    Nonaktif
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-3 pt-2">
                        <button
                            wire:click="{{ $modeEdit ? 'update' : 'simpan' }}"
                            wire:loading.attr="disabled"
                            wire:target="{{ $modeEdit ? 'update' : 'simpan' }}"
                            class="flex-1 flex items-center justify-center gap-2
                                   bg-green-700 hover:bg-green-800 disabled:opacity-60
                                   text-white font-bold py-3 rounded-xl transition-all
                                   active:scale-[0.98] shadow-md shadow-green-200"
                        >
                            <span wire:loading.remove wire:target="{{ $modeEdit ? 'update' : 'simpan' }}">
                                {{ $modeEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                            </span>
                            <span wire:loading wire:target="{{ $modeEdit ? 'update' : 'simpan' }}"
                                  class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                        <button
                            wire:click="$set('tampilForm', false)"
                            class="px-7 bg-slate-100 hover:bg-slate-200 text-slate-700
                                   font-semibold py-3 rounded-xl transition-all"
                        >
                            Batal
                        </button>
                    </div>

                </div>{{-- akhir body --}}
            </div>
        </div>
    </div>
    @endif


    {{-- ─────────────────────────────────────────────────
         DIALOG KONFIRMASI HAPUS
    ───────────────────────────────────────────────────── --}}
    @if ($konfirmasiHapus)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data
        x-on:keydown.escape.window="$wire.batalHapus()"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="batalHapus"></div>

        <div
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-7 text-center"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-on:click.stop
        >
            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center
                        text-3xl mx-auto mb-4">&#128465;&#65039;</div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Pengguna?</h3>
            <p class="text-sm text-slate-500 mb-1">Anda akan menghapus akun:</p>
            <p class="font-bold text-slate-800 mb-5">{{ $namaHapus }}</p>

            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6 text-left">
                <p class="text-xs text-red-700">
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                    Data pengguna akan dihapus secara permanen.
                </p>
            </div>

            <div class="flex gap-3">
                <button
                    wire:click="hapus"
                    wire:loading.attr="disabled"
                    wire:target="hapus"
                    class="flex-1 flex items-center justify-center gap-2
                           bg-red-600 hover:bg-red-700 disabled:opacity-60
                           text-white font-bold py-3 rounded-xl transition-all active:scale-95"
                >
                    <span wire:loading.remove wire:target="hapus">Ya, Hapus</span>
                    <span wire:loading wire:target="hapus" class="flex items-center gap-1.5">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Menghapus...
                    </span>
                </button>
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

</div>{{-- akhir komponen --}}