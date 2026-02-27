// --- BAGIAN KALKULATOR ASAM URAT ---
// Klasifikasi Asam Urat (Dewasa, Standar Kemenkes/WHO):
// level -1 | Rendah        : < low   (Hipourisemia)
// level  0 | Normal        : low–high
// level  1 | Tinggi        : high – high+2.0  (Hiperurisemia)
// level  2 | Sangat Tinggi : > high+2.0       (Risiko Gout Akut)
//
// Nilai Rujukan Normal:
// Laki-laki  : 3.5 – 7.2 mg/dL
// Perempuan  : 2.6 – 6.0 mg/dL

document.addEventListener('DOMContentLoaded', () => {
    const inputUA      = document.getElementById('asam_urat');
    const inputUmurUA  = document.getElementById('ua_umur');
    const uaStatusEl   = document.getElementById('ua-status');
    const uaCardEl     = document.getElementById('ua-card');
    const uaAdviceEl   = document.getElementById('ua-advice');
    const uaIconEl     = document.getElementById('ua-icon');
    const uaGenderIcon = document.getElementById('ua-gender-icon');
    const uaTitleEl    = document.getElementById('ua-title');
    const uaReadingEl  = document.getElementById('ua-reading');
    const uaTermEl     = document.getElementById('ua-term');
    const uaRangeEl    = document.getElementById('ua-range');
    const uaCatLabel   = document.getElementById('ua-cat-label');

    // Batas logis input
    const BATAS = {
        UA_MIN: 1.0,   // mg/dL
        UA_MAX: 30.0,  // mg/dL
    };

    // Nilai rujukan per gender
    const RUJUKAN = {
        laki      : { low: 3.5, high: 7.2 },
        perempuan : { low: 2.6, high: 6.0 },
    };

    // -------------------------------------------------------
    // Event Listeners
    if (inputUA) {
        inputUA.addEventListener('input', calculateUA);
    }
    // Dipanggil oleh ajax.js saat pegawai dipilih
    document.addEventListener('uaDataUpdated', calculateUA);

    // -------------------------------------------------------
    function getGender() {
        const jkEl = document.getElementById('jenis_kelamin');
        if (!jkEl) return '';
        let jk = (jkEl.value || '').toLowerCase().trim();
        if (jk.includes('laki') || jk === 'l')          return 'laki';
        if (jk.includes('perempuan') || jk === 'p')     return 'perempuan';
        return '';
    }

    // -------------------------------------------------------
    function calculateUA() {
        if (!inputUA) return;

        let nilai  = parseFloat(inputUA.value);
        let gender = getGender();

        // Kosmetik — selalu update icon gender & title
        updateTitle();
        updateGenderIcon(gender);

        // Kosong — reset biasa
        if (!inputUA.value) {
            resetUAUI();
            return;
        }

        // Di luar batas logis — tampilkan Tidak Valid
        if (isNaN(nilai) || nilai < BATAS.UA_MIN || nilai > BATAS.UA_MAX) {
            showInvalidUAUI();
            return;
        }

        // Gender belum dipilih — reset (tunggu data pegawai)
        if (!gender) {
            resetUAUI();
            return;
        }

        // Valid — proses klasifikasi
        let level = classifyUA(nilai, gender);
        updateUAUI(level, nilai, gender);
    }

    // -------------------------------------------------------
    // Klasifikasi Asam Urat — return level saja
    function classifyUA(nilai, gender) {
        let { low, high } = RUJUKAN[gender];

        if (nilai < low)          return -1; // Rendah
        if (nilai <= high)        return  0; // Normal
        if (nilai <= high + 2.0)  return  1; // Tinggi
        return                            2; // Sangat Tinggi
    }

    // -------------------------------------------------------
    // Palet warna premium per level
    function getUAColors(level) {
        switch (level) {
            case -1: return { bg: '#f0f6ff', statusColor: '#3a6eb5', text: '#1e3a7a', border: '#3a6eb518' }; // Rendah
            case  0: return { bg: '#f4faf6', statusColor: '#3a9e6f', text: '#1d5c3f', border: '#3a9e6f18' }; // Normal
            case  1: return { bg: '#fdf7f2', statusColor: '#c47c3a', text: '#7a4210', border: '#c47c3a18' }; // Tinggi
            case  2: return { bg: '#fdf4f4', statusColor: '#c45252', text: '#7a2424', border: '#c4525218' }; // Sangat Tinggi
            default: return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
        }
    }

    // -------------------------------------------------------
    // Info per level: status, term, icon, advice
    function getUAInfo(level) {
        switch (level) {
            case -1: return {
                status : 'RENDAH',
                term   : 'Hipourisemia',
                icon   : 'fa-arrow-down',
                advice : 'Kadar asam urat terlalu rendah (Hipourisemia). Bisa mengindikasikan malnutrisi atau gangguan metabolisme. Konsultasi dokter.',
            };
            case 0: return {
                status : 'NORMAL',
                term   : 'Normal',
                icon   : 'fa-check-circle',
                advice : 'Kadar asam urat dalam batas normal. Pertahankan pola makan sehat dan hidrasi cukup.',
            };
            case 1: return {
                status : 'TINGGI',
                term   : 'Hiperurisemia',
                icon   : 'fa-exclamation-circle',
                advice : 'Kadar asam urat tinggi (Hiperurisemia). Hindari makanan tinggi purin (jeroan, seafood) dan perbanyak air putih. Konsultasi dokter.',
            };
            case 2: return {
                status : 'SANGAT TINGGI',
                term   : 'Risiko Gout Akut',
                icon   : 'fa-exclamation-triangle',
                advice : 'Kadar asam urat sangat tinggi. Risiko serangan gout akut dan kerusakan sendi. Segera konsultasi dokter untuk penanganan.',
            };
            default: return {
                status : '— —',
                term   : '—',
                icon   : 'fa-minus-circle',
                advice : '—',
            };
        }
    }

    // -------------------------------------------------------
    // Rentang normal — tetap berdasarkan gender, tidak berubah per level
    function getNormalRange(gender) {
        if (!gender || !RUJUKAN[gender]) return '—';
        let { low, high } = RUJUKAN[gender];
        return `${low} – ${high}`;
    }

    // -------------------------------------------------------
    function updateTitle() {
        if (uaTitleEl) {
            uaTitleEl.innerHTML = `Analisis <span class="text-primary">Asam Urat</span>`;
        }
    }

    // -------------------------------------------------------
    function updateGenderIcon(gender) {
        if (!uaGenderIcon) return;
        if (gender === 'laki') {
            uaGenderIcon.innerHTML    = '<i class="fas fa-mars"></i>';
            uaGenderIcon.style.color  = '#4e73df';
        } else if (gender === 'perempuan') {
            uaGenderIcon.innerHTML    = '<i class="fas fa-venus"></i>';
            uaGenderIcon.style.color  = '#e83e8c';
        } else {
            uaGenderIcon.innerHTML    = '<i class="fas fa-venus-mars"></i>';
            uaGenderIcon.style.color  = '#d1d3e2';
        }
        uaGenderIcon.style.transition = 'color 0.4s ease';
    }

    // -------------------------------------------------------
    function updateUAUI(level, nilai, gender) {
        let colors = getUAColors(level);
        let info   = getUAInfo(level);

        if (uaStatusEl) {
            uaStatusEl.style.setProperty('color', '#fff', 'important');
            uaStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
            uaStatusEl.innerText = info.status;
        }

        if (uaIconEl) {
            uaIconEl.style.color      = colors.statusColor;
            uaIconEl.style.transition = 'color 0.4s ease';
        }

        if (uaCardEl) {
            uaCardEl.classList.remove('border-0');
            uaCardEl.style.backgroundColor = colors.bg;
            uaCardEl.style.border = `1px solid ${colors.border}`;
        }

        if (uaReadingEl) uaReadingEl.innerText = nilai.toFixed(1);

        if (uaTermEl) {
            uaTermEl.innerText      = info.term;
            uaTermEl.style.color    = colors.statusColor;
            uaTermEl.style.fontWeight = '700';
        }

        // Range selalu menampilkan rentang normal, tidak berubah per level
        if (uaRangeEl) uaRangeEl.innerText = getNormalRange(gender);

        if (uaCatLabel) uaCatLabel.innerText = 'Klasifikasi Asam Urat';

        if (uaAdviceEl) {
            uaAdviceEl.style.color = colors.text;
            uaAdviceEl.innerHTML   = `<i class="fas ${info.icon} mr-1"></i>${info.advice}`;
        }
    }

    // -------------------------------------------------------
    function showInvalidUAUI() {
        if (uaStatusEl) {
            uaStatusEl.style.setProperty('color', '#fff', 'important');
            uaStatusEl.style.setProperty('background-color', '#858796', 'important');
            uaStatusEl.innerText = 'TIDAK VALID';
        }
        if (uaIconEl)  uaIconEl.style.color = '#858796';
        if (uaCardEl) {
            uaCardEl.classList.remove('border-0');
            uaCardEl.style.backgroundColor = '#f8f9fa';
            uaCardEl.style.border = '1px solid #85879618';
        }
        if (uaReadingEl)  uaReadingEl.innerText = '--.-';
        if (uaTermEl) {   uaTermEl.innerText = '—'; uaTermEl.style.color = ''; uaTermEl.style.fontWeight = ''; }
        if (uaRangeEl)    uaRangeEl.innerText = '—';
        if (uaCatLabel)   uaCatLabel.innerText = 'Klasifikasi Asam Urat';
        if (uaAdviceEl) {
            uaAdviceEl.style.color = '#5a5c69';
            uaAdviceEl.innerHTML   = `<i class="fas fa-times-circle mr-1"></i>Angka tidak valid.`;
        }
    }

    // -------------------------------------------------------
    function resetUAUI() {
        if (uaStatusEl) {
            uaStatusEl.innerText = '— —';
            uaStatusEl.style.setProperty('color', '#d1d3e2', 'important');
            uaStatusEl.style.setProperty('background-color', 'transparent', 'important');
        }
        if (uaIconEl)  uaIconEl.style.color = '#e3e6f0';
        if (uaCardEl) { uaCardEl.style.backgroundColor = '#f8f9fa'; uaCardEl.style.border = 'none'; }
        if (uaReadingEl)  uaReadingEl.innerText = '--.-';
        if (uaTermEl) {   uaTermEl.innerText = '—'; uaTermEl.style.color = ''; uaTermEl.style.fontWeight = ''; }
        if (uaRangeEl)    uaRangeEl.innerText = '—';
        if (uaCatLabel)   uaCatLabel.innerText = 'Klasifikasi Asam Urat';
        if (uaAdviceEl) { uaAdviceEl.innerText = '—'; uaAdviceEl.style.color = ''; }
    }

    // Expose untuk dipanggil dari luar (ajax.js)
    window.recalculateUA = calculateUA;
});
