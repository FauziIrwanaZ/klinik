{{--
|═══════════════════════════════════════════════════════════════════════════
| login.blade.php — Halaman Login Klinik Sehat Bersama
|═══════════════════════════════════════════════════════════════════════════
| Stack  : Laravel Blade · Tailwind CSS · Alpine.js
| Layout : @extends('layouts.simple')
|═══════════════════════════════════════════════════════════════════════════
--}}

@extends('layouts.simple')
@section('judul', 'Masuk — Klinik Rawat Inap')

@section('konten')

{{-- ─────────────────────────────────────────────────────────────────── --}}
{{-- FONTS & PAGE-SCOPED STYLES                                          --}}
{{-- ─────────────────────────────────────────────────────────────────── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    [x-cloak] { display: none !important; }
    /* ── Tokens ── */
    :root {
        --g-deep:   #052e16;
        --g-dark:   #14532d;
        --g-mid:    #166534;
        --g-base:   #16a34a;
        --g-soft:   #4ade80;
        --g-pale:   #dcfce7;
        --surface:  #ffffff;
        --muted:    #64748b;
        --border:   #e2e8f0;
        --error:    #ef4444;
        --focus:    rgba(34,197,94,.35);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0fdf4; }

    /* ── Left panel radial glow ── */
    .panel-left {
        background:
            radial-gradient(ellipse 80% 60% at 30% 40%, rgba(74,222,128,.18) 0%, transparent 70%),
            radial-gradient(ellipse 60% 80% at 70% 70%, rgba(20,83,45,.6)  0%, transparent 80%),
            linear-gradient(155deg, #052e16 0%, #14532d 55%, #166534 100%);
    }

    /* ── Floating label ── */
    .field-wrap { position: relative; }
    .field-wrap input { padding-top: 1.35rem; padding-bottom: .6rem; }
    .field-wrap label {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: .875rem;
        color: var(--muted);
        pointer-events: none;
        transition: all .18s cubic-bezier(.4,0,.2,1);
        transform-origin: left center;
        white-space: nowrap;
    }
    .field-wrap input:focus ~ label,
    .field-wrap input:not(:placeholder-shown) ~ label {
        top: .75rem;
        transform: translateY(0) scale(.72);
        color: var(--g-base);
        font-weight: 700;
        letter-spacing: .03em;
    }
    .field-wrap input.has-error ~ label { color: var(--error); }

    /* ── Input base ── */
    .login-input {
        width: 100%;
        border: 1.5px solid var(--border);
        border-radius: .875rem;
        padding: 1.35rem 1rem .6rem;
        font-size: .9375rem;
        color: #0f172a;
        background: #fff;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        font-family: inherit;
    }
    .login-input::placeholder { color: transparent; }
    .login-input:focus {
        border-color: var(--g-base);
        box-shadow: 0 0 0 4px var(--focus);
    }
    .login-input.has-error {
        border-color: var(--error);
        background: #fff5f5;
    }
    .login-input.has-error:focus {
        box-shadow: 0 0 0 4px rgba(239,68,68,.2);
    }

    /* ── Submit button shimmer ── */
    @keyframes shimmer-sweep {
        0%   { transform: translateX(-100%) skewX(-15deg); }
        100% { transform: translateX(300%)  skewX(-15deg); }
    }
    .btn-login { position: relative; overflow: hidden; }
    .btn-login::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.2) 50%, transparent 100%);
        transform: translateX(-100%) skewX(-15deg);
        transition: none;
    }
    .btn-login:not(:disabled):hover::after {
        animation: shimmer-sweep .55s ease forwards;
    }

    /* ── Decorative SVG pulse ── */
    @keyframes pulse-ring {
        0%   { transform: scale(1);    opacity: .5; }
        100% { transform: scale(1.55); opacity: 0; }
    }
    .pulse-ring {
        animation: pulse-ring 2.5s cubic-bezier(.4,0,.6,1) infinite;
    }

    /* ── Flash slide-in ── */
    @keyframes flash-in {
        from { opacity:0; transform:translateY(-8px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .flash-enter { animation: flash-in .3s cubic-bezier(.34,1.4,.64,1) both; }

    /* ── Feature card hover ── */
    .feature-card {
        transition: transform .2s ease, background .2s ease;
    }
    .feature-card:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,.12);
    }

    /* ── Form entrance ── */
    @keyframes form-rise {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .form-rise { animation: form-rise .5s cubic-bezier(.34,1.1,.64,1) .1s both; }

    /* ── Custom checkbox ── */
    .custom-check {
        appearance: none;
        width: 1.125rem; height: 1.125rem;
        border: 1.5px solid #cbd5e1;
        border-radius: .3rem;
        background: #fff;
        cursor: pointer;
        transition: all .15s ease;
        flex-shrink: 0;
        position: relative;
    }
    .custom-check:checked {
        background: var(--g-base);
        border-color: var(--g-base);
    }
    .custom-check:checked::after {
        content: '';
        position: absolute;
        left: 3px; top: 1px;
        width: 5px; height: 9px;
        border: 2px solid #fff;
        border-top: 0; border-left: 0;
        transform: rotate(45deg);
    }
    .custom-check:focus { outline: none; box-shadow: 0 0 0 3px var(--focus); }
    /* ── Dot pulse (untuk loading button) ── */
    @keyframes dot {
        0%, 80%, 100% { opacity: .2; transform: scale(.8); }
        40%            { opacity: 1;  transform: scale(1);  }
    }
</style>  {{-- ← tutup tag style yang sudah ada --}}
</style>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- ROOT: Alpine controller                                             --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div class="flex min-h-screen"
     x-data="{
         showPass:    false,
         loading:     false,
         showError:   {{ session('error')  ? 'true' : 'false' }},
         showSukses:  {{ session('sukses') ? 'true' : 'false' }},

         /* Auto-dismiss flash messages */
         init() {
             if (this.showError)  setTimeout(() => this.showError  = false, 6000);
             if (this.showSukses) setTimeout(() => this.showSukses = false, 5000);
         },

         /* Trigger loading state on submit */
         handleSubmit(e) {
             this.loading = true;
             /* Allow natural form POST — do not prevent default */
         }
     }">

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- LEFT PANEL — Decorative branding                              --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <aside class="hidden lg:flex panel-left w-[52%] xl:w-[55%] flex-col
                  items-center justify-center p-14 relative overflow-hidden"
           aria-hidden="true">

        {{-- ── Decorative SVG background geometry ── --}}
        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 800 900"
             fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <!-- Grid dots -->
            <defs>
                <pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="rgba(74,222,128,.12)"/>
                </pattern>
            </defs>
            <rect width="800" height="900" fill="url(#dots)"/>

            <!-- Large decorative circles -->
            <circle cx="650" cy="120"  r="220" stroke="rgba(74,222,128,.08)" stroke-width="1.5" fill="none"/>
            <circle cx="650" cy="120"  r="160" stroke="rgba(74,222,128,.06)" stroke-width="1"   fill="none"/>
            <circle cx="150" cy="780"  r="180" stroke="rgba(74,222,128,.07)" stroke-width="1.5" fill="none"/>
            <circle cx="400" cy="450"  r="300" stroke="rgba(255,255,255,.03)" stroke-width="1"  fill="none"/>

            <!-- Medical cross (top right, large faded) -->
            <rect x="580" y="30"  width="18" height="70" rx="9"  fill="rgba(74,222,128,.07)"/>
            <rect x="556" y="54"  width="66" height="22" rx="9"  fill="rgba(74,222,128,.07)"/>

            <!-- Medical cross (bottom left) -->
            <rect x="80"  y="730" width="12" height="48" rx="6"  fill="rgba(74,222,128,.1)"/>
            <rect x="62"  y="748" width="48" height="12" rx="6"  fill="rgba(74,222,128,.1)"/>

            <!-- Diagonal line accents -->
            <line x1="0"   y1="200" x2="200" y2="0"   stroke="rgba(74,222,128,.05)" stroke-width="1"/>
            <line x1="600" y1="900" x2="800" y2="700" stroke="rgba(74,222,128,.05)" stroke-width="1"/>

            <!-- Small accent pills -->
            <rect x="30"  y="350" width="60" height="4" rx="2" fill="rgba(74,222,128,.15)"/>
            <rect x="710" y="600" width="60" height="4" rx="2" fill="rgba(74,222,128,.15)"/>
        </svg>

        {{-- ── Main content ── --}}
        <div class="relative z-10 text-center max-w-md">

            {{-- Logo mark with pulse rings --}}
            <div class="flex justify-center mb-8">
                <div class="relative">
                    {{-- Pulse rings --}}
                    <div class="pulse-ring absolute inset-0 rounded-full bg-green-400/20"></div>
                    <div class="pulse-ring absolute inset-0 rounded-full bg-green-400/15"
                         style="animation-delay:.8s"></div>
                    {{-- Icon circle --}}
                    <div class="relative w-20 h-20 rounded-2xl
                                bg-gradient-to-br from-green-400 to-emerald-600
                                flex items-center justify-center shadow-2xl
                                shadow-green-900/50">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Headline --}}
            <h1 style="font-family:'Sora',sans-serif"
                class="text-4xl font-bold text-white leading-tight mb-3">
                Klinik Sehat<br>
                <span class="text-green-300">Bersama</span>
            </h1>
            <p class="text-green-200/80 text-base leading-relaxed mb-12 max-w-xs mx-auto">
                Sistem informasi rawat inap modern untuk pelayanan kesehatan yang lebih baik.
            </p>

            {{-- Feature cards --}}
            <div class="grid grid-cols-3 gap-3">
                @foreach([
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',            'label' => 'Rekam Medis'],
                    ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Rawat Inap'],
                    ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Tagihan'],
                ] as $feat)
                    <div class="feature-card bg-white/8 border border-white/10
                                rounded-2xl p-4 cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-green-500/20 border border-green-400/20
                                    flex items-center justify-center mx-auto mb-2.5">
                            <svg class="w-5 h-5 text-green-300" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-green-200/90 leading-tight">
                            {{ $feat['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom attribution --}}
        <p class="absolute bottom-6 text-[11px] text-green-400/50 font-medium tracking-wide">
            © {{ date('Y') }} Klinik Sehat Bersama. All rights reserved.
        </p>
    </aside>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- RIGHT PANEL — Login form                                      --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col items-center justify-center
                 px-5 sm:px-10 py-12 bg-white min-h-screen
                 overflow-y-auto"
          role="main">

        {{-- Mobile logo (hidden on desktop) --}}
        <div class="lg:hidden flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-700
                        flex items-center justify-center shadow-lg mb-3">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-lg font-bold text-slate-800">Klinik Sehat Bersama</h1>
        </div>

        {{-- Form container --}}
        <div class="form-rise w-full max-w-[420px]">

            {{-- ── Page heading ── --}}
            <div class="mb-8">
                <h2 style="font-family:'Sora',sans-serif"
                    class="text-[1.75rem] font-bold text-slate-900 leading-tight">
                    Selamat datang
                    <span class="inline-block animate-[wave_.8s_ease_1]">👋</span>
                </h2>
                <p class="text-slate-500 mt-1.5 text-sm leading-relaxed">
                    Masuk ke sistem untuk melanjutkan ke dashboard Anda.
                </p>
            </div>

            {{-- ── Flash: Error ── --}}
            <div x-show="showError"
                 x-transition:enter="flash-enter"
                 x-transition:leave="transition-all duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-5 flex items-start gap-3 px-4 py-3.5 rounded-2xl
                        bg-red-50 border border-red-200 text-red-700"
                 role="alert"
                 aria-live="assertive"
                 style="display:none">
                <span class="flex-shrink-0 w-8 h-8 rounded-xl bg-red-100
                             flex items-center justify-center mt-px" aria-hidden="true">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-red-600 uppercase tracking-wide">Gagal masuk</p>
                    <p class="text-sm mt-0.5">{{ session('error') }}</p>
                </div>
                <button @click="showError = false"
                        class="flex-shrink-0 text-red-400 hover:text-red-600 transition-colors"
                        aria-label="Tutup pesan error">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ── Flash: Sukses ── --}}
            <div x-show="showSukses"
                 x-transition:enter="flash-enter"
                 x-transition:leave="transition-all duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-5 flex items-start gap-3 px-4 py-3.5 rounded-2xl
                        bg-green-50 border border-green-200 text-green-700"
                 role="status"
                 aria-live="polite"
                 style="display:none">
                <span class="flex-shrink-0 w-8 h-8 rounded-xl bg-green-100
                             flex items-center justify-center mt-px" aria-hidden="true">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <p class="flex-1 text-sm font-medium">{{ session('sukses') }}</p>
                <button @click="showSukses = false"
                        class="flex-shrink-0 text-green-400 hover:text-green-600 transition-colors"
                        aria-label="Tutup pesan sukses">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ════════════════════════════════════════════════════ --}}
            {{-- LOGIN FORM                                           --}}
            {{-- ════════════════════════════════════════════════════ --}}
            <form method="POST"
                  action="{{ route('login.proses') }}"
                  @submit="handleSubmit"
                  class="space-y-4"
                  novalidate
                  aria-label="Form masuk akun">
                @csrf

                {{-- ── Email field ── --}}
                <div class="field-wrap">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email"
                        autocomplete="email"
                        autofocus
                        class="login-input @error('email') has-error @enderror"
                        aria-label="Alamat email"
                        aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    <label for="email">Email</label>

                    @error('email')
                        <p id="email-error"
                           class="mt-1.5 flex items-center gap-1 text-xs text-red-500 font-medium"
                           role="alert">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Password field (with show/hide toggle) ── --}}
                <div>
                    <div class="field-wrap">
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            placeholder="password"
                            autocomplete="current-password"
                            class="login-input pr-12 @error('password') has-error @enderror"
                            aria-label="Kata sandi"
                            aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        <label for="password">Password</label>

                        {{-- Toggle visibility button --}}
                        <button type="button"
                                @click="showPass = !showPass"
                                :aria-label="showPass ? 'Sembunyikan password' : 'Tampilkan password'"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                       w-8 h-8 rounded-lg flex items-center justify-center
                                       text-slate-400 hover:text-slate-600
                                       hover:bg-slate-100 transition-colors duration-150
                                       focus:outline-none focus:ring-2 focus:ring-green-400">
                            {{-- Eye open --}}
                            <svg x-show="!showPass" class="w-4.5 h-4.5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Eye closed --}}
                            <svg x-show="showPass" class="w-4.5 h-4.5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p id="password-error"
                           class="mt-1.5 flex items-center gap-1 text-xs text-red-500 font-medium"
                           role="alert">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ── Remember me + Forgot password ── --}}
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                        <input type="checkbox"
                               id="ingat_saya"
                               name="ingat_saya"
                               class="custom-check"
                               aria-label="Ingat saya di perangkat ini">
                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">
                            Ingat saya
                        </span>
                    </label>

                    {{-- Placeholder forgot password link --}}
                    {{-- Uncomment when route tersedia --}}
                    {{-- <a href="{{ route('password.request') }}"
                       class="text-sm text-green-600 font-semibold hover:text-green-700
                              hover:underline focus:outline-none focus:underline transition-colors">
                        Lupa password?
                    </a> --}}
                </div>

                {{-- ── Submit button ── --}}
               {{-- ── Submit button ── --}}
