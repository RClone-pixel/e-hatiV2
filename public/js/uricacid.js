// --- BAGIAN KALKULATOR ASAM URAT ---
document.addEventListener('DOMContentLoaded', () => {
    const inputUA       = document.getElementById('asam_urat');
    const inputUmurUA   = document.getElementById('ua_umur');
    const uaStatusEl    = document.getElementById('ua-status');
    const uaCardEl      = document.getElementById('ua-card');
    const uaAdviceEl    = document.getElementById('ua-advice');
    const uaIconEl      = document.getElementById('ua-icon');
    const uaGenderIcon  = document.getElementById('ua-gender-icon');
    const uaTitleEl     = document.getElementById('ua-title');
    const uaReadingEl   = document.getElementById('ua-reading');
    const uaTermEl      = document.getElementById('ua-term');
    const uaRangeEl     = document.getElementById('ua-range');
    const uaCatLabel    = document.getElementById('ua-cat-label');

    // ===== Event Listeners (real-time) =====
    if (inputUA) {
        inputUA.addEventListener('input', calculateUA);
    }

    // Listen for age/gender changes (triggered by ajax.js when employee is selected)
    document.addEventListener('uaDataUpdated', calculateUA);

    // ===== Ambil data umur dan jenis kelamin dari form pegawai =====
    function getUmur() {
        // Coba ambil dari ua_umur (readonly, angka saja)
        let umurVal = inputUmurUA ? inputUmurUA.value : '';
        if (umurVal && !isNaN(parseInt(umurVal))) {
            return parseInt(umurVal);
        }
        // Fallback ke umur utama
        const mainUmur = document.getElementById('umur');
        if (mainUmur) {
            let val = mainUmur.value;
            let match = val.match(/(\d+)/);
            if (match) return parseInt(match[1]);
        }
        return 0;
    }

    function getGender() {
        const jkEl = document.getElementById('jenis_kelamin');
        if (!jkEl) return '';
        let jk = (jkEl.value || '').toLowerCase().trim();
        if (jk.includes('laki') || jk === 'l') return 'laki';
        if (jk.includes('perempuan') || jk === 'p') return 'perempuan';
        return '';
    }

    function getAgeCategory(umur) {
        if (umur >= 60) return 'lansia';
        return 'dewasa';
    }

    // ===== Main Calculation =====
    function calculateUA() {
        let nilai = parseFloat(inputUA ? inputUA.value : 0);
        let umur = getUmur();
        let gender = getGender();
        let ageCategory = getAgeCategory(umur);

        // Update title sesuai kategori umur
        updateTitle(ageCategory);

        // Update gender icon
        updateGenderIcon(gender);

        // Update umur field (readonly)
        if (inputUmurUA && umur > 0) {
            inputUmurUA.value = umur;
        }

        if (nilai > 0 && gender) {
            let category = classifyUA(nilai, gender, ageCategory);
            updateUAUI(category, nilai);
        } else {
            resetUAUI();
        }
    }

    // ===== Klasifikasi Asam Urat =====
    // Data tabel:
    // DEWASA (≥16 thn):  Laki: Normal 3.5–7.2 | Perempuan: Normal 2.6–6.0
    // LANSIA (≥60 thn):  Laki: Normal 3.5–8.0 | Perempuan: Normal 2.7–7.3
    function classifyUA(nilai, gender, ageCategory) {
        let low, high;

        if (ageCategory === 'lansia') {
            if (gender === 'laki') {
                low = 3.5; high = 8.0;
            } else {
                low = 2.7; high = 7.3;
            }
        } else {
            // Dewasa
            if (gender === 'laki') {
                low = 3.5; high = 7.2;
            } else {
                low = 2.6; high = 6.0;
            }
        }

        if (nilai < low) {
            return {
                status: 'Rendah', level: 0,
                term: 'Hipourisemia',
                range: `< ${low}`
            };
        } else if (nilai <= high) {
            return {
                status: 'Normal', level: 1,
                term: 'Normal',
                range: `${low} – ${high}`
            };
        } else {
            return {
                status: 'Tinggi', level: 2,
                term: 'Hiperurisemia',
                range: `> ${high}`
            };
        }
    }

    // ===== Palet Warna Premium =====
    function getUAColors(level) {
        switch (level) {
            case 0: // Rendah (Hipourisemia) — biru dingin
                return { bg: '#eff6ff', statusColor: '#2563eb', text: '#1e40af', border: '#2563eb20' };
            case 1: // Normal — hijau
                return { bg: '#f0fdf4', statusColor: '#059669', text: '#065f46', border: '#05966920' };
            case 2: // Tinggi (Hiperurisemia) — merah
                return { bg: '#fef2f2', statusColor: '#dc2626', text: '#991b1b', border: '#dc262620' };
            default:
                return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
        }
    }

    // ===== Advice =====
    function getAdvice(level) {
        if (level === 0) return { icon: 'fa-info-circle', text: 'Kadar asam urat rendah (Hipourisemia). Perbanyak konsumsi protein hewani.' };
        if (level === 1) return { icon: 'fa-check-circle', text: 'Kadar asam urat dalam batas normal. Pertahankan pola hidup sehat.' };
        return { icon: 'fa-exclamation-triangle', text: 'Kadar asam urat tinggi (Hiperurisemia). Hindari makanan tinggi purin dan konsultasi dokter.' };
    }

    // ===== Update Title =====
    function updateTitle(ageCategory) {
        if (uaTitleEl) {
            let catLabel = ageCategory === 'lansia' ? 'Lansia' : 'Dewasa';
            uaTitleEl.innerHTML = `Analisis <span class="text-primary">Asam Urat ${catLabel}</span>`;
        }
    }

    // ===== Update Gender Icon =====
    function updateGenderIcon(gender) {
        if (!uaGenderIcon) return;
        if (gender === 'laki') {
            uaGenderIcon.innerHTML = '<i class="fas fa-mars"></i>';
            uaGenderIcon.style.color = '#4e73df';
        } else if (gender === 'perempuan') {
            uaGenderIcon.innerHTML = '<i class="fas fa-venus"></i>';
            uaGenderIcon.style.color = '#e83e8c';
        } else {
            uaGenderIcon.innerHTML = '<i class="fas fa-venus-mars"></i>';
            uaGenderIcon.style.color = '#d1d3e2';
        }
        uaGenderIcon.style.transition = 'color 0.4s ease';
    }

    // ===== Update UI =====
    function updateUAUI(category, nilai) {
        let colors = getUAColors(category.level);

        // Klasifikasi (hero badge besar)
        if (uaStatusEl) {
            uaStatusEl.style.setProperty('color', '#fff', 'important');
            uaStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
            uaStatusEl.innerText = category.status;
        }

        // Icon uric acid
        if (uaIconEl) {
            uaIconEl.style.color = colors.statusColor;
            uaIconEl.style.transition = 'color 0.4s ease';
        }

        // Kartu background
        if (uaCardEl) {
            uaCardEl.classList.remove('border-0');
            uaCardEl.style.backgroundColor = colors.bg;
            uaCardEl.style.border = `1px solid ${colors.border}`;
        }

        // Nilai reading
        if (uaReadingEl) {
            uaReadingEl.innerText = nilai.toFixed(1);
        }

        // Term (Hipourisemia / Normal / Hiperurisemia)
        if (uaTermEl) {
            uaTermEl.innerText = category.term;
            uaTermEl.style.color = colors.statusColor;
            uaTermEl.style.fontWeight = '700';
        }

        // Rentang
        if (uaRangeEl) {
            uaRangeEl.innerText = category.range;
        }

        // Category label update
        if (uaCatLabel) {
            uaCatLabel.innerText = 'Klasifikasi Asam Urat';
        }

        // Advice
        if (uaAdviceEl) {
            let advice = getAdvice(category.level);
            uaAdviceEl.style.color = colors.text;
            uaAdviceEl.innerHTML = `<i class="fas ${advice.icon} mr-1"></i>${advice.text}`;
        }
    }

    // ===== Reset UI =====
    function resetUAUI() {
        if (uaStatusEl) {
            uaStatusEl.innerText = '— —';
            uaStatusEl.style.setProperty('color', '#d1d3e2', 'important');
            uaStatusEl.style.setProperty('background-color', 'transparent', 'important');
        }
        if (uaIconEl) {
            uaIconEl.style.color = '#e3e6f0';
        }
        if (uaCardEl) {
            uaCardEl.style.backgroundColor = '#f8f9fa';
            uaCardEl.style.border = 'none';
        }
        if (uaReadingEl) uaReadingEl.innerText = '--.-';
        if (uaTermEl) {
            uaTermEl.innerText = '—';
            uaTermEl.style.color = '';
            uaTermEl.style.fontWeight = '';
        }
        if (uaRangeEl) uaRangeEl.innerText = '—';
        if (uaCatLabel) uaCatLabel.innerText = 'Klasifikasi Asam Urat';
        if (uaAdviceEl) {
            uaAdviceEl.innerText = '—';
            uaAdviceEl.style.color = '';
        }
    }

    // Expose recalculate function for external calls
    window.recalculateUA = calculateUA;
});
