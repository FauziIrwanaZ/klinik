<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')
                  ->unique()                   // One-to-One
                  ->constrained('pengguna')
                  ->onDelete('cascade');        // Hapus akun = hapus profil pasien

            $table->string('nomor_rm', 20)->unique()
                  ->comment('Nomor Rekam Medis, contoh: RM-000001');
            $table->string('nik', 16)->nullable()->unique()
                  ->comment('Nomor Induk Kependudukan');
            $table->char('jenis_kelamin', 1)->comment('L=Laki-laki, P=Perempuan');
            $table->date('tanggal_lahir')->nullable();
            $table->string('golongan_darah', 5)->nullable()
                  ->comment('A, B, AB, O atau dengan rhesus: A+, A-, dst');
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('nama_penjamin', 100)->nullable()
                  ->comment('Nama keluarga/wali yang dapat dihubungi');
            $table->string('no_telepon_penjamin', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
