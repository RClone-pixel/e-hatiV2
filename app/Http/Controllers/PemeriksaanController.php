<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use App\Exports\PemeriksaanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PemeriksaanController extends Controller
{
    /**
     * ============================================================
     * INDEX - Halaman utama (Tab Pemeriksaan + Tab Riwayat)
     * ============================================================
     */
    public function index(Request $request)
    {
        $pegawai = Pegawai::orderBy('nama', 'asc')->get();

        // Riwayat query dengan filter
        $query = Pemeriksaan::with('pegawai');

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('tanggal_pemeriksaan')) {
            $query->whereDate('tanggal_pemeriksaan', $request->tanggal_pemeriksaan);
        }

        // Sorting — default terbaru
        $sortBy    = $request->input('sort_by', 'tanggal_pemeriksaan');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['tanggal_pemeriksaan', 'pegawai_id', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'tanggal_pemeriksaan';
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';

        $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc');
        $riwayat = $query->paginate(10);

        // Statistics untuk dashboard (tetap ada, bisa dipakai kalau dibutuhkan)
        $countBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)->count();
        $countHariIni  = Pemeriksaan::whereDate('tanggal_pemeriksaan', Carbon::today())->count();
        $countPegawai  = Pemeriksaan::distinct('pegawai_id')->count('pegawai_id');

        return view('admin.pemeriksaan.index', [
            'title'           => 'Pemeriksaan',
            'menuPemeriksaan' => 'active',
            'pegawai'         => $pegawai,
            'riwayat'         => $riwayat,
            'countBulanIni'   => $countBulanIni,
            'countHariIni'    => $countHariIni,
            'countPegawai'    => $countPegawai,
        ]);
    }

    /**
     * ============================================================
     * RIWAYAT - Redirect ke index dengan tab riwayat aktif
     * ============================================================
     */
    public function riwayat(Request $request)
    {
        $pegawai = Pegawai::orderBy('nama', 'asc')->get();

        $query = Pemeriksaan::with('pegawai');

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('tanggal_pemeriksaan')) {
            $query->whereDate('tanggal_pemeriksaan', $request->tanggal_pemeriksaan);
        }

        $sortBy    = $request->input('sort_by', 'tanggal_pemeriksaan');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['tanggal_pemeriksaan', 'pegawai_id', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'tanggal_pemeriksaan';
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';

        $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc');
        $riwayat = $query->paginate(10);

        $countBulanIni = Pemeriksaan::whereMonth('tanggal_pemeriksaan', Carbon::now()->month)
            ->whereYear('tanggal_pemeriksaan', Carbon::now()->year)->count();
        $countHariIni  = Pemeriksaan::whereDate('tanggal_pemeriksaan', Carbon::today())->count();
        $countPegawai  = Pemeriksaan::distinct('pegawai_id')->count('pegawai_id');

        return view('admin.pemeriksaan.index', [
            'title'           => 'Pemeriksaan',
            'menuPemeriksaan' => 'active',
            'pegawai'         => $pegawai,
            'riwayat'         => $riwayat,
            'countBulanIni'   => $countBulanIni,
            'countHariIni'    => $countHariIni,
            'countPegawai'    => $countPegawai,
            'activeTab'       => 'riwayat',
        ]);
    }

    /**
     * ============================================================
     * STORE - Simpan hasil pemeriksaan baru
     * ============================================================
     *
     * CATATAN PENTING:
     * Form input menggunakan nama 'nilai_glukometer' (di blade),
     * sedangkan kolom DB adalah 'konsentrasi_glukosa'.
     * Mapping dilakukan di sini secara eksplisit.
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'          => 'required|exists:pegawais,id',
            'tanggal_pemeriksaan' => 'required|date',
            'tinggi_badan'        => 'nullable|numeric|min:50|max:300',
            'berat_badan'         => 'nullable|numeric|min:20|max:500',
            'sistolik'            => 'nullable|numeric|min:50|max:300',
            'diastolik'           => 'nullable|numeric|min:30|max:200',
            'nadi'                => 'nullable|numeric|min:30|max:250',
            'nilai_glukometer'    => 'nullable|numeric|min:30|max:600', // nama input di form
            'parameter_gula'      => 'nullable|in:GDS,GDP,GD2PP',
            'kolesterol_total'    => 'nullable|numeric|min:50|max:800',
            'asam_urat'           => 'nullable|numeric|min:1|max:15',
            'catatan_dokter'      => 'nullable|string|max:1000',
        ]);

        Pemeriksaan::create([
            'pegawai_id'          => $request->pegawai_id,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'tinggi_badan'        => $request->tinggi_badan,
            'berat_badan'         => $request->berat_badan,
            'sistolik'            => $request->sistolik,
            'diastolik'           => $request->diastolik,
            'nadi'                => $request->nadi,
            // ⚠️ Map nilai_glukometer (form) → konsentrasi_glukosa (DB)
            'konsentrasi_glukosa' => $request->nilai_glukometer,
            'parameter_gula'      => $request->parameter_gula,
            'kolesterol_total'    => $request->kolesterol_total,
            'asam_urat'           => $request->asam_urat,
            'catatan_dokter'      => $request->catatan_dokter,
        ]);

        return redirect()   ->route('pemeriksaanRiwayat')
                            ->with('success', 'Data pemeriksaan berhasil disimpan!');
    }

    /**
     * ============================================================
     * SHOW - Detail pemeriksaan (JSON untuk AJAX modal)
     * ============================================================
     */
    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with('pegawai')->findOrFail($id);

        // Tambahkan nilai_glukometer alias agar modal JS tetap bekerja
        $data = $pemeriksaan->toArray();
        $data['nilai_glukometer'] = $pemeriksaan->konsentrasi_glukosa;

        return response()->json($data);
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

        return redirect()->route('pemeriksaanRiwayat')
            ->with('success', 'Data pemeriksaan berhasil dihapus!');
    }

    /**
     * ============================================================
     * EXPORT EXCEL - Mengikuti filter & sort yang aktif
     * ============================================================
     */
    public function exportExcel(Request $request)
    {
        $query = Pemeriksaan::with('pegawai');

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('tanggal_pemeriksaan')) {
            $query->whereDate('tanggal_pemeriksaan', $request->tanggal_pemeriksaan);
        }

        $sortBy    = $request->input('sort_by', 'tanggal_pemeriksaan');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['tanggal_pemeriksaan', 'pegawai_id', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'tanggal_pemeriksaan';
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';

        $pemeriksaan = $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc')->get();
        $fileName    = 'Riwayat_Pemeriksaan_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PemeriksaanExport($pemeriksaan), $fileName);
    }

    /**
     * ============================================================
     * EXPORT PDF - Mengikuti filter & sort yang aktif
     * ============================================================
     */
    public function exportPdf(Request $request)
    {
        $query = Pemeriksaan::with('pegawai');

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('tanggal_pemeriksaan')) {
            $query->whereDate('tanggal_pemeriksaan', $request->tanggal_pemeriksaan);
        }

        $sortBy    = $request->input('sort_by', 'tanggal_pemeriksaan');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['tanggal_pemeriksaan', 'pegawai_id', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'tanggal_pemeriksaan';
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';

        $pemeriksaan = $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc')->get();
        $fileName    = 'Riwayat_Pemeriksaan_' . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('admin.pemeriksaan.pdf', compact('pemeriksaan'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($fileName);
    }
}
