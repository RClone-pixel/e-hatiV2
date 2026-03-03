{{-- ============================================================
RIWAYAT TAB – resources/views/admin/pemeriksaan/riwayat.blade.php

Perubahan dari original:
- Filter: dari_tanggal + sampai_tanggal → tanggal_pemeriksaan (single)
- Hapus 4 stat card (dipindah ke dashboard)
- Tambah sort header tabel (client-side JS)
- Export URL otomatis bawa filter + sort aktif
- Fix field: nilai_glukometer → konsentrasi_glukosa
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
            <div class="col-lg-5 col-md-6 mb-2">
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
            <div class="col-lg-5 col-md-6 mb-2">
                <label class="ehati-label-sm">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control ehati-input" name="tanggal_pemeriksaan"
                    value="{{ request('tanggal_pemeriksaan') }}">
            </div>
            <div class="col-lg-2 col-md-12 mb-2 d-flex align-items-end">
                <button type="submit" class="ehati-btn-search btn-block">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>
</div>

{{-- === Riwayat Table === --}}
<div class="ehati-card card shadow-sm border-0 mb-4 ehati-animate" style="animation-delay:.1s">
<div class="card-body p-4">

    {{-- Header: Judul + Export --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
        <div class="ehati-section-badge mb-2 mb-md-0">
            <div class="ehati-badge-icon bg-teal"><i class="fas fa-history"></i></div>
            <span class="ehati-badge-text">Riwayat Pemeriksaan</span>
        </div>
        <div class="d-flex">
            <a href="#" id="btnExportExcel" class="btn btn-sm btn-success mr-2">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="#" id="btnExportPdf" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>

    @if (isset($riwayat) && $riwayat->count() > 0)
        <div class="table-responsive">
            <table class="table ehati-table mb-0" id="riwayatTable">
                <thead>
                    <tr>
                        <th style="width:44px" class="text-center sortable" data-sort="no">#</th>
                        <th class="sortable" data-sort="nama">Nama</th>
                        <th class="text-center sortable" data-sort="tanggal">Tanggal</th>
                        <th class="text-center sortable" data-sort="bmi">BMI</th>
                        <th class="text-center sortable" data-sort="td">Tekanan Darah</th>
                        <th class="text-center sortable" data-sort="gula">Gula Darah</th>
                        <th class="text-center sortable" data-sort="kol">Kolesterol</th>
                        <th class="text-center sortable" data-sort="au">Asam Urat</th>
                        <th>Catatan Dokter</th>
                        <th style="width:90px" class="text-center"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayat as $i => $r)
                        <tr>
                            {{-- # --}}
                            <td class="text-center" style="color:#b7b9cc;font-weight:600;">
                                {{ $riwayat->firstItem() + $i }}
                            </td>

                            {{-- Nama --}}
                            <td class="font-weight-bold">{{ $r->pegawai->nama ?? '-' }}</td>

                            {{-- Tanggal --}}
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($r->tanggal_pemeriksaan)->format('d M Y') }}
                            </td>

                            {{-- BMI --}}
                            <td class="text-center">
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
                            <td class="text-center">
                                @if ($r->sistolik && $r->diastolik)
                                    <span class="font-weight-bold">{{ $r->sistolik }}/{{ $r->diastolik }}</span>
                                    @if ($r->sistolik < 120 && $r->diastolik < 80)
                                        <span class="ehati-badge ehati-badge-normal">Normal</span>
                                    @elseif($r->sistolik < 140 && $r->diastolik < 90)
                                        <span class="ehati-badge ehati-badge-warning">Tinggi</span>
                                    @else
                                        <span class="ehati-badge ehati-badge-danger">Hipertensi</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Gula Darah — pakai konsentrasi_glukosa (kolom DB) --}}
                            <td class="text-center">
                                @if ($r->konsentrasi_glukosa)
                                    <span class="font-weight-bold">{{ $r->konsentrasi_glukosa }}</span>
                                    <small class="text-muted">mg/dL</small>
                                    @if ($r->parameter_gula)
                                        <br><small class="text-muted">{{ $r->parameter_gula }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Kolesterol --}}
                            <td class="text-center">
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
                            <td class="text-center">
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
                                    <span class="text-truncate d-inline-block" style="max-width:150px;"
                                        title="{{ $r->catatan_dokter }}">
                                        {{ Str::limit($r->catatan_dokter, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                    <button type="button" class="ehati-btn-action ehati-btn-view"
                                        data-toggle="modal" data-target="#detailRiwayatModal"
                                        data-id="{{ $r->id }}" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('pemeriksaanDelete', $r->id) }}" method="POST"
                                        style="display:flex;align-items:center;margin:0;" class="form-delete-riwayat">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ehati-btn-action ehati-btn-delete"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="ehati-pagination-wrap">
            <small class="ehati-pagination-info">
                Menampilkan <strong>{{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }}</strong>
                dari <strong>{{ $riwayat->total() }}</strong> data
                @if(request('pegawai_id') || request('tanggal_pemeriksaan'))
                    <span style="background:#eef0fb;color:#4e73df;font-size:0.72rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:6px;">
                        <i class="fas fa-filter mr-1"></i>Filter aktif
                    </span>
                @endif
            </small>
            <div>
                {{ $riwayat->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>

    @else
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

// ============================================================
// Export URLs — bawa filter + sort yang sedang aktif
// ============================================================
var baseExcelUrl = '{{ route('pemeriksaanExportExcel') }}';
var basePdfUrl   = '{{ route('pemeriksaanExportPdf') }}';

function buildExportUrl(base) {
    var params = new URLSearchParams(window.location.search);
    params.delete('page'); // jangan bawa page
    return base + '?' + params.toString();
}

document.getElementById('btnExportExcel').href = buildExportUrl(baseExcelUrl);
document.getElementById('btnExportPdf').href   = buildExportUrl(basePdfUrl);

// ============================================================
// Table Sort — client-side
// ============================================================
var table   = document.getElementById('riwayatTable');
if (!table) return;

var headers = table.querySelectorAll('th.sortable');

headers.forEach(function(header) {
    header.addEventListener('click', function () {
        var sortKey = header.dataset.sort;
        var isAsc   = !header.classList.contains('sort-asc');

        headers.forEach(function(h) { h.classList.remove('sort-asc', 'sort-desc'); });
        header.classList.add(isAsc ? 'sort-asc' : 'sort-desc');

        sortTable(table, sortKey, isAsc);
    });
});

function sortTable(table, sortKey, isAsc) {
    var tbody = table.querySelector('tbody');
    var rows  = Array.from(tbody.querySelectorAll('tr'));

    rows.sort(function(a, b) {
        var aVal = getCellVal(a, sortKey);
        var bVal = getCellVal(b, sortKey);
        if (aVal < bVal) return isAsc ? -1 : 1;
        if (aVal > bVal) return isAsc ?  1 : -1;
        return 0;
    });

    rows.forEach(function(row) { tbody.appendChild(row); });
}

function getCellVal(row, sortKey) {
    var cells = row.querySelectorAll('td');
    switch (sortKey) {
        case 'no':      return parseInt(cells[0].textContent.trim()) || 0;
        case 'nama':    return cells[1].textContent.trim().toLowerCase();
        case 'tanggal': return parseIndonesianDate(cells[2].textContent.trim());
        case 'bmi':     return parseFloat(cells[3].textContent.trim()) || 0;
        case 'td':      return cells[4].textContent.trim();
        case 'gula':    return parseFloat(cells[5].textContent.trim()) || 0;
        case 'kol':     return parseFloat(cells[6].textContent.trim()) || 0;
        case 'au':      return parseFloat(cells[7].textContent.trim()) || 0;
        default:        return '';
    }
}

function parseIndonesianDate(str) {
    var months = {
        'Jan':0,'Feb':1,'Mar':2,'Apr':3,'Mei':4,'Jun':5,
        'Jul':6,'Agu':7,'Sep':8,'Okt':9,'Nov':10,'Des':11
    };
    var parts = str.split(' ');
    if (parts.length === 3) {
        return new Date(parseInt(parts[2]), months[parts[1]] ?? 0, parseInt(parts[0])).getTime();
    }
    return 0;
}
});
</script>
@endpush
