<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Transaksi extends Model
{
    protected $table = 'transaksi';
 
    protected $fillable = [
        'rawat_inap_id',
        'nomor_invoice',
        'biaya_kamar',
        'biaya_dokter',
        'biaya_lain',
        'total_biaya',
        'cara_bayar',       // tunai | bpjs | asuransi | transfer
        'status_bayar',     // belum_bayar | lunas | cicilan
        'tanggal_bayar',
        'catatan',
    ];
 
    protected $casts = [
        'biaya_kamar'   => 'decimal:2',
        'biaya_dokter'  => 'decimal:2',
        'biaya_lain'    => 'decimal:2',
        'total_biaya'   => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];
 
    // ============================================
    // RELASI
    // ============================================
 
    /**
     * Transaksi ini milik satu rawat inap.
     * Relasi: belongsTo (transaksis → rawat_inaps)
     */
    public function rawatInap()
    {
        return $this->belongsTo(RawatInap::class, 'rawat_inap_id');
    }
 
    // ============================================
    // ACCESSOR
    // ============================================
 
    /**
     * Format total biaya ke dalam Rupiah.
     */
    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }
 
    /**
     * Label cara bayar dalam Bahasa Indonesia.
     */
    public function getCaraBayarLabelAttribute(): string
    {
        return match($this->cara_bayar) {
            'tunai'     => 'Tunai (Cash)',
            'bpjs'      => 'BPJS Kesehatan',
            'asuransi'  => 'Asuransi Swasta',
            'transfer'  => 'Transfer Bank',
            default     => ucfirst($this->cara_bayar),
        };
    }
 
    /**
     * Label status bayar.
     */
    public function getStatusBayarLabelAttribute(): string
    {
        return match($this->status_bayar) {
            'belum_bayar' => 'Belum Bayar',
            'lunas'       => 'Lunas',
            'cicilan'     => 'Cicilan',
            default       => $this->status_bayar,
        };
    }
 
    // ============================================
    // BOOT — Auto-generate Nomor Invoice
    // ============================================
 
    protected static function boot()
    {
        parent::boot();
 
        // Otomatis generate nomor invoice saat transaksi dibuat
        static::creating(function ($transaksi) {
            if (!$transaksi->nomor_invoice) {
                $urutan = static::whereDate('created_at', today())->count() + 1;
                $transaksi->nomor_invoice = 'INV-'
                    . date('Ymd') . '-'
                    . str_pad($urutan, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
 
