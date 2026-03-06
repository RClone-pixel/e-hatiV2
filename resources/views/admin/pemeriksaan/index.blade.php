@extends('layouts.app')

<link rel="icon" href="{{ asset('sbadmin2/img/icon_e-hati_v3.svg') }}" type="image/svg+xml">

@section('content')

    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-check mr-2"></i>
        {{ $title }}
    </h1>

    {{-- ===== Premium Tab Navigation ===== --}}
    <div class="mb-4">
        <div class="ehati-tabs">
            <ul class="nav nav-pills mb-0" id="mainTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ (!isset($activeTab) || $activeTab !== 'riwayat') ? 'active' : '' }}"
                        id="pemeriksaan-tab"
                        href="{{ route('pemeriksaan') }}"
                        role="tab">
                        <i class="fas fa-stethoscope mr-1"></i><span>Pemeriksaan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isset($activeTab) && $activeTab === 'riwayat' ? 'active' : '' }}"
                        id="riwayat-tab"
                        href="{{ route('pemeriksaanRiwayat') }}"
                        role="tab">
                        <i class="fas fa-history mr-1"></i><span>Riwayat</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- ===== Tab Content ===== --}}
    <div class="tab-content" id="mainTabContent">

        {{-- ==================== TAB: PEMERIKSAAN ==================== --}}
        <div id="pemeriksaanContent" style="{{ isset($activeTab) && $activeTab === 'riwayat' ? 'display:none;' : '' }}">

            <form action="{{ route('pemeriksaanStore') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- === Profil Pegawai Card === --}}
                <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate">
                    <div class="card-body p-4">
                        <div class="ehati-section-badge mb-3">
                            <div class="ehati-badge-icon bg-teal"><i class="fas fa-user-circle"></i></div>
                            <span class="ehati-badge-text">Profil Pegawai</span>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 d-flex justify-content-center align-items-start">
                                <div class="ehati-photo-frame">
                                    <img id="foto" src="{{ asset('sbadmin2/img/user_kppn.png') }}" alt="Foto Pegawai">
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-8 mt-3 mt-lg-0">
                                <div class="form-group mb-3">
                                    <label class="ehati-label">
                                        <i class="fas fa-id-badge mr-1 text-info"></i> Nama Pegawai
                                    </label>
                                    <select class="custom-select ehati-select shadow-sm" name="pegawai_id" id="pegawai_id">
                                        <option selected disabled class="text-muted">-- Pilih Pegawai --</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <label class="ehati-label-sm">Tanggal Lahir</label>
                                        <input type="text" class="form-control ehati-input-readonly" name="tanggal_lahir"
                                            id="tanggal_lahir" value="-" readonly>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <label class="ehati-label-sm">Umur</label>
                                        <input type="text" class="form-control ehati-input-readonly" name="umur"
                                            id="umur" value="-" readonly>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <label class="ehati-label-sm">Jenis Kelamin</label>
                                        <input type="text" class="form-control ehati-input-readonly" name="jenis_kelamin"
                                            id="jenis_kelamin" value="-" readonly>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <label class="ehati-label-sm">Gol. Darah</label>
                                        <input type="text" class="form-control ehati-input-readonly"
                                            name="golongan_darah" id="golongan_darah" value="-" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="ehati-label">
                                        <i class="fas fa-notes-medical mr-1 text-warning"></i> Riwayat Penyakit
                                    </label>
                                    <textarea class="form-control ehati-input-readonly" name="riwayat_penyakit" id="riwayat_penyakit" readonly
                                        rows="2">-</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== CREATIVE CONTROL BAR ===== --}}
                <div class="ehati-control-bar mb-4 ehati-animate" style="animation-delay:.02s">
                    <div class="ehati-control-bar-inner">
                        {{-- Progress Section --}}
                        <div class="ehati-progress-section">
                            <div class="ehati-progress-ring">
                                <svg viewBox="0 0 36 36">
                                    <path class="ehati-progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path id="progress-circle" class="ehati-progress-fill" stroke-dasharray="0, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="ehati-progress-text">
                                    <span id="progress-percent">0%</span>
                                </div>
                            </div>
                            <div class="ehati-progress-info">
                                <span class="ehati-progress-label">Progress Pemeriksaan</span>
                                <span class="ehati-progress-count"><span id="filled-count">0</span>/7 Form</span>
                            </div>
                        </div>

                        {{-- Control Buttons --}}
                        <div class="ehati-control-buttons">
                            <button type="button" class="ehati-btn-control ehati-btn-expand" id="expandAllBtn">
                                <span class="ehati-btn-icon">
                                    <i class="fas fa-expand-alt"></i>
                                </span>
                                <span class="ehati-btn-text">
                                    <span class="ehati-btn-main">Expand All</span>
                                    <span class="ehati-btn-sub">Buka Semua</span>
                                </span>
                            </button>
                            <button type="button" class="ehati-btn-control ehati-btn-collapse" id="collapseAllBtn">
                                <span class="ehati-btn-icon">
                                    <i class="fas fa-compress-alt"></i>
                                </span>
                                <span class="ehati-btn-text">
                                    <span class="ehati-btn-main">Collapse All</span>
                                    <span class="ehati-btn-sub">Tutup Semua</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Section Indicators --}}
                    <div class="ehati-section-indicators">
                        <div class="ehati-indicator" data-section="0" title="Informasi Pemeriksaan">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="ehati-indicator" data-section="1" title="BMI & Broca">
                            <i class="fas fa-weight"></i>
                        </div>
                        <div class="ehati-indicator" data-section="2" title="Tekanan Darah">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="ehati-indicator" data-section="3" title="Gula Darah">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="ehati-indicator" data-section="4" title="Kolesterol">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="ehati-indicator" data-section="5" title="Asam Urat">
                            <i class="fas fa-vial"></i>
                        </div>
                        <div class="ehati-indicator" data-section="6" title="Catatan Dokter">
                            <i class="fas fa-clipboard"></i>
                        </div>
                    </div>
                </div>

                {{-- Examination Sections (Disabled until employee selected) --}}
                <fieldset id="pemeriksaan-section" disabled>

                    {{-- === ACCORDION CONTAINER === --}}
                    <div class="ehati-accordion" id="pemeriksaanAccordion">

                        {{-- --- Section 1: Informasi Pemeriksaan --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.05s" data-section="info">
                            <div class="ehati-accordion-header" data-target="#collapseInfo" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-teal"><i class="fas fa-calendar-check"></i></div>
                                    <span class="ehati-badge-text">Informasi Pemeriksaan</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <span class="ehati-accordion-status" id="status-info">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseInfo" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-md-0">
                                                <label class="ehati-label">
                                                    Tanggal Pemeriksaan
                                                </label>
                                                <input type="date" class="form-control ehati-input" id="tanggal_pemeriksaan"
                                                    name="tanggal_pemeriksaan" min="{{ now()->subDays(31)->format('Y-m-d') }}"
                                                    max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label class="ehati-label">Status Puasa</label>
                                                <select class="custom-select ehati-select" name="puasa" id="puasa">
                                                    <option value="0">Tidak Puasa</option>
                                                    <option value="1">Sedang Puasa</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 2: BMI --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.1s" data-section="bmi">
                            <div class="ehati-accordion-header" data-target="#collapseBmi" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-blue"><i class="fas fa-weight"></i></div>
                                    <span class="ehati-badge-text">Body Mass Index & Broca</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <button type="button" class="btn btn-sm btn-outline-info ehati-table-btn" data-toggle="modal"
                                        data-target="#bmiTableModal">
                                        <i class="fas fa-table mr-1"></i>Tabel BMI
                                    </button>
                                    <span class="ehati-accordion-status" id="status-bmi">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseBmi" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row align-items-stretch">
                                        <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                            <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                                <div class="form-group mb-3">
                                                    <label class="ehati-label">Tinggi Badan</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="tinggi_badan"
                                                            id="tinggi_badan" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">cm</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="ehati-label">Berat Badan</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="berat_badan"
                                                            id="berat_badan" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-12">
                                            <div id="bmi-card" class="ehati-analysis-card">
                                                <div class="ehati-analysis-inner">
                                                    <h6 class="ehati-analysis-title">Analisis <span>Body Mass Index & Indeks
                                                            Broca</span></h6>
                                                    <div id="gender-icon" class="ehati-analysis-icon">
                                                        <i class="fas fa-venus-mars"></i>
                                                    </div>
                                                    <h2 id="bmi-status" class="ehati-analysis-status">— —</h2>
                                                    <span class="ehati-analysis-sub">Klasifikasi Massa Tubuh</span>
                                                    <div class="ehati-stats-row">
                                                        <div class="ehati-stat-item">
                                                            <span id="bmi-score" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">BMI</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="bmi-ideal" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">Ideal (kg)</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="bmi-selisih" class="ehati-stat-val">--.-</span>
                                                            <small id="bmi-selisih-label" class="ehati-stat-lbl">Selisih
                                                                (kg)</small>
                                                        </div>
                                                    </div>
                                                    <div class="ehati-advice">
                                                        <small id="bmi-advice">—</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 3: Blood Pressure --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.15s" data-section="bp">
                            <div class="ehati-accordion-header" data-target="#collapseBp" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-red"><i class="fas fa-heartbeat"></i></div>
                                    <span class="ehati-badge-text">Tekanan Darah & Denyut Nadi</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <button type="button" class="btn btn-sm btn-outline-danger ehati-table-btn" data-toggle="modal"
                                        data-target="#TekananDarahTableModal">
                                        <i class="fas fa-table mr-1"></i>Tabel Tekanan Darah
                                    </button>
                                    <span class="ehati-accordion-status" id="status-bp">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseBp" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row align-items-stretch">
                                        <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                            <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                                <div class="form-group mb-3">
                                                    <label class="ehati-label">Sistolik / SBP</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="sistolik"
                                                            id="sistolik" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">mmHg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="ehati-label">Diastolik / DBP</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="diastolik"
                                                            id="diastolik" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">mmHg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="ehati-label">Denyut Nadi</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="nadi" id="nadi"
                                                            placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">bpm</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-12">
                                            <div id="bp-card" class="ehati-analysis-card">
                                                <div class="ehati-analysis-inner">
                                                    <h6 class="ehati-analysis-title">Analisis <span>Tekanan Darah & Denyut
                                                            Nadi</span></h6>
                                                    <div id="bp-icon" class="ehati-analysis-icon">
                                                        <i class="fas fa-heartbeat"></i>
                                                    </div>
                                                    <h2 id="bp-status" class="ehati-analysis-status">— —</h2>
                                                    <span class="ehati-analysis-sub">Klasifikasi Tekanan Darah</span>
                                                    <div class="ehati-stats-row">
                                                        <div class="ehati-stat-item">
                                                            <span id="bp-reading" class="ehati-stat-val">--/--</span>
                                                            <small class="ehati-stat-lbl">mmHg</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="pulse-reading" class="ehati-stat-val">--</span>
                                                            <small id="pulse-label" class="ehati-stat-lbl">bpm</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="map-score" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">MAP</small>
                                                        </div>
                                                    </div>
                                                    <div class="ehati-advice">
                                                        <small id="bp-advice">—</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 4: Blood Sugar --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.2s" data-section="bs">
                            <div class="ehati-accordion-header" data-target="#collapseBs" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-amber"><i class="fas fa-tint"></i></div>
                                    <span class="ehati-badge-text">Gula Darah</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <button type="button" class="btn btn-sm btn-outline-warning ehati-table-btn" data-toggle="modal"
                                        data-target="#GulaDarahTableModal">
                                        <i class="fas fa-table mr-1"></i>Tabel Gula Darah
                                    </button>
                                    <span class="ehati-accordion-status" id="status-bs">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseBs" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row align-items-stretch">
                                        <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                            <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                                <div class="form-group mb-3">
                                                    <label class="ehati-label">Konsentrasi Glukosa</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="nilai_glukometer"
                                                            id="nilai_glukometer" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">mg/dL</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="ehati-label">Parameter</label>
                                                    <select class="custom-select ehati-select" name="parameter_gula"
                                                        id="parameter_gula">
                                                        <option value="" selected disabled>-- Pilih Parameter --</option>
                                                        <option value="GDS">GDS (Gula Darah Sewaktu)</option>
                                                        <option value="GDP">GDP (Gula Darah Puasa)</option>
                                                        <option value="GD2PP">GD2PP (Gula Darah 2 Jam PP)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-12">
                                            <div id="bs-card" class="ehati-analysis-card">
                                                <div class="ehati-analysis-inner">
                                                    <h6 id="bs-title" class="ehati-analysis-title">Analisis <span>Gula
                                                            Darah</span></h6>
                                                    <div id="bs-icon" class="ehati-analysis-icon">
                                                        <i class="fas fa-tint"></i>
                                                    </div>
                                                    <h2 id="bs-status" class="ehati-analysis-status">— —</h2>
                                                    <span id="bs-class-label" class="ehati-analysis-sub">Klasifikasi Gula
                                                        Darah</span>
                                                    <div class="ehati-stats-row">
                                                        <div class="ehati-stat-item">
                                                            <span id="bs-reading" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">mg/dL</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="bs-range" class="ehati-stat-val">—</span>
                                                            <small class="ehati-stat-lbl">Rentang Normal</small>
                                                        </div>
                                                    </div>
                                                    <div class="ehati-advice">
                                                        <small id="bs-advice">—</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 5: Cholesterol --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.25s" data-section="chol">
                            <div class="ehati-accordion-header" data-target="#collapseChol" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-green"><i class="fas fa-flask"></i></div>
                                    <span class="ehati-badge-text">Kolesterol</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <button type="button" class="btn btn-sm btn-outline-success ehati-table-btn" data-toggle="modal"
                                        data-target="#KolesterolTableModal">
                                        <i class="fas fa-table mr-1"></i>Tabel Kolesterol
                                    </button>
                                    <span class="ehati-accordion-status" id="status-chol">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseChol" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row align-items-stretch">
                                        <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                            <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                                <div class="form-group mb-0">
                                                    <label class="ehati-label">Kolesterol Total</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" class="form-control" name="kolesterol_total"
                                                            id="kolesterol_total" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">mg/dL</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-12">
                                            <div id="chol-card" class="ehati-analysis-card">
                                                <div class="ehati-analysis-inner">
                                                    <h6 class="ehati-analysis-title">Analisis <span>Kadar Kolesterol</span>
                                                    </h6>
                                                    <div id="chol-icon" class="ehati-analysis-icon">
                                                        <i class="fas fa-flask"></i>
                                                    </div>
                                                    <h2 id="chol-status" class="ehati-analysis-status">— —</h2>
                                                    <span class="ehati-analysis-sub">Klasifikasi Kolesterol</span>
                                                    <div class="ehati-stats-row">
                                                        <div class="ehati-stat-item">
                                                            <span id="chol-reading" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">mg/dL</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="chol-range" class="ehati-stat-val">—</span>
                                                            <small class="ehati-stat-lbl">Rentang Normal</small>
                                                        </div>
                                                    </div>
                                                    <div class="ehati-advice">
                                                        <small id="chol-advice">—</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 6: Uric Acid --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.3s" data-section="ua">
                            <div class="ehati-accordion-header" data-target="#collapseUa" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-cyan"><i class="fas fa-vial"></i></div>
                                    <span class="ehati-badge-text">Asam Urat</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <button type="button" class="btn btn-sm btn-outline-info ehati-table-btn" data-toggle="modal"
                                        data-target="#AsamTableModal">
                                        <i class="fas fa-table mr-1"></i>Tabel Asam Urat
                                    </button>
                                    <span class="ehati-accordion-status" id="status-ua">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseUa" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="row align-items-stretch">
                                        <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                            <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                                <div class="form-group mb-3">
                                                    <label class="ehati-label">Asam Urat</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="number" step="0.1" class="form-control"
                                                            name="asam_urat" id="asam_urat" placeholder="0.0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">mg/dL</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="ehati-label">Umur Pegawai</label>
                                                    <div class="input-group ehati-input-group">
                                                        <input type="text" class="form-control" id="ua_umur"
                                                            placeholder="-" readonly style="background-color: #f0f2f8;">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Tahun</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i>Otomatis terisi saat memilih
                                                        pegawai.
                                                        <span id="ua-age-badge" class="badge badge-pill ml-1"
                                                            style="font-size: 0.7rem;"></span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-12">
                                            <div id="ua-card" class="ehati-analysis-card">
                                                <div class="ehati-analysis-inner">
                                                    <h6 id="ua-title" class="ehati-analysis-title">Analisis <span>Asam
                                                            Urat</span></h6>
                                                    <div class="ehati-analysis-icon-dual">
                                                        <span id="ua-icon"><i class="fas fa-vial"></i></span>
                                                        <span class="ehati-icon-sep">|</span>
                                                        <span id="ua-gender-icon"><i class="fas fa-venus-mars"></i></span>
                                                    </div>
                                                    <h2 id="ua-status" class="ehati-analysis-status">— —</h2>
                                                    <span id="ua-cat-label" class="ehati-analysis-sub">Klasifikasi Asam
                                                        Urat</span>
                                                    <div class="ehati-stats-row">
                                                        <div class="ehati-stat-item">
                                                            <span id="ua-reading" class="ehati-stat-val">--.-</span>
                                                            <small class="ehati-stat-lbl">mg/dL</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="ua-term" class="ehati-stat-val">—</span>
                                                            <small class="ehati-stat-lbl">Diagnosa</small>
                                                        </div>
                                                        <div class="ehati-stat-div"></div>
                                                        <div class="ehati-stat-item">
                                                            <span id="ua-range" class="ehati-stat-val">—</span>
                                                            <small class="ehati-stat-lbl">Rentang Normal</small>
                                                        </div>
                                                    </div>
                                                    <div class="ehati-advice">
                                                        <small id="ua-advice">—</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- --- Section 7: Catatan Dokter --- --}}
                        <div class="ehati-accordion-item ehati-animate" style="animation-delay:.35s" data-section="note">
                            <div class="ehati-accordion-header" data-target="#collapseNote" aria-expanded="false">
                                <div class="ehati-accordion-badge">
                                    <div class="ehati-badge-icon bg-teal"><i class="fas fa-clipboard"></i></div>
                                    <span class="ehati-badge-text">Catatan Dokter</span>
                                </div>
                                <div class="ehati-accordion-actions">
                                    <span class="ehati-accordion-status" id="status-note">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                    <span class="ehati-accordion-toggle">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="collapseNote" class="ehati-collapse">
                                <div class="ehati-accordion-body">
                                    <div class="form-group mb-0">
                                        <textarea class="form-control ehati-input" name="catatan_dokter" id="catatan_dokter" rows="5"
                                            placeholder="Masukkan catatan dokter jika diperlukan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- End Accordion --}}

                    {{-- === Submit Button (Separated Card) === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.4s">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                            <div class="mb-2 mb-md-0">
                                <h6 class="mb-1 font-weight-bold text-gray-800">
                                    <i class="fas fa-clipboard-check mr-2 text-success"></i>Simpan Hasil Pemeriksaan
                                </h6>
                                <small class="text-muted">Pastikan semua data sudah terisi dengan benar sebelum
                                    menyimpan.</small>
                            </div>
                            <button type="submit" class="ehati-btn-save">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>

        {{-- ==================== TAB: RIWAYAT ==================== --}}
        <div id="riwayatContent"
        style="{{ !isset($activeTab) || $activeTab !== 'riwayat' ? 'display:none;' : '' }}">
        @include('admin.pemeriksaan.riwayat')
        </div>

    </div>

@endsection

@include('admin.pemeriksaan.modal.bmi')
@include('admin.pemeriksaan.modal.tekanan-darah')
@include('admin.pemeriksaan.modal.kolesterol')
@include('admin.pemeriksaan.modal.gula-darah')
@include('admin.pemeriksaan.modal.asam-urat')

@push('scripts')
<script>
    // ===== ACCORDION CONTROL WITH SMOOTH SCROLL =====
    $(document).ready(function() {

        // Helper function to collapse all sections except one
        function collapseAllExcept(exceptTarget) {
            $('.ehati-collapse').each(function() {
                const $collapse = $(this);
                const targetId = $collapse.attr('id');
                const $header = $(`.ehati-accordion-header[data-target="#${targetId}"]`);
                const $toggle = $header.find('.ehati-accordion-toggle i');

                if ('#' + targetId !== exceptTarget) {
                    // Collapse other sections
                    $collapse.removeClass('show').slideUp(300);
                    $header.attr('aria-expanded', 'false');
                    $toggle.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                }
            });
        }

        // Helper function to scroll to section
        function scrollToSection($element) {
            const headerHeight = $('.topbar').outerHeight() || 70;
            const controlBarHeight = $('.ehati-control-bar').outerHeight() || 120;
            const offset = headerHeight + controlBarHeight + 20;

            $('html, body').animate({
                scrollTop: $element.offset().top - offset
            }, 400, 'swing');
        }

        // Toggle individual accordion
        $('.ehati-accordion-header').on('click', function(e) {
            // Don't toggle if clicking on buttons inside header
            if ($(e.target).closest('button').length) {
                return;
            }

            const target = $(this).data('target');
            const $collapse = $(target);
            const $header = $(this);
            const $toggle = $header.find('.ehati-accordion-toggle i');
            const $accordionItem = $header.closest('.ehati-accordion-item');

            // Check if currently expanded
            const isExpanded = $collapse.hasClass('show');

            if (isExpanded) {
                // Collapse this section
                $collapse.removeClass('show').slideUp(300);
                $header.attr('aria-expanded', 'false');
                $toggle.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            } else {
                // Collapse all other sections first (accordion behavior)
                collapseAllExcept(target);

                // Expand this section
                $collapse.addClass('show').slideDown(300);
                $header.attr('aria-expanded', 'true');
                $toggle.removeClass('fa-chevron-right').addClass('fa-chevron-down');

                // Scroll to this section
                setTimeout(() => {
                    scrollToSection($accordionItem);
                }, 100);
            }
        });

        // Expand All Button
        $('#expandAllBtn').on('click', function() {
            $('.ehati-collapse').each(function() {
                const $collapse = $(this);
                const targetId = $collapse.attr('id');
                const $header = $(`.ehati-accordion-header[data-target="#${targetId}"]`);
                const $toggle = $header.find('.ehati-accordion-toggle i');

                $collapse.addClass('show').slideDown(300);
                $header.attr('aria-expanded', 'true');
                $toggle.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            });
        });

        // Collapse All Button
        $('#collapseAllBtn').on('click', function() {
            $('.ehati-collapse').each(function() {
                const $collapse = $(this);
                const targetId = $collapse.attr('id');
                const $header = $(`.ehati-accordion-header[data-target="#${targetId}"]`);
                const $toggle = $header.find('.ehati-accordion-toggle i');

                $collapse.removeClass('show').slideUp(300);
                $header.attr('aria-expanded', 'false');
                $toggle.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            });
        });

        // ===== PROGRESS TRACKER =====
        const sections = {
            'info': ['#tanggal_pemeriksaan'],
            'bmi': ['#tinggi_badan', '#berat_badan'],
            'bp': ['#sistolik', '#diastolik', '#nadi'],
            'bs': ['#nilai_glukometer', '#parameter_gula'],
            'chol': ['#kolesterol_total'],
            'ua': ['#asam_urat'],
            'note': ['#catatan_dokter']
        };

        function updateProgress() {
            let filledCount = 0;

            Object.keys(sections).forEach(section => {
                const inputs = sections[section];
                let hasValue = false;

                inputs.forEach(selector => {
                    const val = $(selector).val();
                    if (val && val !== '' && val !== '-') {
                        hasValue = true;
                    }
                });

                // Update section status indicator
                const statusIcon = $(`#status-${section} i`);
                const indicator = $(`.ehati-indicator[data-section="${Object.keys(sections).indexOf(section)}"]`);

                if (hasValue) {
                    filledCount++;
                    statusIcon.removeClass('text-muted').addClass('text-success');
                    indicator.addClass('filled');
                } else {
                    statusIcon.removeClass('text-success').addClass('text-muted');
                    indicator.removeClass('filled');
                }
            });

            // Update progress circle
            const percent = Math.round((filledCount / 7) * 100);
            $('#progress-percent').text(percent + '%');
            $('#filled-count').text(filledCount);

            // Update SVG circle
            const circle = document.getElementById('progress-circle');
            if (circle) {
                circle.setAttribute('stroke-dasharray', `${percent}, 100`);
            }
        }

        // Bind input changes
        Object.values(sections).flat().forEach(selector => {
            $(selector).on('input change', updateProgress);
        });

        // Initial update
        updateProgress();

        // ===== CLICK INDICATOR TO NAVIGATE TO SECTION =====
        $('.ehati-indicator').on('click', function() {
            const sectionIndex = $(this).data('section');
            const sectionKeys = Object.keys(sections);
            const sectionKey = sectionKeys[sectionIndex];
            const targetId = '#collapse' + sectionKey.charAt(0).toUpperCase() +
                            sectionKey.slice(1).replace('info', 'Info').replace('bp', 'Bp').replace('bs', 'Bs').replace('chol', 'Chol').replace('ua', 'Ua').replace('note', 'Note');

            const $collapse = $(targetId);
            const $header = $(`.ehati-accordion-header[data-target="${targetId}"]`);
            const $toggle = $header.find('.ehati-accordion-toggle i');
            const $accordionItem = $header.closest('.ehati-accordion-item');

            // Collapse all other sections
            collapseAllExcept(targetId);

            // Expand this section
            $collapse.addClass('show').slideDown(300);
            $header.attr('aria-expanded', 'true');
            $toggle.removeClass('fa-chevron-right').addClass('fa-chevron-down');

            // Scroll to this section with animation
            setTimeout(() => {
                scrollToSection($accordionItem);
            }, 100);
        });
    });
</script>
@endpush
