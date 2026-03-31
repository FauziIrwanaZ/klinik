<!DOCTYPE html>
<html lang="id" class="h-full" x-data="appLayout()" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Klinik Rawat Inap') — Klinik Sehat Bersama</title>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- FONTS: Plus Jakarta Sans (display) + DM Sans (body) --}}
    {{-- ═══════════════════════════════════════════ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* ── Design Tokens ── */
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
            --topbar-height: 64px;

            --brand-50:  #f0fdf4;
            --brand-100: #dcfce7;
            --brand-200: #bbf7d0;
            --brand-400: #4ade80;
            --brand-500: #22c55e;
            --brand-600: #16a34a;
            --brand-700: #15803d;
            --brand-800: #166534;
            --brand-900: #14532d;
            --brand-950: #052e16;

            --surface:      #ffffff;
            --surface-2:    #f8fafc;
            --surface-3:    #f1f5f9;
            --border:       #e2e8f0;
            --text-primary: #0f172a;
            --text-muted:   #64748b;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:    0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
            --shadow-xl:    0 20px 40px rgba(0,0,0,.12);
        }

        .dark {
            --surface:      #0f172a;
            --surface-2:    #1e293b;
            --surface-3:    #334155;
            --border:       #334155;
            --text-primary: #f1f5f9;
            --text-muted:   #94a3b8;
        }

        /* ── Typography ── */
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Sidebar Transitions ── */
        #sidebar {
            width: var(--sidebar-width);
            transition: width 0.25s cubic-bezier(.4,0,.2,1),
                        transform 0.25s cubic-bezier(.4,0,.2,1);
            will-change: width, transform;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .sidebar-header-text,
        #sidebar.collapsed .user-info,
        #sidebar.collapsed .section-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            transition: opacity 0.15s ease, width 0.25s ease;
        }

        #sidebar .nav-label,
        #sidebar .sidebar-header-text,
        #sidebar .user-info,
        #sidebar .section-label {
            opacity: 1;
            transition: opacity 0.2s ease 0.05s;
        }

        #sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--brand-800); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brand-600); }

        /* ── Nav Active Indicator ── */
        .nav-active-pill {
            background: linear-gradient(135deg, var(--brand-600), var(--brand-700));
            box-shadow: 0 4px 12px rgba(22,163,74,.35);
        }

        /* ── Role Badge ── */
        .role-badge {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 99px;
        }

        /* ── Topbar ── */
        #topbar {
            height: var(--topbar-height);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        /* ── Flash message animations ── */
        @keyframes slideDown {
            from { transform: translateY(-12px); opacity: 0; }
            to   { transform: translateY(0);     opacity: 1; }
        }
        .flash-enter { animation: slideDown .3s cubic-bezier(.34,1.56,.64,1) both; }

        /* ── Livewire global loading bar ── */
        #nprogress-bar {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, #22c55e, #4ade80, #86efac);
            border-radius: 0 99px 99px 0;
            z-index: 9999;
            transition: width .3s ease;
        }

        /* ── Tooltip ── */
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #0f172a;
            color: #f8fafc;
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 50;
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(0,0,0,.2);
        }

        /* Mobile overlay */
        #sidebar-overlay {
            backdrop-filter: blur(2px);
        }

             
    </style>
