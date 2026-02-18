/**
 * Riwayat Pemeriksaan - JavaScript Handler
 * File: public/js/riwayat.js
 * Description: Handle modal detail, delete confirmation, dan print functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Riwayat Module
    RiwayatModule.init();
});

const RiwayatModule = {
    /**
     * Initialize all event listeners
     */
    init: function() {
        this.initDetailModal();
        this.initDeleteConfirmation();
        this.initPrintButton();
    },

    /**
     * Detail Modal AJAX Handler
     */
    initDetailModal: function() {
        const modal = document.getElementById('detailRiwayatModal');
        if (!modal) return;

        $('#detailRiwayatModal').on('show.bs.modal', function(e) {
            const btn = $(e.relatedTarget);
            const id = btn.data('id');
            const body = document.getElementById('detailRiwayatBody');

            // Show loading state
            body.innerHTML = RiwayatTemplates.loadingState();

            // Fetch data
            fetch('/pemeriksaan/riwayat/' + id)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data || data.error) {
                        body.innerHTML = RiwayatTemplates.errorState('Data tidak ditemukan.');
                        return;
                    }
                    body.innerHTML = RiwayatTemplates.detailContent(data);
                })
                .catch(error => {
                    console.error('Error fetching detail:', error);
                    body.innerHTML = RiwayatTemplates.errorState('Gagal memuat data. Silakan coba lagi.');
                });
        });
    },

    /**
     * Delete Confirmation with SweetAlert
     */
    initDeleteConfirmation: function() {
        document.querySelectorAll('.form-delete-riwayat').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const theForm = this;

                Swal.fire({
                    title: 'Hapus data ini?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        theForm.submit();
                    }
                });
            });
        });
    },

    /**
     * Print Button Handler
     */
    initPrintButton: function() {
        const printBtn = document.getElementById('btnPrintRiwayat');
        if (!printBtn) return;

        printBtn.addEventListener('click', function() {
            const modalBody = document.getElementById('detailRiwayatBody');
            const printContent = modalBody.innerHTML;

            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(RiwayatTemplates.printPage(printContent));
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        });
    }
};

/**
 * HTML Templates for Riwayat Module
 */
