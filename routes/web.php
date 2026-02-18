<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemeriksaanController;

Route::get('/', function () {
    return view('welcome');
});

// ============================================================
// DASHBOARD
// ============================================================
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============================================================
// PEGAWAI ROUTES
// ============================================================
Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai');
Route::get('pegawai/create', [PegawaiController::class, 'create'])->name('pegawaiCreate');
Route::post('pegawai/store', [PegawaiController::class, 'store'])->name('pegawaiStore');
Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawaiEdit');
Route::post('pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawaiUpdate');
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'delete'])->name('pegawaiDelete');

/**
 * ============================================================
 * EXPORT PEGAWAI - EXCEL & PDF
 * ============================================================
 *
 * Route untuk export data pegawai ke Excel dan PDF
 *
 * CARA PENAMBAHAN:
 * 1. Pastikan sudah install package:
 *    - composer require maatwebsite/excel
 *    - composer require barryvdh/laravel-dompdf
 *
 * 2. Method ada di PegawaiController:
 *    - exportExcel() untuk export Excel
 *    - exportPdf() untuk export PDF
 *
 * 3. File terkait:
 *    - App\Exports\PegawaiExport (untuk Excel)
 *    - resources/views/admin/pegawai/pdf.blade.php (untuk PDF)
 */
// Export Excel Pegawai
Route::get('pegawai/export-excel', [PegawaiController::class, 'exportExcel'])->name('pegawaiExportExcel');
// Export PDF Pegawai
Route::get('pegawai/export-pdf', [PegawaiController::class, 'exportPdf'])->name('pegawaiExportPdf');

// ============================================================
// PEMERIKSAAN ROUTES
// ============================================================
Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan');
Route::post('pemeriksaan/store', [PemeriksaanController::class, 'store'])->name('pemeriksaanStore');
Route::get('pemeriksaan/riwayat', [PemeriksaanController::class, 'riwayat'])->name('pemeriksaanRiwayat');
Route::get('pemeriksaan/riwayat/{id}', [PemeriksaanController::class, 'show'])->name('pemeriksaanShow');
Route::delete('pemeriksaan/delete/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaanDelete');

/**
 * ============================================================
 * EXPORT PEMERIKSAAN - EXCEL & PDF
 * ============================================================
 *
 * Route untuk export data pemeriksaan/riwayat ke Excel dan PDF
 *
 * CARA PENAMBAHAN:
 * 1. Pastikan sudah install package:
 *    - composer require maatwebsite/excel
 *    - composer require barryvdh/laravel-dompdf
 *
 * 2. Method ada di PemeriksaanController:
 *    - exportExcel() untuk export Excel
 *    - exportPdf() untuk export PDF
 *
 * 3. File terkait:
 *    - App\Exports\PemeriksaanExport (untuk Excel)
 *    - resources/views/admin/pemeriksaan/pdf.blade.php (untuk PDF)
 */
// Export Excel Pemeriksaan
Route::get('pemeriksaan/export-excel', [PemeriksaanController::class, 'exportExcel'])->name('pemeriksaanExportExcel');
// Export PDF Pemeriksaan
Route::get('pemeriksaan/export-pdf', [PemeriksaanController::class, 'exportPdf'])->name('pemeriksaanExportPdf');

// ============================================================
// AJAX ROUTES (untuk dropdown dan data dinamis)
// ============================================================
Route::get('/ajax/pegawai', [PegawaiController::class, 'ajaxIndex'])->name('ajaxPegawai');
Route::get('/ajax/pegawai/{id}', [PegawaiController::class, 'ajaxShow'])->name('ajaxShow');
