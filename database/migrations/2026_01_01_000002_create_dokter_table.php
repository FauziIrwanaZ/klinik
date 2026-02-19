<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')
                  ->unique()                   // One-to-One
                  ->constrained('pengguna')
                  ->onDelete('cascade');        // Hapus akun = hapus profil dokter

            $table->string('nomor_sip', 50)->unique()->comment('Surat Izin Praktik');
            $table->string('spesialisasi', 100);
            $table->string('no_telepon', 20)->nullable();
            $table->decimal('tarif_konsultasi', 10, 2)->default(0);
            $table->boolean('tersedia')->default(true)
                  ->comment('true=bisa menerima pasien, false=tidak praktik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
