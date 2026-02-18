<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Pegawai;
use Carbon\Carbon;

/** ============================================================
 * IMPORT UNTUK EXPORT EXCEL
 * ============================================================
 * Pastikan sudah install package Laravel Excel:
 * composer require maatwebsite/excel
 *
 * Dokumentasi: https://docs.laravel-excel.com/3.1/
 ============================================================ */

use App\Exports\PemeriksaanExport;
use Maatwebsite\Excel\Facades\Excel;

/** ============================================================
 * IMPORT UNTUK EXPORT PDF
 * ============================================================
 * Pastikan sudah install package Laravel DomPDF:
 * composer require barryvdh/laravel-dompdf
 *
 * Dokumentasi: https://github.com/barryvdh/laravel-dompdf
 ============================================================ */

use PDF;

class PemeriksaanController extends Controller
{
    /**
     * ============================================================
     * INDEX - Halaman utama Pemeriksaan (Tab Pemeriksaan + Tab Riwayat)
     * ============================================================
     */
    public function index(Request $request)
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        // Riwayat data for tab
        $query = Pemeriksaan::with('pegawai')->orderBy('tanggal_pemeriksaan', 'desc');

        // Filters
        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->sampai_tanggal);
        }

        $riwayat = $query->paginate(15);

        // Statistics
        $countBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)
            ->count();
        $countHariIni  = Pemeriksaan::whereDate('tanggal_pemeriksaan', Carbon::today())->count();
        $countPegawai  = Pemeriksaan::distinct('pegawai_id')->count('pegawai_id');

        $data = [
            'title'            => 'Pemeriksaan',
            'menuPemeriksaan'  => 'active',
            'pegawai'          => $pegawai,
            'riwayat'          => $riwayat,
            'countBulanIni'    => $countBulanIni,
            'countHariIni'     => $countHariIni,
            'countPegawai'     => $countPegawai,
        ];

        return view('admin.pemeriksaan.index', $data);
    }

    /**
     * ============================================================
     * STORE - Simpan hasil pemeriksaan baru
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'           => 'required|exists:pegawais,id',
            'tanggal_pemeriksaan'  => 'required|date',
            'puasa'                => 'required|in:0,1',
        ]);

        Pemeriksaan::create([
            'pegawai_id'           => $request->pegawai_id,
            'tanggal_pemeriksaan'  => $request->tanggal_pemeriksaan,
            'puasa'                => $request->puasa,
            'tinggi_badan'         => $request->tinggi_badan,
            'berat_badan'          => $request->berat_badan,
            'sistolik'             => $request->sistolik,
            'diastolik'            => $request->diastolik,
            'nadi'                 => $request->nadi,
            'nilai_glukometer'     => $request->nilai_glukometer,
            'parameter_gula'       => $request->parameter_gula,
            'kolesterol_total'     => $request->kolesterol_total,
            'asam_urat'            => $request->asam_urat,
            'catatan_dokter'       => $request->catatan_dokter,
        ]);

        return redirect()->route('pemeriksaan')->with('success', 'Data pemeriksaan berhasil disimpan!');
    }

    /**
     * ============================================================
     * RIWAYAT - Halaman riwayat dengan filter
     * Redirect ke index agar tab riwayat aktif
     * ============================================================
     */
    public function riwayat(Request $request)
    {
        // Same as index but we'll add a flag to activate riwayat tab
        $pegawai = Pegawai::orderBy('nama')->get();

        $query = Pemeriksaan::with('pegawai')->orderBy('tanggal_pemeriksaan', 'desc');

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->sampai_tanggal);
        }

        $riwayat = $query->paginate(15);

        $countBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)
            ->count();
        $countHariIni  = Pemeriksaan::whereDate('tanggal_pemeriksaan', Carbon::today())->count();
        $countPegawai  = Pemeriksaan::distinct('pegawai_id')->count('pegawai_id');

        $data = [
            'title'            => 'Pemeriksaan',
            'menuPemeriksaan'  => 'active',
            'pegawai'          => $pegawai,
            'riwayat'          => $riwayat,
            'countBulanIni'    => $countBulanIni,
            'countHariIni'     => $countHariIni,
            'countPegawai'     => $countPegawai,
            'activeTab'        => 'riwayat',
        ];

        return view('admin.pemeriksaan.index', $data);
    }

    /**
     * ============================================================
     * SHOW - Detail pemeriksaan (JSON for AJAX modal)
     * ============================================================
     */
    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with('pegawai')->findOrFail($id);
        return response()->json($pemeriksaan);
    }

    /**
     * ============================================================
     * DESTROY - Hapus data pemeriksaan
     * ============================================================
     */
    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaanRiwayat')->with('success', 'Data pemeriksaan berhasil dihapus!');
    }

    /**
     * ============================================================
     * EXPORT EXCEL - Export data pemeriksaan ke format Excel
     * ============================================================
     *
     * URL: /pemeriksaan/export-excel
     * Method: GET
     *
     * Menggunakan Laravel Excel untuk generate file .xlsx
     * File akan otomatis didownload oleh browser
     *
     * CARA KERJA:
     * 1. Memanggil class PemeriksaanExport (App\Exports\PemeriksaanExport)
     * 2. Laravel Excel akan generate file Excel
     * 3. File didownload dengan nama yang sudah diformat
     */
    public function exportExcel()
    {
        // Format nama file: Riwayat_Pemeriksaan_YYYYMMDD_His.xlsx
        $fileName = 'Riwayat_Pemeriksaan_' . now()->format('Ymd_His') . '.xlsx';

        // Download file Excel
        return Excel::download(new PemeriksaanExport, $fileName);
    }

    /**
     * ============================================================
     * EXPORT PDF - Export data pemeriksaan ke format PDF
     * ============================================================
     *
     * URL: /pemeriksaan/export-pdf
     * Method: GET
     *
     * Menggunakan Laravel DomPDF untuk generate file PDF
     * File akan otomatis didownload atau ditampilkan di browser
     *
     * CARA KERJA:
     * 1. Mengambil semua data pemeriksaan dari database dengan relasi pegawai
     * 2. Load view khusus untuk PDF (admin.pemeriksaan.pdf)
     * 3. DomPDF akan merender HTML menjadi PDF
     * 4. File didownload dengan nama yang sudah diformat
     */
    public function exportPdf()
    {
        // Ambil semua data pemeriksaan dengan relasi pegawai, urutkan berdasarkan tanggal terbaru
        $pemeriksaan = Pemeriksaan::with('pegawai')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        // Load view PDF dengan data pemeriksaan
        $pdf = PDF::loadView('admin.pemeriksaan.pdf', compact('pemeriksaan'));

        // Set ukuran kertas (A4 landscape karena kolom banyak)
        $pdf->setPaper('A4', 'landscape');

        // Format nama file: Riwayat_Pemeriksaan_YYYYMMDD_His.pdf
        $fileName = 'Riwayat_Pemeriksaan_' . now()->format('Ymd_His') . '.pdf';

        // Download file PDF
        return $pdf->download($fileName);
    }
}
