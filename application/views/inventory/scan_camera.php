<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Scan QR Perangkat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .slide-down { animation: slideDown 0.3s ease-out; }

    /* Light mode overrides */
    html.light .theme-card {
        background: rgba(255, 255, 255, 0.95) !important;
        border-color: rgba(203, 213, 225, 0.8) !important;
    }

    html.light .theme-bg-secondary {
        background: rgba(248, 250, 252, 0.8) !important;
    }

    html.light .theme-border {
        border-color: rgba(203, 213, 225, 0.6) !important;
    }

    html.light input[type="file"] {
        background: rgba(255, 255, 255, 0.95) !important;
        border-color: rgba(203, 213, 225, 0.8) !important;
    }

    html.light input[type="file"]::-webkit-file-upload-button {
        background: rgba(248, 250, 252, 0.95) !important;
        color: var(--text-primary) !important;
    }
</style>
</head>

<body class="bg-slate-950">

<div class="max-w-2xl mx-auto p-4">
    <!-- HEADER -->
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl mb-4 shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold header-title mb-2">Scan QR Perangkat</h1>
        <p class="header-subtitle text-sm">
            Scan QR code untuk melihat detail perangkat IT
        </p>
    </div>

    <!-- BANNER PENGEMBANGAN -->
    <div class="bg-gradient-to-r from-yellow-900/40 to-orange-900/40 border-2 border-yellow-600/50 rounded-2xl p-5 mb-6 slide-down">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400 pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-yellow-400 font-semibold mb-1 flex items-center gap-2">
                    🚧 Dalam Pengembangan
                </h3>
                <p class="text-yellow-200/80 text-sm leading-relaxed">
                    Fitur scan QR code masih dalam tahap pengembangan dan belum dapat digunakan. 
                    Kami sedang menyempurnakan sistem untuk memberikan pengalaman terbaik.
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-yellow-300/70">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Coming soon
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="theme-card backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border">
        
        <!-- NOTIF -->
        <div id="notif" class="hidden mx-6 mt-6"></div>

        <!-- SCAN SECTION -->
        <div class="p-6">
            <!-- CAMERA PREVIEW -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="header-title font-semibold">Kamera Scanner</h3>
                </div>
                
                <div class="relative bg-black rounded-xl overflow-hidden theme-border border-2" style="min-height: 300px;">
                    <div id="preview" class="w-full"></div>
                    
                    <!-- PLACEHOLDER SAAT TIDAK ADA KAMERA -->
                    <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center header-subtitle">
                        <svg class="w-20 h-20 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <p class="text-sm">Arahkan kamera ke QR code</p>
                    </div>
                </div>

                <!-- STOP CAMERA -->
                <button id="btnStop"
                        class="bg-red-600 hover:bg-red-500 px-4 py-2.5 rounded-xl w-full mt-3 font-medium hidden transition-all flex items-center justify-center gap-2 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    Stop Kamera
                </button>
            </div>

            <!-- DIVIDER -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t theme-border"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 theme-card header-subtitle">atau</span>
                </div>
            </div>

            <!-- UPLOAD SECTION -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="header-title font-semibold">Upload Gambar QR</h3>
                </div>

                <div class="space-y-3">
                    <div class="relative">
                        <input type="file"
                               id="qrFile"
                               accept="image/*"
                               class="block w-full text-sm header-subtitle
                                      file:mr-4 file:py-2.5 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-gray-800 file:text-gray-300
                                      hover:file:bg-gray-700 file:cursor-pointer
                                      cursor-pointer bg-slate-800/80 theme-border border
                                      rounded-xl p-3 hover:border-gray-700 transition-all">
                    </div>

                    <button id="btnUpload"
                            class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 px-4 py-2.5 rounded-xl w-full font-medium transition-all flex items-center justify-center gap-2 shadow-lg text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Scan dari Gambar
                    </button>
                </div>
            </div>

            <!-- DUMMY ELEMENT -->
            <div id="dummy" style="display:none"></div>
        </div>

        <!-- RESULT BOX -->
        <div id="resultBox" class="hidden theme-border border-t theme-bg-secondary p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="header-title font-semibold">Hasil Scan</h3>
            </div>
            <div id="resultContent" class="bg-slate-800/50 rounded-xl p-4 theme-border border text-sm space-y-2"></div>
        </div>

        <!-- FOOTER -->
        <div class="p-6 theme-border border-t theme-bg-secondary">
            <a href="<?= base_url('inventory') ?>"
               class="bg-gray-700 hover:bg-gray-600 px-6 py-2.5 rounded-xl inline-flex items-center gap-2 font-medium transition-all text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Inventory
            </a>
        </div>
    </div>
</div>

<!-- QR LIBRARY -->
<script src="<?= base_url('assets/js/html5-qrcode.min.js') ?>"></script>

