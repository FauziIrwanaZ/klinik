<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Livewire\PasienComponent;
use App\Livewire\KamarComponent;
use App\Livewire\RawatInapComponent;
use App\Livewire\TransaksiComponent;
 
// ══════════════════════════════════════════
// PUBLIK — tidak butuh login
// ══════════════════════════════════════════
Route::get('/', fn() => redirect()->route('login'))->name('home');
 
// ── Autentikasi ──
Route::get('/login',    [AuthController::class, 'tampilLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'prosesLogin'])->name('login.proses');
Route::get('/register', [AuthController::class, 'tampilRegister'])->name('register');
Route::post('/register',[AuthController::class, 'prosesRegister'])->name('register.proses');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
 
// ══════════════════════════════════════════
// TERAUTENTIKASI — semua role
// ══════════════════════════════════════════
Route::middleware(['auth'])->group(function () {
 
    // Dashboard utama — DashboardController handle tampilan per-role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // ── ADMIN & PETUGAS ──
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/pasien',    PasienComponent::class)->name('admin.pasien');
        Route::get('/kamar',     KamarComponent::class)->name('admin.kamar');
        Route::get('/transaksi', TransaksiComponent::class)->name('admin.transaksi');
    });
 
    // ── PETUGAS & DOKTER ──
    Route::middleware(['role:petugas,dokter'])->group(function () {
        Route::get('/rawat-inap', RawatInapComponent::class)->name('rawat-inap');
    });
 
    // ── PASIEN — riwayat & tagihan sendiri ──
    Route::middleware(['role:pasien'])->group(function () {
        Route::get('/riwayat-saya', TransaksiComponent::class)->name('pasien.riwayat');
    });
});
