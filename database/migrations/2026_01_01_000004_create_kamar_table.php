<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kamar', 10)->unique();
            $table->enum('tipe_kamar', ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3'])
                  ->default('Kelas 3');
            $table->tinyInteger('kapasitas')->unsigned()->default(1)
                  ->comment('Jumlah tempat tidur dalam kamar');
            $table->decimal('harga_malam', 10, 2)
                  ->comment('Harga sewa kamar per malam dalam Rupiah');
            $table->text('fasilitas')->nullable()
                  ->comment('Deskripsi fasilitas: AC, TV, kamar mandi dalam, dll');
            $table->enum('status', ['tersedia', 'terisi', 'maintenance'])
                  ->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
