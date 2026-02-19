<?php
 
namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class Pengguna extends Authenticatable
{
    use Notifiable;
 
    // Nama tabel di database
    protected $table = 'pengguna';
 
    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',       // admin | petugas | dokter | pasien
        'aktif',
    ];
 
    // Kolom yang disembunyikan dari array/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];
 
    // Casting otomatis tipe data
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'aktif'             => 'boolean',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Satu pengguna dengan role "dokter" memiliki satu profil dokter.
     * Relasi: One-to-One (penggunas → dokters)
     */
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'pengguna_id');
    }
 
    /**
     * Satu pengguna dengan role "pasien" memiliki satu profil pasien.
     * Relasi: One-to-One (penggunas → pasiens)
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'pengguna_id');
    }
 
    // ============================================
    // ACCESSOR & HELPER
    // ============================================
 
    /**
     * Cek apakah pengguna adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
 
    /**
     * Cek apakah pengguna adalah Dokter.
     */
    public function isDokter(): bool
    {
        return $this->role === 'dokter';
    }
 
    /**
     * Cek apakah pengguna adalah Pasien.
     */
    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }

       public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }
 
    /**
     * Label role dalam Bahasa Indonesia.
     */
    public function getLabelRoleAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'Administrator',
            'petugas' => 'Petugas',
            'dokter'  => 'Dokter',
            'pasien'  => 'Pasien',
            default   => ucfirst($this->role),
        };
    }
}
