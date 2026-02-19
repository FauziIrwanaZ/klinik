@extends('layouts.simple')
@section('judul', 'Daftar Pasien Baru — Klinik Rawat Inap')
 
@section('konten')
<div class="flex items-center justify-center min-h-screen px-4 py-12">
    <div class="w-full max-w-lg">
 
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🏥</div>
            <h1 class="text-3xl font-bold text-slate-800">Daftar Pasien Baru</h1>
            <p class="text-slate-500 mt-2">
                Buat akun untuk melihat riwayat perawatan Anda.
            </p>
        </div>
 
        {{-- Card Form --}}
        <div class="bg-white shadow-xl shadow-slate-100 rounded-3xl p-8 border border-slate-100">
 
            {{-- Flash sukses --}}
            @if(session('sukses'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    ✅ {{ session('sukses') }}
                </div>
            @endif
 
            <form method="POST" action="{{ route('register.proses') }}" class="space-y-5">
                @csrf
 
                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap sesuai KTP"
                           class="w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all
                                  focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  @error('nama') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
 
                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           class="w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all
                                  focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  @error('email') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
 
                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password"
                           placeholder="Minimal 8 karakter"
                           class="w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all
                                  focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  @error('password') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
 
                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi password Anda"
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm outline-none transition-all
                                  focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
 
                {{-- Info Penting --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                    <p class="font-semibold mb-1">📋 Catatan Penting:</p>
                    <p>Akun yang dibuat akan memiliki hak akses sebagai <strong>Pasien</strong>.
                    Untuk role lain, hubungi administrator klinik.</p>
                </div>
 
                {{-- Tombol Daftar --}}
                <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-bold
                               py-3.5 rounded-xl transition-all duration-200 shadow-md text-base">
                    ✅ Daftar Sekarang
                </button>
            </form>
 
            <p class="text-center text-sm text-slate-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
