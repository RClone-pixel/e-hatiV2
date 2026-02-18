// --- BAGIAN KALKULATOR GULA DARAH ---
document.addEventListener('DOMContentLoaded', () => {
const inputParameter = document.getElementById('parameter_gula');
const inputGlukometer = document.getElementById('nilai_glukometer');
const bsStatusEl = document.getElementById('bs-status');
const bsCardEl = document.getElementById('bs-card');
const bsAdviceEl = document.getElementById('bs-advice');
const bsIconEl = document.getElementById('bs-icon');
const bsReadingEl = document.getElementById('bs-reading');
const bsRangeEl = document.getElementById('bs-range');
const bsTitleEl = document.getElementById('bs-title');
const bsClassLabel = document.getElementById('bs-class-label');

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

// Update judul analisis berdasarkan parameter terpilih
function updateTitle() {
    if (!bsTitleEl || !inputParameter) return;

    let selected = inputParameter.value;
    let titleMap = {
        'GDS': 'Gula Darah Sewaktu (GDS)',
        'GDP': 'Gula Darah Puasa (GDP)',
        'GD2PP': 'Gula Darah 2 Jam PP (GD2PP)'
    };

    let titleText = titleMap[selected] || 'Gula Darah';
    bsTitleEl.innerHTML = `Analisis <span class="text-primary">${titleText}</span>`;
}

function calculateBS() {
    let nilai = parseFloat(inputGlukometer.value);
    let parameter = inputParameter ? inputParameter.value : '';

    if (nilai > 0 && parameter) {
        let category = classifyBS(parameter, nilai);
        updateBSUI(category, nilai, parameter);
    } else {
        resetBSUI();
    }
}

// Klasifikasi Gula Darah berdasarkan jenis tes
function classifyBS(parameter, nilai) {
    switch (parameter) {
        case 'GDP': // Gula Darah Puasa
            if (nilai < 100) {
                return { status: 'Normal', level: 0 };
            } else if (nilai <= 125) {
                return { status: 'Prediabetes', level: 1 };
            } else {
                return { status: 'Diabetes', level: 2 };
            }

        case 'GD2PP': // Gula Darah 2 Jam Post Prandial
            if (nilai < 140) {
                return { status: 'Normal', level: 0 };
            } else if (nilai <= 179) {
                return { status: 'Prediabetes', level: 1 };
            } else {
                return { status: 'Diabetes', level: 2 };
            }

        case 'GDS': // Gula Darah Sewaktu
        default:
            if (nilai < 140) {
                return { status: 'Normal', level: 0 };
            } else if (nilai <= 199) {
                return { status: 'Waspada', level: 1 };
            } else {
                return { status: 'Diabetes', level: 2 };
            }
    }
}

// Palet warna premium per level
function getBSColors(level) {
    switch (level) {
        case 0: // Normal
            return { bg: '#f0fdf4', statusColor: '#059669', text: '#065f46', border: '#05966920' };
        case 1: // Prediabetes / Waspada
            return { bg: '#fffbeb', statusColor: '#d97706', text: '#92400e', border: '#d9770620' };
        case 2: // Diabetes
            return { bg: '#fef2f2', statusColor: '#dc2626', text: '#991b1b', border: '#dc262620' };
        default:
            return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
    }
}

// Ambil rentang normal berdasarkan parameter
function getNormalRange(parameter) {
    switch (parameter) {
        case 'GDP':  return '< 100';
        case 'GD2PP': return '< 140';
        case 'GDS':  return '< 140';
        default:     return '—';
    }
}

// Ambil advice berdasarkan parameter dan level
function getAdvice(parameter, level) {
    switch (parameter) {
        case 'GDP':
            if (level === 0) return { icon: 'fa-check-circle', text: 'Kadar gula puasa stabil.' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Waspada, kurangi konsumsi gula harian.' };
            return { icon: 'fa-exclamation-triangle', text: 'Indikasi Diabetes, segera cek HbA1c.' };

        case 'GD2PP':
            if (level === 0) return { icon: 'fa-check-circle', text: 'Respons gula darah setelah makan baik.' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Hati-hati, tubuh mulai lambat mengolah gula.' };
            return { icon: 'fa-exclamation-triangle', text: 'Gula darah setelah makan sangat tinggi.' };

        case 'GDS':
        default:
            if (level === 0) return { icon: 'fa-check-circle', text: 'Gula darah sewaktu Anda aman.' };
            if (level === 1) return { icon: 'fa-exclamation-circle', text: 'GDS tinggi, disarankan tes ulang kondisi Puasa.' };
            return { icon: 'fa-exclamation-triangle', text: 'Gula darah sangat tinggi, segera ke dokter.' };
    }
}

function updateBSUI(category, nilai, parameter) {
    let colors = getBSColors(category.level);

    // Klasifikasi (hero badge besar)
    if (bsStatusEl) {
        bsStatusEl.style.setProperty('color', '#fff', 'important');
        bsStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
        bsStatusEl.innerText = category.status;
    }

    // Label klasifikasi
    if (bsClassLabel) {
        bsClassLabel.innerText = 'Klasifikasi Gula Darah';
    }

    // Icon tetesan darah warna mengikuti level
    if (bsIconEl) {
        bsIconEl.style.color = colors.statusColor;
        bsIconEl.style.transition = 'color 0.4s ease';
    }

    // Kartu background
    if (bsCardEl) {
        bsCardEl.classList.remove('border-0');
        bsCardEl.style.backgroundColor = colors.bg;
        bsCardEl.style.border = `1px solid ${colors.border}`;
    }

    // Nilai glukometer reading
    if (bsReadingEl) {
        bsReadingEl.innerText = nilai;
    }

    // Rentang normal
    if (bsRangeEl) {
        bsRangeEl.innerText = getNormalRange(parameter);
    }

    // Advice
    if (bsAdviceEl) {
        let advice = getAdvice(parameter, category.level);
        bsAdviceEl.style.color = colors.text;
        bsAdviceEl.innerHTML = `<i class="fas ${advice.icon} mr-1"></i>${advice.text}`;
    }
}

function resetBSUI() {
    if (bsStatusEl) {
        bsStatusEl.innerText = '— —';
        bsStatusEl.style.setProperty('color', '#d1d3e2', 'important');
        bsStatusEl.style.setProperty('background-color', 'transparent', 'important');
    }
    if (bsClassLabel) {
        bsClassLabel.innerText = 'Klasifikasi Gula Darah';
    }
    if (bsIconEl) {
        bsIconEl.style.color = '#e3e6f0';
    }
    if (bsCardEl) {
        bsCardEl.style.backgroundColor = '#f8f9fa';
        bsCardEl.style.border = 'none';
    }
    if (bsReadingEl) bsReadingEl.innerText = '--.-';
    if (bsRangeEl) bsRangeEl.innerText = '—';
    if (bsAdviceEl) {
        bsAdviceEl.innerText = '—';
        bsAdviceEl.style.color = '';
    }
    if (bsTitleEl) {
        bsTitleEl.innerHTML = 'Analisis <span class="text-primary">Gula Darah</span>';
    }
}
});
