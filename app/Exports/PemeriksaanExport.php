<?php

namespace App\Exports;

use App\Models\Pemeriksaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemeriksaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $pemeriksaan;

    public function __construct($pemeriksaan)
    {
        $this->pemeriksaan = $pemeriksaan;
    }

    public function collection()
    {
        return $this->pemeriksaan;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'Tanggal Pemeriksaan',
            'Tinggi Badan (cm)',
            'Berat Badan (kg)',
            'BMI',
            'Status BMI',
            'Sistolik (mmHg)',
            'Diastolik (mmHg)',
            'MAP',
            'Status Tekanan Darah',
            'Denyut Nadi (bpm)',
            'Konsentrasi Glukosa (mg/dL)',  // kolom DB: konsentrasi_glukosa
            'Parameter Gula',
            'Status Gula Darah',
            'Kolesterol Total (mg/dL)',
            'Status Kolesterol',
            'Asam Urat (mg/dL)',
            'Status Asam Urat',
            'Catatan Dokter',
        ];
    }

    public function map($pemeriksaan): array
    {
        static $no = 0;
        $no++;

        // BMI
        $bmi = null;
        $bmiStatus = '-';
        if ($pemeriksaan->tinggi_badan > 0 && $pemeriksaan->berat_badan > 0) {
            $bmi = round($pemeriksaan->berat_badan / ($pemeriksaan->tinggi_badan / 100) ** 2, 1);
            if      ($bmi < 16)    $bmiStatus = 'Kekurangan Tingkat III';
            elseif  ($bmi < 17)    $bmiStatus = 'Kekurangan Tingkat II';
            elseif  ($bmi < 18.5)  $bmiStatus = 'Kekurangan Tingkat I';
            elseif  ($bmi <= 24.9) $bmiStatus = 'Normal/Ideal';
            elseif  ($bmi <= 29.9) $bmiStatus = 'Kelebihan Berat Badan';
            elseif  ($bmi <= 34.9) $bmiStatus = 'Obesitas Tingkat I';
            elseif  ($bmi <= 39.9) $bmiStatus = 'Obesitas Tingkat II';
            else                    $bmiStatus = 'Obesitas Tingkat III';
        }

        // MAP
        $map = null;
        if ($pemeriksaan->sistolik && $pemeriksaan->diastolik) {
            $map = round(($pemeriksaan->sistolik + (2 * $pemeriksaan->diastolik)) / 3, 1);
        }

        // Tekanan Darah
        $bpStatus = '-';
        if ($pemeriksaan->sistolik && $pemeriksaan->diastolik) {
            $sbp = $pemeriksaan->sistolik;
            $dbp = $pemeriksaan->diastolik;
            if      ($sbp > 180 || $dbp > 120)                                           $bpStatus = 'Krisis Hipertensi';
            elseif  ($sbp >= 140 || $dbp >= 90)                                           $bpStatus = 'Hipertensi Derajat 2';
            elseif  (($sbp >= 130 && $sbp <= 139) || ($dbp >= 80 && $dbp <= 89))         $bpStatus = 'Hipertensi Derajat 1';
            elseif  ($sbp >= 120 && $sbp <= 129 && $dbp < 80)                            $bpStatus = 'Prehipertensi';
            elseif  ($sbp < 120 && $dbp < 80)                                            $bpStatus = 'Optimal/Normal';
            elseif  ($sbp < 90 && $dbp < 60)                                             $bpStatus = 'Hipotensi';
        }

        // Gula Darah — pakai konsentrasi_glukosa (kolom DB)
        $bsStatus = '-';
        if ($pemeriksaan->konsentrasi_glukosa && $pemeriksaan->parameter_gula) {
            $nilai = $pemeriksaan->konsentrasi_glukosa;
            switch ($pemeriksaan->parameter_gula) {
                case 'GDP':
                    if      ($nilai < 110)  $bsStatus = 'Normal';
                    elseif  ($nilai <= 125) $bsStatus = 'Prediabetes';
                    else                    $bsStatus = 'Diabetes';
                    break;
                case 'GD2PP':
                    if      ($nilai < 140)  $bsStatus = 'Normal';
                    elseif  ($nilai <= 179) $bsStatus = 'Prediabetes';
                    else                    $bsStatus = 'Diabetes';
                    break;
                case 'GDS':
                    if      ($nilai < 180)  $bsStatus = 'Normal';
                    elseif  ($nilai <= 199) $bsStatus = 'Waspada';
                    else                    $bsStatus = 'Diabetes';
                    break;
            }
        }

        // Kolesterol
        $cholStatus = '-';
        if ($pemeriksaan->kolesterol_total) {
            if      ($pemeriksaan->kolesterol_total < 200)  $cholStatus = 'Normal';
            elseif  ($pemeriksaan->kolesterol_total <= 239) $cholStatus = 'Borderline';
            else                                             $cholStatus = 'Tinggi';
        }

        // Asam Urat
        $uaStatus = '-';
        if ($pemeriksaan->asam_urat && $pemeriksaan->pegawai) {
            $umur  = $pemeriksaan->pegawai->umur;
            $jk    = strtolower($pemeriksaan->pegawai->jenis_kelamin);
            $nilai = $pemeriksaan->asam_urat;
            $laki  = str_contains($jk, 'laki');

            if ($umur >= 60) {
                $min = $laki ? 3.5 : 2.7;
                $max = $laki ? 8.0 : 7.3;
            } else {
                $min = $laki ? 3.5 : 2.6;
                $max = $laki ? 7.2 : 6.0;
            }
            if      ($nilai < $min) $uaStatus = 'Rendah';
            elseif  ($nilai <= $max) $uaStatus = 'Normal';
            else                     $uaStatus = 'Tinggi';
        }

        return [
            $no,
            $pemeriksaan->pegawai->nama ?? '-',
            $pemeriksaan->tanggal_pemeriksaan ? $pemeriksaan->tanggal_pemeriksaan->format('d-m-Y') : '-',
            $pemeriksaan->tinggi_badan ?? '-',
            $pemeriksaan->berat_badan ?? '-',
            $bmi ?? '-',
            $bmiStatus,
            $pemeriksaan->sistolik ?? '-',
            $pemeriksaan->diastolik ?? '-',
            $map ?? '-',
            $bpStatus,
            $pemeriksaan->nadi ?? '-',
            $pemeriksaan->konsentrasi_glukosa ?? '-',  // kolom DB
            $pemeriksaan->parameter_gula ?? '-',
            $bsStatus,
            $pemeriksaan->kolesterol_total ?? '-',
            $cholStatus,
            $pemeriksaan->asam_urat ?? '-',
            $uaStatus,
            $pemeriksaan->catatan_dokter ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4E73DF']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getRowDimension(1)->setRowHeight(28);

        return $sheet;
    }
}
