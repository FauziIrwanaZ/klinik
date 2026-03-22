{{--
|═══════════════════════════════════════════════════════════════════════════
| register.blade.php — Pendaftaran Pasien Baru · Klinik Sehat Bersama
|═══════════════════════════════════════════════════════════════════════════
| Stack  : Laravel Blade · Tailwind CSS · Alpine.js
| Layout : @extends('layouts.simple')
|═══════════════════════════════════════════════════════════════════════════
--}}

@extends('layouts.simple')
@section('judul', 'Daftar Pasien Baru — Klinik Rawat Inap')

@section('konten')

{{-- ─────────────────────────────────────────────────────────────────── --}}
{{-- FONTS & PAGE-SCOPED STYLES                                          --}}
{{-- ─────────────────────────────────────────────────────────────────── --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    /* ── Design tokens ── */
    :root {
        --g-deep:   #052e16;
        --g-dark:   #14532d;
        --g-mid:    #166534;
        --g-base:   #16a34a;
        --g-soft:   #4ade80;
        --g-pale:   #dcfce7;
        --surface:  #ffffff;
        --bg:       #f0fdf4;
        --muted:    #64748b;
        --border:   #e2e8f0;
        --error:    #ef4444;
        --focus:    rgba(34,197,94,.3);
        --warn:     #f59e0b;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); }

    /* ── Floating label input ── */
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
        transition: all .17s cubic-bezier(.4,0,.2,1);
        transform-origin: left center;
    }
    .field-wrap input:focus ~ label,
    .field-wrap input:not(:placeholder-shown) ~ label {
        top: .72rem;
        transform: translateY(0) scale(.72);
        color: var(--g-base);
        font-weight: 700;
        letter-spacing: .03em;
    }
    .field-wrap input.has-error ~ label { color: var(--error); }

    /* ── Input base style ── */
    .reg-input {
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
    .reg-input::placeholder { color: transparent; }
    .reg-input:focus {
        border-color: var(--g-base);
        box-shadow: 0 0 0 4px var(--focus);
    }
    .reg-input.has-error {
        border-color: var(--error);
        background: #fff5f5;
    }
    .reg-input.has-error:focus { box-shadow: 0 0 0 4px rgba(239,68,68,.18); }
    .reg-input.is-valid {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    /* ── Password strength bar ── */
    .strength-track {
        height: 4px;
        border-radius: 99px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .strength-bar {
        height: 100%;
        border-radius: 99px;
        transition: width .35s cubic-bezier(.4,0,.2,1), background .35s ease;
    }

    /* ── Password match indicator ── */
    .match-icon { transition: opacity .2s ease, transform .2s ease; }

    /* ── Submit shimmer ── */
    @keyframes shimmer-sweep {
        0%   { transform: translateX(-100%) skewX(-15deg); }
        100% { transform: translateX(300%)  skewX(-15deg); }
    }
    .btn-register { position: relative; overflow: hidden; }
    .btn-register::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.2) 50%, transparent 100%);
        transform: translateX(-100%) skewX(-15deg);
    }
    .btn-register:not(:disabled):hover::after {
        animation: shimmer-sweep .55s ease forwards;
    }

    /* ── Form entrance animation ── */
    @keyframes form-rise {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .form-rise { animation: form-rise .5s cubic-bezier(.34,1.1,.64,1) .05s both; }

    /* ── Step header active ── */
    .step-active { color: var(--g-base); }
    .step-dot { transition: background .2s ease, transform .2s ease; }

    /* ── Flash auto-dismiss progress ── */
    @keyframes drain {
        from { width: 100%; }
        to   { width: 0%; }
    }
    .flash-drain { animation: drain 5s linear forwards; }

    /* ── Shake on error ── */
    @keyframes shake {
        0%,100%{ transform: translateX(0);    }
        20%    { transform: translateX(-6px);  }
        40%    { transform: translateX( 6px);  }
        60%    { transform: translateX(-4px);  }
        80%    { transform: translateX( 4px);  }
    }
    .shake { animation: shake .4s cubic-bezier(.36,.07,.19,.97) both; }

    /* ── Custom checkbox ── */
    .custom-check {
        appearance: none;
        width: 1.125rem; height: 1.125rem;
        border: 1.5px solid #cbd5e1; border-radius: .3rem;
        background: #fff; cursor: pointer;
        transition: all .15s ease; flex-shrink: 0; position: relative;
    }
    .custom-check:checked { background: var(--g-base); border-color: var(--g-base); }
    .custom-check:checked::after {
        content: ''; position: absolute;
        left: 3px; top: 1px; width: 5px; height: 9px;
        border: 2px solid #fff; border-top: 0; border-left: 0;
        transform: rotate(45deg);
    }
    .custom-check:focus { outline: none; box-shadow: 0 0 0 3px var(--focus); }
</style>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- ALPINE COMPONENT ROOT                                               --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12"
     x-data="registerForm()"
     x-init="init()">

    {{-- ── Progress steps indicator ── --}}
    <div class="flex items-center gap-3 mb-7 form-rise" style="animation-delay:0s" aria-label="Progres pendaftaran">
        @foreach([
            ['num' => 1, 'label' => 'Identitas'],
            ['num' => 2, 'label' => 'Keamanan'],
            ['num' => 3, 'label' => 'Konfirmasi'],
        ] as $s)
            <div class="flex items-center gap-1.5"
                 :class="currentStep >= {{ $s['num'] }} ? 'step-active' : 'text-slate-400'"
                 aria-current="{{ $loop->first ? 'step' : 'false' }}">
                <div class="step-dot w-6 h-6 rounded-full flex items-center justify-center
                            text-[11px] font-bold border-2 transition-all duration-200"
                     :class="currentStep >= {{ $s['num'] }}
                        ? 'bg-green-600 border-green-600 text-white scale-110'
                        : 'bg-white border-slate-300 text-slate-400'">
                    <span x-show="currentStep > {{ $s['num'] }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span x-show="currentStep <= {{ $s['num'] }}">{{ $s['num'] }}</span>
                </div>
                <span class="text-xs font-semibold hidden sm:inline">{{ $s['label'] }}</span>
            </div>
            @if(!$loop->last)
                <div class="w-10 h-0.5 rounded-full transition-colors duration-300"
                     :class="currentStep > {{ $s['num'] }} ? 'bg-green-500' : 'bg-slate-200'"
                     aria-hidden="true"></div>
            @endif
        @endforeach
    </div>

    {{-- ── Main card ── --}}
    <div class="form-rise w-full max-w-[460px]" style="animation-delay:.06s">

        {{-- Logo + heading ── --}}
        <div class="text-center mb-7">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl
                        bg-gradient-to-br from-green-500 to-emerald-700
                        shadow-lg shadow-green-900/20 mb-4" aria-hidden="true">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>

            <h1 style="font-family:'Sora',sans-serif"
                class="text-2xl font-bold text-slate-900 leading-tight">
                Daftar sebagai Pasien Baru
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">
                Buat akun untuk mengakses riwayat perawatan Anda secara online.
            </p>
        </div>

        {{-- ── Flash: sukses ── --}}
        @if(session('sukses'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="relative mb-5 overflow-hidden flex items-start gap-3
                        px-4 py-3.5 rounded-2xl
                        bg-green-50 border border-green-200 text-green-700"
                 role="status" aria-live="polite">
                <span class="flex-shrink-0 w-8 h-8 rounded-xl bg-green-100
                             flex items-center justify-center" aria-hidden="true">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <p class="flex-1 text-sm font-medium">{{ session('sukses') }}</p>
                <button @click="show = false"
                        class="text-green-400 hover:text-green-600 transition-colors"
                        aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                {{-- Auto-dismiss progress bar --}}
                <div class="absolute bottom-0 left-0 h-0.5 bg-green-400 flash-drain rounded-b-2xl" aria-hidden="true"></div>
            </div>
        @endif

        {{-- ── Form card ── --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60
                    border border-slate-100 px-7 py-8">

            <form method="POST"
                  action="{{ route('register.proses') }}"
                  @submit="handleSubmit"
                  :class="hasErrors ? 'shake' : ''"
                  class="space-y-5"
                  novalidate
                  aria-label="Form pendaftaran pasien baru">
                @csrf

                {{-- ══════════════════════════════════════════════ --}}
                {{-- STEP 1 — Identitas Diri                        --}}
                {{-- ══════════════════════════════════════════════ --}}
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4
                               flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 text-[10px]
                                     font-extrabold flex items-center justify-center" aria-hidden="true">1</span>
                        Identitas Diri
                    </p>

                    {{-- Nama Lengkap --}}
                    <div class="field-wrap">
                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="nama"
                            autocomplete="name"
                            autofocus
                            x-on:input="validateNama($event.target.value)"
                            :class="errors.nama ? 'has-error' : (touched.nama && !errors.nama ? 'is-valid' : '')"
                            class="reg-input"
                            aria-label="Nama lengkap sesuai KTP"
                            :aria-invalid="errors.nama ? 'true' : 'false'"
                            aria-describedby="nama-help {{ $errors->has('nama') ? 'nama-error' : '' }}">
                        <label for="nama">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>

                        {{-- Validation checkmark --}}
                        <span x-show="touched.nama && !errors.nama"
                              class="match-icon absolute right-3.5 top-1/2 -translate-y-1/2 text-green-500"
                              aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                    </div>
                    <p id="nama-help" class="mt-1.5 text-xs text-slate-400">
                        Gunakan nama lengkap sesuai KTP / Kartu Identitas.
                    </p>
                    {{-- Server-side error --}}
                    @error('nama')
                        <p id="nama-error" class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1" role="alert">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    {{-- Client-side error --}}
                    <p x-show="errors.nama" x-text="errors.nama"
                       class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1"
                       role="alert"></p>
                </div>

                {{-- Email --}}
                <div class="field-wrap">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email"
                        autocomplete="email"
                        x-on:input="validateEmail($event.target.value)"
                        :class="errors.email ? 'has-error' : (touched.email && !errors.email ? 'is-valid' : '')"
                        class="reg-input"
                        aria-label="Alamat email aktif"
                        :aria-invalid="errors.email ? 'true' : 'false'"
                        aria-describedby="email-help {{ $errors->has('email') ? 'email-server-error' : '' }}">
                    <label for="email">
                        Alamat Email <span class="text-red-400">*</span>
                    </label>
                    <span x-show="touched.email && !errors.email"
                          class="match-icon absolute right-3.5 top-1/2 -translate-y-1/2 text-green-500"
                          aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </div>
                <p id="email-help" class="mt-1.5 text-xs text-slate-400">
                    Email digunakan untuk masuk ke sistem dan notifikasi.
                </p>
                @error('email')
                    <p id="email-server-error" class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1" role="alert">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p x-show="errors.email" x-text="errors.email"
                   class="mt-1 text-xs text-red-500 font-medium" role="alert"></p>

                {{-- ══════════════════════════════════════════════ --}}
                {{-- STEP 2 — Keamanan Akun                        --}}
                {{-- ══════════════════════════════════════════════ --}}
                <div class="pt-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4
                               flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 text-[10px]
                                     font-extrabold flex items-center justify-center" aria-hidden="true">2</span>
                        Keamanan Akun
                    </p>

                    {{-- Password --}}
                    <div>
                        <div class="field-wrap">
                            <input
                                id="password"
                                :type="showPass ? 'text' : 'password'"
                                name="password"
                                placeholder="password"
                                autocomplete="new-password"
                                x-on:input="checkStrength($event.target.value); validatePassword($event.target.value)"
                                :class="errors.password ? 'has-error' : ''"
                                class="reg-input pr-12"
                                aria-label="Buat kata sandi"
                                :aria-invalid="errors.password ? 'true' : 'false'"
                                aria-describedby="password-strength password-help {{ $errors->has('password') ? 'password-error' : '' }}">
                            <label for="password">
                                Password <span class="text-red-400">*</span>
                            </label>

                            {{-- Toggle visibility --}}
                            <button type="button"
                                    @click="showPass = !showPass"
                                    :aria-label="showPass ? 'Sembunyikan password' : 'Tampilkan password'"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2
                                           w-8 h-8 rounded-lg flex items-center justify-center
                                           text-slate-400 hover:text-slate-600
                                           hover:bg-slate-100 transition-colors
                                           focus:outline-none focus:ring-2 focus:ring-green-400">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Password strength indicator --}}
                        <div id="password-strength" class="mt-2.5 space-y-1.5" aria-live="polite">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 strength-track">
                                    <div class="strength-bar"
                                         :style="`width: ${strengthPct}%; background: ${strengthColor}`">
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold w-14 text-right transition-colors duration-200"
                                      :style="`color: ${strengthColor}`"
                                      x-text="strengthLabel"
                                      aria-label="Kekuatan password:"></span>
                            </div>

                            {{-- Password rules checklist --}}
                            <ul class="grid grid-cols-2 gap-x-3 gap-y-1 text-[11px] mt-1" aria-label="Syarat password">
                                @foreach([
                                    ['key' => 'length',  'text' => 'Min. 8 karakter'],
                                    ['key' => 'upper',   'text' => 'Huruf kapital (A-Z)'],
                                    ['key' => 'number',  'text' => 'Angka (0-9)'],
                                    ['key' => 'special', 'text' => 'Karakter khusus (!@#)'],
                                ] as $rule)
                                    <li class="flex items-center gap-1.5 transition-colors duration-200"
                                        :class="rules.{{ $rule['key'] }} ? 'text-green-600' : 'text-slate-400'"
                                        :aria-checked="rules.{{ $rule['key'] }} ? 'true' : 'false'"
                                        role="checkbox">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path x-show="rules.{{ $rule['key'] }}" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            <path x-show="!rules.{{ $rule['key'] }}" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                        </svg>
                                        {{ $rule['text'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @error('password')
                            <p id="password-error" class="mt-1.5 text-xs text-red-500 font-medium flex items-center gap-1" role="alert">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mt-5">
                        <div class="field-wrap">
                            <input
                                id="password_confirmation"
                                :type="showPassConf ? 'text' : 'password'"
                                name="password_confirmation"
                                placeholder="konfirmasi"
                                autocomplete="new-password"
                                x-on:input="validateConfirmation($event.target.value)"
                                :class="touched.confirmation
                                    ? (passwordMatch ? 'is-valid' : 'has-error')
                                    : ''"
                                class="reg-input pr-12"
                                aria-label="Ulangi kata sandi"
                                :aria-invalid="touched.confirmation && !passwordMatch ? 'true' : 'false'"
                                aria-describedby="conf-status">
                            <label for="password_confirmation">
                                Konfirmasi Password <span class="text-red-400">*</span>
                            </label>

                            {{-- Toggle visibility --}}
                            <button type="button"
                                    @click="showPassConf = !showPassConf"
                                    :aria-label="showPassConf ? 'Sembunyikan konfirmasi' : 'Tampilkan konfirmasi'"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2
                                           w-8 h-8 rounded-lg flex items-center justify-center
                                           text-slate-400 hover:text-slate-600
                                           hover:bg-slate-100 transition-colors
                                           focus:outline-none focus:ring-2 focus:ring-green-400">
                                <svg x-show="!showPassConf" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassConf" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Match feedback --}}
                        <div id="conf-status" aria-live="polite">
                            <p x-show="touched.confirmation && passwordMatch"
                               class="mt-1.5 text-xs text-green-600 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Password cocok
                            </p>
                            <p x-show="touched.confirmation && !passwordMatch"
                               class="mt-1.5 text-xs text-red-500 font-medium flex items-center gap-1"
                               role="alert">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Password tidak cocok
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════ --}}
                {{-- Info box: role & keamanan data                 --}}
                {{-- ══════════════════════════════════════════════ --}}
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 space-y-3 text-sm text-amber-800">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-amber-100
                                    flex items-center justify-center mt-px" aria-hidden="true">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-amber-900 mb-0.5">Informasi Penting</p>
                            <p class="leading-relaxed text-xs">
                                Akun yang didaftarkan akan memiliki hak akses sebagai
                                <strong class="text-amber-900">Pasien</strong>.
                                Untuk role lain (dokter/petugas), hubungi administrator klinik.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-amber-700 border-t border-amber-200 pt-3">
                        <svg class="w-3.5 h-3.5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Data Anda aman dan tidak akan dibagikan kepada pihak ketiga.
                    </div>
                </div>

                {{-- Terms checkbox --}}
                <label class="flex items-start gap-3 cursor-pointer group select-none"
                       aria-label="Setuju dengan syarat dan ketentuan">
                    <input type="checkbox"
                           x-model="agreedTerms"
                           class="custom-check mt-0.5"
                           required
                           aria-required="true">
                    <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors leading-relaxed">
                        Saya menyetujui
                        <a href="#" class="text-green-600 font-semibold hover:underline
                                          focus:outline-none focus:underline"
                           aria-label="Baca syarat dan ketentuan">syarat & ketentuan</a>
                        serta
                        <a href="#" class="text-green-600 font-semibold hover:underline
                                          focus:outline-none focus:underline"
                           aria-label="Baca kebijakan privasi">kebijakan privasi</a>
                        Klinik Sehat Bersama.
                    </span>
                </label>

                {{-- ══════════════════════════════════════════════ --}}
                {{-- Submit button                                   --}}
                {{-- ══════════════════════════════════════════════ --}}
                <button type="submit"
                        :disabled="loading || !agreedTerms"
                        class="btn-register w-full flex items-center justify-center gap-2.5
                               bg-gradient-to-r from-green-700 to-emerald-700
                               hover:from-green-600 hover:to-emerald-600
                               active:from-green-800 active:to-emerald-800
                               disabled:opacity-50 disabled:cursor-not-allowed
                               text-white font-bold text-[.9375rem] py-3.5 rounded-2xl
                               shadow-md shadow-green-900/20
                               hover:shadow-lg hover:shadow-green-900/25
                               transition-all duration-200
                               focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                        aria-label="Daftar sebagai pasien baru">

                    <span x-show="!loading" class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Daftar Sekarang
                    </span>

                    <span x-show="loading"
                          class="flex items-center gap-2"
                          role="status"
                          aria-live="polite">
                        <svg class="w-4.5 h-4.5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Mendaftar…
                    </span>
                </button>

            </form>

            {{-- ── Login link ── --}}
            <p class="text-center text-sm text-slate-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                   class="text-green-600 font-bold hover:text-green-700 hover:underline
                          focus:outline-none focus:underline transition-colors"
                   aria-label="Masuk ke sistem">
                    Masuk di sini
                </a>
            </p>
        </div>{{-- /card --}}

        {{-- Trust badges ── --}}
        <div class="flex items-center justify-center gap-5 mt-6 text-[11px] text-slate-400 font-medium"
             aria-label="Jaminan keamanan">
            @foreach([
                ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'text' => 'Enkripsi SSL'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'text' => 'Data Aman'],
                ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'text' => 'Dukungan 24/7'],
            ] as $badge)
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}"/>
                    </svg>
                    {{ $badge['text'] }}
                </div>
            @endforeach
        </div>

    </div>{{-- /form-rise wrapper --}}
</div>{{-- /alpine root --}}

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- ALPINE.JS — Registration Form Controller                           --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<script>
function registerForm() {
    return {
        /* ── State ── */
        loading:      false,
        showPass:     false,
        showPassConf: false,
        agreedTerms:  false,
        hasErrors:    {{ $errors->any() ? 'true' : 'false' }},

        /* Tracks what the user has typed */
        passwordValue:      '',
        confirmationValue:  '',

        /* Validation errors (client-side) */
        errors:  { nama: '', email: '' },
        touched: { nama: false, email: false, confirmation: false },

        /* Password strength */
        strengthPct:   0,
        strengthLabel: '',
        strengthColor: '#e2e8f0',
        rules: { length: false, upper: false, number: false, special: false },

        /* Computed: passwords match */
        get passwordMatch() {
            return this.touched.confirmation
                && this.confirmationValue !== ''
                && this.confirmationValue === this.passwordValue;
        },

        /* ── Step indicator (1–3 based on what's filled) ── */
        get currentStep() {
            if (this.passwordMatch)           return 3;
            if (this.passwordValue.length > 0) return 2;
            return 1;
        },

        /* ── Init ── */
        init() {
            /* Remove shake class after it plays */
            if (this.hasErrors) {
                setTimeout(() => { this.hasErrors = false; }, 500);
            }
        },

        /* ── Validators ── */
        validateNama(val) {
            this.touched.nama = true;
            this.errors.nama = val.trim().length < 3
                ? 'Nama minimal 3 karakter.'
                : (val.trim().length > 100 ? 'Nama terlalu panjang.' : '');
        },

        validateEmail(val) {
            this.touched.email = true;
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            this.errors.email = val.trim() === ''
                ? 'Email wajib diisi.'
                : (!re.test(val) ? 'Format email tidak valid.' : '');
        },

        validatePassword(val) {
            this.passwordValue = val;
        },

        validateConfirmation(val) {
            this.touched.confirmation = true;
            this.confirmationValue = val;
        },

        /* ── Password strength engine ── */
        checkStrength(val) {
            this.passwordValue = val;

            const r = {
                length:  val.length >= 8,
                upper:   /[A-Z]/.test(val),
                number:  /[0-9]/.test(val),
                special: /[^A-Za-z0-9]/.test(val),
            };
            this.rules = r;

            const score = Object.values(r).filter(Boolean).length;

            const map = {
                0: { pct: 0,   label: '',        color: '#e2e8f0' },
                1: { pct: 25,  label: 'Lemah',   color: '#ef4444' },
                2: { pct: 50,  label: 'Cukup',   color: '#f59e0b' },
                3: { pct: 75,  label: 'Baik',    color: '#3b82f6' },
                4: { pct: 100, label: 'Kuat',    color: '#22c55e' },
            };

            const s = map[score];
            this.strengthPct   = s.pct;
            this.strengthLabel = s.label;
            this.strengthColor = s.color;
        },

        /* ── Submit handler ── */
        handleSubmit(e) {
            /* Validate required fields before submitting */
            this.validateNama(document.getElementById('nama')?.value || '');
            this.validateEmail(document.getElementById('email')?.value || '');

            const hasClientErrors = Object.values(this.errors).some(v => v !== '');
            if (hasClientErrors || !this.agreedTerms) {
                e.preventDefault();
                this.hasErrors = true;
                setTimeout(() => { this.hasErrors = false; }, 500);
                return;
            }

            this.loading = true;
            /* Allow natural form POST */
        },
    };
}
</script>

@endsection