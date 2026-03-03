@extends('layouts.app')

@include('partials.modal-video')

@section('content')

    {{-- ============================================================
         Page Title
    ============================================================ --}}
    <div class="dashboard-section-badge mb-4 dashboard-animate">
        <div class="dashboard-badge-icon bg-primary-gradient">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <span class="dashboard-badge-text">{{ $title }}</span>
    </div>

    {{-- ============================================================
         Welcome Card
    ============================================================ --}}
    <div class="dashboard-welcome-card mb-4 dashboard-animate">
        <i class="fas fa-heartbeat dashboard-welcome-icon"></i>
        <h3 class="dashboard-welcome-title">Selamat Datang di e-HATi</h3>
        <p class="dashboard-welcome-text">
            Employee Health Information — Kelola data kesehatan pegawai.
        </p>
    </div>

    {{-- ============================================================
         Statistics Cards — 3 Card: Total Pegawai, Hari Ini, Bulan Ini
    ============================================================ --}}
    <div class="row mb-4">

        {{-- Total Pegawai --}}
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 dashboard-animate" style="animation-delay: 0.05s">
            <div class="dashboard-stat-card dashboard-stat-primary h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-icon-wrapper bg-primary-gradient">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Total Pegawai</span>
                        <span class="dashboard-stat-value">{{ $totalPegawai }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan Hari Ini --}}
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 dashboard-animate" style="animation-delay: 0.1s">
            <div class="dashboard-stat-card dashboard-stat-warning h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-icon-wrapper bg-warning-gradient">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Pemeriksaan Hari Ini</span>
                        <span class="dashboard-stat-value">{{ $pemeriksaanHariIni }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan Bulan Ini --}}
        <div class="col-xl-4 col-md-4 col-sm-6 mb-4 dashboard-animate" style="animation-delay: 0.15s">
            <div class="dashboard-stat-card dashboard-stat-info h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-icon-wrapper bg-info-gradient">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Pemeriksaan Bulan Ini</span>
                        <span class="dashboard-stat-value">{{ $pemeriksaanBulanIni }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================
         Informasi — Bootstrap 4 Carousel
    ============================================================ --}}
    <div class="dashboard-card card shadow-sm mb-4 dashboard-animate" style="animation-delay: 0.2s">
        <div class="card-body p-4">

            <div class="dashboard-section-badge mb-4">
                <div class="dashboard-badge-icon bg-info-gradient">
                    <i class="fas fa-photo-video"></i>
                </div>
                <span class="dashboard-badge-text">Informasi & Edukasi Kesehatan</span>
            </div>

            <div id="dashboardCarousel"
                 class="carousel slide dashboard-carousel"
                 data-ride="carousel">

                {{-- Indicators --}}
                <ol class="carousel-indicators dashboard-carousel-indicators">
                    <li data-target="#dashboardCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#dashboardCarousel" data-slide-to="1"></li>
                    <li data-target="#dashboardCarousel" data-slide-to="2"></li>
                </ol>

                <div class="carousel-inner dashboard-carousel-inner">

                    {{-- Slide 1: Video Edukasi (interval 20 detik) --}}
                    <div class="carousel-item active" data-interval="20000">
                        <div class="dashboard-carousel-slide dashboard-carousel-slide-video">

                            {{-- Wrap video agar klik tidak konflik dengan kontrol carousel --}}
                            <div class="dashboard-carousel-video-wrap">
                                <video
                                    id="dashboardVideo"
                                    class="dashboard-carousel-video"
                                    muted
                                    loop
                                    playsinline
                                    poster="{{ asset('sbadmin2/img/streaching-pak-margo.mp4_snapshot_00.01.740.jpg') }}">
                                    <source src="{{ asset('sbadmin2/vid/streaching-pak-margo.mp4') }}" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>

                                {{-- Hint hover: klik pause, double-klik float --}}
                                <div class="dashboard-video-hint">
                                    <div class="dashboard-video-hint-icon">
                                        <i id="dashboardVideoHintIcon" class="fas fa-pause"></i>
                                    </div>
                                    <span class="dashboard-video-hint-text">
                                        1× Pause/Play, 2× Fullscreen
                                    </span>
                                </div>
                            </div>

                            <div class="dashboard-carousel-overlay"></div>

                            {{-- Badge PiP aktif --}}
                            <div id="dashboardPipBadge" class="dashboard-pip-badge">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Video Floating</span>
                                <button id="dashboardPipBtn"
                                    style="background:none;border:none;color:#fff;font-size:0.72rem;padding:0 0 0 6px;cursor:pointer;">
                                    Tutup ×
                                </button>
                            </div>

                        </div>
                        <div class="carousel-caption dashboard-carousel-caption">
                            <h5><i class="fas fa-play-circle mr-2"></i>Video Edukasi Kesehatan</h5>
                            <p>Peregangan (Stretching) untuk Pegawai — Tetap Aktif & Sehat di Tempat Kerja</p>
                        </div>
                    </div>

                    {{-- Slide 2: Logo (interval 5 detik) --}}
                    <div class="carousel-item" data-interval="5000">
                        <div class="dashboard-carousel-slide dashboard-carousel-slide-logo">
                            <img
                                src="{{ asset('sbadmin2/img/logo_e-hati_v3.svg') }}"
                                alt="e-HATi Logo"
                                class="dashboard-carousel-logo">
                        </div>
                        <div class="carousel-caption dashboard-carousel-caption">
                            <h5>e-HATi</h5>
                            <p>Employee Health Information — KPPN Pangkalan Bun</p>
                        </div>
                    </div>

                    {{-- Slide 3: Logo (interval 5 detik) --}}
                    <div class="carousel-item" data-interval="5000">
                        <div class="dashboard-carousel-slide dashboard-carousel-slide-logo-alt">
                            <img
                                src="{{ asset('sbadmin2/img/logo_e-hati_v3.svg') }}"
                                alt="e-HATi Logo"
                                class="dashboard-carousel-logo">
                        </div>
                        <div class="carousel-caption dashboard-carousel-caption">
                            <h5>Kesehatan adalah Investasi</h5>
                            <p>Jaga kesehatan Anda dengan pemeriksaan rutin bersama e-HATi</p>
                        </div>
                    </div>

                </div>

                {{-- Controls --}}
                <a class="carousel-control-prev dashboard-carousel-control" href="#dashboardCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next dashboard-carousel-control" href="#dashboardCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>

        </div>
    </div>

@endsection
