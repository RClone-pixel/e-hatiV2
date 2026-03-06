/**
 * Riwayat Pemeriksaan - JavaScript Handler
 * File: public/js/riwayat.js
 *
 * Badge klasifikasi disinkronkan dengan 5 kalkulator:
 *   bmi.js           → getBMIBadge()  — 8 level
 *   bloodpressure.js → getBPBadge()   — 7 level
 *   bloodsugar.js    → getBSBadge()   — 8 level + per parameter GDP/GD2PP/GDS
 *   cholesterol.js   → getCholBadge() — 5 level
 *   uricacid.js      → getUABadge()   — 4 level per gender
 */

document.addEventListener("DOMContentLoaded", function () {
  RiwayatModule.init();
});

const RiwayatModule = {
  init: function () {
    this.initDetailModal();
    this.initDeleteConfirmation();
    this.initPrintButton();
  },

  initDetailModal: function () {
    const modal = document.getElementById("detailRiwayatModal");
    if (!modal) return;

    $("#detailRiwayatModal").on("show.bs.modal", function (e) {
      const btn = $(e.relatedTarget);
      const id = btn.data("id");
      const body = document.getElementById("detailRiwayatBody");

      body.innerHTML = RiwayatTemplates.loadingState();

      fetch("/pemeriksaan/riwayat/" + id)
        .then((response) => {
          if (!response.ok) throw new Error("Network response was not ok");
          return response.json();
        })
        .then((data) => {
          if (!data || data.error) {
            body.innerHTML = RiwayatTemplates.errorState(
              "Data tidak ditemukan.",
            );
            return;
          }
          body.innerHTML = RiwayatTemplates.detailContent(data);
        })
        .catch((error) => {
          console.error("Error fetching detail:", error);
          body.innerHTML = RiwayatTemplates.errorState(
            "Gagal memuat data. Silakan coba lagi.",
          );
        });
    });
  },

  initDeleteConfirmation: function () {
    document.querySelectorAll(".form-delete-riwayat").forEach((form) => {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        const theForm = this;
        Swal.fire({
          title: "Hapus data ini?",
          text: "Data yang dihapus tidak dapat dikembalikan!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#e74a3b",
          cancelButtonColor: "#858796",
          confirmButtonText: "Ya, Hapus!",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) theForm.submit();
        });
      });
    });
  },

  initPrintButton: function () {
    const printBtn = document.getElementById("btnPrintRiwayat");
    if (!printBtn) return;
    printBtn.addEventListener("click", function () {
      const modalBody = document.getElementById("detailRiwayatBody");
      const printContent = modalBody.innerHTML;
      const printWindow = window.open("", "_blank", "width=800,height=600");
      printWindow.document.write(RiwayatTemplates.printPage(printContent));
      printWindow.document.close();
      printWindow.focus();
      setTimeout(() => {
        printWindow.print();
        printWindow.close();
      }, 250);
    });
  },
};

/* ================================================================
   TEMPLATES & CLASSIFIER
   ================================================================ */