<script>
// Helper untuk mendapatkan warna berdasarkan theme
function getThemeColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        info: isLight ? 'bg-blue-100 border-blue-400 text-blue-800' : 'bg-blue-900/40 border-blue-600/50 text-blue-300',
        success: isLight ? 'bg-green-100 border-green-400 text-green-800' : 'bg-green-900/40 border-green-600/50 text-green-300',
        error: isLight ? 'bg-red-100 border-red-400 text-red-800' : 'bg-red-900/40 border-red-600/50 text-red-300',
        warning: isLight ? 'bg-yellow-100 border-yellow-400 text-yellow-800' : 'bg-yellow-900/40 border-yellow-600/50 text-yellow-300'
    };
}

// ================= ELEMENT =================
const notif = document.getElementById('notif');
const resultBox = document.getElementById('resultBox');
const resultContent = document.getElementById('resultContent');
const btnStop = document.getElementById('btnStop');
const btnUpload = document.getElementById('btnUpload');
const qrFile = document.getElementById('qrFile');
const placeholder = document.getElementById('placeholder');

let html5QrCode = null;
let scanning = false;

// ================= NOTIF =================
function showNotif(msg, type = 'info') {
    const colors = getThemeColors();
    
    const icons = {
        info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
    };

    notif.className = `p-4 rounded-xl text-sm border-2 flex items-start gap-3 slide-down ${colors[type]}`;
    notif.innerHTML = `
        <div class="flex-shrink-0 mt-0.5">${icons[type]}</div>
        <p class="flex-1">${msg}</p>
    `;
    notif.classList.remove('hidden');
}

// ================= RESULT =================
function showResult(d) {
    resultBox.classList.remove('hidden');
    resultContent.innerHTML = `
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="header-subtitle text-xs mb-1">Nama Barang</p>
                <p class="header-title font-semibold">${d.nama_barang}</p>
            </div>
            <div>
                <p class="header-subtitle text-xs mb-1">Kode Barang</p>
                <p class="header-title font-semibold">${d.kode_barang}</p>
            </div>
            <div>
                <p class="header-subtitle text-xs mb-1">NUP</p>
                <p class="header-title font-semibold">${d.nup}</p>
            </div>
            <div>
                <p class="header-subtitle text-xs mb-1">Kondisi</p>
                <p class="header-title font-semibold">${d.kondisi ?? '-'}</p>
            </div>
            <div class="col-span-2">
                <p class="header-subtitle text-xs mb-1">Lokasi</p>
                <p class="header-title font-semibold">${d.lokasi ?? '-'}</p>
            </div>
        </div>
    `;
    resultBox.scrollIntoView({ behavior: 'smooth' });
}

// ================= BACKEND =================
function searchDevice(code) {
    if (!code) return;

    showNotif('🔍 QR terdeteksi, memuat data...', 'info');
    resultBox.classList.add('hidden');

    fetch('<?= base_url("inventory/scan") ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'code=' + encodeURIComponent(code)
    })
    .then(r => r.json())
    .then(r => {
        if (r.status === 'success') {
            showNotif('✅ Data berhasil ditemukan!', 'success');
            showResult(r.data);
        } else {
            showNotif('❌ Data tidak ditemukan dalam database', 'error');
        }
    })
    .catch(() => showNotif('⚠️ Gagal menghubungi server', 'error'));
}

// ================= START CAMERA AUTO =================
function startCamera() {
    if (scanning || typeof Html5Qrcode !== 'function') return;

    html5QrCode = new Html5Qrcode("preview");

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 5, qrbox: 300 },
        text => {
            stopCamera();
            searchDevice(text);
        }
    ).then(() => {
        scanning = true;
        placeholder.style.display = 'none';
        btnStop.classList.remove('hidden');
        showNotif('📷 Kamera aktif - arahkan ke QR code', 'success');
    }).catch(() => {
        showNotif('⚠️ Kamera tidak dapat diakses. Gunakan upload gambar sebagai alternatif.', 'warning');
    });
}

// ================= STOP CAMERA =================
function stopCamera() {
    if (!html5QrCode || !scanning) return;

    html5QrCode.stop().then(() => {
        scanning = false;
        placeholder.style.display = 'flex';
        btnStop.classList.add('hidden');
        showNotif('⏹ Kamera dihentikan', 'info');
    });
}

btnStop.onclick = stopCamera;

// ================= UPLOAD QR =================
btnUpload.onclick = () => {
    if (!qrFile.files.length) {
        showNotif('⚠️ Pilih gambar QR terlebih dahulu', 'warning');
        return;
    }

    showNotif('🔄 Memproses gambar...', 'info');
    const qr = new Html5Qrcode("dummy");
    qr.scanFile(qrFile.files[0], true)
      .then(text => searchDevice(text))
      .catch(() => showNotif('❌ QR tidak terbaca dari gambar. Pastikan gambar jelas dan fokus.', 'error'));
};

// ================= AUTO RUN =================
window.addEventListener('load', () => {
    // Tampilkan notifikasi pengembangan dulu
    showNotif('🚧 Fitur ini masih dalam tahap pengembangan dan belum dapat digunakan secara penuh.', 'warning');
    
    // Delay start camera 2 detik agar notifikasi terlihat
    setTimeout(() => {
        // startCamera(); // Uncomment saat sudah siap digunakan
    }, 2000);
});
</script>

</body>
</html>