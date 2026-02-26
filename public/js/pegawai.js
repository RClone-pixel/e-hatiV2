/**
 * Pegawai Module - JavaScript Handler
 * File: public/js/pegawai.js
 * Description: Handle table sorting and interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    PegawaiModule.init();
});

const PegawaiModule = {
    init: function() {
        this.initTableSorting();
    },

    /**
     * Initialize Table Sorting
     */
    initTableSorting: function() {
        const table = document.getElementById('pegawaiTable');
        if (!table) return;

        const headers = table.querySelectorAll('th.sortable');
        headers.forEach(header => {
            header.addEventListener('click', () => {
                const sortKey = header.dataset.sort;
                const isAsc = !header.classList.contains('sort-asc');

                // Remove sort classes from all headers
                headers.forEach(h => {
                    h.classList.remove('sort-asc', 'sort-desc');
                });

                // Add sort class to current header
                header.classList.add(isAsc ? 'sort-asc' : 'sort-desc');

                // Sort the table
                this.sortTable(table, sortKey, isAsc);
            });
        });
    },

    /**
     * Sort Table Rows
     */
    sortTable: function(table, sortKey, isAsc) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            let aVal, bVal;

            switch(sortKey) {
                case 'no':
                    aVal = parseInt(a.cells[0].textContent.trim());
                    bVal = parseInt(b.cells[0].textContent.trim());
                    break;
                case 'nama':
                    aVal = a.cells[1].textContent.trim().toLowerCase();
                    bVal = b.cells[1].textContent.trim().toLowerCase();
                    break;
                case 'jenis_kelamin':
                    aVal = a.cells[2].textContent.trim();
                    bVal = b.cells[2].textContent.trim();
                    break;
                case 'tanggal_lahir':
                    aVal = this.parseDate(a.cells[3].textContent.trim());
                    bVal = this.parseDate(b.cells[3].textContent.trim());
                    break;
                case 'umur':
                    aVal = parseInt(a.cells[4].textContent.trim());
                    bVal = parseInt(b.cells[4].textContent.trim());
                    break;
                case 'gol_darah':
                    aVal = a.cells[5].textContent.trim();
                    bVal = b.cells[5].textContent.trim();
                    break;
                default:
                    return 0;
            }

            if (aVal < bVal) return isAsc ? -1 : 1;
            if (aVal > bVal) return isAsc ? 1 : -1;
            return 0;
        });

        // Re-append rows in sorted order
        rows.forEach(row => tbody.appendChild(row));
    },

    /**
     * Parse Date from dd-mm-yyyy format
     */
    parseDate: function(dateStr) {
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }
        return new Date(0);
    }
};
