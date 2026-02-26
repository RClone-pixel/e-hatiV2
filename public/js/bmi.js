// --- BAGIAN KALKULATOR BMI & BROCA ---
// Klasifikasi BMI (WHO Global):
// level -3 | Kekurangan III : BMI < 16
// level -2 | Kekurangan II  : BMI 16–16.9
// level -1 | Kekurangan I   : BMI 17–18.4
// level  0 | Normal         : BMI 18.5–24.9
// level  1 | Kelebihan      : BMI 25–29.9
// level  2 | Obesitas I     : BMI 30–34.9
// level  3 | Obesitas II    : BMI 35–39.9
// level  4 | Obesitas III   : BMI >= 40
//
// Broca (Standar Kemenkes):
// Pria  < 160 cm  → TB - 100 (tanpa potongan)
// Pria >= 160 cm  → (TB - 100) dikurangi 10%
// Wanita  < 150 cm → TB - 100 (tanpa potongan)
// Wanita >= 150 cm → (TB - 100) dikurangi 15%

document.addEventListener('DOMContentLoaded', () => {
    const inputTinggi    = document.getElementById('tinggi_badan');
    const inputBerat     = document.getElementById('berat_badan');
    const genderInput    = document.getElementById('jenis_kelamin');
    const scoreEl        = document.getElementById('bmi-score');
    const statusEl       = document.getElementById('bmi-status');
    const cardEl         = document.getElementById('bmi-card');
    const adviceEl       = document.getElementById('bmi-advice');
    const idealEl        = document.getElementById('bmi-ideal');
    const selisihEl      = document.getElementById('bmi-selisih');
    const selisihLabelEl = document.getElementById('bmi-selisih-label');
    const genderIconEl   = document.getElementById('gender-icon');

    // Batas logis input
    const BATAS = {
        TINGGI_MIN: 60,  TINGGI_MAX: 400,  // cm
        BERAT_MIN:  20,  BERAT_MAX:  700,  // kg
    };

    // -------------------------------------------------------
    // Event Listeners
    if (inputTinggi && inputBerat) {
        inputTinggi.addEventListener('input', calculateBMI);
        inputBerat.addEventListener('input', calculateBMI);
    }
    // Trigger ulang saat jenis kelamin diubah (agar Broca langsung update)
    if (genderInput) {
        genderInput.addEventListener('change', calculateBMI);
    }

    // -------------------------------------------------------
    function calculateBMI() {
        let tb = parseFloat(inputTinggi.value);
        let bb = parseFloat(inputBerat.value);

        // Kosong — reset biasa (salah satu saja kosong sudah cukup)
        if (!inputTinggi.value || !inputBerat.value) {
            resetUI();
            return;
        }

        // Di luar batas logis — tampilkan Tidak Valid
        if (!isValidInput(tb, bb)) {
            showInvalidUI();
            return;
        }

        // Valid — proses kalkulasi
        let bmi = bb / Math.pow(tb / 100, 2);
        if (scoreEl) scoreEl.innerText = bmi.toFixed(1);

        let brocaData = calculateBroca(tb, bb);
        updateUI(bmi, brocaData);
    }

    // -------------------------------------------------------
    function isValidInput(tb, bb) {
        return tb >= BATAS.TINGGI_MIN && tb <= BATAS.TINGGI_MAX &&
               bb >= BATAS.BERAT_MIN  && bb <= BATAS.BERAT_MAX;
    }

    // -------------------------------------------------------
    // Klasifikasi BMI — return level saja, data lain di getBMIInfo/getBMIColors
    function classifyBMI(bmi) {
        if (bmi < 16)   return -3;
        if (bmi < 17)   return -2;
        if (bmi < 18.5) return -1;
        if (bmi < 25)   return  0;
        if (bmi < 30)   return  1;
        if (bmi < 35)   return  2;
        if (bmi < 40)   return  3;
        return                   4;
    }

    // -------------------------------------------------------
    // Hitung Berat Ideal Broca (Standar Kemenkes)
    function calculateBroca(tb_cm, bb_kg) {
        let gender = genderInput ? genderInput.value.toLowerCase() : '';
        if (!tb_cm || !bb_kg) return null;

        let base = tb_cm - 100;
        let beratIdeal;

        if (gender.includes('laki') || gender === 'l') {
            beratIdeal = (tb_cm < 160) ? base : base - (base * 0.10);
        } else {
            beratIdeal = (tb_cm < 150) ? base : base - (base * 0.15);
        }

        return {
            beratIdeal: beratIdeal,
            selisih: bb_kg - beratIdeal
        };
    }

    // -------------------------------------------------------
    // Palet warna premium per level
    function getBMIColors(level) {
        switch (level) {
            case -3: return { bg: '#fdf6f6', statusColor: '#b5444b', text: '#7a2329', border: '#b5444b18' }; // Kekurangan III
            case -2: return { bg: '#fdf8f3', statusColor: '#c4683a', text: '#8a3d1f', border: '#c4683a18' }; // Kekurangan II
            case -1: return { bg: '#fdfaf2', statusColor: '#c49a3a', text: '#7a5c10', border: '#c49a3a18' }; // Kekurangan I
            case  0: return { bg: '#f4faf6', statusColor: '#3a9e6f', text: '#1d5c3f', border: '#3a9e6f18' }; // Normal
            case  1: return { bg: '#fdf7f2', statusColor: '#c47c3a', text: '#7a4210', border: '#c47c3a18' }; // Kelebihan
            case  2: return { bg: '#fdf4f4', statusColor: '#c45252', text: '#7a2424', border: '#c4525218' }; // Obesitas I
            case  3: return { bg: '#fcf2f6', statusColor: '#b54470', text: '#7a1d45', border: '#b5447018' }; // Obesitas II
            case  4: return { bg: '#f9f2fc', statusColor: '#8b52b5', text: '#521d7a', border: '#8b52b518' }; // Obesitas III
            default: return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
        }
    }

    // -------------------------------------------------------
    // Status label & advice per level
    function getBMIInfo(level) {
        switch (level) {
            case -3: return { status: 'KEKURANGAN III', icon: 'fa-exclamation-triangle', advice: 'Kekurangan berat badan tingkat III. Segera konsultasi ke dokter.' };
            case -2: return { status: 'KEKURANGAN II',  icon: 'fa-exclamation-circle',   advice: 'Kekurangan berat badan tingkat II. Tingkatkan asupan gizi.' };
            case -1: return { status: 'KEKURANGAN I',   icon: 'fa-exclamation-circle',   advice: 'Kekurangan berat badan tingkat I. Perhatikan pola makan sehat.' };
            case  0: return { status: 'NORMAL',         icon: 'fa-check-circle',         advice: 'Berat badan dalam kategori ideal. Pertahankan pola hidup sehat!' };
            case  1: return { status: 'KELEBIHAN',      icon: 'fa-exclamation-circle',   advice: 'Kelebihan berat badan. Atur pola makan dan olahraga teratur.' };
            case  2: return { status: 'OBESITAS I',     icon: 'fa-exclamation-triangle', advice: 'Obesitas Tingkat I. Konsultasi dokter dan terapkan diet sehat.' };
            case  3: return { status: 'OBESITAS II',    icon: 'fa-exclamation-triangle', advice: 'Obesitas Tingkat II. Segera konsultasi dokter.' };
            case  4: return { status: 'OBESITAS III',   icon: 'fa-exclamation-triangle', advice: 'Obesitas Tingkat III. Butuh penanganan medis segera.' };
            default: return { status: '— —',            icon: 'fa-minus-circle',         advice: '—' };
        }
    }

    // -------------------------------------------------------
    function updateUI(bmi, brocaData) {
        let level  = classifyBMI(bmi);
        let colors = getBMIColors(level);
        let info   = getBMIInfo(level);

        if (statusEl) {
            statusEl.style.setProperty('color', '#fff', 'important');
            statusEl.style.setProperty('background-color', colors.statusColor, 'important');
            statusEl.innerText = info.status;
        }

        if (genderIconEl) genderIconEl.style.transition = 'color 0.4s ease';

        if (cardEl) {
            cardEl.classList.remove('border-0');
            cardEl.style.backgroundColor = colors.bg;
            cardEl.style.border = `1px solid ${colors.border}`;
        }

        // Stats: Berat Ideal & Selisih (Broca)
        if (brocaData) {
            if (idealEl) idealEl.innerText = brocaData.beratIdeal.toFixed(1);

            if (selisihEl) {
                let absVal = Math.abs(brocaData.selisih);
                let absStr = absVal.toFixed(1);
                selisihEl.innerText   = absStr === '0.0' ? '0.0'
                                      : brocaData.selisih > 0 ? `+${absStr}` : `-${absStr}`;
                selisihEl.style.color = absVal <= 1  ? '#3a9e6f'
                                      : brocaData.selisih > 0 ? '#c45252'
                                      :                          '#c49a3a';
            }

            if (selisihLabelEl) {
                if (Math.abs(brocaData.selisih) <= 1) {
                    selisihLabelEl.innerHTML = `<span style="color: #3a9e6f;">Pas!</span>`;
                } else {
                    selisihLabelEl.innerHTML = brocaData.selisih > 0 ? 'Kelebihan' : 'Kekurangan';
                }
            }
        }

        if (adviceEl) {
            adviceEl.style.color = colors.text;
            adviceEl.innerHTML   = `<i class="fas ${info.icon} mr-1"></i>${info.advice}`;
        }
    }

    // -------------------------------------------------------
    function showInvalidUI() {
        if (scoreEl)  scoreEl.innerText = '--.-';
        if (statusEl) {
            statusEl.style.setProperty('color', '#fff', 'important');
            statusEl.style.setProperty('background-color', '#858796', 'important');
            statusEl.innerText = 'TIDAK VALID';
        }
        if (cardEl) {
            cardEl.classList.remove('border-0');
            cardEl.style.backgroundColor = '#f8f9fa';
            cardEl.style.border = '1px solid #85879618';
        }
        if (idealEl)        idealEl.innerText = '--.-';
        if (selisihEl) {    selisihEl.innerText = '--.-'; selisihEl.style.color = ''; }
        if (selisihLabelEl) selisihLabelEl.innerHTML = 'Selisih (kg)';
        if (adviceEl) {
            adviceEl.style.color = '#5a5c69';
            adviceEl.innerHTML   = `<i class="fas fa-times-circle mr-1"></i>Angka tidak valid.`;
        }
    }

    // -------------------------------------------------------
    function resetUI() {
        if (scoreEl)  scoreEl.innerText = '--.-';
        if (statusEl) {
            statusEl.innerText = '— —';
            statusEl.style.setProperty('color', '#d1d3e2', 'important');
            statusEl.style.setProperty('background-color', 'transparent', 'important');
        }
        if (cardEl) {           cardEl.style.backgroundColor = '#f8f9fa'; cardEl.style.border = 'none'; }
        if (idealEl)            idealEl.innerText = '--.-';
        if (selisihEl) {        selisihEl.innerText = '--.-'; selisihEl.style.color = ''; }
        if (selisihLabelEl)     selisihLabelEl.innerHTML = 'Selisih (kg)';
        if (adviceEl) {         adviceEl.innerText = '—'; adviceEl.style.color = ''; }
    }
});
