<h2 class="tek-modal-title text-xl font-semibold mb-4">Ubah Status Teknisi</h2>

<form class="form-update-teknisi space-y-4"
      data-action="<?= base_url('teknisi/update/'.$teknisi['id']) ?>">

    <div>
        <label class="tek-label text-xs">Nama Teknisi</label>
        <input type="text"
               value="<?= $teknisi['name'] ?>"
               disabled
               class="tek-input tek-input-disabled w-full rounded-lg px-3 py-2 mt-1">
    </div>

    <div>
        <label class="tek-label text-xs">Status</label>
        <div class="tek-select-wrap mt-1">
            <select name="status" class="tek-select w-full">
                <option value="available" <?= $teknisi['status']=='available'?'selected':'' ?>>Tersedia</option>
                <option value="busy"      <?= $teknisi['status']=='busy'?'selected':'' ?>>Sedang Bertugas</option>
                <option value="offline"   <?= $teknisi['status']=='offline'?'selected':'' ?>>Offline</option>
            </select>
            <span class="tek-chevron">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-3 tek-footer border-t">
        <button type="button" onclick="closeModal()"
                class="tek-btn-cancel px-4 py-2 rounded-lg transition-colors">
            Batal
        </button>
        <button type="button"
                class="btn-save-teknisi px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
            Simpan
        </button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ── dark default ── */
.tek-modal-title  { color: #f1f5f9; }
.tek-label        { color: #9ca3af; display: block; }
.tek-footer       { border-color: #374151; }

.tek-input {
    background: #0b0f14;
    border: 1px solid #374151;
    color: #f1f5f9;
    font-size: 0.875rem;
}
.tek-input-disabled { opacity: 0.6; cursor: not-allowed; color: #9ca3af; }

.tek-select-wrap { position: relative; }
.tek-select-wrap select.tek-select {
    all: unset;
    display: block; width: 100%; box-sizing: border-box;
    background: #0b0f14;
    border: 1px solid #374151;
    color: #f1f5f9;
    border-radius: 0.5rem;
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    font-size: 0.875rem;
    cursor: pointer;
}
.tek-select-wrap select.tek-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.2); }
.tek-chevron {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: #6b7280;
    display: flex; align-items: center;
}

.tek-btn-cancel { background: #374151; color: #d1d5db; }
.tek-btn-cancel:hover { background: #4b5563; color: #fff; }

/* ── light overrides ── */
html.light .tek-modal-title  { color: #0f172a !important; }
html.light .tek-label        { color: #64748b !important; }
html.light .tek-footer       { border-color: #e2e8f0 !important; }

html.light .tek-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .tek-input-disabled { color: #64748b !important; }

html.light .tek-select-wrap select.tek-select {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .tek-chevron { color: #64748b !important; }

html.light .tek-btn-cancel { background: #f1f5f9 !important; color: #374155 !important; }
html.light .tek-btn-cancel:hover { background: #e2e8f0 !important; color: #0f172a !important; }
</style>