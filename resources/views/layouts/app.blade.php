<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Klinik Rawat Inap') — Klinik Sehat Bersama</title>
 
    {{-- Font Inter via Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
 
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-aktif { @apply bg-green-700 text-white; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #14532d; border-radius: 3px; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased">
 
<div class="flex h-screen overflow-hidden">
 
    {{-- ═══════════════════════════════════════════ --}}
    {{-- SIDEBAR --}}
    {{-- ═══════════════════════════════════════════ --}}
    <aside class="w-64 bg-green-900 text-white flex flex-col flex-shrink-0 shadow-2xl">
 
        {{-- Logo / Nama Klinik --}}
        <div class="px-5 py-6 border-b border-green-800">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🏥</span>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Klinik Sehat</h1>
                    <p class="text-xs text-green-300">Rawat Inap Bersama</p>
                </div>
            </div>
        </div>
 
        {{-- Info Pengguna Login --}}
        <div class="px-5 py-4 bg-green-800/50 border-b border-green-800">
            <p class="text-sm font-semibold truncate">{{ auth()->user()->nama }}</p>
            <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block font-medium
                {{ match(auth()->user()->role) {
                    'admin'   => 'bg-yellow-400 text-yellow-900',
                    'petugas' => 'bg-blue-400 text-blue-900',
                    'dokter'  => 'bg-purple-400 text-purple-900',
                    'pasien'  => 'bg-green-400 text-green-900',
                    default   => 'bg-gray-400 text-gray-900',
                } }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
 
        {{-- Navigasi Utama --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
 
            {{-- Dashboard (semua role) --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span class="text-lg">🏠</span> Dashboard
            </a>
 
            {{-- ── ADMIN ── --}}
            @if(auth()->user()->isAdmin())
            <div class="pt-3 pb-1">
                <p class="px-4 text-xs font-bold text-green-400 uppercase tracking-widest">Admin</p>
            </div>
            <a href="{{ route('admin.pasien') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ request()->routeIs('admin.pasien') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>👥</span> Data Pasien
            </a>
            <a href="{{ route('admin.kamar') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('admin.kamar') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>🛏</span> Data Kamar
            </a>
            <a href="{{ route('admin.transaksi') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('admin.transaksi') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>💰</span> Transaksi
            </a>
            <a href="{{ route('rawat-inap') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('rawat-inap') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>🏥</span> Rawat Inap
            </a>
            @endif
 
            {{-- ── PETUGAS ── --}}
            @if(auth()->user()->isPetugas())
            <div class="pt-3 pb-1">
                <p class="px-4 text-xs font-bold text-green-400 uppercase tracking-widest">Petugas</p>
            </div>
            <a href="{{ route('admin.pasien') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('admin.pasien') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>👥</span> Data Pasien
            </a>
            <a href="{{ route('admin.kamar') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('admin.kamar') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>🛏</span> Data Kamar
            </a>
            <a href="{{ route('rawat-inap') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('rawat-inap') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>🏥</span> Rawat Inap
            </a>
            <a href="{{ route('admin.transaksi') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('admin.transaksi') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>💰</span> Transaksi
            </a>
            @endif
 
            {{-- ── DOKTER ── --}}
            @if(auth()->user()->isDokter())
            <div class="pt-3 pb-1">
                <p class="px-4 text-xs font-bold text-green-400 uppercase tracking-widest">Dokter</p>
            </div>
            <a href="{{ route('rawat-inap') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('rawat-inap') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>🩺</span> Pasien Saya
            </a>
            @endif
 
            {{-- ── PASIEN ── --}}
            @if(auth()->user()->isPasien())
            <div class="pt-3 pb-1">
                <p class="px-4 text-xs font-bold text-green-400 uppercase tracking-widest">Pasien</p>
            </div>
            <a href="{{ route('pasien.riwayat') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs('pasien.riwayat') ? 'bg-green-700 text-white shadow' : 'text-green-100 hover:bg-green-700/60' }}">
                <span>📋</span> Riwayat Perawatan
            </a>
            @endif
        </nav>
 
        {{-- Tombol Logout --}}
        <div class="p-4 border-t border-green-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-green-800 hover:bg-red-700 text-white text-sm
                               font-semibold py-2.5 px-4 rounded-xl transition-colors duration-200">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>
 
    {{-- ═══════════════════════════════════════════ --}}
    {{-- AREA KONTEN UTAMA --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">
 
        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between flex-shrink-0 shadow-sm">
            <div>
                <h2 class="text-xl font-bold text-slate-800">@yield('header', 'Dashboard')</h2>
                <p class="text-xs text-slate-400 mt-0.5">@yield('sub-header', 'Sistem Informasi Klinik Rawat Inap')</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500">
                    📅 {{ now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </header>
 
        {{-- Flash Message Global --}}
        <div class="px-6 pt-4">
            @if(session('sukses'))
                <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
                     x-transition class="mb-4 flex gap-3 items-center bg-green-50 border border-green-300
                     text-green-800 px-5 py-3 rounded-xl shadow-sm">
                    <span>✅</span>
                    <span class="flex-1 font-medium">{{ session('sukses') }}</span>
                    <button @click="show=false" class="font-bold text-lg">×</button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
                     x-transition class="mb-4 flex gap-3 items-center bg-red-50 border border-red-300
                     text-red-800 px-5 py-3 rounded-xl shadow-sm">
                    <span>❌</span>
                    <span class="flex-1 font-medium">{{ session('error') }}</span>
                    <button @click="show=false" class="font-bold text-lg">×</button>
                </div>
            @endif
        </div>
 
        {{-- Konten Dinamis --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('konten')
        </main>
    </div>
</div>
 
@livewireScripts
</body>
</html>
