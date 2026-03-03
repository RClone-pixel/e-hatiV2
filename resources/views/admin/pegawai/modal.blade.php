{{-- Delete Modal for Pegawai --}}
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content pegawai-modal-content">
            {{-- Modal Header --}}
            <div class="pegawai-modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="pegawai-modal-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}"
                                 alt="{{ $item->nama }}"
                                 class="pegawai-photo-preview"
                                 onerror="this.src='{{ asset('sbadmin2/img/user_kppn.png') }}'">
                        @else
                            <img src="{{ asset('sbadmin2/img/user_kppn.png') }}"
                                 alt="{{ $item->nama }}"
                                 class="pegawai-photo-preview">
                        @endif
                    </div>
                    <h5 class="font-weight-bold text-gray-800 mb-1">{{ $item->nama }}</h5>
                    <p class="text-muted mb-0">{{ $item->jenis_kelamin }} · {{ $item->umur }} Tahun</p>
                </div>

                <div class="bg-light rounded-lg p-3 mb-3">
                    <div class="pegawai-detail-item">
                        <span class="pegawai-detail-label">Tanggal Lahir</span>
                        <span class="pegawai-detail-value">{{ $item->tanggal_lahir->format('d-m-Y') }}</span>
                    </div>
                    <div class="pegawai-detail-item">
                        <span class="pegawai-detail-label">Golongan Darah</span>
                        <span class="pegawai-detail-value">
                            <span class="badge badge-info">{{ $item->gol_darah }}</span>
                        </span>
                    </div>
                    <div class="pegawai-detail-item">
                        <span class="pegawai-detail-label">Riwayat Penyakit</span>
                        <span class="pegawai-detail-value">{{ Str::limit($item->riwayat_penyakit, 40) }}</span>
                    </div>
                </div>

                <div class="alert alert-warning mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Perhatian!</strong> Data yang dihapus tidak dapat dikembalikan.
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="pegawai-modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <form action="{{ route('pegawaiDelete', $item->id) }}" method="post" class="form-delete-pegawai">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-2"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
