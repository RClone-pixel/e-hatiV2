// --- BAGIAN KALKULATOR TEKANAN DARAH ---
document.addEventListener('DOMContentLoaded', () => {
const inputSistolik = document.getElementById('sistolik');
const inputDiastolik = document.getElementById('diastolik');
const inputNadi = document.getElementById('nadi');
const mapScoreEl = document.getElementById('map-score');
const bpStatusEl = document.getElementById('bp-status');
const bpCardEl = document.getElementById('bp-card');
const bpAdviceEl = document.getElementById('bp-advice');
const bpIconEl = document.getElementById('bp-icon');
const bpReadingEl = document.getElementById('bp-reading');
const pulseReadingEl = document.getElementById('pulse-reading');
const pulseLabelEl = document.getElementById('pulse-label');

// Event Listeners (real-time calculation)
if (inputSistolik && inputDiastolik) {
    inputSistolik.addEventListener('input', calculateBP);
    inputDiastolik.addEventListener('input', calculateBP);
}
if (inputNadi) {
    inputNadi.addEventListener('input', calculateBP);
}

function calculateBP() {
    let sbp = parseFloat(inputSistolik.value);
    let dbp = parseFloat(inputDiastolik.value);
    let pulse = parseFloat(inputNadi.value);

    if (sbp > 0 && dbp > 0) {
        // Rumus MAP = (SBP + 2×DBP) / 3
        let map = (sbp + (2 * dbp)) / 3;
        if (mapScoreEl) mapScoreEl.innerText = map.toFixed(1);

        let bpCategory = classifyBP(sbp, dbp);
        let pulseCategory = classifyPulse(pulse);

        updateBPUI(bpCategory, pulseCategory, sbp, dbp, pulse);
    } else {
        resetBPUI();
    }
}

// Klasifikasi Tekanan Darah (ESC/ESH Guidelines)
function classifyBP(sbp, dbp) {
    if (sbp >= 180 || dbp >= 110) {
        return { status: 'Hipertensi Stage 3', level: 5 };
    } else if (sbp >= 160 || dbp >= 100) {
        return { status: 'Hipertensi Stage 2', level: 4 };
    } else if (sbp >= 140 || dbp >= 90) {
        return { status: 'Hipertensi Stage 1', level: 3 };
    } else if (sbp >= 130 || dbp >= 85) {
        return { status: 'High Normal', level: 2 };
    } else if (sbp >= 120 || dbp >= 80) {
        return { status: 'Normal', level: 1 };
    } else {
        return { status: 'Optimal', level: 0 };
    }
}

// Klasifikasi Denyut Nadi
function classifyPulse(pulse) {
    if (!pulse || isNaN(pulse)) return { status: '-', color: '#858796' };
    if (pulse < 60) return { status: 'Bradycardia', color: '#3b82f6' };
    if (pulse <= 100) return { status: 'Normal', color: '#10b981' };
    return { status: 'Tachycardia', color: '#ef4444' };
}

// Palet warna premium per level
function getBPColors(level) {
    switch (level) {
        case 0: // Optimal
            return { bg: '#f0fdf4', statusColor: '#059669', text: '#065f46', border: '#05966920' };
        case 1: // Normal
            return { bg: '#f0f9ff', statusColor: '#0284c7', text: '#075985', border: '#0284c720' };
        case 2: // High Normal
            return { bg: '#fffbeb', statusColor: '#d97706', text: '#92400e', border: '#d9770620' };
        case 3: // Hipertensi Stage 1
            return { bg: '#fff7ed', statusColor: '#ea580c', text: '#9a3412', border: '#ea580c20' };
        case 4: // Hipertensi Stage 2
            return { bg: '#fef2f2', statusColor: '#dc2626', text: '#991b1b', border: '#dc262620' };
        case 5: // Hipertensi Stage 3
            return { bg: '#fdf2f8', statusColor: '#be123c', text: '#881337', border: '#be123c20' };
        default:
            return { bg: '#f8f9fa', statusColor: '#858796', text: '#5a5c69', border: 'transparent' };
    }
}

function updateBPUI(bpCategory, pulseCategory, sbp, dbp, pulse) {
    let colors = getBPColors(bpCategory.level);

    // Klasifikasi (hero badge besar)
    if (bpStatusEl) {
        bpStatusEl.style.setProperty('color', '#fff', 'important');
        bpStatusEl.style.setProperty('background-color', colors.statusColor, 'important');
        bpStatusEl.innerText = bpCategory.status;
    }

    // Icon heartbeat warna mengikuti level
    if (bpIconEl) {
        bpIconEl.style.color = colors.statusColor;
        bpIconEl.style.transition = 'color 0.4s ease';
    }

    // Kartu background
    if (bpCardEl) {
        bpCardEl.classList.remove('border-0');
        bpCardEl.style.backgroundColor = colors.bg;
        bpCardEl.style.border = `1px solid ${colors.border}`;
    }

    // SBP/DBP reading
    if (bpReadingEl) {
        bpReadingEl.innerText = `${sbp}/${dbp}`;
    }

    // Pulse reading + label
    if (pulseReadingEl) {
        pulseReadingEl.innerText = pulse > 0 ? pulse : '--';
        pulseReadingEl.style.color = pulseCategory.color;
    }
    if (pulseLabelEl) {
        let pulseStatusText = pulseCategory.status !== '-' ? ` · ${pulseCategory.status}` : '';
        pulseLabelEl.innerHTML = `bpm<span style="color: ${pulseCategory.color}; font-weight: 600;">${pulseStatusText}</span>`;
    }

    // Advice
    if (bpAdviceEl) {
        bpAdviceEl.style.color = colors.text;
        if (bpCategory.level <= 1) {
            bpAdviceEl.innerHTML = `<i class="fas fa-check-circle mr-1"></i>Tekanan darah dalam batas aman.`;
        } else if (bpCategory.level === 2) {
            bpAdviceEl.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Perlu pemantauan berkala.`;
        } else {
            bpAdviceEl.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>Perlu perhatian medis lebih lanjut.`;
        }
    }
}

function resetBPUI() {
    if (mapScoreEl) mapScoreEl.innerText = '--.-';
    if (bpStatusEl) {
        bpStatusEl.innerText = '— —';
        bpStatusEl.style.setProperty('color', '#d1d3e2', 'important');
        bpStatusEl.style.setProperty('background-color', 'transparent', 'important');
    }
    if (bpIconEl) {
        bpIconEl.style.color = '#e3e6f0';
    }
    if (bpCardEl) {
        bpCardEl.style.backgroundColor = '#f8f9fa';
        bpCardEl.style.border = 'none';
    }
    if (bpReadingEl) bpReadingEl.innerText = '--/--';
    if (pulseReadingEl) {
        pulseReadingEl.innerText = '--';
        pulseReadingEl.style.color = '';
    }
    if (pulseLabelEl) pulseLabelEl.innerHTML = 'bpm';
    if (bpAdviceEl) {
        bpAdviceEl.innerText = '—';
        bpAdviceEl.style.color = '';
    }
}
});
