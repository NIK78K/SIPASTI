<?php
$admin_name = $this->session->userdata('name');
?>

<!-- ================= FORM CONTAINER ================= -->
<div class="relative tck-card border rounded-2xl overflow-hidden shadow-2xl max-w-5xl">

    <!-- Decorative blobs -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <!-- ── HEADER ── -->
    <div class="relative tck-header border-b px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-600
                            rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400
                               bg-clip-text text-transparent">
                        Buat Tiket Baru
                    </h3>
                    <p class="text-sm tck-muted mt-1">Laporkan masalah atau ajukan permintaan layanan IT</p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 tck-badge-wrap border rounded-xl">
                <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                <div class="text-xs tck-muted">Admin</div>
                <div class="text-sm font-semibold tck-text"><?= $admin_name ?></div>
            </div>
        </div>
    </div>

    <!-- ── FORM ── -->
    <form method="post"
          action="<?= base_url('ticket/store') ?>"
          id="createForm"
          class="relative p-8 space-y-6 max-h-[70vh] overflow-y-auto tck-scrollbar">

        <!-- SECTION 1: PELAPOR -->
        <div class="tck-section-blue border rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold tck-text">Informasi Pelapor</h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Nama Pelapor <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="pelapor" required
                           value="<?= $admin_name ?>"
                           placeholder="Nama lengkap pelapor"
                           class="tck-input w-full border rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lokasi / Ruangan
                    </label>
                    <input type="text" name="lokasi"
                           placeholder="Contoh: Ruang 301, Lab Komputer"
                           class="tck-input w-full border rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
            </div>
        </div>

        <!-- SECTION 2: KATEGORI -->
        <div class="tck-section-purple border rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold tck-text">Kategori & Klasifikasi</h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Kategori -->
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <div class="tck-select-wrap">
                        <select name="kategori_id" id="kategoriSelect" required class="tck-select w-full">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($master_kategori as $k): ?>
                                <option value="<?= $k->id ?>"><?= $k->nama_kategori ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="tck-chevron">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Sub Kategori -->
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Sub Kategori
                    </label>
                    <div class="tck-select-wrap">
                        <select name="sub_kategori_id" id="subKategoriSelect" disabled
                                class="tck-select w-full tck-select-disabled">
                            <option value="">Pilih Sub Kategori</option>
                        </select>
                        <span class="tck-chevron">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Perangkat -->
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Perangkat
                    </label>
                    <input type="text" name="perangkat"
                           placeholder="Contoh: PC-LAB-01, Printer Canon"
                           class="tck-input w-full border rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all">
                </div>

            </div>
        </div>

        <!-- SECTION 3: JADWAL -->
        <div class="tck-section-cyan border rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold tck-text">Jadwal Layanan</h4>
                    <p class="text-xs tck-muted">Opsional – Untuk permintaan layanan terjadwal</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label">Tanggal</label>
                    <input type="date" name="tanggal"
                           class="tck-input w-full border rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium tck-label">Jam</label>
                    <input type="time" name="jam"
                           class="tck-input w-full border rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all">
                </div>
            </div>
        </div>

        <!-- SECTION 4: DETAIL MASALAH -->
        <div class="tck-section-orange border rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold tck-text">Detail Masalah / Kebutuhan</h4>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium tck-label flex items-center gap-2">
                    Deskripsi <span class="text-red-400">*</span>
                </label>
                <textarea name="jenis_kerusakan" rows="5" required
                          placeholder="Jelaskan masalah atau kebutuhan Anda secara detail..."
                          class="tck-input w-full border rounded-xl px-4 py-3 text-sm leading-relaxed
                                 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition-all resize-none"></textarea>
                <div class="flex justify-between items-center text-xs tck-muted">
                    <span>Jelaskan dengan detail untuk penanganan yang lebih cepat</span>
                    <span id="charCount">0 karakter</span>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-4 pt-2">
            <button type="button" id="btnCreateCancel"
                    class="tck-btn-cancel flex-1 px-6 py-4 rounded-xl text-sm font-semibold border
                           transition-all flex items-center justify-center gap-2
                           hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </button>
            <button type="button" id="btnCreateSave"
                    class="flex-1 px-6 py-4 rounded-xl text-sm font-semibold
                           bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500
                           text-white shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50
                           transition-all flex items-center justify-center gap-2
                           hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Buat Tiket
            </button>
        </div>

    </form>
</div>

