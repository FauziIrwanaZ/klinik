{{--
|─────────────────────────────────────────────────────────────────────────────
| Component: <x-dashboard.panel />
|─────────────────────────────────────────────────────────────────────────────
| Props:
|   @prop string $title     — Judul panel, e.g. "Rawat Inap Terbaru"
|   @prop string $href      — URL tombol "Lihat semua" (opsional)
|   @prop string $linkLabel — Label tombol link (default: "Lihat semua")
|   @prop string $color     — Warna header: green | blue | slate | violet
|   @prop int    $delay     — Delay animasi entrance (ms)
|
| Slot default: konten tabel / list rows
|─────────────────────────────────────────────────────────────────────────────
--}}

@props([
    'title'     => 'Data',
    'href'      => null,
    'linkLabel' => 'Lihat semua',
    'color'     => 'green',
    'delay'     => 200,
])

@php
$headerPalette = [
    'green'  => 'from-green-800 to-green-700',
    'blue'   => 'from-sky-700 to-blue-700',
    'slate'  => 'from-slate-700 to-slate-600',
    'violet' => 'from-violet-700 to-purple-700',
    'teal'   => 'from-teal-700 to-emerald-700',
];
$grad = $headerPalette[$color] ?? $headerPalette['green'];
@endphp

<div x-data
     x-init="setTimeout(() => { $el.style.opacity='1'; $el.style.transform='translateY(0)' }, {{ $delay }})"
     style="opacity:0; transform:translateY(10px); transition: opacity .45s ease, transform .45s ease"
     class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm
            border border-slate-100 dark:border-slate-700 overflow-hidden"
     role="region"
     aria-label="{{ $title }}">

    {{-- Panel Header --}}
    <div class="flex items-center justify-between px-5 py-4
                bg-gradient-to-r {{ $grad }}">

        <h4 class="font-bold text-white text-sm tracking-wide">{{ $title }}</h4>

        @if($href)
            <a href="{{ $href }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-white/80
                      hover:text-white transition-colors duration-100
                      focus:outline-none focus:ring-2 focus:ring-white/50 rounded"
               aria-label="{{ $linkLabel }} — {{ $title }}">
                {{ $linkLabel }}
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        @endif
    </div>

    {{-- Panel Body (default slot) --}}
    <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
        {{ $slot }}
    </div>

</div>