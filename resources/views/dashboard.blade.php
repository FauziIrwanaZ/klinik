@extends('layouts.app')
 
@section('judul', 'Dashboard')
@section('header', 'Dashboard')
@section('sub-header', 'Ringkasan sistem klinik rawat inap')
 
@section('konten')
 
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- DASHBOARD ADMIN --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($role === 'admin')
 
    {{-- Salam selamat datang --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-slate-700">
            👑 Selamat datang, <span class="text-green-700">{{ auth()->user()->nama }}</span>!
        </h3>
        <p class="text-sm text-slate-400">Berikut adalah ringkasan operasional klinik hari ini.</p>
    </div>
 
    {{-- Kartu Statistik Utama --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
 
        {{-- Total Pasien --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100
                    border-l-4 border-l-green-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Pasien</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $data['total_pasien'] }}</p>
                    <p class="text-xs text-green-600 mt-1">👥 Terdaftar</p>
                </div>
                <span class="text-3xl opacity-40">👥</span>
            </div>
        </div>
 
        {{-- Total Dokter --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100
                    border-l-4 border-l-blue-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Dokter</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $data['total_dokter'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">🩺 Aktif praktik</p>
                </div>
                <span class="text-3xl opacity-40">👨‍⚕️</span>
            </div>
        </div>
 
        {{-- Sedang Dirawat --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100
                    border-l-4 border-l-orange-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Dirawat</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $data['sedang_dirawat'] }}</p>
                    <p class="text-xs text-orange-600 mt-1">🏥 Pasien aktif</p>
                </div>
                <span class="text-3xl opacity-40">🛏</span>
            </div>
        </div>
 
        {{-- Kamar Tersedia --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100
                    border-l-4 border-l-emerald-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Kamar Tersedia</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $data['kamar_tersedia'] }}</p>
                    <p class="text-xs text-emerald-600 mt-1">✅ Siap digunakan</p>
                </div>
                <span class="text-3xl opacity-40">🏨</span>
            </div>
        </div>
    </div>
 
    {{-- Baris kedua statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-700 rounded-2xl p-5 text-white shadow-md">
            <p class="text-xs text-green-200 uppercase tracking-wide">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold mt-1">
                Rp {{ number_format($data['pendapatan_bulan'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-green-300 mt-1">💰 Transaksi lunas</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-500 uppercase tracking-wide">Selesai Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $data['selesai_hari_ini'] }}</p>
            <p class="text-xs text-slate-400">📅 Pasien pulang</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-500 uppercase tracking-wide">Kamar Terisi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $data['kamar_terisi'] }}</p>
            <p class="text-xs text-slate-400">🔴 Sedang digunakan</p>
        </div>
    </div>
 
    {{-- Tabel Rawat Inap Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h4 class="font-bold text-slate-700">🕐 Rawat Inap Terbaru</h4>
            <a href="{{ route('rawat-inap') }}" class="text-green-600 text-sm font-medium hover:underline">
                Lihat semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-slate-500 text-xs uppercase tracking-wide">
                        <th class="px-5 py-3 text-left">Pasien</th>
                        <th class="px-5 py-3 text-left">Dokter</th>
                        <th class="px-5 py-3 text-left">Kamar</th>
                        <th class="px-5 py-3 text-left">Tgl Masuk</th>
                        <th class="px-5 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data['rawat_inap_terbaru'] as $ri)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-5 py-3.5 font-medium">{{ $ri->pasien->nama }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $ri->dokter->nama_lengkap }}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded-lg">
                                    {{ $ri->kamar->nomor_kamar }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $ri->tanggal_masuk->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $ri->status === 'dirawat'
                                        ? 'bg-blue-100 text-blue-700'
                                        : 'bg-green-100 text-green-700' }}">
                                    {{ $ri->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">
                            Belum ada data rawat inap.
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
 
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- DASHBOARD PETUGAS --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($role === 'petugas')
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-slate-700">
            📋 Selamat datang, <span class="text-green-700">{{ auth()->user()->nama }}</span>!
        </h3>
    </div>
 
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-blue-500">
            <p class="text-xs text-slate-500 uppercase">Sedang Dirawat</p>
            <p class="text-3xl font-bold mt-1">{{ $data['sedang_dirawat'] }}</p>
            <p class="text-xs text-blue-500 mt-1">🏥 Pasien aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-green-500">
            <p class="text-xs text-slate-500 uppercase">Kamar Tersedia</p>
            <p class="text-3xl font-bold mt-1">{{ $data['kamar_tersedia'] }}</p>
            <p class="text-xs text-green-500 mt-1">✅ Siap pakai</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-red-400">
            <p class="text-xs text-slate-500 uppercase">Kamar Terisi</p>
            <p class="text-3xl font-bold mt-1">{{ $data['kamar_terisi'] }}</p>
            <p class="text-xs text-red-500 mt-1">🔴 Digunakan</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-amber-400">
            <p class="text-xs text-slate-500 uppercase">Tagihan Pending</p>
            <p class="text-3xl font-bold mt-1">{{ $data['tagihan_pending'] }}</p>
            <p class="text-xs text-amber-500 mt-1">⏳ Belum bayar</p>
        </div>
    </div>
 
    {{-- Rawat Inap Aktif --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 bg-green-800 flex justify-between items-center">
            <h4 class="font-bold text-white">🏥 Pasien Aktif Dirawat</h4>
            <a href="{{ route('rawat-inap') }}"
               class="text-green-200 text-sm hover:text-white">Kelola →</a>
        </div>
        <div class="divide-y">
            @forelse($data['rawat_inap_aktif'] as $ri)
                <div class="px-5 py-4 flex items-center justify-between hover:bg-green-50">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $ri->pasien->nama }}</p>
                        <p class="text-xs text-slate-400">
                            🛏 Kamar {{ $ri->kamar->nomor_kamar }} · {{ $ri->tipe_kamar }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Masuk: {{ $ri->tanggal_masuk->format('d M') }}</p>
                        <p class="text-xs text-blue-600 font-medium">{{ $ri->lama_dirawat }} hari</p>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-slate-400">Tidak ada pasien aktif.</p>
            @endforelse
        </div>
    </div>
@endif
 
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- DASHBOARD DOKTER --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($role === 'dokter')
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-slate-700">
            🩺 Selamat datang, <span class="text-green-700">dr. {{ auth()->user()->nama }}</span>!
        </h3>
    </div>
 
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-700 rounded-2xl p-6 text-white shadow-md">
            <p class="text-xs text-green-200 uppercase tracking-wide">Pasien Aktif Saya</p>
            <p class="text-4xl font-bold mt-2">{{ $data['pasien_aktif'] }}</p>
            <p class="text-xs text-green-300 mt-2">🏥 Sedang dirawat</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-500 uppercase">Total Pasien Ditangani</p>
            <p class="text-4xl font-bold text-slate-800 mt-2">{{ $data['total_pasien'] }}</p>
            <p class="text-xs text-slate-400 mt-2">📊 Sepanjang waktu</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-500 uppercase">Selesai Bulan Ini</p>
            <p class="text-4xl font-bold text-slate-800 mt-2">{{ $data['selesai_bulan'] }}</p>
            <p class="text-xs text-slate-400 mt-2">✅ Pasien pulang</p>
        </div>
    </div>
 
    {{-- Daftar Pasien Dokter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 bg-blue-700 flex justify-between items-center">
            <h4 class="font-bold text-white">🩺 Pasien Aktif Saya</h4>
            <a href="{{ route('rawat-inap') }}" class="text-blue-200 text-sm hover:text-white">
                Input Diagnosa →
            </a>
        </div>
        <div class="divide-y">
            @forelse($data['pasien_terbaru'] as $ri)
                <div class="px-5 py-4 flex items-center justify-between hover:bg-blue-50">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $ri->pasien->nama }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @if($ri->diagnosa)
                                📋 {{ Str::limit($ri->diagnosa, 40) }}
                            @else
                                <span class="text-amber-500">⚠️ Belum ada diagnosa</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">🛏 {{ $ri->kamar->nomor_kamar }}</p>
                        <p class="text-xs text-blue-600">{{ $ri->lama_dirawat }} hari</p>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-slate-400">
                    Tidak ada pasien aktif saat ini.
                </p>
            @endforelse
        </div>
    </div>
@endif
 
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- DASHBOARD PASIEN --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if($role === 'pasien')
    @php $profil = auth()->user()->pasien; @endphp
 
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-slate-700">
            👋 Halo, <span class="text-green-700">{{ auth()->user()->nama }}</span>!
        </h3>
        <p class="text-sm text-slate-400">Lihat riwayat perawatan dan tagihan Anda di bawah ini.</p>
    </div>
 
    {{-- Info Profil Pasien --}}
    @if($profil)
        <div class="bg-green-700 rounded-2xl p-6 text-white mb-6 shadow-md">
            <div class="flex items-center gap-5">
                <div class="text-5xl">👤</div>
                <div>
                    <p class="font-bold text-xl">{{ auth()->user()->nama }}</p>
                    <p class="text-green-200 text-sm mt-1">
                        🪪 No. RM: <strong>{{ $profil->nomor_rm }}</strong>
                        @if($profil->golongan_darah)
                            · 🩸 Gol. Darah: <strong>{{ $profil->golongan_darah }}</strong>
                        @endif
                    </p>
                    <p class="text-green-200 text-sm">
                        🎂 {{ $profil->umur }}
                        · {{ $profil->jenis_kelamin_label }}
                    </p>
                </div>
            </div>
        </div>
    @endif
 
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-green-500">
            <p class="text-xs text-slate-500 uppercase">Total Riwayat</p>
            <p class="text-3xl font-bold mt-1">{{ $data['riwayat_total'] }}</p>
            <p class="text-xs text-slate-400 mt-1">📋 Rawat inap</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-blue-400">
            <p class="text-xs text-slate-500 uppercase">Aktif</p>
            <p class="text-3xl font-bold mt-1">{{ $data['aktif'] }}</p>
            <p class="text-xs text-slate-400 mt-1">🏥 Sedang dirawat</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-emerald-400">
            <p class="text-xs text-slate-500 uppercase">Tagihan Lunas</p>
            <p class="text-3xl font-bold mt-1">{{ $data['tagihan_lunas'] }}</p>
            <p class="text-xs text-slate-400 mt-1">✅ Sudah dibayar</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-amber-400">
            <p class="text-xs text-slate-500 uppercase">Tagihan Pending</p>
            <p class="text-3xl font-bold mt-1">{{ $data['tagihan_pending'] }}</p>
            <p class="text-xs text-slate-400 mt-1">⏳ Belum dibayar</p>
        </div>
    </div>
 
    {{-- Riwayat Perawatan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 bg-green-800 flex justify-between">
            <h4 class="font-bold text-white">📋 Riwayat Perawatan Terbaru</h4>
            <a href="{{ route('pasien.riwayat') }}" class="text-green-200 text-sm hover:text-white">
                Lihat semua →
            </a>
        </div>
        <div class="divide-y">
            @forelse($data['riwayat'] as $ri)
                <div class="px-5 py-4 hover:bg-green-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-slate-800">
                                {{ $ri->dokter->nama_lengkap }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                🛏 Kamar {{ $ri->kamar->nomor_kamar }}
                                · Masuk: {{ $ri->tanggal_masuk->format('d M Y') }}
                            </p>
                            @if($ri->diagnosa)
                                <p class="text-xs text-slate-500 mt-1">
                                    📋 {{ Str::limit($ri->diagnosa, 50) }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $ri->status === 'dirawat'
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-green-100 text-green-700' }}">
                                {{ $ri->status_label }}
                            </span>
                            @if($ri->transaksi)
                                <p class="text-xs mt-1 font-medium
                                    {{ $ri->transaksi->status_bayar === 'lunas'
                                        ? 'text-green-600'
                                        : 'text-amber-600' }}">
                                    {{ $ri->transaksi->total_format }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="px-5 py-10 text-center text-slate-400">
                    Belum ada riwayat perawatan.
                </p>
            @endforelse
        </div>
    </div>
@endif
 
@endsection
