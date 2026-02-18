<!-- Modal -->
<div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel">Hapus {{ $title }} ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <div class="row">
                    <div class="col-6">
                        Nama
                    </div>
                    <div class="col-6">
                        : {{ $item->nama }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Jenis Kelamin
                    </div>
                    <div class="col-6">
                        : {{ $item->jenis_kelamin }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Tanggal Lahir
                    </div>
                    <div class="col-6">
                        : {{ $item->tanggal_lahir->format('d-m-Y') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Umur
                    </div>
                    <div class="col-6">
                        : {{ $item->umur }} Tahun
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Golongan Darah
                    </div>
                    <div class="col-6">
                        : {{ $item->gol_darah }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Riwayat Penyakit
                    </div>
                    <div class="col-6">
                        : {{ $item->riwayat_penyakit }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>
                    Tutup</button>
                <form action="{{ route('pegawaiDelete', $item->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1"></i>
                        Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>