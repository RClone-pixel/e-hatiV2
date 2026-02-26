<?php

namespace App\Exports;

use App\Models\Pemeriksaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * ============================================================
 * PEMERIKSAAN EXPORT - Export Data Riwayat Pemeriksaan ke Excel
 * ============================================================
 *
 * Package: maatwebsite/excel (Laravel Excel)
 * Dokumentasi: https://docs.laravel-excel.com/3.1/
 *
 * Install:
 * composer require maatwebsite/excel
 *
 * Fitur:
 * - Export seluruh data pemeriksaan ke format Excel (.xlsx)
 * - Dengan header yang jelas
 * - Format tanggal yang readable
 * - Perhitungan BMI otomatis
 *
 * CARA MENAMBAH KOLOM BARU:
 * 1. Tambahkan heading di method headings()
 * 2. Tambahkan data di method map()
 * 3. Sesuaikan styling jika diperlukan
 */

class PemeriksaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Mengambil seluruh data pemeriksaan untuk diekspor
     * Data diurutkan berdasarkan tanggal pemeriksaan (terbaru dulu)
     * Include relasi pegawai untuk mengambil nama pegawai
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pemeriksaan::with('pegawai')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();
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
            'No.',                  // Kolom A - Nomor urut
            'Nama Pegawai',         // Kolom B - Nama pegawai (dari relasi)
            'Tanggal Pemeriksaan',  // Kolom C - Format: DD-MM-YYYY
            'Status Puasa',         // Kolom D - Puasa / Tidak Puasa

            // BMI Section
            'Tinggi Badan (cm)',    // Kolom E
            'Berat Badan (kg)',     // Kolom F
            'BMI',                  // Kolom G - Dihitung otomatis

            // Tekanan Darah Section
            'Sistolik (mmHg)',      // Kolom H
            'Diastolik (mmHg)',     // Kolom I
            'Nadi (bpm)',           // Kolom J

            // Laboratorium Section
            'Gula Darah (mg/dL)',   // Kolom K
            'Parameter Gula',       // Kolom L - GDS/GDP/GD2PP
            'Kolesterol (mg/dL)',   // Kolom M
            'Asam Urat (mg/dL)',    // Kolom N

            // Catatan
            'Catatan Dokter',       // Kolom O
        ];
    }

    /**
     * ============================================================
     * MAPPING DATA KE KOLOM
     * ============================================================
     *
     * Mengubah object Pemeriksaan menjadi array untuk setiap baris Excel
     * Urutan harus sesuai dengan headings() di atas
     *
     * @param Pemeriksaan $pemeriksaan
     * @return array
     */
    public function map($pemeriksaan): array
    {
        // Static counter untuk nomor urut
        static $no = 0;
        $no++;

        // Hitung BMI jika data tersedia
        $bmi = '-';
        if ($pemeriksaan->tinggi_badan > 0 && $pemeriksaan->berat_badan > 0) {
            $bmiValue = $pemeriksaan->berat_badan / pow($pemeriksaan->tinggi_badan / 100, 2);
            $bmi = number_format($bmiValue, 1);
        }

        return [
            $no,                                                    // No.
            $pemeriksaan->pegawai->nama ?? '-',                    // Nama Pegawai
            $pemeriksaan->tanggal_pemeriksaan->format('d-m-Y'),     // Tanggal Pemeriksaan
            $pemeriksaan->puasa ? 'Puasa' : 'Tidak Puasa',          // Status Puasa

            // BMI
            $pemeriksaan->tinggi_badan ?: '-',                      // Tinggi Badan
            $pemeriksaan->berat_badan ?: '-',                       // Berat Badan
            $bmi,                                                   // BMI

            // Tekanan Darah
            $pemeriksaan->sistolik ?: '-',                          // Sistolik
            $pemeriksaan->diastolik ?: '-',                         // Diastolik
            $pemeriksaan->nadi ?: '-',                              // Nadi

            // Laboratorium
            $pemeriksaan->konsentrasi_glukosa ?: '-',                  // Gula Darah
            $pemeriksaan->parameter_gula ?: '-',                    // Parameter Gula
            $pemeriksaan->kolesterol_total ?: '-',                  // Kolesterol
            $pemeriksaan->asam_urat ?: '-',                         // Asam Urat

            // Catatan
            $pemeriksaan->catatan_dokter ?: '-',                    // Catatan Dokter
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
     * - Alignment untuk kolom tertentu
     *
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header (baris 1)
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,                 // Teks tebal
                'color' => ['rgb' => 'FFFFFF'], // Warna teks putih
                'size' => 11,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '36B9CC'], // Background biru muda (teal)
            ],
            'alignment' => [
                'horizontal' => 'center',       // Teks di tengah
                'vertical' => 'center',
                'wrapText' => true,             // Wrap text untuk header panjang
            ],
        ]);

        // Auto-width untuk semua kolom
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Alignment center untuk kolom numerik
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal('center');   // No
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal('center');   // Tinggi
        $sheet->getStyle('F:F')->getAlignment()->setHorizontal('center');   // Berat
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal('center');   // BMI
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal('center');   // Sistolik
        $sheet->getStyle('I:I')->getAlignment()->setHorizontal('center');   // Diastolik
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal('center');   // Nadi
        $sheet->getStyle('K:K')->getAlignment()->setHorizontal('center');   // Gula
        $sheet->getStyle('M:M')->getAlignment()->setHorizontal('center');   // Kolesterol
        $sheet->getStyle('N:N')->getAlignment()->setHorizontal('center');   // Asam Urat

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}