</head>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- ROOT BODY — Alpine state is defined in appLayout() at bottom --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<body class="h-full overflow-hidden bg-[var(--surface-2)]">

    {{-- ── Livewire global loading progress bar ── --}}
    <div id="nprogress-bar"
         x-show="$wire !== undefined"
         wire:loading.style="width: 80%"
         wire:loading.delay
         style="width: 0%">
    </div>

    <div class="flex h-screen overflow-hidden">

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- MOBILE OVERLAY (backdrop when sidebar open on mobile)     --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div id="sidebar-overlay"
             x-show="mobileOpen"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 bg-black/50 z-30 lg:hidden"
             style="display:none">
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- SIDEBAR                                                    --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <aside id="sidebar"
               :class="{
                   'collapsed': sidebarCollapsed && !isMobile(),
                   '-translate-x-full': !mobileOpen && isMobile(),
                   'translate-x-0':  mobileOpen || !isMobile()
               }"
               class="fixed lg:relative z-40 flex flex-col flex-shrink-0 h-full
                      bg-[var(--brand-950)] text-white
                      shadow-[var(--shadow-xl)]
                      lg:translate-x-0"
               aria-label="Navigasi Utama">

            {{-- ── Sidebar Header ── --}}
            <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10 flex-shrink-0">
                {{-- Logo icon --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-xl
                            bg-gradient-to-br from-green-400 to-emerald-600
                            flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 2a9 9 0 00-9 9 9 9 0 009 9 9 9 0 009-9 9 9 0 00-9-9zm0 5v4m0 0h4m-4 0H8"/>
                    </svg>
                </div>
                {{-- Brand text — hidden when collapsed --}}
                <div class="sidebar-header-text min-w-0">
                    <h1 class="text-sm font-bold leading-tight text-white truncate">Klinik Sehat</h1>
                    <p class="text-[10px] text-green-300/80 font-medium tracking-wide">Rawat Inap Bersama</p>
                </div>
                {{-- Collapse toggle (desktop) --}}
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="ml-auto hidden lg:flex items-center justify-center
                               w-7 h-7 rounded-lg text-green-300 hover:text-white
                               hover:bg-white/10 transition-colors flex-shrink-0"
                        :aria-label="sidebarCollapsed ? 'Buka sidebar' : 'Tutup sidebar'">
                    <svg class="w-4 h-4 transition-transform duration-300"
                         :class="sidebarCollapsed ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            {{-- ── User Info Card ── --}}
            <div class="mx-3 my-3 rounded-xl bg-white/5 border border-white/10 p-3 flex items-center gap-3 flex-shrink-0 user-info">
                {{-- Avatar --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-teal-500
                            flex items-center justify-center text-sm font-bold text-white shadow">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                {{-- Name + Role --}}
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate leading-tight">
                        {{ auth()->user()->nama }}
                    </p>
                    <span class="role-badge mt-0.5 inline-block
                        {{ match(auth()->user()->role) {
                            'admin'   => 'bg-amber-400/20 text-amber-300 ring-1 ring-amber-400/30',
                            'petugas' => 'bg-sky-400/20 text-sky-300 ring-1 ring-sky-400/30',
                            'dokter'  => 'bg-violet-400/20 text-violet-300 ring-1 ring-violet-400/30',
                            'pasien'  => 'bg-green-400/20 text-green-300 ring-1 ring-green-400/30',
                            default   => 'bg-white/10 text-white/70',
                        } }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>

            {{-- ── Navigation ── --}}
            <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-0.5"
                 aria-label="Menu navigasi">

                {{-- Helper macro: nav item --}}
                {{-- Dashboard — semua role --}}
                @php
                    /**
                     * nav_item() — Generate a sidebar nav link.
                     * @param string $route   Route name
                     * @param string $label   Display label
                     * @param string $icon    Heroicon path (stroke)
                     * @param string $match   Optional route pattern for active detection
                     */
                    function nav_item(string $route, string $label, string $icon, string $match = ''): string
                    {
                        $isActive = $match
                            ? request()->routeIs($match)
                            : request()->routeIs($route);

                        $activeClass = $isActive
                            ? 'nav-active-pill text-white'
                            : 'text-green-100/80 hover:bg-white/10 hover:text-white';

                        $url = route($route);

                        return <<<HTML
                        <a href="{$url}"
                           class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl
                                  text-sm font-medium transition-all duration-150
                                  {$activeClass} group"
                           data-tooltip="{$label}"
                           aria-current="{ $isActive ? 'page' : 'false' }">
                            <span class="flex-shrink-0 w-5 h-5 text-current">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">{$icon}</svg>
                            </span>
                            <span class="nav-label truncate">{$label}</span>
                        </a>
                        HTML;
                    }
                @endphp

                {{-- Dashboard --}}
                {!! nav_item(
                    'dashboard', 'Dashboard',
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'
                ) !!}

                {{-- ── ADMIN / PETUGAS Section ── --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                    <div class="section-label pt-4 pb-1 px-3">
                        <p class="text-[10px] font-bold text-green-400/70 uppercase tracking-widest">
                            {{ auth()->user()->isAdmin() ? 'Administrasi' : 'Petugas' }}
                        </p>
                    </div>

                      {!! nav_item(
                        'admin.pengguna', 'Data Pengguna',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
                    ) !!}
                    {!! nav_item(
                        'admin.pasien', 'Data Pasien',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
                    ) !!}

                    {!! nav_item(
                        'admin.kamar', 'Data Kamar',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>'
                    ) !!}

                    {!! nav_item(
                        'rawat-inap', 'Rawat Inap',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'
                    ) !!}

                    {!! nav_item(
                        'admin.transaksi', 'Transaksi',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'
                    ) !!}
                @endif

                {{-- ── DOKTER Section ── --}}
                @if(auth()->user()->isDokter())
                    <div class="section-label pt-4 pb-1 px-3">
                        <p class="text-[10px] font-bold text-green-400/70 uppercase tracking-widest">Dokter</p>
                    </div>

                    {!! nav_item(
                        'rawat-inap', 'Pasien Saya',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>'
                    ) !!}
                @endif

                {{-- ── PASIEN Section ── --}}
                @if(auth()->user()->isPasien())
                    <div class="section-label pt-4 pb-1 px-3">
                        <p class="text-[10px] font-bold text-green-400/70 uppercase tracking-widest">Pasien</p>
                    </div>

                    {!! nav_item(
                        'pasien.riwayat', 'Riwayat Perawatan',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                    ) !!}
                @endif

            </nav>

            {{-- ── Sidebar Footer: Logout + Dark Mode ── --}}
            <div class="p-3 border-t border-white/10 flex-shrink-0 space-y-1.5">

                {{-- Dark mode toggle --}}
                <button @click="darkMode = !darkMode"
                        class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl
                               text-sm font-medium text-green-100/80 hover:bg-white/10
                               hover:text-white transition-all duration-150"
                        :aria-label="darkMode ? 'Nonaktifkan dark mode' : 'Aktifkan dark mode'"
                        data-tooltip="Tema Gelap/Terang">
                    <span class="flex-shrink-0 w-5 h-5">
                        <svg x-show="!darkMode" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </span>
                    <span class="nav-label" x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                </button>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl
                                   text-sm font-medium text-red-300/90 hover:bg-red-500/15
                                   hover:text-red-200 transition-all duration-150"
                            data-tooltip="Keluar"
                            aria-label="Keluar dari aplikasi">
                        <span class="flex-shrink-0 w-5 h-5">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </span>
                        <span class="nav-label">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT AREA                                         --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0
                    transition-all duration-250">

            {{-- ── TOPBAR ── --}}
            <header id="topbar"
                    class="flex items-center justify-between px-5 lg:px-6 flex-shrink-0
                           bg-[var(--surface)] border-b border-[var(--border)]"
                    role="banner">

                {{-- Left: Hamburger (mobile) + Page title --}}
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="lg:hidden flex items-center justify-center
                                   w-9 h-9 rounded-xl text-[var(--text-muted)]
                                   hover:bg-[var(--surface-3)] hover:text-[var(--text-primary)]
                                   transition-colors"
                            :aria-expanded="mobileOpen"
                            aria-controls="sidebar"
                            aria-label="Toggle menu navigasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  x-show="!mobileOpen"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  x-show="mobileOpen"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Page title + breadcrumb --}}
                    <div>
                        <h2 class="text-base font-bold text-[var(--text-primary)] leading-tight">
                            @yield('header', 'Dashboard')
                        </h2>
                        <p class="text-xs text-[var(--text-muted)] hidden sm:block">
                            @yield('sub-header', 'Sistem Informasi Klinik Rawat Inap')
                        </p>
                    </div>
                </div>

                {{-- Right: Date + Livewire loading indicator + notification bell --}}
                <div class="flex items-center gap-2 lg:gap-3">

                    {{-- Livewire loading spinner --}}
                    <div wire:loading
                         wire:loading.delay.shortest
                         class="flex items-center gap-1.5 text-xs font-medium text-green-600">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span class="hidden sm:inline">Memuat…</span>
                    </div>

                    {{-- Date chip --}}
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl
                                bg-[var(--surface-3)] text-xs font-medium text-[var(--text-muted)]">
                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <time datetime="{{ now()->toDateString() }}">
                            {{ now()->isoFormat('D MMM Y') }}
                        </time>
                    </div>

                    {{-- User avatar chip --}}
                    <div class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl
                                bg-[var(--surface-3)] text-xs font-semibold text-[var(--text-primary)]">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-teal-500
                                    flex items-center justify-center text-white text-[10px] font-bold">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline truncate max-w-[120px]">
                            {{ auth()->user()->nama }}
                        </span>
                    </div>
                </div>
            </header>

            {{-- ── FLASH MESSAGES ── --}}
            @if(session('sukses') || session('error') || session('info') || session('warning'))
                <div class="px-5 lg:px-6 pt-4 space-y-2 flex-shrink-0" role="alert" aria-live="polite">

                    @if(session('sukses'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:enter="flash-enter"
                             x-transition:leave="transition-all duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="flex items-center gap-3 px-4 py-3 rounded-xl
                                    bg-green-50 dark:bg-green-900/30
                                    border border-green-200 dark:border-green-700/50
                                    text-green-800 dark:text-green-200
                                    shadow-sm">
                            {{-- Icon --}}
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-green-100 dark:bg-green-800/40
                                         flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            {{-- Message --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wide">Berhasil</p>
                                <p class="text-sm font-medium">{{ session('sukses') }}</p>
                            </div>
                            {{-- Progress bar --}}
                            <div class="absolute bottom-0 left-0 h-0.5 bg-green-400 rounded-b-xl"
                                 x-data
                                 x-init="$el.animate([{width:'100%'},{width:'0%'}], {duration:5000, fill:'forwards'})">
                            </div>
                            {{-- Dismiss --}}
                            <button @click="show = false"
                                    class="flex-shrink-0 w-7 h-7 rounded-lg
                                           text-green-400 hover:text-green-600
                                           hover:bg-green-100 dark:hover:bg-green-800/40
                                           flex items-center justify-center transition-colors"
                                    aria-label="Tutup notifikasi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 6000)"
                             x-transition:enter="flash-enter"
                             x-transition:leave="transition-all duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="flex items-center gap-3 px-4 py-3 rounded-xl
                                    bg-red-50 dark:bg-red-900/30
                                    border border-red-200 dark:border-red-700/50
                                    text-red-800 dark:text-red-200
                                    shadow-sm">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 dark:bg-red-800/40
                                         flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide">Kesalahan</p>
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false"
                                    class="flex-shrink-0 w-7 h-7 rounded-lg
                                           text-red-400 hover:text-red-600
                                           hover:bg-red-100 dark:hover:bg-red-800/40
                                           flex items-center justify-center transition-colors"
                                    aria-label="Tutup notifikasi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 5500)"
                             x-transition:enter="flash-enter"
                             x-transition:leave="transition-all duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="flex items-center gap-3 px-4 py-3 rounded-xl
                                    bg-amber-50 dark:bg-amber-900/30
                                    border border-amber-200 dark:border-amber-700/50
                                    text-amber-800 dark:text-amber-200
                                    shadow-sm">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-800/40
                                         flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wide">Peringatan</p>
                                <p class="text-sm font-medium">{{ session('warning') }}</p>
                            </div>
                            <button @click="show = false"
                                    class="flex-shrink-0 w-7 h-7 rounded-lg
                                           text-amber-400 hover:text-amber-600
                                           hover:bg-amber-100 dark:hover:bg-amber-800/40
                                           flex items-center justify-center transition-colors"
                                    aria-label="Tutup notifikasi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:enter="flash-enter"
                             x-transition:leave="transition-all duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="flex items-center gap-3 px-4 py-3 rounded-xl
                                    bg-sky-50 dark:bg-sky-900/30
                                    border border-sky-200 dark:border-sky-700/50
                                    text-sky-800 dark:text-sky-200
                                    shadow-sm">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-800/40
                                         flex items-center justify-center">
                                <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-sky-600 dark:text-sky-400 uppercase tracking-wide">Informasi</p>
                                <p class="text-sm font-medium">{{ session('info') }}</p>
                            </div>
                            <button @click="show = false"
                                    class="flex-shrink-0 w-7 h-7 rounded-lg
                                           text-sky-400 hover:text-sky-600
                                           hover:bg-sky-100 dark:hover:bg-sky-800/40
                                           flex items-center justify-center transition-colors"
                                    aria-label="Tutup notifikasi">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── MAIN CONTENT ── --}}
            <main id="main-content"
                  class="flex-1 overflow-y-auto px-5 lg:px-6 py-5 lg:py-6"
                  role="main"
                  aria-label="Konten halaman">
                @yield('konten')
            </main>

        </div>{{-- /content area --}}
    </div>{{-- /flex root --}}

    @livewireScripts

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- ALPINE.JS — App Layout Controller                                  --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    <script>
    function appLayout() {
        return {
            // ── State ──
            sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
            mobileOpen: false,
            darkMode: localStorage.getItem('dark_mode') === 'true',

            // ── Helpers ──
            isMobile() {
                return window.innerWidth < 1024; // lg breakpoint
            },

            // ── Watchers ──
            init() {
                // Persist sidebar collapsed state
                this.$watch('sidebarCollapsed', val => {
                    localStorage.setItem('sidebar_collapsed', val);
                });

                // Persist dark mode
                this.$watch('darkMode', val => {
                    localStorage.setItem('dark_mode', val);
                });

                // Close sidebar on resize to desktop
                window.addEventListener('resize', () => {
                    if (!this.isMobile()) {
                        this.mobileOpen = false;
                    }
                });

                // Close sidebar on escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.mobileOpen) {
                        this.mobileOpen = false;
                    }
                });
            }
        };
    }
    </script>
</body>
</html>