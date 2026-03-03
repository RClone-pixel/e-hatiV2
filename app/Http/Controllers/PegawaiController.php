<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/** ============================================================
 * IMPORT UNTUK EXPORT EXCEL
 * ============================================================
 * Pastikan sudah install package Laravel Excel:
 * composer require maatwebsite/excel
 *
 * Dokumentasi: https://docs.laravel-excel.com/3.1/
 ============================================================ */

use App\Exports\PegawaiExport;
use Maatwebsite\Excel\Facades\Excel;

/** ============================================================
 * IMPORT UNTUK EXPORT PDF
 * ============================================================
 * Pastikan sudah install package Laravel DomPDF:
 * composer require barryvdh/laravel-dompdf
 *
 * Dokumentasi: https://github.com/barryvdh/laravel-dompdf
 ============================================================ */

 use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiController extends Controller
{
    /**
     * ============================================================
     * INDEX - Menampilkan daftar semua pegawai
     * ============================================================
     */
    public function index()
    {
        $data = array(
            'title'             => 'Data Pegawai',
            'menuPegawai'       => 'active',
            'pegawai'           => Pegawai::orderBy('Nama', 'asc')->get(),
        );
        return view('admin.pegawai.index', $data);
    }

    /**
     * ============================================================
     * CREATE - Menampilkan form tambah pegawai baru
     * ============================================================
     */
    public function create()
    {
        $data = array(
            'title'             => 'Tambah Data Pegawai',
            'menuPegawai'       => 'active',
        );
        return view('admin.pegawai.create', $data);
    }

    /**
     * ============================================================
     * STORE - Menyimpan data pegawai baru ke database
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required',
            'jenis_kelamin'     => 'required',
            'tanggal_lahir'     => 'required',
            'golongan_darah'    => 'required',
            'riwayat_penyakit'  => 'nullable',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required'                 => 'Nama tidak boleh kosong',
            'jenis_kelamin.required'        => 'Jenis Kelamin tidak boleh kosong',
            'tanggal_lahir.required'        => 'Tanggal Lahir tidak boleh kosong',
            'golongan_darah.required'       => 'Golongan Darah harus dipilih',
            'foto.image'                    => 'File harus berupa gambar',
            'foto.mimes'                    => 'Format foto harus jpeg, jpg, png',
            'foto.max'                      => 'Ukuran foto maksimal 2MB',
        ]);

        $pegawai = new Pegawai;
        $pegawai->nama              = $request->nama;
        $pegawai->jenis_kelamin     = $request->jenis_kelamin;
        $pegawai->tanggal_lahir     = $request->tanggal_lahir;
        $pegawai->umur              = \Carbon\Carbon::parse($request->tanggal_lahir)->age;
        $pegawai->gol_darah         = $request->golongan_darah;
        $pegawai->riwayat_penyakit  = $request->riwayat_penyakit;

        $pegawai->riwayat_penyakit  = $request->riwayat_penyakit ?? 'Tidak Ada';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            if ($file->isValid()) {
                // Simpan ke disk 'public' agar bisa diakses via URL
                $path = $file->store('foto-pegawai', 'public');
                $pegawai->foto = $path;
            } else {
                return back()->withErrors(['foto' => 'Gagal mengunggah foto'])->withInput();
            }
        }
        $pegawai->save();

        return redirect()->route('pegawai')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * ============================================================
     * EDIT - Menampilkan form edit pegawai
     * ============================================================
     */
    public function edit($id)
    {
        $data = array(
            'title'             => 'Edit Data Pegawai',
            'menuPegawai'       => 'active',
            'pegawai'           => Pegawai::FindOrFail($id),
        );
        return view('admin.pegawai.edit', $data);
    }

    /**
     * ============================================================
     * UPDATE - Menyimpan perubahan data pegawai
     * ============================================================
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'              => 'required',
            'jenis_kelamin'     => 'required',
            'tanggal_lahir'     => 'required',
            'golongan_darah'    => 'required',
            'riwayat_penyakit'  => 'required',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required'             => 'Nama tidak boleh kosong',
            'jenis_kelamin.required'    => 'Jenis Kelamin tidak boleh kosong',
            'tanggal_lahir.required'    => 'Tanggal Lahir tidak boleh kosong',
            'golongan_darah.required'   => 'Golongan Darah harus dipilih',
            'foto.image'                => 'File harus berupa gambar',
            'foto.mimes'                => 'Format foto harus jpeg, jpg, png',
            'foto.max'                  => 'Ukuran foto maksimal 2MB',
            'foto.uploaded'             => 'File gagal diupload. Pastikan file berupa gambar dan ukuran di bawah 2MB.',
        ]);

        $pegawai = Pegawai::FindOrFail($id);
        $pegawai->nama              = $request->nama;
        $pegawai->jenis_kelamin     = $request->jenis_kelamin;
        $pegawai->tanggal_lahir     = $request->tanggal_lahir;
        $pegawai->umur              = \Carbon\Carbon::parse($request->tanggal_lahir)->age;
        $pegawai->gol_darah         = $request->golongan_darah;
        $pegawai->riwayat_penyakit  = $request->riwayat_penyakit;

        $pegawai->riwayat_penyakit  = $request->riwayat_penyakit ?? 'Tidak Ada';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            if ($file->isValid()) {
                // Hapus foto lama jika ada
                if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                    Storage::disk('public')->delete($pegawai->foto);
                }

                // Simpan foto baru ke disk 'public'
                $path = $file->store('foto-pegawai', 'public');
                $pegawai->foto = $path;
            } else {
                return back()->withErrors(['foto' => 'Gagal mengunggah foto'])->withInput();
            }
        }
        $pegawai->save();

        return redirect()->route('pegawai')->with('success', 'Data berhasil diedit');
    }

    /**
     * ============================================================
     * DELETE - Menghapus data pegawai
     * ============================================================
     */
    public function delete($id)
    {
        $pegawai = Pegawai::FindOrFail($id);

        // Hapus foto dari storage jika ada
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('pegawai')->with('success', 'Data berhasil dihapus');
    }

    /**
     * ============================================================
     * EXPORT EXCEL - Export data pegawai ke format Excel
     * ============================================================
     *
     * URL: /pegawai/export-excel
     * Method: GET
     *
     * Menggunakan Laravel Excel untuk generate file .xlsx
     * File akan otomatis didownload oleh browser
     *
     * CARA KERJA:
     * 1. Memanggil class PegawaiExport (App\Exports\PegawaiExport)
     * 2. Laravel Excel akan generate file Excel
     * 3. File didownload dengan nama yang sudah diformat
     */
    public function exportExcel(Request $request)
    {
        $sort      = $request->query('sort', 'nama');
        $direction = $request->query('dir', 'asc');

        $allowedSorts = ['nama', 'jenis_kelamin', 'tanggal_lahir', 'umur', 'gol_darah'];
        if (!in_array($sort, $allowedSorts)) $sort = 'nama';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        $pegawai = Pegawai::orderBy($sort, $direction)->get();

        $fileName = 'Data_Pegawai_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PegawaiExport($pegawai), $fileName);
    }

    /**
     * ============================================================
     * EXPORT PDF - Export data pegawai ke format PDF
     * ============================================================
     *
     * URL: /pegawai/export-pdf
     * Method: GET
     *
     * Menggunakan Laravel DomPDF untuk generate file PDF
     * File akan otomatis didownload atau ditampilkan di browser
     *
     * CARA KERJA:
     * 1. Mengambil semua data pegawai dari database
     * 2. Load view khusus untuk PDF (admin.pegawai.pdf)
     * 3. DomPDF akan merender HTML menjadi PDF
     * 4. File didownload dengan nama yang sudah diformat
     */
    public function exportPdf(Request $request)
    {
        $sort      = $request->query('sort', 'nama');      // default: nama
        $direction = $request->query('dir', 'asc');         // default: asc

        // Whitelist kolom yang boleh disort (keamanan)
        $allowedSorts = ['nama', 'jenis_kelamin', 'tanggal_lahir', 'umur', 'gol_darah'];
        if (!in_array($sort, $allowedSorts)) $sort = 'nama';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        $pegawai = Pegawai::orderBy($sort, $direction)->get();

        $pdf = Pdf::loadView('admin.pegawai.pdf', compact('pegawai', 'sort', 'direction'));
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Data_Pegawai_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * ============================================================
     * AJAX INDEX - API untuk mendapatkan daftar pegawai (JSON)
     * ============================================================
     * Digunakan untuk dropdown select di form pemeriksaan
     */
    public function ajaxIndex()
    {
        $pegawai = Pegawai::select('id', 'nama')
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json($pegawai);
    }

    /**
     * ============================================================
     * AJAX SHOW - API untuk mendapatkan detail pegawai (JSON)
     * ============================================================
     * Digunakan untuk menampilkan detail pegawai saat dipilih
     */
    public function ajaxShow($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $data = $pegawai->toArray();

        $data['tanggal_lahir'] = $pegawai->tanggal_lahir->format('Y-m-d');

        if ($pegawai->foto) {
            $data['foto_url'] = asset('storage/' . $pegawai->foto);
        } else {
            $data['foto_url'] = asset('sbadmin2/img/user_kppn.png');
        }

        return response()->json($data);
    }
}
