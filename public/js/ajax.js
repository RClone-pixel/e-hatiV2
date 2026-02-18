document.addEventListener('DOMContentLoaded', () => {
    // 1. Populate Dropdown Nama Pegawai
    fetch('/ajax/pegawai')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('pegawai_id');
            if (select) {
                // Reset option dulu biar rapi
                select.innerHTML = '<option selected disabled class="text-muted">-- Pilih Pegawai --</option>';
                data.forEach(p => {
                    select.innerHTML += `<option value="${p.id}">${p.nama}</option>`;
                });
            }
        })
        .catch(err => console.error("Gagal memuat daftar pegawai:", err));

    // FUngsi Reset Form Pemeriksaan
    function resetPemeriksaanForm() {
        const fields = ['tinggi_badan', 'berat_badan', 'sistolik', 'diastolik', 'nadi', 'nilai_glukometer', 'kolesterol_total', 'asam_urat', 'catatan_dokter'];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // Reset select fields
        const selectPuasa = document.getElementById('puasa');
        if (selectPuasa) selectPuasa.value = '0';

        const selectParameter = document.getElementById('parameter_gula');
        if (selectParameter) selectParameter.selectedIndex = 0;

        // Set Tanggal Pemeriksaan ke hari ini
        const today = new Date().toISOString().split('T')[0];
        const inputTanggal = document.getElementById('tanggal_pemeriksaan');
        if (inputTanggal) inputTanggal.value = today;

        // Reset Gender Icon & Color
        const genderIcon = document.getElementById('gender-icon');
        if (genderIcon) {
            genderIcon.style.color = '#e3e6f0'; // Grayish white
            genderIcon.innerHTML = '<i class="fas fa-venus-mars"></i>';
        }

        // Trigger BMI Reset (bmi.js akan mendeteksi input kosong dan mereset UI)
        const inputBerat = document.getElementById('berat_badan');
        if (inputBerat) {
            inputBerat.dispatchEvent(new Event('input'));
        }

        // Trigger BP Reset (bloodpressure.js akan mendeteksi input kosong dan mereset UI)
        const inputSistolik = document.getElementById('sistolik');
        if (inputSistolik) {
            inputSistolik.dispatchEvent(new Event('input'));
        }

        // Trigger BS Reset (bloodsugar.js akan mendeteksi input kosong dan mereset UI)
        const inputGlukometer = document.getElementById('nilai_glukometer');
        if (inputGlukometer) {
            inputGlukometer.dispatchEvent(new Event('input'));
        }

        // Trigger Cholesterol Reset (cholesterol.js akan mendeteksi input kosong dan mereset UI)
        const inputKolesterol = document.getElementById('kolesterol_total');
        if (inputKolesterol) {
            inputKolesterol.dispatchEvent(new Event('input'));
        }

        // Reset Asam Urat fields
        const uaUmur = document.getElementById('ua_umur');
        if (uaUmur) uaUmur.value = '';

        const uaAgeBadge = document.getElementById('ua-age-badge');
        if (uaAgeBadge) {
            uaAgeBadge.innerText = '';
            uaAgeBadge.className = 'badge badge-pill ml-1';
        }

        // Trigger UA Reset
        const inputUA = document.getElementById('asam_urat');
        if (inputUA) {
            inputUA.dispatchEvent(new Event('input'));
        }
    }

    // 2. Event Listener saat Nama Pegawai dipilih
    const selectPegawai = document.getElementById('pegawai_id');
    if (selectPegawai) {
        selectPegawai.addEventListener('change', function () {
            const id = this.value;
            console.log("Pegawai dipilih, ID:", id);

            // RESET FORM PEMERIKSAAN SETIAP GANTI PEGAWAI
            resetPemeriksaanForm();

            // Validasi ID
            if (!id || id.includes('--')) {
                if (document.getElementById('pemeriksaan-section')) {
                    document.getElementById('pemeriksaan-section').disabled = true;
                }
                return;
            }

            // AKTIFKAN FORM PEMERIKSAAN
            const section = document.getElementById('pemeriksaan-section');
            if (section) {
                console.log("Mengaktifkan form pemeriksaan...");
                section.disabled = false;
            }

            // Ambil data detail pegawai via AJAX
            fetch(`/ajax/pegawai/${id}`)
                .then(res => {
                    if (!res.ok) throw new Error("Gagal mengambil data detail");
                    return res.json();
                })
                .then(p => {
                    // Isi data ke form (Read-only section)
                    if (document.getElementById('tanggal_lahir')) {
                        document.getElementById('tanggal_lahir').value = p.tanggal_lahir;
                    }

                    if (document.getElementById('umur')) {
                        document.getElementById('umur').value = p.umur;
                    }

                    if (document.getElementById('golongan_darah')) {
                        document.getElementById('golongan_darah').value = p.gol_darah;
                    }

                    if (document.getElementById('riwayat_penyakit')) {
                        document.getElementById('riwayat_penyakit').value = p.riwayat_penyakit ?? '-';
                    }

                    if (document.getElementById('jenis_kelamin')) {
                        document.getElementById('jenis_kelamin').value = p.jenis_kelamin;
                    }

                    // UPDATE ICON & WARNA GENDER
                    const genderIcon = document.getElementById('gender-icon');
                    if (genderIcon) {
                        const jk = p.jenis_kelamin ? p.jenis_kelamin.toLowerCase() : '';
                        if (jk.includes('laki') || jk === 'l') {
                            genderIcon.style.color = '#4e73df'; // Biru (Primary)
                            genderIcon.innerHTML = '<i class="fas fa-mars"></i>';
                        } else if (jk.includes('perempuan') || jk === 'p') {
                            genderIcon.style.color = '#e83e8c'; // Pink
                            genderIcon.innerHTML = '<i class="fas fa-venus"></i>';
                        } else {
                            genderIcon.style.color = '#e3e6f0';
                            genderIcon.innerHTML = '<i class="fas fa-venus-mars"></i>';
                        }
                    }

                    // Update Foto
                    const imgElement = document.getElementById('foto');
                    if (imgElement) {
                        imgElement.src = p.foto_url;
                    }

                    // Re-trigger BMI calculation if needed (though it should be empty now)
                    const inputBerat = document.getElementById('berat_badan');
                    if (inputBerat && inputBerat.value) {
                        inputBerat.dispatchEvent(new Event('input'));
                    }

                    // === ASAM URAT: Fill umur field & age badge ===
                    const uaUmurField = document.getElementById('ua_umur');
                    const uaAgeBadge = document.getElementById('ua-age-badge');
                    if (p.umur !== undefined && p.umur !== null) {
                        // Extract angka dari umur (bisa string "35 Tahun" atau angka 35)
                        let umurNum = parseInt(String(p.umur).match(/\d+/)?.[0] || 0);

                        if (uaUmurField) {
                            uaUmurField.value = umurNum;
                        }

                        // Show age category badge
                        if (uaAgeBadge) {
                            if (umurNum >= 60) {
                                uaAgeBadge.innerText = 'Lansia';
                                uaAgeBadge.className = 'badge badge-pill badge-danger ml-1';
                                uaAgeBadge.style.fontSize = '0.7rem';
                            } else {
                                uaAgeBadge.innerText = 'Dewasa';
                                uaAgeBadge.className = 'badge badge-pill badge-info ml-1';
                                uaAgeBadge.style.fontSize = '0.7rem';
                            }
                        }

                        // Dispatch event so uricacid.js recalculates
                        document.dispatchEvent(new Event('uaDataUpdated'));
                    }
                })
                .catch(err => {
                    console.error("Error Fetch Detail Pegawai:", err);
                });
        });
    }
});
