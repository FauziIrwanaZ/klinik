<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Kamar;
use App\Models\RawatInap;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // 1. BUAT AKUN PENGGUNA
        // =============================================
        $admin = Pengguna::create([
            'nama'     => 'Administrator',
            'email'    => 'admin@klinik.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $petugas = Pengguna::create([
            'nama'     => 'Budi Santoso',
            'email'    => 'petugas@klinik.com',
            'password' => Hash::make('petugas123'),
            'role'     => 'petugas',
        ]);

        // Akun dokter
        $akun_dokter1 = Pengguna::create([
            'nama'     => 'Siti Rahayu',
            'email'    => 'dokter1@klinik.com',
            'password' => Hash::make('dokter123'),
            'role'     => 'dokter',
        ]);
        $akun_dokter2 = Pengguna::create([
            'nama'     => 'Ahmad Fauzi',
            'email'    => 'dokter2@klinik.com',
            'password' => Hash::make('dokter123'),
            'role'     => 'dokter',
        ]);

        // Akun pasien
        $akun_pasien1 = Pengguna::create([
            'nama'     => 'Andi Wijaya',
            'email'    => 'pasien1@klinik.com',
            'password' => Hash::make('pasien123'),
            'role'     => 'pasien',
        ]);
        $akun_pasien2 = Pengguna::create([
            'nama'     => 'Dewi Lestari',
            'email'    => 'pasien2@klinik.com',
            'password' => Hash::make('pasien123'),
            'role'     => 'pasien',
        ]);

        // =============================================
        // 2. BUAT PROFIL DOKTER
        // =============================================
        $dokter1 = Dokter::create([
            'pengguna_id'       => $akun_dokter1->id,
            'nomor_sip'         => 'SIP-001/2024',
            'spesialisasi'      => 'Dokter Umum',
            'no_telepon'        => '081234567890',
            'tarif_konsultasi'  => 150000,
            'tersedia'          => true,
        ]);
        $dokter2 = Dokter::create([
            'pengguna_id'       => $akun_dokter2->id,
            'nomor_sip'         => 'SIP-002/2024',
            'spesialisasi'      => 'Penyakit Dalam',
            'no_telepon'        => '081234567891',
            'tarif_konsultasi'  => 200000,
            'tersedia'          => true,
        ]);

        // =============================================
        // 3. BUAT PROFIL PASIEN
        // =============================================
        $pasien1 = Pasien::create([
            'pengguna_id'    => $akun_pasien1->id,
            'nomor_rm'       => 'RM-000001',
            'nik'            => '3201010101010001',
            'jenis_kelamin'  => 'L',
            'tanggal_lahir'  => '1990-05-15',
            'golongan_darah' => 'A+',
            'alamat'         => 'Jl. Merdeka No. 1, Bandung',
            'no_telepon'     => '081298765432',
            'nama_penjamin'  => 'Siti Wijaya',
            'no_telepon_penjamin' => '081298765433',
        ]);
        $pasien2 = Pasien::create([
            'pengguna_id'    => $akun_pasien2->id,
            'nomor_rm'       => 'RM-000002',
            'nik'            => '3201010101010002',
            'jenis_kelamin'  => 'P',
            'tanggal_lahir'  => '1995-08-20',
            'golongan_darah' => 'O+',
            'alamat'         => 'Jl. Sudirman No. 5, Bandung',
            'no_telepon'     => '081387654321',
        ]);

        // =============================================
        // 4. BUAT DATA KAMAR
        // =============================================
        $kamars = [
            ['nomor_kamar'=>'VIP-1','tipe_kamar'=>'VIP',     'kapasitas'=>1,'harga_malam'=>600000,'fasilitas'=>'AC, TV 32", sofa, kulkas mini, kamar mandi dalam'],
            ['nomor_kamar'=>'VIP-2','tipe_kamar'=>'VIP',     'kapasitas'=>1,'harga_malam'=>600000,'fasilitas'=>'AC, TV 32", sofa, kulkas mini, kamar mandi dalam'],
            ['nomor_kamar'=>'101',  'tipe_kamar'=>'Kelas 1', 'kapasitas'=>1,'harga_malam'=>350000,'fasilitas'=>'AC, TV 21", kamar mandi dalam'],
            ['nomor_kamar'=>'102',  'tipe_kamar'=>'Kelas 1', 'kapasitas'=>1,'harga_malam'=>350000,'fasilitas'=>'AC, TV 21", kamar mandi dalam'],
            ['nomor_kamar'=>'201',  'tipe_kamar'=>'Kelas 2', 'kapasitas'=>2,'harga_malam'=>200000,'fasilitas'=>'AC, kamar mandi dalam'],
            ['nomor_kamar'=>'202',  'tipe_kamar'=>'Kelas 2', 'kapasitas'=>2,'harga_malam'=>200000,'fasilitas'=>'AC, kamar mandi dalam'],
            ['nomor_kamar'=>'301',  'tipe_kamar'=>'Kelas 3', 'kapasitas'=>4,'harga_malam'=>100000,'fasilitas'=>'Kipas angin, kamar mandi bersama'],
            ['nomor_kamar'=>'302',  'tipe_kamar'=>'Kelas 3', 'kapasitas'=>4,'harga_malam'=>100000,'fasilitas'=>'Kipas angin, kamar mandi bersama'],
        ];
        foreach ($kamars as $kamar) { Kamar::create($kamar); }

        // =============================================
        // 5. CONTOH DATA RAWAT INAP
        // =============================================
        $rawatInap1 = RawatInap::create([
            'pasien_id'      => $pasien1->id,
            'dokter_id'      => $dokter1->id,
            'kamar_id'       => 3,  // Kamar 101
            'tanggal_masuk'  => now()->subDays(3)->format('Y-m-d'),
            'keluhan'        => 'Demam tinggi, sakit kepala, dan mual sejak 2 hari lalu.',
            'diagnosa'       => 'Demam Berdarah Dengue (DBD) derajat I',
            'catatan_dokter' => 'Pasien perlu istirahat total, hidrasi cukup, pantau trombosit.',
            'tindakan_medis' => 'Infus RL 1500cc/24 jam, injeksi antipiretik.',
            'status'         => 'dirawat',
        ]);
        // Update status kamar menjadi 'terisi'
        Kamar::find(3)->update(['status' => 'terisi']);

        // Rawat inap yang sudah selesai + transaksi
        $rawatInap2 = RawatInap::create([
            'pasien_id'      => $pasien2->id,
            'dokter_id'      => $dokter2->id,
            'kamar_id'       => 5,  // Kamar 201
            'tanggal_masuk'  => now()->subDays(7)->format('Y-m-d'),
            'tanggal_keluar' => now()->subDays(2)->format('Y-m-d'),
            'keluhan'        => 'Nyeri perut hebat, mual muntah.',
            'diagnosa'       => 'Gastroenteritis Akut',
            'catatan_dokter' => 'Kondisi membaik, pasien diizinkan pulang.',
            'tindakan_medis' => 'Infus NaCl 0.9%, injeksi ondansetron.',
            'status'         => 'selesai',
        ]);

        // Buat transaksi untuk rawat inap yang sudah selesai
        $lamaRawat = 5; // 7 - 2 = 5 hari
        $biayaKamar  = $lamaRawat * 200000;  // 5 × Rp200.000 = Rp1.000.000
        $biayaDokter = $dokter2->tarif_konsultasi; // Rp200.000
        $biayaLain   = 350000;                // Obat & infus

        Transaksi::create([
            'rawat_inap_id' => $rawatInap2->id,
            'nomor_invoice' => 'INV-'.date('Ymd').'-0001',
            'biaya_kamar'   => $biayaKamar,
            'biaya_dokter'  => $biayaDokter,
            'biaya_lain'    => $biayaLain,
            'total_biaya'   => $biayaKamar + $biayaDokter + $biayaLain,
            'cara_bayar'    => 'bpjs',
            'status_bayar'  => 'lunas',
            'tanggal_bayar' => now()->subDays(2)->format('Y-m-d'),
            'catatan'       => 'Pembayaran via BPJS Kesehatan. Selesai diproses.',
        ]);

        $this->command->info('✅  Seeder berhasil! Database siap digunakan.');
        $this->command->info('📧  Admin   : admin@klinik.com   | admin123');
        $this->command->info('📧  Petugas : petugas@klinik.com | petugas123');
        $this->command->info('📧  Dokter  : dokter1@klinik.com | dokter123');
        $this->command->info('📧  Pasien  : pasien1@klinik.com | pasien123');
    }
}
