@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-tachometer-alt mr-2"></i>
        {{ $title }}
    </h1>

    {{-- ============================================================
        DASHBOARD STATISTIC CARDS

        Cara menambah card baru:
        1. Copy salah satu card di bawah
        2. Ganti class warna (border-left-primary, border-left-success, dll)
        3. Ganti icon (fas fa-user, fas fa-clipboard-list, dll)
        4. Ganti label dan value dari variable controller

        Warna yang tersedia:
        - border-left-primary (Biru)
        - border-left-success (Hijau)
        - border-left-info (Biru Muda)
        - border-left-warning (Kuning/Oranye)
        - border-left-danger (Merah)
        - border-left-secondary (Abu-abu)
        - border-left-dark (Hitam)
        ============================================================ --}}

    <div class="row">

        {{-- ============================================================
            CARD 1: TOTAL PEGAWAI
            Menampilkan jumlah seluruh pegawai dalam database
            ============================================================ --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pegawai
                            </div>
                            {{-- Nilai diambil dari DashboardController --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPegawai }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            CARD 2: TOTAL PEMERIKSAAN
            Menampilkan jumlah seluruh riwayat pemeriksaan
            ============================================================ --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pemeriksaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPemeriksaan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            CARD 3: PEMERIKSAAN BULAN INI
            Menampilkan jumlah pemeriksaan di bulan berjalan
            ============================================================ --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pemeriksaan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pemeriksaanBulanIni }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            CARD 4: PEMERIKSAAN HARI INI
            Menampilkan jumlah pemeriksaan di hari ini
            ============================================================ --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pemeriksaan Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pemeriksaanHariIni }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            CARD 5: PEGAWAI YANG SUDAH DIPERIKSA
            Menampilkan jumlah pegawai unik yang sudah pernah diperiksa
            ============================================================ --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pegawai Sudah Diperiksa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pegawaiDiperiksa }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            TAMBAH CARD BARU DI SINI

            Template card baru:

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-[warna] shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-[warna] text-uppercase mb-1">
                                    [LABEL CARD]
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $variable }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-[icon] fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ============================================================ --}}

    </div>

    {{-- ============================================================
        QUICK LINKS / MENU CEPAT (Opsional)
        Bisa ditambahkan untuk navigasi cepat
        ============================================================ --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-link mr-2"></i>Menu Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('pegawai') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users mr-2"></i>Kelola Data Pegawai
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('pemeriksaan') }}" class="btn btn-success btn-block">
                                <i class="fas fa-stethoscope mr-2"></i>Input Pemeriksaan
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('pemeriksaanRiwayat') }}" class="btn btn-info btn-block">
                                <i class="fas fa-history mr-2"></i>Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
