<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Dokter extends Model
{
    protected $table = 'dokter';
 
    protected $fillable = [
        'pengguna_id',
        'nomor_sip',           // Surat Izin Praktik
        'spesialisasi',
        'no_telepon',
        'tarif_konsultasi',
        'tersedia',
    ];
 
    protected $casts = [
        'tarif_konsultasi' => 'decimal:2',
        'tersedia'         => 'boolean',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Dokter ini milik satu akun pengguna.
     * Relasi: belongsTo (dokters → penggunas)
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
 
    /**
     * Satu dokter bisa menangani banyak pasien rawat inap.
     * Relasi: One-to-Many (dokters → rawat_inaps)
     */
    public function rawatInaps()
    {
        return $this->hasMany(RawatInap::class, 'dokter_id');
    }
 
    // ============================================
    // ACCESSOR
    // ============================================
 
    /**
     * Nama lengkap dokter dengan prefix "dr."
     * Contoh: dr. Siti Rahayu
     */
    public function getNamaLengkapAttribute(): string
    {
        return 'dr. ' . $this->pengguna->nama;
    }
 
    /**
     * Format tarif konsultasi menjadi format Rupiah.
     * Contoh: Rp 150.000
     */
    public function getTarifFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->tarif_konsultasi, 0, ',', '.');
    }
 
    /**
     * Label status ketersediaan dokter.
     */
    public function getStatusLabel(): string
    {
        return $this->tersedia ? 'Tersedia' : 'Tidak Tersedia';
    }
 
    // ============================================
    // SCOPE
    // ============================================
 
    /**
     * Scope: ambil hanya dokter yang sedang tersedia.
     * Penggunaan: Dokter::tersedia()->get()
     */
    public function scopeTersedia($query)
    {
        return $query->where('tersedia', true);
    }
}
 
