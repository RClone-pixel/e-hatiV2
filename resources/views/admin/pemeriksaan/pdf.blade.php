<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemeriksaan - e-HATi</title>
    <style>
        {!! file_get_contents(public_path('css/pemeriksaan-pdf.css')) !!}
    </style>
</head>
<body>

{{-- ============================================================
     HEADER
     ============================================================ --}}
<div class="header">
    <div class="header-title">RIWAYAT PEMERIKSAAN KESEHATAN</div>
    <div class="header-sub">e-HATi &mdash; Employee Health Information System</div>
    <div class="header-org">KPPN Pangkalan Bun</div>
    <div class="header-date">Dicetak pada: {{ now()->format('d F Y, H:i') }} WITA</div>
</div>

{{-- ============================================================
     INFO BAR
     ============================================================ --}}
@php
    $totalData = $pemeriksaan->count();
@endphp

<table class="info-bar">
    <tr>
        <td class="info-total">
            Total &nbsp;<strong>{{ $totalData }} Data</strong>
            &nbsp;&nbsp;Pemeriksaan
            <span>&bull; {{ now()->format('F Y') }}</span>
        </td>
        <td class="info-right">
            Periode: {{ now()->format('F Y') }}
        </td>
    </tr>
</table>

{{-- ============================================================
     DATA TABLE
     ============================================================ --}}
