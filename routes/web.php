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

// Export Excel Pegawai
Route::get('pegawai/export-excel', [PegawaiController::class, 'exportExcel'])->name('pegawaiExportExcel');
// Export PDF Pegawai
Route::get('pegawai/export-pdf', [PegawaiController::class, 'exportPdf'])->name('pegawaiExportPdf');

// ============================================================
// PEGAWAI ROUTES
// ============================================================
Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai');
Route::get('pegawai/create', [PegawaiController::class, 'create'])->name('pegawaiCreate');
Route::post('pegawai/store', [PegawaiController::class, 'store'])->name('pegawaiStore');
Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawaiEdit');
Route::post('pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawaiUpdate');
Route::delete('pegawai/delete/{id}', [PegawaiController::class, 'delete'])->name('pegawaiDelete');

// ============================================================
// PEMERIKSAAN ROUTES
// ============================================================
Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan');
Route::post('pemeriksaan/store', [PemeriksaanController::class, 'store'])->name('pemeriksaanStore');
Route::get('pemeriksaan/riwayat', [PemeriksaanController::class, 'riwayat'])->name('pemeriksaanRiwayat');
Route::get('pemeriksaan/riwayat/{id}', [PemeriksaanController::class, 'show'])->name('pemeriksaanShow');
Route::delete('pemeriksaan/delete/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaanDelete');

// Export Excel Pemeriksaan
Route::get('pemeriksaan/export-excel', [PemeriksaanController::class, 'exportExcel'])->name('pemeriksaanExportExcel');
// Export PDF Pemeriksaan
Route::get('pemeriksaan/export-pdf', [PemeriksaanController::class, 'exportPdf'])->name('pemeriksaanExportPdf');

// ============================================================
// AJAX ROUTES (untuk dropdown dan data dinamis)
// ============================================================
Route::get('/ajax/pegawai', [PegawaiController::class, 'ajaxIndex'])->name('ajaxPegawai');
Route::get('/ajax/pegawai/{id}', [PegawaiController::class, 'ajaxShow'])->name('ajaxShow');
