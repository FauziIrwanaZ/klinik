{{-- ══════════════════════════════════════════════════════════════════
     VIEW  : rawat-inap-component.blade.php
     Komponen: app/Livewire/RawatInapComponent.php
     Role   : petugas (CRUD penuh), dokter (input diagnosa)
     Tema   : Putih–Hijau Profesional — Klinik Rawat Inap
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
    {{-- STATISTIK RAWAT INAP — 4 kartu                  --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Dirawat --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-blue-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Dirawat</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $rawatInap->where('status', 'dirawat')->count() }}
                    </p>
                    <p class="text-xs text-blue-600 mt-1 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse inline-block"></span>
                        Aktif dirawat
                    </p>
                </div>
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center text-xl shrink-0">
                    🏥
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-green-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Selesai</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $rawatInap->where('status', 'selesai')->count() }}
                    </p>
                    <p class="text-xs text-green-600 mt-1 font-medium">✅ Pulang normal</p>
                </div>
                <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center text-xl shrink-0">
                    🏠
                </div>
            </div>
        </div>

        {{-- Dirujuk --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-amber-400 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Dirujuk</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $rawatInap->where('status', 'dirujuk')->count() }}
                    </p>
                    <p class="text-xs text-amber-600 mt-1 font-medium">🚑 Ke RS lain</p>
                </div>
                <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center text-xl shrink-0">
                    🚑
                </div>
            </div>
        </div>

        {{-- Meninggal --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-red-400 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Meninggal</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $rawatInap->where('status', 'meninggal')->count() }}
                    </p>
                    <p class="text-xs text-red-500 mt-1 font-medium">🕊 Wafat</p>
                </div>
                <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center text-xl shrink-0">
                    🕊
                </div>
            </div>
        </div>

    </div>{{-- akhir statistik --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- KARTU UTAMA — tabel rawat inap                  --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- ── HEADER + TOMBOL DAFTARKAN ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold tracking-tight text-green-900">🏥 Rawat Inap Pasien</h2>
                    {{-- Badge role aktif --}}
                    @if ($rolePengguna === 'dokter')
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                     bg-purple-100 text-purple-700 border border-purple-200">
                            🩺 Mode Dokter
                        </span>
                    @else
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                     bg-blue-100 text-blue-700 border border-blue-200">
                            📋 Mode Petugas
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Manajemen pasien yang sedang dirawat di klinik</p>
            </div>

            {{-- Tombol hanya untuk petugas --}}
            @if ($rolePengguna === 'petugas')
                <button
                    wire:click="bukaFormTambah"
                    class="flex items-center gap-2 bg-green-700 hover:bg-green-800
                           active:bg-green-900 text-white text-sm font-semibold
                           px-5 py-2.5 rounded-xl shadow-sm shadow-green-200
                           hover:shadow-md hover:shadow-green-300
                           transition-all duration-200 active:scale-95 shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Daftarkan Rawat Inap
                </button>
            @endif
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
                    placeholder="Cari nama pasien..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all duration-200"
                >
            </div>

            {{-- Filter status --}}
            <select
                wire:model.live="filterStatus"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all duration-200 min-w-[175px]"
            >
                <option value="">Semua Status</option>
                <option value="dirawat">🏥 Dirawat</option>
                <option value="selesai">✅ Selesai</option>
                <option value="dirujuk">🚑 Dirujuk</option>
                <option value="meninggal">🕊 Meninggal</option>
            </select>

            {{-- Indikator loading --}}
            <div wire:loading wire:target="cari,filterStatus"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Mencari...
            </div>
        </div>

        {{-- ── TABEL RAWAT INAP ── --}}
        <div class="overflow-x-auto"
             wire:loading.class="opacity-50"
             wire:target="cari,filterStatus">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold">Pasien</th>
                        <th class="px-5 py-4 text-left font-semibold">Dokter</th>
                        <th class="px-5 py-4 text-left font-semibold">Kamar</th>
                        <th class="px-5 py-4 text-left font-semibold">Tgl Masuk</th>
                        <th class="px-5 py-4 text-left font-semibold">Lama / Keluar</th>
                        <th class="px-5 py-4 text-center font-semibold">Status</th>
                        <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse ($rawatInap as $ri)

                        {{-- BONUS: Highlight pasien dirawat > 7 hari --}}
                        @php
                            $lamaHari = $ri->tanggal_masuk
                                ? (int) $ri->tanggal_masuk->diffInDays(now())
                                : 0;
                            $terlaluLama = $ri->status === 'dirawat' && $lamaHari > 7;
                        @endphp

                        <tr class="transition-colors duration-150
                            {{ $terlalu_lama ?? $terlalu_lama = $ri->status === 'dirawat' && $ri->tanggal_masuk && $ri->tanggal_masuk->diffInDays(now()) > 7 }}
                            @if($terlalu_lama) bg-amber-50/60 hover:bg-amber-50 @else hover:bg-green-50/60 @endif">

                            {{-- Pasien --}}
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800 flex items-center gap-2">
                                    {{ $ri->pasien->pengguna->nama ?? '—' }}
                                    @if($terlalu_lama)
                                        <span class="text-xs bg-amber-100 text-amber-700 border border-amber-200
                                                     px-1.5 py-0.5 rounded-md font-semibold" title="Dirawat lebih dari 7 hari">
                                            ⚠ {{ $lamaHari }}h
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $ri->pasien->nomor_rm ?? '' }}
                                </div>
                            </td>

                            {{-- Dokter --}}
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-700">
                                    {{ $ri->dokter->pengguna->nama ?? '—' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $ri->dokter->spesialisasi ?? '' }}
                                </div>
                            </td>

                            {{-- Kamar --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-bold text-green-800 bg-green-50
                                             border border-green-200 px-2.5 py-1 rounded-lg text-xs">
                                    {{ $ri->kamar->nomor_kamar ?? '—' }}
                                </span>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $ri->kamar->tipe_kamar ?? '' }}
                                </p>
                            </td>

                            {{-- Tanggal Masuk --}}
                            <td class="px-5 py-3.5">
                                <div class="text-slate-700 font-medium text-sm">
                                    {{ $ri->tanggal_masuk?->format('d M Y') ?? '—' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $ri->tanggal_masuk?->format('H:i') }} WIB
                                </div>
                            </td>

                            {{-- BONUS: Indikator lama dirawat / tanggal keluar --}}
                            <td class="px-5 py-3.5">
                                @if($ri->status === 'dirawat')
                                    {{-- Masih dirawat: tampilkan counter hari --}}
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg
                                                text-xs font-semibold
                                                {{ $lamaHari > 7 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $lamaHari }} hari
                                    </div>
                                @elseif($ri->tanggal_keluar)
                                    {{-- Sudah keluar: tampilkan tanggal keluar --}}
                                    <div class="text-slate-600 text-sm font-medium">
                                        {{ $ri->tanggal_keluar->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $ri->tanggal_masuk && $ri->tanggal_keluar
                                            ? $ri->tanggal_masuk->diffInDays($ri->tanggal_keluar).' hari'
                                            : '' }}
                                    </div>
                                @else
                                    <span class="text-slate-300 text-xs italic">—</span>
                                @endif
                            </td>

                            {{-- Badge Status --}}
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $badgeStatus = match($ri->status) {
                                        'dirawat'   => ['bg-blue-100 text-blue-700 border-blue-200',   '🏥', 'Dirawat'],
                                        'selesai'   => ['bg-green-100 text-green-700 border-green-200', '✅', 'Selesai'],
                                        'dirujuk'   => ['bg-amber-100 text-amber-700 border-amber-200', '🚑', 'Dirujuk'],
                                        'meninggal' => ['bg-red-100 text-red-600 border-red-200',       '🕊', 'Meninggal'],
                                        default     => ['bg-slate-100 text-slate-600 border-slate-200', '❓', $ri->status],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                             text-xs font-semibold border {{ $badgeStatus[0] }}">
                                    @if($ri->status === 'dirawat')
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    @endif
                                    {{ $badgeStatus[2] }}
                                </span>

                                {{-- Indikator diagnosa (khusus dokter) --}}
                                @if($rolePengguna === 'dokter')
                                    @if($ri->diagnosa)
                                        <p class="text-xs text-green-500 mt-1">📋 Ada diagnosa</p>
                                    @else
                                        <p class="text-xs text-amber-500 mt-1">⚠ Belum diagnosa</p>
                                    @endif
                                @endif
                            </td>

                            {{-- Tombol Aksi — berbeda per role --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">

                                    @if ($rolePengguna === 'petugas')
                                        {{-- ── AKSI PETUGAS ── --}}

                                        {{-- Tombol Edit --}}
                                        <button
                                            wire:click="bukaEdit({{ $ri->id }})"
                                            title="Edit data rawat inap"
                                            class="inline-flex items-center gap-1 bg-amber-50 hover:bg-amber-100
                                                   border border-amber-200 text-amber-700 font-semibold
                                                   text-xs px-2.5 py-1.5 rounded-lg transition-all
                                                   active:scale-95"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus — hanya jika belum ada transaksi --}}
                                        <button
                                            wire:click="hapus({{ $ri->id }})"
                                            wire:confirm="Yakin hapus data rawat inap ini? Tindakan tidak bisa dibatalkan."
                                            title="Hapus data rawat inap"
                                            class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100
                                                   border border-red-200 text-red-600 font-semibold
                                                   text-xs px-2.5 py-1.5 rounded-lg transition-all
                                                   active:scale-95"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>

                                    @elseif ($rolePengguna === 'dokter')
                                        {{-- ── AKSI DOKTER ── --}}

                                        <button
                                            wire:click="bukaDiagnosa({{ $ri->id }})"
                                            title="Input diagnosa medis"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                   text-xs font-semibold transition-all active:scale-95
                                                   border
                                                   {{ $ri->diagnosa
                                                       ? 'bg-green-50 hover:bg-green-100 border-green-200 text-green-700'
                                                       : 'bg-purple-50 hover:bg-purple-100 border-purple-200 text-purple-700 animate-pulse' }}"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                            {{ $ri->diagnosa ? 'Update' : 'Diagnosa' }}
                                        </button>

                                    @endif

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
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            @if($cari || $filterStatus)
                                                Tidak ada hasil yang sesuai
                                            @else
                                                Belum ada data rawat inap
                                            @endif
                                        </p>
                                        <p class="text-sm mt-1">
                                            @if($cari || $filterStatus)
                                                Coba ubah kata kunci atau filter status
                                            @elseif($rolePengguna === 'petugas')
                                                Klik "Daftarkan Rawat Inap" untuk memulai
                                            @else
                                                Belum ada pasien yang ditangani Anda
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
                Menampilkan {{ $rawatInap->count() }} dari {{ $rawatInap->total() }} data rawat inap
            </p>
            <div>{{ $rawatInap->links() }}</div>
        </div>

    </div>{{-- akhir kartu utama --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL FORM RAWAT INAP — khusus petugas          --}}
    {{-- Muncul saat $tampilForm && !$modeDiagnosa        --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if ($tampilForm && !$modeDiagnosa)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false)"
    >
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="$set('tampilForm', false)"></div>

        {{-- Kontainer modal --}}
        <div class="relative flex min-h-screen items-start justify-center p-4 pt-10">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[88vh] overflow-y-auto"
                x-data
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
                            {{ $modeEdit ? '✏️' : '🏥' }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $modeEdit ? 'Edit Data Rawat Inap' : 'Daftarkan Rawat Inap Baru' }}
                            </h3>
                            <p class="text-xs text-green-300">
                                {{ $modeEdit ? 'Perbarui informasi rawat inap' : 'Isi formulir pendaftaran pasien' }}
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
                <div class="px-7 py-6 space-y-5">

                    {{-- ── SEKSI 1: DATA PASIEN, DOKTER, KAMAR ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-green-100 text-green-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">1</span>
                            <p class="text-xs font-bold text-green-700 uppercase tracking-widest">
                                Data Perawatan
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-4">

                            {{-- Pasien --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Pasien <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="pasienId"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                           outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           transition-all @error('pasienId') border-red-400 @else border-slate-200 @enderror"
                                >
                                    <option value="">— Pilih pasien —</option>
                                    @foreach ($daftarPasien as $ps)
                                        <option value="{{ $ps->id }}">
                                            {{ $ps->pengguna->nama ?? '?' }}
                                            @if($ps->nomor_rm) ({{ $ps->nomor_rm }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('pasienId')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Dokter + Kamar (2 kolom) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Dokter Penanggung Jawab <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="dokterId"
                                        class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('dokterId') border-red-400 @else border-slate-200 @enderror"
                                    >
                                        <option value="">— Pilih dokter —</option>
                                        @foreach ($daftarDokter as $dk)
                                            <option value="{{ $dk->id }}">
                                                {{ $dk->pengguna->nama ?? '?' }}
                                                @if($dk->spesialisasi) · {{ $dk->spesialisasi }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dokterId')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Kamar Rawat Inap <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="kamarId"
                                        class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('kamarId') border-red-400 @else border-slate-200 @enderror"
                                    >
                                        <option value="">— Pilih kamar —</option>
                                        @foreach ($daftarKamar as $km)
                                            <option value="{{ $km->id }}">
                                                {{ $km->nomor_kamar }} — {{ $km->tipe_kamar }}
                                                (Rp {{ number_format($km->harga_malam, 0, ',', '.') }}/mlm)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kamarId')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    @if(count($daftarKamar) === 0)
                                        <p class="text-xs text-amber-600 mt-1">
                                            ⚠️ Tidak ada kamar tersedia saat ini.
                                        </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── SEKSI 2: WAKTU & STATUS ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">2</span>
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-widest">
                                Waktu & Status
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-4">

                            {{-- Tgl Masuk + Tgl Keluar (2 kolom) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Tanggal Masuk <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        wire:model="tanggalMasuk"
                                        type="date"
                                        max="{{ date('Y-m-d') }}"
                                        class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('tanggalMasuk') border-red-400 @else border-slate-200 @enderror"
                                    >
                                    @error('tanggalMasuk')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Tanggal Keluar
                                        <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                                    </label>
                                    <input
                                        wire:model="tanggalKeluar"
                                        type="date"
                                        class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('tanggalKeluar') border-red-400 @enderror"
                                    >
                                    @error('tanggalKeluar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Status Rawat Inap <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    @foreach ([
                                        ['dirawat',   '🏥', 'bg-blue-700',  'Dirawat'],
                                        ['selesai',   '✅', 'bg-green-700', 'Selesai'],
                                        ['dirujuk',   '🚑', 'bg-amber-600', 'Dirujuk'],
                                        ['meninggal', '🕊', 'bg-red-600',   'Meninggal'],
                                    ] as [$val, $ico, $clr, $lbl])
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="status" value="{{ $val }}" class="sr-only peer">
                                            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border-2
                                                        text-xs font-semibold transition-all duration-150
                                                        border-slate-200 text-slate-500 bg-white
                                                        peer-checked:border-transparent peer-checked:text-white peer-checked:{{ $clr }}
                                                        hover:border-slate-300">
                                                <span>{{ $ico }}</span> {{ $lbl }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ── SEKSI 3: KELUHAN PASIEN ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-amber-100 text-amber-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">3</span>
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-widest">
                                Keluhan Awal
                            </p>
                        </div>
                        <textarea
                            wire:model="keluhan"
                            rows="3"
                            placeholder="Tulis keluhan utama pasien saat masuk..."
                            class="w-full border border-slate-200 bg-white rounded-2xl px-4 py-3 text-sm
                                   outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                   transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- ── TOMBOL AKSI ── --}}
                    <div class="flex gap-3 pt-1">
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
                                {{ $modeEdit ? '💾  Simpan Perubahan' : '✅  Daftarkan Pasien' }}
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
                        >Batal</button>
                    </div>

                </div>{{-- akhir body form --}}
            </div>
        </div>
    </div>
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL DIAGNOSA DOKTER                           --}}
    {{-- Muncul saat $modeDiagnosa == true               --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if ($modeDiagnosa)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false); $wire.set('modeDiagnosa', false)"
    >
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="$set('tampilForm', false); $set('modeDiagnosa', false)"></div>

        {{-- Kontainer modal --}}
        <div class="relative flex min-h-screen items-start justify-center p-4 pt-10">
            <div
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[88vh] overflow-y-auto"
                x-data
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-on:click.stop
            >
                {{-- Header Modal Diagnosa — warna ungu untuk membedakan --}}
                <div class="sticky top-0 z-10 flex items-center justify-between
                            px-7 py-5 bg-purple-800 rounded-t-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-700 rounded-xl flex items-center
                                    justify-center text-xl">🩺</div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Input Diagnosa Medis</h3>
                            <p class="text-xs text-purple-300">
                                Form khusus dokter — catat diagnosa dan tindakan medis
                            </p>
                        </div>
                    </div>
                    <button
                        wire:click="$set('tampilForm', false); $set('modeDiagnosa', false)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-purple-200 hover:text-white hover:bg-purple-700
                               transition-all text-2xl font-bold leading-none"
                    >×</button>
                </div>

                {{-- Body Form Diagnosa --}}
                <div class="px-7 py-6 space-y-5">

                    {{-- Banner info dokter --}}
                    <div class="flex items-center gap-3 bg-purple-50 border border-purple-100
                                rounded-xl px-4 py-3">
                        <span class="text-2xl">🩺</span>
                        <div>
                            <p class="text-sm font-semibold text-purple-800">
                                dr. {{ auth()->user()->nama }}
                            </p>
                            <p class="text-xs text-purple-500">
                                Mengisi catatan medis untuk rawat inap #{{ $rawatInapId }}
                            </p>
                        </div>
                    </div>

                    {{-- ── DIAGNOSA UTAMA ── --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Diagnosa Medis <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            wire:model="diagnosa"
                            rows="3"
                            placeholder="Tuliskan diagnosa medis utama pasien secara lengkap..."
                            class="w-full border rounded-xl px-4 py-3 text-sm bg-white
                                   outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                   transition-all resize-none
                                   @error('diagnosa') border-red-400 bg-red-50 @else border-slate-200 @enderror"
                        ></textarea>
                        @error('diagnosa')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <span>⚠</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ── TINDAKAN MEDIS ── --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tindakan Medis
                            <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea
                            wire:model="tindakanMedis"
                            rows="3"
                            placeholder="Contoh: Pemasangan infus, pemberian antibiotik IV, fisioterapi..."
                            class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm
                                   outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                   transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- ── CATATAN DOKTER ── --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Catatan Tambahan Dokter
                            <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea
                            wire:model="catatanDokter"
                            rows="2"
                            placeholder="Instruksi khusus, observasi, atau catatan perkembangan pasien..."
                            class="w-full border border-slate-200 bg-white rounded-xl px-4 py-3 text-sm
                                   outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                   transition-all resize-none"
                        ></textarea>
                    </div>

                    {{-- ── STATUS PASIEN ── --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Perbarui Status Pasien <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach ([
                                ['dirawat',   '🏥', 'peer-checked:bg-blue-700',   'Dirawat'],
                                ['selesai',   '✅', 'peer-checked:bg-green-700',  'Selesai / Pulang'],
                                ['dirujuk',   '🚑', 'peer-checked:bg-amber-600',  'Dirujuk'],
                                ['meninggal', '🕊', 'peer-checked:bg-red-600',    'Meninggal'],
                            ] as [$val, $ico, $clr, $lbl])
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="status" value="{{ $val }}" class="sr-only peer">
                                    <div class="flex flex-col items-center gap-1 px-3 py-3 rounded-xl border-2
                                                text-xs font-semibold transition-all duration-150 text-center
                                                border-slate-200 text-slate-500 bg-white
                                                peer-checked:border-transparent peer-checked:text-white {{ $clr }}
                                                hover:border-slate-300">
                                        <span class="text-lg">{{ $ico }}</span>
                                        <span>{{ $lbl }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- Peringatan jika status mengakhiri rawat inap --}}
                        @if(in_array($status, ['selesai', 'dirujuk', 'meninggal']))
                            <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                                <p class="text-xs text-amber-800">
                                    ⚠️ <strong>Perhatian:</strong> Mengubah status ke
                                    <strong>{{ ucfirst($status) }}</strong> akan
                                    membebaskan kamar dan menetapkan tanggal keluar hari ini
                                    (jika belum diisi).
                                </p>
                            </div>
                        @endif

                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── TOMBOL SIMPAN DIAGNOSA ── --}}
                    <div class="flex gap-3 pt-1">
                        <button
                            wire:click="simpanDiagnosa"
                            wire:loading.attr="disabled"
                            wire:target="simpanDiagnosa"
                            class="flex-1 flex items-center justify-center gap-2
                                   bg-purple-700 hover:bg-purple-800 disabled:opacity-60
                                   text-white font-bold py-3 rounded-xl transition-all
                                   active:scale-[0.98] shadow-md shadow-purple-200"
                        >
                            <span wire:loading.remove wire:target="simpanDiagnosa">
                                💾  Simpan Diagnosa & Catatan Medis
                            </span>
                            <span wire:loading wire:target="simpanDiagnosa"
                                  class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                        <button
                            wire:click="$set('tampilForm', false); $set('modeDiagnosa', false)"
                            class="px-6 bg-slate-100 hover:bg-slate-200 text-slate-700
                                   font-semibold py-3 rounded-xl transition-all"
                        >Batal</button>
                    </div>

                </div>{{-- akhir body diagnosa --}}
            </div>
        </div>
    </div>
    @endif

</div>{{-- akhir komponen utama --}}