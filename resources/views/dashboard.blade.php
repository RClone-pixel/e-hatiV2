@extends('layouts.app')

@section('content')
    {{-- Page Title --}}
    <div class="dashboard-section-badge mb-4 dashboard-animate">
        <div class="dashboard-badge-icon bg-primary-gradient">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <span class="dashboard-badge-text">{{ $title }}</span>
    </div>

    {{-- Welcome Card --}}
    <div class="dashboard-welcome-card mb-4 dashboard-animate">
        <i class="fas fa-heartbeat dashboard-welcome-icon"></i>
        <h3 class="dashboard-welcome-title">Selamat Datang di e-HATi</h3>
        <p class="dashboard-welcome-text">
            Employee Health Information - Kelola data kesehatan pegawai.
        </p>
    </div>

    {{-- Statistics Cards Row --}}
    <div class="row mb-4">
        {{-- Total Pegawai --}}
        <div class="col-xl-3 col-md-6 mb-4 dashboard-animate" style="animation-delay: 0.05s">
            <div class="dashboard-stat-card dashboard-stat-primary h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Total Pegawai</span>
                        <span class="dashboard-stat-value">{{ $totalPegawai }}</span>
                    </div>
                    <div class="dashboard-stat-icon-wrapper bg-primary-gradient">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pemeriksaan --}}
        <div class="col-xl-3 col-md-6 mb-4 dashboard-animate" style="animation-delay: 0.1s">
            <div class="dashboard-stat-card dashboard-stat-success h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Total Pemeriksaan</span>
                        <span class="dashboard-stat-value">{{ $totalPemeriksaan }}</span>
                    </div>
                    <div class="dashboard-stat-icon-wrapper bg-success-gradient">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan Bulan Ini --}}
        <div class="col-xl-3 col-md-6 mb-4 dashboard-animate" style="animation-delay: 0.15s">
            <div class="dashboard-stat-card dashboard-stat-info h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Bulan Ini</span>
                        <span class="dashboard-stat-value">{{ $pemeriksaanBulanIni }}</span>
                    </div>
                    <div class="dashboard-stat-icon-wrapper bg-info-gradient">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan Hari Ini --}}
        <div class="col-xl-3 col-md-6 mb-4 dashboard-animate" style="animation-delay: 0.2s">
            <div class="dashboard-stat-card dashboard-stat-warning h-100">
                <div class="dashboard-stat-body">
                    <div class="dashboard-stat-content">
                        <span class="dashboard-stat-label">Hari Ini</span>
                        <span class="dashboard-stat-value">{{ $pemeriksaanHariIni }}</span>
                    </div>
                    <div class="dashboard-stat-icon-wrapper bg-warning-gradient">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Cards Grid --}}
    <div class="dashboard-card card shadow-sm mb-4 dashboard-animate" style="animation-delay: 0.25s">
        <div class="card-body p-4">
            <div class="dashboard-section-badge mb-4">
                <div class="dashboard-badge-icon bg-info-gradient">
                    <i class="fas fa-info-circle"></i>
                </div>
                <span class="dashboard-badge-text">Informasi Sistem</span>
            </div>
        </div>
    </div>
@endsection
