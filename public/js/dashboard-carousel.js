/**
 * dashboard-carousel.js
 * ============================================================
 * Fitur video carousel dashboard e-HATi:
 *   1. Klik video → pause/play toggle
 *   2. Double-klik → buka modal video besar (Bootstrap modal)
 *   3. Saat modal dibuka: carousel otomatis pause, modal video play
 *   4. Saat modal ditutup: carousel resume, video carousel lanjut
 *
 * Cara pasang di app.blade.php (setelah bootstrap.bundle.min.js):
 *   <script src="{{ asset('js/dashboard-carousel.js') }}"></script>
 *
 * File ini hanya aktif jika elemen #dashboardCarousel ada di halaman.
 * ============================================================
 */

(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var carousel = document.getElementById("dashboardCarousel");
        if (!carousel) return;

        var video = document.getElementById("dashboardVideo");
        var hintIcon = document.getElementById("dashboardVideoHintIcon");

        if (!video) return;

        // ── Set icon awal = play (video mulai dalam keadaan pause) ──
        setHintIcon("play");

        // ── Ambil wrapper video ─────────────────────────────────────
        var videoWrap = video.closest(".dashboard-carousel-video-wrap");
        var clickTarget = videoWrap || video;

        // ── Pause carousel saat mouse di atas slide video ──────────
        var slide1 = video.closest(".carousel-item");
        if (slide1) {
            slide1.addEventListener("mouseenter", function () {
                $(carousel).carousel("pause");
            });
            slide1.addEventListener("mouseleave", function () {
                $(carousel).carousel("cycle");
            });
        }

        // ── Klik: Pause / Play toggle ───────────────────────────────
        clickTarget.addEventListener("click", function (e) {
            e.stopPropagation();
            if (video.paused) {
                video.play();
                setHintIcon("pause");
            } else {
                video.pause();
                setHintIcon("play");
            }
            flashHint();
        });

        // ── Double-klik: Buka modal video besar ─────────────────────
        clickTarget.addEventListener("dblclick", function (e) {
            e.stopPropagation();
            openVideoModal();
        });

        // ── Fungsi buka modal ───────────────────────────────────────
        function openVideoModal() {
            var modalVideo = document.getElementById("dashboardModalVideo");
            if (!modalVideo) return;

            if (!modalVideo.src || modalVideo.src !== video.currentSrc) {
                modalVideo.src = video.currentSrc;
            }
            modalVideo.currentTime = video.currentTime;

            $(carousel).carousel("pause");
            video.pause();
            setHintIcon("play");

            $("#videoModal").modal("show");
        }

        // ── Event Bootstrap modal ───────────────────────────────────
        $("#videoModal").on("shown.bs.modal", function () {
            var modalVideo = document.getElementById("dashboardModalVideo");
            if (modalVideo) modalVideo.play();
        });

        $("#videoModal").on("hide.bs.modal", function () {
            var modalVideo = document.getElementById("dashboardModalVideo");
            if (modalVideo) {
                video.currentTime = modalVideo.currentTime;
                modalVideo.pause();
            }
        });

        $("#videoModal").on("hidden.bs.modal", function () {
            video.play();
            $(carousel).carousel("cycle");
        });

        // ── Helper: update icon hint ─────────────────────────────────
        function setHintIcon(state) {
            if (!hintIcon) return;
            hintIcon.className =
                state === "pause" ? "fas fa-pause" : "fas fa-play";
        }

        // ── Helper: flash hint sebentar ──────────────────────────────
        function flashHint() {
            var hint = document.querySelector(".dashboard-video-hint");
            if (!hint) return;
            hint.style.opacity = "1";
            clearTimeout(hint._flashTimer);
            hint._flashTimer = setTimeout(function () {
                hint.style.opacity = "";
            }, 800);
        }

        // ── Sinkronisasi: saat video play/pause dari luar ───────────
        video.addEventListener("play", function () {
            setHintIcon("pause");
        });
        video.addEventListener("pause", function () {
            setHintIcon("play");
        });
    });
})();
