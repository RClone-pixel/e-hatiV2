{{-- ============================================================
    MODAL DETAIL RIWAYAT - Resources/views/admin/pemeriksaan/modal-detail.blade.php
    ============================================================ --}}

{{-- Detail Modal --}}
<div class="modal fade" id="detailRiwayatModal" tabindex="-1" role="dialog" aria-labelledby="detailRiwayatModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ehati-modal-content">
            {{-- Modal Header --}}
            <div class="ehati-modal-header">
                <h5 class="modal-title" id="detailRiwayatModalLabel">
                    <i class="fas fa-file-medical mr-2"></i>Detail Pemeriksaan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4" id="detailRiwayatBody">
                {{-- Content will be loaded via AJAX --}}
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Memuat data pemeriksaan...</p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="ehati-modal-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    e-HATi - Employee Health Information
                </small>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="button" class="ehati-btn-print btn-sm" id="btnPrintRiwayat">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
