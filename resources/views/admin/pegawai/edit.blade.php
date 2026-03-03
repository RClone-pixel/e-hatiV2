@extends('layouts.app')

@section('content')
    {{-- Page Title --}}
    <div class="pegawai-section-badge mb-4 pegawai-animate">
        <div class="pegawai-badge-icon bg-warning-gradient">
            <i class="fas fa-user-edit"></i>
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
            <form action="{{ route('pegawaiUpdate', $pegawai->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Photo Upload Section --}}
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="text-center">
                            <label class="pegawai-form-label d-block">Foto Pegawai</label>
                            <div class="position-relative d-inline-block">
                                <div class="pegawai-photo-upload mx-auto has-image" id="photoUpload" onclick="document.getElementById('foto').click()">
                                    @if($pegawai->foto)
                                        <img id="photoPreview" src="{{ asset('storage/' . $pegawai->foto) }}" onerror="this.src='{{ asset('sbadmin2/img/user_kppn.png') }}'">
                                    @else
                                        <img id="photoPreview" src="{{ asset('sbadmin2/img/user_kppn.png') }}">
                                    @endif
                                </div>
                                {{-- View Photo Button --}}
                                <button type="button" class="pegawai-photo-view-btn" data-toggle="modal" data-target="#viewPhotoModal" title="Lihat Foto">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <input type="file" class="d-none" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(this)">
                            <small class="text-muted d-block mt-2">Klik foto untuk mengganti (Max 2MB)</small>
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
                                       id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}"
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
                                    <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir->format('Y-m-d')) }}"
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
                                    <option value="" disabled>-- Pilih Gol. Darah --</option>
                                    <optgroup label="Rhesus Positif">
                                        <option value="O+" {{ old('golongan_darah', $pegawai->gol_darah) == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="A+" {{ old('golongan_darah', $pegawai->gol_darah) == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="B+" {{ old('golongan_darah', $pegawai->gol_darah) == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="AB+" {{ old('golongan_darah', $pegawai->gol_darah) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    </optgroup>
                                    <optgroup label="Rhesus Negatif">
                                        <option value="O-" {{ old('golongan_darah', $pegawai->gol_darah) == 'O-' ? 'selected' : '' }}>O-</option>
                                        <option value="A-" {{ old('golongan_darah', $pegawai->gol_darah) == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B-" {{ old('golongan_darah', $pegawai->gol_darah) == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB-" {{ old('golongan_darah', $pegawai->gol_darah) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    </optgroup>
                                </select>
                                @error('golongan_darah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Riwayat Penyakit --}}
                            <div class="col-12 mb-3">
                                <label class="pegawai-form-label">
                                    <span class="text-danger">*</span> Riwayat Penyakit
                                </label>
                                <textarea class="pegawai-form-input form-control @error('riwayat_penyakit') is-invalid @enderror"
                                          id="riwayat_penyakit" name="riwayat_penyakit" rows="4"
                                          placeholder="Tuliskan riwayat penyakit pegawai (jika tidak ada, tulis 'Tidak Ada')">{{ old('riwayat_penyakit', $pegawai->riwayat_penyakit) }}</textarea>
                                <small class="text-muted">Riwayat penyakit akan membantu dokter dalam pemeriksaan.</small>
                                @error('riwayat_penyakit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-right pt-3 border-top">
                    <button type="submit" class="pegawai-btn-submit" style="background: linear-gradient(135deg, #f6c23e, #dda20a);">
                        <i class="fas fa-edit mr-2"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Photo Modal --}}
    <div class="modal fade" id="viewPhotoModal" tabindex="-1" role="dialog" aria-labelledby="viewPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content pegawai-modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #4e73df, #224abe); border: none; padding: 16px 20px;">
                    <h5 class="modal-title text-white" id="viewPhotoModalLabel">
                        <i class="fas fa-image mr-2"></i>Foto Pegawai
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    @if($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}"
                             alt="{{ $pegawai->nama }}"
                             class="img-fluid rounded"
                             style="max-height: 300px; object-fit: cover;"
                             onerror="this.src='{{ asset('sbadmin2/img/user_kppn.png') }}'">
                    @else
                        <img src="{{ asset('sbadmin2/img/user_kppn.png') }}"
                             alt="{{ $pegawai->nama }}"
                             class="img-fluid rounded"
                             style="max-height: 300px; object-fit: cover;">
                    @endif
                    <h5 class="mt-3 mb-0 font-weight-bold">{{ $pegawai->nama }}</h5>
                    <p class="text-muted">{{ $pegawai->jenis_kelamin }} · {{ $pegawai->umur }} Tahun</p>
                </div>
                <div class="pegawai-modal-footer text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
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

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
