<?php
 
namespace App\Http\Controllers;
 
use App\Models\{Pasien, Dokter, Kamar, RawatInap, Transaksi};
 
class DashboardController extends Controller
{
 
    public function index()
    {
        $role = auth()->user()->role;
 
        $data = [];
 
       
        if ($role === 'admin') {
            $data = [
                'total_pasien'      => Pasien::count(),
                'total_dokter'      => Dokter::count(),
                'total_kamar'       => Kamar::count(),
                'kamar_tersedia'    => Kamar::where('status', 'tersedia')->count(),
                'kamar_terisi'      => Kamar::where('status', 'terisi')->count(),
                'sedang_dirawat'    => RawatInap::where('status', 'dirawat')->count(),
                'selesai_hari_ini'  => RawatInap::where('status', 'selesai')
                                              ->whereDate('updated_at', today())->count(),
                'pendapatan_bulan'  => Transaksi::where('status_bayar', 'lunas')
                                              ->whereMonth('tanggal_bayar', now()->month)
                                              ->sum('total_biaya'),
                'rawat_inap_terbaru'=> RawatInap::with(['pasien.pengguna','dokter.pengguna','kamar'])
                                              ->latest()->take(5)->get(),
            ];
        }
 

        elseif ($role === 'petugas') {
            $data = [
                'sedang_dirawat'  => RawatInap::where('status', 'dirawat')->count(),
                'kamar_tersedia'  => Kamar::where('status', 'tersedia')->count(),
                'kamar_terisi'    => Kamar::where('status', 'terisi')->count(),
                'tagihan_pending' => Transaksi::where('status_bayar', 'belum_bayar')->count(),
                'rawat_inap_aktif'=> RawatInap::with(['pasien.pengguna','kamar'])
                                           ->where('status', 'dirawat')
                                           ->latest()->take(8)->get(),
            ];
        }
 

        elseif ($role === 'dokter') {
            $dokterId = auth()->user()->dokter?->id;
            $data = [
                'pasien_aktif'     => RawatInap::where('dokter_id', $dokterId)
                                              ->where('status', 'dirawat')->count(),
                'total_pasien'     => RawatInap::where('dokter_id', $dokterId)->count(),
                'selesai_bulan'    => RawatInap::where('dokter_id', $dokterId)
                                              ->where('status', 'selesai')
                                              ->whereMonth('updated_at', now()->month)->count(),
                'pasien_terbaru'   => RawatInap::with(['pasien.pengguna', 'kamar'])
                                              ->where('dokter_id', $dokterId)
                                              ->where('status', 'dirawat')
                                              ->latest()->take(6)->get(),
            ];
        }
 
   
        elseif ($role === 'pasien') {
            $pasienId = auth()->user()->pasien?->id;
            $data = [
                'riwayat_total'  => RawatInap::where('pasien_id', $pasienId)->count(),
                'aktif'          => RawatInap::where('pasien_id', $pasienId)
                                            ->where('status', 'dirawat')->count(),
                'tagihan_lunas' => Transaksi::whereHas('rawatInap', fn($q) => $q->where('pasien_id', $pasienId))
                                            ->where('status_bayar', 'lunas')->count(),
                'tagihan_pending'=> Transaksi::whereHas('rawatInap', fn($q) => $q->where('pasien_id', $pasienId))
                                            ->where('status_bayar', 'belum_bayar')->count(),
                'riwayat'       => RawatInap::with(['dokter.pengguna', 'kamar', 'transaksi'])
                                            ->where('pasien_id', $pasienId)
                                            ->latest()->take(5)->get(),
            ];
        }
 
        return view('dashboard', compact('data', 'role'));
    }
}
 
