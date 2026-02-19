<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class RoleAccess
{
    /**
     * Tangani setiap HTTP request masuk.
     * Periksa apakah role pengguna login termasuk dalam daftar role yang diizinkan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  — daftar role yang diizinkan (contoh: admin, petugas)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login')
                             ->with('error', 'Silakan login terlebih dahulu.');
        }
 
        $penggunaLogin = auth()->user();
 
        // Cek apakah role pengguna ada dalam daftar role yang diizinkan
        if (!in_array($penggunaLogin->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }
 
        // Cek apakah akun pengguna masih aktif
        if (isset($penggunaLogin->aktif) && !$penggunaLogin->aktif) {
            auth()->logout();
            return redirect()->route('login')
                             ->with('error', 'Akun Anda telah dinonaktifkan oleh administrator.');
        }
 
        return $next($request);
    }
}