<button type="submit"
        :disabled="loading"
        class="w-full relative flex items-center justify-center gap-2
               h-[46px] px-6 rounded-xl
               bg-green-50 hover:bg-green-100
               text-green-700 text-sm font-medium
               ring-1 ring-green-200 hover:ring-green-300
               disabled:opacity-50 disabled:cursor-not-allowed
               transition-all duration-150
               focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        aria-label="Masuk ke sistem">

    {{-- Normal state --}}
    <span x-show="!loading" class="flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Masuk ke Sistem
    </span>

    {{-- Loading state — dot pulse --}}
    <span x-show="loading" x-cloak
          class="flex items-center gap-2.5"
          role="status" aria-live="polite">
        Sedang masuk
        <span class="flex items-center gap-1" aria-hidden="true">
            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-[dot_.9s_ease-in-out_infinite]"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-[dot_.9s_ease-in-out_.2s_infinite]"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-[dot_.9s_ease-in-out_.4s_infinite]"></span>
        </span>
    </span>

</button>
            </form>

            {{-- ── Register link ── --}}
            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-green-600 font-bold hover:text-green-700 hover:underline
                          focus:outline-none focus:underline transition-colors"
                   aria-label="Daftar sebagai pasien baru">
                    Daftar sebagai Pasien Baru
                </a>
            </p>

            {{-- ── Demo credentials (remove in production) ── --}}
            @if(app()->environment('local', 'development'))
            <!-- <details class="mt-8 group">
                <summary class="flex items-center gap-2 cursor-pointer select-none
                                text-xs font-bold text-slate-400 uppercase tracking-widest
                                hover:text-slate-600 transition-colors list-none">
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-90"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    Akun Demo
                </summary>

                <div class="mt-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl
                            text-xs text-slate-600 space-y-2">
                    <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Hapus sebelum production!
                    </p>
                    @foreach([
                        ['role'=>'Admin',   'badge'=>'bg-amber-100 text-amber-700',  'email'=>'admin@klinik.com',   'pass'=>'admin123'],
                        ['role'=>'Petugas', 'badge'=>'bg-sky-100 text-sky-700',      'email'=>'petugas@klinik.com', 'pass'=>'petugas123'],
                        ['role'=>'Dokter',  'badge'=>'bg-violet-100 text-violet-700','email'=>'dokter1@klinik.com', 'pass'=>'dokter123'],
                        ['role'=>'Pasien',  'badge'=>'bg-green-100 text-green-700',  'email'=>'pasien1@klinik.com', 'pass'=>'pasien123'],
                    ] as $demo)
                        <div class="flex items-center justify-between gap-3 py-1.5
                                    border-b border-slate-100 last:border-0">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="flex-shrink-0 px-1.5 py-0.5 rounded-md text-[10px]
                                             font-bold {{ $demo['badge'] }}">
                                    {{ $demo['role'] }}
                                </span>
                                <span class="truncate text-slate-500">{{ $demo['email'] }}</span>
                            </div>
                            <code class="flex-shrink-0 font-mono text-slate-400 bg-white
                                         border border-slate-200 px-1.5 py-0.5 rounded-lg">
                                {{ $demo['pass'] }}
                            </code>
                        </div>
                    @endforeach
                </div>
            </details> -->
            @endif

        </div>{{-- /form container --}}
    </main>

</div>{{-- /root --}}

@endsection