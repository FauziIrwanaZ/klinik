{{-- ══════════════════════════════════════════════════════════════════
     VIEW  : kamar-component.blade.php
     Komponen: app/Livewire/KamarComponent.php
     Role   : admin, petugas
     Tema   : Putih–Hijau Profesional (Klinik Rawat Inap)
     ══════════════════════════════════════════════════════════════════ --}}

<div class="space-y-5">

    {{-- ════════════════════════════════════════════════ --}}
    {{-- FLASH MESSAGE — sukses & error (auto-dismiss)   --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if (session()->has('pesan'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-3 bg-green-50 border border-green-200
                   text-green-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">✅</span>
            <span class="flex-1 font-medium text-sm">{{ session('pesan') }}</span>
            <button @click="tampil = false"
                    class="text-green-400 hover:text-green-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-3 bg-red-50 border border-red-200
                   text-red-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">❌</span>
            <span class="flex-1 font-medium text-sm">{{ session('error') }}</span>
            <button @click="tampil = false"
                    class="text-red-400 hover:text-red-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- KARTU STATISTIK KAMAR — 3 kolom                 --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Tersedia --}}
        <div class="bg-white rounded-2xl border border-green-100 shadow-sm px-5 py-4
                    border-l-4 border-l-green-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Tersedia</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistikKamar['tersedia'] }}
                    </p>
                    <p class="text-xs text-green-600 mt-1 font-medium">✅ Siap digunakan</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">
                    🛏
                </div>
            </div>
        </div>

        {{-- Terisi --}}
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm px-5 py-4
                    border-l-4 border-l-red-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Terisi</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistikKamar['terisi'] }}
                    </p>
                    <p class="text-xs text-red-600 mt-1 font-medium">🔴 Sedang digunakan</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">
                    🏥
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="bg-white rounded-2xl border border-yellow-100 shadow-sm px-5 py-4
                    border-l-4 border-l-yellow-400 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Maintenance</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistikKamar['maintenance'] }}
                    </p>
                    <p class="text-xs text-yellow-600 mt-1 font-medium">🔧 Sedang diperbaiki</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl">
                    🔧
                </div>
            </div>
        </div>

    </div>{{-- akhir statistik --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- KARTU UTAMA — tabel data kamar                  --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- ── HEADER + TOMBOL TAMBAH ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-green-900">🏠 Data Kamar</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manajemen data kamar klinik rawat inap</p>
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
                Tambah Kamar Baru
            </button>
        </div>

        {{-- ── TOOLBAR: CARI + FILTER ── --}}
        <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 bg-slate-50/50 border-b border-slate-100">

            {{-- Input pencarian --}}
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="cari"
                    type="text"
                    placeholder="Cari nomor kamar..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all duration-200"
                >
            </div>

            {{-- Filter tipe kamar --}}
            <select
                wire:model.live="filterTipe"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all duration-200 min-w-[140px]"
            >
                <option value="">Semua Tipe</option>
                <option value="VIP">🌟 VIP</option>
                <option value="Kelas 1">Kelas 1</option>
                <option value="Kelas 2">Kelas 2</option>
                <option value="Kelas 3">Kelas 3</option>
            </select>

            {{-- Filter status --}}
            <select
                wire:model.live="filterStatus"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all duration-200 min-w-[160px]"
            >
                <option value="">Semua Status</option>
                <option value="tersedia">✅ Tersedia</option>
                <option value="terisi">🔴 Terisi</option>
                <option value="maintenance">🔧 Maintenance</option>
            </select>

            {{-- Indikator loading --}}
            <div wire:loading wire:target="cari,filterTipe,filterStatus"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Memuat...
            </div>
        </div>

        {{-- ── TABEL DATA KAMAR ── --}}
        <div class="overflow-x-auto"
             wire:loading.class="opacity-50"
             wire:target="cari,filterTipe,filterStatus">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold">No. Kamar</th>
                        <th class="px-5 py-4 text-left font-semibold">Tipe</th>
                        <th class="px-5 py-4 text-center font-semibold">Kapasitas</th>
                        <th class="px-5 py-4 text-right font-semibold">Harga/Malam</th>
                        <th class="px-5 py-4 text-left font-semibold">Fasilitas</th>
                        <th class="px-5 py-4 text-center font-semibold">Status</th>
                        <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse ($kamar as $k)
                        <tr class="hover:bg-green-50/60 transition-colors duration-150">

                            {{-- Nomor Kamar --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-bold text-green-800 bg-green-50
                                             border border-green-200 px-2.5 py-1 rounded-lg text-sm">
                                    {{ $k->nomor_kamar }}
                                </span>
                            </td>

                            {{-- Tipe Kamar dengan badge warna --}}
                            <td class="px-5 py-3.5">
                                @php
                                    $tipeStyle = match($k->tipe_kamar) {
                                        'VIP'     => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'Kelas 1' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'Kelas 2' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        default   => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                                             text-xs font-semibold border {{ $tipeStyle }}">
                                    @if($k->tipe_kamar === 'VIP') 🌟 @endif
                                    {{ $k->tipe_kamar }}
                                </span>
                            </td>

                            {{-- Kapasitas --}}
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1 text-slate-600">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20H7M12 4v16M5 10h14"/>
                                    </svg>
                                    {{ $k->kapasitas }} TT
                                </span>
                            </td>

                            {{-- Harga per Malam --}}
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-semibold text-slate-800">
                                    {{ $k->harga_format }}
                                </span>
                                <p class="text-xs text-slate-400">per malam</p>
                            </td>

                            {{-- Fasilitas --}}
                            <td class="px-5 py-3.5 text-slate-500 max-w-[200px]">
                                @if($k->fasilitas)
                                    <span title="{{ $k->fasilitas }}">
                                        {{ Str::limit($k->fasilitas, 35) }}
                                    </span>
                                @else
                                    <span class="text-slate-300 italic text-xs">—</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-3.5 text-center">
                                @if($k->status === 'tersedia')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-green-100 text-green-700
                                                 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Tersedia
                                    </span>
                                @elseif($k->status === 'terisi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-red-100 text-red-700
                                                 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Terisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-yellow-100 text-yellow-700
                                                 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                        Maintenance
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol Aksi --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Edit --}}
                                    <button
                                        wire:click="bukaEdit({{ $k->id }})"
                                        title="Edit kamar {{ $k->nomor_kamar }}"
                                        class="inline-flex items-center gap-1 bg-amber-50 hover:bg-amber-100
                                               border border-amber-200 text-amber-700 font-semibold
                                               text-xs px-2.5 py-1.5 rounded-lg transition-all duration-150
                                               active:scale-95"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Ubah Status Cepat (dropdown kecil) --}}
                                    <div x-data="{ buka: false }" class="relative">
                                        <button
                                            @click="buka = !buka"
                                            title="Ubah status kamar"
                                            class="inline-flex items-center gap-1 bg-slate-50 hover:bg-slate-100
                                                   border border-slate-200 text-slate-600 font-semibold
                                                   text-xs px-2.5 py-1.5 rounded-lg transition-all duration-150
                                                   active:scale-95"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Status
                                        </button>

                                        {{-- Dropdown pilihan status --}}
                                        <div
                                            x-show="buka"
                                            @click.outside="buka = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute right-0 top-full mt-1 z-20 bg-white border border-slate-200
                                                   rounded-xl shadow-lg overflow-hidden min-w-[150px]"
                                        >
                                            @if($k->status !== 'tersedia')
                                            <button
                                                wire:click="ubahStatus({{ $k->id }}, 'tersedia')"
                                                @click="buka = false"
                                                class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-medium
                                                       text-green-700 hover:bg-green-50 transition-colors text-left"
                                            >
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                Tandai Tersedia
                                            </button>
                                            @endif
                                            @if($k->status !== 'terisi')
                                            <button
                                                wire:click="ubahStatus({{ $k->id }}, 'terisi')"
                                                @click="buka = false"
                                                class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-medium
                                                       text-red-600 hover:bg-red-50 transition-colors text-left"
                                            >
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                Tandai Terisi
                                            </button>
                                            @endif
                                            @if($k->status !== 'maintenance')
                                            <button
                                                wire:click="ubahStatus({{ $k->id }}, 'maintenance')"
                                                @click="buka = false"
                                                class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-medium
                                                       text-yellow-600 hover:bg-yellow-50 transition-colors text-left"
                                            >
                                                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                                Maintenance
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Hapus --}}
                                    <button
                                        wire:click="konfirmasiHapus({{ $k->id }})"
                                        title="Hapus kamar {{ $k->nomor_kamar }}"
                                        class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100
                                               border border-red-200 text-red-600 font-semibold
                                               text-xs px-2.5 py-1.5 rounded-lg transition-all duration-150
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
                        {{-- State Kosong --}}
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-16 h-16 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            @if($cari || $filterStatus || $filterTipe)
                                                Tidak ada hasil yang sesuai filter
                                            @else
                                                Belum ada data kamar
                                            @endif
                                        </p>
                                        <p class="text-sm mt-1 text-slate-400">
                                            @if($cari || $filterStatus || $filterTipe)
                                                Coba ubah kata kunci atau filter
                                            @else
                                                Klik "Tambah Kamar Baru" untuk memulai
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

        {{-- Footer: info jumlah + pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $kamar->count() }} dari {{ $kamar->total() }} kamar
            </p>
            <div>
                {{ $kamar->links() }}
            </div>
        </div>

    </div>{{-- akhir kartu utama --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL FORM TAMBAH / EDIT KAMAR                  --}}
    {{-- Dikontrol oleh $tampilForm dari Livewire         --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if ($tampilForm)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false)"
    >
        {{-- Overlay --}}
        <div
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
            wire:click="$set('tampilForm', false)"
        ></div>

        {{-- Kontainer modal --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
                x-on:click.stop
            >
                {{-- Header Modal --}}
                <div class="sticky top-0 z-10 flex items-center justify-between
                            px-7 py-5 bg-green-800 rounded-t-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center
                                    justify-center text-xl">
                            {{ $modeEdit ? '✏️' : '🏠' }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $modeEdit ? 'Edit Data Kamar' : 'Tambah Kamar Baru' }}
                            </h3>
                            <p class="text-xs text-green-300">
                                {{ $modeEdit ? 'Perbarui informasi kamar' : 'Isi data kamar baru' }}
                            </p>
                        </div>
                    </div>
                    <button
                        wire:click="$set('tampilForm', false)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-green-200 hover:text-white hover:bg-green-700
                               transition-all text-2xl font-bold leading-none"
                    >×</button>
                </div>

                {{-- Body Form --}}
                <div class="px-7 py-6 space-y-4">

                    {{-- Nomor Kamar --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nomor Kamar <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="nomorKamar"
                            type="text"
                            placeholder="Contoh: A-101"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm font-mono
                                   outline-none transition-all focus:ring-2 focus:ring-green-500
                                   focus:border-transparent
                                   @error('nomorKamar') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                        >
                        @error('nomorKamar')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span>⚠</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tipe Kamar + Status (2 kolom) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tipe Kamar <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model="tipeKamar"
                                class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                       outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                       transition-all @error('tipeKamar') border-red-400 @enderror"
                            >
                                <option value="VIP">🌟 VIP</option>
                                <option value="Kelas 1">Kelas 1</option>
                                <option value="Kelas 2">Kelas 2</option>
                                <option value="Kelas 3">Kelas 3</option>
                            </select>
                            @error('tipeKamar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model="status"
                                class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                       outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                       transition-all @error('status') border-red-400 @enderror"
                            >
                                <option value="tersedia">✅ Tersedia</option>
                                <option value="terisi">🔴 Terisi</option>
                                <option value="maintenance">🔧 Maintenance</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kapasitas + Harga per Malam (2 kolom) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Kapasitas (Tempat Tidur) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    wire:model="kapasitas"
                                    type="number"
                                    min="1" max="20"
                                    placeholder="1"
                                    class="w-full border rounded-xl px-4 py-2.5 pr-12 text-sm
                                           outline-none transition-all focus:ring-2 focus:ring-green-500
                                           focus:border-transparent
                                           @error('kapasitas') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2
                                             text-xs text-slate-400 font-medium">TT</span>
                            </div>
                            @error('kapasitas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Harga per Malam <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2
                                             text-xs text-slate-500 font-semibold">Rp</span>
                                <input
                                    wire:model="hargaMalam"
                                    type="number"
                                    min="0" step="10000"
                                    placeholder="150000"
                                    class="w-full border rounded-xl pl-9 pr-4 py-2.5 text-sm
                                           outline-none transition-all focus:ring-2 focus:ring-green-500
                                           focus:border-transparent
                                           @error('hargaMalam') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                                >
                            </div>
                            @error('hargaMalam')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Fasilitas
                            <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea
                            wire:model="fasilitas"
                            rows="3"
                            placeholder="Contoh: AC, TV, Kamar mandi dalam, WiFi..."
                            class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                   outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- Info harga preview (hanya tampil saat harga terisi) --}}
                    @if($hargaMalam && is_numeric($hargaMalam) && $hargaMalam > 0)
                    <div class="bg-green-50 border border-green-100 rounded-xl px-4 py-3">
                        <p class="text-xs text-green-700">
                            💡 Harga per malam:
                            <strong>Rp {{ number_format((int)$hargaMalam, 0, ',', '.') }}</strong>
                            — Estimasi 7 malam:
                            <strong>Rp {{ number_format((int)$hargaMalam * 7, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-3 pt-2">
                        {{-- Tombol Simpan --}}
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
                                {{ $modeEdit ? '💾  Simpan Perubahan' : '✅  Tambah Kamar' }}
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

                        {{-- Tombol Batal --}}
                        <button
                            wire:click="$set('tampilForm', false)"
                            type="button"
                            class="px-7 bg-slate-100 hover:bg-slate-200 text-slate-700
                                   font-semibold py-3 rounded-xl transition-all"
                        >
                            Batal
                        </button>
                    </div>

                </div>{{-- akhir body form --}}
            </div>{{-- akhir modal card --}}
        </div>{{-- akhir modal container --}}
    </div>{{-- akhir modal overlay --}}
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- DIALOG KONFIRMASI HAPUS                         --}}
    {{-- Tambahkan properti konfirmasiHapus, idHapus,    --}}
    {{-- namaHapus ke KamarComponent jika belum ada      --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if (isset($konfirmasiHapus) && $konfirmasiHapus)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data
        x-on:keydown.escape.window="$wire.batalHapus()"
    >
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="batalHapus"></div>

        {{-- Card Konfirmasi --}}
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-7 text-center"
             x-on:click.stop>

            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center
                        text-3xl mx-auto mb-4">
                🗑️
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Data Kamar?</h3>
            <p class="text-sm text-slate-500 mb-1">Anda akan menghapus kamar:</p>
            <p class="font-bold text-slate-800 text-base mb-4">
                {{ $namaHapus ?? 'kamar ini' }}
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-left">
                <p class="text-xs text-amber-800">
                    ⚠️ <strong>Perhatian:</strong>
                    Kamar tidak dapat dihapus jika masih ada pasien aktif di dalamnya.
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
    @else
    {{-- Fallback: jika KamarComponent tidak punya konfirmasiHapus,
         gunakan wire:confirm bawaan Livewire v3 --}}
    @endif

</div>{{-- akhir komponen utama --}}