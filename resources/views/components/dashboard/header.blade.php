{{--
|─────────────────────────────────────────────────────────────────────────────
| Component: <x-dashboard.header />
|─────────────────────────────────────────────────────────────────────────────
| Props:
|   @prop string $greeting   — Kata sapa pembuka, e.g. "Selamat datang"
|   @prop string $name       — Nama pengguna
|   @prop string $subtitle   — Teks kecil di bawah nama
|   @prop string $icon       — Emoji atau karakter dekoratif (default: 👋)
|   @prop string $role       — Role user: admin | petugas | dokter | pasien
|─────────────────────────────────────────────────────────────────────────────
--}}

@props([
    'greeting' => 'Selamat datang',
    'name'     => '',
    'subtitle' => 'Berikut adalah ringkasan untuk Anda.',
    'icon'     => '👋',
    'role'     => 'pasien',
])

@php
/**
 * Role-specific metadata: badge color, label, icon path (Heroicons stroke).
 */
$roleMeta = [
    'admin'   => ['label' => 'Administrator',  'badgeBg' => 'bg-amber-100 dark:bg-amber-900/30',  'badgeText' => 'text-amber-700 dark:text-amber-300',  'dot' => 'bg-amber-400'],
    'petugas' => ['label' => 'Petugas Klinik', 'badgeBg' => 'bg-sky-100 dark:bg-sky-900/30',      'badgeText' => 'text-sky-700 dark:text-sky-300',      'dot' => 'bg-sky-400'],
    'dokter'  => ['label' => 'Dokter',         'badgeBg' => 'bg-violet-100 dark:bg-violet-900/30','badgeText' => 'text-violet-700 dark:text-violet-300','dot' => 'bg-violet-400'],
    'pasien'  => ['label' => 'Pasien',         'badgeBg' => 'bg-green-100 dark:bg-green-900/30',  'badgeText' => 'text-green-700 dark:text-green-300',  'dot' => 'bg-green-400'],
];
$meta = $roleMeta[$role] ?? $roleMeta['pasien'];
@endphp

{{-- Staggered fade-in entrance --}}
<div x-data
     x-init="setTimeout(() => { $el.style.opacity='1'; $el.style.transform='translateY(0)' }, 0)"
     style="opacity:0; transform:translateY(-8px); transition: opacity .5s ease, transform .5s ease"
     class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8"
     role="banner"
     aria-label="Header halaman dashboard">

    {{-- Left: greeting + subtitle --}}
    <div class="flex items-center gap-4">
        {{-- Decorative avatar circle --}}
        <div class="flex-shrink-0 w-12 h-12 rounded-2xl
                    bg-gradient-to-br from-green-400 to-emerald-600
                    flex items-center justify-center shadow-md
                    text-xl select-none"
             aria-hidden="true">
            {{ $icon }}
        </div>

        <div>
            <div class="flex items-center flex-wrap gap-2">
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 leading-tight">
                    {{ $greeting }},
                    <span class="text-green-600 dark:text-green-400">{{ $name }}</span>!
                </h3>
                {{-- Role badge --}}
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold
                             uppercase tracking-wide {{ $meta['badgeBg'] }} {{ $meta['badgeText'] }}"
                      aria-label="Role: {{ $meta['label'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $meta['dot'] }}" aria-hidden="true"></span>
                    {{ $meta['label'] }}
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">
                {{ $subtitle }}
            </p>
        </div>
    </div>

    {{-- Right: current date/time chip --}}
    <div class="flex-shrink-0 hidden sm:flex items-center gap-2
                px-4 py-2.5 rounded-xl
                bg-white dark:bg-slate-800
                border border-slate-200 dark:border-slate-700
                shadow-sm text-sm">
        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <time datetime="{{ now()->toDateString() }}"
              class="font-semibold text-slate-600 dark:text-slate-300">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </time>
    </div>

</div>