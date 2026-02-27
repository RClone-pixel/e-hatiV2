// --- BAGIAN KALKULATOR TEKANAN DARAH ---
// Klasifikasi Tekanan Darah:
// level -2 | Krisis Hipotensi (Syok) : SBP < 70  OR  DBP < 40
// level -1 | Hipotensi               : SBP < 90  OR  DBP < 60
// level  0 | Normal                  : SBP 90–119 AND DBP 60–79
// level  1 | Pre-Hipertensi          : SBP >= 120 OR  DBP >= 80
// level  2 | Hipertensi Tingkat 1    : SBP >= 140 OR  DBP >= 90
// level  3 | Hipertensi Tingkat 2    : SBP >= 160 OR  DBP >= 100
// level  4 | Krisis Hipertensi       : SBP > 180  OR  DBP > 120
//
// Klasifikasi Nadi:
// level -2 | Bradikardia Berat       : < 40 bpm
// level -1 | Bradikardia             : 40–59 bpm
// level  0 | Normal                  : 60–100 bpm
// level  1 | Takikardia              : 101–130 bpm
// level  2 | Takikardia Berat        : > 130 bpm

document.addEventListener('DOMContentLoaded', () => {
    const inputSistolik  = document.getElementById('sistolik');
    const inputDiastolik = document.getElementById('diastolik');
    const inputNadi      = document.getElementById('nadi');
    const mapScoreEl     = document.getElementById('map-score');
    const bpStatusEl     = document.getElementById('bp-status');
    const bpCardEl       = document.getElementById('bp-card');
    const bpAdviceEl     = document.getElementById('bp-advice');
    const bpIconEl       = document.getElementById('bp-icon');
    const bpReadingEl    = document.getElementById('bp-reading');
    const pulseReadingEl = document.getElementById('pulse-reading');
    const pulseLabelEl   = document.getElementById('pulse-label');

    // Batas logis input
    const BATAS = {
        SBP_MIN: 40,  SBP_MAX: 350,
        DBP_MIN: 20,  DBP_MAX: 250,
        NADI_MIN: 20, NADI_MAX: 250,
    };

    // Event Listeners
    if (inputSistolik && inputDiastolik) {
        inputSistolik.addEventListener('input', calculateBP);
        inputDiastolik.addEventListener('input', calculateBP);
    }
    if (inputNadi) {
        inputNadi.addEventListener('input', calculateBP);
    }

    // -------------------------------------------------------
    function calculateBP() {
        let sbp   = parseFloat(inputSistolik.value);
        let dbp   = parseFloat(inputDiastolik.value);
        let pulse = parseFloat(inputNadi.value);

        // Kosong — reset biasa
        if (!inputSistolik.value && !inputDiastolik.value) {
            resetBPUI();
            return;
        }

        // Di luar batas logis — tampilkan Tidak Valid
        if (!isValidInput(sbp, dbp)) {
            showInvalidBPUI();
            return;
        }

        // Valid — proses klasifikasi
        let map = (sbp + (2 * dbp)) / 3;
        if (mapScoreEl) mapScoreEl.innerText = map.toFixed(1);

        let bpCategory    = classifyBP(sbp, dbp);
        let pulseCategory = classifyPulse(pulse);

        updateBPUI(bpCategory, pulseCategory, sbp, dbp, pulse, map);
    }

    // -------------------------------------------------------
    function isValidInput(sbp, dbp) {
        return  sbp  >= BATAS.SBP_MIN  && sbp  <= BATAS.SBP_MAX &&
                dbp  >= BATAS.DBP_MIN  && dbp  <= BATAS.DBP_MAX;
    }

    // -------------------------------------------------------
    // Klasifikasi Tekanan Darah — Zona Atas → Bawah → Normal
    function classifyBP(sbp, dbp) {

        // --- Zona Atas (dari paling tinggi) ---
        if (sbp > 180 || dbp > 120) return { status: 'Krisis Hipertensi',      level: 4  };
        if (sbp >= 160 || dbp >= 100) return { status: 'Hipertensi Tingkat 2', level: 3  };
        if (sbp >= 140 || dbp >= 90)  return { status: 'Hipertensi Tingkat 1', level: 2  };
        if (sbp >= 120 || dbp >= 80)  return { status: 'Pre-Hipertensi',       level: 1  };

        // --- Zona Bawah ---
        if (sbp < 70  || dbp < 40)   return { status: 'Krisis Hipotensi',      level: -2 };
        if (sbp < 90  || dbp < 60)   return { status: 'Hipotensi',             level: -1 };

        // --- Normal (SBP 90–119 AND DBP 60–79) ---
        return { status: 'Normal', level: 0 };
    }

    // -------------------------------------------------------
    function classifyPulse(pulse) {
        if (!pulse || isNaN(pulse) || pulse < BATAS.NADI_MIN || pulse > BATAS.NADI_MAX) {
            return { status: '-', color: '#858796', level: null };
        }
        if (pulse < 40)  return { status: 'Bradikardia Berat',       color: '#1e3a8a', level: -2 };
        if (pulse < 60)  return { status: 'Bradikardia',             color: '#3a6eb5', level: -1 };
        if (pulse <= 100) return { status: 'Normal',                 color: '#3a9e6f', level:  0 };
        if (pulse <= 130) return { status: 'Takikardia',             color: '#c49a3a', level:  1 };
        return               { status: 'Takikardia Berat',           color: '#c45252', level:  2 };
    }

    // -------------------------------------------------------
    // Klasifikasi MAP (Mean Arterial Pressure)
    // MAP Normal : 70–100 mmHg
    // < 65       : Perfusi organ tidak memadai (kritis)
    // 65–69      : Ambang batas rendah (waspada)
    // 70–100     : Normal
    // 101–120    : Beban jantung meningkat (waspada)
    // > 120      : Risiko kerusakan organ / stroke
    function classifyMAP(map) {
        if (map < 65)   return { level: -1, color: '#c45252', icon: 'fa-exclamation-triangle', text: `MAP ${map.toFixed(1)} mmHg — Perfusi organ tidak memadai! Risiko kerusakan organ vital (< 65 mmHg).` };
        if (map <= 69)  return { level:  0, color: '#c49a3a', icon: 'fa-exclamation-circle',   text: `MAP ${map.toFixed(1)} mmHg — Ambang batas bawah. Pantau kondisi dengan cermat.` };
        if (map <= 100) return { level:  1, color: null,      icon: null,                       text: null }; // Normal, tidak perlu peringatan
        if (map <= 120) return { level:  2, color: '#c49a3a', icon: 'fa-exclamation-circle',   text: `MAP ${map.toFixed(1)} mmHg — Beban kerja jantung meningkat (> 100 mmHg).` };
        return           { level:  3, color: '#c45252', icon: 'fa-exclamation-triangle', text: `MAP ${map.toFixed(1)} mmHg — Risiko tinggi kerusakan organ dan stroke (> 120 mmHg)!` };
    }


    function getBPColors(level) {
        switch (level) {
            case -2: return { bg: '#f9f2fc', statusColor: '#8b52b5', text: '#521d7a', border: '#8b52b518' }; // Krisis Hipotensi
            case -1: return { bg: '#f0f6ff', statusColor: '#3a6eb5', text: '#1e3a7a', border: '#3a6eb518' }; // Hipotensi
            case  0: return { bg: '#f4faf6', statusColor: '#3a9e6f', text: '#1d5c3f', border: '#3a9e6f18' }; // Normal
            case  1: return { bg: '#fdfaf2', statusColor: '#c49a3a', text: '#7a5c10', border: '#c49a3a18' }; // Pre-Hipertensi
            case  2: return { bg: '#fdf7f2', statusColor: '#c47c3a', text: '#7a4210', border: '#c47c3a18' }; // Hipertensi 1
            case  3: return { bg: '#fdf4f4', statusColor: '#c45252', text: '#7a2424', border: '#c4525218' }; // Hipertensi 2
            case  4: return { bg: '#fcf2f6', statusColor: '#b54470', text: '#7a1d45', border: '#b5447018' }; // Krisis Hipertensi
            default: return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
        }
    }

    // -------------------------------------------------------
    function updateBPUI(bpCategory, pulseCategory, sbp, dbp, pulse, map) {
        let colors = getBPColors(bpCategory.level);

        if (bpStatusEl) {
            bpStatusEl.style.setProperty('color', '#fff', 'important');
            bpStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
            bpStatusEl.innerText = bpCategory.status;
        }

        if (bpIconEl) {
            bpIconEl.style.color = colors.statusColor;
            bpIconEl.style.transition = 'color 0.4s ease';
        }

        if (bpCardEl) {
            bpCardEl.classList.remove('border-0');
            bpCardEl.style.backgroundColor = colors.bg;
            bpCardEl.style.border = `1px solid ${colors.border}`;
        }

        if (bpReadingEl) {
            bpReadingEl.innerText = `${sbp}/${dbp}`;
        }

        if (pulseReadingEl) {
            let nadiValid = pulse >= BATAS.NADI_MIN && pulse <= BATAS.NADI_MAX;
            pulseReadingEl.innerText = nadiValid ? pulse : '--';
            pulseReadingEl.style.color = pulseCategory.color;
        }

        if (pulseLabelEl) {
            let pulseStatusText = pulseCategory.status !== '-' ? ` · ${pulseCategory.status}` : '';
            pulseLabelEl.innerHTML = `bpm<span style="color: ${pulseCategory.color}; font-weight: 600;">${pulseStatusText}</span>`;
        }

        if (bpAdviceEl) {
            bpAdviceEl.style.color = colors.text;
            const adviceMap = {
                '-2': { icon: 'fa-exclamation-triangle', text: 'Krisis Hipotensi / Syok (SBP < 70). Darurat medis! Segera hubungi dokter atau ke UGD.' },
                '-1': { icon: 'fa-exclamation-circle',   text: 'Tekanan darah rendah (Hipotensi). Perbanyak cairan dan konsultasi dokter jika ada gejala.' },
                  '0': { icon: 'fa-check-circle',         text: 'Tekanan darah normal dan optimal. Pertahankan pola hidup sehat!' },
                  '1': { icon: 'fa-exclamation-circle',   text: 'Pre-Hipertensi. Perlu pemantauan berkala dan perubahan gaya hidup.' },
                  '2': { icon: 'fa-exclamation-circle',   text: 'Hipertensi Tingkat 1. Disarankan konsultasi dokter dan perbaikan pola hidup.' },
                  '3': { icon: 'fa-exclamation-triangle', text: 'Hipertensi Tingkat 2. Segera konsultasi dokter untuk penanganan medis.' },
                  '4': { icon: 'fa-exclamation-triangle', text: 'Krisis Hipertensi! Risiko stroke/serangan jantung. Segera ke UGD rumah sakit!' },
            };

            let advice   = adviceMap[bpCategory.level] ?? adviceMap['0'];
            let mapAlert = classifyMAP(map);

            // Baris utama (klasifikasi BP)
            let html = `<span><i class="fas ${advice.icon} mr-1"></i>${advice.text}</span>`;

            // Baris tambahan MAP — hanya tampil jika ada peringatan (MAP normal tidak ditampilkan)
            if (mapAlert.text) {
                html += `<span style="color: ${mapAlert.color}; display: block; margin-top: 5px; padding-top: 5px; border-top: 1px solid ${mapAlert.color}28;">
                             <i class="fas ${mapAlert.icon} mr-1"></i>${mapAlert.text}
                         </span>`;
            }

            bpAdviceEl.innerHTML = html;
        }
    }

    // -------------------------------------------------------
    function showInvalidBPUI() {
        if (bpStatusEl) {
            bpStatusEl.style.setProperty('color', '#fff', 'important');
            bpStatusEl.style.setProperty('background-color', '#858796', 'important');
            bpStatusEl.innerText = 'TIDAK VALID';
        }
        if (bpIconEl)  bpIconEl.style.color = '#858796';
        if (bpCardEl) {
            bpCardEl.classList.remove('border-0');
            bpCardEl.style.backgroundColor = '#f8f9fa';
            bpCardEl.style.border = '1px solid #85879618';
        }
        if (mapScoreEl)     mapScoreEl.innerText = '--.-';
        if (bpReadingEl)    bpReadingEl.innerText = '--/--';
        if (pulseReadingEl) { pulseReadingEl.innerText = '--'; pulseReadingEl.style.color = ''; }
        if (pulseLabelEl)   pulseLabelEl.innerHTML = 'bpm';
        if (bpAdviceEl) {
            bpAdviceEl.style.color = '#5a5c69';
            bpAdviceEl.innerHTML   = `<i class="fas fa-times-circle mr-1"></i>Angka tidak valid.`;
        }
    }

    // -------------------------------------------------------
    function resetBPUI() {
        if (mapScoreEl)     mapScoreEl.innerText = '--.-';
        if (bpStatusEl) {
            bpStatusEl.innerText = '— —';
            bpStatusEl.style.setProperty('color', '#d1d3e2', 'important');
            bpStatusEl.style.setProperty('background-color', 'transparent', 'important');
        }
        if (bpIconEl)       bpIconEl.style.color = '#e3e6f0';
        if (bpCardEl) {
            bpCardEl.style.backgroundColor = '#f8f9fa';
            bpCardEl.style.border = 'none';
        }
        if (bpReadingEl)    bpReadingEl.innerText = '--/--';
        if (pulseReadingEl) {
            pulseReadingEl.innerText = '--';
            pulseReadingEl.style.color = '';
        }
        if (pulseLabelEl)   pulseLabelEl.innerHTML = 'bpm';
        if (bpAdviceEl) {
            bpAdviceEl.innerText = '—';
            bpAdviceEl.style.color = '';
        }
    }
});
