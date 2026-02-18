<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai - e-HATi</title>

    {{-- ============================================================
        STYLE UNTUK PDF EXPORT
        ============================================================

        Package: barryvdh/laravel-dompdf
        Install: composer require barryvdh/laravel-dompdf

        Catatan penting untuk PDF:
        - Gunakan inline CSS (tidak bisa load file eksternal)
        - Hindari CSS yang terlalu kompleks
        - Gunakan table untuk layout data
        - Set width dalam px atau %
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
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4E73DF;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #4E73DF;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 12px;
            color: #666;
        }

        .header-date {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fc;
            border-radius: 5px;
        }

        .info-item {
            display: inline-block;
            margin-right: 20px;
        }

        .info-label {
            font-weight: bold;
            color: #4E73DF;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        /* Header Table */
        thead {
            display: table-header-group;
        }

        th {
            background-color: #4E73DF;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #4E73DF;
        }

        /* Body Table */
        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
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
            width: 5%;
            text-align: center;
        }

        .col-nama {
            width: 25%;
        }

        .col-jk {
            width: 12%;
            text-align: center;
        }

        .col-tgl {
            width: 13%;
            text-align: center;
        }

        .col-umur {
            width: 10%;
            text-align: center;
        }

        .col-goldar {
            width: 10%;
            text-align: center;
        }

        .col-riwayat {
            width: 25%;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
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
            <i class="fas fa-users"></i> DATA PEGAWAI
        </div>
        <div class="header-subtitle">
            e-HATi (Employee Health Information)
        </div>
        <div class="header-subtitle">
            KPPN Pangkalan Bun
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
            <span class="info-label">Total Pegawai:</span> {{ $pegawai->count() }} orang
        </span>
        <span class="info-item">
            <span class="info-label">Periode:</span> {{ now()->format('F Y') }}
        </span>
    </div>

    {{-- ============================================================
        TABEL DATA PEGAWAI
        ============================================================ --}}
    <table>
        <thead>
            <tr>
                {{-- Header kolom - urutan harus sesuai dengan data --}}
                <th class="col-no">No.</th>
                <th class="col-nama">Nama</th>
                <th class="col-jk">Jenis Kelamin</th>
                <th class="col-tgl">Tanggal Lahir</th>
                <th class="col-umur">Umur</th>
                <th class="col-goldar">Gol. Darah</th>
                <th class="col-riwayat">Riwayat Penyakit</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop data pegawai --}}
            @foreach ($pegawai as $index => $item)
                <tr>
                    {{-- Nomor urut --}}
                    <td class="col-no">{{ $index + 1 }}</td>

                    {{-- Nama lengkap --}}
                    <td class="col-nama">{{ $item->nama }}</td>

                    {{-- Jenis kelamin --}}
                    <td class="col-jk">{{ $item->jenis_kelamin }}</td>

                    {{-- Tanggal lahir format Indonesia --}}
                    <td class="col-tgl">{{ $item->tanggal_lahir->format('d-m-Y') }}</td>

                    {{-- Umur dengan satuan tahun --}}
                    <td class="col-umur">{{ $item->umur }} Thn</td>

                    {{-- Golongan darah --}}
                    <td class="col-goldar">{{ $item->gol_darah }}</td>

                    {{-- Riwayat penyakit --}}
                    <td class="col-riwayat">{{ $item->riwayat_penyakit ?: '-' }}</td>
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