const RiwayatTemplates = {
    /**
     * Loading State HTML
     */
    loadingState: function() {
        return `
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-3 mb-0">Memuat data pemeriksaan...</p>
            </div>
        `;
    },

    /**
     * Error State HTML
     */
    errorState: function(message) {
        return `
            <div class="text-center text-danger py-5">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle fa-3x"></i>
                </div>
                <h6 class="font-weight-bold">Oops!</h6>
                <p class="mb-0">${message}</p>
            </div>
        `;
    },

    /**
     * Format number with fallback
     */
    formatNumber: function(value, decimals = 1) {
        if (value === null || value === undefined || value === '') return '-';
        const num = parseFloat(value);
        if (isNaN(num)) return '-';
        return num.toFixed(decimals);
    },

    /**
     * Format date to Indonesian format
     */
    formatDate: function(dateString) {
        if (!dateString) return '-';
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    },

    /**
     * Calculate BMI
     */
    calculateBMI: function(tinggi, berat) {
        if (!tinggi || !berat || tinggi <= 0 || berat <= 0) return null;
        return (berat / Math.pow(tinggi / 100, 2)).toFixed(1);
    },

    /**
     * Get BMI Badge HTML
     */
    getBMIBadge: function(bmi) {
        if (!bmi || bmi === '-') return '';
        const bmiValue = parseFloat(bmi);
        if (bmiValue < 18.5) {
            return '<span class="ehati-badge ehati-badge-info">Kurus</span>';
        } else if (bmiValue <= 24.9) {
            return '<span class="ehati-badge ehati-badge-normal">Normal</span>';
        } else if (bmiValue <= 29.9) {
            return '<span class="ehati-badge ehati-badge-warning">Overweight</span>';
        } else {
            return '<span class="ehati-badge ehati-badge-danger">Obesitas</span>';
        }
    },

    /**
     * Get Blood Pressure Badge HTML
     */
    getBPBadge: function(sistolik, diastolik) {
        if (!sistolik || !diastolik) return '';
        if (sistolik < 120 && diastolik < 80) {
            return '<span class="ehati-badge ehati-badge-normal">Normal</span>';
        } else if (sistolik < 140 || diastolik < 90) {
            return '<span class="ehati-badge ehati-badge-warning">Tinggi</span>';
        } else {
            return '<span class="ehati-badge ehati-badge-danger">Hipertensi</span>';
        }
    },

    /**
     * Get Cholesterol Badge HTML
     */
    getCholBadge: function(kolesterol) {
        if (!kolesterol) return '';
        if (kolesterol < 200) {
            return '<span class="ehati-badge ehati-badge-normal">Normal</span>';
        } else if (kolesterol <= 239) {
            return '<span class="ehati-badge ehati-badge-warning">Borderline</span>';
        } else {
            return '<span class="ehati-badge ehati-badge-danger">Tinggi</span>';
        }
    },

    /**
     * Detail Content HTML
     */
    detailContent: function(data) {
        const pegawai = data.pegawai ? data.pegawai.nama : '-';
        const tgl = this.formatDate(data.tanggal_pemeriksaan);
        const bmi = this.calculateBMI(data.tinggi_badan, data.berat_badan);
        const catatanDokter = data.catatan_dokter ? data.catatan_dokter.replace(/\n/g, '<br>') : '-';

        return `
            <!-- Header Info -->
            <div class="ehati-detail-section">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="ehati-detail-label">
                            <i class="fas fa-user mr-1 text-info"></i> Nama Pegawai
                        </label>
                        <p class="ehati-detail-value">${pegawai}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="ehati-detail-label">
                            <i class="fas fa-calendar mr-1 text-success"></i> Tanggal Pemeriksaan
                        </label>
                        <p class="ehati-detail-value">${tgl}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="ehati-detail-label">
                            <i class="fas fa-utensils mr-1 text-warning"></i> Status Puasa
                        </label>
                        <p class="ehati-detail-value">
                            <span class="ehati-status-badge ${data.puasa == 1 ? 'puasa' : 'tidak-puasa'}">
                                ${data.puasa == 1 ? 'Puasa' : 'Tidak Puasa'}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <hr class="ehati-divider">

            <!-- BMI Section -->
            <div class="ehati-detail-section">
                <h6 class="ehati-section-title">
                    <span class="ehati-section-icon bg-blue"><i class="fas fa-weight"></i></span>
                    Body Mass Index (BMI)
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Tinggi Badan</label>
                        <p class="ehati-detail-value">${this.formatNumber(data.tinggi_badan, 1)} <small>cm</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Berat Badan</label>
                        <p class="ehati-detail-value">${this.formatNumber(data.berat_badan, 1)} <small>kg</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">BMI</label>
                        <p class="ehati-detail-value">
                            ${bmi || '-'} ${bmi ? this.getBMIBadge(bmi) : ''}
                        </p>
                    </div>
                </div>
            </div>

            <hr class="ehati-divider">

            <!-- Blood Pressure Section -->
            <div class="ehati-detail-section">
                <h6 class="ehati-section-title">
                    <span class="ehati-section-icon bg-red"><i class="fas fa-heartbeat"></i></span>
                    Tekanan Darah & Denyut Nadi
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Sistolik</label>
                        <p class="ehati-detail-value">${data.sistolik || '-'} <small>mmHg</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Diastolik</label>
                        <p class="ehati-detail-value">${data.diastolik || '-'} <small>mmHg</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Denyut Nadi</label>
                        <p class="ehati-detail-value">${data.nadi || '-'} <small>bpm</small></p>
                    </div>
                </div>
                ${data.sistolik && data.diastolik ? `
                <div class="mt-2">
                    <span class="text-muted small">Klasifikasi:</span>
                    ${this.getBPBadge(data.sistolik, data.diastolik)}
                </div>
                ` : ''}
            </div>

            <hr class="ehati-divider">

            <!-- Lab Results Section -->
            <div class="ehati-detail-section">
                <h6 class="ehati-section-title">
                    <span class="ehati-section-icon bg-amber"><i class="fas fa-flask"></i></span>
                    Hasil Laboratorium
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">
                            <i class="fas fa-tint mr-1 text-danger"></i> Gula Darah
                        </label>
                        <p class="ehati-detail-value">
                            ${this.formatNumber(data.nilai_glukometer, 1)} <small>mg/dL</small>
                            ${data.parameter_gula ? `<span class="text-muted">(${data.parameter_gula})</span>` : ''}
                        </p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">
                            <i class="fas fa-vial mr-1 text-success"></i> Kolesterol Total
                        </label>
                        <p class="ehati-detail-value">
                            ${this.formatNumber(data.kolesterol_total, 1)} <small>mg/dL</small>
                            ${data.kolesterol_total ? this.getCholBadge(data.kolesterol_total) : ''}
                        </p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">
                            <i class="fas fa-vial mr-1 text-info"></i> Asam Urat
                        </label>
                        <p class="ehati-detail-value">
                            ${this.formatNumber(data.asam_urat, 1)} <small>mg/dL</small>
                        </p>
                    </div>
                </div>
            </div>

            ${data.catatan_dokter ? `
            <hr class="ehati-divider">

            <!-- Doctor Notes Section -->
            <div class="ehati-detail-section">
                <h6 class="ehati-section-title">
                    <span class="ehati-section-icon bg-teal"><i class="fas fa-clipboard"></i></span>
                    Catatan Dokter
                </h6>
                <div class="ehati-notes-box">
                    ${catatanDokter}
                </div>
            </div>
            ` : ''}
        `;
    },

    /**
     * Print Page Template
     */
    printPage: function(content) {
        return `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Detail Pemeriksaan - e-HATi</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        padding: 30px;
                        color: #333;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 25px;
                        padding-bottom: 15px;
                        border-bottom: 2px solid #36b9cc;
                    }
                    .print-header h4 {
                        color: #36b9cc;
                        margin-bottom: 5px;
                    }
                    .print-header p {
                        color: #666;
                        font-size: 12px;
                        margin-bottom: 0;
                    }
                    .ehati-detail-section { margin-bottom: 20px; }
                    .ehati-detail-label {
                        font-size: 11px;
                        color: #666;
                        margin-bottom: 2px;
                        text-transform: uppercase;
                    }
                    .ehati-detail-value {
                        font-size: 14px;
                        font-weight: 600;
                        margin-bottom: 0;
                    }
                    .ehati-section-title {
                        font-size: 13px;
                        font-weight: 700;
                        color: #333;
                        margin-bottom: 12px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .ehati-section-icon {
                        width: 26px;
                        height: 26px;
                        border-radius: 6px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        color: #fff;
                    }
                    .bg-blue { background: linear-gradient(135deg, #4e73df, #224abe); }
                    .bg-red { background: linear-gradient(135deg, #e74a3b, #be2617); }
                    .bg-amber { background: linear-gradient(135deg, #f6c23e, #dda20a); }
                    .bg-teal { background: linear-gradient(135deg, #36b9cc, #1cc88a); }
                    .ehati-divider {
                        border: none;
                        border-top: 1px dashed #ddd;
                        margin: 15px 0;
                    }
                    .ehati-status-badge {
                        display: inline-block;
                        padding: 3px 10px;
                        border-radius: 12px;
                        font-size: 11px;
                        font-weight: 600;
                    }
                    .puasa { background: #d4edda; color: #155724; }
                    .tidak-puasa { background: #fff3cd; color: #856404; }
                    .ehati-notes-box {
                        background: #f8f9fa;
                        border-left: 3px solid #36b9cc;
                        padding: 12px 15px;
                        border-radius: 0 8px 8px 0;
                        font-size: 13px;
                        line-height: 1.6;
                    }
                    .ehati-badge {
                        display: inline-block;
                        padding: 2px 8px;
                        border-radius: 10px;
                        font-size: 10px;
                        font-weight: 600;
                        margin-left: 5px;
                    }
                    .ehati-badge-normal { background: #d4edda; color: #155724; }
                    .ehati-badge-warning { background: #fff3cd; color: #856404; }
                    .ehati-badge-danger { background: #f8d7da; color: #721c24; }
                    .ehati-badge-info { background: #d1ecf1; color: #0c5460; }
                    @media print {
                        body { padding: 15px; }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h4><i class="fas fa-file-medical mr-2"></i>Detail Pemeriksaan Kesehatan</h4>
                    <p>e-HATi (Employee Health Information) - KPPN Pangkalan Bun</p>
                </div>
                ${content}
                <div class="print-footer mt-4 pt-3" style="border-top: 1px solid #ddd; text-align: center; font-size: 11px; color: #999;">
                    <p class="mb-0">Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                </div>
            </body>
            </html>
        `;
    }
};
