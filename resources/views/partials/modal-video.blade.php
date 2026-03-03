{{-- ============================================================
Modal Video — Dashboard e-HATi
Include di dashboard.blade.php (di luar section content):
@include('partials.modal-video')
============================================================ --}}

<div class="modal fade" id="videoModal" tabindex="-1" role="dialog"
aria-labelledby="videoModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
   <div class="modal-content video-modal-content">

        {{-- Header --}}
        <div class="video-modal-header">
            <div class="video-modal-title-wrap">
                <div class="video-modal-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <span class="video-modal-title" id="videoModalLabel">
                    Video Edukasi Kesehatan
                </span>
            </div>
            <button type="button" class="video-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body: Video Player --}}
        <div class="video-modal-body">
            <div class="video-modal-player-wrap">
                <video
                    id="dashboardModalVideo"
                    class="video-modal-player"
                    controls
                    playsinline
                    preload="metadata">
                    {{-- src diisi oleh JS saat modal dibuka --}}
                    Browser Anda tidak mendukung tag video.
                </video>
            </div>
        </div>

        {{-- Footer: Keterangan --}}
        <div class="video-modal-footer">
            <i class="fas fa-info-circle mr-2 text-info"></i>
            <span>Peregangan (Stretching) untuk Pegawai — Tetap Aktif &amp; Sehat di Tempat Kerja</span>
        </div>

    </div>
</div>
</div>
