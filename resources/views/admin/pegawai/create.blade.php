@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus mr-2"></i>
        {{ $title }}
    </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <a href="{{ route('pegawai') }}" class="btn btn-sm btn-success">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('pegawaiStore') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-6 mt-2">
                        <label class="form-label font-weight-bold">
                            <span class="text-danger">*</span>
                            Nama :
                        </label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-xl-3 mt-2">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">
                                <span class="text-danger">*</span>
                                Upload Foto :
                            </label>
                            <input type="file" class="form-control-file @error('foto') is-invalid @enderror"
                                id="foto" name="foto" accept="image/jpeg,image/png"></input>
                            <small class="text-muted">.jpg, .jpeg, .png | Max: 2MB</small> </br>
                            @error('foto')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-4 mb-xl-0 mb-2">
                        <label for="tanggal_lahir" class="font-weight-bold"">
                            <span class="text-danger">*</span>
                            Jenis Kelamin
                        </label>
                        <select class="custom-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                            name="jenis_kelamin" value="{{ old('jenis_kelamin') }}">
                            <option disabled {{ old('jenis_kelamin') ? '' : 'selected' }} class="text-muted">-- Pilih Jenis
                                Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-xl-4 mb-xl-0 mb-2">
                        <label for="tanggal_lahir" class="font-weight-bold"">
                            <span class="text-danger">*</span>
                            Tanggal Lahir
                        </label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                            id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" min="1950-01-01"
                            max="2026-01-01">
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
                                name="golongan_darah" value="{{ old('golongan_darah') }}">
                                <option disabled {{ old('golongan_darah') ? '' : 'selected' }} class="text-muted">-- Pilih
                                    Gol. Darah --</option>
                                <optgroup label="-- Rhesus Positif --">
                                    <option value="O+" {{ old('golongan_darah') == 'O+' ? 'selected' : '' }}>O+
                                    </option>
                                    <option value="A+" {{ old('golongan_darah') == 'A+' ? 'selected' : '' }}>A+
                                    </option>
                                    <option value="B+" {{ old('golongan_darah') == 'B+' ? 'selected' : '' }}>B+
                                    </option>
                                    <option value="AB+" {{ old('golongan_darah') == 'AB+' ? 'selected' : '' }}>AB+
                                    </option>
                                </optgroup>
                                <optgroup label="-- Rhesus Negatif --">
                                    <option value="O-" {{ old('golongan_darah') == 'O-' ? 'selected' : '' }}>O-
                                    </option>
                                    <option value="A-" {{ old('golongan_darah') == 'A-' ? 'selected' : '' }}>A-
                                    </option>
                                    <option value="B-" {{ old('golongan_darah') == 'B-' ? 'selected' : '' }}>B-
                                    </option>
                                    <option value="AB-" {{ old('golongan_darah') == 'AB-' ? 'selected' : '' }}>AB-
                                    </option>
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
                            placeholder="Tulis riwayat penyakit di sini...">{{ old('riwayat_penyakit') }}</textarea>
                        <small class="text-muted">Riwayat Penyakit jika tidak ada tulis "Tidak Ada"
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
