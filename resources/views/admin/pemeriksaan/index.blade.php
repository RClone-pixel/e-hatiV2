@extends('layouts.app')

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
                    <a class="nav-link active" id="pemeriksaan-tab" data-toggle="pill" href="#pemeriksaanContent"
                        role="tab" aria-controls="pemeriksaanContent" aria-selected="true">
                        <i class="fas fa-stethoscope mr-1"></i><span>Pemeriksaan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="riwayat-tab" data-toggle="pill" href="#riwayatContent" role="tab"
                        aria-controls="riwayatContent" aria-selected="false">
                        <i class="fas fa-history mr-1"></i><span>Riwayat</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- ===== Tab Content ===== --}}
    <div class="tab-content" id="mainTabContent">

        {{-- ==================== TAB: PEMERIKSAAN ==================== --}}
        <div class="tab-pane fade show active" id="pemeriksaanContent" role="tabpanel" aria-labelledby="pemeriksaan-tab">

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

                {{-- === Examination Sections (Disabled until employee selected) === --}}
                <fieldset id="pemeriksaan-section" disabled>

                    {{-- --- Meta Info --- --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.05s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-teal"><i class="fas fa-calendar-check"></i></div>
                                <span class="ehati-badge-text">Informasi Pemeriksaan</span>
                            </div>
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

                    {{-- === SECTION: BMI === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.1s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-blue"><i class="fas fa-weight"></i></div>
                                <span class="ehati-badge-text">Body Mass Index & Broca</span>
                            </div>
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

                    {{-- === SECTION: Blood Pressure === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.15s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-red"><i class="fas fa-heartbeat"></i></div>
                                <span class="ehati-badge-text">Tekanan Darah & Denyut Nadi</span>
                            </div>
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

                    {{-- === SECTION: Blood Sugar === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.2s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-amber"><i class="fas fa-tint"></i></div>
                                <span class="ehati-badge-text">Gula Darah</span>
                            </div>
                            <div class="row align-items-stretch">
                                <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                    <div class="ehati-input-section h-100 d-flex flex-column justify-content-center">
                                        <div class="form-group mb-3">
                                            <label class="ehati-label">Nilai Glukometer</label>
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
                                                    <small class="ehati-stat-lbl">Rentang</small>
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

                    {{-- === SECTION: Cholesterol === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.25s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-green"><i class="fas fa-flask"></i></div>
                                <span class="ehati-badge-text">Kolesterol</span>
                            </div>
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
                                            <h6 class="ehati-analysis-title">Analisis <span>Kadar Kolesterol</span></h6>
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
                                                    <small class="ehati-stat-lbl">Rentang</small>
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

                    {{-- === SECTION: Uric Acid === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.3s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-cyan"><i class="fas fa-vial"></i></div>
                                <span class="ehati-badge-text">Asam Urat</span>
                            </div>
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
                                                    <small class="ehati-stat-lbl">Rentang (mg/dL)</small>
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

                    {{-- Catatan Dokter --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.25s">
                        <div class="card-body p-4">
                            <div class="ehati-section-badge mb-3">
                                <div class="ehati-badge-icon bg-teal"><i class="fas fa-clipboard"></i></div>
                                <span class="ehati-badge-text">Catatan Dokter</span>
                            </div>
                            <div class="form-group mb-0">
                                <textarea class="form-control ehati-input" name="catatan_dokter" id="catatan_dokter" rows="5"
                                    placeholder="-"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- === Submit Button === --}}
                    <div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.35s">
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
        <div class="tab-pane fade" id="riwayatContent" role="tabpanel" aria-labelledby="riwayat-tab">
            @include('admin.pemeriksaan.riwayat')
        </div>

    </div>

    {{-- Tab activation script --}}
    @if (isset($activeTab) && $activeTab === 'riwayat')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#riwayat-tab').tab('show');
            });
        </script>
    @endif
@endsection
