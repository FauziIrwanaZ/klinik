{{--
|─────────────────────────────────────────────────────────────────────────────
| Component: <x-dashboard.empty-state />
|─────────────────────────────────────────────────────────────────────────────
| Props:
|   @prop string $title     — Judul empty state
|   @prop string $message   — Pesan penjelas (lebih panjang)
|   @prop string $icon      — Path SVG Heroicon (stroke)
|   @prop string $action    — Label tombol aksi (opsional)
|   @prop string $href      — URL tombol aksi (opsional)
|   @prop string $color     — Warna tema: green | blue | amber (default green)
|─────────────────────────────────────────────────────────────────────────────
--}}

@props([
    'title'   => 'Belum ada data',
    'message' => 'Data akan muncul di sini setelah ditambahkan.',
    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>',
    'action'  => null,
    'href'    => null,
    'color'   => 'green',
])

@php
$palette = [
    'green' => ['iconBg' => 'bg-green-50 dark:bg-green-900/20',   'icon' => 'text-green-400',  'btn' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500'],
    'blue'  => ['iconBg' => 'bg-sky-50 dark:bg-sky-900/20',       'icon' => 'text-sky-400',    'btn' => 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500'],
    'amber' => ['iconBg' => 'bg-amber-50 dark:bg-amber-900/20',   'icon' => 'text-amber-400',  'btn' => 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500'],
];
$c = $palette[$color] ?? $palette['green'];
@endphp

<div class="flex flex-col items-center justify-center px-6 py-14 text-center"
     role="status"
     aria-label="{{ $title }}">

    {{-- Icon circle --}}
    <div class="w-16 h-16 rounded-2xl {{ $c['iconBg'] }} flex items-center justify-center mb-4" aria-hidden="true">
        <svg class="w-8 h-8 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
            {!! $icon !!}
        </svg>
    </div>

    {{-- Title --}}
    <p class="text-base font-bold text-slate-700 dark:text-slate-300 mb-1">{{ $title }}</p>

    {{-- Message --}}
    <p class="text-sm text-slate-400 dark:text-slate-500 max-w-xs leading-relaxed">{{ $message }}</p>

    {{-- CTA Button (optional) --}}
    @if($action && $href)
        <a href="{{ $href }}"
           class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                  text-white {{ $c['btn'] }} shadow-sm transition-colors duration-150
                  focus:outline-none focus:ring-2 focus:ring-offset-2"
           aria-label="{{ $action }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            {{ $action }}
        </a>
    @endif

</div>