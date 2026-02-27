/**
 * Pegawai Module - JavaScript Handler
 * File: public/js/pegawai.js
 */

document.addEventListener('DOMContentLoaded', function () {
    PegawaiModule.init();
});

const PegawaiModule = {

    init: function () {
        this.initTableSorting();
        this.initPhotoLightbox();
    },

    // -------------------------------------------------------
    // Lightbox: klik foto profil → tampilkan modal foto besar
    initPhotoLightbox: function () {
        // Buat overlay lightbox sekali, masukkan ke body
        const overlay = document.createElement('div');
        overlay.className = 'pegawai-lightbox-overlay';
        overlay.id = 'pegawaiLightbox';
        overlay.innerHTML = `
            <div class="pegawai-lightbox-box">
                <div class="pegawai-lightbox-header">
                    <span id="pegawaiLightboxName"></span>
                    <button class="pegawai-lightbox-close" id="pegawaiLightboxClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="pegawai-lightbox-img-wrap">
                    <img id="pegawaiLightboxImg" src="" alt="Foto Pegawai">
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        // Tutup saat klik tombol close
        document.getElementById('pegawaiLightboxClose').addEventListener('click', () => {
            this.closeLightbox();
        });

        // Tutup saat klik di luar box
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) this.closeLightbox();
        });

        // Tutup saat tekan Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeLightbox();
        });

        // Pasang event klik di setiap foto
        document.querySelectorAll('.pegawai-photo-thumb').forEach(img => {
            img.addEventListener('click', () => {
                const src  = img.src;
                const name = img.getAttribute('data-name') || img.alt || 'Foto Pegawai';
                this.openLightbox(src, name);
            });
        });

        // Smart direction: hover preview ke atas jika foto dekat batas bawah wrapper
        document.querySelectorAll('.pegawai-photo-wrapper').forEach(wrapper => {
            const preview = wrapper.querySelector('.pegawai-photo-hover-preview');
            if (!preview) return;

            wrapper.addEventListener('mouseenter', () => {
                const tableWrapper = document.querySelector('.pegawai-table-wrapper');
                if (!tableWrapper) return;

                const wrapperRect      = wrapper.getBoundingClientRect();
                const tableWrapperRect = tableWrapper.getBoundingClientRect();
                const spaceBelow       = tableWrapperRect.bottom - wrapperRect.bottom;
                const previewHeight    = 170; // estimasi tinggi preview (px)

                if (spaceBelow < previewHeight + 20) {
                    // Tidak cukup ruang di bawah → tampilkan ke atas
                    preview.classList.add('flip-up');
                } else {
                    preview.classList.remove('flip-up');
                }
            });
        });
    },

    openLightbox: function (src, name) {
        document.getElementById('pegawaiLightboxImg').src  = src;
        document.getElementById('pegawaiLightboxName').textContent = name;
        document.getElementById('pegawaiLightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    closeLightbox: function () {
        document.getElementById('pegawaiLightbox').classList.remove('active');
        document.body.style.overflow = '';
    },

    // -------------------------------------------------------
    initTableSorting: function () {
        const table = document.getElementById('pegawaiTable');
        if (!table) return;

        const headers = table.querySelectorAll('th.sortable');

        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                const sortKey = header.dataset.sort;

                // Toggle arah: jika sudah sort-asc → jadi sort-desc, selain itu → sort-asc
                const isAsc = !header.classList.contains('sort-asc');

                // Reset semua header
                headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));

                // Set state header yang diklik
                header.classList.add(isAsc ? 'sort-asc' : 'sort-desc');

                // Jalankan sort
                this.sortTable(table, sortKey, isAsc);

                // Update nomor urut setelah sort
                this.renumberRows(table);
            });
        });
    },

    // -------------------------------------------------------
    sortTable: function (table, sortKey, isAsc) {
        const tbody = table.querySelector('tbody');
        const rows  = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            let aVal = this.getCellValue(a, sortKey);
            let bVal = this.getCellValue(b, sortKey);

            // Angka
            if (!isNaN(aVal) && !isNaN(bVal)) {
                aVal = parseFloat(aVal);
                bVal = parseFloat(bVal);
            }

            if (aVal < bVal) return isAsc ? -1 :  1;
            if (aVal > bVal) return isAsc ?  1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    },

    // -------------------------------------------------------
    // Ambil nilai dari kolom yang sesuai sortKey
    getCellValue: function (row, sortKey) {
        const cells = row.querySelectorAll('td');

        switch (sortKey) {
            case 'no':
                // Kolom 0 — ambil angka saja
                return parseInt(cells[0].textContent.trim()) || 0;

            case 'nama':
                // Kolom 1 — teks nama saja (abaikan spasi dari foto)
                return cells[1].textContent.trim().toLowerCase();

            case 'jenis_kelamin':
                // Kolom 2
                return cells[2].textContent.trim().toLowerCase();

            case 'tanggal_lahir':
                // Kolom 3 — parse dd-mm-yyyy menjadi Date untuk perbandingan numerik
                return this.parseDate(cells[3].textContent.trim());

            case 'umur':
                // Kolom 4 — ambil angka saja (hilangkan " th")
                return parseInt(cells[4].textContent.trim()) || 0;

            case 'gol_darah':
                // Kolom 5
                return cells[5].textContent.trim().toLowerCase();

            default:
                return '';
        }
    },

    // -------------------------------------------------------
    // Parse tanggal format dd-mm-yyyy → timestamp angka
    parseDate: function (dateStr) {
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            // new Date(yyyy, mm-1, dd)
            return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
        }
        return 0;
    },

    // -------------------------------------------------------
    // Update nomor urut kolom # setelah sort
    renumberRows: function (table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            const noCell = row.querySelector('td:first-child');
            if (noCell) noCell.textContent = index + 1;
        });
    }
};
