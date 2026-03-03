@extends('layouts.app')

@section('content')
    {{-- Page Title --}}
    <div class="pegawai-section-badge mb-4 pegawai-animate">
        <div class="pegawai-badge-icon bg-success-gradient">
            <i class="fas fa-user-plus"></i>
        </div>
        <span class="pegawai-badge-text">{{ $title }}</span>
    </div>

    {{-- Main Card --}}
    <div class="pegawai-card card shadow-sm pegawai-animate" style="animation-delay: 0.05s">
        <div class="card-body p-4">
            {{-- Back Button --}}
            <div class="mb-4">
                <a href="{{ route('pegawai') }}" class="pegawai-btn-back">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            {{-- Form --}}
            <form action="{{ route('pegawaiStore') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Photo Upload Section --}}
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="text-center">
                            <label class="pegawai-form-label d-block">Foto Pegawai</label>
                            <div class="pegawai-photo-upload mx-auto" id="photoUpload" onclick="document.getElementById('foto').click()">
                                <div class="pegawai-photo-upload-placeholder" id="photoPlaceholder">
                                    <i class="fas fa-camera"></i>
                                    <span>Klik untuk upload</span>
                                </div>
                                <img id="photoPreview" style="display: none;">
                            </div>
                            <input type="file" class="d-none" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(this)">
                            <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB)</small>
                            @error('foto')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="col-lg-9 col-md-8">
                        <div class="row">
                            {{-- Nama --}}
                            <div class="col-md-6 mb-3">
                                <label class="pegawai-form-label">
                                    <span class="text-danger">*</span> Nama Lengkap
                                </label>
                                <input type="text" class="pegawai-form-input form-control @error('nama') is-invalid @enderror"
                                       id="nama" name="nama" value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap pegawai">
                                @error('nama')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6 mb-3">
                                <label class="pegawai-form-label">
                                    <span class="text-danger">*</span> Jenis Kelamin
                                </label>
                                <select class="pegawai-form-select custom-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="col-md-6 mb-3">
                                <label class="pegawai-form-label">
                                    <span class="text-danger">*</span> Tanggal Lahir
                                </label>
                                <input type="date" class="pegawai-form-input form-control @error('tanggal_lahir') is-invalid @enderror"
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                       min="1950-01-01" max="{{ date('Y-m-d') }}">
                                @error('tanggal_lahir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Golongan Darah --}}
                            <div class="col-md-6 mb-3">
                                <label class="pegawai-form-label">
                                    <span class="text-danger">*</span> Golongan Darah
                                </label>
                                <select class="pegawai-form-select custom-select @error('golongan_darah') is-invalid @enderror"
                                        id="golongan_darah" name="golongan_darah">
                                    <option value="" disabled {{ old('golongan_darah') ? '' : 'selected' }}>-- Pilih Gol. Darah --</option>
                                    <optgroup label="Rhesus Positif">
                                        <option value="O+" {{ old('golongan_darah') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="A+" {{ old('golongan_darah') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="B+" {{ old('golongan_darah') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="AB+" {{ old('golongan_darah') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    </optgroup>
                                    <optgroup label="Rhesus Negatif">
                                        <option value="O-" {{ old('golongan_darah') == 'O-' ? 'selected' : '' }}>O-</option>
                                        <option value="A-" {{ old('golongan_darah') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B-" {{ old('golongan_darah') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB-" {{ old('golongan_darah') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    </optgroup>
                                </select>
                                @error('golongan_darah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Riwayat Penyakit --}}
                            <div class="col-12 mb-3">
                                <label class="pegawai-form-label">
                                    Riwayat Penyakit
                                </label>
                                <textarea class="pegawai-form-input form-control @error('riwayat_penyakit') is-invalid @enderror"
                                          id="riwayat_penyakit" name="riwayat_penyakit" rows="4"
                                          placeholder="Jika tidak ada, langsung simpan">{{ old('riwayat_penyakit') }}</textarea>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-right pt-3 border-top">
                    <button type="submit" class="pegawai-btn-submit">
                        <i class="fas fa-save mr-2"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('css/pegawai.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            const upload = document.getElementById('photoUpload');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    upload.classList.add('has-image');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.pegawai-form-input, .pegawai-form-select').forEach(function(el) {
                el.addEventListener('input', clearError);
                el.addEventListener('change', clearError);
            });
        });

        function clearError(e) {
            const field = e.target;
            field.classList.remove('is-invalid');

            let next = field.nextElementSibling;
            while (next) {
                if (next.tagName === 'SMALL' && next.classList.contains('text-danger')) {
                    next.remove();
                    break;
                }
                next = next.nextElementSibling;
            }
        }
    </script>
@endpush
