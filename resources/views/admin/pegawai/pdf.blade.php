<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pegawai - e-HATi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(public_path('css/pegawai-pdf.css')) !!}
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-title">DATA PEGAWAI</div>
    <div class="header-sub">e-HATi &mdash; Employee Health Information</div>
    <div class="header-org">KPPN Pangkalan Bun</div>
    <div class="header-date">Dicetak pada: {{ now()->format('d F Y, H:i') }} WITA</div>
</div>

{{-- INFO BAR --}}
@php
    $totalAll       = $pegawai->count();
    $totalLaki      = $pegawai->where('jenis_kelamin', 'Laki-laki')->count();
    $totalPerempuan = $pegawai->where('jenis_kelamin', 'Perempuan')->count();
@endphp

<table class="info-bar">
    <tr>
        <td class="info-total">
            Total &nbsp;<strong>{{ $totalAll }} Pegawai</strong>
            &nbsp;&nbsp;
            Laki-laki <span>{{ $totalLaki }}</span>
            &nbsp;&bull;&nbsp;
            Perempuan <span>{{ $totalPerempuan }}</span>
        </td>
    </tr>
</table>

{{-- DATA TABLE --}}
<table class="pdf-table">
    <thead>
        <tr>
            <th class="col-no">No.</th>
            <th class="col-nama th-nama">Nama</th>
            <th class="col-jk">Jenis Kelamin</th>
            <th class="col-tgl">Tgl. Lahir</th>
            <th class="col-umur">Umur</th>
            <th class="col-goldar">Gol. Darah</th>
            <th class="col-riwayat">Riwayat Penyakit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pegawai as $index => $p)
            <tr>
                <td class="td-no">{{ $index + 1 }}</td>
                <td class="td-nama">{{ $p->nama }}</td>
                <td class="td-secondary">
                    @if(str_contains(strtolower($p->jenis_kelamin), 'laki'))
                        <span class="badge badge-laki">Laki-laki</span>
                    @else
                        <span class="badge badge-perempuan">Perempuan</span>
                    @endif
                </td>
                <td class="td-secondary">
                    {{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d-m-Y') : '-' }}
                </td>
                <td class="td-secondary">
                    <span class="td-umur-num">{{ $p->umur }}</span>
                    <span class="td-umur-unit"> th</span>
                </td>
                <td class="td-secondary">
                    <span class="badge badge-goldar">{{ $p->gol_darah }}</span>
                </td>
                <td class="td-riwayat">{{ $p->riwayat_penyakit ?: '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-main"><strong>e-HATi</strong> &mdash; Employee Health Information</div>
    <div class="footer-copy">&copy; {{ date('Y') }} &bull; KPPN Pangkalan Bun </div>
</div>

</body>
</html>
