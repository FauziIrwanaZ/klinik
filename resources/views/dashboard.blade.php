{{--
|═══════════════════════════════════════════════════════════════════════════════
| dashboard.blade.php — Sistem Informasi Klinik Rawat Inap
|═══════════════════════════════════════════════════════════════════════════════
|
| Role yang ditangani: admin | petugas | dokter | pasien
|
| Komponen yang digunakan:
|   <x-dashboard.header    />  → resources/views/components/dashboard/header.blade.php
|   <x-dashboard.card-stat />  → resources/views/components/dashboard/card-stat.blade.php
|   <x-dashboard.panel     />  → resources/views/components/dashboard/panel.blade.php
|   <x-dashboard.empty-state/> → resources/views/components/dashboard/empty-state.blade.php
|
| Tidak ada perubahan pada backend / controller — hanya frontend.
|═══════════════════════════════════════════════════════════════════════════════
--}}

@extends('layouts.app')

@section('judul', 'Dashboard')
@section('header', 'Dashboard')
@section('sub-header', 'Ringkasan sistem klinik rawat inap')

@section('konten')

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- INLINE STYLES — scoped to this page only                               --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<style>
    /* Status badge pulse for "dirawat" state */
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }
    .status-pulse { animation: pulse-dot 2s ease-in-out infinite; }

    /* Skeleton shimmer */
    @keyframes shimmer {
        0%   { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 800px 100%;
        animation: shimmer 1.4s ease-in-out infinite;
    }
    .dark .skeleton {
        background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
        background-size: 800px 100%;
    }

    /* Row hover highlight */
    .data-row { transition: background-color .12s ease; }
    .data-row:hover { background-color: rgba(20, 83, 45, .04); }
    .dark .data-row:hover { background-color: rgba(74, 222, 128, .05); }
</style>

{{-- ─────────────────────────────────────────────────────────────────────── --}}
{{-- LIVEWIRE SKELETON LOADING OVERLAY (shown during any wire loading)      --}}
{{-- ─────────────────────────────────────────────────────────────────────── --}}
<div wire:loading.delay
     class="fixed inset-0 z-50 bg-white/40 dark:bg-black/40 backdrop-blur-sm
            flex items-center justify-center pointer-events-none"
     aria-live="polite"
     aria-label="Sedang memuat data...">
    <div class="flex flex-col items-center gap-3 bg-white dark:bg-slate-800
                rounded-2xl px-8 py-6 shadow-xl border border-slate-100 dark:border-slate-700">
        <svg class="w-8 h-8 animate-spin text-green-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Memuat data…</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- ROLE SWITCH — Main entry point                                         --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
@switch($role)

    {{-- ─────────────────────────── ADMIN ─────────────────────────────── --}}
    @case('admin')

        {{-- Greeting header --}}
        <x-dashboard.header
            greeting="Selamat datang"
            :name="auth()->user()->nama"
            subtitle="Berikut adalah ringkasan operasional klinik hari ini."
            icon="👑"
            role="admin"
        />

        {{-- ── Baris 1: 4 stat cards utama ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

            <x-dashboard.card-stat
                title="Total Pasien"
                :value="$data['total_pasien']"
                desc="Pasien terdaftar"
                color="green"
                :delay="0"
                :href="route('admin.pasien')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
            />

            <x-dashboard.card-stat
                title="Total Dokter"
                :value="$data['total_dokter']"
                desc="Aktif praktik"
                color="blue"
                :delay="80"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>'
            />

            <x-dashboard.card-stat
                title="Sedang Dirawat"
                :value="$data['sedang_dirawat']"
                desc="Pasien aktif"
                color="orange"
                :delay="160"
                :href="route('rawat-inap')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'
            />

            <x-dashboard.card-stat
                title="Kamar Tersedia"
                :value="$data['kamar_tersedia']"
                desc="Siap digunakan"
                color="emerald"
                :delay="240"
                :href="route('admin.kamar')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>'
            />
        </div>

        {{-- ── Baris 2: 3 stat pendukung ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            {{-- Revenue card — solid brand green background --}}
            <div x-data
                 x-init="setTimeout(() => { $el.style.opacity='1'; $el.style.transform='translateY(0)' }, 320)"
                 style="opacity:0; transform:translateY(10px); transition: opacity .4s ease, transform .4s ease"
                 class="relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-green-700 to-emerald-800
                        p-6 shadow-md text-white"
                 role="region"
                 aria-label="Pendapatan bulan ini">
                {{-- Decorative circle --}}
                <div class="absolute -right-6 -top-6 w-28 h-28 bg-white/10 rounded-full" aria-hidden="true"></div>
                <div class="absolute -right-2 bottom-2 w-14 h-14 bg-white/5 rounded-full" aria-hidden="true"></div>

                <p class="text-xs font-bold text-green-200 uppercase tracking-widest mb-2">
                    Pendapatan Bulan Ini
                </p>
                <p class="text-2xl font-extrabold leading-tight tabular-nums">
                    Rp {{ number_format($data['pendapatan_bulan'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-green-300 mt-2 font-medium">Transaksi lunas</p>

                <div class="mt-4 pt-3 border-t border-white/20 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="text-xs font-semibold text-green-200">Lihat transaksi</span>
                </div>
            </div>

            <x-dashboard.card-stat
                title="Selesai Hari Ini"
                :value="$data['selesai_hari_ini']"
                desc="Pasien dipulangkan"
                color="teal"
                :delay="400"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            />

            <x-dashboard.card-stat
                title="Kamar Terisi"
                :value="$data['kamar_terisi']"
                desc="Sedang digunakan"
                color="red"
                :delay="480"
                :href="route('admin.kamar')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'
            />
        </div>

        {{-- ── Tabel Rawat Inap Terbaru ── --}}
        <x-dashboard.panel
            title="Rawat Inap Terbaru"
            :href="route('rawat-inap')"
            link-label="Lihat semua"
            color="green"
            :delay="560">

            @if($data['rawat_inap_terbaru']->isEmpty())
                <x-dashboard.empty-state
                    title="Belum ada data rawat inap"
                    message="Data pasien yang dirawat akan muncul di sini secara otomatis."
                    color="green"
                />
            @else
                {{-- Table header --}}
                <div class="hidden md:grid grid-cols-5 px-5 py-2.5
                            bg-slate-50 dark:bg-slate-700/40
                            text-[10px] font-bold uppercase tracking-widest
                            text-slate-400 dark:text-slate-500"
                     aria-hidden="true">
                    <span>Pasien</span>
                    <span>Dokter</span>
                    <span>Kamar</span>
                    <span>Tgl Masuk</span>
                    <span>Status</span>
                </div>

                @foreach($data['rawat_inap_terbaru'] as $ri)
                    <div class="data-row px-5 py-3.5 grid grid-cols-1 md:grid-cols-5
                                gap-1 md:gap-0 md:items-center"
                         role="row">

                        {{-- Pasien --}}
                        <div class="flex items-center gap-2">
                            {{-- Avatar initial --}}
                            <div class="flex-shrink-0 w-7 h-7 rounded-full
                                        bg-green-100 dark:bg-green-900/40
                                        flex items-center justify-center
                                        text-[10px] font-bold text-green-700 dark:text-green-300"
                                 aria-hidden="true">
                                {{ strtoupper(substr($ri->pasien->nama, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $ri->pasien->nama }}
                            </span>
                        </div>

                        {{-- Dokter --}}
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $ri->dokter->nama_lengkap }}
                        </span>

                        {{-- Kamar --}}
                        <span>
                            <code class="text-xs font-mono px-2 py-0.5 rounded-lg
                                         bg-slate-100 dark:bg-slate-700
                                         text-slate-600 dark:text-slate-300">
                                {{ $ri->kamar->nomor_kamar }}
                            </code>
                        </span>

                        {{-- Tanggal --}}
                        <time datetime="{{ $ri->tanggal_masuk->toDateString() }}"
                              class="text-xs text-slate-400 dark:text-slate-500">
                            {{ $ri->tanggal_masuk->format('d M Y') }}
                        </time>

                        {{-- Status badge --}}
                        <span>
                            @if($ri->status === 'dirawat')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold bg-blue-100 text-blue-700
                                             dark:bg-blue-900/30 dark:text-blue-300">
                                    <span class="status-pulse w-1.5 h-1.5 rounded-full bg-blue-500" aria-hidden="true"></span>
                                    {{ $ri->status_label }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold bg-green-100 text-green-700
                                             dark:bg-green-900/30 dark:text-green-300">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $ri->status_label }}
                                </span>
                            @endif
                        </span>
                    </div>
                @endforeach
            @endif
        </x-dashboard.panel>

    @break

    {{-- ─────────────────────────── PETUGAS ────────────────────────────── --}}
    @case('petugas')

        <x-dashboard.header
            greeting="Selamat datang"
            :name="auth()->user()->nama"
            subtitle="Kelola operasional klinik — pasien, kamar, dan tagihan."
            icon="📋"
            role="petugas"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            <x-dashboard.card-stat
                title="Sedang Dirawat"
                :value="$data['sedang_dirawat']"
                desc="Pasien aktif"
                color="blue"
                :delay="0"
                :href="route('rawat-inap')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>'
            />

            <x-dashboard.card-stat
                title="Kamar Tersedia"
                :value="$data['kamar_tersedia']"
                desc="Siap digunakan"
                color="green"
                :delay="80"
                :href="route('admin.kamar')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>'
            />

            <x-dashboard.card-stat
                title="Kamar Terisi"
                :value="$data['kamar_terisi']"
                desc="Sedang digunakan"
                color="red"
                :delay="160"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'
            />

            <x-dashboard.card-stat
                title="Tagihan Pending"
                :value="$data['tagihan_pending']"
                desc="Menunggu pembayaran"
                color="amber"
                :delay="240"
                :href="route('admin.transaksi')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'
            />
        </div>

        {{-- Daftar pasien aktif --}}
        <x-dashboard.panel
            title="Pasien Aktif Dirawat"
            :href="route('rawat-inap')"
            link-label="Kelola"
            color="green"
            :delay="320">

            @forelse($data['rawat_inap_aktif'] as $ri)
                <div class="data-row flex items-center justify-between px-5 py-4 gap-4">
                    {{-- Kiri: nama + kamar --}}
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl
                                    bg-blue-100 dark:bg-blue-900/40
                                    flex items-center justify-center
                                    text-sm font-bold text-blue-600 dark:text-blue-300"
                             aria-hidden="true">
                            {{ strtoupper(substr($ri->pasien->nama, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">
                                {{ $ri->pasien->nama }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                Kamar
                                <code class="font-mono text-slate-500 dark:text-slate-400">{{ $ri->kamar->nomor_kamar }}</code>
                                · {{ $ri->tipe_kamar }}
                            </p>
                        </div>
                    </div>

                    {{-- Kanan: tanggal + lama --}}
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            Masuk {{ $ri->tanggal_masuk->format('d M') }}
                        </p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-bold
                                     bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300">
                            {{ $ri->lama_dirawat }} hari
                        </span>
                    </div>
                </div>
            @empty
                <x-dashboard.empty-state
                    title="Tidak ada pasien aktif"
                    message="Semua pasien telah dipulangkan atau belum ada pendaftaran rawat inap baru."
                    action="Tambah Rawat Inap"
                    :href="route('rawat-inap')"
                    color="blue"
                />
            @endforelse
        </x-dashboard.panel>

    @break

    {{-- ─────────────────────────── DOKTER ─────────────────────────────── --}}
    @case('dokter')

        <x-dashboard.header
            greeting="Selamat pagi"
            :name="'dr. ' . auth()->user()->nama"
            subtitle="Lihat daftar pasien Anda yang sedang dirawat."
            icon="🩺"
            role="dokter"
        />

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            {{-- Featured card: pasien aktif --}}
            <div x-data
                 x-init="setTimeout(() => { $el.style.opacity='1'; $el.style.transform='translateY(0)' }, 0)"
                 style="opacity:0; transform:translateY(10px); transition: opacity .4s ease, transform .4s ease"
                 class="relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-violet-700 to-purple-800
                        p-6 shadow-md text-white"
                 role="region"
                 aria-label="Pasien aktif saya">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full" aria-hidden="true"></div>

                <p class="text-xs font-bold text-violet-200 uppercase tracking-widest mb-1">
                    Pasien Aktif Saya
                </p>
                <p class="text-5xl font-extrabold tabular-nums">{{ $data['pasien_aktif'] }}</p>
                <p class="text-xs text-violet-300 mt-2">Sedang dirawat</p>
            </div>

            <x-dashboard.card-stat
                title="Total Pasien Ditangani"
                :value="$data['total_pasien']"
                desc="Sepanjang waktu"
                color="blue"
                :delay="80"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
            />

            <x-dashboard.card-stat
                title="Selesai Bulan Ini"
                :value="$data['selesai_bulan']"
                desc="Pasien dipulangkan"
                color="teal"
                :delay="160"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            />
        </div>

        {{-- Daftar pasien aktif dokter --}}
        <x-dashboard.panel
            title="Pasien Aktif Saya"
            :href="route('rawat-inap')"
            link-label="Input Diagnosa"
            color="violet"
            :delay="240">

            @forelse($data['pasien_terbaru'] as $ri)
                <div class="data-row flex items-start justify-between px-5 py-4 gap-4">
                    {{-- Kiri: pasien + diagnosa --}}
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="flex-shrink-0 w-9 h-9 rounded-xl
                                    bg-violet-100 dark:bg-violet-900/40
                                    flex items-center justify-center
                                    text-sm font-bold text-violet-600 dark:text-violet-300"
                             aria-hidden="true">
                            {{ strtoupper(substr($ri->pasien->nama, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">
                                {{ $ri->pasien->nama }}
                            </p>
                            @if($ri->diagnosa)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate max-w-xs">
                                    {{ Str::limit($ri->diagnosa, 48) }}
                                </p>
                            @else
                                {{-- Warning: belum ada diagnosa --}}
                                <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-full
                                             text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30
                                             text-amber-700 dark:text-amber-300"
                                      role="alert">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                                    </svg>
                                    Belum ada diagnosa
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Kanan: kamar + lama dirawat --}}
                    <div class="flex-shrink-0 text-right">
                        <code class="text-xs font-mono px-2 py-0.5 rounded-lg
                                     bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            {{ $ri->kamar->nomor_kamar }}
                        </code>
                        <p class="text-xs text-violet-600 dark:text-violet-400 font-bold mt-1">
                            {{ $ri->lama_dirawat }} hari
                        </p>
                    </div>
                </div>
            @empty
                <x-dashboard.empty-state
                    title="Tidak ada pasien aktif"
                    message="Anda belum memiliki pasien yang sedang dirawat saat ini."
                    color="blue"
                />
            @endforelse
        </x-dashboard.panel>

    @break

    {{-- ─────────────────────────── PASIEN ─────────────────────────────── --}}
    @case('pasien')
        @php $profil = auth()->user()->pasien; @endphp

        <x-dashboard.header
            greeting="Halo"
            :name="auth()->user()->nama"
            subtitle="Lihat riwayat perawatan dan tagihan Anda di bawah ini."
            icon="👋"
            role="pasien"
        />

        {{-- ── Profil Card (jika ada data pasien) ── --}}
        @if($profil)
            <div x-data
                 x-init="setTimeout(() => { $el.style.opacity='1'; $el.style.transform='translateY(0)' }, 0)"
                 style="opacity:0; transform:translateY(10px); transition: opacity .45s ease, transform .45s ease"
                 class="relative overflow-hidden rounded-2xl mb-6
                        bg-gradient-to-br from-green-800 to-emerald-900
                        p-6 shadow-md text-white"
                 role="region"
                 aria-label="Profil pasien">

                {{-- Decorative blobs --}}
                <div class="absolute right-0 top-0 w-40 h-40 rounded-full
                            bg-white/5 -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
                <div class="absolute right-12 bottom-0 w-20 h-20 rounded-full
                            bg-white/5 translate-y-1/2" aria-hidden="true"></div>

                <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-5">

                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl
                                bg-white/15 border border-white/20
                                flex items-center justify-center
                                text-3xl font-extrabold select-none shadow-inner"
                         aria-hidden="true">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xl font-extrabold">{{ auth()->user()->nama }}</h4>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                            <span class="text-sm text-green-200">
                                No. RM: <strong class="text-white font-bold">{{ $profil->nomor_rm }}</strong>
                            </span>
                            @if($profil->golongan_darah)
                                <span class="text-sm text-green-200">
                                    Gol. Darah: <strong class="text-white font-bold">{{ $profil->golongan_darah }}</strong>
                                </span>
                            @endif
                            <span class="text-sm text-green-200">{{ $profil->umur }}</span>
                            <span class="text-sm text-green-200">{{ $profil->jenis_kelamin_label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── 4 Stat cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            <x-dashboard.card-stat
                title="Total Riwayat"
                :value="$data['riwayat_total']"
                desc="Rawat inap"
                color="green"
                :delay="0"
                :href="route('pasien.riwayat')"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
            />

            <x-dashboard.card-stat
                title="Aktif"
                :value="$data['aktif']"
                desc="Sedang dirawat"
                color="blue"
                :delay="80"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>'
            />

            <x-dashboard.card-stat
                title="Tagihan Lunas"
                :value="$data['tagihan_lunas']"
                desc="Sudah dibayar"
                color="emerald"
                :delay="160"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            />

            <x-dashboard.card-stat
                title="Tagihan Pending"
                :value="$data['tagihan_pending']"
                desc="Menunggu pembayaran"
                color="amber"
                :delay="240"
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            />
        </div>

        {{-- ── Riwayat Perawatan ── --}}
        <x-dashboard.panel
            title="Riwayat Perawatan Terbaru"
            :href="route('pasien.riwayat')"
            link-label="Lihat semua"
            color="green"
            :delay="320">

            @forelse($data['riwayat'] as $ri)
                <div class="data-row px-5 py-4" role="row">
                    <div class="flex items-start justify-between gap-4">

                        {{-- Kiri: dokter + kamar + diagnosa --}}
                        <div class="flex items-start gap-3 min-w-0">
                            {{-- Dokter avatar --}}
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl mt-0.5
                                        bg-green-100 dark:bg-green-900/40
                                        flex items-center justify-center
                                        text-sm font-bold text-green-700 dark:text-green-300"
                                 aria-hidden="true">
                                {{ strtoupper(substr($ri->dokter->nama_lengkap, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $ri->dokter->nama_lengkap }}
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                    Kamar
                                    <code class="font-mono">{{ $ri->kamar->nomor_kamar }}</code>
                                    ·
                                    <time datetime="{{ $ri->tanggal_masuk->toDateString() }}">
                                        Masuk {{ $ri->tanggal_masuk->format('d M Y') }}
                                    </time>
                                </p>
                                @if($ri->diagnosa)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 italic">
                                        "{{ Str::limit($ri->diagnosa, 55) }}"
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Kanan: status + tagihan --}}
                        <div class="flex-shrink-0 flex flex-col items-end gap-1.5">
                            {{-- Status badge --}}
                            @if($ri->status === 'dirawat')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold bg-blue-100 text-blue-700
                                             dark:bg-blue-900/30 dark:text-blue-300">
                                    <span class="status-pulse w-1.5 h-1.5 rounded-full bg-blue-500" aria-hidden="true"></span>
                                    {{ $ri->status_label }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold bg-green-100 text-green-700
                                             dark:bg-green-900/30 dark:text-green-300">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $ri->status_label }}
                                </span>
                            @endif

                            {{-- Tagihan --}}
                            @if($ri->transaksi)
                                <span class="text-xs font-bold
                                    {{ $ri->transaksi->status_bayar === 'lunas'
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $ri->transaksi->total_format }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <x-dashboard.empty-state
                    title="Belum ada riwayat perawatan"
                    message="Riwayat rawat inap Anda akan ditampilkan di sini setelah ada pendaftaran."
                    color="green"
                />
            @endforelse
        </x-dashboard.panel>

    @break

    {{-- ── Fallback (role tidak dikenali) ── --}}
    @default
        <div class="flex items-center justify-center h-64" role="alert">
            <p class="text-slate-400 text-sm">Role tidak dikenali. Silakan hubungi administrator.</p>
        </div>

@endswitch

@endsection