{{-- Detail Modal Tabel Tekanan Darah --}}
<div class="modal fade" id="GulaDarahTableModal" tabindex="-1" role="dialog" aria-labelledby="GulaDarahTableModal"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content ehati-modal-content">

            {{-- Modal Header --}}
            <div class="ehati-modal-header p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="GulaDarahTableModalLabel">
                    <i class="fas fa-table mr-2"></i>Tabel Klasifikasi Gula Darah
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-3 text-center bg-light">
                {{-- Menggunakan helper asset() Laravel 12 untuk memanggil gambar dari public/ --}}
                {{-- Class img-fluid memastikan gambar responsif dan tidak keluar batas modal --}}
                <img src="{{ asset('sbadmin2/img/gula-darah.jpg') }}" alt="Tabel Gula Darah"
                    class="img-fluid rounded shadow-sm" style="max-height: 75vh; object-fit: contain;">
            </div>

            {{-- Modal Footer --}}
            <div class="ehati-modal-footer p-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    e-HATi - Employee Health Information
                </small>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                </div>
            </div>

        </div>
    </div>
</div>
