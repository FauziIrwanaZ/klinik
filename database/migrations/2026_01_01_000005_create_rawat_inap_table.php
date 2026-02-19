<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rawat_inap', function (Blueprint $table) {
            $table->id();

            // === FOREIGN KEYS ===
            $table->foreignId('pasien_id')
                  ->constrained('pasien')
                  ->onDelete('restrict')   // Pasien tidak bisa dihapus jika ada riwayat
                  ->onUpdate('cascade');

            $table->foreignId('dokter_id')
                  ->constrained('dokter')
                  ->onDelete('restrict')   // Dokter tidak bisa dihapus jika ada riwayat
                  ->onUpdate('cascade');

            $table->foreignId('kamar_id')
                  ->constrained('kamar')
                  ->onDelete('restrict')   // Kamar tidak bisa dihapus jika ada riwayat
                  ->onUpdate('cascade');

            // === DATA RAWAT INAP ===
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable()
                  ->comment('NULL artinya pasien masih dirawat');

            // === DATA MEDIS ===
            $table->text('keluhan')->nullable()
                  ->comment('Keluhan utama pasien saat masuk');
            $table->text('diagnosa')->nullable()
                  ->comment('Diagnosa dokter (diisi saat pemeriksaan)');
            $table->text('catatan_dokter')->nullable()
                  ->comment('Catatan perkembangan kondisi pasien');
            $table->text('tindakan_medis')->nullable()
                  ->comment('Prosedur/tindakan medis yang dilakukan');

            // === STATUS ===
            $table->enum('status', ['dirawat', 'selesai', 'dirujuk', 'meninggal'])
                  ->default('dirawat');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rawat_inap');
    }
};
