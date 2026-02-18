@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-edit mr-2"></i>
        {{ $title }}
    </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header bg-warning">
            <a href="{{ route('pegawai') }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('pegawaiUpdate', $pegawai->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-xl-6">
                        <label class="form-label font-weight-bold">
                            <span class="text-danger">*</span>
                            Nama :
                        </label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ $pegawai->nama }}" placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-xl-6">
                        <label class="form-label font-weight-bold">
                            <span class="text-danger">*</span>
                            Upload Foto :
                        </label>
                        <input type="file" class="custom-control-file @error('foto') is-invalid @enderror ml-2"
                            id="foto" name="foto" accept="image/jpeg,image/png">
                        <div class="">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye mr-2"></i>
                                View
                            </a>
                        </div>
                        @error('foto')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-4 mb-4 mb-xl-0">
                        <label for="tanggal_lahir" class="font-weight-bold"">
                            <span class="text-danger">*</span>
                            Jenis Kelamin
                        </label>
                        <select class="custom-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                            name="jenis_kelamin" value="{{ $pegawai->jenis_kelamin }}">
                            <option disabled {{ old('jenis_kelamin') ? '' : 'selected' }} class="text-muted">-- Pilih Jenis
                                Kelamin --</option>
                            <option value="Laki-laki" {{ $pegawai->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" {{ $pegawai->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-xl-4">
                        <label for="tanggal_lahir" class="font-weight-bold"">
                            <span class="text-danger">*</span>
                            Tanggal Lahir
                        </label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                            id="tanggal_lahir" name="tanggal_lahir" value="{{ $pegawai->tanggal_lahir->format('Y-m-d') }}"
                            min="1950-01-01" max="2026-01-01">
                        @error('tanggal_lahir')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-xl-4">
                        <div class="form-label">
                            <label for="golongan darah" class="font-weight-bold">
                                <span class="text-danger">*</span>
                                Golongan Darah
                            </label>
                            <select class="custom-select @error('golongan_darah') is-invalid @enderror" id="golongan_darah"
                                name="golongan_darah" value="{{ $pegawai->golongan_darah }}">
                                <option disabled class="text-muted" class="active">-- Pilih Gol. Darah --</option>
                                <optgroup label="-- Rhesus Positif --">
                                    <option value="O+" @if ($pegawai->golongan_darah == 'O+') selected @endif>O+</option>
                                    <option value="A+" @if ($pegawai->golongan_darah == 'A+') selected @endif>A+</option>
                                    <option value="B+" @if ($pegawai->golongan_darah == 'B+') selected @endif>B+</option>
                                    <option value="AB+" @if ($pegawai->golongan_darah == 'AB+') selected @endif>AB+</option>
                                </optgroup>
                                <optgroup label="-- Rhesus Negatif --">
                                    <option value="O-" @if ($pegawai->golongan_darah == 'O-') selected @endif>O-</option>
                                    <option value="A-" @if ($pegawai->golongan_darah == 'A-') selected @endif>A-</option>
                                    <option value="B-" @if ($pegawai->golongan_darah == 'B-') selected @endif>B-</option>
                                    <option value="AB-" @if ($pegawai->golongan_darah == 'AB-') selected @endif>AB-</option>
                                </optgroup>
                            </select>
                            @error('golongan_darah')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label font-weight-bold">
                            <span class="text-danger">*</span>
                            Riwayat Penyakit :
                        </label>
                        <textarea class="form-control @error('riwayat_penyakit') is-invalid @enderror" name="riwayat_penyakit" rows="5"
                            placeholder="Tulis riwayat penyakit di sini...">{{ $pegawai->riwayat_penyakit }}</textarea>
                        <small class="text-muted">Riwayat Penyakit jika tidak ada tulis "Tidak Ada"
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
