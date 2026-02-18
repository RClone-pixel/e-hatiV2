<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * ============================================================
 * PEGAWAI EXPORT - Export Data Pegawai ke Excel
 * ============================================================
 *
 * Package: maatwebsite/excel (Laravel Excel)
 * Dokumentasi: https://docs.laravel-excel.com/3.1/
 *
 * Install:
 * composer require maatwebsite/excel
 *
 * Fitur:
 * - Export seluruh data pegawai ke format Excel (.xlsx)
 * - Dengan header yang jelas
 * - Format tanggal yang readable
 *
 * CARA MENAMBAH KOLOM BARU:
 * 1. Tambahkan heading di method headings()
 * 2. Tambahkan data di method map()
 * 3. Sesuaikan styling jika diperlukan
 */

class PegawaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Mengambil seluruh data pegawai untuk diekspor
     * Data diurutkan berdasarkan nama ascending (A-Z)
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pegawai::orderBy('nama', 'asc')->get();
    }

    /**
     * ============================================================
     * HEADER KOLOM EXCEL
     * ============================================================
     *
     * Definisi nama kolom yang akan muncul di baris pertama Excel
     * Urutan harus sesuai dengan method map() di bawah
     *
     * CARA MENAMBAH KOLOM:
     * Tambahkan string baru di array ini
     */
    public function headings(): array
    {
        return [
            'No.',              // Kolom A - Nomor urut
            'Nama',             // Kolom B - Nama lengkap pegawai
            'Jenis Kelamin',    // Kolom C - Laki-laki / Perempuan
            'Tanggal Lahir',    // Kolom D - Format: DD-MM-YYYY
            'Umur',             // Kolom E - Umur dalam tahun
            'Gol. Darah',       // Kolom F - Golongan darah
            'Riwayat Penyakit', // Kolom G - Riwayat kesehatan
        ];
    }

    /**
     * ============================================================
     * MAPPING DATA KE KOLOM
     * ============================================================
     *
     * Mengubah object Pegawai menjadi array untuk setiap baris Excel
     * Urutan harus sesuai dengan headings() di atas
     *
     * @param Pegawai $pegawai
     * @return array
     */
    public function map($pegawai): array
    {
        // Static counter untuk nomor urut
        static $no = 0;
        $no++;

        return [
            $no,                                            // No.
            $pegawai->nama,                                 // Nama
            $pegawai->jenis_kelamin,                        // Jenis Kelamin
            $pegawai->tanggal_lahir->format('d-m-Y'),       // Tanggal Lahir (format Indonesia)
            $pegawai->umur . ' Tahun',                      // Umur
            $pegawai->gol_darah,                            // Gol. Darah
            $pegawai->riwayat_penyakit,                     // Riwayat Penyakit
        ];
    }

    /**
     * ============================================================
     * STYLING EXCEL
     * ============================================================
     *
     * Memberikan style pada worksheet Excel
     * - Header: Bold, background biru, teks putih
     * - Auto-width kolom
     *
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header (baris 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,                 // Teks tebal
                'color' => ['rgb' => 'FFFFFF'], // Warna teks putih
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4E73DF'], // Background biru
            ],
            'alignment' => [
                'horizontal' => 'center',       // Teks di tengah
                'vertical' => 'center',
            ],
        ]);

        // Auto-width untuk semua kolom
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Alignment center untuk kolom No, Jenis Kelamin, Umur, Gol. Darah
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C:C')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F:F')->getAlignment()->setHorizontal('center');

        return [];
    }
}
