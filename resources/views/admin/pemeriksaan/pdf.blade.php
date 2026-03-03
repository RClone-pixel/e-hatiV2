<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemeriksaan - e-HATi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 7.5pt;
            line-height: 1.4;
            color: #3a3b45;
            padding: 16px 18px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2.5px solid #36b9cc;
        }
        .header-title   { font-size: 14pt; font-weight: 900; color: #36b9cc; margin-bottom: 3px; letter-spacing: 1px; }
        .header-sub     { font-size: 8.5pt; font-weight: 700; color: #5a5c69; }
        .header-org     { font-size: 7.5pt; color: #858796; }
        .header-date    { font-size: 7pt; color: #b7b9cc; margin-top: 3px; }

        /* Info bar */
        .info-bar       { width: 100%; border-collapse: collapse; margin: 10px 0 12px 0; }
        .info-bar td    { border: none; padding: 0; vertical-align: middle; font-size: 7.8pt; }
        .info-total     { color: #5a5c69; font-weight: 600; }
        .info-total strong { color: #36b9cc; font-size: 9pt; font-weight: 900; }

        /* Table */
        table.pdf-table { width: 100%; border-collapse: collapse; }
        table.pdf-table thead th {
            background: #36b9cc;
            color: #ffffff;
            font-weight: 700;
            font-size: 6.8pt;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 7px 5px;
            text-align: center;
            border: none;
        }
        table.pdf-table tbody td {
            padding: 6px 5px;
            font-size: 7.5pt;
            color: #5a5c69;
            border-bottom: 1px solid #eaecf4;
            vertical-align: middle;
        }
        table.pdf-table tbody tr:nth-child(even) td { background: #f8f9fc; }
        table.pdf-table tbody tr:nth-child(odd)  td { background: #ffffff; }
        table.pdf-table tbody tr:last-child td      { border-bottom: 2px solid #d1d3e2; }

        /* Column widths — landscape A4 */
        .col-no    { width: 24px; text-align: center; color: #b7b9cc; font-weight: 700; font-size: 7pt; }
        .col-nama  { width: 13%; font-weight: 700; color: #2e2f38; }
        .col-tgl   { width: 7%;  text-align: center; }
        .col-num   { width: 5%;  text-align: center; }
        .col-stat  { width: 8%;  text-align: center; }
        .col-td    { width: 6%;  text-align: center; }
        .col-nadi  { width: 4%;  text-align: center; }
        .col-gula  { width: 5%;  text-align: center; }
        .col-param { width: 5%;  text-align: center; }
        .col-kol   { width: 5%;  text-align: center; }
        .col-au    { width: 5%;  text-align: center; }
        .col-catat { text-align: left; font-size: 7pt; }

        /* Badges */
        .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-size: 6.5pt; font-weight: 700; }
        .b-normal  { background: #d4edda; color: #155724; }
        .b-warning { background: #fff3cd; color: #856404; }
        .b-danger  { background: #f8d7da; color: #721c24; }
        .b-info    { background: #d1ecf1; color: #0c5460; }

        /* Footer */
        .footer { margin-top: 14px; padding-top: 8px; border-top: 1px solid #e3e6f0; text-align: center; font-size: 7pt; color: #b7b9cc; }
        .footer strong { color: #858796; }

        tbody tr { page-break-inside: avoid; }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-title">RIWAYAT PEMERIKSAAN KESEHATAN</div>
    <div class="header-sub">e-HATi &mdash; Employee Health Information System</div>
    <div class="header-org">KPPN Pangkalan Bun</div>
    <div class="header-date">Dicetak pada: {{ now()->format('d F Y, H:i') }} WITA</div>
</div>

{{-- INFO BAR --}}
<table class="info-bar">
    <tr>
        <td class="info-total">
            Total: <strong>{{ $pemeriksaan->count() }} data</strong>
        </td>
        <td style="text-align:right; color:#b7b9cc; font-size:7pt;">
            Periode: {{ now()->format('F Y') }}
        </td>
    </tr>
</table>

{{-- DATA TABLE --}}
<table class="pdf-table">
    <thead>
        <tr>
            <th class="col-no">No</th>
            <th class="col-nama">Nama Pegawai</th>
            <th class="col-tgl">Tanggal</th>
            <th class="col-num">TB</th>
            <th class="col-num">BB</th>
            <th class="col-num">BMI</th>
            <th class="col-stat">Status BMI</th>
            <th class="col-td">TD (mmHg)</th>
            <th class="col-stat">Status TD</th>
            <th class="col-nadi">Nadi</th>
            <th class="col-gula">Glukosa</th>
            <th class="col-param">Param</th>
            <th class="col-stat">Status GD</th>
            <th class="col-kol">Kol.</th>
            <th class="col-stat">Status Kol.</th>
            <th class="col-au">Asam Urat</th>
            <th class="col-stat">Status AU</th>
            <th class="col-catat">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pemeriksaan as $index => $item)
            @php
                // BMI
                $bmi = null; $bmiStatus = '-'; $bmiClass = '';
                if ($item->tinggi_badan > 0 && $item->berat_badan > 0) {
                    $bmi = round($item->berat_badan / pow($item->tinggi_badan / 100, 2), 1);
                    if      ($bmi < 16)    { $bmiStatus = 'Kkrg III'; $bmiClass = 'b-danger'; }
                    elseif  ($bmi < 17)    { $bmiStatus = 'Kkrg II';  $bmiClass = 'b-warning'; }
                    elseif  ($bmi < 18.5)  { $bmiStatus = 'Kkrg I';   $bmiClass = 'b-info'; }
                    elseif  ($bmi <= 24.9) { $bmiStatus = 'Normal';    $bmiClass = 'b-normal'; }
                    elseif  ($bmi <= 29.9) { $bmiStatus = 'Overweight';$bmiClass = 'b-warning'; }
                    elseif  ($bmi <= 34.9) { $bmiStatus = 'Obst I';   $bmiClass = 'b-danger'; }
                    elseif  ($bmi <= 39.9) { $bmiStatus = 'Obst II';  $bmiClass = 'b-danger'; }
                    else                   { $bmiStatus = 'Obst III';  $bmiClass = 'b-danger'; }
                }

                // Tekanan Darah
                $bpStatus = '-'; $bpClass = '';
                if ($item->sistolik && $item->diastolik) {
                    $s = $item->sistolik; $d = $item->diastolik;
                    if      ($s > 180 || $d > 120)                        { $bpStatus = 'Krisis';    $bpClass = 'b-danger'; }
                    elseif  ($s >= 140 || $d >= 90)                       { $bpStatus = 'HT Drj 2';  $bpClass = 'b-danger'; }
                    elseif  (($s >= 130 && $s <= 139)||($d >= 80&&$d<=89)){ $bpStatus = 'HT Drj 1';  $bpClass = 'b-warning'; }
                    elseif  ($s >= 120 && $s <= 129 && $d < 80)           { $bpStatus = 'Pre-HT';    $bpClass = 'b-warning'; }
                    elseif  ($s < 120 && $d < 80)                         { $bpStatus = 'Normal';    $bpClass = 'b-normal'; }
                    elseif  ($s < 90 && $d < 60)                          { $bpStatus = 'Hipotensi'; $bpClass = 'b-info'; }
                }

                // Gula Darah — konsentrasi_glukosa
                $bsStatus = '-'; $bsClass = '';
                if ($item->konsentrasi_glukosa && $item->parameter_gula) {
                    $ng = $item->konsentrasi_glukosa;
                    switch ($item->parameter_gula) {
                        case 'GDP':
                            if ($ng < 110) { $bsStatus = 'Normal'; $bsClass = 'b-normal'; }
                            elseif ($ng <= 125) { $bsStatus = 'Prediabetes'; $bsClass = 'b-warning'; }
                            else { $bsStatus = 'Diabetes'; $bsClass = 'b-danger'; }
                            break;
                        case 'GD2PP':
                            if ($ng < 140) { $bsStatus = 'Normal'; $bsClass = 'b-normal'; }
                            elseif ($ng <= 179) { $bsStatus = 'Prediabetes'; $bsClass = 'b-warning'; }
                            else { $bsStatus = 'Diabetes'; $bsClass = 'b-danger'; }
                            break;
                        case 'GDS':
                            if ($ng < 180) { $bsStatus = 'Normal'; $bsClass = 'b-normal'; }
                            elseif ($ng <= 199) { $bsStatus = 'Waspada'; $bsClass = 'b-warning'; }
                            else { $bsStatus = 'Diabetes'; $bsClass = 'b-danger'; }
                            break;
                    }
                }

                // Kolesterol
                $cholStatus = '-'; $cholClass = '';
                if ($item->kolesterol_total) {
                    if      ($item->kolesterol_total < 200)  { $cholStatus = 'Normal';     $cholClass = 'b-normal'; }
                    elseif  ($item->kolesterol_total <= 239) { $cholStatus = 'Borderline'; $cholClass = 'b-warning'; }
                    else                                     { $cholStatus = 'Tinggi';     $cholClass = 'b-danger'; }
                }

                // Asam Urat
                $uaStatus = '-'; $uaClass = '';
                if ($item->asam_urat && $item->pegawai) {
                    $umur  = $item->pegawai->umur;
                    $jk    = strtolower($item->pegawai->jenis_kelamin);
                    $laki  = str_contains($jk, 'laki');
                    $nilai = $item->asam_urat;
                    $min = $umur >= 60 ? ($laki ? 3.5 : 2.7) : ($laki ? 3.5 : 2.6);
                    $max = $umur >= 60 ? ($laki ? 8.0 : 7.3) : ($laki ? 7.2 : 6.0);
                    if      ($nilai < $min)  { $uaStatus = 'Rendah'; $uaClass = 'b-info'; }
                    elseif  ($nilai <= $max) { $uaStatus = 'Normal'; $uaClass = 'b-normal'; }
                    else                     { $uaStatus = 'Tinggi'; $uaClass = 'b-danger'; }
                }
            @endphp
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-nama">{{ $item->pegawai->nama ?? '-' }}</td>
                <td class="col-tgl">{{ $item->tanggal_pemeriksaan->format('d-m-Y') }}</td>
                <td class="col-num">{{ $item->tinggi_badan ?? '-' }}</td>
                <td class="col-num">{{ $item->berat_badan ?? '-' }}</td>
                <td class="col-num">{{ $bmi ?? '-' }}</td>
                <td class="col-stat">@if($bmiStatus != '-')<span class="badge {{ $bmiClass }}">{{ $bmiStatus }}</span>@else -@endif</td>
                <td class="col-td">{{ $item->sistolik && $item->diastolik ? $item->sistolik.'/'.$item->diastolik : '-' }}</td>
                <td class="col-stat">@if($bpStatus != '-')<span class="badge {{ $bpClass }}">{{ $bpStatus }}</span>@else -@endif</td>
                <td class="col-nadi">{{ $item->nadi ?? '-' }}</td>
                <td class="col-gula">{{ $item->konsentrasi_glukosa ?? '-' }}</td>
                <td class="col-param">{{ $item->parameter_gula ?? '-' }}</td>
                <td class="col-stat">@if($bsStatus != '-')<span class="badge {{ $bsClass }}">{{ $bsStatus }}</span>@else -@endif</td>
                <td class="col-kol">{{ $item->kolesterol_total ?? '-' }}</td>
                <td class="col-stat">@if($cholStatus != '-')<span class="badge {{ $cholClass }}">{{ $cholStatus }}</span>@else -@endif</td>
                <td class="col-au">{{ $item->asam_urat ?? '-' }}</td>
                <td class="col-stat">@if($uaStatus != '-')<span class="badge {{ $uaClass }}">{{ $uaStatus }}</span>@else -@endif</td>
                <td class="col-catat">{{ $item->catatan_dokter ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    <strong>e-HATi</strong> &mdash; Employee Health Information System &bull; KPPN Pangkalan Bun<br>
    &copy; {{ date('Y') }} &bull; Dicetak: {{ now()->format('d F Y H:i') }} WITA
</div>

</body>
</html>
