// --- BAGIAN KALKULATOR KOLESTEROL ---
// Klasifikasi Kolesterol Total (NCEP ATP III / AHA):
// level -1 | Rendah         : < 120  mg/dL  (Hipokolesterolemia)
// level  0 | Normal         : 120–199 mg/dL
// level  1 | Ambang Batas   : 200–239 mg/dL  (Borderline High)
// level  2 | Tinggi         : 240–299 mg/dL
// level  3 | Sangat Tinggi  : >= 300 mg/dL   (Risiko Familial Hypercholesterolemia)

document.addEventListener('DOMContentLoaded', () => {
    const inputKolesterol = document.getElementById('kolesterol_total');
    const cholStatusEl    = document.getElementById('chol-status');
    const cholCardEl      = document.getElementById('chol-card');
    const cholAdviceEl    = document.getElementById('chol-advice');
    const cholIconEl      = document.getElementById('chol-icon');
    const cholReadingEl   = document.getElementById('chol-reading');
    const cholRangeEl     = document.getElementById('chol-range');

    // Batas logis input
    const BATAS = {
        KOLESTEROL_MIN: 50,   // mg/dL
        KOLESTEROL_MAX: 800,  // mg/dL
    };

    // -------------------------------------------------------
    // Event Listener
    if (inputKolesterol) {
        inputKolesterol.addEventListener('input', calculateChol);
    }

    // -------------------------------------------------------
    function calculateChol() {
        let nilai = parseFloat(inputKolesterol.value);

        // Kosong — reset biasa
        if (!inputKolesterol.value) {
            resetCholUI();
            return;
        }

        // Di luar batas logis — tampilkan Tidak Valid
        if (isNaN(nilai) || nilai < BATAS.KOLESTEROL_MIN || nilai > BATAS.KOLESTEROL_MAX) {
            showInvalidCholUI();
            return;
        }

        // Valid — proses klasifikasi
        let category = classifyChol(nilai);
        updateCholUI(category, nilai);
    }

    // -------------------------------------------------------
    // Klasifikasi Kolesterol — return level saja
    function classifyChol(nilai) {
        if (nilai < 120) return -1; // Rendah
        if (nilai < 200) return  0; // Normal
        if (nilai < 240) return  1; // Ambang Batas  (< 240 bukan <= 239, kebal desimal)
        if (nilai < 300) return  2; // Tinggi
        return                   3; // Sangat Tinggi
    }

    // -------------------------------------------------------
    // Palet warna premium per level
    function getCholColors(level) {
        switch (level) {
            case -1: return { bg: '#fdfaf2', statusColor: '#c49a3a', text: '#7a5c10', border: '#c49a3a18' }; // Rendah
            case  0: return { bg: '#f4faf6', statusColor: '#3a9e6f', text: '#1d5c3f', border: '#3a9e6f18' }; // Normal
            case  1: return { bg: '#fdf7f2', statusColor: '#c47c3a', text: '#7a4210', border: '#c47c3a18' }; // Ambang Batas
            case  2: return { bg: '#fdf4f4', statusColor: '#c45252', text: '#7a2424', border: '#c4525218' }; // Tinggi
            case  3: return { bg: '#f9f2fc', statusColor: '#8b52b5', text: '#521d7a', border: '#8b52b518' }; // Sangat Tinggi
            default: return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
        }
    }

    // -------------------------------------------------------
    // Status label, icon, advice & rentang normal per level
    function getCholInfo(level) {
        switch (level) {
            case -1: return {
                status : 'RENDAH',
                icon   : 'fa-arrow-down',
                advice : 'Kolesterol terlalu rendah (< 120). Bisa mengindikasikan malnutrisi, gangguan hati, atau hipertiroid. Konsultasi dokter.',
                range  : '< 200',
            };
            case 0: return {
                status : 'NORMAL',
                icon   : 'fa-check-circle',
                advice : 'Kolesterol total ideal (120–199). Pertahankan pola makan sehat dan olahraga teratur!',
                range  : '< 200',
            };
            case 1: return {
                status : 'AMBANG BATAS',
                icon   : 'fa-exclamation-circle',
                advice : 'Kolesterol mulai tinggi / Borderline (200–239). Kurangi makanan berlemak dan gorengan, tingkatkan aktivitas fisik.',
                range  : '< 200',
            };
            case 2: return {
                status : 'TINGGI',
                icon   : 'fa-exclamation-triangle',
                advice : 'Kolesterol tinggi (240–299). Risiko penyakit jantung meningkat. Segera konsultasi dokter.',
                range  : '< 200',
            };
            case 3: return {
                status : 'SANGAT TINGGI',
                icon   : 'fa-exclamation-triangle',
                advice : 'Kolesterol sangat tinggi (>= 300). Risiko penyumbatan pembuluh darah serius. Butuh penanganan medis segera.',
                range  : '< 200',
            };
            default: return {
                status : '— —',
                icon   : 'fa-minus-circle',
                advice : '—',
                range  : '—',
            };
        }
    }

    // -------------------------------------------------------
    function updateCholUI(level, nilai) {
        let colors = getCholColors(level);
        let info   = getCholInfo(level);

        if (cholStatusEl) {
            cholStatusEl.style.setProperty('color', '#fff', 'important');
            cholStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
            cholStatusEl.innerText = info.status;
        }

        if (cholIconEl) {
            cholIconEl.style.color      = colors.statusColor;
            cholIconEl.style.transition = 'color 0.4s ease';
        }

        if (cholCardEl) {
            cholCardEl.classList.remove('border-0');
            cholCardEl.style.backgroundColor = colors.bg;
            cholCardEl.style.border = `1px solid ${colors.border}`;
        }

        if (cholReadingEl) cholReadingEl.innerText = nilai;
        if (cholRangeEl)   cholRangeEl.innerText   = info.range;

        if (cholAdviceEl) {
            cholAdviceEl.style.color = colors.text;
            cholAdviceEl.innerHTML   = `<i class="fas ${info.icon} mr-1"></i>${info.advice}`;
        }
    }

    // -------------------------------------------------------
    function showInvalidCholUI() {
        if (cholStatusEl) {
            cholStatusEl.style.setProperty('color', '#fff', 'important');
            cholStatusEl.style.setProperty('background-color', '#858796', 'important');
            cholStatusEl.innerText = 'TIDAK VALID';
        }
        if (cholIconEl)  cholIconEl.style.color = '#858796';
        if (cholCardEl) {
            cholCardEl.classList.remove('border-0');
            cholCardEl.style.backgroundColor = '#f8f9fa';
            cholCardEl.style.border = '1px solid #85879618';
        }
        if (cholReadingEl) cholReadingEl.innerText = '--.-';
        if (cholRangeEl)   cholRangeEl.innerText   = '—';
        if (cholAdviceEl) {
            cholAdviceEl.style.color = '#5a5c69';
            cholAdviceEl.innerHTML   = `<i class="fas fa-times-circle mr-1"></i>Angka tidak valid.`;
        }
    }

    // -------------------------------------------------------
    function resetCholUI() {
        if (cholStatusEl) {
            cholStatusEl.innerText = '— —';
            cholStatusEl.style.setProperty('color', '#d1d3e2', 'important');
            cholStatusEl.style.setProperty('background-color', 'transparent', 'important');
        }
        if (cholIconEl)  cholIconEl.style.color = '#e3e6f0';
        if (cholCardEl) { cholCardEl.style.backgroundColor = '#f8f9fa'; cholCardEl.style.border = 'none'; }
        if (cholReadingEl) cholReadingEl.innerText = '--.-';
        if (cholRangeEl)   cholRangeEl.innerText   = '—';
        if (cholAdviceEl) { cholAdviceEl.innerText = '—'; cholAdviceEl.style.color = ''; }
    }
});
