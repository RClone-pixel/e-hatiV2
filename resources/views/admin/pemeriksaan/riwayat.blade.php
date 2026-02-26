{{-- ============================================================
    RIWAYAT TAB – Resources/views/admin/pemeriksaan/riwayat.blade.php
    ============================================================ --}}

{{-- === Search / Filter === --}}
<div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate">
    <div class="card-body p-4">
        <div class="ehati-section-badge mb-3">
            <div class="ehati-badge-icon bg-blue"><i class="fas fa-search"></i></div>
            <span class="ehati-badge-text">Cari & Filter Riwayat</span>
        </div>
        <form action="{{ route('pemeriksaanRiwayat') }}" method="GET" id="riwayatFilterForm">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-2">
                    <label class="ehati-label-sm">Nama Pegawai</label>
                    <select class="custom-select ehati-select" name="pegawai_id" id="riwayat_pegawai_id">
                        <option value="">-- Semua Pegawai --</option>
                        @if (isset($pegawai))
                            @foreach ($pegawai as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <label class="ehati-label-sm">Dari Tanggal</label>
                    <input type="date" class="form-control ehati-input" name="dari_tanggal"
                        value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <label class="ehati-label-sm">Sampai Tanggal</label>
                    <input type="date" class="form-control ehati-input" name="sampai_tanggal"
                        value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-lg-2 col-md-6 mb-2 d-flex align-items-end">
                    <button type="submit" class="ehati-btn-search btn-block">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- === Statistics Summary === --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="ehati-stat-card ehati-stat-total ehati-animate" style="animation-delay:.05s">
            <div class="ehati-stat-body">
                <div>
                    <span class="ehati-stat-card-label">Total Pemeriksaan</span>
                    <span class="ehati-stat-card-value">{{ $riwayat->total() ?? 0 }}</span>
                </div>
                <div class="ehati-stat-card-icon"><i class="fas fa-clipboard-list"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="ehati-stat-card ehati-stat-month ehati-animate" style="animation-delay:.1s">
            <div class="ehati-stat-body">
                <div>
                    <span class="ehati-stat-card-label">Bulan Ini</span>
                    <span class="ehati-stat-card-value">{{ $countBulanIni ?? 0 }}</span>
                </div>
                <div class="ehati-stat-card-icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="ehati-stat-card ehati-stat-today ehati-animate" style="animation-delay:.15s">
            <div class="ehati-stat-body">
                <div>
                    <span class="ehati-stat-card-label">Hari Ini</span>
                    <span class="ehati-stat-card-value">{{ $countHariIni ?? 0 }}</span>
                </div>
                <div class="ehati-stat-card-icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="ehati-stat-card ehati-stat-emp ehati-animate" style="animation-delay:.2s">
            <div class="ehati-stat-body">
                <div>
                    <span class="ehati-stat-card-label">Pegawai Diperiksa</span>
                    <span class="ehati-stat-card-value">{{ $countPegawai ?? 0 }}</span>
                </div>
                <div class="ehati-stat-card-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
</div>

{{-- === Riwayat Table === --}}
<div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.25s">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
            {{-- Judul Section --}}
            <div class="ehati-section-badge mb-2 mb-md-0">
                <div class="ehati-badge-icon bg-teal"><i class="fas fa-history"></i></div>
                <span class="ehati-badge-text">Riwayat Pemeriksaan</span>
            </div>

            {{-- Tombol Export --}}
            <div class="d-flex gap-2">
                <a href="{{ route('pemeriksaanExportExcel') }}" class="btn btn-sm btn-success mr-2">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>

                {{-- Tombol Export PDF --}}
                <a href="{{ route('pemeriksaanExportPdf') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
            </div>
        </div>

        @if (isset($riwayat) && $riwayat->count() > 0)
            <div class="table-responsive">
                <table class="table ehati-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px" class="text-center">#</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">BMI</th>
                            <th class="text-center">Tekanan Darah</th>
                            <th class="text-center">Gula Darah</th>
                            <th class="text-center">Kolesterol</th>
                            <th class="text-center">Asam Urat</th>
                            <th class="text-center">Catatan Dokter</th>
                            <th style="width:90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $i => $r)
                            <tr>
                                {{-- # --}}
                                <td class="font-weight-bold text-muted">
                                    {{ $riwayat->firstItem() + $i }}
                                </td>

                                {{-- Nama Pegawai --}}
                                <td class="font-weight-bold">{{ $r->pegawai->nama ?? '-' }}</td>

                                {{-- Tanggal --}}
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($r->tanggal_pemeriksaan)->format('d M Y') }}</td>

                                {{-- BMI --}}
                                <td>
                                    @php
                                        $bmi = null;
                                        if ($r->tinggi_badan > 0 && $r->berat_badan > 0) {
                                            $bmi = round($r->berat_badan / ($r->tinggi_badan / 100) ** 2, 1);
                                        }
                                    @endphp
                                    @if ($bmi)
                                        <span class="font-weight-bold">{{ $bmi }}</span>
                                        @if ($bmi < 18.5)
                                            <span class="ehati-badge ehati-badge-info">Kurus</span>
                                        @elseif($bmi <= 24.9)
                                            <span class="ehati-badge ehati-badge-normal">Normal</span>
                                        @elseif($bmi <= 29.9)
                                            <span class="ehati-badge ehati-badge-warning">Overweight</span>
                                        @else
                                            <span class="ehati-badge ehati-badge-danger">Obesitas</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Tekanan Darah --}}
                                <td>
                                    @if ($r->sistolik && $r->diastolik)
                                        <span class="font-weight-bold">{{ $r->sistolik }}/{{ $r->diastolik }}</span>
                                        @if ($r->sistolik < 120 && $r->diastolik < 80)
                                            <span class="ehati-badge ehati-badge-normal">Normal</span>
                                        @elseif($r->sistolik < 140 || $r->diastolik < 90)
                                            <span class="ehati-badge ehati-badge-warning">Tinggi</span>
                                        @else
                                            <span class="ehati-badge ehati-badge-danger">Hipertensi</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Gula Darah --}}
                                <td>
                                    @if ($r->konsentrasi_glukosa)
                                        <span class="font-weight-bold">{{ $r->konsentrasi_glukosa }}</span>
                                        <small class="text-muted">mg/dL</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Kolesterol --}}
                                <td>
                                    @if ($r->kolesterol_total)
                                        <span class="font-weight-bold">{{ $r->kolesterol_total }}</span>
                                        @if ($r->kolesterol_total < 200)
                                            <span class="ehati-badge ehati-badge-normal">Normal</span>
                                        @elseif($r->kolesterol_total <= 239)
                                            <span class="ehati-badge ehati-badge-warning">Borderline</span>
                                        @else
                                            <span class="ehati-badge ehati-badge-danger">Tinggi</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Asam Urat --}}
                                <td>
                                    @if ($r->asam_urat)
                                        <span class="font-weight-bold">{{ $r->asam_urat }}</span>
                                        <small class="text-muted">mg/dL</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Catatan Dokter --}}
                                <td>
                                    @if ($r->catatan_dokter)
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                            title="{{ $r->catatan_dokter }}">
                                            {{ Str::limit($r->catatan_dokter, 20) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" class="ehati-btn-action ehati-btn-view mr-1"
                                        data-toggle="modal" data-target="#detailRiwayatModal"
                                        data-id="{{ $r->id }}" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <form action="{{ route('pemeriksaanDelete', $r->id) }}" method="POST"
                                        style="display:inline" class="form-delete-riwayat">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ehati-btn-action ehati-btn-delete"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                <small class="text-muted">
                    Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }}
                    dari {{ $riwayat->total() }} data
                </small>
                <div>
                    {{ $riwayat->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-5">
                <div class="ehati-empty-icon mx-auto mb-3">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h5 class="font-weight-bold text-gray-800 mb-2">Belum Ada Data Riwayat</h5>
                <p class="text-muted mb-0" style="max-width:380px;margin:0 auto;">
                    Data riwayat pemeriksaan akan muncul di sini setelah Anda menyimpan hasil pemeriksaan
                    dari tab <strong>Pemeriksaan</strong>.
                </p>
            </div>
        @endif
    </div>
</div>

{{-- === Include Modal Detail === --}}
@include('admin.pemeriksaan.modal-detail')
