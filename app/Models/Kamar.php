<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Kamar extends Model
{
    protected $table = 'kamar';
 
    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',     // VIP | Kelas 1 | Kelas 2 | Kelas 3
        'kapasitas',
        'harga_malam',
        'fasilitas',
        'status',         // tersedia | terisi | maintenance
    ];
 
    protected $casts = [
        'harga_malam' => 'decimal:2',
        'kapasitas'   => 'integer',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Satu kamar bisa digunakan di banyak riwayat rawat inap (berbeda waktu).
     * Relasi: One-to-Many (kamars → rawat_inaps)
     */
    public function rawatInaps()
    {
        return $this->hasMany(RawatInap::class, 'kamar_id');
    }
 
    // ============================================
    // ACCESSOR
    // ============================================
 
    /**
     * Format harga malam menjadi format Rupiah.
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_malam, 0, ',', '.');
    }
 
    /**
     * Label status kamar dengan emoji.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'tersedia'    => '✅ Tersedia',
            'terisi'      => '🔴 Terisi',
            'maintenance' => '🔧 Maintenance',
            default       => $this->status,
        };
    }
 
    /**
     * Cek apakah kamar bisa digunakan untuk rawat inap baru.
     */
    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }
 
    // ============================================
    // SCOPE
    // ============================================
 
    /**
     * Scope: hanya kamar yang tersedia.
     * Penggunaan: Kamar::tersedia()->get()
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }
 
    /**
     * Scope: filter berdasarkan tipe kamar.
     * Penggunaan: Kamar::tipe('VIP')->get()
     */
    public function scopeTipe($query, string $tipe)
    {
        return $query->where('tipe_kamar', $tipe);
    }
}
