// --- BAGIAN KALKULATOR GULA DARAH ---
document.addEventListener('DOMContentLoaded', () => {
const inputParameter  = document.getElementById('parameter_gula');
const inputGlukometer = document.getElementById('konsentrasi_glukosa');
const bsStatusEl      = document.getElementById('bs-status');
const bsCardEl        = document.getElementById('bs-card');
const bsAdviceEl      = document.getElementById('bs-advice');
const bsIconEl        = document.getElementById('bs-icon');
const bsReadingEl     = document.getElementById('bs-reading');
const bsRangeEl       = document.getElementById('bs-range');
const bsTitleEl       = document.getElementById('bs-title');
const bsClassLabel    = document.getElementById('bs-class-label');

const BATAS = {
    GULA_MIN: 30,   // mg/dL
    GULA_MAX: 600   // mg/dL
};

// Event Listeners (real-time calculation)
if (inputGlukometer) {
    inputGlukometer.addEventListener('input', calculateBS);
}
if (inputParameter) {
    inputParameter.addEventListener('change', function () {
        updateTitle();
        calculateBS();
    });
}

// -------------------------------------------------------
function updateTitle() {
    if (!bsTitleEl || !inputParameter) return;
    const titleMap = {
        'GDS'  : 'Gula Darah Sewaktu (GDS)',
        'GDP'  : 'Gula Darah Puasa (GDP)',
        'GD2PP': 'Gula Darah 2 Jam PP (GD2PP)'
    };
    let titleText = titleMap[inputParameter.value] || 'Gula Darah';
    bsTitleEl.innerHTML = `Analisis <span class="text-primary">${titleText}</span>`;
}

// -------------------------------------------------------
function calculateBS() {
    let nilai     = parseFloat(inputGlukometer.value);
    let parameter = inputParameter ? inputParameter.value : '';
    let inputEl   = inputGlukometer;

    // Kosong atau belum pilih parameter — reset biasa
    if (!inputEl.value || isNaN(nilai) || !parameter) {
        resetBSUI();
        return;
    }

    // Di luar batas logis — tampilkan Tidak Valid
    if (nilai < BATAS.GULA_MIN || nilai > BATAS.GULA_MAX) {
        showInvalidUI(nilai);
        return;
    }

    // Valid — proses klasifikasi
    let category = classifyBS(parameter, nilai);
    updateBSUI(category, nilai, parameter);
}

// -------------------------------------------------------
// -------------------------------------------------------
// Klasifikasi Gula Darah
// Sumber: WHO, ADA + konsultasi dokter
//
// [ZONA BAWAH — berlaku semua parameter]
// Hipoglikemia Kritis  : < 40  mg/dL
// Hipoglikemia Level 2 : 40–53 mg/dL
// Hipoglikemia Level 1 : 54–69 mg/dL
//
// [ZONA TENGAH — spesifik per parameter]
// GDP   : Normal 70–99   | Prediabetes 100–125 | Diabetes 126–249
// GD2PP : Normal 70–139  | Prediabetes 140–199 | Diabetes 200–249
// GDS   : Normal 70–179  | Waspada 180–199     | Diabetes 200–249
//
// [ZONA ATAS — berlaku semua parameter]
// Hiperglikemia Berat  : 250–599 mg/dL
// Krisis Hiperglikemia : >= 600 mg/dL
function classifyBS(parameter, nilai) {

    // --- ZONA BAWAH (sama untuk semua parameter) ---
    if (nilai < 40)  return { status: 'Hipoglikemia Kritis',  level: -3 }; // < 40
    if (nilai < 54)  return { status: 'Hipoglikemia Level 2', level: -2 }; // 40–53
    if (nilai < 70)  return { status: 'Hipoglikemia Level 1', level: -1 }; // 54–69

    // --- ZONA ATAS (sama untuk semua parameter) ---
    if (nilai >= 600) return { status: 'Krisis Hiperglikemia',  level: 4 }; // >= 600
    if (nilai >= 250) return { status: 'Hiperglikemia Berat',   level: 3 }; // 250–599

    // --- ZONA TENGAH (spesifik per parameter, nilai sudah 70–249) ---
    switch (parameter) {
        case 'GDP': // Gula Darah Puasa
            if (nilai <= 109) return { status: 'Normal',      level: 0 }; // 70–109 (dokter)
            if (nilai <= 125) return { status: 'Prediabetes', level: 1 }; // 110–125
            return              { status: 'Diabetes',         level: 2 }; // 126–249

        case 'GD2PP': // Gula Darah 2 Jam Post Prandial
            if (nilai <= 139) return { status: 'Normal',      level: 0 }; // 70–139
            if (nilai <= 199) return { status: 'Prediabetes', level: 1 }; // 140–199
            return              { status: 'Diabetes',         level: 2 }; // 200–249

        case 'GDS': // Gula Darah Sewaktu
        default:
            if (nilai <= 179) return { status: 'Normal',   level: 0 }; // 70–179
            if (nilai <= 199) return { status: 'Waspada',  level: 1 }; // 180–199
            return              { status: 'Diabetes',      level: 2 }; // 200–249
    }
}

// -------------------------------------------------------
// Palet warna premium per level
function getBSColors(level) {
    switch (level) {
        case -3: return { bg: '#f9f2fc', statusColor: '#8b52b5', text: '#521d7a', border: '#8b52b518' }; // Hipoglikemia Kritis
        case -2: return { bg: '#fcf2f6', statusColor: '#b54470', text: '#7a1d45', border: '#b5447018' }; // Hipoglikemia Level 2
        case -1: return { bg: '#f0f6ff', statusColor: '#3a6eb5', text: '#1e3a7a', border: '#3a6eb518' }; // Hipoglikemia Level 1
        case  0: return { bg: '#f4faf6', statusColor: '#3a9e6f', text: '#1d5c3f', border: '#3a9e6f18' }; // Normal
        case  1: return { bg: '#fdfaf2', statusColor: '#c49a3a', text: '#7a5c10', border: '#c49a3a18' }; // Prediabetes/Waspada
        case  2: return { bg: '#fdf4f4', statusColor: '#c45252', text: '#7a2424', border: '#c4525218' }; // Diabetes
        case  3: return { bg: '#fdf7f2', statusColor: '#c47c3a', text: '#7a4210', border: '#c47c3a18' }; // Hiperglikemia Berat
        case  4: return { bg: '#fdf4f4', statusColor: '#b5444b', text: '#7a2329', border: '#b5444b18' }; // Krisis Hiperglikemia
        default: return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
    }
}

// -------------------------------------------------------
// Rentang nilai normal per parameter
function getNormalRange(parameter) {
    switch (parameter) {
        case 'GDP'  : return '70 – 109';  // dokter: Normal < 110
        case 'GD2PP': return '70 – 139';
        case 'GDS'  : return '70 – 179';
        default     : return '-';
    }
}

// -------------------------------------------------------
// Advice spesifik per parameter & level
function getAdvice(parameter, level) {

    // --- Zona Bawah (sama untuk semua parameter) ---
    if (level === -3) return { icon: 'fa-exclamation-triangle', text: 'Hipoglikemia Kritis (< 40). Darurat medis! Otak sangat kekurangan glukosa, risiko pingsan & kejang. Segera hubungi dokter.' };
    if (level === -2) return { icon: 'fa-exclamation-triangle', text: 'Hipoglikemia Berat (40–53). Otak mulai kekurangan glukosa. Segera konsumsi gula cepat dan cari bantuan medis.' };
    if (level === -1) return { icon: 'fa-exclamation-circle',   text: 'Hipoglikemia (54–69). Gejala lapar, gemetar, jantung berdebar. Segera konsumsi jus/permen dan hubungi dokter.' };

    // --- Zona Atas (sama untuk semua parameter) ---
    if (level === 3) return { icon: 'fa-exclamation-triangle', text: 'Hiperglikemia Berat (250–599). Gula darah sangat tinggi. Segera konsultasi dokter untuk penanganan intensif.' };
    if (level === 4) return { icon: 'fa-exclamation-triangle', text: 'Krisis Hiperglikemia (>= 600). Kondisi darurat! Risiko koma diabetik. Segera ke UGD rumah sakit.' };

    // --- Zona Tengah (spesifik per parameter) ---
    switch (parameter) {
        case 'GDP':
            if (level === 0) return { icon: 'fa-check-circle',      text: 'Gula darah puasa normal (70–109). Pertahankan pola makan sehat.' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Prediabetes (110–125). Kurangi gula, perbanyak aktivitas fisik, dan pantau secara rutin.' };
            return             { icon: 'fa-exclamation-triangle',    text: 'Diabetes (126–249). Segera konsultasi dokter dan lakukan pemeriksaan HbA1c.' };

        case 'GD2PP':
            if (level === 0) return { icon: 'fa-check-circle',      text: 'Respons gula darah setelah makan baik (70–139). Pertahankan!' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Prediabetes (140–199). Tubuh mulai lambat mengolah gula. Perlu perubahan pola makan.' };
            return             { icon: 'fa-exclamation-triangle',    text: 'Diabetes (200–249). Gula darah pasca makan sangat tinggi. Segera ke dokter.' };

        case 'GDS':
        default:
            if (level === 0) return { icon: 'fa-check-circle',      text: 'Gula darah sewaktu aman (70–179). Tetap jaga pola hidup sehat.' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Waspada (180–199). GDS tinggi, disarankan tes ulang dalam kondisi puasa untuk diagnosis lebih pasti.' };
            return             { icon: 'fa-exclamation-triangle',    text: 'Diabetes (200–249). Kadar gula sangat tinggi. Segera konsultasi ke dokter.' };
    }
}

// -------------------------------------------------------
function updateBSUI(category, nilai, parameter) {
    let colors = getBSColors(category.level);

    if (bsStatusEl) {
        bsStatusEl.style.setProperty('color', '#fff', 'important');
        bsStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
        bsStatusEl.innerText = category.status;
    }

    if (bsClassLabel) bsClassLabel.innerText = 'Klasifikasi Gula Darah';

    if (bsIconEl) {
        bsIconEl.style.color = colors.statusColor;
        bsIconEl.style.transition = 'color 0.4s ease';
    }

    if (bsCardEl) {
        bsCardEl.classList.remove('border-0');
        bsCardEl.style.backgroundColor = colors.bg;
        bsCardEl.style.border = `1px solid ${colors.border}`;
    }

    if (bsReadingEl) bsReadingEl.innerText = nilai;
    if (bsRangeEl)   bsRangeEl.innerText = getNormalRange(parameter);

    if (bsAdviceEl) {
        let advice = getAdvice(parameter, category.level);
        bsAdviceEl.style.color = colors.text;
        bsAdviceEl.innerHTML = `<i class="fas ${advice.icon} mr-1"></i>${advice.text}`;
    }
}

// -------------------------------------------------------
function showInvalidUI(nilai) {
    let pesanMin = `Nilai terlalu rendah (min. ${BATAS.GULA_MIN} mg/dL). Periksa kembali input Anda.`;
    let pesanMax = `Nilai terlalu tinggi (maks. ${BATAS.GULA_MAX} mg/dL). Periksa kembali input Anda.`;
    let pesan    = nilai < BATAS.GULA_MIN ? pesanMin : pesanMax;

    if (bsStatusEl) {
        bsStatusEl.style.setProperty('color', '#fff', 'important');
        bsStatusEl.style.setProperty('background-color', '#858796', 'important');
        bsStatusEl.innerText = 'TIDAK VALID';
    }
    if (bsIconEl)   bsIconEl.style.color = '#858796';
    if (bsCardEl) {
        bsCardEl.classList.remove('border-0');
        bsCardEl.style.backgroundColor = '#f8f9fa';
        bsCardEl.style.border = '1px solid #85879618';
    }
    if (bsReadingEl) bsReadingEl.innerText = nilai;
    if (bsRangeEl)   bsRangeEl.innerText = '-';
    if (bsAdviceEl) {
        bsAdviceEl.style.color = '#5a5c69';
        bsAdviceEl.innerHTML = `<i class="fas fa-times-circle mr-1"></i>${pesan}`;
    }
}

// -------------------------------------------------------
function resetBSUI() {
    if (bsStatusEl) {
        bsStatusEl.innerText = '- -';
        bsStatusEl.style.setProperty('color', '#d1d3e2', 'important');
        bsStatusEl.style.setProperty('background-color', 'transparent', 'important');
    }
    if (bsClassLabel) bsClassLabel.innerText = 'Klasifikasi Gula Darah';
    if (bsIconEl)     bsIconEl.style.color = '#e3e6f0';
    if (bsCardEl) {
        bsCardEl.style.backgroundColor = '#f8f9fa';
        bsCardEl.style.border = 'none';
    }
    if (bsReadingEl) bsReadingEl.innerText = '--.-';
    if (bsRangeEl)   bsRangeEl.innerText = '-';
    if (bsAdviceEl) {
        bsAdviceEl.innerText = '-';
        bsAdviceEl.style.color = '';
    }
    if (bsTitleEl) {
        bsTitleEl.innerHTML = 'Analisis <span class="text-primary">Gula Darah</span>';
    }
}
});
