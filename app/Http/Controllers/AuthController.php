<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Pasien;
 
class AuthController extends Controller
{
    // ══════════════════════════════════════════
    // LOGIN
    // ══════════════════════════════════════════
 
 
    public function tampilLogin()
    {
      
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
 
    public function prosesLogin(Request $request)
    {
     
        $validasi = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);
 
   
        $pengguna = Pengguna::where('email', $request->email)->first();
 
   
        if ($pengguna && !$pengguna->aktif) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }
 
      
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('ingat_saya'))) {
            $request->session()->regenerate(); 
 
      
            return redirect()->intended(route('dashboard'));
        }
 
    
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah. Silakan coba lagi.');
    }
 
    // ══════════════════════════════════════════
    // REGISTER
    // ══════════════════════════════════════════
 
    public function tampilRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }
 
   
    public function prosesRegister(Request $request)
    {
       
        $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:penggunas,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'nama.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar. Gunakan email lain.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);
 
       
        $pengguna = Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pasien', 
        ]);
 
        Pasien::create([
            'pengguna_id'   => $pengguna->id,
            'jenis_kelamin' => 'L', 
        ]);
 
        return redirect()->route('login')
            ->with('sukses', '✅ Pendaftaran berhasil! Silakan masuk menggunakan akun Anda.');
    }
 
    // ══════════════════════════════════════════
    // LOGOUT
    // ══════════════════════════════════════════
 
   
    public function logout(Request $request)
    {
        $namaUser = Auth::user()->nama; 
 
        Auth::logout();
 
  
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect()->route('login')
            ->with("sukses", "👋 Sampai jumpa! Anda telah berhasil keluar.");
    }
}
