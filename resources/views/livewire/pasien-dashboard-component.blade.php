{{-- ══════════════════════════════════════════════════════════════════
     VIEW  : pasien-dashboard-component.blade.php
     Komponen: app/Livewire/PasienDashboardComponent.php
     Role   : pasien (view only — portal riwayat & tagihan pribadi)
     Tema   : Putih–Hijau Profesional — Portal Pasien Klinik
     ══════════════════════════════════════════════════════════════════ --}}

<div class="space-y-6">

    {{-- ════════════════════════════════════════════════ --}}
    {{-- 1. HEADER DASHBOARD PASIEN                      --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="bg-gradient-to-r from-green-700 to-green-800 rounded-2xl overflow-hidden shadow-md">
        <div class="px-7 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            {{-- Salam & Identitas --}}
            <div class="flex items-center gap-4">
                {{-- Avatar inisial --}}
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center
                            text-white text-2xl font-bold shrink-0">
                    {{ mb_strtoupper(mb_substr($pasien->pengguna->nama ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <p class="text-green-200 text-sm font-medium">Selamat datang kembali,</p>
                    <h1 class="text-white text-2xl font-bold tracking-tight">
                        {{ $pasien->pengguna->nama ?? 'Pasien' }}
                    </h1>
                    <p class="text-green-300 text-xs mt-0.5">
                        Portal Pasien — Klinik Rawat Inap
                    </p>
                </div>
            </div>

            {{-- Info No. RM & Status --}}
            <div class="flex flex-wrap gap-3">
                @if ($pasien->nomor_rm)
                    <div class="bg-white/15 rounded-xl px-4 py-2.5 text-center">
                        <p class="text-green-200 text-xs">No. Rekam Medis</p>
                        <p class="text-white font-bold font-mono text-sm mt-0.5">
                            {{ $pasien->nomor_rm }}
                        </p>
                    </div>
                @endif
                @if ($rawatInapAktif > 0)
                    <div class="bg-blue-500/80 border border-blue-300/40 rounded-xl px-4 py-2.5 text-center">
                        <p class="text-blue-100 text-xs">Status Saat Ini</p>
                        <p class="text-white font-bold text-sm mt-0.5 flex items-center gap-1.5 justify-center">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse inline-block"></span>
                            Dirawat
                        </p>
                    </div>
                @else
                    <div class="bg-white/15 rounded-xl px-4 py-2.5 text-center">
                        <p class="text-green-200 text-xs">Status Saat Ini</p>
                        <p class="text-white font-semibold text-sm mt-0.5">Tidak Dirawat</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Data singkat profil --}}
        <div class="px-7 py-3 bg-black/10 flex flex-wrap gap-x-6 gap-y-1">
            @if ($pasien->pengguna->email)
                <span class="text-green-200 text-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $pasien->pengguna->email }}
                </span>
            @endif
            @if ($pasien->no_telepon)
                <span class="text-green-200 text-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $pasien->no_telepon }}
                </span>
            @endif
            @if ($pasien->tanggal_lahir)
                <span class="text-green-200 text-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $pasien->tanggal_lahir->format('d M Y') }}
                    · {{ $pasien->umur ?? $pasien->tanggal_lahir->age }} tahun
                </span>
            @endif
            @if ($pasien->golongan_darah)
                <span class="text-green-200 text-xs flex items-center gap-1.5">
                    🩸 Gol. {{ $pasien->golongan_darah }}
                </span>
            @endif
        </div>
    </div>{{-- akhir header --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- 2. RAWAT INAP AKTIF — highlight jika ada        --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if ($rawatInapAktifDetail)
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-3 bg-blue-700 flex items-center justify-between">
                <div class="flex items-center gap-2 text-white font-bold">
                    <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
                    Rawat Inap Aktif Saat Ini
                </div>
                <span class="text-xs text-blue-200 font-medium">
                    Masuk: {{ $rawatInapAktifDetail->tanggal_masuk?->format('d M Y') }}
                    · {{ (int) $rawatInapAktifDetail->tanggal_masuk?->diffInDays(now()) }} hari
                </span>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide mb-1">Dokter</p>
                    <p class="font-bold text-slate-800">
                        {{ $rawatInapAktifDetail->dokter->pengguna->nama ?? '—' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $rawatInapAktifDetail->dokter->spesialisasi ?? '' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide mb-1">Kamar</p>
                    <p class="font-bold text-slate-800">
                        {{ $rawatInapAktifDetail->kamar->nomor_kamar ?? '—' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $rawatInapAktifDetail->kamar->tipe_kamar ?? '' }}
                    </p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide mb-1">Keluhan</p>
                    <p class="text-slate-700 text-sm leading-relaxed">
                        {{ $rawatInapAktifDetail->keluhan
                            ? Str::limit($rawatInapAktifDetail->keluhan, 120)
                            : '—' }}
                    </p>
                </div>
            </div>
            @if ($rawatInapAktifDetail->diagnosa)
                <div class="px-6 pb-5">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide mb-1">Diagnosa</p>
                    <p class="text-slate-700 text-sm bg-white rounded-xl px-4 py-2.5 border border-blue-100">
                        {{ $rawatInapAktifDetail->diagnosa }}
                    </p>
                </div>
            @endif
        </div>
    @else
        {{-- Tidak ada rawat inap aktif --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-5
                    flex items-center gap-4 text-slate-400">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-xl shrink-0">
                🏥
            </div>
            <div>
                <p class="font-semibold text-slate-500">Tidak ada rawat inap aktif saat ini</p>
                <p class="text-xs mt-0.5">Hubungi petugas klinik jika Anda perlu mendaftar rawat inap.</p>
            </div>
        </div>
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- 3. STATISTIK KESEHATAN — 4 kartu               --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div>
        <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-3">
            📊 Statistik Kesehatan Anda
        </h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-blue-500 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Rawat Inap</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalRawatInap }}</p>
                <p class="text-xs text-blue-500 mt-1">📋 Sepanjang waktu</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-green-500 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Selesai</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $rawatInapSelesai }}</p>
                <p class="text-xs text-green-500 mt-1">✅ Sudah pulang</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-amber-400 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Dirujuk</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $rawatInapDirujuk }}</p>
                <p class="text-xs text-amber-500 mt-1">🚑 Ke RS lain</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-purple-400 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Hari Dirawat</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalHariDirawat }}</p>
                <p class="text-xs text-purple-500 mt-1">📅 Kumulatif</p>
            </div>

        </div>
    </div>


    {{-- ════════════════════════════════════════════════ --}}
    {{-- 4. STATISTIK TAGIHAN — 4 kartu keuangan         --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div>
        <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-3">
            💰 Ringkasan Tagihan
        </h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-slate-400 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Tagihan</p>
                <p class="text-xl font-bold text-slate-800 mt-1 leading-tight">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 mt-1">📄 Semua tagihan</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-green-500 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Sudah Lunas</p>
                <p class="text-xl font-bold text-green-700 mt-1 leading-tight">
                    Rp {{ number_format($tagihanLunas, 0, ',', '.') }}
                </p>
                <p class="text-xs text-green-500 mt-1">✅ Terbayar</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-red-400 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Belum Bayar</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $tagihanBelumBayar }}</p>
                <p class="text-xs text-red-500 mt-1">⏳ Tagihan</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                        border-l-4 border-l-amber-400 hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Cicilan</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $tagihanCicilan }}</p>
                <p class="text-xs text-amber-500 mt-1">💳 Bertahap</p>
            </div>

        </div>
    </div>


    {{-- ════════════════════════════════════════════════ --}}
    {{-- 5. TABEL RIWAYAT RAWAT INAP                     --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Header tabel --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold tracking-tight text-green-900">
                    📋 Riwayat Rawat Inap
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Seluruh riwayat perawatan Anda di klinik
                </p>
            </div>
            <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full font-medium">
                {{ $riwayatRawatInap->total() }} total data
            </span>
        </div>

        {{-- Filter & Pencarian --}}
        <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 bg-slate-50/50 border-b border-slate-100">

            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="cari"
                    type="text"
                    placeholder="Cari nama dokter..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all"
                >
            </div>

            <select
                wire:model.live="filterStatus"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all min-w-[175px]"
            >
                <option value="">Semua Status</option>
                <option value="dirawat">🏥 Dirawat</option>
                <option value="selesai">✅ Selesai</option>
                <option value="dirujuk">🚑 Dirujuk</option>
                <option value="meninggal">🕊 Meninggal</option>
            </select>

            <div wire:loading wire:target="cari,filterStatus"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Memuat...
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto"
             wire:loading.class="opacity-50"
             wire:target="cari,filterStatus">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold">Tgl Masuk</th>
                        <th class="px-5 py-4 text-left font-semibold">Tgl Keluar</th>
                        <th class="px-5 py-4 text-left font-semibold">Dokter</th>
                        <th class="px-5 py-4 text-left font-semibold">Kamar</th>
                        <th class="px-5 py-4 text-center font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Diagnosa</th>
                        <th class="px-5 py-4 text-right font-semibold">Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse ($riwayatRawatInap as $ri)

                        {{-- Highlight baris jika sedang aktif dirawat --}}
                        <tr class="transition-colors duration-150
                            {{ $ri->status === 'dirawat' ? 'bg-blue-50/60 hover:bg-blue-50' : 'hover:bg-green-50/50' }}">

                            {{-- Tanggal Masuk --}}
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-700">
                                    {{ $ri->tanggal_masuk?->format('d M Y') ?? '—' }}
                                </div>
                                @if ($ri->status === 'dirawat')
                                    <div class="text-xs text-blue-500 font-medium mt-0.5">
                                        {{ (int) $ri->tanggal_masuk?->diffInDays(now()) }} hari lalu
                                    </div>
                                @endif
                            </td>

                            {{-- Tanggal Keluar --}}
                            <td class="px-5 py-3.5">
                                @if ($ri->tanggal_keluar)
                                    <div class="text-slate-600">
                                        {{ $ri->tanggal_keluar->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        {{ $ri->tanggal_masuk->diffInDays($ri->tanggal_keluar) }} hari
                                    </div>
                                @elseif ($ri->status === 'dirawat')
                                    <span class="inline-flex items-center gap-1 text-xs text-blue-600 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Masih dirawat
                                    </span>
                                @else
                                    <span class="text-slate-300 italic text-xs">—</span>
                                @endif
                            </td>

                            {{-- Dokter --}}
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-800">
                                    {{ $ri->dokter->pengguna->nama ?? '—' }}
                                </div>
                                @if ($ri->dokter?->spesialisasi)
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        {{ $ri->dokter->spesialisasi }}
                                    </div>
                                @endif
                            </td>

                            {{-- Kamar --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-bold text-green-800 bg-green-50
                                             border border-green-200 px-2 py-1 rounded-lg text-xs">
                                    {{ $ri->kamar->nomor_kamar ?? '—' }}
                                </span>
                                @if ($ri->kamar?->tipe_kamar)
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $ri->kamar->tipe_kamar }}
                                    </p>
                                @endif
                            </td>

                            {{-- Badge Status --}}
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $badge = match($ri->status) {
                                        'dirawat'   => ['bg-blue-100 text-blue-700 border-blue-200',   'Dirawat',   true],
                                        'selesai'   => ['bg-green-100 text-green-700 border-green-200', 'Selesai',  false],
                                        'dirujuk'   => ['bg-amber-100 text-amber-700 border-amber-200', 'Dirujuk',  false],
                                        'meninggal' => ['bg-red-100 text-red-600 border-red-200',       'Meninggal',false],
                                        default     => ['bg-slate-100 text-slate-600 border-slate-200', $ri->status,false],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                             text-xs font-semibold border {{ $badge[0] }}">
                                    @if ($badge[2])
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    @endif
                                    {{ $badge[1] }}
                                </span>
                            </td>

                            {{-- Diagnosa --}}
                            <td class="px-5 py-3.5 max-w-[200px]">
                                @if ($ri->diagnosa)
                                    <p class="text-slate-600 text-xs leading-relaxed"
                                       title="{{ $ri->diagnosa }}">
                                        {{ Str::limit($ri->diagnosa, 50) }}
                                    </p>
                                @else
                                    <span class="text-xs text-amber-500 italic">⚠ Belum ada diagnosa</span>
                                @endif
                            </td>

                            {{-- Detail Tagihan --}}
                            <td class="px-5 py-3.5 text-right">
                                @if ($ri->transaksi)
                                    <div class="font-bold text-slate-800">
                                        Rp {{ number_format($ri->transaksi->total_biaya, 0, ',', '.') }}
                                    </div>
                                    @php
                                        $tagBadge = match($ri->transaksi->status_bayar) {
                                            'lunas'      => 'bg-green-100 text-green-700',
                                            'cicilan'    => 'bg-amber-100 text-amber-700',
                                            default      => 'bg-red-100 text-red-600',
                                        };
                                        $tagLabel = match($ri->transaksi->status_bayar) {
                                            'lunas'      => 'Lunas',
                                            'cicilan'    => 'Cicilan',
                                            default      => 'Belum Bayar',
                                        };
                                    @endphp
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full
                                                 text-xs font-semibold {{ $tagBadge }}">
                                        {{ $tagLabel }}
                                    </span>
                                    @if ($ri->transaksi->cara_bayar)
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ ucfirst($ri->transaksi->cara_bayar) }}
                                        </p>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada tagihan</span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        {{-- State Kosong --}}
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-16 h-16 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            @if ($cari || $filterStatus)
                                                Tidak ada hasil yang sesuai
                                            @else
                                                Belum ada riwayat rawat inap
                                            @endif
                                        </p>
                                        <p class="text-sm mt-1">
                                            @if ($cari || $filterStatus)
                                                Coba ubah kata kunci atau filter status
                                            @else
                                                Data riwayat perawatan Anda akan muncul di sini
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

        {{-- Footer + Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $riwayatRawatInap->count() }}
                dari {{ $riwayatRawatInap->total() }} riwayat rawat inap
            </p>
            <div>
                {{ $riwayatRawatInap->links() }}
            </div>
        </div>

    </div>{{-- akhir tabel --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- 6. FOOTER INFO PROFIL LENGKAP                   --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-700">👤 Informasi Profil Saya</h2>
        </div>
        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">

            @php
                $infoItem = function(string $label, ?string $nilai, string $icon = '') {
                    return ['label' => $label, 'nilai' => $nilai, 'icon' => $icon];
                };
                $items = [
                    ['label'=>'Nama Lengkap',    'nilai'=> $pasien->pengguna->nama ?? '—',     'icon'=>'👤'],
                    ['label'=>'Email',            'nilai'=> $pasien->pengguna->email ?? '—',    'icon'=>'✉️'],
                    ['label'=>'No. Telepon',      'nilai'=> $pasien->no_telepon ?? '—',         'icon'=>'📱'],
                    ['label'=>'Jenis Kelamin',    'nilai'=> $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : ($pasien->jenis_kelamin === 'P' ? 'Perempuan' : '—'), 'icon'=>'🚻'],
                    ['label'=>'Tanggal Lahir',    'nilai'=> $pasien->tanggal_lahir?->format('d M Y') ?? '—', 'icon'=>'🎂'],
                    ['label'=>'Golongan Darah',   'nilai'=> $pasien->golongan_darah ?? '—',     'icon'=>'🩸'],
                    ['label'=>'Nama Penjamin',    'nilai'=> $pasien->nama_penjamin ?? '—',      'icon'=>'👪'],
                    ['label'=>'Telp. Penjamin',   'nilai'=> $pasien->no_telepon_penjamin ?? '—','icon'=>'📞'],
                    ['label'=>'Alamat',           'nilai'=> $pasien->alamat ?? '—',             'icon'=>'🏠'],
                ];
            @endphp

            @foreach ($items as $item)
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">
                        {{ $item['icon'] }} {{ $item['label'] }}
                    </p>
                    <p class="text-sm font-medium text-slate-700
                               {{ $item['nilai'] === '—' ? 'text-slate-300 italic' : '' }}">
                        {{ $item['nilai'] }}
                    </p>
                </div>
            @endforeach

        </div>
    </div>

</div>{{-- akhir komponen utama --}}