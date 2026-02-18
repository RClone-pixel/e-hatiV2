// --- BAGIAN KALKULATOR BMI ---
document.addEventListener('DOMContentLoaded', () => {
const inputTinggi = document.getElementById('tinggi_badan');
const inputBerat  = document.getElementById('berat_badan');
const scoreEl     = document.getElementById('bmi-score');
const statusEl    = document.getElementById('bmi-status');
const cardEl      = document.getElementById('bmi-card');
const adviceEl    = document.getElementById('bmi-advice');
const idealEl     = document.getElementById('bmi-ideal');
const selisihEl   = document.getElementById('bmi-selisih');
const selisihLabelEl = document.getElementById('bmi-selisih-label');
const genderIconEl = document.getElementById('gender-icon');

// Arahkan ke input jenis kelamin
const genderInput = document.getElementById('jenis_kelamin');

// Pastikan elemen ada baru jalankan listener
if (inputTinggi && inputBerat) {
    inputTinggi.addEventListener('input', calculateBMI);
    inputBerat.addEventListener('input', calculateBMI);
}

function calculateBMI() {
    let tb = parseFloat(inputTinggi.value) / 100;
    let bb = parseFloat(inputBerat.value);

    if (tb > 0 && bb > 0) {
        let bmi = bb / (tb * tb);
        let score = bmi.toFixed(1);
        if(scoreEl) scoreEl.innerText = score;

        // Hitung Broca
        let brocaData = calculateBroca();
        updateUI(bmi, brocaData);
    } else {
        resetUI();
    }
}

// Hitung Berat Ideal Broca
function calculateBroca() {
    let tb_cm = parseFloat(inputTinggi.value);
    let bb_kg = parseFloat(inputBerat.value);

    let genderRaw = genderInput ? genderInput.value : '';
    let gender = genderRaw.toLowerCase();

    if (!tb_cm || !bb_kg) return null;

    let base = tb_cm - 100;
    let beratIdeal;

    if (gender.includes('laki') || gender === 'l') {
        beratIdeal = base - (base * 0.10);
    } else {
        beratIdeal = base - (base * 0.15);
    }

    let selisih = bb_kg - beratIdeal;

    return {
        beratIdeal: beratIdeal,
        selisih: selisih
    };
}

// Palet warna premium per kategori
function getBMIColors(bmi) {
    if (bmi < 18.5) {
        return { status: 'KURUS', bg: '#fffbf0', statusColor: '#f59e0b', text: '#92400e', border: '#f59e0b20' };
    } else if (bmi <= 25) {
        return { status: 'IDEAL', bg: '#f0fdf4', statusColor: '#10b981', text: '#065f46', border: '#10b98120' };
    } else if (bmi <= 29.9) {
        return { status: 'GEMUK', bg: '#fff7ed', statusColor: '#f97316', text: '#9a3412', border: '#f9731620' };
    } else {
        return { status: 'OBESITAS', bg: '#fef2f2', statusColor: '#ef4444', text: '#991b1b', border: '#ef444420' };
    }
}

function updateUI(bmi, brocaData) {
    let colors = getBMIColors(bmi);

    // Klasifikasi (hero badge besar)
    if (statusEl) {
        statusEl.style.setProperty('color', '#fff', 'important');
        statusEl.style.setProperty('background-color', colors.statusColor, 'important');
        statusEl.innerText = colors.status;
    }

    // Gender icon warna mengikuti kategori
    if (genderIconEl) {
        genderIconEl.style.transition = 'color 0.4s ease';
    }

    // Kartu background
    if (cardEl) {
        cardEl.classList.remove('border-0');
        cardEl.style.backgroundColor = colors.bg;
        cardEl.style.border = `1px solid ${colors.border}`;
    }

    // Stats: Berat Ideal & Selisih
    if (brocaData) {
        if (idealEl) idealEl.innerText = brocaData.beratIdeal.toFixed(1);

        if (selisihEl) {
            let abs = Math.abs(brocaData.selisih).toFixed(1);
            selisihEl.innerText = brocaData.selisih > 0 ? `+${abs}` : `-${abs}`;

            // Warna selisih
            if (Math.abs(brocaData.selisih) <= 1) {
                selisihEl.style.color = '#10b981'; // hijau
            } else if (brocaData.selisih > 0) {
                selisihEl.style.color = '#ef4444'; // merah (kelebihan)
            } else {
                selisihEl.style.color = '#f59e0b'; // amber (kekurangan)
            }
        }

        if (selisihLabelEl) {
            if (Math.abs(brocaData.selisih) <= 1) {
                selisihLabelEl.innerHTML = '<span style="color: #10b981;">Pas!</span>';
            } else if (brocaData.selisih > 0) {
                selisihLabelEl.innerHTML = 'Kelebihan';
            } else {
                selisihLabelEl.innerHTML = 'Kekurangan';
            }
        }
    }

    // Advice
    if (adviceEl) {
        adviceEl.style.color = colors.text;
        if (bmi < 18.5) {
            adviceEl.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Berat badan di bawah ideal. Perlu asupan gizi lebih.`;
        } else if (bmi <= 25) {
            adviceEl.innerHTML = `<i class="fas fa-check-circle mr-1"></i>Berat badan dalam kategori ideal. Pertahankan!`;
        } else if (bmi <= 29.9) {
            adviceEl.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Berat badan berlebih. Disarankan olahraga teratur.`;
        } else {
            adviceEl.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>Obesitas. Perlu konsultasi medis & pola hidup sehat.`;
        }
    }
}

function resetUI() {
    if (scoreEl) scoreEl.innerText = '--.-';
    if (statusEl) {
        statusEl.innerText = '— —';
        statusEl.style.setProperty('color', '#d1d3e2', 'important');
        statusEl.style.setProperty('background-color', 'transparent', 'important');
    }
    if (cardEl) {
        cardEl.style.backgroundColor = '#f8f9fa';
        cardEl.style.border = 'none';
    }
    if (idealEl) idealEl.innerText = '--.-';
    if (selisihEl) {
        selisihEl.innerText = '--.-';
        selisihEl.style.color = '';
    }
    if (selisihLabelEl) selisihLabelEl.innerHTML = 'Selisih (kg)';
    if (adviceEl) {
        adviceEl.innerText = '—';
        adviceEl.style.color = '';
    }
}
});

