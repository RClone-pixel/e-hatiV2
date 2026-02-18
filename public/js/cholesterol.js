// --- BAGIAN KALKULATOR KOLESTEROL ---
document.addEventListener('DOMContentLoaded', () => {
const inputKolesterol = document.getElementById('kolesterol_total');
const cholStatusEl = document.getElementById('chol-status');
const cholCardEl = document.getElementById('chol-card');
const cholAdviceEl = document.getElementById('chol-advice');
const cholIconEl = document.getElementById('chol-icon');
const cholReadingEl = document.getElementById('chol-reading');
const cholRangeEl = document.getElementById('chol-range');

// Event Listener (real-time calculation)
if (inputKolesterol) {
    inputKolesterol.addEventListener('input', calculateChol);
}

function calculateChol() {
    let nilai = parseFloat(inputKolesterol.value);

    if (nilai > 0) {
        let category = classifyChol(nilai);
        updateCholUI(category, nilai);
    } else {
        resetCholUI();
    }
}

// Klasifikasi Kolesterol Total
function classifyChol(nilai) {
    if (nilai < 200) {
        return { status: 'Normal', level: 0 };
    } else if (nilai <= 239) {
        return { status: 'Borderline', level: 1 };
    } else {
        return { status: 'Tinggi', level: 2 };
    }
}

// Palet warna premium per level
function getCholColors(level) {
    switch (level) {
        case 0: // Normal
            return { bg: '#f0fdf4', statusColor: '#059669', text: '#065f46', border: '#05966920' };
        case 1: // Borderline
            return { bg: '#fffbeb', statusColor: '#d97706', text: '#92400e', border: '#d9770620' };
        case 2: // Tinggi
            return { bg: '#fef2f2', statusColor: '#dc2626', text: '#991b1b', border: '#dc262620' };
        default:
            return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
    }
}

// Ambil rentang normal
function getNormalRange() {
    return '< 200';
}

// Ambil advice berdasarkan level
function getAdvice(level) {
    if (level === 0) return { icon: 'fa-check-circle', text: 'Kadar kolesterol dalam batas normal.' };
    if (level === 1) return { icon: 'fa-exclamation-circle', text: 'Kolesterol borderline, perlu kontrol pola makan.' };
    return { icon: 'fa-exclamation-triangle', text: 'Kolesterol tinggi, segera konsultasi ke dokter.' };
}

function updateCholUI(category, nilai) {
    let colors = getCholColors(category.level);

    // Klasifikasi (hero badge besar)
    if (cholStatusEl) {
        cholStatusEl.style.setProperty('color', '#fff', 'important');
        cholStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
        cholStatusEl.innerText = category.status;
    }

    // Icon kolesterol warna mengikuti level
    if (cholIconEl) {
        cholIconEl.style.color = colors.statusColor;
        cholIconEl.style.transition = 'color 0.4s ease';
    }

    // Kartu background
    if (cholCardEl) {
        cholCardEl.classList.remove('border-0');
        cholCardEl.style.backgroundColor = colors.bg;
        cholCardEl.style.border = `1px solid ${colors.border}`;
    }

    // Nilai kolesterol reading
    if (cholReadingEl) {
        cholReadingEl.innerText = nilai;
    }

    // Rentang normal
    if (cholRangeEl) {
        cholRangeEl.innerText = getNormalRange();
    }

    // Advice
    if (cholAdviceEl) {
        let advice = getAdvice(category.level);
        cholAdviceEl.style.color = colors.text;
        cholAdviceEl.innerHTML = `<i class="fas ${advice.icon} mr-1"></i>${advice.text}`;
    }
}

function resetCholUI() {
    if (cholStatusEl) {
        cholStatusEl.innerText = '— —';
        cholStatusEl.style.setProperty('color', '#d1d3e2', 'important');
        cholStatusEl.style.setProperty('background-color', 'transparent', 'important');
    }
    if (cholIconEl) {
        cholIconEl.style.color = '#e3e6f0';
    }
    if (cholCardEl) {
        cholCardEl.style.backgroundColor = '#f8f9fa';
        cholCardEl.style.border = 'none';
    }
    if (cholReadingEl) cholReadingEl.innerText = '--.-';
    if (cholRangeEl) cholRangeEl.innerText = '—';
    if (cholAdviceEl) {
        cholAdviceEl.innerText = '—';
        cholAdviceEl.style.color = '';
    }
}
});
