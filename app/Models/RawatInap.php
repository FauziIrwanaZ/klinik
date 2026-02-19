<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
 
class RawatInap extends Model
{
    protected $table = 'rawat_inap';
 
    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'kamar_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'keluhan',
        'diagnosa',
        'catatan_dokter',
        'tindakan_medis',
        'status',    // dirawat | selesai | dirujuk | meninggal
    ];
 
    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Rawat inap ini milik satu pasien.
     * Relasi: belongsTo (rawat_inaps → pasiens)
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
 
    /**
     * Rawat inap ini ditangani oleh satu dokter.
     * Relasi: belongsTo (rawat_inaps → dokters)
     */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
 
    /**
     * Rawat inap ini menempati satu kamar.
     * Relasi: belongsTo (rawat_inaps → kamars)
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
 
    /**
     * Satu rawat inap menghasilkan satu tagihan transaksi.
     * Relasi: One-to-One (rawat_inaps → transaksis)
     */
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'rawat_inap_id');
    }
 
    // ============================================
    // ACCESSOR
    // ============================================
 
    /**
     * Hitung jumlah hari dirawat.
     * Jika belum keluar, dihitung sampai hari ini.
     */
    public function getLamaDirawatAttribute(): int
    {
        $keluar = $this->tanggal_keluar ?? now();
        return max(1, $this->tanggal_masuk->diffInDays($keluar));
    }
 
    /**
     * Label status dengan warna (untuk tampilan badge).
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'dirawat'   => 'Sedang Dirawat',
            'selesai'   => 'Selesai / Pulang',
            'dirujuk'   => 'Dirujuk',
            'meninggal' => 'Meninggal Dunia',
            default     => ucfirst($this->status),
        };
    }
 
    /**
     * Hitung estimasi total biaya berdasarkan lama rawat.
     */
    public function getEstimasiBiayaAttribute(): float
    {
        $biayaKamar  = $this->lama_dirawat * $this->kamar->harga_malam;
        $biayaDokter = $this->dokter->tarif_konsultasi;
        return $biayaKamar + $biayaDokter;
    }
}
