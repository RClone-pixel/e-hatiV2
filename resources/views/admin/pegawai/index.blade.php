@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-users mr-2"></i>{{ $title }}
    </h1>

    <div class="pegawai-card card shadow-sm border-0 mb-4 pegawai-animate">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                <div class="pegawai-section-badge mb-2 mb-md-0">
                    <div class="pegawai-badge-icon bg-primary-gradient"><i class="fas fa-list"></i></div>
                    <span class="pegawai-badge-text">Daftar Pegawai</span>
                </div>
                <div class="d-flex flex-wrap">
                    <a href="{{ route('pegawaiCreate') }}" class="pegawai-btn-add mr-2 mb-1">
                        <i class="fas fa-plus mr-1"></i> Tambah Data
                    </a>
                    <a href="{{ route('pegawaiExportExcel') }}" id="btnExportExcel" class="btn btn-sm btn-success pegawai-btn-export mr-2 mb-1">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </a>
                    <a href="{{ route('pegawaiExportPdf') }}" id="btnExportPdf" class="btn btn-sm btn-danger pegawai-btn-export mb-1">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </a>
                </div>
            </div>

            {{-- Table --}}
            @if (isset($pegawai) && count($pegawai) > 0)
                <div class="pegawai-table-wrapper">
                    <table class="table pegawai-table mb-0" id="pegawaiTable">
                        <thead>
                            <tr>
                                <th class="text-center sortable" data-sort="no" style="width:50px;">#</th>
                                <th class="text-center sortable" data-sort="nama">Nama</th>
                                <th class="text-center sortable" data-sort="jenis_kelamin" style="width:120px;">Jenis
                                    Kelamin</th>
                                <th class="text-center sortable" data-sort="tanggal_lahir" style="width:130px;">Tgl. Lahir
                                </th>
                                <th class="text-center sortable" data-sort="umur" style="width:80px;">Umur</th>
                                <th class="text-center sortable" data-sort="gol_darah" style="width:90px;">Gol. Darah</th>
                                <th class="text-center">Riwayat Penyakit</th>
                                <th class="text-center" style="width:90px;"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawai as $item)
                                <tr>
                                    <td class="text-center" style="color:#b7b9cc;font-weight:600;">{{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Wrapper untuk hover float preview --}}
                                            <div class="pegawai-photo-wrapper mr-2">
                                                <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('sbadmin2/img/user_kppn.png') }}"
                                                    alt="{{ $item->nama }}" data-name="{{ $item->nama }}"
                                                    onerror="this.src='{{ asset('sbadmin2/img/user_kppn.png') }}'"
                                                    class="pegawai-photo-thumb">
                                                {{-- Hover float preview (CSS only, tanpa JS) --}}
                                                <div class="pegawai-photo-hover-preview">
                                                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('sbadmin2/img/user_kppn.png') }}"
                                                        alt="{{ $item->nama }}"
                                                        onerror="this.src='{{ asset('sbadmin2/img/user_kppn.png') }}'">
                                                </div>
                                            </div>
                                            <span style="font-weight:600;color:#3a3b45;">{{ $item->nama }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if (str_contains(strtolower($item->jenis_kelamin), 'laki'))
                                            <span
                                                style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;background:#f0f6ff;color:#3a6eb5;font-size:0.78rem;font-weight:600;">
                                                <i class="fas fa-mars" style="font-size:0.7rem;"></i> Laki-laki
                                            </span>
                                        @else
                                            <span
                                                style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;background:#fef0f7;color:#c4448a;font-size:0.78rem;font-weight:600;">
                                                <i class="fas fa-venus" style="font-size:0.7rem;"></i> Perempuan
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center" style="color:#5a5c69;font-size:0.88rem;">
                                        {{ $item->tanggal_lahir->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center">
                                        <span style="font-weight:700;color:#3a3b45;">{{ $item->umur }}</span>
                                        <small style="color:#b7b9cc;"> th</small>
                                    </td>

                                    <td class="text-center">
                                        <span
                                            style="display:inline-block;padding:3px 10px;border-radius:20px;background:#f4faf6;color:#3a9e6f;font-size:0.82rem;font-weight:700;border:1px solid #3a9e6f18;">
                                            {{ $item->gol_darah }}
                                        </span>
                                    </td>

                                    <td style="color:#5a5c69;font-size:0.88rem;" title="{{ $item->riwayat_penyakit }}">
                                        {{ Str::limit($item->riwayat_penyakit, 35) }}
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                            <a href="{{ route('pegawaiEdit', $item->id) }}"
                                                class="pegawai-btn-action pegawai-btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="pegawai-btn-action pegawai-btn-delete"
                                                data-toggle="modal" data-target="#deleteModal{{ $item->id }}"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="pegawai-empty-icon mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6 class="font-weight-bold text-gray-800 mb-1">Belum Ada Data Pegawai</h6>
                    <p class="text-muted small mb-3">Tambahkan data pegawai untuk mulai menggunakan fitur ini.</p>
                    <a href="{{ route('pegawaiCreate') }}" class="pegawai-btn-add">
                        <i class="fas fa-plus mr-1"></i> Tambah Pegawai
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Modals — WAJIB di luar tabel, bukan di dalam <td> --}}
    @foreach ($pegawai as $item)
        @include('admin.pegawai.modal', ['item' => $item])
    @endforeach

@endsection

@push('styles')
    <link href="{{ asset('css/pegawai.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/pegawai.js') }}"></script>
@endpush
