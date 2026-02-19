<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
 
class Pasien extends Model
{
    protected $table = 'pasien';
 
    protected $fillable = [
        'pengguna_id',
        'nomor_rm',          // Nomor Rekam Medis
        'nik',
        'jenis_kelamin',     // L atau P
        'tanggal_lahir',
        'golongan_darah',
        'alamat',
        'no_telepon',
        'nama_penjamin',
        'no_telepon_penjamin',
    ];
 
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Pasien ini milik satu akun pengguna.
     * Relasi: belongsTo (pasiens → penggunas)
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
 
    /**
     * Satu pasien bisa memiliki banyak riwayat rawat inap.
     * Relasi: One-to-Many (pasiens → rawat_inaps)
     */
    public function rawatInaps()
    {
        return $this->hasMany(RawatInap::class, 'pasien_id');
    }
 
    /**
     * Ambil rawat inap yang sedang aktif (status = "dirawat").
     */
    public function rawatInapAktif()
    {
        return $this->hasOne(RawatInap::class, 'pasien_id')
                    ->where('status', 'dirawat')
                    ->latest();
    }
 
    // ============================================
    // ACCESSOR
    // ============================================
 
    /**
     * Nama pasien diambil dari relasi pengguna.
     */
    public function getNamaAttribute(): string
    {
        return $this->pengguna->nama ?? '-';
    }
 
    /**
     * Hitung umur berdasarkan tanggal lahir.
     * Contoh: 34 tahun
     */
    public function getUmurAttribute(): string
    {
        if (!$this->tanggal_lahir) return '-';
        return Carbon::parse($this->tanggal_lahir)->age . ' tahun';
    }
 
    /**
     * Label jenis kelamin.
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
 
    // ============================================
    // BOOT — Auto-generate Nomor RM
    // ============================================
 
    protected static function boot()
    {
        parent::boot();
 
        // Otomatis generate Nomor RM saat data pasien dibuat
        static::creating(function ($pasien) {
            if (!$pasien->nomor_rm) {
                $urutan = static::count() + 1;
                $pasien->nomor_rm = 'RM-' . str_pad($urutan, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
 
