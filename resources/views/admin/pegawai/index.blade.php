@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title }}
    </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-xl-between justify-content-center">

            <div class="mb-1 mr-1">
                <a href="{{ route('pegawaiCreate') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah data
                </a>
            </div>

            <div>
                {{-- Tombol Export Excel --}}
                <a href="{{ route('pegawaiExportExcel') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel mr-2"></i>
                    Excel
                </a>

                {{-- Tombol Export PDF --}}
                <a href="{{ route('pegawaiExportPdf') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf mr-2"></i>
                    PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Umur</th>
                            <th>Gol. Darah</th>
                            <th>Riwayat Penyakit</th>
                            <th>
                                <i class="fas fa-cog"></i>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pegawai as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                                <td class="text-center">{{ date('d-m-Y', strtotime($item->tanggal_lahir)) }}</td>
                                <td class="text-center">{{ $item->umur }}</td>
                                <td class="text-center">{{ $item->gol_darah }}</td>
                                <td>{{ $item->riwayat_penyakit }}</td>
                                <td class="text-center">
                                    <a href="{{ route('pegawaiEdit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#exampleModal{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @include('admin.pegawai.modal')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