const RiwayatTemplates = {
  /* ── Helpers ── */
  loadingState: function () {
    return `
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status" style="width:3rem;height:3rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-3 mb-0">Memuat data pemeriksaan...</p>
            </div>`;
  },

  errorState: function (message) {
    return `
            <div class="text-center text-danger py-5">
                <div class="mb-3"><i class="fas fa-exclamation-circle fa-3x"></i></div>
                <h6 class="font-weight-bold">Oops!</h6>
                <p class="mb-0">${message}</p>
            </div>`;
  },

  formatNumber: function (value, decimals = 1) {
    if (value === null || value === undefined || value === "") return "-";
    const num = parseFloat(value);
    return isNaN(num) ? "-" : num.toFixed(decimals);
  },

  formatDate: function (dateString) {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  },

  calculateBMI: function (tinggi, berat) {
    if (!tinggi || !berat || tinggi <= 0 || berat <= 0) return null;
    return (berat / Math.pow(tinggi / 100, 2)).toFixed(1);
  },

  badge: function (cls, label) {
    return `<span class="ehati-badge ehati-badge-${cls}">${label}</span>`;
  },

  /* ================================================================
       BMI — sync bmi.js classifyBMI()
       -3: < 16  | -2: 16–16.9 | -1: 17–18.4 | 0: 18.5–24.9
        1: 25–29.9 |  2: 30–34.9 |  3: 35–39.9 | 4: >= 40
       ================================================================ */
  getBMIBadge: function (bmi) {
    if (!bmi || bmi === "-") return "";
    const v = parseFloat(bmi);
    if (v < 16) return this.badge("danger", "Kkrg III");
    if (v < 17) return this.badge("danger", "Kkrg II");
    if (v < 18.5) return this.badge("warning", "Kkrg I");
    if (v < 25) return this.badge("normal", "Normal");
    if (v < 30) return this.badge("warning", "Kelebihan");
    if (v < 35) return this.badge("danger", "Obesitas I");
    if (v < 40) return this.badge("danger", "Obesitas II");
    return this.badge("danger", "Obesitas III");
  },

  /* ================================================================
       TEKANAN DARAH — sync bloodpressure.js classifyBP()
       Zona atas (tertinggi duluan) → zona bawah → normal
       ================================================================ */
  getBPBadge: function (sistolik, diastolik) {
    if (!sistolik || !diastolik) return "";
    const s = parseInt(sistolik),
      d = parseInt(diastolik);
    if (s > 180 || d > 120) return this.badge("danger", "Krisis HT");
    if (s >= 160 || d >= 100) return this.badge("danger", "HT Drj 2");
    if (s >= 140 || d >= 90) return this.badge("danger", "HT Drj 1");
    if (s >= 120 || d >= 80) return this.badge("warning", "Pre-HT");
    if (s < 70 || d < 40) return this.badge("danger", "Krisis Hipo");
    if (s < 90 || d < 60) return this.badge("info", "Hipotensi");
    return this.badge("normal", "Normal");
  },

  /* ================================================================
       GULA DARAH — sync bloodsugar.js classifyBS()
       Zona bawah & atas sama semua parameter.
       Zona tengah (70–249) beda per GDP / GD2PP / GDS.
       ================================================================ */
  getBSBadge: function (nilai, parameter) {
    if (!nilai || !parameter) return "";
    const ng = parseFloat(nilai);
    const pg = (parameter || "").toUpperCase();

    // Zona bawah
    if (ng < 40) return this.badge("danger", "Hipo Kritis");
    if (ng < 54) return this.badge("danger", "Hipo Lvl 2");
    if (ng < 70) return this.badge("info", "Hipo Lvl 1");
    // Zona atas
    if (ng >= 600) return this.badge("danger", "Krisis Hiper");
    if (ng >= 250) return this.badge("danger", "Hiper Berat");
    // Zona tengah per parameter (70–249)
    if (pg === "GDP") {
      if (ng <= 109) return this.badge("normal", "Normal");
      if (ng <= 125) return this.badge("warning", "Prediabetes");
      return this.badge("danger", "Diabetes");
    }
    if (pg === "GD2PP") {
      if (ng <= 139) return this.badge("normal", "Normal");
      if (ng <= 199) return this.badge("warning", "Prediabetes");
      return this.badge("danger", "Diabetes");
    }
    // GDS (default)
    if (ng <= 179) return this.badge("normal", "Normal");
    if (ng <= 199) return this.badge("warning", "Waspada");
    return this.badge("danger", "Diabetes");
  },

  /* ================================================================
       KOLESTEROL — sync cholesterol.js classifyChol()
       < 120 Rendah | 120–199 Normal | 200–239 Ambang Batas
       240–299 Tinggi | >= 300 Sangat Tinggi
       Pakai < 240 (bukan <= 239) sesuai komentar JS: "kebal desimal"
       ================================================================ */
  getCholBadge: function (kolesterol) {
    if (!kolesterol) return "";
    const v = parseFloat(kolesterol);
    if (v < 120) return this.badge("warning", "Rendah");
    if (v < 200) return this.badge("normal", "Normal");
    if (v < 240) return this.badge("warning", "Ambang Batas");
    if (v < 300) return this.badge("danger", "Tinggi");
    return this.badge("danger", "Sangat Tinggi");
  },

  /* ================================================================
       ASAM URAT — sync uricacid.js classifyUA()
       RUJUKAN: laki { low: 3.5, high: 7.2 }  |  perempuan { low: 2.6, high: 6.0 }
       -1 Rendah | 0 Normal | 1 Tinggi (≤ high+2) | 2 Sangat Tinggi
       ================================================================ */
  getUABadge: function (asamUrat, jenisKelamin) {
    if (!asamUrat) return "";
    const jk = (jenisKelamin || "").toLowerCase();
    const laki = jk.includes("laki");
    const low = laki ? 3.5 : 2.6;
    const high = laki ? 7.2 : 6.0;
    const ua = parseFloat(asamUrat);
    if (ua < low) return this.badge("info", "Rendah");
    if (ua <= high) return this.badge("normal", "Normal");
    if (ua <= high + 2.0) return this.badge("warning", "Tinggi");
    return this.badge("danger", "Sangat Tinggi");
  },

  /* ================================================================
       DETAIL CONTENT — HTML template untuk modal body
       ================================================================ */
  detailContent: function (data) {
    const pegawai = data.pegawai ? data.pegawai.nama : "-";
    const jenisKelamin = data.pegawai ? data.pegawai.jenis_kelamin || "" : "";
    const tgl = this.formatDate(data.tanggal_pemeriksaan);
    const bmi = this.calculateBMI(data.tinggi_badan, data.berat_badan);
    const catatanDokter = data.catatan_dokter
      ? data.catatan_dokter.replace(/\n/g, "<br>")
      : "-";

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
                            <span class="ehati-status-badge ${data.puasa == 1 ? "puasa" : "tidak-puasa"}">
                                ${data.puasa == 1 ? "Puasa" : "Tidak Puasa"}
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
                            ${bmi || "-"} ${bmi ? this.getBMIBadge(bmi) : ""}
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
                        <p class="ehati-detail-value">${data.sistolik || "-"} <small>mmHg</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Diastolik</label>
                        <p class="ehati-detail-value">${data.diastolik || "-"} <small>mmHg</small></p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">Denyut Nadi</label>
                        <p class="ehati-detail-value">${data.nadi || "-"} <small>bpm</small></p>
                    </div>
                </div>
                ${
                  data.sistolik && data.diastolik
                    ? `
                <div class="mt-2">
                    <span class="text-muted small">Klasifikasi:</span>
                    ${this.getBPBadge(data.sistolik, data.diastolik)}
                </div>`
                    : ""
                }
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
                            ${this.formatNumber(data.konsentrasi_glukosa, 1)} <small>mg/dL</small>
                            ${data.parameter_gula ? `<span class="text-muted">(${data.parameter_gula})</span>` : ""}
                        </p>
                        ${
                          data.konsentrasi_glukosa && data.parameter_gula
                            ? `
                        <div class="mt-1">
                            <span class="text-muted small">Klasifikasi:</span>
                            ${this.getBSBadge(data.konsentrasi_glukosa, data.parameter_gula)}
                        </div>`
                            : ""
                        }
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">
                            <i class="fas fa-vial mr-1 text-success"></i> Kolesterol Total
                        </label>
                        <p class="ehati-detail-value">
                            ${this.formatNumber(data.kolesterol_total, 1)} <small>mg/dL</small>
                        </p>
                        ${
                          data.kolesterol_total
                            ? `
                        <div class="mt-1">
                            <span class="text-muted small">Klasifikasi:</span>
                            ${this.getCholBadge(data.kolesterol_total)}
                        </div>`
                            : ""
                        }
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="ehati-detail-label">
                            <i class="fas fa-vial mr-1 text-info"></i> Asam Urat
                        </label>
                        <p class="ehati-detail-value">
                            ${this.formatNumber(data.asam_urat, 1)} <small>mg/dL</small>
                        </p>
                        ${
                          data.asam_urat
                            ? `
                        <div class="mt-1">
                            <span class="text-muted small">Klasifikasi:</span>
                            ${this.getUABadge(data.asam_urat, jenisKelamin)}
                        </div>`
                            : ""
                        }
                    </div>
                </div>
            </div>

            ${
              data.catatan_dokter
                ? `
            <hr class="ehati-divider">
            <div class="ehati-detail-section">
                <h6 class="ehati-section-title">
                    <span class="ehati-section-icon bg-teal"><i class="fas fa-clipboard"></i></span>
                    Catatan Dokter
                </h6>
                <div class="ehati-notes-box">
                    ${catatanDokter}
                </div>
            </div>`
                : ""
            }
        `;
  },

  /* ================================================================
       PRINT PAGE — inline CSS supaya badge muncul di print preview
       ================================================================ */
  printPage: function (content) {
    return `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Detail Pemeriksaan - e-HATi</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
                <style>
                    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; color: #333; }
                    .print-header { text-align:center; margin-bottom:25px; padding-bottom:15px; border-bottom:2px solid #36b9cc; }
                    .print-header h4 { color:#36b9cc; margin-bottom:5px; }
                    .print-header p  { color:#666; font-size:12px; margin-bottom:0; }
                    .ehati-detail-section { margin-bottom:20px; }
                    .ehati-detail-label   { font-size:11px; color:#666; margin-bottom:2px; text-transform:uppercase; display:block; }
                    .ehati-detail-value   { font-size:14px; font-weight:600; margin-bottom:0; }
                    .ehati-section-title  { font-size:13px; font-weight:700; color:#333; margin-bottom:12px; display:flex; align-items:center; gap:8px; }
                    .ehati-section-icon   { width:26px; height:26px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; color:#fff; }
                    .bg-blue  { background:linear-gradient(135deg,#4e73df,#224abe); }
                    .bg-red   { background:linear-gradient(135deg,#e74a3b,#be2617); }
                    .bg-amber { background:linear-gradient(135deg,#f6c23e,#dda20a); }
                    .bg-teal  { background:linear-gradient(135deg,#36b9cc,#1cc88a); }
                    .ehati-divider { border:none; border-top:1px dashed #ddd; margin:15px 0; }
                    .ehati-status-badge { display:inline-block; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; }
                    .puasa       { background:#d4edda; color:#155724; }
                    .tidak-puasa { background:#fff3cd; color:#856404; }
                    .ehati-notes-box { background:#f8f9fa; border-left:3px solid #36b9cc; padding:12px 15px; border-radius:0 8px 8px 0; font-size:13px; line-height:1.6; }
                    /* Badge — semua variant */
                    .ehati-badge         { display:inline-block; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:700; margin-left:4px; }
                    .ehati-badge-normal  { background:#d4edda; color:#155724; }
                    .ehati-badge-warning { background:#fff3cd; color:#856404; }
                    .ehati-badge-danger  { background:#f8d7da; color:#721c24; }
                    .ehati-badge-info    { background:#d1ecf1; color:#0c5460; }
                    @media print { body { padding:15px; } }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h4>Detail Pemeriksaan Kesehatan</h4>
                    <p>e-HATi (Employee Health Information) &mdash; KPPN Pangkalan Bun</p>
                </div>
                ${content}
                <div class="mt-4 pt-3" style="border-top:1px solid #ddd;text-align:center;font-size:11px;color:#999;">
                    <p class="mb-0">Dicetak pada: ${new Date().toLocaleString("id-ID")}</p>
                </div>
            </body>
            </html>
        `;
  },
};