<table class="pdf-table">
    <thead>
        <tr>
            <th class="col-no">No.</th>
            <th class="col-nama th-nama">Nama Pegawai</th>
            <th class="col-tgl">Tgl. Periksa</th>
            <th class="col-num">TB</th>
            <th class="col-num">BB</th>
            <th class="col-bmi">BMI</th>
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
            <th class="col-catat">Catatan Dokter</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pemeriksaan as $index => $item)
            @php
                /* ── BMI ── */
                $bmi = null; $bmiStatus = '-'; $bmiClass = '';
                if ($item->tinggi_badan > 0 && $item->berat_badan > 0) {
                    $bmi = round($item->berat_badan / pow($item->tinggi_badan / 100, 2), 1);
                    if      ($bmi < 16)    { $bmiStatus = 'Kkrg III';   $bmiClass = 'badge-danger'; }
                    elseif  ($bmi < 17)    { $bmiStatus = 'Kkrg II';    $bmiClass = 'badge-warning'; }
                    elseif  ($bmi < 18.5)  { $bmiStatus = 'Kkrg I';     $bmiClass = 'badge-info'; }
                    elseif  ($bmi <= 24.9) { $bmiStatus = 'Normal';     $bmiClass = 'badge-normal'; }
                    elseif  ($bmi <= 29.9) { $bmiStatus = 'Overweight'; $bmiClass = 'badge-warning'; }
                    elseif  ($bmi <= 34.9) { $bmiStatus = 'Obst I';     $bmiClass = 'badge-danger'; }
                    elseif  ($bmi <= 39.9) { $bmiStatus = 'Obst II';    $bmiClass = 'badge-danger'; }
                    else                   { $bmiStatus = 'Obst III';   $bmiClass = 'badge-danger'; }
                }

                /* ── Tekanan Darah — sync bloodpressure.js classifyBP()
                   Zona atas (tertinggi duluan) → zona bawah → normal ── */
                $bpStatus = '-'; $bpClass = '';
                if ($item->sistolik && $item->diastolik) {
                    $s = (int) $item->sistolik; $d = (int) $item->diastolik;
                    if      ($s > 180 || $d > 120)  { $bpStatus = 'Krisis HT';  $bpClass = 'badge-danger'; }
                    elseif  ($s >= 160 || $d >= 100) { $bpStatus = 'HT Drj 2';  $bpClass = 'badge-danger'; }
                    elseif  ($s >= 140 || $d >= 90)  { $bpStatus = 'HT Drj 1';  $bpClass = 'badge-danger'; }
                    elseif  ($s >= 120 || $d >= 80)  { $bpStatus = 'Pre-HT';    $bpClass = 'badge-warning'; }
                    elseif  ($s < 70  || $d < 40)    { $bpStatus = 'Krisis Hipo';$bpClass = 'badge-danger'; }
                    elseif  ($s < 90  || $d < 60)    { $bpStatus = 'Hipotensi'; $bpClass = 'badge-info'; }
                    else                              { $bpStatus = 'Normal';    $bpClass = 'badge-normal'; }
                }

                /* ── Gula Darah — sync bloodsugar.js classifyBS()
                   Zona bawah & atas sama semua parameter.
                   Zona tengah (70–249) beda per GDP/GD2PP/GDS ── */
                $bsStatus = '-'; $bsClass = '';
                if ($item->konsentrasi_glukosa && $item->parameter_gula) {
                    $ng = (float) $item->konsentrasi_glukosa;
                    $pg = $item->parameter_gula;
                    // Zona bawah
                    if      ($ng < 40)  { $bsStatus = 'Hipo Kritis';  $bsClass = 'badge-danger'; }
                    elseif  ($ng < 54)  { $bsStatus = 'Hipo Lvl 2';   $bsClass = 'badge-danger'; }
                    elseif  ($ng < 70)  { $bsStatus = 'Hipo Lvl 1';   $bsClass = 'badge-info'; }
                    // Zona atas
                    elseif  ($ng >= 600){ $bsStatus = 'Krisis Hiper';  $bsClass = 'badge-danger'; }
                    elseif  ($ng >= 250){ $bsStatus = 'Hiper Berat';   $bsClass = 'badge-danger'; }
                    // Zona tengah per parameter (70–249)
                    elseif  ($pg === 'GDP') {
                        if      ($ng <= 109) { $bsStatus = 'Normal';      $bsClass = 'badge-normal'; }
                        elseif  ($ng <= 125) { $bsStatus = 'Prediabetes'; $bsClass = 'badge-warning'; }
                        else                 { $bsStatus = 'Diabetes';    $bsClass = 'badge-danger'; }
                    }
                    elseif  ($pg === 'GD2PP') {
                        if      ($ng <= 139) { $bsStatus = 'Normal';      $bsClass = 'badge-normal'; }
                        elseif  ($ng <= 199) { $bsStatus = 'Prediabetes'; $bsClass = 'badge-warning'; }
                        else                 { $bsStatus = 'Diabetes';    $bsClass = 'badge-danger'; }
                    }
                    else { /* GDS default */
                        if      ($ng <= 179) { $bsStatus = 'Normal';   $bsClass = 'badge-normal'; }
                        elseif  ($ng <= 199) { $bsStatus = 'Waspada';  $bsClass = 'badge-warning'; }
                        else                 { $bsStatus = 'Diabetes'; $bsClass = 'badge-danger'; }
                    }
                }

                /* ── Kolesterol — sync cholesterol.js classifyChol()
                   < 120 Rendah | 120–199 Normal | 200–239 Ambang Batas
                   240–299 Tinggi | >= 300 Sangat Tinggi
                   Pakai < 240 (bukan <= 239) sesuai komentar JS ── */
                $cholStatus = '-'; $cholClass = '';
                if ($item->kolesterol_total) {
                    $kol = (float) $item->kolesterol_total;
                    if      ($kol < 120) { $cholStatus = 'Rendah';       $cholClass = 'badge-warning'; }
                    elseif  ($kol < 200) { $cholStatus = 'Normal';       $cholClass = 'badge-normal'; }
                    elseif  ($kol < 240) { $cholStatus = 'Ambang Batas'; $cholClass = 'badge-warning'; }
                    elseif  ($kol < 300) { $cholStatus = 'Tinggi';       $cholClass = 'badge-danger'; }
                    else                 { $cholStatus = 'Sgt Tinggi';   $cholClass = 'badge-danger'; }
                }

                /* ── Asam Urat — sync uricacid.js classifyUA()
                   RUJUKAN: laki { low: 3.5, high: 7.2 } | perempuan { low: 2.6, high: 6.0 }
                   Tidak ada pembeda umur di uricacid.js — hanya gender ── */
                $uaStatus = '-'; $uaClass = '';
                if ($item->asam_urat && $item->pegawai) {
                    $jk   = strtolower($item->pegawai->jenis_kelamin ?? '');
                    $laki = str_contains($jk, 'laki');
                    $low  = $laki ? 3.5 : 2.6;
                    $high = $laki ? 7.2 : 6.0;
                    $ua   = (float) $item->asam_urat;
                    if      ($ua < $low)          { $uaStatus = 'Rendah';       $uaClass = 'badge-info'; }
                    elseif  ($ua <= $high)         { $uaStatus = 'Normal';       $uaClass = 'badge-normal'; }
                    elseif  ($ua <= $high + 2.0)   { $uaStatus = 'Tinggi';       $uaClass = 'badge-warning'; }
                    else                           { $uaStatus = 'Sgt Tinggi';   $uaClass = 'badge-danger'; }
                }
            @endphp
            <tr>
                <td class="td-no">{{ $index + 1 }}</td>
                <td class="td-nama">{{ $item->pegawai->nama ?? '-' }}</td>
                <td class="td-secondary">{{ $item->tanggal_pemeriksaan ? $item->tanggal_pemeriksaan->format('d-m-Y') : '-' }}</td>
                <td class="td-num">{{ $item->tinggi_badan ?? '-' }}</td>
                <td class="td-num">{{ $item->berat_badan ?? '-' }}</td>
                <td class="td-num">{{ $bmi ?? '-' }}</td>
                <td class="td-secondary">
                    @if($bmiStatus !== '-')
                        <span class="badge {{ $bmiClass }}">{{ $bmiStatus }}</span>
                    @else -@endif
                </td>
                <td class="td-td">
                    {{ ($item->sistolik && $item->diastolik) ? $item->sistolik.'/'.$item->diastolik : '-' }}
                </td>
                <td class="td-secondary">
                    @if($bpStatus !== '-')
                        <span class="badge {{ $bpClass }}">{{ $bpStatus }}</span>
                    @else -@endif
                </td>
                <td class="td-num">{{ $item->nadi ?? '-' }}</td>
                <td class="td-num">{{ $item->konsentrasi_glukosa ?? '-' }}</td>
                <td class="td-secondary">{{ $item->parameter_gula ?? '-' }}</td>
                <td class="td-secondary">
                    @if($bsStatus !== '-')
                        <span class="badge {{ $bsClass }}">{{ $bsStatus }}</span>
                    @else -@endif
                </td>
                <td class="td-num">{{ $item->kolesterol_total ?? '-' }}</td>
                <td class="td-secondary">
                    @if($cholStatus !== '-')
                        <span class="badge {{ $cholClass }}">{{ $cholStatus }}</span>
                    @else -@endif
                </td>
                <td class="td-num">{{ $item->asam_urat ?? '-' }}</td>
                <td class="td-secondary">
                    @if($uaStatus !== '-')
                        <span class="badge {{ $uaClass }}">{{ $uaStatus }}</span>
                    @else -@endif
                </td>
                <td class="td-catat">{{ $item->catatan_dokter ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ============================================================
     FOOTER
     ============================================================ --}}
<div class="footer">
    <div class="footer-main"><strong>e-HATi</strong> &mdash; Employee Health Information System</div>
    <div class="footer-copy">&copy; {{ date('Y') }} &bull; KPPN Pangkalan Bun &bull; Dicetak: {{ now()->format('d F Y, H:i') }} WITA</div>
</div>

</body>
</html>
