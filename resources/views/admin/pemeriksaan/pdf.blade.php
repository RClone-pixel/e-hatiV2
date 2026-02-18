<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemeriksaan - e-HATi</title>

    {{-- ============================================================
        STYLE UNTUK PDF EXPORT - RIWAYAT PEMERIKSAAN
        ============================================================

        Package: barryvdh/laravel-dompdf
        Install: composer require barryvdh/laravel-dompdf

        Catatan penting untuk PDF:
        - Gunakan inline CSS (tidak bisa load file eksternal)
        - Hindari CSS yang terlalu kompleks
        - Gunakan table untuk layout data
        - Set width dalam px atau %
        - Font size lebih kecil karena data banyak
    ============================================================ --}}

    <style>
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #333;
            padding: 15px;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #36B9CC;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #36B9CC;
            margin-bottom: 3px;
        }

        .header-subtitle {
            font-size: 11px;
            color: #666;
        }

        .header-date {
            font-size: 9px;
            color: #999;
            margin-top: 3px;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f8f9fc;
            border-radius: 4px;
        }

        .info-item {
            display: inline-block;
            margin-right: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #36B9CC;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
        }

        /* Header Table */
        thead {
            display: table-header-group;
        }

        th {
            background-color: #36B9CC;
            color: white;
            font-weight: bold;
            padding: 6px 4px;
            text-align: center;
            font-size: 8px;
            text-transform: uppercase;
            border: 1px solid #36B9CC;
            vertical-align: middle;
        }

        /* Body Table */
        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        /* Alternating row colors */
        tbody tr:nth-child(even) {
            background-color: #f8f9fc;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Column specific styles */
        .col-no {
            width: 3%;
            text-align: center;
        }

        .col-nama {
            width: 15%;
        }

        .col-tgl {
            width: 8%;
            text-align: center;
        }

        .col-puasa {
            width: 7%;
            text-align: center;
        }

        .col-bmi {
            width: 4%;
            text-align: center;
        }

        .col-td {
            width: 6%;
            text-align: center;
        }

        .col-nadi {
            width: 5%;
            text-align: center;
        }

        .col-lab {
            width: 6%;
            text-align: center;
        }

        .col-catatan {
            width: 18%;
            font-size: 7px;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-normal {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #999;
        }

        /* Page break untuk tabel panjang */
        tbody tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    {{-- ============================================================
        HEADER PDF
        ============================================================ --}}
    <div class="header">
        <div class="header-title">
            RIWAYAT PEMERIKSAAN KESEHATAN
        </div>
        <div class="header-subtitle">
            e-HATi (Employee Health Information) - KPPN Pangkalan Bun
        </div>
        <div class="header-date">
            Dicetak pada: {{ now()->format('d F Y H:i') }}
        </div>
    </div>

    {{-- ============================================================
        INFO RINGKASAN
        ============================================================ --}}
    <div class="info-section">
        <span class="info-item">
            <span class="info-label">Total Data:</span> {{ $pemeriksaan->count() }} pemeriksaan
        </span>
        <span class="info-item">
            <span class="info-label">Periode:</span> {{ now()->format('F Y') }}
        </span>
    </div>

    {{-- ============================================================
        TABEL DATA PEMERIKSAAN
        ============================================================ --}}
    <table>
        <thead>
            <tr>
                {{-- Header kolom - urutan harus sesuai dengan data --}}
                <th class="col-no">No</th>
                <th class="col-nama">Nama Pegawai</th>
                <th class="col-tgl">Tanggal</th>
                <th class="col-puasa">Puasa</th>
                <th class="col-bmi">BMI</th>
                <th class="col-td">TD</th>
                <th class="col-nadi">Nadi</th>
                <th class="col-lab">Gula</th>
                <th class="col-lab">Kol</th>
                <th class="col-lab">AU</th>
                <th class="col-catatan">Catatan</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop data pemeriksaan --}}
            @foreach ($pemeriksaan as $index => $item)
                @php
                    // Hitung BMI
                    $bmi = null;
                    if ($item->tinggi_badan > 0 && $item->berat_badan > 0) {
                        $bmi = round($item->berat_badan / pow($item->tinggi_badan / 100, 2), 1);
                    }
                @endphp
                <tr>
                    {{-- Nomor urut --}}
                    <td class="col-no">{{ $index + 1 }}</td>

                    {{-- Nama pegawai --}}
                    <td class="col-nama">{{ $item->pegawai->nama ?? '-' }}</td>

                    {{-- Tanggal pemeriksaan --}}
                    <td class="col-tgl">{{ $item->tanggal_pemeriksaan->format('d-m-Y') }}</td>

                    {{-- Status puasa --}}
                    <td class="col-puasa">
                        @if ($item->puasa)
                            <span class="badge badge-normal">Ya</span>
                        @else
                            <span class="badge badge-warning">Tidak</span>
                        @endif
                    </td>

                    {{-- BMI dengan badge status --}}
                    <td class="col-bmi">
                        @if ($bmi)
                            {{ $bmi }}
                            @if ($bmi < 18.5)
                                <span class="badge badge-info">K</span>
                            @elseif($bmi <= 24.9)
                                <span class="badge badge-normal">N</span>
                            @elseif($bmi <= 29.9)
                                <span class="badge badge-warning">OW</span>
                            @else
                                <span class="badge badge-danger">O</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    {{-- Tekanan darah --}}
                    <td class="col-td">
                        @if ($item->sistolik && $item->diastolik)
                            {{ $item->sistolik }}/{{ $item->diastolik }}
                        @else
                            -
                        @endif
                    </td>

                    {{-- Nadi --}}
                    <td class="col-nadi">{{ $item->nadi ?: '-' }}</td>

                    {{-- Gula darah --}}
                    <td class="col-lab">
                        @if ($item->nilai_glukometer)
                            {{ $item->nilai_glukometer }}
                            <br><small>{{ $item->parameter_gula }}</small>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Kolesterol --}}
                    <td class="col-lab">
                        @if ($item->kolesterol_total)
                            {{ $item->kolesterol_total }}
                            @if ($item->kolesterol_total < 200)
                                <span class="badge badge-normal">N</span>
                            @elseif($item->kolesterol_total <= 239)
                                <span class="badge badge-warning">B</span>
                            @else
                                <span class="badge badge-danger">T</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    {{-- Asam urat --}}
                    <td class="col-lab">{{ $item->asam_urat ?: '-' }}</td>

                    {{-- Catatan dokter --}}
                    <td class="col-catatan">
                        {{ $item->catatan_dokter ?: '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ============================================================
        FOOTER PDF
        ============================================================ --}}
    <div class="footer">
        <p>e-HATi - Employee Health Information System</p>
        <p>KPPN Pangkalan Bun &copy; {{ date('Y') }}</p>
    </div>

</body>

</html>
