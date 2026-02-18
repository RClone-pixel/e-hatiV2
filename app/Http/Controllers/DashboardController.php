<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Pemeriksaan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * ============================================================
     * DASHBOARD CONTROLLER - Menampilkan statistik utama aplikasi
     * ============================================================
     *
     * Statistik yang ditampilkan:
     * 1. Total Pegawai - Jumlah seluruh pegawai dalam database
     * 2. Total Pemeriksaan - Jumlah seluruh riwayat pemeriksaan
     * 3. Pemeriksaan Bulan Ini - Jumlah pemeriksaan di bulan berjalan
     * 4. Pemeriksaan Hari Ini - Jumlah pemeriksaan di hari ini
     * 5. Pegawai Diperiksa - Jumlah pegawai unik yang sudah diperiksa
     *
     * CARA MENAMBAH CARD BARU:
     * 1. Tambahkan query di method index() di bawah komentar "TAMBAH CARD BARU DI SINI"
     * 2. Tambahkan variable ke array $data
     * 3. Tambahkan HTML card di dashboard.blade.php mengikuti pola yang ada
     */

    public function index()
    {
        /* ============================================================
        STATISTIK PEGAWAI
           ============================================================ */

        // Total seluruh pegawai dalam database
        $totalPegawai = Pegawai::count();

        /* ============================================================
        STATISTIK PEMERIKSAAN
           ============================================================ */

        // Total seluruh riwayat pemeriksaan
        $totalPemeriksaan = Pemeriksaan::count();

        // Jumlah pemeriksaan di bulan ini (berdasarkan tanggal pemeriksaan)
        $pemeriksaanBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)
            ->count();

        // Jumlah pemeriksaan di hari ini (berdasarkan tanggal pemeriksaan)
        $pemeriksaanHariIni = Pemeriksaan::whereDate('tanggal_pemeriksaan', Carbon::today())->count();

        // Jumlah pegawai unik yang sudah pernah diperiksa
        // Menggunakan distinct untuk menghitung pegawai yang berbeda
        $pegawaiDiperiksa = Pemeriksaan::distinct('pegawai_id')->count('pegawai_id');

        /* ============================================================
        TAMBAH CARD BARU DI SINI
        ============================================================

        Contoh menambah statistik baru:

        $statistikBaru = Pemeriksaan::where(...)->count();

        Lalu tambahkan ke array $data di bawah
        */

        // Array data yang akan dikirim ke view
        $data = array(
            'title'                 => 'Dashboard',
            'menuDashboard'         => 'active',

            // Statistik Pegawai
            'totalPegawai'          => $totalPegawai,

            // Statistik Pemeriksaan
            'totalPemeriksaan'      => $totalPemeriksaan,
            'pemeriksaanBulanIni'   => $pemeriksaanBulanIni,
            'pemeriksaanHariIni'    => $pemeriksaanHariIni,
            'pegawaiDiperiksa'      => $pegawaiDiperiksa,

            /* TAMBAH VARIABLE BARU DI SINI */
        );

        return view('dashboard', $data);
    }
}