<!-- ================= STYLES ================= -->
<style>
/* ── DARK default ── */
.tck-card          { background: linear-gradient(135deg,#0e141b,#0e141b,#1a1f2e); border-color: rgba(55,65,81,0.5); }
.tck-header        { background: linear-gradient(90deg,rgba(6,182,212,.08),rgba(59,130,246,.08),rgba(168,85,247,.08)); border-color: rgba(55,65,81,0.5); }
.tck-badge-wrap    { background: rgba(168,85,247,.1); border-color: rgba(168,85,247,.3); }
.tck-text          { color: #f1f5f9; }
.tck-label         { color: #cbd5e1; }
.tck-muted         { color: #64748b; }
.tck-section-blue  { background: linear-gradient(135deg,rgba(59,130,246,.05),transparent); border-color: rgba(59,130,246,.2); }
.tck-section-purple{ background: linear-gradient(135deg,rgba(168,85,247,.05),transparent); border-color: rgba(168,85,247,.2); }
.tck-section-cyan  { background: linear-gradient(135deg,rgba(6,182,212,.05),transparent); border-color: rgba(6,182,212,.2); }
.tck-section-orange{ background: linear-gradient(135deg,rgba(249,115,22,.05),transparent); border-color: rgba(249,115,22,.2); }
.tck-input {
    background: rgba(11,15,20,0.9);
    border-color: rgba(51,65,85,0.5);
    color: #f1f5f9;
}
.tck-input::placeholder { color: #475569; }
.tck-btn-cancel     { background: rgba(31,41,55,0.5); color: #d1d5db; border-color: #374151; }
.tck-btn-cancel:hover { background: rgba(55,65,81,0.5); color: #fff; border-color: #4b5563; }

/* ── SELECT WRAPPER — solusi untuk Tailwind CDN ── */
.tck-select-wrap {
    position: relative;
    width: 100%;
}

.tck-select-wrap select.tck-select {
    all: unset;
    display: block;
    width: 100%;
    box-sizing: border-box;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(51,65,85,0.5);
    background: rgba(11,15,20,0.9);
    color: #f1f5f9;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.tck-select-wrap select.tck-select:focus {
    border-color: rgba(168,85,247,0.6);
    box-shadow: 0 0 0 2px rgba(168,85,247,0.2);
}

.tck-select-wrap select.tck-select.tck-select-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Arrow icon — bukan dari CSS background, tapi dari HTML */
.tck-chevron {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #9ca3af;
    display: flex;
    align-items: center;
}

/* ── LIGHT overrides ── */
html.light .tck-card        { background: #f8fafc !important; border-color: #e2e8f0 !important; }
html.light .tck-header      { background: linear-gradient(90deg,rgba(6,182,212,.06),rgba(59,130,246,.06),rgba(168,85,247,.06)) !important; border-color: #e2e8f0 !important; }
html.light .tck-badge-wrap  { background: rgba(168,85,247,.07) !important; border-color: rgba(168,85,247,.25) !important; }
html.light .tck-text        { color: #0f172a !important; }
html.light .tck-label       { color: #334155 !important; }
html.light .tck-muted       { color: #94a3b8 !important; }
html.light .tck-section-blue   { background: rgba(219,234,254,0.4) !important; border-color: rgba(59,130,246,.2) !important; }
html.light .tck-section-purple { background: rgba(237,233,254,0.4) !important; border-color: rgba(168,85,247,.2) !important; }
html.light .tck-section-cyan   { background: rgba(207,250,254,0.3) !important; border-color: rgba(6,182,212,.2) !important; }
html.light .tck-section-orange { background: rgba(255,237,213,0.4) !important; border-color: rgba(249,115,22,.2) !important; }
html.light .tck-input {
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .tck-input::placeholder { color: #94a3b8 !important; }
html.light .tck-select-wrap select.tck-select {
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .tck-select-wrap select.tck-select:focus {
    border-color: rgba(168,85,247,0.5) !important;
    box-shadow: 0 0 0 2px rgba(168,85,247,0.15) !important;
}
html.light .tck-chevron     { color: #64748b !important; }
html.light .tck-btn-cancel  { background: #f1f5f9 !important; color: #334155 !important; border-color: #cbd5e1 !important; }
html.light .tck-btn-cancel:hover { background: #e2e8f0 !important; color: #0f172a !important; }

/* scrollbar */
.tck-scrollbar::-webkit-scrollbar       { width: 6px; }
.tck-scrollbar::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
.tck-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
html.light .tck-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }

/* input focus lift */
.tck-input:focus { transform: translateY(-1px); }

/* button ripple */
button { position: relative; overflow: hidden; }
button::before {
    content: '';
    position: absolute; top: 50%; left: 50%;
    width: 0; height: 0; border-radius: 50%;
    background: rgba(255,255,255,0.1);
    transform: translate(-50%,-50%);
    transition: width .6s, height .6s;
}
button:hover::before { width: 300px; height: 300px; }
</style>

<!-- ================= SCRIPT ================= -->
<script>
document.querySelector('textarea[name="jenis_kerusakan"]').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length + ' karakter';
});

document.getElementById('kategoriSelect').addEventListener('change', function() {
    const subSelect = document.getElementById('subKategoriSelect');
    subSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
    if (!this.value) {
        subSelect.disabled = true;
        subSelect.classList.add('tck-select-disabled');
        return;
    }
    const data = <?= json_encode($master_sub_kategori) ?>;
    data.filter(sk => sk.kategori_id == this.value).forEach(sk => {
        const opt = document.createElement('option');
        opt.value = sk.id;
        opt.textContent = sk.nama_sub_kategori;
        subSelect.appendChild(opt);
    });
    subSelect.disabled = false;
    subSelect.classList.remove('tck-select-disabled');
});

document.querySelectorAll('input[type="date"], input[type="time"]').forEach(input => {
    input.addEventListener('focus', function() { if (this.showPicker) this.showPicker(); });
});
</script>