{{-- ══════════════════════════════════════════════════════════════════
     VIEW  : transaksi-component.blade.php
     Komponen: app/Livewire/TransaksiComponent.php
     Role   : admin, petugas (CRUD), pasien (view only)
     Tema   : Putih–Hijau Profesional (Klinik Rawat Inap)
     ══════════════════════════════════════════════════════════════════ --}}

<div class="space-y-5">

    {{-- ════════════════════════════════════════════════ --}}
    {{-- FLASH MESSAGE — sukses & error (auto-dismiss)   --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if (session()->has('pesan'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-3 bg-green-50 border border-green-200
                   text-green-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">✅</span>
            <span class="flex-1 font-medium text-sm">{{ session('pesan') }}</span>
            <button @click="tampil = false"
                    class="text-green-400 hover:text-green-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            x-data="{ tampil: true }"
            x-show="tampil"
            x-init="setTimeout(() => tampil = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-3 bg-red-50 border border-red-200
                   text-red-800 px-5 py-3 rounded-xl shadow-sm"
        >
            <span class="text-xl">❌</span>
            <span class="flex-1 font-medium text-sm">{{ session('error') }}</span>
            <button @click="tampil = false"
                    class="text-red-400 hover:text-red-700 text-xl font-bold leading-none">×</button>
        </div>
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- KARTU STATISTIK — 3 kolom                       --}}
    {{-- ════════════════════════════════════════════════ --}}

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Belum Bayar --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-amber-400 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Belum Bayar</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistik['belum_bayar'] ?? 0 }}
                    </p>
                    <p class="text-xs text-amber-600 mt-1 font-medium">⏳ Menunggu pembayaran</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">
                    🧾
                </div>
            </div>
        </div>

        {{-- Cicilan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-blue-400 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Cicilan</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistik['cicilan'] ?? 0 }}
                    </p>
                    <p class="text-xs text-blue-600 mt-1 font-medium">💳 Pembayaran bertahap</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">
                    🔄
                </div>
            </div>
        </div>

        {{-- Lunas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4
                    border-l-4 border-l-green-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Lunas</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">
                        {{ $statistik['lunas'] ?? 0 }}
                    </p>
                    <p class="text-xs text-green-600 mt-1 font-medium">✅ Sudah dibayar</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>
        </div>

    </div>{{-- akhir statistik --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- KARTU UTAMA — tabel data transaksi              --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- ── HEADER + TOMBOL BUAT TAGIHAN ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    gap-3 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-green-900">💳 Transaksi / Tagihan Pasien</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manajemen pembayaran rawat inap klinik</p>
            </div>

            {{-- Tombol hanya muncul untuk admin & petugas --}}
            @if (!$hanyaLihat)
                <button
                    wire:click="bukaFormTambah"
                    class="flex items-center gap-2 bg-green-700 hover:bg-green-800
                           active:bg-green-900 text-white text-sm font-semibold
                           px-5 py-2.5 rounded-xl shadow-sm shadow-green-200
                           hover:shadow-md hover:shadow-green-300
                           transition-all duration-200 active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tagihan Baru
                </button>
            @else
                {{-- Banner info untuk pasien --}}
                <div class="flex items-center gap-2 bg-blue-50 border border-blue-100
                            text-blue-700 text-xs font-medium px-4 py-2.5 rounded-xl">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tampilan hanya baca — riwayat tagihan Anda
                </div>
            @endif
        </div>

        {{-- ── TOOLBAR: CARI + FILTER ── --}}
        <div class="flex flex-col sm:flex-row gap-3 px-6 py-4 bg-slate-50/50 border-b border-slate-100">

            {{-- Input pencarian --}}
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    wire:model.live.debounce.300ms="cari"
                    type="text"
                    placeholder="Cari nomor invoice atau nama pasien..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:border-transparent transition-all duration-200"
                >
            </div>

            {{-- Filter status bayar --}}
            <select
                wire:model.live="filterStatusBayar"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white
                       focus:outline-none focus:ring-2 focus:ring-green-500
                       focus:border-transparent transition-all duration-200 min-w-[175px]"
            >
                <option value="">Semua Status</option>
                <option value="belum_bayar">⏳ Belum Bayar</option>
                <option value="cicilan">💳 Cicilan</option>
                <option value="lunas">✅ Lunas</option>
            </select>

            {{-- Indikator loading --}}
            <div wire:loading wire:target="cari,filterStatusBayar"
                 class="flex items-center gap-2 text-green-600 text-sm font-medium px-2 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Memuat...
            </div>
        </div>

        {{-- ── TABEL TRANSAKSI ── --}}
        <div class="overflow-x-auto"
             wire:loading.class="opacity-50"
             wire:target="cari,filterStatusBayar">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-800 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-4 text-left font-semibold">Invoice</th>
                        <th class="px-5 py-4 text-left font-semibold">Pasien</th>
                        <th class="px-5 py-4 text-left font-semibold">Kamar</th>
                        <th class="px-5 py-4 text-right font-semibold">Total Tagihan</th>
                        <th class="px-5 py-4 text-left font-semibold">Metode Bayar</th>
                        <th class="px-5 py-4 text-center font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Tgl Bayar</th>
                        @if (!$hanyaLihat)
                            <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse ($transaksi as $t)
                        <tr class="hover:bg-green-50/60 transition-colors duration-150">

                            {{-- Nomor Invoice --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-bold text-green-800 bg-green-50
                                             border border-green-200 px-2 py-1 rounded-lg text-xs">
                                    {{ $t->nomor_invoice ?? 'INV-'.$t->id }}
                                </span>
                            </td>

                            {{-- Nama Pasien --}}
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">
                                    {{ $t->rawatInap->pasien->nama ?? '—' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $t->rawatInap->pasien->nomor_rm ?? '' }}
                                </div>
                            </td>

                            {{-- Nomor Kamar --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs text-slate-600 bg-slate-100
                                             px-2 py-1 rounded-lg">
                                    {{ $t->rawatInap->kamar->nomor_kamar ?? '—' }}
                                </span>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $t->rawatInap->kamar->tipe_kamar ?? '' }}
                                </p>
                            </td>

                            {{-- Total Tagihan --}}
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-bold text-slate-800">
                                    {{ $t->total_format ?? 'Rp '.number_format($t->total_biaya, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Metode Bayar --}}
                            <td class="px-5 py-3.5">
                                @php
                                    $metodeLabel = match($t->cara_bayar ?? '') {
                                        'tunai'    => ['💵', 'Tunai',   'bg-slate-100 text-slate-600'],
                                        'bpjs'     => ['🏥', 'BPJS',   'bg-teal-100 text-teal-700'],
                                        'asuransi' => ['🛡️', 'Asuransi','bg-indigo-100 text-indigo-700'],
                                        'transfer' => ['🏦', 'Transfer','bg-purple-100 text-purple-700'],
                                        default    => ['💳', $t->cara_bayar ?? '—', 'bg-slate-100 text-slate-500'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg
                                             text-xs font-semibold {{ $metodeLabel[2] }}">
                                    {{ $metodeLabel[0] }} {{ $metodeLabel[1] }}
                                </span>
                            </td>

                            {{-- Badge Status Bayar --}}
                            <td class="px-5 py-3.5 text-center">
                                @if(($t->status_bayar ?? '') === 'lunas')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-green-100 text-green-700
                                                 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Lunas
                                    </span>
                                @elseif(($t->status_bayar ?? '') === 'cicilan')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-blue-100 text-blue-700
                                                 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Cicilan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                                 text-xs font-semibold bg-amber-100 text-amber-700
                                                 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal Bayar --}}
                            <td class="px-5 py-3.5 text-slate-500 text-xs">
                                @if($t->tanggal_bayar)
                                    <div class="font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($t->tanggal_bayar)->format('d M Y') }}
                                    </div>
                                    <div class="text-slate-400">
                                        {{ \Carbon\Carbon::parse($t->tanggal_bayar)->format('H:i') }} WIB
                                    </div>
                                @else
                                    <span class="text-slate-300 italic">—</span>
                                @endif
                            </td>

                            {{-- Tombol Aksi (hanya admin & petugas) --}}
                            @if (!$hanyaLihat)
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">

                                        {{-- Tandai Lunas — hanya tampil jika belum lunas --}}
                                        @if(($t->status_bayar ?? '') !== 'lunas')
                                            <button
                                                wire:click="tandaiLunas({{ $t->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="tandaiLunas({{ $t->id }})"
                                                title="Tandai tagihan ini sebagai lunas"
                                                class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100
                                                       border border-green-200 text-green-700 font-semibold
                                                       text-xs px-2.5 py-1.5 rounded-lg transition-all duration-150
                                                       active:scale-95 disabled:opacity-60"
                                            >
                                                <span wire:loading.remove wire:target="tandaiLunas({{ $t->id }})">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                                <span wire:loading wire:target="tandaiLunas({{ $t->id }})">
                                                    <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                    </svg>
                                                </span>
                                                Lunas
                                            </button>
                                        @else
                                            {{-- Sudah lunas: tampilkan cetak struk (opsional) --}}
                                            <span class="inline-flex items-center gap-1 text-green-600 text-xs
                                                         font-medium bg-green-50 px-2.5 py-1.5 rounded-lg
                                                         border border-green-100">
                                                ✅ Lunas
                                            </span>
                                        @endif

                                        {{-- Hapus --}}
                                        <button
                                            wire:click="hapus({{ $t->id }})"
                                            title="Hapus transaksi ini"
                                            class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100
                                                   border border-red-200 text-red-600 font-semibold
                                                   text-xs px-2.5 py-1.5 rounded-lg transition-all duration-150
                                                   active:scale-95"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            @endif

                        </tr>

                    @empty
                        {{-- State Kosong --}}
                        <tr>
                            <td colspan="{{ $hanyaLihat ? 7 : 8 }}" class="px-5 py-16 text-center">
                                <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-16 h-16 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-slate-500">
                                            @if($cari || $filterStatusBayar)
                                                Tidak ada hasil yang sesuai filter
                                            @else
                                                Belum ada data transaksi
                                            @endif
                                        </p>
                                        <p class="text-sm mt-1 text-slate-400">
                                            @if($cari || $filterStatusBayar)
                                                Coba ubah kata kunci atau filter status
                                            @elseif(!$hanyaLihat)
                                                Klik "Buat Tagihan Baru" untuk memulai
                                            @else
                                                Belum ada tagihan untuk akun Anda
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

        {{-- Footer: info jumlah + pagination --}}
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-slate-400">
                Menampilkan {{ $transaksi->count() }} dari {{ $transaksi->total() }} transaksi
            </p>
            <div>
                {{ $transaksi->links() }}
            </div>
        </div>

    </div>{{-- akhir kartu utama --}}


    {{-- ════════════════════════════════════════════════ --}}
    {{-- MODAL FORM BUAT TAGIHAN                         --}}
    {{-- Dikontrol oleh $tampilForm dari Livewire         --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if ($tampilForm && !$hanyaLihat)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data
        x-on:keydown.escape.window="$wire.set('tampilForm', false)"
    >
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="$set('tampilForm', false)"></div>

        {{-- Kontainer modal --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[92vh] overflow-y-auto"
                 x-on:click.stop>

                {{-- Header Modal --}}
                <div class="sticky top-0 z-10 flex items-center justify-between
                            px-7 py-5 bg-green-800 rounded-t-3xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center
                                    justify-center text-xl">💳</div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Buat Tagihan Baru</h3>
                            <p class="text-xs text-green-300">Pilih rawat inap dan isi detail pembayaran</p>
                        </div>
                    </div>
                    <button
                        wire:click="$set('tampilForm', false)"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-green-200 hover:text-white hover:bg-green-700
                               transition-all text-2xl font-bold leading-none"
                    >×</button>
                </div>

                {{-- Body Form --}}
                <div class="px-7 py-6 space-y-5">

                    {{-- ── SEKSI 1: PILIH RAWAT INAP ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-green-100 text-green-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">1</span>
                            <p class="text-xs font-bold text-green-700 uppercase tracking-widest">
                                Pilih Rawat Inap
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-3">

                            {{-- Dropdown rawat inap yang belum ditagih --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Pasien / Rawat Inap
                                    <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model.live="rawatInapId"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm bg-white
                                           outline-none focus:ring-2 focus:ring-green-500
                                           focus:border-transparent transition-all
                                           @error('rawatInapId') border-red-400 @else border-slate-200 @enderror"
                                >
                                    <option value="">— Pilih pasien yang akan ditagih —</option>
                                    @forelse ($rawatInapBelumTagih as $ri)
                                        <option value="{{ $ri->id }}">
                                            {{ $ri->pasien->nama ?? '?' }}
                                            — Kamar {{ $ri->kamar->nomor_kamar ?? '?' }}
                                            ({{ $ri->tanggal_masuk?->format('d M Y') }})
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada rawat inap yang belum ditagih</option>
                                    @endforelse
                                </select>
                                @error('rawatInapId')
                                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                        <span>⚠</span> {{ $message }}
                                    </p>
                                @enderror
                                @if(count($rawatInapBelumTagih) === 0)
                                    <p class="text-xs text-amber-600 mt-1.5">
                                        ⚠️ Semua pasien rawat inap sudah memiliki tagihan.
                                    </p>
                                @endif
                            </div>

                            {{-- ── RINGKASAN BIAYA OTOMATIS ── --}}
                            {{-- Muncul saat rawat inap dipilih dan biaya sudah dihitung --}}
                            @if ($rawatInapId && ($biayaKamar > 0 || $biayaDokter > 0))
                                <div class="bg-white border border-green-100 rounded-xl overflow-hidden mt-2">
                                    <div class="px-4 py-2.5 bg-green-800">
                                        <p class="text-xs font-bold text-green-100 uppercase tracking-wide">
                                            📊 Ringkasan Biaya Otomatis
                                        </p>
                                    </div>
                                    <div class="px-4 py-3 space-y-2.5">

                                        {{-- Biaya Kamar --}}
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm">🛏</span>
                                                <span class="text-sm text-slate-600">Biaya Kamar</span>
                                                @if($rawatInapId)
                                                    <span class="text-xs text-slate-400">
                                                        ({{ $lamaRawat ?? '?' }} malam × Rp {{ number_format($hargaPerMalam ?? 0, 0, ',', '.') }})
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="font-semibold text-slate-800 text-sm">
                                                Rp {{ number_format($biayaKamar, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        {{-- Biaya Dokter --}}
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm">🩺</span>
                                                <span class="text-sm text-slate-600">Biaya Dokter</span>
                                            </div>
                                            <span class="font-semibold text-slate-800 text-sm">
                                                Rp {{ number_format($biayaDokter, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        {{-- Biaya Tambahan --}}
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm">➕</span>
                                                <span class="text-sm text-slate-600">Biaya Tambahan</span>
                                            </div>
                                            <span class="font-semibold text-slate-800 text-sm">
                                                Rp {{ number_format((float) ($biayaLain ?? 0), 0, ',', '.') }}
                                            </span>
                                        </div>

                                        {{-- Garis pemisah --}}
                                        <div class="border-t border-dashed border-slate-200 pt-2.5 mt-1">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-bold text-slate-700">TOTAL TAGIHAN</span>
                                                <span class="text-xl font-bold text-green-700">
                                                    Rp {{ number_format((float)($totalBiaya ?? 0), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @elseif($rawatInapId)
                                {{-- Loading kalkulasi --}}
                                <div class="flex items-center gap-2 text-green-600 text-sm py-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Menghitung biaya...
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- ── SEKSI 2: DETAIL PEMBAYARAN ── --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg
                                         flex items-center justify-center text-sm font-bold">2</span>
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-widest">
                                Detail Pembayaran
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 space-y-4">

                            {{-- Biaya Tambahan --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Biaya Tambahan
                                    <span class="text-slate-400 font-normal text-xs">(opsional — obat, lab, dll)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2
                                                 text-xs text-slate-500 font-semibold">Rp</span>
                                    <input
                                        wire:model.live="biayaLain"
                                        type="number"
                                        min="0" step="1000"
                                        placeholder="0"
                                        class="w-full border border-slate-200 bg-white rounded-xl pl-9 pr-4 py-2.5
                                               text-sm outline-none focus:ring-2 focus:ring-green-500
                                               focus:border-transparent transition-all
                                               @error('biayaLain') border-red-400 @enderror"
                                    >
                                </div>
                                @error('biayaLain')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Metode Bayar + Status Bayar (2 kolom) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="caraBayar"
                                        class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('caraBayar') border-red-400 @enderror"
                                    >
                                        <option value="">— Pilih —</option>
                                        <option value="tunai">💵 Tunai</option>
                                        <option value="transfer">🏦 Transfer Bank</option>
                                        <option value="bpjs">🏥 BPJS Kesehatan</option>
                                        <option value="asuransi">🛡️ Asuransi Swasta</option>
                                    </select>
                                    @error('caraBayar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Status Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        wire:model="statusBayar"
                                        class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('statusBayar') border-red-400 @enderror"
                                    >
                                        <option value="belum_bayar">⏳ Belum Bayar</option>
                                        <option value="cicilan">💳 Cicilan</option>
                                        <option value="lunas">✅ Lunas</option>
                                    </select>
                                    @error('statusBayar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Bayar — hanya muncul jika status = lunas/cicilan --}}
                            @if (in_array($statusBayar ?? '', ['lunas', 'cicilan']))
                                <div
                                    x-data
                                    x-show="true"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                >
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Tanggal Bayar
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        wire:model="tanggalBayar"
                                        type="date"
                                        max="{{ date('Y-m-d') }}"
                                        class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                               outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                               transition-all @error('tanggalBayar') border-red-400 @enderror"
                                    >
                                    @error('tanggalBayar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Catatan
                                    <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                                </label>
                                <textarea
                                    wire:model="catatan"
                                    rows="2"
                                    placeholder="Catatan tambahan terkait pembayaran..."
                                    class="w-full border border-slate-200 bg-white rounded-xl px-4 py-2.5 text-sm
                                           outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                           transition-all resize-none"
                                ></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- ── KOTAK TOTAL AKHIR ── --}}
                    @if ($rawatInapId && $totalBiaya > 0)
                        <div class="bg-gradient-to-r from-green-700 to-green-800 rounded-2xl px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-200 font-semibold uppercase tracking-wide">
                                        Total yang Harus Dibayar
                                    </p>
                                    <p class="text-3xl font-bold text-white mt-1">
                                        Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-4xl opacity-50">💰</div>
                            </div>
                        </div>
                    @endif

                    {{-- ── TOMBOL AKSI FORM ── --}}
                    <div class="flex gap-3 pt-1">
                        <button
                            wire:click="simpan"
                            wire:loading.attr="disabled"
                            wire:target="simpan"
                            class="flex-1 flex items-center justify-center gap-2
                                   bg-green-700 hover:bg-green-800 disabled:opacity-60
                                   text-white font-bold py-3 rounded-xl transition-all
                                   active:scale-[0.98] shadow-md shadow-green-200"
                        >
                            <span wire:loading.remove wire:target="simpan">
                                ✅  Simpan Tagihan
                            </span>
                            <span wire:loading wire:target="simpan" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>

                        <button
                            wire:click="$set('tampilForm', false)"
                            type="button"
                            class="px-7 bg-slate-100 hover:bg-slate-200 text-slate-700
                                   font-semibold py-3 rounded-xl transition-all"
                        >
                            Batal
                        </button>
                    </div>

                </div>{{-- akhir body form --}}
            </div>{{-- akhir modal card --}}
        </div>{{-- akhir modal container --}}
    </div>{{-- akhir modal overlay --}}
    @endif


    {{-- ════════════════════════════════════════════════ --}}
    {{-- DIALOG KONFIRMASI HAPUS                         --}}
    {{-- ════════════════════════════════════════════════ --}}

    @if (isset($konfirmasiHapus) && $konfirmasiHapus && !$hanyaLihat)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data
        x-on:keydown.escape.window="$wire.batalHapus()"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             wire:click="batalHapus"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-7 text-center"
             x-on:click.stop>

            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center
                        text-3xl mx-auto mb-4">🗑️</div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Transaksi?</h3>
            <p class="text-sm text-slate-500 mb-4">
                Tindakan ini akan menghapus data tagihan secara permanen
                dan tidak dapat dibatalkan.
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-left">
                <p class="text-xs text-amber-800">
                    ⚠️ <strong>Perhatian:</strong>
                    Status rawat inap akan dikembalikan ke aktif
                    jika tagihan yang dihapus berstatus lunas.
                </p>
            </div>

            <div class="flex gap-3">
                <button
                    wire:click="hapus"
                    wire:loading.attr="disabled"
                    wire:target="hapus"
                    class="flex-1 flex items-center justify-center gap-2
                           bg-red-600 hover:bg-red-700 disabled:opacity-60
                           text-white font-bold py-3 rounded-xl transition-all active:scale-95"
                >
                    <span wire:loading.remove wire:target="hapus">Ya, Hapus</span>
                    <span wire:loading wire:target="hapus" class="flex items-center gap-1.5">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Menghapus...
                    </span>
                </button>

                <button
                    wire:click="batalHapus"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700
                           font-semibold py-3 rounded-xl transition-all"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

</div>{{-- akhir komponen utama --}}