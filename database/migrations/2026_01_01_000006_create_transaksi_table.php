<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            // One-to-One dengan rawat_inaps
            $table->foreignId('rawat_inap_id')
                  ->unique()                    // Satu rawat inap = satu transaksi
                  ->constrained('rawat_inap')
                  ->onDelete('cascade');         // Hapus rawat inap = hapus transaksi

            $table->string('nomor_invoice', 30)->unique()
                  ->comment('Contoh: INV-20240101-0001');

            // === RINCIAN BIAYA ===
            $table->decimal('biaya_kamar', 12, 2)
                  ->comment('harga_malam × jumlah_hari');
            $table->decimal('biaya_dokter', 12, 2)
                  ->comment('tarif_konsultasi dokter');
            $table->decimal('biaya_lain', 12, 2)->default(0)
                  ->comment('Biaya obat, lab, tindakan tambahan, dll');
            $table->decimal('total_biaya', 12, 2)
                  ->comment('biaya_kamar + biaya_dokter + biaya_lain');

            // === PEMBAYARAN ===
            $table->enum('cara_bayar', ['tunai', 'bpjs', 'asuransi', 'transfer'])
                  ->default('tunai');
            $table->enum('status_bayar', ['belum_bayar', 'lunas', 'cicilan'])
                  ->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
