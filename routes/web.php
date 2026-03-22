<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Livewire\KamarComponent;
use App\Livewire\RawatInapComponent;
use App\Livewire\TransaksiComponent;
use App\Livewire\PasienDashboardComponent;
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
          Route::get('/pasien', fn() => view('admin.pasien'))->name('admin.pasien');
           Route::get('/kamar', fn() => view('admin.kamar'))->name('admin.kamar');
        Route::get('/transaksi', fn() => view('admin.transaksi'))->name('admin.transaksi');
        Route::get('/pengguna', fn() => view('admin.pengguna'))->name('admin.pengguna');
    });
 
    // ── PETUGAS & DOKTER ──
    Route::middleware(['role:petugas,dokter'])->group(function () {
        Route::get('/rawat-inap', fn() => view('petugas.rawatInap'))->name('rawat-inap');
    });
 
    // ── PASIEN — riwayat & tagihan sendiri ──
    Route::middleware(['role:pasien'])->group(function () {
        Route::get('/riwayat-saya', fn() => view('pasien.riwayat-saya.riwayat'))->name('pasien.riwayat');
    });
});
