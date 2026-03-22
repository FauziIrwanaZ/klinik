{{--
|─────────────────────────────────────────────────────────────────────────────
| Component: <x-dashboard.card-stat />
|─────────────────────────────────────────────────────────────────────────────
| Props:
|   @prop string  $title       — Label di atas angka, e.g. "Total Pasien"
|   @prop mixed   $value       — Nilai utama (angka / string), e.g. 42
|   @prop string  $desc        — Keterangan kecil di bawah nilai, e.g. "Terdaftar"
|   @prop string  $color       — Tema warna: green | blue | orange | red | amber | violet | teal
|   @prop string  $icon        — Path SVG Heroicon (stroke only)
|   @prop string  $href        — (opsional) URL saat card diklik
|   @prop int     $delay       — Delay animasi dalam ms (default 0), untuk staggered entrance
|─────────────────────────────────────────────────────────────────────────────
--}}

@props([
    'title'  => 'Statistik',
    'value'  => 0,
    'desc'   => '',
    'color'  => 'green',
    'icon'   => '',
    'href'   => null,
    'delay'  => 0,
])

@php
/**
 * Color palette map.
 * Setiap warna memiliki 5 token: bg card, icon bg, icon color, accent bar, text value.
 */
$palette = [
    'green'  => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-green-100 dark:bg-green-900/40',  'iconClr' => 'text-green-600 dark:text-green-400',  'bar' => 'bg-green-500',  'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-green-600 dark:text-green-400'],
    'blue'   => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-sky-100 dark:bg-sky-900/40',      'iconClr' => 'text-sky-600 dark:text-sky-400',      'bar' => 'bg-sky-500',    'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-sky-600 dark:text-sky-400'],
    'orange' => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-orange-100 dark:bg-orange-900/40','iconClr' => 'text-orange-600 dark:text-orange-400','bar' => 'bg-orange-500', 'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-orange-600 dark:text-orange-400'],
    'red'    => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-red-100 dark:bg-red-900/40',      'iconClr' => 'text-red-600 dark:text-red-400',      'bar' => 'bg-red-500',    'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-red-600 dark:text-red-400'],
    'amber'  => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-amber-100 dark:bg-amber-900/40',  'iconClr' => 'text-amber-600 dark:text-amber-400',  'bar' => 'bg-amber-500',  'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-amber-600 dark:text-amber-400'],
    'violet' => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-violet-100 dark:bg-violet-900/40','iconClr' => 'text-violet-600 dark:text-violet-400','bar' => 'bg-violet-500', 'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-violet-600 dark:text-violet-400'],
    'teal'   => ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-teal-100 dark:bg-teal-900/40',    'iconClr' => 'text-teal-600 dark:text-teal-400',    'bar' => 'bg-teal-500',   'val' => 'text-slate-800 dark:text-slate-100', 'desc' => 'text-teal-600 dark:text-teal-400'],
    'emerald'=> ['card' => 'bg-white dark:bg-slate-800',   'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/40','iconClr'=>'text-emerald-600 dark:text-emerald-400','bar'=>'bg-emerald-500','val'=>'text-slate-800 dark:text-slate-100','desc'=>'text-emerald-600 dark:text-emerald-400'],
];

$c = $palette[$color] ?? $palette['green'];

// Wrap in <a> jika ada href, else <div>
$tag = $href ? 'a' : 'div';
$attrs = $href ? "href={$href}" : '';
@endphp

{{-- Card wrapper — Alpine handles fade-in + hover scale --}}
 <{{ $tag }}
    {{ $attributes->merge($href ? ['href' => $href] : []) }}
    x-data="{ hovered: false }"
    x-init="
        setTimeout(() => {
            $el.style.opacity = '1';
            $el.style.transform = 'translateY(0)';
        }, {{ $delay }});
    "
    @mouseenter="hovered = true"
    @mouseleave="hovered = false"
    :class="hovered ? 'shadow-lg -translate-y-0.5' : 'shadow-sm'"
    style="opacity:0; transform:translateY(10px); transition: opacity .4s ease, transform .4s ease, box-shadow .2s ease;"
    class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-700
           {{ $c['card'] }} transition-all duration-200
           {{ $href ? 'cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500' : '' }}"
    @if($href) aria-label="Lihat detail {{ $title }}" @endif
    role="{{ $href ? 'link' : 'region' }}"
    aria-label="{{ $title }}: {{ $value }}">

    {{-- Colored accent bar (top) --}}
    <div class="absolute top-0 inset-x-0 h-0.5 {{ $c['bar'] }}" aria-hidden="true"></div>

    <div class="px-5 pt-5 pb-4">
        {{-- Header: icon + title --}}
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-none">
                {{ $title }}
            </p>
            @if($icon)
                <span class="flex-shrink-0 w-9 h-9 rounded-xl {{ $c['iconBg'] }} {{ $c['iconClr'] }}
                             flex items-center justify-center" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8">
                        {!! $icon !!}
                    </svg>
                </span>
            @endif
        </div>

        {{-- Value --}}
        <p class="text-4xl font-extrabold {{ $c['val'] }} tabular-nums leading-none tracking-tight">
            {{ $value }}
        </p>

        {{-- Description --}}
        @if($desc)
            <p class="mt-2 text-xs font-medium {{ $c['desc'] }} leading-none">
                {{ $desc }}
            </p>
        @endif
    </div>

    {{-- Hover arrow indicator (only when clickable) --}}
    @if($href)
        <div class="absolute bottom-3 right-4 transition-opacity duration-150"
             :class="hovered ? 'opacity-100' : 'opacity-0'"
             aria-hidden="true">
            <svg class="w-4 h-4 {{ $c['iconClr'] }}" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </div>
    @endif

</{{ $tag }}>