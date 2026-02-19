@extends('layouts.simple')
@section('judul', 'Masuk — Klinik Rawat Inap')
 
@section('konten')
<div class="flex min-h-screen">
 
    {{-- Panel Kiri — Ilustrasi & Info Klinik --}}
    <div class="hidden lg:flex w-1/2 bg-green-900 flex-col items-center justify-center p-12">
        <div class="text-center text-white">
            <div class="text-8xl mb-6">🏥</div>
            <h1 class="text-4xl font-bold mb-3">Klinik Sehat Bersama</h1>
            <p class="text-green-300 text-lg max-w-md leading-relaxed">
                Sistem Informasi Rawat Inap modern untuk
                pelayanan kesehatan yang lebih baik.
            </p>
            <div class="mt-10 grid grid-cols-3 gap-4 text-center">
                <div class="bg-green-800 rounded-2xl p-4">
                    <div class="text-3xl mb-1">👨‍⚕️</div>
                    <p class="text-xs text-green-300">Dokter</p>
                </div>
                <div class="bg-green-800 rounded-2xl p-4">
                    <div class="text-3xl mb-1">🛏</div>
                    <p class="text-xs text-green-300">Kamar</p>
                </div>
                <div class="bg-green-800 rounded-2xl p-4">
                    <div class="text-3xl mb-1">💊</div>
                    <p class="text-xs text-green-300">Layanan</p>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Panel Kanan — Form Login --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
 
            {{-- Header form --}}
            <div class="text-center mb-8">
                <div class="text-5xl mb-4 lg:hidden">🏥</div>
                <h2 class="text-3xl font-bold text-slate-800">Selamat Datang!</h2>
                <p class="text-slate-500 mt-2">Masuk untuk melanjutkan ke sistem klinik.</p>
            </div>
 
            {{-- Flash message error --}}
            @if(session('error'))
                <div class="mb-5 flex gap-3 items-start bg-red-50 border border-red-200
                            text-red-700 px-4 py-3 rounded-xl text-sm">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif
 
            {{-- Flash message sukses (dari register/logout) --}}
            @if(session('sukses'))
                <div class="mb-5 flex gap-3 items-start bg-green-50 border border-green-200
                            text-green-700 px-4 py-3 rounded-xl text-sm">
                    <span>✅</span> {{ session('sukses') }}
                </div>
            @endif
 
            {{-- Form Login --}}
            <form method="POST" action="{{ route('login.proses') }}" class="space-y-5">
                @csrf
 
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="dokter@klinik.com"
                        autofocus
                        class="w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all duration-200
                               focus:ring-2 focus:ring-green-500 focus:border-transparent
                               @error('email') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="text-sm font-semibold text-slate-700">
                            Password
                        </label>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm outline-none transition-all
                               focus:ring-2 focus:ring-green-500 focus:border-transparent
                               @error('password') border-red-400 bg-red-50 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Ingat Saya --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="ingat_saya" name="ingat_saya"
                           class="w-4 h-4 accent-green-600">
                    <label for="ingat_saya" class="text-sm text-slate-600">Ingat saya</label>
                </div>
 
                {{-- Tombol Login --}}
                <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 active:bg-green-900
                               text-white font-bold py-3.5 rounded-xl transition-all duration-200
                               shadow-md shadow-green-200 hover:shadow-lg hover:shadow-green-300
                               text-base">
                    🔑 Masuk ke Sistem
                </button>
            </form>
 
            {{-- Link Daftar --}}
            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-green-700 font-semibold hover:text-green-800 hover:underline">
                    Daftar sebagai Pasien Baru
                </a>
            </p>
 
            {{-- Akun Demo --}}
            <div class="mt-8 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                    Akun Demo (Hapus di Produksi!)
                </p>
                <div class="grid grid-cols-2 gap-1.5 text-xs text-slate-600">
                    <div>👑 admin@klinik.com</div><div>admin123</div>
                    <div>📋 petugas@klinik.com</div><div>petugas123</div>
                    <div>🩺 dokter1@klinik.com</div><div>dokter123</div>
                    <div>👤 pasien1@klinik.com</div><div>pasien123</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
