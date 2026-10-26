<?php
if (!function_exists('_parse_ticket_title_view')) {
function _parse_ticket_title_view($jenis_kerusakan, $kategori_nama) {
    if (in_array(strtolower($kategori_nama), ['gangguan', 'kerusakan'])) return null;
    if (empty($jenis_kerusakan)) return null;
    $labels = [
        'Keperluan / Agenda:','Keperluan:',
        'Tujuan Perjalanan:','Tujuan Peminjaman:',
        'Tujuan:','Detail Permintaan:',
    ];
    foreach (explode("\n", $jenis_kerusakan) as $line) {
        $line = trim($line);
        foreach ($labels as $label) {
            if (stripos($line, $label) === 0) {
                $judul = trim(substr($line, strlen($label)));
                if ($judul !== '') return $judul;
            }
        }
    }
    return null;
}
}
// Ringkasan items persediaan per tiket (tampilan saja, tanpa ubah model).
if (!function_exists('_persediaan_ringkas_view')) {
function _persediaan_ringkas_view($ci, $ticket_id) {
    if (!$ci->db->table_exists('ticket_persediaan_items')) return [];
    return $ci->db
        ->select('i.jumlah, i.nama_barang_snapshot, b.nama_barang, i.satuan_snapshot, b.satuan')
        ->from('ticket_persediaan_items i')
        ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
        ->where('i.ticket_id', (int)$ticket_id)
        ->order_by('i.id', 'ASC')
        ->limit(3)
        ->get()
        ->result_array();
}
}
// Full items persediaan per tiket (tanpa limit, untuk modal detail + cetak + ACC).
// Sertakan barang_id + jumlah_disetujui (fallback = diminta bila belum ACC).
if (!function_exists('_persediaan_full_view')) {
function _persediaan_full_view($ci, $ticket_id) {
    if (!$ci->db->table_exists('ticket_persediaan_items')) return [];
    if (!$ci->db->field_exists('keterangan', 'ticket_persediaan_items')) {
        $ci->db->query("ALTER TABLE `ticket_persediaan_items` ADD COLUMN `keterangan` VARCHAR(255) NULL AFTER `jumlah_disetujui`");
    }
    $hasAcc = $ci->db->field_exists('jumlah_disetujui', 'ticket_persediaan_items');
    $hasKet = $ci->db->field_exists('keterangan', 'ticket_persediaan_items');
    return $ci->db
        ->select('i.barang_id, i.jumlah' . ($hasAcc ? ', i.jumlah_disetujui' : '') . ($hasKet ? ', i.keterangan' : '') . ', i.nama_barang_snapshot, b.nama_barang, b.kode_barang, i.satuan_snapshot, b.satuan')
        ->from('ticket_persediaan_items i')
        ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
        ->where('i.ticket_id', (int)$ticket_id)
        ->order_by('i.id', 'ASC')
        ->get()
        ->result_array();
}
}
// Sisa stok kini per barang (cache per request; null bila tak bisa dihitung).
// ponytail: hitung di view agar modal ACC langsung tampil sisa; pindah ke controller/model bila daftar tiket membesar.
if (!function_exists('_persediaan_sisa_view')) {
function _persediaan_sisa_view($ci, $barang_id) {
    static $cache = [];
    $bid = (int)$barang_id;
    if ($bid <= 0) return null;
    if (array_key_exists($bid, $cache)) return $cache[$bid];
    $sisa = null;
    try {
        $ci->load->model('User_ticket_model');
        $sisa = $ci->User_ticket_model->get_sisa_persediaan($bid, (int)date('n'), (int)date('Y'));
    } catch (Throwable $e) { $sisa = null; }
    $cache[$bid] = $sisa;
    return $sisa;
}
}
// Paket siap JSON untuk modal ACC: barang_id, kode, nama, diminta, disetujui, keterangan, sisa, satuan.
if (!function_exists('_persediaan_acc_view')) {
function _persediaan_acc_view($ci, $ticket_id) {
    $out = [];
    foreach (_persediaan_full_view($ci, $ticket_id) as $it) {
        $diminta = (int)($it['jumlah'] ?? 0);
        $acc = (array_key_exists('jumlah_disetujui', $it) && $it['jumlah_disetujui'] !== null && $it['jumlah_disetujui'] !== '')
            ? (int)$it['jumlah_disetujui'] : $diminta;
        $out[] = [
            'barang_id'  => (int)($it['barang_id'] ?? 0),
            'kode'       => $it['kode_barang'] ?? '-',
            'nama'       => $it['nama_barang_snapshot'] ?: ($it['nama_barang'] ?? '-'),
            'satuan'     => $it['satuan_snapshot'] ?: ($it['satuan'] ?? ''),
            'diminta'    => $diminta,
            'disetujui'  => $acc,
            'keterangan' => $it['keterangan'] ?? '',
            'sisa'       => _persediaan_sisa_view($ci, $it['barang_id'] ?? 0),
        ];
    }
    return $out;
}
}
// Tandai tiket persediaan: nama sub kategori mengandung 'persediaan' atau ada marker di jenis_kerusakan.
if (!function_exists('_is_persediaan_view')) {
function _is_persediaan_view($t) {
    if (stripos($t['nama_sub_kategori'] ?? '', 'persediaan') !== false) return true;
    return strpos($t['jenis_kerusakan'] ?? '', '[PERMINTAAN BARANG PERSEDIAAN]') !== false;
}
}
// Gabung items full + flag + paket ACC ke JSON kartu detail (dipakai 4 titik kartu di file ini).
if (!function_exists('_tkn_detail_json_view')) {
function _tkn_detail_json_view($ci, $t) {
    $t['persediaan_items_full'] = _persediaan_full_view($ci, $t['id'] ?? 0);
    $t['is_persediaan'] = _is_persediaan_view($t);
    $t['persediaan_acc_items'] = $t['is_persediaan'] ? _persediaan_acc_view($ci, $t['id'] ?? 0) : [];
    return $t;
}
}
?>
<!-- ===== THEME VARIABLES TEKNISI ===== -->
<style>
/* ── Dark mode (default) ── */
:root,
html:not(.light) {
    --tkn-bg-card:        #1a2233;
    --tkn-bg-input:       #0f1623;
    --tkn-border:         rgba(255,255,255,0.10);
    --tkn-border-5:       rgba(255,255,255,0.05);
    --tkn-text-primary:   #ffffff;
    --tkn-text-secondary: #d1d5db;
    --tkn-text-muted:     #6b7280;
    --tkn-text-label:     #9ca3af;
    --tkn-input-bg:       rgba(15,22,35,0.80);
    --tkn-row-hover:      rgba(255,255,255,0.04);
    --tkn-modal-overlay:  rgba(0,0,0,0.80);
    --swal2-background:   #1a2233;
}

/* ── Light mode ── */
html.light {
    --tkn-bg-card:        #ffffff;
    --tkn-bg-input:       #f8fafc;
    --tkn-border:         rgba(0,0,0,0.10);
    --tkn-border-5:       rgba(0,0,0,0.05);
    --tkn-text-primary:   #0f172a;
    --tkn-text-secondary: #334155;
    --tkn-text-muted:     #94a3b8;
    --tkn-text-label:     #64748b;
    --tkn-input-bg:       rgba(255,255,255,0.95);
    --tkn-row-hover:      rgba(0,0,0,0.025);
    --tkn-modal-overlay:  rgba(0,0,0,0.60);
    --swal2-background:   #ffffff;
}

/* ══════════════════════════════════════════
   LIGHT MODE — GLOBAL
══════════════════════════════════════════ */
html.light .theme-card {
    background-color: var(--tkn-bg-card) !important;
    color: var(--tkn-text-primary) !important;
}
html.light .header-title    { color: var(--tkn-text-primary) !important; }
html.light .header-subtitle { color: var(--tkn-text-label)   !important; }

/* Input / Textarea / Select */
html.light input:not([type="checkbox"]):not([type="radio"]),
html.light select,
html.light textarea {
    background-color: var(--tkn-bg-input) !important;
    color: var(--tkn-text-primary) !important;
    border-color: rgba(203,213,225,0.8) !important;
}
html.light input::placeholder,
html.light textarea::placeholder { color: var(--tkn-text-muted) !important; }
html.light select option {
    background-color: #ffffff !important;
    color: #0f172a !important;
}

/* ══════════════════════════════════════════
   LIGHT MODE — MODAL
══════════════════════════════════════════ */
html.light #modalEditTiket,
html.light #modalEditZoom,
html.light #modalDetail,
html.light #modalAccPersediaan,
html.light #modalAlasanOffline {
    background-color: var(--tkn-modal-overlay) !important;
}
html.light #modalEditTiket > div,
html.light #modalEditZoom  > div,
html.light #modalDetail    > div,
html.light #modalAccPersediaan > div,
html.light #modalAlasanOffline > div {
    background-color: var(--tkn-bg-card) !important;
    border-color: var(--tkn-border) !important;
}

/* SweetAlert2 */
html.light .swal2-popup          { background-color: #ffffff !important; color: #0f172a !important; border: 1.5px solid rgba(0,0,0,0.10) !important; }
html.light .swal2-title          { color: #0f172a !important; }
html.light .swal2-html-container { color: #334155 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — TOMBOL
══════════════════════════════════════════ */

/* Detail (bg-slate-700/50) */
html.light .bg-slate-700\/50 {
    background-color: #e2e8f0 !important;
    color: #1e293b !important;
    border-color: #cbd5e1 !important;
}
html.light .hover\:bg-slate-600:hover {
    background-color: #cbd5e1 !important;
    color: #0f172a !important;
}

/* Edit Tiket (amber) */
html.light .bg-amber-600\/30 {
    background-color: #f59e0b !important;
    color: #ffffff !important;
    border-color: #d97706 !important;
}
html.light .bg-amber-600\/30 .text-amber-300,
html.light .bg-amber-600\/30 svg { color: #ffffff !important; stroke: #ffffff !important; }
html.light .hover\:bg-amber-500\/50:hover { background-color: #d97706 !important; }

/* Edit Tiket tabel selesai (bg-amber-600/20) */
html.light .bg-amber-600\/20 {
    background-color: #f59e0b !important;
    color: #ffffff !important;
    border-color: #d97706 !important;
}
html.light .bg-amber-600\/20 svg { stroke: #ffffff !important; }
html.light .hover\:bg-amber-500\/40:hover { background-color: #d97706 !important; }
html.light .text-amber-300,
html.light .text-amber-400 { color: #ffffff !important; }

/* Batal Ambil (red) */
html.light .bg-red-600\/20 {
    background-color: #ffffff !important;
    color: #dc2626 !important;
    border-color: #fca5a5 !important;
}
html.light .bg-red-600\/20 svg { stroke: #dc2626 !important; }
html.light .hover\:bg-red-500\/40:hover { background-color: #fee2e2 !important; color: #b91c1c !important; }
html.light .text-red-400 { color: #dc2626 !important; }

/* Buka Layanan (blue-600/50) */
html.light .bg-blue-600\/50 {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border-color: #1d4ed8 !important;
}
html.light .hover\:bg-blue-500:hover { background-color: #1d4ed8 !important; }

/* Edit Link / Akun Zoom (indigo-600/40) */
html.light .bg-indigo-600\/40 {
    background-color: #6366f1 !important;
    color: #ffffff !important;
    border-color: #4f46e5 !important;
}
html.light .bg-indigo-600\/40 svg { stroke: #ffffff !important; }
html.light .hover\:bg-indigo-500\/60:hover { background-color: #4f46e5 !important; }
html.light .text-indigo-300 { color: #ffffff !important; }

/* Tombol bg-gray-700 / bg-gray-800 (Batal di modal) */
html.light .bg-gray-700,
html.light button.bg-gray-700 { background-color: #e2e8f0 !important; color: #334155 !important; border-color: #cbd5e1 !important; }
html.light .bg-gray-800,
html.light button.bg-gray-800 { background-color: #e2e8f0 !important; color: #334155 !important; }
html.light .hover\:bg-gray-600:hover,
html.light .hover\:bg-gray-700:hover { background-color: #cbd5e1 !important; color: #1e293b !important; }

/* Tombol Simpan Catatan purple */
html.light .bg-purple-600\/50 {
    background-color: #9333ea !important;
    color: #ffffff !important;
    border-color: #7e22ce !important;
}
html.light .hover\:bg-purple-500:hover { background-color: #7e22ce !important; }

/* Tombol Simpan Catatan blue */
html.light .bg-blue-600\/50 {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border-color: #1d4ed8 !important;
}
html.light .hover\:bg-blue-500:hover { background-color: #1d4ed8 !important; }

/* Lihat PDF violet */
html.light .bg-violet-600\/40 {
    background-color: #7c3aed !important;
    color: #ffffff !important;
    border-color: #6d28d9 !important;
}
html.light .hover\:bg-violet-500\/60:hover { background-color: #6d28d9 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — BADGE & PILL
══════════════════════════════════════════ */

/* Badge kategori (bg-slate-700/60 text-slate-300) */
html.light .bg-slate-700\/60 {
    background-color: #e2e8f0 !important;
    color: #334155 !important;
}
html.light .text-slate-300   { color: #475569 !important; }
html.light .border-slate-600\/50 { border-color: #cbd5e1 !important; }
html.light .bg-slate-600     { background-color: #cbd5e1 !important; }
html.light .border-slate-600 { border-color: #cbd5e1 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — SECTION BOXES
══════════════════════════════════════════ */
html.light .bg-slate-800\/40 {
    background-color: rgba(241,245,249,0.90) !important;
    color: var(--tkn-text-primary) !important;
}
html.light .bg-slate-800\/80 {
    background-color: var(--tkn-bg-input) !important;
    color: var(--tkn-text-primary) !important;
}
html.light .bg-slate-700\/50.rounded-full { background-color: #e2e8f0 !important; }

/* Zoom picker inner card */
html.light .border-slate-600\/50 {
    border-color: #cbd5e1 !important;
    background-color: #f8fafc !important;
}

/* Upload label */
html.light label[class*="border-violet-500"],
html.light label[class*="border-purple-500"] {
    background-color: #f8fafc !important;
    color: #475569 !important;
    border-color: rgba(139,92,246,0.25) !important;
}

/* ══════════════════════════════════════════
   LIGHT MODE — ACCENT BOXES
══════════════════════════════════════════ */
html.light .bg-blue-500\/10   { background-color: rgba(59,130,246,0.07)  !important; }
html.light .bg-teal-500\/10   { background-color: rgba(20,184,166,0.07)  !important; }
html.light .bg-teal-500\/5    { background-color: rgba(20,184,166,0.04)  !important; }
html.light .bg-indigo-500\/10 { background-color: rgba(99,102,241,0.07)  !important; }
html.light .bg-indigo-500\/5  { background-color: rgba(99,102,241,0.04)  !important; }
html.light .bg-purple-500\/10 { background-color: rgba(168,85,247,0.07)  !important; }
html.light .bg-violet-500\/10 { background-color: rgba(139,92,246,0.07)  !important; }
html.light .bg-amber-500\/10  { background-color: rgba(245,158,11,0.07)  !important; }
html.light .bg-amber-500\/5   { background-color: rgba(245,158,11,0.04)  !important; }
html.light .bg-red-500\/10    { background-color: rgba(239,68,68,0.07)   !important; }
html.light .bg-green-500\/10  { background-color: rgba(16,185,129,0.07)  !important; }
html.light .bg-green-500\/5   { background-color: rgba(16,185,129,0.04)  !important; }
html.light .bg-cyan-500\/5    { background-color: rgba(6,182,212,0.04)   !important; }
html.light .bg-green-500\/\[0\.02\] { background-color: rgba(16,185,129,0.02) !important; }

/* Teks di dalam accent boxes */
html.light .text-blue-300   { color: #1d4ed8 !important; }
html.light .text-teal-400   { color: #0f766e !important; }
html.light .text-indigo-300,
html.light .text-indigo-400 { color: #4338ca !important; }
html.light .text-purple-300 { color: #7e22ce !important; }
html.light .text-violet-300 { color: #6d28d9 !important; }
html.light .text-cyan-400   { color: #0e7490 !important; }
html.light .text-cyan-300   { color: #475569 !important; }
html.light .text-emerald-400 { color: #059669 !important; }
html.light .text-yellow-400  { color: #b45309 !important; }
html.light .text-orange-400  { color: #c2410c !important; }
html.light .text-pink-400    { color: #be185d !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — CARD TIKET GRADIENT
══════════════════════════════════════════ */
html.light [class*="from-emerald-500\/5"] { background-color: #f0fdf9 !important; }
html.light [class*="from-yellow-500\/5"]  { background-color: #fefce8 !important; }
html.light [class*="from-blue-500\/5"]    { background-color: #eff6ff !important; }
html.light [class*="from-purple-500\/5"]  { background-color: #faf5ff !important; }
html.light [class*="from-orange-500\/5"]  { background-color: #fff7ed !important; }
html.light [class*="from-cyan-500\/5"]    { background-color: #ecfeff !important; }
html.light [class*="from-pink-500\/5"]    { background-color: #fdf2f8 !important; }
html.light [class*="from-indigo-500\/5"]  { background-color: #eef2ff !important; }
html.light [class*="from-green-500\/5"]   { background-color: #f0fdf4 !important; }

/* Stat cards */
html.light [class*="from-cyan-500\/10"]    { background-color: #f0f9ff !important; }
html.light [class*="from-emerald-500\/10"] { background-color: #ecfdf5 !important; }
html.light [class*="from-yellow-500\/10"]  { background-color: #fefce8 !important; }
html.light [class*="from-green-500\/10"]   { background-color: #f0fdf4 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — TABEL SELESAI
══════════════════════════════════════════ */
html.light thead tr    { border-color: var(--tkn-border) !important; }
html.light th          { color: var(--tkn-text-label) !important; }
html.light td          { color: var(--tkn-text-secondary) !important; }
html.light tbody tr    { border-color: var(--tkn-border-5) !important; }
html.light .hover\:bg-green-500\/\[0\.03\]:hover { background-color: rgba(0,0,0,0.02) !important; }

/* Avatar initial */
html.light .from-slate-600.to-slate-700 {
    background: linear-gradient(to bottom right, #94a3b8, #64748b) !important;
}

/* ══════════════════════════════════════════
   LIGHT MODE — PROFIL & STATUS
══════════════════════════════════════════ */
html.light #formStatusTeknisi select {
    background-color: var(--tkn-bg-input) !important;
    color: var(--tkn-text-primary) !important;
    border-color: var(--tkn-border) !important;
}
html.light #statusIndicator { background-color: var(--tkn-bg-input) !important; }
html.light #statusDot        { opacity: 1 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — LIVE INDICATOR
══════════════════════════════════════════ */
html.light .live-update-indicator {
    background-color: rgba(16,185,129,0.08) !important;
    border-color: rgba(16,185,129,0.25) !important;
}
html.light .backdrop-blur-xl.px-5 {
    background-color: var(--tkn-bg-card) !important;
    border-color: var(--tkn-border) !important;
}

/* ══════════════════════════════════════════
   LIGHT MODE — EMPTY STATE
══════════════════════════════════════════ */
html.light .bg-gray-800   { background-color: #e2e8f0 !important; }
html.light .text-gray-600 { color: #94a3b8 !important; }
html.light .text-gray-500 { color: #94a3b8 !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — RUANG RAPAT SECTION
══════════════════════════════════════════ */
html.light #editSectionRuangRapat input,
html.light #editSectionRuangRapat textarea,
html.light #editSectionRuangRapat select {
    background-color: var(--tkn-bg-input) !important;
    color: var(--tkn-text-primary) !important;
    border-color: var(--tkn-border) !important;
}
html.light #editRuangRapatInfo           { color: var(--tkn-text-secondary) !important; }
html.light #editRuangRapatInfoRuangan    { color: #3730a3 !important; }
html.light #editRuangRapatJadwalRuangan  { color: #92400e !important; }
html.light #editRuangRapatInfoJamSelesai { background-color: rgba(16,185,129,0.08) !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — MODAL OFFLINE
══════════════════════════════════════════ */
html.light #alasanOfflineInput {
    background-color: var(--tkn-bg-input) !important;
    color: var(--tkn-text-primary) !important;
    border-color: rgba(239,68,68,0.25) !important;
}
html.light #alasanOfflineInput::placeholder { color: var(--tkn-text-muted) !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — DITUGASKAN (amber tetap terbaca)
══════════════════════════════════════════ */
html.light #ticketsAssignedContainer .text-amber-400 { color: #92400e !important; }
html.light #ticketsAssignedContainer .bg-amber-500\/20 { background-color: rgba(245,158,11,0.16) !important; }
html.light .stok-kurang-badge { background-color: #fef3c7 !important; color: #92400e !important; border-color: #f59e0b !important; }

/* ══════════════════════════════════════════
   LIGHT MODE — DAFTAR BARANG PERSEDIAAN MODAL
══════════════════════════════════════════ */
html.light #modal-persediaan-container { background-color: #f0fdf9 !important; border-color: rgba(16,185,129,0.30) !important; }
html.light #modal-persediaan-container thead tr { color: #64748b !important; }
html.light #modal-persediaan-items td { color: #0f172a !important; border-color: var(--tkn-border) !important; }
html.light #btnExportPersediaanTeknisi { background: linear-gradient(to right, #059669, #10b981) !important; color: #ffffff !important; border-color: #059669 !important; }
html.light #modal-persediaan-container .text-emerald-400 { color: #059669 !important; }

/* ══════════════════════════════════════════
   CETAK FORM PERSEDIAAN TEKNISI
══════════════════════════════════════════ */
#pmPrintSheetTeknisi {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    color: #000;
    background: #fff;
}
.pm-print-kop {
    display: flex;
    align-items: center;
    border-bottom: 1.5px solid #000;
    padding-bottom: 10px;
    margin-bottom: 15px;
}
.pm-print-kop img {
    height: 64px;
    width: auto;
    object-fit: contain;
    margin-right: 15px;
}
.pm-print-kop-text {
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    line-height: 1.2;
}
.pm-print-title {
    display: block;
    width: 100%;
    font-size: 13pt;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 15px;
}
.pm-print-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 6px 20px;
    margin-bottom: 15px;
    font-size: 12px;
}
.pm-print-grid div {
    display: flex;
}
.pm-print-grid span.label {
    width: 130px;
    font-weight: normal;
}
.pm-print-grid span.sep {
    width: 10px;
}
.pm-print-grid strong {
    font-weight: normal;
}
.pm-print-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    font-size: 12px;
    margin-bottom: 25px;
}
.pm-print-table th, .pm-print-table td {
    padding: 6px 8px;
    border: 1px solid #000;
    text-align: left;
    font-weight: normal;
}
.pm-print-table th {
    background-color: #e5e7eb !important;
    font-weight: bold;
    text-align: center;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
.pm-print-table th:first-child, .pm-print-table td:first-child { width: 6%; text-align: center; }
.pm-print-table th:nth-child(2), .pm-print-table td:nth-child(2) { width: 22%; }
.pm-print-table th:nth-child(3), .pm-print-table td:nth-child(3) { width: 42%; }
.pm-print-table th:nth-child(4), .pm-print-table td:nth-child(4) { width: 15%; text-align: center; }
.pm-print-table th:nth-child(5), .pm-print-table td:nth-child(5) { width: 15%; text-align: center; }

.pm-print-signature {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    margin-top: 20px;
    page-break-inside: avoid !important;
}
.pm-print-signature > div {
    width: 200px;
    text-align: center;
}
.pm-print-signature .space {
    height: 60px;
}
.pm-print-signature strong {
    display: block;
    font-weight: bold;
}
@media screen {
    #pmPrintSheetTeknisi { display:none; }
}
@media print {
    @page { size: A4 portrait; margin: 15mm; }
    html, body { width: 210mm; height: 297mm; margin: 0 !important; padding: 0 !important; overflow: hidden !important; }
    body * { visibility: hidden !important; }
    #modalDetail, #pmPrintSheetTeknisi, #pmPrintSheetTeknisi * { visibility: visible !important; }
    #modalDetail { position: absolute !important; inset: 0 !important; display: block !important; width: 210mm !important; height: 297mm !important; padding: 0 !important; background: #fff !important; overflow: hidden !important; }
    #modalDetail > div:not(#pmPrintSheetTeknisi) { display: none !important; }
    #pmPrintSheetTeknisi { display: block !important; width: 100%; height: 100%; overflow: hidden !important; color: #000; font-family: Arial, Helvetica, sans-serif; padding: 0 !important; box-sizing: border-box !important; page-break-after: avoid !important; }
    #pmPrintSheetTeknisi, #pmPrintSheetTeknisi * { page-break-inside: avoid !important; }
}

/* ══════════════════════════════════════════
   POINTER EVENTS — RUANG RAPAT (dark + light)
══════════════════════════════════════════ */
#editSectionRuangRapat textarea,
#editSectionRuangRapat input {
    pointer-events: auto !important;
    user-select: text !important;
    -webkit-user-select: text !important;
    cursor: text !important;
}
</style>
<!-- ================= HEADER - UPDATED WITH LIVE INDICATOR ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-1 h-8 bg-gradient-to-b from-cyan-400 to-blue-600 rounded-full"></div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                    Dashboard Teknisi
                </h1>
            </div>
            <p class="header-subtitle ml-7">
                Pantau dan kelola tiket yang ditugaskan ke Anda
            </p>
        </div>

        <!-- Live Indicator -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 live-update-indicator">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-400 text-sm font-semibold" data-status="live-text">Live</span>
            </div>

            <div class="text-right theme-card backdrop-blur-xl px-5 py-3 rounded-xl border shadow-xl">
                <div class="flex items-center gap-2 text-xs header-subtitle uppercase tracking-wider mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Last Update
                </div>
                <div class="text-sm header-title font-bold" data-update="last_update"><?= date('H:i:s') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= STAT CARDS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">

    <div class="group relative theme-card bg-gradient-to-br from-cyan-500/10 via-transparent to-transparent
                border border-cyan-500/30 rounded-2xl p-6 overflow-hidden
                hover:border-cyan-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 rounded-full blur-3xl group-hover:bg-cyan-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cyan-400 uppercase tracking-wider font-semibold">Total Tiket</p>
                <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold header-title" data-stat="total_tiket"><?= count($tickets_my) ?></p>
        </div>
    </div>

    <div class="group relative theme-card bg-gradient-to-br from-emerald-500/10 via-transparent to-transparent
                border border-emerald-500/30 rounded-2xl p-6 overflow-hidden
                hover:border-emerald-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-emerald-400 uppercase tracking-wider font-semibold">Belum Diambil</p>
                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold header-title" data-stat="belum_diambil"><?= count($tickets_available) ?></p>
        </div>
    </div>

    <div class="group relative theme-card bg-gradient-to-br from-yellow-500/10 via-transparent to-transparent
                border border-yellow-500/30 rounded-2xl p-6 overflow-hidden
                hover:border-yellow-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/5 rounded-full blur-3xl group-hover:bg-yellow-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-yellow-400 uppercase tracking-wider font-semibold">On Progress</p>
                <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold header-title" data-stat="on_progress">
                <?= count(array_filter($tickets_my, fn($t)=>$t['status']==='On Progress')) ?>
            </p>
        </div>
    </div>

    <div class="group relative theme-card bg-gradient-to-br from-green-500/10 via-transparent to-transparent
                border border-green-500/30 rounded-2xl p-6 overflow-hidden
                hover:border-green-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full blur-3xl group-hover:bg-green-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-green-400 uppercase tracking-wider font-semibold">Selesai</p>
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold header-title" data-stat="selesai">
                <?= count(array_filter($tickets_my, fn($t)=>$t['status']==='Selesai')) ?>
            </p>
        </div>
    </div>

</div>

<!-- ================= PROFIL TEKNISI ================= -->
<?php if (!empty($profil)): ?>
<div class="relative theme-card backdrop-blur-sm border rounded-2xl p-6 mb-10 overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>

            <div>
                <div class="text-xs text-purple-400 mb-1 uppercase tracking-wider">Profil Teknisi</div>
                <div class="text-xl font-bold header-title"><?= $profil['name'] ?></div>
                <div class="text-sm header-subtitle mt-1">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Keahlian: <span class="text-purple-300 font-medium"><?= $profil['keahlian'] ?></span>
                    </span>
                </div>
            </div>
        </div>

        <form id="formStatusTeknisi" method="post"
              action="<?= base_url('teknisi_panel/update_status') ?>"
              class="flex items-center gap-3">
            <label class="text-sm header-subtitle">Status:</label>
            <select name="status" id="statusSelect"
                    class="bg-slate-800/80 border border-slate-700/50 rounded-xl px-4 py-2.5 text-sm header-title
                           focus:outline-none focus:ring-2 focus:ring-purple-500/50
                           hover:border-purple-400/50 transition-all cursor-pointer appearance-none pr-10 pl-4">
                <option value="available" <?= $profil['status']=='available'?'selected':'' ?>>Tersedia</option>
                <option value="busy"      <?= $profil['status']=='busy'     ?'selected':'' ?>>Sibuk</option>
                <option value="offline"   <?= $profil['status']=='offline'  ?'selected':'' ?>>Tidak Tersedia</option>
            </select>

            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border" id="statusIndicator">
                <span class="w-2 h-2 rounded-full" id="statusDot"></span>
                <span class="text-xs font-medium" id="statusLabel"></span>
            </div>
        </form>

    </div>
</div>
<?php endif; ?>

<!-- ================= DITUGASKAN KE SAYA (VIRTUAL: Menunggu + teknisi_id=self) ================= -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-1 h-6 bg-gradient-to-b from-amber-400 to-amber-600 rounded-full"></div>
        <h2 class="text-xl font-bold header-title">Ditugaskan ke Saya</h2>
        <span class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full text-xs font-semibold">
            <?= count($tickets_assigned ?? []) ?>
        </span>
        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">
            Status: Ditugaskan
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="ticketsAssignedContainer">

    <?php if (empty($tickets_assigned)): ?>
        <div class="theme-card border border-dashed rounded-2xl p-8 text-center col-span-full">
            <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="header-subtitle text-sm">Tidak ada tiket yang ditugaskan ke Anda</p>
        </div>
    <?php else: ?>
    <?php foreach ($tickets_assigned as $t): ?>
    <?php $judulAssigned = _parse_ticket_title_view($t['jenis_kerusakan'], $t['nama_kategori'] ?? ''); ?>
    <?php $itemsAssigned = _persediaan_ringkas_view($this, $t['id'] ?? 0); ?>
    <?php $detailAssigned = _tkn_detail_json_view($this, $t); ?>
    <?php $isPersediaanAssigned = !empty($detailAssigned['is_persediaan']); ?>

    <div class="ticket-assigned-card group theme-card bg-gradient-to-br from-amber-500/10 via-transparent to-transparent
                border border-amber-500/25 rounded-2xl p-5
                hover:border-amber-400/45 hover:shadow-lg hover:shadow-amber-500/10
                transition-all duration-300 flex flex-col h-full">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-lg text-xs font-mono font-semibold">
                    <?= $t['kode_tiket'] ?>
                </span>
                <span class="px-2 py-0.5 bg-amber-500/15 text-amber-400 rounded-md text-[10px] font-semibold border border-amber-500/30">
                    📌 Ditugaskan
                </span>
                <?php if (!empty($itemsAssigned)): ?>
                <span class="stok-kurang-badge px-2 py-0.5 bg-amber-500/15 text-amber-400 rounded-md text-[10px] font-semibold border border-amber-500/30" title="Tiket persediaan — stok hanya info, tidak menghambat Ambil atau Selesaikan">
                    Stok hanya info — ACC 0 sampai diminta
                </span>
                <?php endif; ?>
                <?php if (!empty($t['nama_kategori'])): ?>
                <span class="px-2 py-0.5 bg-slate-700/60 text-slate-300 rounded-md text-[10px] font-medium border border-slate-600/50">
                    <?= htmlspecialchars($t['nama_kategori']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($t['nama_sub_kategori'])): ?>
                <span class="px-2 py-0.5 bg-cyan-500/10 text-cyan-400 rounded-md text-[10px] font-medium border border-cyan-500/20">
                    <?= htmlspecialchars($t['nama_sub_kategori']) ?>
                </span>
                <?php endif; ?>
            </div>
            <?php if ($judulAssigned): ?>
            <div class="text-xs text-cyan-300 font-medium truncate mb-1" title="<?= htmlspecialchars($judulAssigned) ?>">
                📋 <?= htmlspecialchars($judulAssigned) ?>
            </div>
            <?php endif; ?>
            <div class="header-title font-medium mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 header-subtitle flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="truncate"><?= $t['pelapor'] ?></span>
            </div>
            <div class="header-subtitle text-sm flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate"><?= $t['lokasi'] ?: 'Lokasi tidak disebutkan' ?></span>
            </div>
            <?php if (!empty($itemsAssigned)): ?>
            <div class="text-[11px] header-subtitle leading-relaxed mb-1">
                📦 <?= htmlspecialchars(($itemsAssigned[0]['nama_barang_snapshot'] ?: ($itemsAssigned[0]['nama_barang'] ?? '-')) . ' x ' . (int)$itemsAssigned[0]['jumlah'] . ' ' . ($itemsAssigned[0]['satuan_snapshot'] ?: ($itemsAssigned[0]['satuan'] ?? ''))) ?><?php if (count($itemsAssigned) > 1): ?>, <?= htmlspecialchars(($itemsAssigned[1]['nama_barang_snapshot'] ?: ($itemsAssigned[1]['nama_barang'] ?? '-')) . ' x ' . (int)$itemsAssigned[1]['jumlah']) ?><?php endif; ?><?php if (count($itemsAssigned) > 2): ?> +<?= count($itemsAssigned) - 2 ?> jenis lain<?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col gap-2 mt-auto">
            <button onclick='showDetailModal(<?= json_encode($detailAssigned, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-700/50 hover:bg-slate-600
                           text-white border border-slate-600 hover:border-slate-500 transition-all duration-200">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
            </button>

            <button onclick="takeTicket(<?= $t['id'] ?>, this)"
                    data-persediaan="<?= $isPersediaanAssigned ? '1' : '0' ?>"
                    data-kode="<?= htmlspecialchars($t['kode_tiket'] ?? '') ?>"
                    data-acc='<?= json_encode($detailAssigned['persediaan_acc_items'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                    class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-amber-600 to-amber-500
                           hover:from-amber-500 hover:to-amber-400 text-white font-medium
                           shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition-all duration-200">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ambil<?= $isPersediaanAssigned ? ' &amp; ACC' : '' ?>
            </button>
            <?php if ($isPersediaanAssigned): ?>
            <p class="text-[11px] header-subtitle leading-relaxed text-center">Klik Ambil untuk cek jumlah yang disetujui. Stok hanya info.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php endforeach; endif; ?>
    </div>
</div>

<!-- ================= TIKET BELUM DIAMBIL ================= -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-1 h-6 bg-gradient-to-b from-emerald-400 to-emerald-600 rounded-full"></div>
        <h2 class="text-xl font-bold header-title">Tiket Belum Diambil</h2>
        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-semibold">
            <?= count($tickets_available) ?>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="ticketsAvailableContainer">

    <?php if (empty($tickets_available)): ?>
        <div class="theme-card border border-dashed rounded-2xl p-8 text-center col-span-full">
            <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <p class="header-subtitle text-sm">Tidak ada tiket tersedia saat ini</p>
        </div>
    <?php else:
        $maxDisplay   = 12;
        $displayCount = 0;
        foreach ($tickets_available as $t):
            $displayCount++;
            $isHidden = $displayCount > $maxDisplay ? 'hidden' : '';
            $detailAvail = _tkn_detail_json_view($this, $t);
            $isPersediaanAvail = !empty($detailAvail['is_persediaan']);
    ?>

    <div class="ticket-available-card <?= $isHidden ?> group theme-card bg-gradient-to-br from-emerald-500/5 via-transparent to-transparent
                border border-emerald-500/20 rounded-2xl p-5
                hover:border-emerald-400/40 hover:shadow-lg hover:shadow-emerald-500/10
                transition-all duration-300 flex flex-col h-full">
        <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-3">
    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-lg text-xs font-mono font-semibold">
        <?= $t['kode_tiket'] ?>
    </span>
    <?php if (!empty($t['nama_kategori'])): ?>
    <span class="px-2 py-0.5 bg-slate-700/60 text-slate-300 rounded-md text-[10px] font-medium border border-slate-600/50">
        <?= htmlspecialchars($t['nama_kategori']) ?>
    </span>
    <?php endif; ?>
    <?php if (!empty($t['nama_sub_kategori'])): ?>
    <span class="px-2 py-0.5 bg-cyan-500/10 text-cyan-400 rounded-md text-[10px] font-medium border border-cyan-500/20">
        <?= htmlspecialchars($t['nama_sub_kategori']) ?>
    </span>
    <?php endif; ?>
</div>
<?php $judul = _parse_ticket_title_view($t['jenis_kerusakan'], $t['nama_kategori'] ?? ''); ?>
<?php if ($judul): ?>
<div class="text-xs text-cyan-300 font-medium truncate mb-1" title="<?= htmlspecialchars($judul) ?>">
    📋 <?= htmlspecialchars($judul) ?>
</div>
<?php endif; ?>
            <div class="header-title font-medium mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 header-subtitle flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="truncate"><?= $t['pelapor'] ?></span>
            </div>
            <div class="header-subtitle text-sm flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate"><?= $t['lokasi'] ?: 'Lokasi tidak disebutkan' ?></span>
            </div>
        </div>

        <div class="flex flex-col gap-2 mt-auto">
            <?php
            $jk_avail = $t['jenis_kerusakan'] ?? '';
            $isBlockingTicket = (
                strpos($jk_avail, '[ref:peminjaman_id=')           !== false ||
                strpos($jk_avail, '[ref:peminjaman_kendaraan_id=') !== false ||
                strpos($jk_avail, '[ref:peminjaman_bmn_id=')       !== false
            );
            ?>

            <button onclick='showDetailModal(<?= json_encode($detailAvail, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, {hideDisetujui:true})'
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-700/50 hover:bg-slate-600
                           text-white border border-slate-600 hover:border-slate-500 transition-all duration-200">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
            </button>

            <button onclick="bukaEditTiket(<?= $t['id'] ?>)"
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-amber-600/30 hover:bg-amber-500/50
                           text-amber-300 hover:text-white border border-amber-500/30 hover:border-amber-400
                           transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Tiket
            </button>

            <?php if ($isBlockingTicket): ?>
            <button onclick="batalkanTiketMenunggu(<?= $t['id'] ?>, '<?= htmlspecialchars($t['kode_tiket']) ?>')"
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-red-600/20 hover:bg-red-500/40
                           text-red-400 hover:text-white border border-red-500/30 hover:border-red-400
                           transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Batalkan Permintaan
            </button>
            <?php endif; ?>

            <button onclick="takeTicket(<?= $t['id'] ?>, this)"
                    data-persediaan="<?= $isPersediaanAvail ? '1' : '0' ?>"
                    data-kode="<?= htmlspecialchars($t['kode_tiket'] ?? '') ?>"
                    data-acc='<?= json_encode($detailAvail['persediaan_acc_items'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                    class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-emerald-600 to-emerald-500
                           hover:from-emerald-500 hover:to-emerald-400 text-white font-medium
                           shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all duration-200">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ambil<?= $isPersediaanAvail ? ' &amp; ACC' : '' ?>
            </button>
            <?php if ($isPersediaanAvail): ?>
            <p class="text-[11px] header-subtitle leading-relaxed text-center">Klik Ambil untuk atur jumlah yang disetujui. Stok hanya info.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php endforeach; endif; ?>
    </div>

    <!-- Load More/Less Buttons -->
    <?php if (isset($maxDisplay) && count($tickets_available) > $maxDisplay): ?>
    <div class="flex justify-center mt-6 gap-3">
        <button id="loadMoreAvailable" onclick="loadMoreTickets('available')"
                class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600/20 to-emerald-500/20
                       hover:from-emerald-600/30 hover:to-emerald-500/30
                       text-emerald-400 font-medium border border-emerald-500/30
                       transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
            Tampilkan Lebih Banyak
            <span class="px-2 py-0.5 bg-emerald-500/20 rounded-full text-xs">
                +<?= count($tickets_available) - $maxDisplay ?> tiket
            </span>
        </button>

        <button id="showLessAvailable" onclick="showLessTickets('available')" style="display:none;"
                class="px-6 py-3 rounded-xl bg-gradient-to-r from-gray-600/20 to-gray-500/20
                       hover:from-gray-600/30 hover:to-gray-500/30
                       header-subtitle font-medium border border-gray-500/30
                       transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
            Tampilkan Lebih Sedikit
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- ================= ON PROGRESS ================= -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-1 h-6 bg-gradient-to-b from-yellow-400 to-yellow-600 rounded-full"></div>
        <h2 class="text-xl font-bold header-title">Tiket Dalam Pengerjaan</h2>
        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
            <?= count(array_filter($tickets_my, fn($t)=>$t['status']==='On Progress')) ?>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <?php
    $hasProgress  = false;
    $sub_zoom_ids = [];

    // Ambil semua sub_kategori yang perlu_jadwal = 1 (untuk identifikasi tiket Zoom)
  $sub_jadwal_rows = $this->db
    ->select('id')
    ->where('perlu_jadwal', 1)
    ->where('slug !=', 'permintaan-ruang-rapat')
    ->get('master_sub_kategori')
    ->result_array();
    foreach ($sub_jadwal_rows as $row) $sub_zoom_ids[] = (int)$row['id'];
// Ambil id sub kategori ruang rapat
$id_sub_ruang_rapat = $this->db
    ->select('id')
    ->where('slug', 'permintaan-ruang-rapat')
    ->get('master_sub_kategori')
    ->row_array()['id'] ?? 0;
$id_sub_ruang_rapat = (int)$id_sub_ruang_rapat;
foreach ($tickets_my as $t):
        if ($t['status'] !== 'On Progress') continue;
        $hasProgress = true;

        // Apakah tiket kategori permintaan?
        $isPermintaan = strtolower($t['nama_kategori'] ?? '') === 'permintaan';

        // ★ Deteksi ruang rapat DULU
     $isRuangRapat = $isPermintaan && (
    strpos($t['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') !== false ||
    (int)($t['sub_kategori_id'] ?? 0) === $id_sub_ruang_rapat
);

        // ★ Baru cek Zoom (pakai $isRuangRapat yang sudah ada)
        $isZoomTicket = $isPermintaan && !$isRuangRapat && 
            in_array((int)($t['sub_kategori_id'] ?? 0), $sub_zoom_ids);

        // Sudah ada link Zoom?
        $sudahAdaLink = !empty($t['layanan_link']);

        // Paket ACC persediaan untuk tombol Ubah ACC (fallback = diminta bila belum ACC).
        $detailProgress = _tkn_detail_json_view($this, $t);
        $isPersediaanProgress = !empty($detailProgress['is_persediaan']);
    ?>

    <div class="theme-card bg-gradient-to-br from-yellow-500/5 via-transparent to-transparent
                border border-yellow-500/20 rounded-2xl p-5 flex flex-col h-full">

        <!-- ── HEADER INFO TIKET ── -->
        <div class="flex-1 mb-3">
          <div class="flex flex-wrap items-center gap-2 mb-3">
    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-mono font-semibold">
        <?= $t['kode_tiket'] ?>
    </span>
    <span class="px-3 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
        ⚡ On Progress
    </span>
    <?php if ($isZoomTicket): ?>
    <span class="px-2 py-0.5 rounded-md text-[10px] bg-teal-500/20 text-teal-400 border border-teal-500/30 font-semibold">
        🎥 Zoom
    </span>
    <?php endif; ?>

    <!-- TAMBAHKAN INI -->
    <?php if (!empty($t['nama_kategori'])): ?>
    <span class="px-2 py-0.5 bg-slate-700/60 text-slate-300 rounded-md text-[10px] font-medium border border-slate-600/50">
        <?= htmlspecialchars($t['nama_kategori']) ?>
    </span>
    <?php endif; ?>
    <?php if (!empty($t['nama_sub_kategori'])): ?>
    <span class="px-2 py-0.5 bg-cyan-500/10 text-cyan-400 rounded-md text-[10px] font-medium border border-cyan-500/20">
        <?= htmlspecialchars($t['nama_sub_kategori']) ?>
    </span>
    <?php endif; ?>
</div>
<?php $judul = _parse_ticket_title_view($t['jenis_kerusakan'], $t['nama_kategori'] ?? ''); ?>
<?php if ($judul): ?>
<div class="text-xs text-cyan-300 font-medium truncate mb-1" title="<?= htmlspecialchars($judul) ?>">
    📋 <?= htmlspecialchars($judul) ?>
</div>
<?php endif; ?>
            <?php if ($isZoomTicket && !empty($t['tanggal'])): ?>
            <div class="header-subtitle text-xs flex items-center gap-2 mb-3">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-teal-400 font-medium">
                    <?= date('d M Y', strtotime($t['tanggal'])) ?>
                    <?= !empty($t['jam']) ? '· Pukul ' . $t['jam'] . ' WIB' : '' ?>
                </span>
            </div>
            <?php else: ?>
            <div class="mb-3"></div>
            <?php endif; ?>
        </div>

        <!-- ── AKSI ── -->
        <div class="flex flex-col gap-2 mt-auto">

            <!-- Tombol Detail -->
            <button onclick='showDetailModal(<?= json_encode($detailProgress, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                    class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-700/50 hover:bg-slate-600
                           text-white border border-slate-600 hover:border-slate-500 transition-all duration-200">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detail
            </button>
<button onclick="bukaEditTiket(<?= $t['id'] ?>, this)"
        data-acc='<?= json_encode($detailProgress['persediaan_acc_items'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
        data-kode="<?= htmlspecialchars($t['kode_tiket'] ?? '', ENT_QUOTES) ?>"
        data-persediaan="<?= $isPersediaanProgress ? '1' : '0' ?>"
        class="w-full px-4 py-2.5 rounded-xl text-sm bg-amber-600/30 hover:bg-amber-500/50
               text-amber-300 hover:text-white border border-amber-500/30 hover:border-amber-400
               transition-all duration-200 flex items-center justify-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
    Edit Tiket
</button>

<!-- Batal Ambil Tiket -->
<button onclick="batalAmbilTiket(<?= $t['id'] ?>)"
        class="w-full px-4 py-2.5 rounded-xl text-sm bg-red-600/20 hover:bg-red-500/40
               text-red-400 hover:text-white border border-red-500/30 hover:border-red-400
               transition-all duration-200 flex items-center justify-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
    </svg>
    Batal Ambil
</button>
            <?php /* ================================================
               CABANG A: TIKET PERMINTAAN (Zoom / layanan online)
            ================================================ */ ?>
          <?php if ($isPermintaan): ?>

    <?php if ($isRuangRapat):
        $lines_rr = explode("\n", $t['jenis_kerusakan']);
        $info_rr  = [];
        $catatan_admin_existing = '';
        foreach ($lines_rr as $line) {
            $line = trim($line);
            if (strpos($line, '[ref:') === 0) continue;
            if (strpos($line, 'Catatan Admin:') === 0) {
                $catatan_admin_existing = trim(substr($line, strlen('Catatan Admin:')));
                continue;
            }
            if ($line) $info_rr[] = $line;
        }
    ?>

    <!-- ── INFO RUANG RAPAT ── -->
    <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-xl p-3 mb-2">
        <p class="text-indigo-300 text-xs font-semibold mb-2 flex items-center gap-2">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
            Detail Peminjaman Ruang Rapat
        </p>
        <div class="space-y-1">
            <?php foreach (array_slice($info_rr, 0, 7) as $baris): ?>
            <p class="text-xs text-gray-300 leading-relaxed"><?= htmlspecialchars($baris) ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── FORM CATATAN ADMIN ── -->
    <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-3">
        <p class="text-purple-300 text-xs mb-2 flex items-center gap-2">
            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Catatan Penanganan
        </p>
        <form id="formRuangRapat<?= $t['id'] ?>" class="flex flex-col gap-2">
            <textarea name="catatan_admin" rows="3"
                      placeholder="Contoh: Ruangan sudah disiapkan, AC dinyalakan, proyektor terpasang..."
                      class="w-full bg-slate-800/80 border border-purple-500/30 rounded-lg
                             px-3 py-2 text-xs header-title
                             focus:outline-none focus:ring-2 focus:ring-purple-500/50
                             focus:border-purple-500/50 resize-none transition-all"><?= htmlspecialchars($catatan_admin_existing) ?></textarea>
            <button type="button" onclick="simpanCatatanRuangRapat(<?= $t['id'] ?>)"
                    class="w-full px-4 py-2 text-xs rounded-lg bg-purple-600/50
                           hover:bg-purple-500 text-white font-medium
                           border border-purple-500/50 transition-all duration-200">
                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Catatan
            </button>
        </form>
    </div>
<!-- ── UPLOAD DOKUMEN PESERTA (RUANG RAPAT) ── -->
    <div class="bg-violet-500/10 border border-violet-500/30 rounded-xl p-3">
        <p class="text-violet-300 text-xs mb-2 flex items-center gap-2">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Dokumen Daftar Peserta (PDF)
        </p>
        <?php if (!empty($t['dokumen_peserta'])): ?>
        <div class="flex items-center gap-2 mb-2">
            <a href="<?= base_url('uploads/dokumen_peserta/'.$t['dokumen_peserta']) ?>"
               target="_blank"
               class="flex-1 text-center px-3 py-1.5 text-xs rounded-lg
                      bg-violet-600/40 text-violet-300 border border-violet-500/40
                      hover:bg-violet-500/60 transition-all">
                📄 Lihat PDF
            </a>
            <button onclick="hapusDokumenTeknisi(<?= $t['id'] ?>)"
                    class="px-3 py-1.5 text-xs rounded-lg bg-red-600/30
                           text-red-400 border border-red-500/30 hover:bg-red-500/50 transition-all">
                🗑️
            </button>
        </div>
        <?php endif ?>
        <input type="file" name="dokumen_peserta"
               id="fileDokTeknisi<?= $t['id'] ?>"
               accept=".pdf" class="hidden"
               onchange="uploadDokumenTeknisi(<?= $t['id'] ?>, this)">
        <label for="fileDokTeknisi<?= $t['id'] ?>"
               class="flex items-center justify-center gap-2 w-full px-3 py-2 text-xs
                      bg-slate-800/80 border border-violet-500/30 rounded-lg
                      text-violet-300 hover:bg-violet-500/10 cursor-pointer transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <?= !empty($t['dokumen_peserta']) ? 'Ganti PDF' : 'Unggah PDF' ?>
            <span class="text-gray-500">(max 2MB)</span>
        </label>
    </div>

    <!-- Tombol Selesaikan -->
    <button onclick="selesaikanTiket(<?= $t['id'] ?>)"
            class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-green-600 to-green-500
                   hover:from-green-500 hover:to-green-400 text-white font-medium
                   shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all duration-200">
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Selesaikan
    </button>

    <?php elseif ($isZoomTicket): ?>

        <?php if (!$sudahAdaLink): ?>
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-3">
            <p class="text-blue-300 text-xs mb-3 flex items-center gap-2">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Isi detail sesi Zoom
            </p>

            <?php if ($this->session->flashdata('error_zoom_' . $t['id'])): ?>
            <div class="mb-2 text-red-400 text-[10px] bg-red-500/10 border border-red-500/20 rounded-lg px-2 py-1.5 flex items-center gap-1.5">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <?= $this->session->flashdata('error_zoom_' . $t['id']) ?>
            </div>
            <?php endif; ?>

            <form method="post"
                  action="<?= base_url('teknisi_panel/set_layanan/' . $t['id']) ?>"
                  class="flex flex-col gap-2">

                <input type="url" name="layanan_link" required
                       placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                       class="w-full bg-slate-800/80 border border-blue-500/30 rounded-lg
                              px-3 py-2 text-xs header-title
                              focus:outline-none focus:ring-2 focus:ring-blue-500/50
                              focus:border-blue-500/50 transition-all">
                <div class="mt-1">
                    <div class="text-[10px] text-blue-300 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342
                                     m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316
                                     m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684
                                     3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Link Kirim Undangan <span class="text-gray-500 font-normal">(opsional)</span>
                    </div>
                    <textarea name="undangan_link" rows="3"
                          placeholder="https://... link undangan untuk disalin peserta"
                          class="w-full bg-slate-800/80 border border-blue-500/20 rounded-lg
                                 px-3 py-2 text-xs header-title
                                 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                                 focus:border-blue-500/40 resize-none transition-all"></textarea>
                    <p class="text-[10px] text-gray-600 mt-0.5">Link ini hanya bisa disalin oleh user, tidak bisa diklik</p>
                </div>

                <div class="text-[10px] text-blue-300 font-medium mt-1">Pilih Akun Zoom:</div>
                <div class="grid grid-cols-2 gap-2" id="zoomPicker_<?= $t['id'] ?>">
                    <label class="cursor-pointer select-none" id="label_z1_<?= $t['id'] ?>">
                        <input type="radio" name="akun_zoom" value="1" required
                               class="hidden peer" id="radio_z1_<?= $t['id'] ?>">
                        <div class="border border-slate-600/50 rounded-lg p-2.5 text-center transition-all
                                    peer-checked:border-blue-400 peer-checked:bg-blue-500/20
                                    hover:border-blue-400/50">
                            <div class="text-xs font-bold text-blue-400">📹 Zoom 1</div>
                            <div class="text-[10px] mt-0.5 text-gray-400" id="z1info_<?= $t['id'] ?>">Memuat...</div>
                            <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full transition-all" id="z1bar_<?= $t['id'] ?>" style="width:0%"></div>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer select-none" id="label_z2_<?= $t['id'] ?>">
                        <input type="radio" name="akun_zoom" value="2" required
                               class="hidden peer" id="radio_z2_<?= $t['id'] ?>">
                        <div class="border border-slate-600/50 rounded-lg p-2.5 text-center transition-all
                                    peer-checked:border-indigo-400 peer-checked:bg-indigo-500/20
                                    hover:border-indigo-400/50">
                            <div class="text-xs font-bold text-indigo-400">📹 Zoom 2</div>
                            <div class="text-[10px] mt-0.5 text-gray-400" id="z2info_<?= $t['id'] ?>">Memuat...</div>
                            <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full transition-all" id="z2bar_<?= $t['id'] ?>" style="width:0%"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <script>
                (function () {
                    var tid     = <?= (int)$t['id'] ?>;
                    var tanggal = '<?= addslashes($t['tanggal'] ?? '') ?>';
                    if (!tanggal) {
                        ['z1info_', 'z2info_'].forEach(function (prefix) {
                            var el = document.getElementById(prefix + tid);
                            if (el) el.textContent = 'Tidak ada jadwal';
                        });
                        return;
                    }
                    fetch('<?= base_url("teknisi_panel/cek_slot_zoom") ?>?tanggal=' + encodeURIComponent(tanggal))
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (data.status !== 'success') return;
                            function updateZoom(prefix, radioId, labelId, barId, sisa, pakai, color) {
                                var info  = document.getElementById(prefix + tid);
                                var bar   = document.getElementById(barId + tid);
                                var radio = document.getElementById(radioId + tid);
                                var label = document.getElementById(labelId + tid);
                                if (info) {
                                    info.textContent = sisa + '/5 slot tersisa';
                                    info.className   = 'text-[10px] mt-0.5 ' + (sisa > 0 ? 'text-emerald-400' : 'text-red-400');
                                }
                                if (bar) {
                                    bar.style.width = (pakai / 5 * 100) + '%';
                                    bar.className   = 'h-full rounded-full transition-all ' +
                                        (sisa === 0 ? 'bg-red-500' : (sisa <= 2 ? 'bg-yellow-500' : color));
                                }
                                if (sisa === 0 && radio) {
                                    radio.disabled = true;
                                    if (label) { label.style.pointerEvents = 'none'; label.style.opacity = '0.4'; label.style.cursor = 'not-allowed'; }
                                }
                            }
                            updateZoom('z1info_', 'radio_z1_', 'label_z1_', 'z1bar_', data.zoom1.sisa, data.zoom1.terpakai, 'bg-blue-500');
                            updateZoom('z2info_', 'radio_z2_', 'label_z2_', 'z2bar_', data.zoom2.sisa, data.zoom2.terpakai, 'bg-indigo-500');
                        })
                        .catch(function () {
                            ['z1info_', 'z2info_'].forEach(function (prefix) {
                                var el = document.getElementById(prefix + tid);
                                if (el) { el.textContent = 'Gagal memuat'; el.className = 'text-[10px] mt-0.5 text-red-400'; }
                            });
                        });
                })();
                </script>

                <textarea name="layanan_catatan" rows="2"
                          placeholder="Catatan: Meeting ID, Password, instruksi peserta, dll..."
                          class="w-full bg-slate-800/80 border border-blue-500/30 rounded-lg
                                 px-3 py-2 text-xs header-title
                                 focus:outline-none focus:ring-2 focus:ring-blue-500/50
                                 focus:border-blue-500/50 resize-none transition-all"></textarea>

                <button type="submit"
                        class="w-full px-4 py-2 text-xs rounded-lg font-medium text-white transition-all
                               bg-gradient-to-r from-emerald-600 to-emerald-500
                               hover:from-emerald-500 hover:to-emerald-400
                               shadow-lg shadow-emerald-500/30">
                    ✅ Simpan &amp; Mulai Sesi
                </button>
            </form>
        </div>

        <?php else: ?>
        <a href="<?= htmlspecialchars($t['layanan_link']) ?>" target="_blank"
           class="w-full px-4 py-2.5 rounded-xl text-sm bg-blue-600/50 hover:bg-blue-500
                  text-white border border-blue-500/50 hover:border-blue-400
                  transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Buka Layanan
        </a>

        <?php
        $nama_meeting_btn = '';
        $jk = $t['jenis_kerusakan'] ?? '';
        foreach (explode("\n", $jk) as $line) {
            if (strpos(trim($line), 'Detail Permintaan:') === 0) {
                $nama_meeting_btn = addslashes(trim(substr(trim($line), strlen('Detail Permintaan:'))));
                break;
            }
        }
        ?>
        <button onclick="bukaEditZoom(
            <?= $t['id'] ?>,
            '<?= htmlspecialchars($t['layanan_link'], ENT_QUOTES) ?>',
            <?= (int)($t['akun_zoom'] ?? 1) ?>,
            '<?= htmlspecialchars($t['layanan_catatan'] ?? '', ENT_QUOTES) ?>',
            '<?= $nama_meeting_btn ?>',
            '<?= addslashes($t['tanggal'] ?? '') ?>',
            '<?= htmlspecialchars($t['undangan_link'] ?? '', ENT_QUOTES) ?>'
        )"
                class="w-full px-4 py-2.5 rounded-xl text-sm bg-indigo-600/40 hover:bg-indigo-500/60
                       text-indigo-300 hover:text-white border border-indigo-500/40 hover:border-indigo-400
                       transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Link / Akun Zoom
        </button>
<!-- ── UPLOAD DOKUMEN PESERTA (ZOOM) ── -->
        <div class="bg-violet-500/10 border border-violet-500/30 rounded-xl p-3">
            <p class="text-violet-300 text-xs mb-2 flex items-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Dokumen Daftar Peserta (PDF)
            </p>
            <?php if (!empty($t['dokumen_peserta'])): ?>
            <div class="flex items-center gap-2 mb-2">
                <a href="<?= base_url('uploads/dokumen_peserta/'.$t['dokumen_peserta']) ?>"
                   target="_blank"
                   class="flex-1 text-center px-3 py-1.5 text-xs rounded-lg
                          bg-violet-600/40 text-violet-300 border border-violet-500/40
                          hover:bg-violet-500/60 transition-all">
                    📄 Lihat PDF
                </a>
                <button onclick="hapusDokumenTeknisi(<?= $t['id'] ?>)"
                        class="px-3 py-1.5 text-xs rounded-lg bg-red-600/30
                               text-red-400 border border-red-500/30 hover:bg-red-500/50 transition-all">
                    🗑️
                </button>
            </div>
            <?php endif ?>
            <input type="file" name="dokumen_peserta"
                   id="fileDokTeknisi<?= $t['id'] ?>"
                   accept=".pdf" class="hidden"
                   onchange="uploadDokumenTeknisi(<?= $t['id'] ?>, this)">
            <label for="fileDokTeknisi<?= $t['id'] ?>"
                   class="flex items-center justify-center gap-2 w-full px-3 py-2 text-xs
                          bg-slate-800/80 border border-violet-500/30 rounded-lg
                          text-violet-300 hover:bg-violet-500/10 cursor-pointer transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <?= !empty($t['dokumen_peserta']) ? 'Ganti PDF' : 'Unggah PDF' ?>
                <span class="text-gray-500">(max 2MB)</span>
            </label>
        </div>

        <button onclick="selesaikanTiket(<?= $t['id'] ?>)"
  
                class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-green-600 to-green-500
                       hover:from-green-500 hover:to-green-400 text-white font-medium
                       shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all duration-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Selesaikan
        </button>
        <?php endif; /* sudahAdaLink */ ?>

    <?php else: /* PERMINTAAN NON-ZOOM NON-RUANGRAPAT */ ?>
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-3">
            <p class="text-blue-300 text-xs mb-2 flex items-center gap-2">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Catatan Penyelesaian (Opsional)</span>
            </p>
            <form id="formCatatanPermintaan<?= $t['id'] ?>" class="flex flex-col gap-2" enctype="multipart/form-data">
                <textarea name="layanan_catatan"
                          placeholder="Tambahkan catatan penyelesaian permintaan..."
                          class="w-full bg-slate-800/80 border border-blue-500/30 rounded-lg px-3 py-2 text-xs header-title
                                 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 resize-none"
                          rows="3"></textarea>
                <div class="relative">
                    <input type="file" name="layanan_file" id="file<?= $t['id'] ?>"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden">
                    <label for="file<?= $t['id'] ?>"
                           class="flex items-center justify-center gap-2 w-full px-3 py-2 text-xs
                                  bg-slate-800/80 border border-blue-500/30 rounded-lg
                                  text-blue-300 hover:bg-blue-500/10 cursor-pointer transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span id="fileName<?= $t['id'] ?>">
                            <?php if (!empty($t['layanan_file'])): ?>
                                <?= htmlspecialchars($t['layanan_file']) ?>
                            <?php else: ?>
                                Pilih File (JPG, PNG, PDF, DOC)
                            <?php endif; ?>
                        </span>
                    </label>
                </div>
                <button type="button" onclick="simpanCatatan(<?= $t['id'] ?>)"
                        class="w-full px-4 py-2 text-xs rounded-lg bg-blue-600/50 hover:bg-blue-500
                               text-white font-medium border border-blue-500/50 transition-all duration-200">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Catatan
                </button>
            </form>
        </div>
        <button onclick="selesaikanTiket(<?= $t['id'] ?>)"
                class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-green-600 to-green-500
                       hover:from-green-500 hover:to-green-400 text-white font-medium
                       shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all duration-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Selesaikan
        </button>
    <?php endif; /* isRuangRapat / isZoomTicket / else */ ?>

            <?php /* ================================================
               CABANG B: TIKET NON-PERMINTAAN (Kerusakan, dll.)
               Tampilkan form catatan & file TANPA form Zoom
            ================================================ */ ?>
            <?php else: ?>

                <!-- Form Catatan & File -->
                <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-3">
                    <p class="text-purple-300 text-xs mb-2 flex items-center gap-2">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Catatan &amp; Lampiran (Opsional)</span>
                    </p>

                    <form id="formCatatan<?= $t['id'] ?>" class="flex flex-col gap-2" enctype="multipart/form-data">
                        <textarea name="layanan_catatan"
                                  placeholder="Tambahkan catatan pekerjaan..."
                                  class="w-full bg-slate-800/80 border border-purple-500/30 rounded-lg px-3 py-2 text-xs header-title
                                         focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 resize-none"
                                  rows="3"><?= htmlspecialchars($t['layanan_catatan'] ?? '') ?></textarea>

                        <div class="relative">
                            <input type="file" name="layanan_file" id="file<?= $t['id'] ?>"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden">
                            <label for="file<?= $t['id'] ?>"
                                   class="flex items-center justify-center gap-2 w-full px-3 py-2 text-xs
                                          bg-slate-800/80 border border-purple-500/30 rounded-lg
                                          text-purple-300 hover:bg-purple-500/10 cursor-pointer transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span id="fileName<?= $t['id'] ?>">
                                    <?php if (!empty($t['layanan_file'])): ?>
                                        <?= htmlspecialchars($t['layanan_file']) ?>
                                    <?php else: ?>
                                        Pilih File (JPG, PNG, PDF, DOC)
                                    <?php endif; ?>
                                </span>
                            </label>
                        </div>

                        <button type="button" onclick="simpanCatatan(<?= $t['id'] ?>)"
                                class="w-full px-4 py-2 text-xs rounded-lg bg-purple-600/50 hover:bg-purple-500
                                       text-white font-medium border border-purple-500/50 transition-all duration-200">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Catatan
                        </button>
                    </form>
                </div>

                <button onclick="selesaikanTiket(<?= $t['id'] ?>)"
                        class="w-full px-5 py-2.5 rounded-xl text-sm bg-gradient-to-r from-green-600 to-green-500
                               hover:from-green-500 hover:to-green-400 text-white font-medium
                               shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all duration-200">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Selesaikan
                </button>

            <?php endif; /* isPermintaan */ ?>

        </div><!-- end .flex.flex-col gap-2 -->
    </div><!-- end card -->
<?php endforeach; ?> 

    <?php if (!$hasProgress): ?>
    <div class="theme-card border border-dashed rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="header-subtitle text-sm">Tidak ada tiket yang sedang dikerjakan</p>
    </div>
    <?php endif; ?>

    </div><!-- end .grid -->
</div><!-- end section ON PROGRESS -->


<!-- ================= SELESAI ================= -->
<div class="mb-10">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-1 h-6 bg-gradient-to-b from-green-400 to-green-600 rounded-full"></div>
        <h2 class="text-xl font-bold header-title">Riwayat Selesai</h2>
        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
            <?= count(array_filter($tickets_my, fn($t)=>$t['status']==='Selesai')) ?>
        </span>
    </div>

    <?php
    $hasDone           = false;
    $selesaiTickets    = array_filter($tickets_my, fn($t) => $t['status'] === 'Selesai');
    $maxDisplaySelesai = 10;
    ?>

    <?php if (empty($selesaiTickets)): ?>
    <div class="theme-card border border-dashed rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="header-subtitle text-sm">Belum ada tiket selesai</p>
    </div>
    <?php else: ?>

    <!-- Wrapper tabel dengan scroll horizontal untuk mobile -->
    <div class="theme-card border border-green-500/20 rounded-2xl overflow-hidden">

        <!-- Summary bar -->
        <div class="px-5 py-3 bg-green-500/5 border-b border-green-500/15 flex items-center justify-between">
            <span class="text-xs text-green-400 font-semibold flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Total <?= count($selesaiTickets) ?> tiket diselesaikan
            </span>
            <span class="text-[10px] text-gray-500">Klik Detail untuk info lengkap</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <!-- Sticky header -->
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left px-5 py-3.5 text-[10px] font-bold uppercase tracking-widest text-green-400/80 whitespace-nowrap w-[130px]">
                            Kode Tiket
                        </th>
                        <th class="text-left px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 whitespace-nowrap">
                            Pelapor
                        </th>
                        <th class="text-left px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 whitespace-nowrap hidden md:table-cell">
                            Kategori
                        </th>
                        <th class="text-left px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 whitespace-nowrap hidden lg:table-cell">
                            Lokasi
                        </th>
                        <th class="text-left px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 whitespace-nowrap hidden xl:table-cell">
                            Selesai
                        </th>
                        <th class="text-center px-4 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 whitespace-nowrap w-[160px]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="ticketsSelesaiContainer">
                <?php
                $displayCountSelesai = 0;
                foreach ($selesaiTickets as $t):
                    $hasDone = true;
                    $displayCountSelesai++;
                    $isHiddenSelesai = $displayCountSelesai > $maxDisplaySelesai ? 'ticket-selesai-row hidden' : 'ticket-selesai-row';
                ?>
                <tr class="<?= $isHiddenSelesai ?> border-b border-white/[0.04] hover:bg-green-500/[0.03] transition-colors duration-150 group">

                    <!-- Kode Tiket -->
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="font-mono text-xs font-semibold text-green-400 bg-green-500/10 px-2.5 py-1 rounded-lg border border-green-500/20">
                            <?= htmlspecialchars($t['kode_tiket']) ?>
                        </span>
                    </td>

                 <!-- Pelapor -->
<td class="px-4 py-3.5">
    <div class="flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center flex-shrink-0 text-[11px] font-bold text-white">
            <?= mb_strtoupper(mb_substr($t['pelapor'] ?? '?', 0, 1)) ?>
        </div>
        <span class="header-title text-sm font-medium truncate max-w-[160px]">
            <?= htmlspecialchars($t['pelapor']) ?>
        </span>
    </div>
<?php $judul = _parse_ticket_title_view($t['jenis_kerusakan'], $t['nama_kategori'] ?? ''); ?>
<?php if ($judul): ?>
    <div class="text-[10px] text-cyan-400 truncate mt-0.5 max-w-[160px]" title="<?= htmlspecialchars($judul) ?>">
        📋 <?= htmlspecialchars($judul) ?>
    </div>
    <?php endif; ?>
</td>

                    <!-- Kategori + Sub -->
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        <div class="flex flex-col gap-1">
                            <?php if (!empty($t['nama_kategori'])): ?>
                            <span class="text-[10px] px-2 py-0.5 bg-slate-700/60 text-slate-300 rounded-md border border-slate-600/50 w-fit font-medium">
                                <?= htmlspecialchars($t['nama_kategori']) ?>
                            </span>
                            <?php endif; ?>
                            <?php if (!empty($t['nama_sub_kategori'])): ?>
                            <span class="text-[10px] px-2 py-0.5 bg-cyan-500/10 text-cyan-400 rounded-md border border-cyan-500/20 w-fit">
                                <?= htmlspecialchars($t['nama_sub_kategori']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- Lokasi -->
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <span class="header-subtitle text-xs truncate block max-w-[180px]" title="<?= htmlspecialchars($t['lokasi'] ?? '') ?>">
                            <?php if (!empty($t['lokasi'])): ?>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <?= htmlspecialchars($t['lokasi']) ?>
                            </span>
                            <?php else: ?>
                            <span class="text-gray-600 italic">—</span>
                            <?php endif; ?>
                        </span>
                    </td>

                    <!-- Tanggal Selesai -->
                    <td class="px-4 py-3.5 hidden xl:table-cell whitespace-nowrap">
                        <?php if (!empty($t['selesai_at'])): ?>
                        <span class="text-xs text-gray-400">
                            <?= date('d M Y', strtotime($t['selesai_at'])) ?>
                            <span class="block text-[10px] text-gray-600"><?= date('H:i', strtotime($t['selesai_at'])) ?> WIB</span>
                        </span>
                        <?php elseif (!empty($t['updated_at'])): ?>
                        <span class="text-xs text-gray-500">
                            <?= date('d M Y', strtotime($t['updated_at'])) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-600 text-xs italic">—</span>
                        <?php endif; ?>
                    </td>

                    <!-- Aksi -->
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-1.5">
                            <!-- Tombol Detail -->
                            <button onclick='showDetailModal(<?= json_encode(_tkn_detail_json_view($this, $t), JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                    title="Lihat Detail"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs
                                           bg-slate-700/60 hover:bg-slate-600 text-slate-300 hover:text-white
                                           border border-slate-600/50 hover:border-slate-500
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </button>
                            <!-- Tombol Edit -->
                            <button onclick="bukaEditTiket(<?= $t['id'] ?>)"
                                    title="Edit Tiket"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs
                                           bg-amber-600/20 hover:bg-amber-500/40 text-amber-400 hover:text-white
                                           border border-amber-500/30 hover:border-amber-400
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Load More/Less — di dalam card, di bawah tabel -->
        <?php if (count($selesaiTickets) > $maxDisplaySelesai): ?>
        <div class="flex justify-center gap-3 px-5 py-4 border-t border-white/[0.04] bg-green-500/[0.02]">
            <button id="loadMoreSelesai" onclick="loadMoreTickets('selesai')"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold
                           bg-gradient-to-r from-green-600/20 to-green-500/20
                           hover:from-green-600/30 hover:to-green-500/30
                           text-green-400 border border-green-500/30
                           transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                Tampilkan Lebih Banyak
                <span class="px-2 py-0.5 bg-green-500/20 rounded-full">
                    +<?= count($selesaiTickets) - $maxDisplaySelesai ?>
                </span>
            </button>
            <button id="showLessSelesai" onclick="showLessTickets('selesai')" style="display:none;"
                    class="px-5 py-2.5 rounded-xl text-xs font-semibold
                           bg-gradient-to-r from-gray-600/20 to-gray-500/20
                           hover:from-gray-600/30 hover:to-gray-500/30
                           header-subtitle border border-gray-500/30
                           transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
                Tampilkan Lebih Sedikit
            </button>
        </div>
        <?php endif; ?>

    </div><!-- end .theme-card -->
    <?php endif; ?>
</div>

<!-- ================= MODAL EDIT TIKET (TEKNISI) ================= -->
<div id="modalEditTiket"
     class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="theme-card border border-amber-500/30 rounded-3xl w-full max-w-2xl shadow-2xl
                overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 border-b border-amber-500/20
                    p-6 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold header-title flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Tiket
                </h3>
                <p class="text-xs header-subtitle mt-1" id="editTiketSubtitle">Perbarui informasi tiket</p>
            </div>
            <button onclick="tutupEditTiket()"
                    class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white
                           transition-all flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto flex-1">

            <input type="hidden" id="editTiketId">

            <!-- Loading state -->
            <div id="editTiketLoading" class="text-center py-8">
                <div class="w-10 h-10 border-2 border-amber-400 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-sm header-subtitle">Memuat data tiket...</p>
            </div>

            <!-- Form -->
            <div id="editTiketForm" class="hidden space-y-4">

                <!-- Info badge -->
                <div class="flex flex-wrap gap-2 p-3 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                    <span class="text-xs font-mono text-amber-400 font-semibold" id="editTiketKode"></span>
                    <span class="text-xs px-2 py-0.5 rounded-full" id="editTiketStatusBadge"></span>
                    <span class="text-xs text-gray-400" id="editTiketPelapor"></span>
                </div>

                <!-- ══════════════════════════════════
                     SECTION A: GANGGUAN / KERUSAKAN
                ══════════════════════════════════ -->
                <div id="editSectionGangguan" class="hidden space-y-4">

                    <div>
                        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                            📍 Lokasi Gangguan <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="editGangguanLokasi"
                               placeholder="Contoh: Lantai 2 - Ruang Kepegawaian"
                               class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                                      text-sm header-title focus:outline-none focus:ring-2
                                      focus:ring-amber-500/50 focus:border-amber-500/50 transition-all">
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                            🔧 Deskripsi Gangguan <span class="text-red-400">*</span>
                        </label>
                        <textarea id="editGangguanDeskripsi" rows="6"
                                  placeholder="Jelaskan gangguan yang terjadi secara detail..."
                                  class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                                         text-sm header-title focus:outline-none focus:ring-2
                                         focus:ring-amber-500/50 focus:border-amber-500/50
                                         resize-none transition-all"></textarea>
                    </div>

                </div>

             <!-- ══════════════════════════════════════
     SECTION B: PERMINTAAN DENGAN JADWAL (ZOOM)
══════════════════════════════════════ -->
<div id="editSectionPermintaanZoom" class="hidden space-y-4">

    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            📝 Detail Permintaan <span class="text-red-400">*</span>
        </label>
        <textarea id="editZoomDeskripsi" rows="3"
                  placeholder="Contoh: Rapat Bulanan Divisi IT - April 2026"
                  class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                         text-sm header-title focus:outline-none focus:ring-2
                         focus:ring-amber-500/50 focus:border-amber-500/50
                         resize-none transition-all"></textarea>
    </div>

    <!-- 🔥 TAMBAHAN: Link Zoom + Akun -->
    <div id="editZoomLinkSection" class="bg-teal-500/5 border border-teal-500/20 rounded-xl p-4 space-y-3">
        <p class="text-xs text-teal-400 font-semibold flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Detail Zoom
            <span class="text-gray-500 font-normal">(opsional jika belum ada)</span>
        </p>

        <!-- Link Zoom -->
        <div>
            <label class="text-[10px] text-gray-500 font-semibold mb-1 block">🔗 Link Zoom / Meet</label>
            <input type="url" id="editModalZoomLink"
                   placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                   class="w-full bg-slate-800/80 border border-teal-500/30 rounded-xl px-3 py-2.5
                          text-sm header-title focus:outline-none focus:ring-2
                          focus:ring-teal-500/50 transition-all">
        </div>

        <!-- Link Undangan -->
        <div>
            <label class="text-[10px] text-gray-500 font-semibold mb-1 block">
                🔗 Link Undangan
                <span class="text-gray-600 font-normal">(opsional, hanya bisa dicopy user)</span>
            </label>
            <textarea id="editModalUndanganLink" rows="2"
                      placeholder="https://... link undangan untuk disalin peserta"
                      class="w-full bg-slate-800/80 border border-teal-500/30 rounded-xl px-3 py-2.5
                             text-sm header-title focus:outline-none focus:ring-2
                             focus:ring-teal-500/50 resize-none transition-all"></textarea>
        </div>

        <!-- Akun Zoom Picker -->
        <div>
            <label class="text-[10px] text-gray-500 font-semibold mb-2 block">📹 Akun Zoom</label>
            <div class="grid grid-cols-2 gap-2" id="editModalZoomPicker">
                <label class="cursor-pointer">
                    <input type="radio" name="editModalAkunZoom" value="1"
                           id="editModalRadioZ1" class="hidden peer">
                    <div class="border border-slate-600/50 rounded-lg p-2.5 text-center transition-all
                                peer-checked:border-blue-400 peer-checked:bg-blue-500/20
                                hover:border-blue-400/50">
                        <div class="text-xs font-bold text-blue-400">📹 Zoom 1</div>
                        <div class="text-[10px] mt-0.5 text-gray-400" id="editModalZ1info">—</div>
                        <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full transition-all"
                                 id="editModalZ1bar" style="width:0%"></div>
                        </div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="editModalAkunZoom" value="2"
                           id="editModalRadioZ2" class="hidden peer">
                    <div class="border border-slate-600/50 rounded-lg p-2.5 text-center transition-all
                                peer-checked:border-indigo-400 peer-checked:bg-indigo-500/20
                                hover:border-indigo-400/50">
                        <div class="text-xs font-bold text-indigo-400">📹 Zoom 2</div>
                        <div class="text-[10px] mt-0.5 text-gray-400" id="editModalZ2info">—</div>
                        <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full transition-all"
                                 id="editModalZ2bar" style="width:0%"></div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Catatan Zoom -->
        <div>
            <label class="text-[10px] text-gray-500 font-semibold mb-1 block">
                💬 Catatan
                <span class="text-gray-600 font-normal">(Meeting ID, password, dll)</span>
            </label>
            <textarea id="editModalZoomCatatan" rows="2"
                      placeholder="Catatan untuk peserta..."
                      class="w-full bg-slate-800/80 border border-teal-500/30 rounded-xl px-3 py-2.5
                             text-sm header-title focus:outline-none focus:ring-2
                             focus:ring-teal-500/50 resize-none transition-all"></textarea>
        </div>
    </div>

    <!-- Jadwal -->
    <div class="bg-cyan-500/5 border border-cyan-500/20 rounded-xl p-4 space-y-3">
        <p class="text-xs text-cyan-400 font-semibold flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Layanan
        </p>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-[10px] text-gray-500 font-semibold mb-1 block">Tanggal Mulai</label>
                <input type="date" id="editZoomTanggal"
                       class="w-full bg-slate-800/80 border border-cyan-500/30 rounded-xl px-3 py-2.5
                              text-sm header-title focus:outline-none focus:ring-2
                              focus:ring-cyan-500/50 transition-all">
            </div>
            <div>
                <label class="text-[10px] text-gray-500 font-semibold mb-1 block">
                    Tanggal Selesai <span class="text-gray-600 font-normal">(opsional)</span>
                </label>
                <input type="date" id="editZoomTanggalSelesai"
                       class="w-full bg-slate-800/80 border border-cyan-500/30 rounded-xl px-3 py-2.5
                              text-sm header-title focus:outline-none focus:ring-2
                              focus:ring-cyan-500/50 transition-all">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-[10px] text-gray-500 font-semibold mb-1 block">Jam Mulai (per hari)</label>
                <input type="time" id="editZoomJam"
                       class="w-full bg-slate-800/80 border border-cyan-500/30 rounded-xl px-3 py-2.5
                              text-sm header-title focus:outline-none focus:ring-2
                              focus:ring-cyan-500/50 transition-all">
            </div>
            <div>
                <label class="text-[10px] text-gray-500 font-semibold mb-1 block">Durasi (jam/hari)</label>
                <select id="editZoomDurasi"
                        class="w-full bg-slate-800/80 border border-cyan-500/30 rounded-xl px-3 py-2.5
                               text-sm header-title focus:outline-none focus:ring-2
                               focus:ring-cyan-500/50 transition-all">
                    <option value="">Pilih Durasi</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Jam</option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>

</div>

                <!-- ══════════════════════════════════════════
                     SECTION C: PERMINTAAN TANPA JADWAL
                ══════════════════════════════════════════ -->
                <div id="editSectionPermintaanBiasa" class="hidden space-y-4">

                    <div>
                        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                            📝 Detail Permintaan <span class="text-red-400">*</span>
                        </label>
                        <textarea id="editPermintaanDeskripsi" rows="6"
                                  placeholder="Jelaskan detail permintaan Anda..."
                                  class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                                         text-sm header-title focus:outline-none focus:ring-2
                                         focus:ring-amber-500/50 focus:border-amber-500/50
                                         resize-none transition-all"></textarea>
                    </div>

                </div>

                <!-- ══════════════════════════════
                     SECTION D: LAINNYA (fallback)
                ══════════════════════════════ -->
                <div id="editSectionLainnya" class="hidden space-y-4">

                    <div>
                        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                            📍 Lokasi
                        </label>
                        <input type="text" id="editLainnyaLokasi"
                               placeholder="Lokasi..."
                               class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                                      text-sm header-title focus:outline-none focus:ring-2
                                      focus:ring-amber-500/50 focus:border-amber-500/50 transition-all">
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                            📝 Deskripsi <span class="text-red-400">*</span>
                        </label>
                        <textarea id="editLainnyaDeskripsi" rows="5"
                                  placeholder="Jelaskan detail..."
                                  class="w-full bg-slate-800/80 border border-amber-500/30 rounded-xl px-4 py-3
                                         text-sm header-title focus:outline-none focus:ring-2
                                         focus:ring-amber-500/50 focus:border-amber-500/50
                                         resize-none transition-all"></textarea>
                    </div>

                </div>

  <!-- ══════════════════════════════════════════
     SECTION E: RUANG RAPAT (EDITABLE)
══════════════════════════════════════════ -->
<div id="editSectionRuangRapat" class="hidden space-y-4">

    <!-- Info read-only ringkasan -->
    <div class="bg-indigo-500/5 border border-indigo-500/20 rounded-xl p-4">
        <p class="text-xs text-indigo-400 font-semibold mb-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
            </svg>
            Detail Peminjaman
        </p>
        <div id="editRuangRapatInfo" class="space-y-1 text-xs text-gray-300"></div>
    </div>

    <!-- Unit Kerja -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            🏢 Unit/Tim Kerja <span class="text-red-400">*</span>
        </label>
        <input type="text" id="editRuangRapatUnitKerja"
               placeholder="Contoh: Bagian Keuangan / Tim IPDS"
               class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                      text-sm header-title focus:outline-none focus:ring-2
                      focus:ring-indigo-500/50 transition-all">
    </div>

    <!-- Tanggal -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            📅 Tanggal Pemakaian <span class="text-red-400">*</span>
        </label>
        <input type="date" id="editRuangRapatTanggal"
               class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                      text-sm header-title focus:outline-none focus:ring-2
                      focus:ring-indigo-500/50 transition-all">
    </div>

    <!-- Jam Mulai & Durasi -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                🕐 Jam Mulai <span class="text-red-400">*</span>
            </label>
            <input type="time" id="editRuangRapatJamMulai"
                   class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                          text-sm header-title focus:outline-none focus:ring-2
                          focus:ring-indigo-500/50 transition-all">
        </div>
        <div>
            <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
                ⏱️ Durasi <span class="text-red-400">*</span>
            </label>
            <select id="editRuangRapatDurasi"
                    class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                           text-sm header-title focus:outline-none focus:ring-2
                           focus:ring-indigo-500/50 transition-all">
                <option value="">Pilih Durasi</option>
                <option value="60">1 Jam</option>
                <option value="120">2 Jam</option>
                <option value="180">3 Jam</option>
                <option value="240">4 Jam</option>
                <option value="300">5 Jam</option>
                <option value="360">6 Jam</option>
                <option value="420">7 Jam</option>
                <option value="480">8 Jam (Full Day)</option>
            </select>
        </div>
    </div>

    <!-- Info Jam Selesai -->
    <div id="editRuangRapatInfoJamSelesai"
         class="hidden flex items-center gap-2 p-3 bg-green-500/10 border border-green-500/20 rounded-lg">
        <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-green-400">
            Jam selesai: <strong id="editRuangRapatTampilJamSelesai">--:--</strong> WIB
        </p>
    </div>

    <!-- Pilih Ruangan -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            🏢 Ruangan <span class="text-red-400">*</span>
        </label>
        <select id="editRuangRapatRuanganId"
                class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                       text-sm header-title focus:outline-none focus:ring-2
                       focus:ring-indigo-500/50 transition-all">
            <option value="">── Pilih tanggal & jam dulu ──</option>
        </select>
        <div id="editRuangRapatInfoRuangan"
             class="hidden mt-2 p-3 bg-indigo-500/10 border border-indigo-500/20 rounded-lg
                    text-xs text-indigo-300 leading-relaxed"></div>
        <div id="editRuangRapatJadwalRuangan"
             class="hidden mt-2 p-3 bg-amber-500/10 border border-amber-500/20 rounded-lg
                    text-xs text-amber-300"></div>
    </div>

    <!-- Keperluan -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            📋 Keperluan / Agenda <span class="text-red-400">*</span>
        </label>
        <textarea id="editRuangRapatKeperluan" rows="3"
                  placeholder="Contoh: Rapat Koordinasi Tim Keuangan Q2 2026"
                  class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                         text-sm header-title focus:outline-none focus:ring-2
                         focus:ring-indigo-500/50 resize-none transition-all"></textarea>
    </div>

    <!-- Jumlah Peserta -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            👥 Jumlah Peserta <span class="text-red-400">*</span>
        </label>
        <input type="number" id="editRuangRapatJumlahPeserta" min="1"
               placeholder="Contoh: 20"
               class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                      text-sm header-title focus:outline-none focus:ring-2
                      focus:ring-indigo-500/50 transition-all">
    </div>

    <!-- Snack Corner -->
    <div class="space-y-2">
        <label class="text-xs text-gray-400 font-semibold block">🍪 Snack Corner</label>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="editSnackCorner" id="editSnackYa" value="Ya"
                       class="w-4 h-4 accent-indigo-400">
                <span class="text-sm text-gray-300">Ya, perlu snack</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="editSnackCorner" id="editSnackTidak" value="Tidak" checked
                       class="w-4 h-4 accent-indigo-400">
                <span class="text-sm text-gray-300">Tidak perlu</span>
            </label>
        </div>
        <div id="editSnackDetail" class="hidden">
            <input type="number" id="editJumlahSnack" min="1"
                   placeholder="Jumlah porsi snack"
                   class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                          text-sm header-title focus:outline-none focus:ring-2
                          focus:ring-indigo-500/50 transition-all">
        </div>
    </div>

    <!-- Catatan Peminjam -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            💬 Catatan dari Peminjam
            <span class="text-gray-500 font-normal">(Opsional)</span>
        </label>
        <textarea id="editRuangRapatCatatanPeminjam" rows="3"
                  placeholder="Contoh: Butuh proyektor, meja theatre style, dll"
                  class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                         text-sm header-title focus:outline-none focus:ring-2
                         focus:ring-indigo-500/50 resize-none transition-all"></textarea>
    </div>

    <!-- Catatan Admin/Teknisi -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
            🔧 Catatan Penanganan
            <span class="text-gray-500 font-normal">(Opsional)</span>
        </label>
        <textarea id="editRuangRapatCatatan" rows="3"
                  placeholder="Contoh: Ruangan sudah disiapkan, AC dinyalakan, proyektor terpasang..."
                  class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3
                         text-sm header-title focus:outline-none focus:ring-2
                         focus:ring-indigo-500/50 resize-none transition-all"></textarea>
    </div>

    <!-- Hidden: peminjaman_id & jam selesai -->
    <input type="hidden" id="editRuangRapatPeminjamanId">
    <input type="hidden" id="editRuangRapatJamSelesai">

</div>

                <!-- ACC persediaan (hanya tiket persediaan dalam pengerjaan) -->
                <div id="editAccSection" class="hidden bg-emerald-500/5 border border-emerald-500/20 rounded-xl p-4 space-y-2">
                    <button type="button" onclick="bukaModalAccFromEdit()"
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-emerald-600/20 hover:bg-emerald-500/40
                                   text-emerald-300 hover:text-white border border-emerald-500/30 hover:border-emerald-400
                                   transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ubah ACC
                    </button>
                    <p class="text-[11px] header-subtitle leading-relaxed text-center">Atur jumlah disetujui 0 sampai diminta.</p>
                </div>

                <!-- Peringatan -->
                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-3 text-xs text-blue-300 leading-relaxed">
                    ℹ️ Perubahan tersimpan langsung. <strong>Email tidak dikirim ulang</strong> ke pelapor.
                </div>

                <!-- Tombol -->
                <div class="flex gap-3 pt-2">
                    <button onclick="tutupEditTiket()"
                            class="flex-1 px-4 py-3 rounded-xl bg-gray-700 hover:bg-gray-600
                                   text-white text-sm font-medium transition-all">
                        Batal
                    </button>
                    <button onclick="simpanEditTiket()"
                            class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all
                                   bg-gradient-to-r from-amber-600 to-amber-500
                                   hover:from-amber-500 hover:to-amber-400
                                   shadow-lg shadow-amber-500/30">
                        💾 Simpan Perubahan
                    </button>
                </div>

            </div><!-- end #editTiketForm -->
        </div>
    </div>
</div>
<!-- ================= MODAL DETAIL ================= -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fadeIn">
    <div class="theme-card border rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl">

        <div class="sticky top-0 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 backdrop-blur-xl border-b p-6 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    Detail Tiket
                </h3>
                <p id="modal-kode" class="text-sm text-cyan-400 font-mono mt-1 font-semibold"></p>
            </div>
            <button onclick="closeDetailModal()"
                    class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white
                           transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-5 overflow-y-auto max-h-[calc(90vh-120px)]">

            <div class="flex justify-center">
                <span id="modal-status-badge" class="px-5 py-2 rounded-full text-sm font-semibold"></span>
            </div>

            <!-- Info Pelapor -->
            <div class="bg-gradient-to-br from-blue-500/5 to-transparent border border-blue-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-blue-400 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pelapor
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Nama</span>
                        <span id="modal-pelapor" class="header-title text-sm font-semibold"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Email</span>
                        <span id="modal-email" class="header-title text-sm"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">No. HP</span>
                        <span id="modal-hp" class="header-title text-sm"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Lokasi</span>
                        <span id="modal-lokasi" class="header-title text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Info Tiket -->
            <div class="bg-gradient-to-br from-purple-500/5 to-transparent border border-purple-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-purple-400 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Tiket
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Kategori</span>
                        <span id="modal-kategori" class="header-title text-sm font-semibold"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Sub Kategori</span>
                        <span id="modal-sub-kategori" class="header-title text-sm font-semibold"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Tanggal Dibuat</span>
                        <span id="modal-created" class="header-title text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Detail Masalah -->
            <div class="bg-gradient-to-br from-orange-500/5 to-transparent border border-orange-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-orange-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Detail Masalah
                </h4>
                <div class="bg-slate-800/40 rounded-xl p-4">
                    <p id="modal-jenis-kerusakan" class="header-title text-sm leading-relaxed whitespace-pre-line"></p>
                </div>
            </div>

            <!-- Daftar Barang Persediaan -->
            <div id="modal-persediaan-container" class="hidden bg-gradient-to-br from-emerald-500/5 to-transparent border border-emerald-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-emerald-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10V11m0 10l-8-4V7m8 4L4 7m8 4l8-4"/>
                    </svg>
                    Daftar Barang Persediaan
                </h4>
                <div class="bg-slate-800/40 rounded-xl p-4 overflow-x-auto">
                    <table class="w-full text-sm min-w-[580px]">
                        <thead>
                            <tr class="header-subtitle text-[11px] uppercase tracking-wider text-left">
                                <th class="pb-2 pr-3 font-semibold w-10 text-center">No</th>
                                <th class="pb-2 pr-3 font-semibold">Kode</th>
                                <th class="pb-2 pr-3 font-semibold">Nama Barang</th>
                                <th class="pb-2 font-semibold text-right">Diminta</th>
                                <th id="th-persetujuan-teknisi" class="pb-2 font-semibold text-right">Disetujui</th>
                                <th id="th-keterangan-teknisi" class="pb-2 pl-3 font-semibold text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="modal-persediaan-items"></tbody>
                    </table>
                </div>
                <p id="modal-persediaan-note" class="header-subtitle text-xs mt-3">Disetujui mengikuti jumlah diminta bila belum diubah. Selesaikan memakai jumlah yang disetujui. Unduh PDF bila perlu arsip atau serah terima.</p>
                <div class="flex justify-between mt-4 text-center">
                    <div class="flex-1">
                        <div class="header-subtitle text-xs">Petugas / Teknisi</div>
                        <div id="modal-persediaan-teknisi" class="header-title text-xs font-semibold mt-10">-</div>
                    </div>
                    <div class="flex-1">
                        <div class="header-subtitle text-xs">Pemohon</div>
                        <div id="modal-persediaan-pemohon" class="header-title text-xs font-semibold mt-10">-</div>
                    </div>
                </div>
                <button id="btnExportPersediaanTeknisi" type="button" onclick="exportPersediaanPdfTeknisi()"
                        class="mt-3 w-full px-4 py-2.5 rounded-xl text-sm font-semibold bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white shadow-lg shadow-emerald-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </button>
            </div>

            <!-- Jadwal Layanan -->
            <div id="modal-jadwal-container" class="hidden bg-gradient-to-br from-cyan-500/5 to-transparent border border-cyan-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-cyan-400 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Layanan
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Tanggal</span>
                        <span id="modal-tanggal" class="header-title text-sm font-semibold"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/40 rounded-xl">
                        <span class="header-subtitle text-sm">Jam</span>
                        <span id="modal-jam" class="header-title text-sm font-semibold"></span>
                    </div>
                </div>
            </div>

            <!-- Link Layanan -->
            <div id="modal-layanan-container" class="hidden bg-gradient-to-br from-emerald-500/5 to-transparent border border-emerald-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-emerald-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Link Layanan
                </h4>
                <div class="bg-slate-800/40 rounded-xl p-4">
                    <a id="modal-layanan-link" href="#" target="_blank"
                       class="text-emerald-400 text-sm underline break-all hover:text-emerald-300 transition-colors"></a>
                </div>
            </div>

            <!-- Lampiran Foto (non-persediaan saja) -->
            <div id="modal-lampiran-container" class="hidden bg-gradient-to-br from-pink-500/5 to-transparent border border-pink-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-pink-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Lampiran Foto
                </h4>
                <div class="bg-slate-800/40 rounded-xl p-2 overflow-hidden">
                    <img id="modal-lampiran" src="" alt="Lampiran" class="w-full rounded-lg">
                </div>
            </div>

            <!-- Lampiran File Teknisi -->
            <div id="modal-file-container" class="hidden bg-gradient-to-br from-indigo-500/5 to-transparent border border-indigo-500/20 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-indigo-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lampiran File Teknisi
                </h4>
                <div class="bg-slate-800/40 rounded-xl p-4">
                    <a id="modal-file-link" href="#" target="_blank"
                       class="text-indigo-400 text-sm underline break-all hover:text-indigo-300 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span id="modal-file-name"></span>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div id="pmPrintSheetTeknisi" aria-hidden="true">
        <div class="pm-print-kop">
            <img id="print_logo_bps" src="<?= base_url('assets/img/bps.png') ?>" alt="Logo BPS">
            <div class="pm-print-kop-text">
                <div style="font-size:14pt;font-weight:bold;text-transform:uppercase;">BPS PROVINSI RIAU</div>
                <div style="font-size:6pt;font-weight:normal;margin-top:2px;">Jl. Pattimura No.12, Cinta Raja, Kecamatan Sail, Kota Pekanbaru, Riau 28131</div>
            </div>
        </div>
        <div class="pm-print-title">Form Persetujuan Permintaan Persediaan</div>
        <div class="pm-print-grid">
            <div><span class="label">No. Faktur</span><span class="sep">:</span><strong id="print_faktur_teknisi"></strong></div>
            <div><span class="label">Tim Kerja</span><span class="sep">:</span><strong id="print_unit_teknisi"></strong></div>
            <div><span class="label">Tanggal</span><span class="sep">:</span><strong id="print_tanggal_teknisi"></strong></div>
            <div><span class="label">Tanggal Permintaan</span><span class="sep">:</span><strong id="print_butuh_teknisi"></strong></div>
            <div><span class="label">Pemohon</span><span class="sep">:</span><strong id="print_pemohon_teknisi"></strong></div>
        </div>
        <table class="pm-print-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE BARANG</th>
                    <th>NAMA BARANG</th>
                    <th>DIMINTA</th>
                    <th>DISETUJUI</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody id="print_items_teknisi"></tbody>
        </table>
        <div class="pm-print-signature">
            <div>
                <div>Petugas / Teknisi</div>
                <div class="space"></div>
                <strong id="print_teknisi_nama_teknisi"></strong>
            </div>
            <div>
                <div>Pemohon</div>
                <div class="space"></div>
                <strong id="print_pemohon_nama_teknisi"></strong>
            </div>
        </div>
    </div>
</div>


<!-- ================= MODAL ACC PERSEDIAAN ================= -->
<div id="modalAccPersediaan"
     class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="theme-card border border-emerald-500/30 rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border-b border-emerald-500/20 p-6 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold header-title flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="accModalTitle">Atur jumlah yang disetujui</span>
                </h3>
                <p class="text-xs header-subtitle mt-1" id="accModalSubtitle">Tiket -</p>
            </div>
            <button onclick="tutupModalAcc()"
                    class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white transition-all flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            <input type="hidden" id="accTicketId" value="">
            <input type="hidden" id="accMode" value="take">
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 text-xs header-title leading-relaxed">
                Isi jumlah yang disetujui per barang (0 sampai diminta). Sisa stok hanya info, tidak menghambat Ambil atau Selesaikan. Selesaikan memakai jumlah yang disetujui.
            </div>
            <div id="accItemsWrap" class="space-y-3"></div>
            <p id="accEmpty" class="hidden text-sm header-subtitle text-center py-4">Data barang tidak tersedia.</p>
            <div class="flex gap-3 pt-2">
                <button onclick="tutupModalAcc()"
                        class="flex-1 px-4 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium transition-all">
                    Batal
                </button>
                <button id="accSubmitBtn" onclick="submitAccPersediaan()"
                        class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 shadow-lg shadow-emerald-500/30">
                    Simpan &amp; Ambil
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ================= MODAL EDIT ZOOM ================= -->
<div id="modalEditZoom"
     class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="theme-card border border-indigo-500/30 rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500/10 to-teal-500/10 border-b border-indigo-500/20 p-6 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold header-title flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Detail Zoom
                </h3>
                <p class="text-xs header-subtitle mt-1">Perbarui link, akun, atau catatan sesi Zoom</p>
            </div>
            <button onclick="tutupEditZoom()"
                    class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white
                           transition-all flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-3 overflow-y-auto flex-1">

         <input type="hidden" id="editZoomTicketId" value="">

<!-- ── NAMA/JUDUL MEETING (BARU) ── -->
<div>
    <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
        📝 Nama / Judul Meeting
        <span class="text-gray-500 font-normal">(kosongkan jika tidak ingin mengubah)</span>
    </label>
    <input type="text" id="editNamaMeeting"
           placeholder="Contoh: Rapat Bulanan Divisi IT - April 2026"
           class="w-full bg-slate-800/80 border border-teal-500/30 rounded-xl px-4 py-3 text-sm header-title
                  focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500/50 transition-all">
    <p class="text-[10px] text-gray-500 mt-1">
        ✏️ Nama ini menggantikan judul yang diinput user saat membuat tiket
    </p>
</div>

<!-- Link Zoom -->
<div>
    <label class="text-xs text-gray-400 font-semibold mb-1.5 block">
        🔗 Link Zoom / Meet <span class="text-red-400">*</span>
    </label>
                <input type="url" id="editZoomLink"
                       placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                       class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-4 py-3 text-sm header-title
                              focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
            </div>
<!-- ↓ TAMBAH -->
<div>
    <label class="text-xs text-gray-400 font-semibold mb-1.5 block flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342
                     m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316
                     m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684
                     3 3 0 00-5.368-2.684z"/>
        </svg>
        Link Kirim Undangan
        <span class="text-gray-500 font-normal">(opsional, hanya bisa dicopy user)</span>
    </label>
<textarea id="editUndanganLink" rows="3"
          placeholder="https://... link undangan untuk disalin peserta"
          class="w-full bg-slate-800/80 border border-teal-500/30 rounded-xl px-4 py-3 text-sm header-title
                 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500/50
                 resize-none transition-all"></textarea>
</div>
           <!-- Pilih Akun Zoom + Catatan — 2 kolom -->
<div class="grid grid-cols-2 gap-4">

<!-- Kiri: Pilih Akun Zoom -->
<div>
    <label class="text-xs text-gray-400 font-semibold mb-2 block">
        📹 Akun Zoom <span class="text-red-400">*</span>
    </label>
    <div class="flex flex-col gap-2">
        <label class="cursor-pointer">
            <input type="radio" name="editAkunZoom" value="1" id="editRadioZ1" class="hidden peer">
            <div class="border border-slate-600 rounded-xl p-2.5 text-center transition-all
                        peer-checked:border-blue-400 peer-checked:bg-blue-500/20 hover:border-blue-400/50">
                <div class="text-xs font-bold text-blue-400">📹 Zoom 1</div>
                <div class="text-[10px] mt-0.5 text-gray-400" id="editZ1info">Memuat...</div>
                <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full transition-all" id="editZ1bar" style="width:0%"></div>
                </div>
            </div>
        </label>
        <label class="cursor-pointer">
            <input type="radio" name="editAkunZoom" value="2" id="editRadioZ2" class="hidden peer">
            <div class="border border-slate-600 rounded-xl p-2.5 text-center transition-all
                        peer-checked:border-indigo-400 peer-checked:bg-indigo-500/20 hover:border-indigo-400/50">
                <div class="text-xs font-bold text-indigo-400">📹 Zoom 2</div>
                <div class="text-[10px] mt-0.5 text-gray-400" id="editZ2info">Memuat...</div>
                <div class="mt-1.5 h-1 bg-slate-700/50 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full transition-all" id="editZ2bar" style="width:0%"></div>
                </div>
            </div>
        </label>
    </div>
</div>

    <!-- Kanan: Catatan -->
    <div>
        <label class="text-xs text-gray-400 font-semibold mb-2 block">💬 Catatan (Opsional)</label>
        <textarea id="editZoomCatatan" rows="5"
                  placeholder="Meeting ID, Password, instruksi peserta, dll..."
                  class="w-full bg-slate-800/80 border border-indigo-500/30 rounded-xl px-3 py-2 text-sm header-title
                         focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50
                         resize-none transition-all" style="height: 100%;"></textarea>
    </div>

</div>

            <!-- Peringatan -->
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 text-xs text-amber-300 leading-relaxed">
                ⚠️ <strong>Catatan:</strong> Mengubah akun Zoom akan memperbarui pengelompokan jadwal secara otomatis.
                Namun, <strong>tidak akan mengirim ulang email</strong> ke user. Hubungi user secara langsung jika ada perubahan penting.
            </div>

            <!-- Tombol -->
            <div class="flex gap-3">
                <button onclick="tutupEditZoom()"
                        class="flex-1 px-4 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium transition-all">
                    Batal
                </button>
                <button onclick="simpanEditZoom()"
                        class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-white transition-all
                               bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400
                               shadow-lg shadow-indigo-500/30">
                    💾 Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ================= MODAL ALASAN OFFLINE ================= -->
<div id="modalAlasanOffline"
     class="hidden fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center p-4"
     style="z-index: 9999 !important;">
    <div class="theme-card border border-red-500/30 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">

        <div class="bg-gradient-to-r from-red-500/10 to-orange-500/10 backdrop-blur-xl border-b border-red-500/30 p-6">
            <h3 class="text-xl font-bold header-title flex items-center gap-2">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Alasan Tidak Tersedia
            </h3>
            <p class="header-subtitle text-sm mt-2">Mohon berikan alasan mengapa Anda tidak tersedia</p>
        </div>

        <div class="p-6">
            <form id="formAlasanOffline">
                <textarea id="alasanOfflineInput" name="alasan_offline" rows="4" required
                          placeholder="Contoh: Sedang cuti, Sakit, Dinas luar, dll..."
                          class="w-full bg-slate-800/80 border border-red-500/30 rounded-xl px-4 py-3 header-title
                                 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50
                                 resize-none placeholder:text-gray-500"></textarea>

                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="cancelOffline()"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-gray-700 hover:bg-gray-600 text-white font-medium transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-red-500
                                   hover:from-red-500 hover:to-red-400 text-white font-medium
                                   shadow-lg shadow-red-500/30 transition-all">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ================= JAVASCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ================================================================
// STATUS TEKNISI
// ================================================================
document.addEventListener('DOMContentLoaded', () => {
    const formStatus = document.getElementById('formStatusTeknisi');
    if (formStatus) {
        const selectStatus = formStatus.querySelector('select[name="status"]');
        if (selectStatus) {
            selectStatus.dataset.previousValue = selectStatus.value;
            selectStatus.addEventListener('change', function (e) {
                e.preventDefault(); e.stopPropagation();
                if (this.value === 'offline') { showModalAlasanOffline(); return; }
                updateStatusTeknisi(this.value, null);
            });
        }
    }

    const formAlasanOffline = document.getElementById('formAlasanOffline');
    if (formAlasanOffline) {
        formAlasanOffline.addEventListener('submit', (e) => {
            e.preventDefault();
            const alasan = document.getElementById('alasanOfflineInput').value.trim();
            if (!alasan) { Swal.fire('Peringatan', 'Alasan harus diisi', 'warning'); return; }
            updateStatusTeknisi('offline', alasan);
        });
    }
});

function showModalAlasanOffline() {
    const modal = document.getElementById('modalAlasanOffline');
    if (!modal) { Swal.fire('Error', 'Modal tidak ditemukan', 'error'); return; }
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const textarea = document.getElementById('alasanOfflineInput');
    if (textarea) { textarea.value = ''; setTimeout(() => textarea.focus(), 100); }
}

function cancelOffline() {
    const modal        = document.getElementById('modalAlasanOffline');
    const selectStatus = document.querySelector('select[name="status"]');
    if (selectStatus && selectStatus.dataset.previousValue) selectStatus.value = selectStatus.dataset.previousValue;
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('alasanOfflineInput').value = '';
}

function updateStatusTeknisi(status, alasan) {
    const formData = new FormData();
    formData.append('status', status);
    if (alasan) formData.append('alasan_offline', alasan);

    Swal.fire({ title: 'Memperbarui status...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('<?= base_url("teknisi_panel/update_status") ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            const selectStatus = document.querySelector('select[name="status"]');
            if (selectStatus) selectStatus.dataset.previousValue = status;
            const modal = document.getElementById('modalAlasanOffline');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('alasanOfflineInput').value = '';
            }
            Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
        } else {
            const selectStatus = document.querySelector('select[name="status"]');
            if (selectStatus && selectStatus.dataset.previousValue) selectStatus.value = selectStatus.dataset.previousValue;
            Swal.fire({ icon: 'error', title: res.message, showConfirmButton: true });
        }
    })
    .catch(() => {
        const selectStatus = document.querySelector('select[name="status"]');
        if (selectStatus && selectStatus.dataset.previousValue) selectStatus.value = selectStatus.dataset.previousValue;
        Swal.fire('Error', 'Gagal memperbarui status', 'error');
    });
}

// ================================================================
// FUNGSI TIKET
// ================================================================
function selesaikanTiket(id) {
    Swal.fire({
        title: 'Selesaikan tiket?',
        text: 'Pastikan pekerjaan sudah selesai',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Selesaikan',
        cancelButtonText: 'Batal'
    }).then(res => {
        if (res.isConfirmed) {
            window.location.href = "<?= base_url('teknisi_panel/selesai/') ?>" + id;
        }
    });
}
// ================================================================
// BATAL AMBIL TIKET
// ================================================================
function batalAmbilTiket(id) {
    Swal.fire({
        title: 'Batal ambil tiket ini?',
        html: `<div class="text-sm text-gray-400 leading-relaxed">
                    Tiket akan dikembalikan ke antrian <strong class="text-white">Menunggu</strong>.<br>
                    Jika ini tiket <strong>Ruang Rapat</strong>, peminjaman yang sebelumnya
                    dibatalkan otomatis akan <strong class="text-emerald-400">dipulihkan</strong> kembali.
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batal Ambil',
        cancelButtonText: 'Tidak',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        background: '#1e293b',
        color: '#f1f5f9',
    }).then(res => {
        if (!res.isConfirmed) return;

        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('<?= base_url("teknisi_panel/cancel_ticket/") ?>' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            Swal.fire({
                icon: res.status === 'success' ? 'success' : 'error',
                title: res.message,
                timer: 2000,
                showConfirmButton: false,
                background: '#1e293b',
                color: '#f1f5f9',
            }).then(() => {
                if (res.status === 'success') location.reload();
            });
        })
        .catch(() => Swal.fire('Error', 'Gagal memproses permintaan', 'error'));
    });
}
function _tknEsc(s) {
    return String(s ?? '-').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

function _tknReadAccItems(src) {
    try {
        if (Array.isArray(src)) return src;
        if (src && src.dataset && src.dataset.acc) {
            var parsed = JSON.parse(src.dataset.acc);
            return Array.isArray(parsed) ? parsed : [];
        }
        if (typeof src === 'string' && src) {
            var p2 = JSON.parse(src);
            return Array.isArray(p2) ? p2 : [];
        }
    } catch (e) { /* abaikan, fallback di bawah */ }
    return [];
}

function takeTicket(id, btn) {
    var isPersediaan = !!(btn && btn.dataset && btn.dataset.persediaan === '1');
    if (isPersediaan) {
        var items = _tknReadAccItems(btn);
        var kode = (btn && btn.dataset && btn.dataset.kode) || ('#' + id);
        bukaModalAcc(id, kode, items, 'take');
        return;
    }
    Swal.fire({
        title: 'Ambil tiket ini?',
        text: 'Pastikan Anda siap mengerjakannya',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ambil',
        cancelButtonText: 'Batal'
    }).then(res => {
        if (res.isConfirmed) doTakeTicketRequest(id);
    });
}

function doTakeTicketRequest(id) {
    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    fetch('<?= base_url("teknisi_panel/take_ticket/") ?>' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            if (res.warning || res.deficient_items) {
                _tknTakeWarning(res);
                return;
            }
            Swal.fire({ icon: 'success', title: res.message || 'Tiket berhasil diambil', timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
            return;
        }
        // Kompatibilitas: backend lama bisa kirim code stok_habis — perlakukan sebagai info, bukan penolak.
        if ((res.code || '') === 'stok_habis') {
            _tknTakeWarning(res);
            return;
        }
        Swal.fire({ icon: 'error', title: res.message || 'Gagal mengambil tiket', confirmButtonColor: '#059669' });
    })
    .catch(() => Swal.fire('Error', 'Gagal mengambil tiket', 'error'));
}

function _tknTakeWarning(res) {
    var items = res.deficient_items || res.deficient || [];
    var list = (items || []).map(function (d) {
        return '<li>' + _tknEsc(d.nama ?? '-') + ' x' + (d.qty ?? '?') + ' (sisa ' + (d.sisa ?? 0) + ')</li>';
    }).join('');
    var url = res.input_masuk_url || null;
    var html = '<p>' + _tknEsc(res.warning || res.message || 'Stok menipis, tapi tiket tetap diambil.') + '</p>'
        + (list ? '<ul style="text-align:left;margin:8px 0 0 18px;list-style:disc;">' + list + '</ul>' : '')
        + '<p style="margin-top:8px;font-size:12px;">Stok hanya info — tidak menghambat Ambil atau Selesaikan.</p>';
    Swal.fire({
        icon: 'warning',
        title: res.status === 'success' ? 'Tiket berhasil diambil' : 'Info stok',
        html: html,
        showCancelButton: !!url,
        confirmButtonText: 'Mengerti, muat ulang',
        cancelButtonText: url ? 'Catat Barang Masuk (opsional)' : 'Tutup',
        confirmButtonColor: '#059669'
    }).then(function (r) {
        if (r.dismiss === Swal.DismissReason.cancel && url) {
            window.location.href = url;
            return;
        }
        location.reload();
    });
}

// ================================================================
// MODAL ACC PERSEDIAAN (fleksibel: 0 <= disetujui <= diminta)
// Alur: Simpan & Ambil = POST acc_persediaan lalu POST take_ticket lalu reload.
// Mode edit (Ubah ACC): POST acc saja tanpa take ulang.
var _accTicketId = null;
var _accMode = 'take';

function bukaModalAcc(id, kode, src, mode) {
    var items = _tknReadAccItems(src);
    var m = 'take';
    if (src && src.dataset && src.dataset.mode) m = src.dataset.mode;
    else if (typeof mode === 'string' && mode) m = mode;
    _accTicketId = id;
    _accMode = (m === 'edit') ? 'edit' : 'take';

    document.getElementById('accTicketId').value = id;
    document.getElementById('accMode').value = _accMode;
    document.getElementById('accModalSubtitle').textContent = 'Tiket ' + (kode || ('#' + id));
    document.getElementById('accModalTitle').textContent = (_accMode === 'edit') ? 'Ubah jumlah yang disetujui' : 'Atur jumlah yang disetujui';
    document.getElementById('accSubmitBtn').innerHTML = (_accMode === 'edit') ? 'Simpan ACC' : 'Simpan &amp; Ambil';

    var wrap = document.getElementById('accItemsWrap');
    var empty = document.getElementById('accEmpty');
    wrap.innerHTML = '';
    if (!items.length) {
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
        items.forEach(function (it, idx) {
            var bid = parseInt(it.barang_id ?? 0, 10) || 0;
            var diminta = parseInt(it.diminta ?? it.jumlah ?? 0, 10) || 0;
            var accVal = (it.disetujui !== null && it.disetujui !== undefined && it.disetujui !== '')
                ? (parseInt(it.disetujui, 10) || 0) : diminta;
            if (accVal < 0) accVal = 0;
            if (accVal > diminta) accVal = diminta;
            var ketVal = it.keterangan || '';
            var sisa = (it.sisa !== null && it.sisa !== undefined && it.sisa !== '') ? ('Sisa kini: ' + it.sisa) : 'Sisa kini: -';
            var satuan = it.satuan ? ' ' + it.satuan : '';
            var isDifferent = (accVal < diminta);

            var row = document.createElement('div');
            row.className = 'bg-slate-800/40 border border-white/5 rounded-xl p-3 flex flex-col gap-2';
            row.innerHTML = '<div class="flex items-start justify-between gap-3">'
                + '<div><div class="header-title text-sm font-semibold item-title"></div>'
                + '<div class="header-subtitle text-xs mt-0.5">Diminta ' + diminta + satuan + ' · ' + _tknEsc(sisa) + '</div></div>'
                + '<div class="text-[11px] header-subtitle whitespace-nowrap">#' + (idx + 1) + '</div></div>'
                + '<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-1">'
                + '<div>'
                + '<label class="text-xs header-subtitle font-semibold block mb-1">Jumlah disetujui (0–' + diminta + ')</label>'
                + '<input type="number" class="acc-input w-full bg-slate-800/80 border border-emerald-500/30 rounded-lg px-3 py-2 text-sm header-title focus:outline-none focus:ring-2 focus:ring-emerald-500/50"'
                + ' min="0" max="' + diminta + '" step="1" value="' + accVal + '" data-barang="' + bid + '" data-max="' + diminta + '">'
                + '</div>'
                + '<div>'
                + '<label class="text-xs header-subtitle font-semibold block mb-1">Keterangan / Alasan penyesuaian <span class="acc-ket-badge text-amber-400 font-normal ' + (isDifferent ? '' : 'hidden') + '">(Alasan tidak sesuai)</span></label>'
                + '<input type="text" class="acc-ket-input w-full bg-slate-800/80 border ' + (isDifferent ? 'border-amber-500/60' : 'border-slate-600/60') + ' rounded-lg px-3 py-2 text-sm header-title focus:outline-none focus:ring-2 focus:ring-emerald-500/50"'
                + ' placeholder="cth: Stok menipis / hanya ada ' + accVal + '" value="' + _tknEsc(ketVal) + '" data-barang="' + bid + '">'
                + '</div>'
                + '</div>';
            row.querySelector('.item-title').textContent = (it.kode ? it.kode + ' — ' : '') + (it.nama || '-');

            var input = row.querySelector('.acc-input');
            var ketInput = row.querySelector('.acc-ket-input');
            var ketBadge = row.querySelector('.acc-ket-badge');

            var updateKetState = function () {
                var mx = parseInt(input.dataset.max || '0', 10) || 0;
                var v = parseInt(input.value, 10);
                if (isNaN(v)) v = mx;
                if (v < 0) v = 0;
                if (v > mx) v = mx;
                input.value = v;
                if (v < mx) {
                    ketBadge.classList.remove('hidden');
                    ketInput.classList.remove('border-slate-600/60');
                    ketInput.classList.add('border-amber-500/60');
                } else {
                    ketBadge.classList.add('hidden');
                    ketInput.classList.remove('border-amber-500/60');
                    ketInput.classList.add('border-slate-600/60');
                }
            };
            input.addEventListener('input', updateKetState);
            input.addEventListener('change', updateKetState);
            wrap.appendChild(row);
        });
    }

    document.getElementById('modalAccPersediaan').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function tutupModalAcc() {
    var m = document.getElementById('modalAccPersediaan');
    if (m) { m.classList.add('hidden'); m.style.zIndex = ''; }
    var editOpen = document.getElementById('modalEditTiket') && !document.getElementById('modalEditTiket').classList.contains('hidden');
    document.body.style.overflow = editOpen ? 'hidden' : 'auto';
    _accTicketId = null;
}

function _tknCollectAcc() {
    var values = {};
    var keterangan = {};
    var inputs = document.querySelectorAll('#accItemsWrap .acc-input');
    var ketInputs = document.querySelectorAll('#accItemsWrap .acc-ket-input');
    for (var i = 0; i < inputs.length; i++) {
        var bid = parseInt(inputs[i].dataset.barang || '0', 10) || 0;
        var mx = parseInt(inputs[i].dataset.max || '0', 10) || 0;
        var v = parseInt(inputs[i].value, 10);
        if (!bid) return { ok: false, msg: 'Data barang tidak valid.' };
        if (isNaN(v)) return { ok: false, msg: 'Isi jumlah yang disetujui dengan angka.' };
        if (v < 0 || v > mx) return { ok: false, msg: 'Jumlah harus 0 sampai ' + mx + '.' };
        values[bid] = v;
        var ket = ketInputs[i] ? ketInputs[i].value.trim() : '';
        if (v < mx && !ket) {
            return { ok: false, msg: 'Mohon isi keterangan alasan penyesuaian untuk barang yang disetujui (' + v + ' dari ' + mx + ').' };
        }
        keterangan[bid] = ket;
    }
    if (!Object.keys(values).length) return { ok: false, msg: 'Tidak ada barang untuk ACC.' };
    return { ok: true, values: values, keterangan: keterangan };
}

function _tknPostAcc(id, values, keterangan) {
    var fd = new FormData();
    Object.keys(values).forEach(function (bid) {
        fd.append('items[' + bid + ']', values[bid]);
        if (keterangan && keterangan[bid] !== undefined) {
            fd.append('keterangan[' + bid + ']', keterangan[bid]);
        }
    });
    return fetch('<?= base_url("teknisi_panel/acc_persediaan/") ?>' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    }).then(function (r) { return r.json(); });
}

function submitAccPersediaan() {
    var id = _accTicketId || document.getElementById('accTicketId').value;
    if (!id) return;
    var got = _tknCollectAcc();
    if (!got.ok) {
        Swal.fire({ icon: 'warning', title: got.msg, confirmButtonColor: '#059669' });
        return;
    }
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    _tknPostAcc(id, got.values, got.keterangan)
        .then(function (accRes) {
            if (accRes.status !== 'success') {
                Swal.fire({ icon: 'error', title: accRes.message || 'Gagal menyimpan ACC', confirmButtonColor: '#059669' });
                return;
            }
            // Mode edit: cukup simpan ACC tanpa ambil ulang.
            if (_accMode === 'edit') {
                tutupModalAcc();
                Swal.fire({ icon: 'success', title: accRes.message || 'Jumlah disetujui tersimpan', timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
                return;
            }
            // Mode ambil: simpan ACC dulu, lalu ambil tiket, lalu muat ulang.
            fetch('<?= base_url("teknisi_panel/take_ticket/") ?>' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (takeRes) {
                tutupModalAcc();
                if (takeRes.status === 'success') {
                    if (takeRes.warning || takeRes.deficient_items) {
                        _tknTakeWarning(takeRes);
                        return;
                    }
                    Swal.fire({ icon: 'success', title: takeRes.message || 'Tiket berhasil diambil', timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                    return;
                }
                Swal.fire({ icon: 'error', title: takeRes.message || 'Gagal mengambil tiket', confirmButtonColor: '#059669' });
            })
            .catch(() => Swal.fire('Error', 'ACC tersimpan, tapi ambil tiket gagal. Muat ulang lalu coba lagi.', 'error'));
        })
        .catch(() => Swal.fire('Error', 'Gagal menyimpan ACC', 'error'));
}

document.getElementById('modalAccPersediaan').addEventListener('click', function (e) {
    if (e.target === this) tutupModalAcc();
});

function batalkanTiketMenunggu(id, kode) {
    Swal.fire({
        title: 'Batalkan permintaan ini?',
        html: `<div class="text-sm text-gray-400 leading-relaxed">
                    Tiket <strong class="text-white">${kode}</strong> akan dibatalkan.<br>
                    Slot yang sebelumnya terblokir akan <strong class="text-emerald-400">dibebaskan</strong>
                    sehingga user lain bisa mengajukan di waktu yang sama.<br><br>
                    <span class="text-yellow-400">⚠️ User akan menerima email notifikasi pembatalan.</span>
               </div>`,
        input: 'textarea',
        inputLabel: 'Alasan Pembatalan',
        inputPlaceholder: 'Contoh: Ada permintaan yang lebih prioritas di waktu yang sama...',
        inputAttributes: { rows: 3 },
        inputValidator: (value) => {
            if (!value || !value.trim()) return 'Alasan pembatalan wajib diisi!';
        },
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#374151',
        background: '#1e293b',
        color: '#f1f5f9',
    }).then(res => {
        if (!res.isConfirmed) return;

        Swal.fire({
            title: 'Memproses...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const formData = new FormData();
        formData.append('alasan', res.value);

        fetch('<?= base_url("teknisi_panel/force_cancel_menunggu/") ?>' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            Swal.fire({
                icon: res.status === 'success' ? 'success' : 'error',
                title: res.message,
                timer: 2000,
                showConfirmButton: false,
                background: '#1e293b',
                color: '#f1f5f9',
            }).then(() => {
                if (res.status === 'success') location.reload();
            });
        })
        .catch(() => Swal.fire('Error', 'Gagal memproses permintaan', 'error'));
    });
}
// ================================================================
// UPLOAD FILE & SIMPAN CATATAN
// ================================================================
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
        const fileId = this.id.replace('file', '');
        const fileName = this.files[0] ? this.files[0].name : 'Pilih File (JPG, PNG, PDF, DOC)';
        const el = document.getElementById('fileName' + fileId);
        if (el) el.textContent = fileName;
    });
});

function simpanCatatan(ticketId) {
    // Cari form — coba dua kemungkinan id
    const form = document.getElementById('formCatatanPermintaan' + ticketId)
               || document.getElementById('formCatatan' + ticketId);
    if (!form) return;
    const formData = new FormData(form);
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    fetch("<?= base_url('teknisi_panel/update_catatan/') ?>" + ticketId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        Swal.fire({ icon: res.status, title: res.message, timer: 1500, showConfirmButton: false })
            .then(() => { if (res.status === 'success') location.reload(); });
    })
    .catch(() => Swal.fire('Error', 'Gagal menyimpan catatan', 'error'));
}

// ================================================================
// CATATAN RUANG RAPAT
// ================================================================
function simpanCatatanRuangRapat(ticketId) {
    const form = document.getElementById('formRuangRapat' + ticketId);
    if (!form) return;

    const catatan = form.querySelector('textarea[name="catatan_admin"]').value.trim();

    Swal.fire({
        title: 'Menyimpan catatan...', allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const formData = new FormData();
    formData.append('catatan_admin', catatan);

    fetch("<?= base_url('teknisi_panel/update_ticket/') ?>" + ticketId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        Swal.fire({
            icon: res.status, title: res.message,
            timer: 1500, showConfirmButton: false
        });
    })
    .catch(() => Swal.fire('Error', 'Gagal menyimpan catatan', 'error'));
}
// ================================================================
// MODAL EDIT ZOOM
// ================================================================
function bukaEditZoom(ticketId, link, akunZoom, catatan, namaMeeting, tanggal, undanganLink) {
    document.getElementById('editZoomTicketId').value  = ticketId;
    document.getElementById('editZoomLink').value      = link;
    document.getElementById('editZoomCatatan').value   = catatan || '';
    document.getElementById('editNamaMeeting').value   = namaMeeting || '';
    document.getElementById('editUndanganLink').value  = undanganLink || '';  // ← TAMBAH

    var r1 = document.getElementById('editRadioZ1');
    var r2 = document.getElementById('editRadioZ2');
    if (r1) r1.checked = (akunZoom == 1);
    if (r2) r2.checked = (akunZoom == 2);

    // Reset info slot dulu
    ['editZ1info', 'editZ2info'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.textContent = 'Memuat...'; el.className = 'text-[10px] mt-0.5 text-gray-400'; }
    });
    ['editZ1bar', 'editZ2bar'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.style.width = '0%'; el.className = 'h-full bg-blue-500 rounded-full transition-all'; }
    });

    // Fetch slot zoom jika ada tanggal
    if (tanggal) {
        fetch('<?= base_url("teknisi_panel/cek_slot_zoom") ?>?tanggal=' + encodeURIComponent(tanggal))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status !== 'success') return;

                function setInfo(infoId, barId, sisa, pakai, color) {
                    var info = document.getElementById(infoId);
                    var bar  = document.getElementById(barId);
                    if (info) {
                        info.textContent = sisa + '/5 slot tersisa';
                        info.className   = 'text-[10px] mt-0.5 ' + (sisa > 0 ? 'text-emerald-400' : 'text-red-400');
                    }
                    if (bar) {
                        bar.style.width  = (pakai / 5 * 100) + '%';
                        bar.className    = 'h-full rounded-full transition-all ' +
                            (sisa === 0 ? 'bg-red-500' : (sisa <= 2 ? 'bg-yellow-500' : color));
                    }
                }

                setInfo('editZ1info', 'editZ1bar', data.zoom1.sisa, data.zoom1.terpakai, 'bg-blue-500');
                setInfo('editZ2info', 'editZ2bar', data.zoom2.sisa, data.zoom2.terpakai, 'bg-indigo-500');
            })
            .catch(function() {
                ['editZ1info', 'editZ2info'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) { el.textContent = 'Gagal memuat'; el.className = 'text-[10px] mt-0.5 text-red-400'; }
                });
            });
    } else {
        ['editZ1info', 'editZ2info'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) { el.textContent = 'Tidak ada jadwal'; el.className = 'text-[10px] mt-0.5 text-gray-500'; }
        });
    }

    document.getElementById('modalEditZoom').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function tutupEditZoom() {
    document.getElementById('modalEditZoom').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function simpanEditZoom() {
       var ticketId      = document.getElementById('editZoomTicketId').value;
    var link          = document.getElementById('editZoomLink').value.trim();
    var catatan       = document.getElementById('editZoomCatatan').value.trim();
    var namaMeeting   = document.getElementById('editNamaMeeting').value.trim();
    var undanganLink  = document.getElementById('editUndanganLink').value.trim();  // ← TAMBAH
    var akun          = document.querySelector('input[name="editAkunZoom"]:checked');

    if (!link) {
        Swal.fire({ icon: 'warning', title: 'Link Zoom wajib diisi', confirmButtonColor: '#6366f1' });
        return;
    }
    if (!akun) {
        Swal.fire({ icon: 'warning', title: 'Pilih akun Zoom terlebih dahulu', confirmButtonColor: '#6366f1' });
        return;
    }

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

  var formData = new FormData();
    formData.append('layanan_link',    link);
    formData.append('akun_zoom',       akun.value);
    formData.append('layanan_catatan', catatan);
    formData.append('nama_meeting',    namaMeeting);
    formData.append('undangan_link',   undanganLink);  // ← TAMBAH

    fetch('<?= base_url("teknisi_panel/update_layanan_zoom/") ?>' + ticketId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
        if (res.status === 'success') {
            tutupEditZoom();
            Swal.fire({
                icon: 'success',
                title: res.message || 'Berhasil disimpan',
                timer: 1500,
                showConfirmButton: false
            }).then(function () { location.reload(); });
        } else {
            Swal.fire({ icon: 'error', title: res.message || 'Gagal menyimpan', confirmButtonColor: '#6366f1' });
        }
    })
    .catch(function () {
        Swal.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan', confirmButtonColor: '#6366f1' });
    });
}

// Tutup modal edit zoom jika klik backdrop
document.getElementById('modalEditZoom').addEventListener('click', function (e) {
    if (e.target === this) tutupEditZoom();
});

// ================================================================
// MODAL DETAIL
// ================================================================
function showDetailModal(ticket, opts) {
    const hideDisetujui = !!(opts && opts.hideDisetujui);
    const modal = document.getElementById('modalDetail');
    document.getElementById('modal-kode').textContent = ticket.kode_tiket || '-';

    const statusBadge = document.getElementById('modal-status-badge');
    let statusClass = '';
    let statusText  = ticket.status || '-';
    const isAssignedJs = (ticket.status === 'Menunggu') && (ticket.teknisi_id != null && ticket.teknisi_id !== '');
    if (isAssignedJs) {
        statusClass = 'bg-amber-500/20 text-amber-300 border border-amber-500/30';
        statusText = '📌 Ditugaskan';
    } else switch (ticket.status) {
        case 'Menunggu':    statusClass = 'bg-gray-500/20 text-gray-300 border border-gray-500/30';    statusText = '⏳ ' + statusText; break;
        case 'On Progress': statusClass = 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30'; statusText = '⚡ ' + statusText; break;
        case 'Selesai':     statusClass = 'bg-green-500/20 text-green-300 border border-green-500/30';  statusText = '✓ ' + statusText;  break;
        default:            statusClass = 'bg-blue-500/20 text-blue-300 border border-blue-500/30';
    }
    statusBadge.className  = `px-5 py-2 rounded-full text-sm font-semibold ${statusClass}`;
    statusBadge.textContent = statusText;

    document.getElementById('modal-pelapor').textContent       = ticket.pelapor         || '-';
    document.getElementById('modal-email').textContent         = ticket.email           || '-';
    document.getElementById('modal-hp').textContent            = ticket.no_hp           || '-';
    document.getElementById('modal-lokasi').textContent        = ticket.lokasi          || '-';
    document.getElementById('modal-kategori').textContent      = ticket.nama_kategori   || '-';
    document.getElementById('modal-sub-kategori').textContent  = ticket.nama_sub_kategori || '-';
    document.getElementById('modal-created').textContent       = ticket.created_at      || '-';
 const cleanJK = (ticket.jenis_kerusakan || 'Tidak ada deskripsi')
    .replace(/\[ref:[^\]]+\]/g, '')
    .replace(/\nCatatan Admin:.*$/s, '')
    .trim();
document.getElementById('modal-jenis-kerusakan').textContent = cleanJK || 'Tidak ada deskripsi';

    const jadwalContainer = document.getElementById('modal-jadwal-container');
    if (ticket.tanggal || ticket.jam) {
        document.getElementById('modal-tanggal').textContent = ticket.tanggal || '-';
        document.getElementById('modal-jam').textContent     = ticket.jam     || '-';
        jadwalContainer.classList.remove('hidden');
    } else { jadwalContainer.classList.add('hidden'); }

    const layananContainer = document.getElementById('modal-layanan-container');
    if (ticket.layanan_link) {
        document.getElementById('modal-layanan-link').href        = ticket.layanan_link;
        document.getElementById('modal-layanan-link').textContent = ticket.layanan_link;
        layananContainer.classList.remove('hidden');
    } else { layananContainer.classList.add('hidden'); }

    const fileContainer = document.getElementById('modal-file-container');
    if (ticket.layanan_file) {
        document.getElementById('modal-file-link').href        = '<?= base_url("uploads/layanan/") ?>' + ticket.layanan_file;
        document.getElementById('modal-file-name').textContent = ticket.layanan_file;
        fileContainer.classList.remove('hidden');
    } else { fileContainer.classList.add('hidden'); }

    const lampiranContainer = document.getElementById('modal-lampiran-container');
    const isPForFoto = !!(ticket && ticket.is_persediaan);
    if (!isPForFoto && ticket.foto) {
        document.getElementById('modal-lampiran').src = '<?= base_url("uploads/tiket/") ?>' + ticket.foto;
        lampiranContainer.classList.remove('hidden');
    } else { lampiranContainer.classList.add('hidden'); }

    renderPersediaanTeknisi(ticket, hideDisetujui);

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

const TEKNISI_LOGIN = <?= json_encode($profil['name'] ?? '') ?>;
let _tknPersediaanCache = { ticket: null, items: [] };

function _tknParsePersediaanFallback(jk) {
    const out = [];
    (jk || '').split('\n').forEach(function (line) {
        line = line.trim();
        if (line.indexOf('- ') !== 0) return;
        const m = line.match(/^-\s*(.+?)\s*-\s*(.+?)\s*x\s*(\d+)\s*(.*)$/i);
        if (m) {
            out.push({ kode_barang: m[1].trim() || '-', nama_barang_snapshot: m[2].trim(), nama_barang: m[2].trim(), jumlah: parseInt(m[3], 10) || 0, satuan_snapshot: (m[4] || '').trim(), satuan: (m[4] || '').trim() });
        } else {
            out.push({ kode_barang: '-', nama_barang_snapshot: line.replace(/^-\s*/, ''), nama_barang: line.replace(/^-\s*/, ''), jumlah: 0, satuan_snapshot: '', satuan: '' });
        }
    });
    return out;
}

function renderPersediaanTeknisi(ticket, hideDisetujui) {
    const container = document.getElementById('modal-persediaan-container');
    const tbody = document.getElementById('modal-persediaan-items');
    const isP = !!(ticket && ticket.is_persediaan);
    if (!container || !tbody) return;
    if (!isP) {
        container.classList.add('hidden');
        _tknPersediaanCache = { ticket: null, items: [] };
        return;
    }
    const hide = !!hideDisetujui;
    const thAcc = document.getElementById('th-persetujuan-teknisi');
    if (thAcc) thAcc.style.display = hide ? 'none' : '';
    const thKet = document.getElementById('th-keterangan-teknisi');
    if (thKet) thKet.style.display = hide ? 'none' : '';
    const noteAcc = document.getElementById('modal-persediaan-note');
    if (noteAcc) noteAcc.style.display = hide ? 'none' : '';
    // Sumber utama: paket ACC (ada diminta + disetujui + sisa); fallback ke full lalu parse teks.
    let raw = Array.isArray(ticket.persediaan_acc_items) && ticket.persediaan_acc_items.length
        ? ticket.persediaan_acc_items.map(function (a) {
            return {
                kode_barang: a.kode || '-', nama_barang_snapshot: a.nama || '-', nama_barang: a.nama || '-',
                jumlah: a.diminta ?? 0, jumlah_disetujui: (a.disetujui !== null && a.disetujui !== undefined) ? a.disetujui : null,
                keterangan: a.keterangan || '',
                satuan_snapshot: a.satuan || '', satuan: a.satuan || ''
            };
        })
        : (Array.isArray(ticket.persediaan_items_full) ? ticket.persediaan_items_full : []);
    let items = raw.length ? raw : [];
    if (!items.length) items = _tknParsePersediaanFallback(ticket.jenis_kerusakan || '');
    _tknPersediaanCache = { ticket: ticket, items: items };
    tbody.innerHTML = '';
    if (!items.length) {
        tbody.innerHTML = '<tr><td colspan="' + (hide ? '4' : '6') + '" class="header-subtitle text-xs text-center py-4">Data barang tidak tersedia</td></tr>';
    } else {
        items.forEach(function (it, idx) {
            const kode = it.kode_barang || '-';
            const nama = it.nama_barang_snapshot || it.nama_barang || '-';
            const satuan = it.satuan_snapshot || it.satuan || '';
            const diminta = parseInt(it.jumlah, 10) || 0;
            const accRaw = (it.jumlah_disetujui !== null && it.jumlah_disetujui !== undefined && it.jumlah_disetujui !== '')
                ? parseInt(it.jumlah_disetujui, 10) : diminta;
            const disetujui = isNaN(accRaw) ? diminta : accRaw;
            const tr = document.createElement('tr');
            tr.className = 'border-t border-white/5';
            tr.innerHTML = '<td class="py-2 pr-3 text-center header-subtitle text-xs">' + (idx + 1) + '</td>'
                + '<td class="py-2 pr-3 font-mono text-xs header-title"></td>'
                + '<td class="py-2 pr-3 text-xs header-title"></td>'
                + '<td class="py-2 text-right text-xs header-title"></td>'
                + (hide ? '' : '<td class="py-2 text-right text-xs header-title font-semibold"></td><td class="py-2 pl-3 text-left text-xs header-subtitle"></td>');
            tr.children[1].textContent = kode;
            tr.children[2].textContent = nama;
            tr.children[3].textContent = diminta + (satuan ? ' ' + satuan : '');
            if (!hide) {
                tr.children[4].textContent = disetujui + (satuan ? ' ' + satuan : '');
                tr.children[5].textContent = it.keterangan || '-';
            }
            tbody.appendChild(tr);
        });
    }
    var _tknEl = document.getElementById('modal-persediaan-teknisi');
    var _pmhEl = document.getElementById('modal-persediaan-pemohon');
    if (_tknEl) _tknEl.textContent = (ticket.nama_teknisi || TEKNISI_LOGIN || '-');
    if (_pmhEl) _pmhEl.textContent = ticket.pelapor || '-';
    fillPersediaanPrintTeknisi();
    container.classList.remove('hidden');
}

function fillPersediaanPrintTeknisi() {
    var txt = function (id) { var el = document.getElementById(id); return el ? (el.textContent || '').trim() : ''; };
    var set = function (id, v) { var el = document.getElementById(id); if (el) el.textContent = v; };
    var items = [];
    var tb = document.getElementById('modal-persediaan-items');
    if (tb) {
        Array.prototype.forEach.call(tb.querySelectorAll('tr'), function (tr) {
            if (!tr.cells || tr.cells.length < 4) return;
            if (tr.cells[0].hasAttribute && tr.cells[0].hasAttribute('colspan')) return;
            var k = (tr.cells[1].textContent || '').trim();
            var n = (tr.cells[2].textContent || '').trim();
            var diminta = (tr.cells[3].textContent || '').trim();
            var disetujui = tr.cells.length >= 5 ? (tr.cells[4].textContent || '').trim() : diminta;
            var keterangan = tr.cells.length >= 6 ? (tr.cells[5].textContent || '').trim() : '-';
            if (!k && !n && !diminta) return;
            items.push({ kode_barang: k || '-', nama_barang: n || '-', jumlah: diminta || '-', jumlah_disetujui: disetujui || '-', keterangan: keterangan || '-' });
        });
    }
    var cache = (typeof _tknPersediaanCache !== 'undefined') ? _tknPersediaanCache : { ticket: null, items: [] };
    var ticket = cache.ticket || null;
    if (!items.length && cache.items && cache.items.length) {
        items = cache.items.map(function(it) {
            var diminta = parseInt(it.jumlah, 10) || 0;
            var accRaw = (it.jumlah_disetujui !== null && it.jumlah_disetujui !== undefined && it.jumlah_disetujui !== '') ? parseInt(it.jumlah_disetujui, 10) : diminta;
            var disetujui = isNaN(accRaw) ? diminta : accRaw;
            var satuan = it.satuan_snapshot || it.satuan || '';
            return {
                kode_barang: it.kode_barang || '-',
                nama_barang: it.nama_barang_snapshot || it.nama_barang || '-',
                jumlah: diminta + (satuan ? ' ' + satuan : ''),
                jumlah_disetujui: disetujui + (satuan ? ' ' + satuan : ''),
                keterangan: it.keterangan || '-'
            };
        });
    }
    var kode = txt('modal-kode');
    var dibuat = txt('modal-created');
    var pemohon = txt('modal-persediaan-pemohon');
    if (!pemohon || pemohon === '-') pemohon = txt('modal-pelapor');
    var teknisi = txt('modal-persediaan-teknisi');
    var jk = txt('modal-jenis-kerusakan');
    if (ticket) {
        if (!kode || kode === '-') kode = ticket.kode_tiket || kode;
        if (!dibuat || dibuat === '-') dibuat = ticket.created_at || ticket.tanggal || dibuat;
        if (!pemohon || pemohon === '-') pemohon = ticket.pelapor || pemohon;
        if (!teknisi || teknisi === '-') teknisi = ticket.nama_teknisi || TEKNISI_LOGIN || teknisi;
        if (!teknisi || teknisi === '-') teknisi = TEKNISI_LOGIN || teknisi;
        if (!jk || jk === 'Tidak ada deskripsi') jk = ticket.jenis_kerusakan || jk;
    }
    if ((!kode || kode === '-') && (!items.length)) return false;
    var unitM = (jk || '').match(/Unit Kerja:\s*(.+)/i);
    var butuhM = (jk || '').match(/Tanggal (?:permintaan|dibutuhkan):\s*(.+)/i);
    set('print_faktur_teknisi', kode || '-');
    set('print_tanggal_teknisi', dibuat || '-');
    set('print_unit_teknisi', unitM ? unitM[1].trim() : '-');
    set('print_butuh_teknisi', butuhM ? butuhM[1].trim() : '-');
    set('print_pemohon_teknisi', pemohon || '-');
    set('print_pemohon_nama_teknisi', pemohon || '-');
    var tTeknisi = (teknisi && teknisi !== '-') ? teknisi : '(....................)';
    set('print_teknisi_nama_teknisi', tTeknisi);

    var printRows = document.getElementById('print_items_teknisi');
    if (!printRows) return false;
    printRows.innerHTML = '';
    if (!items.length) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="6" style="text-align:center;padding:8px;">Data barang tidak tersedia</td>';
        printRows.appendChild(tr);
    } else {
        items.forEach(function (it, i) {
            var tr = document.createElement('tr');
            [i + 1, it.kode_barang || '-', it.nama_barang || '-', it.jumlah || '-', it.jumlah_disetujui || '-', it.keterangan || '-'].forEach(function (v, idx) {
                var td = document.createElement('td');
                td.textContent = v;
                if (idx === 0 || idx === 3 || idx === 4) {
                    td.style.textAlign = 'center';
                } else if (idx === 5) {
                    td.style.textAlign = 'left';
                }
                tr.appendChild(td);
            });
            printRows.appendChild(tr);
        });
    }
    return true;
}

function exportPersediaanPdfTeknisi() {
    if (!fillPersediaanPrintTeknisi()) return;
    var fakturEl = document.getElementById('print_faktur_teknisi');
    var faktur = fakturEl ? (fakturEl.textContent || '').trim() : '';
    var cleanFaktur = faktur.replace(/[^a-zA-Z0-9\-]/g, '');
    var filename = cleanFaktur ? cleanFaktur : 'Form-Persetujuan';
    var originalTitle = document.title;
    document.title = filename;

    var doPrint = function() {
        window.print();
        setTimeout(function() {
            document.title = originalTitle;
        }, 1000);
    };

    var img = document.getElementById('print_logo_bps');
    if (img && !img.complete) {
        img.onload = doPrint;
        img.onerror = doPrint;
    } else {
        doPrint();
    }
}
window.fillPersediaanPrintTeknisi = fillPersediaanPrintTeknisi;
window.exportPersediaanPdfTeknisi = exportPersediaanPdfTeknisi;

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('modalDetail').addEventListener('click', function (e) {
    if (e.target === this) closeDetailModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        tutupEditZoom();
        if (typeof tutupModalAcc === 'function') tutupModalAcc();
    }
});

// ================================================================
// LIVE UPDATE
// ================================================================
const LIVE_UPDATE_CONFIG = {
    interval: 30000,
    endpoint: '<?= base_url("teknisi_panel/get_live_data") ?>'
};

let updateInterval = null;
let isUpdating     = false;
let updateCount    = 0;
let lastKnownTickets = { available: [], onProgress: [], selesai: [] };

const animateValue = (element, start, end, duration = 800) => {
    if (!element) return;
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end; clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 16);
};

const showUpdateIndicator = () => {
    const ind = document.querySelector('.live-update-indicator');
    if (ind) { ind.classList.add('updating'); setTimeout(() => ind.classList.remove('updating'), 1000); }
};

const updateElement = (selector, value, animate = true) => {
    const el = document.querySelector(selector);
    if (!el) return;
    const oldVal = parseInt(el.textContent) || 0;
    if (animate && oldVal !== value) {
        el.style.transition = 'transform 0.3s ease';
        el.style.transform  = 'scale(1.15)';
        setTimeout(() => { animateValue(el, oldVal, value); setTimeout(() => { el.style.transform = 'scale(1)'; }, 100); }, 100);
    } else { el.textContent = value; }
};

const showNotification = (type, message) => {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400',
        warning: 'bg-amber-500/10 border-amber-500/30 text-amber-400',
        error:   'bg-red-500/10 border-red-500/30 text-red-400',
        info:    'bg-blue-500/10 border-blue-500/30 text-blue-400'
    };
    const icons = {
        success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        error:   'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        info:    'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    };
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl backdrop-blur-xl border transition-all duration-500 transform translate-x-full ${colors[type] || colors.info}`;
    notification.innerHTML = `<div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icons[type] || icons.info}"/></svg><span class="font-semibold">${message}</span></div>`;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => { notification.classList.add('translate-x-full'); setTimeout(() => notification.remove(), 500); }, 4000);
};

const detectNewTickets = (newTickets, oldTickets) => {
    if (oldTickets.length === 0) return [];
    const oldIds = oldTickets.map(t => t.id);
    return newTickets.filter(t => !oldIds.includes(t.id));
};

const fetchLiveData = async () => {
    if (isUpdating) return;
    isUpdating = true;
    showUpdateIndicator();
    try {
        const response = await fetch(LIVE_UPDATE_CONFIG.endpoint, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        if (data.status !== 'success') throw new Error('Invalid status');

        updateElement('[data-stat="total_tiket"]',  data.stats.total_tiket);
        updateElement('[data-stat="belum_diambil"]', data.stats.belum_diambil);
        updateElement('[data-stat="on_progress"]',   data.stats.on_progress);
        updateElement('[data-stat="selesai"]',       data.stats.selesai);

        const lu = document.querySelector('[data-update="last_update"]');
        if (lu) lu.textContent = data.last_update;

        const newAvailable = detectNewTickets(data.tickets_available, lastKnownTickets.available);
        if (newAvailable.length > 0) {
            showNotification('info', `🎫 ${newAvailable.length} tiket baru tersedia: ${newAvailable.map(t=>t.kode_tiket).join(', ')}`);
        }

        lastKnownTickets.available  = data.tickets_available;
        lastKnownTickets.onProgress = data.tickets_on_progress;
        lastKnownTickets.selesai    = data.tickets_selesai;
        updateCount++;
        console.log(`✅ Live update #${updateCount}`);
    } catch (err) {
        console.error('❌ Live update error:', err);
    } finally { isUpdating = false; }
};

const startLiveUpdates = () => {
    if (updateInterval) return;
    fetchLiveData();
    updateInterval = setInterval(fetchLiveData, LIVE_UPDATE_CONFIG.interval);
    const st = document.querySelector('[data-status="live-text"]');
    if (st) { st.textContent = 'Live'; st.parentElement.classList.remove('opacity-50'); }
};

const stopLiveUpdates = () => {
    if (updateInterval) {
        clearInterval(updateInterval); updateInterval = null;
        const st = document.querySelector('[data-status="live-text"]');
        if (st) { st.textContent = 'Paused'; st.parentElement.classList.add('opacity-50'); }
    }
};

document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopLiveUpdates(); else startLiveUpdates();
});

document.addEventListener('DOMContentLoaded', () => {
    lastKnownTickets.available  = <?= json_encode(array_map(fn($t) => ['id'=>$t['id'],'kode_tiket'=>$t['kode_tiket']], $tickets_available)) ?>;
    lastKnownTickets.onProgress = <?= json_encode(array_map(fn($t) => ['id'=>$t['id'],'kode_tiket'=>$t['kode_tiket']], array_filter($tickets_my, fn($t) => $t['status']==='On Progress'))) ?>;
    lastKnownTickets.selesai    = <?= json_encode(array_map(fn($t) => ['id'=>$t['id'],'kode_tiket'=>$t['kode_tiket']], array_filter($tickets_my, fn($t) => $t['status']==='Selesai'))) ?>;
    startLiveUpdates();
});

window.addEventListener('beforeunload', () => stopLiveUpdates());

const liveStyle = document.createElement('style');
liveStyle.textContent = `
    .live-update-indicator.updating { animation: pulse-indicator 1s ease-in-out; }
    @keyframes pulse-indicator { 0%, 100% { opacity:1; transform:scale(1); } 50% { opacity:0.7; transform:scale(1.1); } }
    [data-stat] { transition: all 0.3s ease; }
`;
document.head.appendChild(liveStyle);

// ================================================================
// STATUS INDICATOR
// ================================================================
function updateStatusIndicator() {
    const select    = document.getElementById('statusSelect');
    const indicator = document.getElementById('statusIndicator');
    const dot       = document.getElementById('statusDot');
    const label     = document.getElementById('statusLabel');
    if (!select || !indicator || !dot || !label) return;
    const status = select.value;
    indicator.className = 'flex items-center gap-2 px-3 py-2 rounded-lg border transition-all';
    dot.className = 'w-2 h-2 rounded-full transition-all';
    switch (status) {
        case 'available':
            indicator.classList.add('bg-emerald-500/10', 'border-emerald-500/30');
            dot.classList.add('bg-emerald-500');
            label.textContent = 'Tersedia';
            label.className   = 'text-xs font-medium text-emerald-400';
            break;
        case 'busy':
            indicator.classList.add('bg-amber-500/10', 'border-amber-500/30');
            dot.classList.add('bg-amber-500');
            label.textContent = 'Sibuk';
            label.className   = 'text-xs font-medium text-amber-400';
            break;
        case 'offline':
            indicator.classList.add('bg-red-500/10', 'border-red-500/30');
            dot.classList.add('bg-red-500');
            label.textContent = 'Tidak Tersedia';
            label.className   = 'text-xs font-medium text-red-400';
            break;
    }
}
document.addEventListener('DOMContentLoaded', () => {
    updateStatusIndicator();
    const select = document.getElementById('statusSelect');
    if (select) select.addEventListener('change', updateStatusIndicator);
});

// ================================================================
// LOAD MORE / SHOW LESS
// ================================================================
function loadMoreTickets(type) {
    let container, loadMoreBtn, showLessBtn, cardClass;
    if (type === 'available') {
        container   = document.getElementById('ticketsAvailableContainer');
        loadMoreBtn = document.getElementById('loadMoreAvailable');
        showLessBtn = document.getElementById('showLessAvailable');
        cardClass   = 'ticket-available-card';
    } else {
        container   = document.getElementById('ticketsSelesaiContainer');
        loadMoreBtn = document.getElementById('loadMoreSelesai');
        showLessBtn = document.getElementById('showLessSelesai');
        cardClass   = 'ticket-selesai-row';
    }
    if (!container || !loadMoreBtn || !showLessBtn) return;
    container.querySelectorAll(`.${cardClass}.hidden`).forEach((el, index) => {
        setTimeout(() => {
            el.classList.remove('hidden');
            el.style.animation = 'fadeInUp 0.4s ease-out';
        }, index * 40);
    });
    loadMoreBtn.style.opacity = '0';
    loadMoreBtn.style.transform = 'scale(0.9)';
    setTimeout(() => {
        loadMoreBtn.style.display = 'none';
        showLessBtn.style.display = 'flex';
        showLessBtn.style.opacity = '0';
        showLessBtn.style.transform = 'scale(0.9)';
        setTimeout(() => {
            showLessBtn.style.opacity = '1';
            showLessBtn.style.transform = 'scale(1)';
        }, 50);
    }, 300);
}

function showLessTickets(type) {
    let container, loadMoreBtn, showLessBtn, cardClass, maxDisplay;
    if (type === 'available') {
        container   = document.getElementById('ticketsAvailableContainer');
        loadMoreBtn = document.getElementById('loadMoreAvailable');
        showLessBtn = document.getElementById('showLessAvailable');
        cardClass   = 'ticket-available-card';
        maxDisplay  = 12;
    } else {
        container   = document.getElementById('ticketsSelesaiContainer');
        loadMoreBtn = document.getElementById('loadMoreSelesai');
        showLessBtn = document.getElementById('showLessSelesai');
        cardClass   = 'ticket-selesai-row';
        maxDisplay  = 10;
    }
    if (!container || !loadMoreBtn || !showLessBtn) return;
    container.querySelectorAll(`.${cardClass}`).forEach((el, index) => {
        if (index >= maxDisplay) {
            el.style.animation = 'fadeOutDown 0.3s ease-out';
            setTimeout(() => {
                el.classList.add('hidden');
                el.style.animation = '';
            }, 300);
        }
    });
    const scrollTarget = container.closest('.mb-10');
    if (scrollTarget) setTimeout(() => scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
    showLessBtn.style.opacity = '0';
    showLessBtn.style.transform = 'scale(0.9)';
    setTimeout(() => {
        showLessBtn.style.display = 'none';
        loadMoreBtn.style.display = 'flex';
        loadMoreBtn.style.opacity = '0';
        loadMoreBtn.style.transform = 'scale(0.9)';
        setTimeout(() => {
            loadMoreBtn.style.opacity = '1';
            loadMoreBtn.style.transform = 'scale(1)';
        }, 50);
    }, 300);
}

const loadMoreStyle = document.createElement('style');
loadMoreStyle.textContent = `
    @keyframes fadeInUp  { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeOutDown { from { opacity:1; transform:translateY(0); } to { opacity:0; transform:translateY(20px); } }
    #loadMoreAvailable, #loadMoreSelesai, #showLessAvailable, #showLessSelesai { transition: all 0.3s ease; }
`;
document.head.appendChild(loadMoreStyle);

// ================================================================
// EDIT TIKET OLEH TEKNISI — AWARE KATEGORI
// ================================================================
let _editTiketData   = null;
let _editTiketMode   = ''; // 'gangguan' | 'zoom' | 'permintaan' | 'lainnya'
let _editAcc         = { id: null, kode: '', items: [], isPersediaan: false };

// Sub kategori yang perlu_jadwal (zoom) — ambil dari PHP
const _subZoomIds = <?= json_encode($sub_zoom_ids ?? []) ?>;
function _sembunyikanSemuaSection() {
    ['editSectionGangguan', 'editSectionPermintaanZoom',
     'editSectionPermintaanBiasa', 'editSectionLainnya',
     'editSectionRuangRapat', 'editAccSection'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
}

function bukaEditTiket(id, btn) {
    const modal   = document.getElementById('modalEditTiket');
    const loading = document.getElementById('editTiketLoading');
    const form    = document.getElementById('editTiketForm');

    _editAcc = { id: id, kode: (btn && btn.dataset && btn.dataset.kode) || ('#' + id),
        items: btn ? _tknReadAccItems(btn) : [],
        isPersediaan: !!(btn && btn.dataset && btn.dataset.persediaan === '1') };

    loading.classList.remove('hidden');
    form.classList.add('hidden');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch('<?= base_url("teknisi_panel/get_ticket_edit/") ?>' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        if (res.status !== 'success') {
            Swal.fire({ icon: 'error', title: res.message || 'Gagal memuat data', confirmButtonColor: '#f59e0b' });
            tutupEditTiket();
            return;
        }

        const t = res.ticket;
        _editTiketData = t;

        // Isi badge info
        document.getElementById('editTiketId').value          = t.id;
        document.getElementById('editTiketKode').textContent  = t.kode_tiket || '';
        document.getElementById('editTiketPelapor').textContent = '👤 ' + (t.pelapor || '-');
        document.getElementById('editTiketSubtitle').textContent =
            (t.nama_kategori || '') + (t.nama_sub_kategori ? ' · ' + t.nama_sub_kategori : '');

        // Status badge
        const badge = document.getElementById('editTiketStatusBadge');
        const statusMap = {
            'Menunggu':    ['⏳ Menunggu',    'bg-gray-500/20 text-gray-300 border border-gray-500/30'],
            'On Progress': ['⚡ On Progress', 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30'],
            'Selesai':     ['✓ Selesai',      'bg-green-500/20 text-green-300 border border-green-500/30'],
        };
        const [statusTxt, statusCls] = statusMap[t.status] || ['— ' + t.status, 'bg-blue-500/20 text-blue-300'];
        badge.textContent = statusTxt;
        badge.className   = 'text-xs px-2 py-0.5 rounded-full ' + statusCls;

  const katSlug      = (t.kategori_slug || t.nama_kategori || '').toLowerCase();
const subKatId     = parseInt(t.sub_kategori_id || 0);
const isPermintaan = katSlug === 'permintaan';

// Pakai is_ruang_rapat dari server sebagai sumber kebenaran utama
const isRuangRapat = (t.is_ruang_rapat == 1) ||
    (isPermintaan && (t.jenis_kerusakan || '').indexOf('[ref:peminjaman_id=') !== -1) ||
    (isPermintaan && subKatId === 32);

const isKendaraan  = isPermintaan &&
    (t.jenis_kerusakan || '').indexOf('[ref:peminjaman_kendaraan_id=') !== -1;
const isBmn = isPermintaan &&
    (t.jenis_kerusakan || '').indexOf('[ref:peminjaman_bmn_id=') !== -1;
const isZoom = isPermintaan && !isRuangRapat && !isKendaraan && !isBmn &&
    (t.perlu_jadwal == 1 || _subZoomIds.includes(subKatId));
const isGangguan   = ['gangguan', 'kerusakan'].includes(katSlug);

_sembunyikanSemuaSection();

if (isGangguan) {
    _editTiketMode = 'gangguan';
    document.getElementById('editSectionGangguan').classList.remove('hidden');
    document.getElementById('editGangguanLokasi').value    = t.lokasi          || '';
    document.getElementById('editGangguanDeskripsi').value = t.jenis_kerusakan || '';

} else if (isRuangRapat) {
    _editTiketMode = 'ruang_rapat';
    document.getElementById('editSectionRuangRapat').classList.remove('hidden');

    // Parse info lines dari jenis_kerusakan
    const lines = (t.jenis_kerusakan || '').split('\n');
    let catatanAdmin = '';
    let unitKerja    = '';
    let keperluan    = '';
    let catatanPeminjam = '';
    let tanggalRapat = t.tanggal || '';
    let jamMulaiRapat = '';
    let jamSelesaiRapat = '';
    let jumlahPeserta = '';
    let snackCorner = 'Tidak';
    let jumlahSnack = '';
    let peminjaman_id = null;

    // Ambil peminjaman_id dari ref tag
    const refMatch = (t.jenis_kerusakan || '').match(/\[ref:peminjaman_id=(\d+)\]/);
    if (refMatch) peminjaman_id = refMatch[1];

    const infoLines = [];
    lines.forEach(function(line) {
        const l = line.trim();
        if (!l) return;
        if (l.startsWith('[ref:')) return;
        if (l.startsWith('Catatan Admin:')) {
            catatanAdmin = l.replace('Catatan Admin:', '').trim();
            return;
        }
        infoLines.push(l);
    });

    // Tampilkan info ringkasan (read-only)
    document.getElementById('editRuangRapatInfo').innerHTML =
        infoLines.slice(0, 5).map(function(l) {
            return '<p class="text-xs text-gray-300 py-0.5">'
                + l.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                + '</p>';
        }).join('');

    // Jika ada peminjaman_id, fetch data lengkap dari server
    if (peminjaman_id) {
        document.getElementById('editRuangRapatPeminjamanId').value = peminjaman_id;

        fetch('<?= base_url("teknisi_panel/get_peminjaman/") ?>' + peminjaman_id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.status !== 'success') return;
            var p = res.peminjaman;

            document.getElementById('editRuangRapatUnitKerja').value    = p.unit_kerja    || '';
            document.getElementById('editRuangRapatTanggal').value      = p.tanggal       || '';
            document.getElementById('editRuangRapatJamMulai').value     = (p.jam_mulai    || '').substring(0,5);
            document.getElementById('editRuangRapatKeperluan').value    = p.keperluan     || '';
            document.getElementById('editRuangRapatJumlahPeserta').value = p.jumlah_peserta || '';
            document.getElementById('editRuangRapatCatatanPeminjam').value = p.catatan_peminjam || '';
            document.getElementById('editRuangRapatCatatan').value      = catatanAdmin;

            // Durasi: hitung dari jam_mulai & jam_selesai
            if (p.jam_mulai && p.jam_selesai) {
                var tM = p.jam_mulai.split(':').map(Number);
                var tS = p.jam_selesai.split(':').map(Number);
                var dur = (tS[0]*60+tS[1]) - (tM[0]*60+tM[1]);
                document.getElementById('editRuangRapatDurasi').value = dur;
                // Update tampilan jam selesai
                document.getElementById('editRuangRapatJamSelesai').value = p.jam_selesai.substring(0,5);
                document.getElementById('editRuangRapatTampilJamSelesai').textContent = p.jam_selesai.substring(0,5);
                document.getElementById('editRuangRapatInfoJamSelesai').classList.remove('hidden');
            }

            // Snack
            if (p.snack_corner === 'Ya') {
                document.getElementById('editSnackYa').checked = true;
                document.getElementById('editSnackDetail').classList.remove('hidden');
                document.getElementById('editJumlahSnack').value = p.jumlah_snack || '';
            } else {
                document.getElementById('editSnackTidak').checked = true;
                document.getElementById('editSnackDetail').classList.add('hidden');
            }

            // Load ruangan setelah data terisi
            editRuangRapatMuatRuangan().then(function() {
                // Set ruangan yang dipilih
                var sel = document.getElementById('editRuangRapatRuanganId');
                sel.value = p.ruangan_id;
                sel.dispatchEvent(new Event('change'));
            });
        })
        .catch(function(e) { console.error('Gagal fetch peminjaman:', e); });
    }

} else if (isKendaraan) {
    _editTiketMode = 'kendaraan';
    document.getElementById('editSectionPermintaanBiasa').classList.remove('hidden');

    // Tampilkan deskripsi tanpa ref tag
    const jkBersih = (t.jenis_kerusakan || '')
        .replace(/\[ref:peminjaman_kendaraan_id=\d+\]/g, '')
        .trim();
    document.getElementById('editPermintaanDeskripsi').value = jkBersih;
} else if (isBmn) {
    _editTiketMode = 'bmn';
    document.getElementById('editSectionPermintaanBiasa').classList.remove('hidden');

    const jkBersih = (t.jenis_kerusakan || '')
        .replace(/\[ref:peminjaman_bmn_id=\d+\]/g, '')
        .trim();
    document.getElementById('editPermintaanDeskripsi').value = jkBersih;
} else if (isZoom) {

    _editTiketMode = 'zoom';
    document.getElementById('editSectionPermintaanZoom').classList.remove('hidden');

    let detailBaris = t.jenis_kerusakan || '';
    const barisPertama = (t.jenis_kerusakan || '').split('\n')[0];
    if (barisPertama.startsWith('Detail Permintaan:')) {
        detailBaris = barisPertama.replace('Detail Permintaan:', '').trim();
    }
    document.getElementById('editZoomDeskripsi').value      = detailBaris;
    document.getElementById('editZoomTanggal').value        = t.tanggal         || '';
    document.getElementById('editZoomTanggalSelesai').value = t.tanggal_selesai || '';
    document.getElementById('editZoomJam').value            = t.jam             || '';
    document.getElementById('editZoomDurasi').value         = t.durasi          || '';
    document.getElementById('editModalZoomLink').value      = t.layanan_link    || '';
    document.getElementById('editModalUndanganLink').value  = t.undangan_link   || '';
    document.getElementById('editModalZoomCatatan').value   = t.layanan_catatan || '';

    const r1 = document.getElementById('editModalRadioZ1');
    const r2 = document.getElementById('editModalRadioZ2');
    if (r1) r1.checked = (parseInt(t.akun_zoom) === 1 || !t.akun_zoom);
    if (r2) r2.checked = (parseInt(t.akun_zoom) === 2);

    const tgl = t.tanggal || '';
    ['editModalZ1info','editModalZ2info'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.textContent = tgl ? 'Memuat...' : '—'; }
    });
    if (tgl) {
        fetch('<?= base_url("teknisi_panel/cek_slot_zoom") ?>?tanggal=' + encodeURIComponent(tgl), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status !== 'success') return;
            const setSlot = (infoId, barId, sisa, pakai, color) => {
                const info = document.getElementById(infoId);
                const bar  = document.getElementById(barId);
                if (info) {
                    info.textContent = sisa + '/5 slot tersisa';
                    info.className   = 'text-[10px] mt-0.5 ' + (sisa > 0 ? 'text-emerald-400' : 'text-red-400');
                }
                if (bar) {
                    bar.style.width = (pakai / 5 * 100) + '%';
                    bar.className   = 'h-full rounded-full transition-all ' +
                        (sisa === 0 ? 'bg-red-500' : (sisa <= 2 ? 'bg-yellow-500' : color));
                }
            };
            setSlot('editModalZ1info','editModalZ1bar', data.zoom1.sisa, data.zoom1.terpakai, 'bg-blue-500');
            setSlot('editModalZ2info','editModalZ2bar', data.zoom2.sisa, data.zoom2.terpakai, 'bg-indigo-500');
        })
        .catch(() => {
            ['editModalZ1info','editModalZ2info'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.textContent = 'Gagal memuat'; el.className = 'text-[10px] mt-0.5 text-red-400'; }
            });
        });
    }

} else if (isPermintaan) {
    _editTiketMode = 'permintaan';
    document.getElementById('editSectionPermintaanBiasa').classList.remove('hidden');
    document.getElementById('editPermintaanDeskripsi').value = t.jenis_kerusakan || '';

} else {
    _editTiketMode = 'lainnya';
    document.getElementById('editSectionLainnya').classList.remove('hidden');
    document.getElementById('editLainnyaLokasi').value    = t.lokasi          || '';
    document.getElementById('editLainnyaDeskripsi').value = t.jenis_kerusakan || '';
}

loading.classList.add('hidden');
form.classList.remove('hidden');
        // Section Ubah ACC: hanya tiket persediaan dalam pengerjaan.
        if (_editAcc.isPersediaan && t.status === 'On Progress' && _editAcc.items.length) {
            document.getElementById('editAccSection').classList.remove('hidden');
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Gagal memuat data tiket', confirmButtonColor: '#f59e0b' });
        tutupEditTiket();
    });
}

function tutupEditTiket() {
    document.getElementById('modalEditTiket').classList.add('hidden');
    document.body.style.overflow = 'auto';
    _editTiketData = null;
    _editTiketMode = '';
    _editAcc = { id: null, kode: '', items: [], isPersediaan: false };
}

function bukaModalAccFromEdit() {
    if (!_editAcc.id || !_editAcc.items.length) return;
    var m = document.getElementById('modalAccPersediaan');
    if (m) m.style.zIndex = '60';
    // _tknReadAccItems terima array langsung; mode edit via argumen ke-4.
    bukaModalAcc(_editAcc.id, _editAcc.kode, _editAcc.items, 'edit');
}

function simpanEditTiket() {
    const id       = document.getElementById('editTiketId').value;
    const formData = new FormData();
    let valid      = true;
    let errMsg     = '';

    if (_editTiketMode === 'gangguan') {
        const lokasi = document.getElementById('editGangguanLokasi').value.trim();
        const desk   = document.getElementById('editGangguanDeskripsi').value.trim();
        if (!desk) { valid = false; errMsg = 'Deskripsi gangguan wajib diisi'; }
        if (lokasi) formData.append('lokasi', lokasi);
        formData.append('jenis_kerusakan', desk);
} else if (_editTiketMode === 'ruang_rapat') {
    const unitKerja    = document.getElementById('editRuangRapatUnitKerja').value.trim();
    const tanggal      = document.getElementById('editRuangRapatTanggal').value;
    const jamMulai     = document.getElementById('editRuangRapatJamMulai').value;
    const jamSelesai   = document.getElementById('editRuangRapatJamSelesai').value;
    const durasi       = document.getElementById('editRuangRapatDurasi').value;
    const ruanganId    = document.getElementById('editRuangRapatRuanganId').value;
    const keperluan    = document.getElementById('editRuangRapatKeperluan').value.trim();
    const jumlahPeserta = document.getElementById('editRuangRapatJumlahPeserta').value;
    const snackCorner  = document.getElementById('editSnackYa').checked ? 'Ya' : 'Tidak';
    const jumlahSnack  = snackCorner === 'Ya' ? document.getElementById('editJumlahSnack').value : '';
    const catatanPeminjam = document.getElementById('editRuangRapatCatatanPeminjam').value.trim();
    const catatan      = document.getElementById('editRuangRapatCatatan').value.trim();
    const peminjaman_id = document.getElementById('editRuangRapatPeminjamanId').value;

    if (!unitKerja)    { valid = false; errMsg = 'Unit kerja wajib diisi'; }
    if (!tanggal)      { valid = false; errMsg = 'Tanggal wajib diisi'; }
    if (!jamMulai)     { valid = false; errMsg = 'Jam mulai wajib diisi'; }
    if (!jamSelesai)   { valid = false; errMsg = 'Pilih durasi terlebih dahulu'; }
    if (!ruanganId)    { valid = false; errMsg = 'Pilih ruangan terlebih dahulu'; }
    if (!keperluan)    { valid = false; errMsg = 'Keperluan wajib diisi'; }
    if (!jumlahPeserta){ valid = false; errMsg = 'Jumlah peserta wajib diisi'; }
    if (snackCorner === 'Ya' && !jumlahSnack) { valid = false; errMsg = 'Jumlah porsi snack wajib diisi'; }

 if (valid) {
        formData.append('peminjaman_id',    peminjaman_id);
        formData.append('unit_kerja',       unitKerja);
        formData.append('tanggal_rapat',    tanggal);
        formData.append('jam_mulai',        jamMulai);
        formData.append('jam_selesai',      jamSelesai);
        formData.append('ruangan_id',       ruanganId);
        formData.append('keperluan',        keperluan);
        formData.append('jumlah_peserta',   jumlahPeserta);
        formData.append('snack_corner',     snackCorner);
        formData.append('jumlah_snack',     jumlahSnack);
        formData.append('catatan_peminjam', catatanPeminjam);
        formData.append('catatan_admin',    catatan);
    }
    // blok duplicate dihapus
   } else if (_editTiketMode === 'zoom') {
    const desk   = document.getElementById('editZoomDeskripsi').value.trim();
    const tgl    = document.getElementById('editZoomTanggal').value;
    const tglSel = document.getElementById('editZoomTanggalSelesai').value;
    const jam    = document.getElementById('editZoomJam').value;
    const durasi = document.getElementById('editZoomDurasi').value;

    // 🔥 TAMBAHAN
    const zoomLink     = document.getElementById('editModalZoomLink').value.trim();
    const undanganLink = document.getElementById('editModalUndanganLink').value.trim();
    const zoomCatatan  = document.getElementById('editModalZoomCatatan').value.trim();
    const akunZoom     = document.querySelector('input[name="editModalAkunZoom"]:checked');

    if (!desk) { valid = false; errMsg = 'Detail permintaan wajib diisi'; }
    if (!tgl)  { valid = false; errMsg = 'Tanggal mulai wajib diisi'; }

    if (valid) {
        const tM    = new Date(tgl);
        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli',
                        'Agustus','September','Oktober','November','Desember'];
        const fmt   = d => d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
        let catatan = ['Detail Permintaan: ' + desk];

        if (tglSel) {
            const tS      = new Date(tglSel);
            const jmlHari = Math.round((tS - tM) / 86400000) + 1;
            catatan.push('Periode: ' + fmt(tM) + ' s.d. ' + fmt(tS) + ' (' + jmlHari + ' hari)');
        } else {
            catatan.push('Tanggal: ' + fmt(tM));
        }
        if (jam)    catatan.push('Jam Mulai (per hari): ' + jam + ' WIB');
        if (durasi) catatan.push('Durasi (per hari): ' + durasi + ' jam');

        formData.append('jenis_kerusakan', catatan.join('\n'));
        formData.append('tanggal', tgl);
        if (tglSel)      formData.append('tanggal_selesai', tglSel);
        if (jam)         formData.append('jam', jam);
        if (durasi)      formData.append('durasi', durasi);

        // 🔥 TAMBAHAN: kirim data zoom
        if (zoomLink)     formData.append('layanan_link',    zoomLink);
        if (undanganLink) formData.append('undangan_link',   undanganLink);
        if (zoomCatatan)  formData.append('layanan_catatan', zoomCatatan);
        if (akunZoom)     formData.append('akun_zoom',       akunZoom.value);
    }
    } else if (_editTiketMode === 'permintaan' || _editTiketMode === 'kendaraan' || _editTiketMode === 'bmn') {
        const desk = document.getElementById('editPermintaanDeskripsi').value.trim();
        if (!desk) { valid = false; errMsg = 'Detail permintaan wajib diisi'; }
        formData.append('jenis_kerusakan', desk);

    } else {
        const lokasi = document.getElementById('editLainnyaLokasi').value.trim();
        const desk   = document.getElementById('editLainnyaDeskripsi').value.trim();
        if (!desk) { valid = false; errMsg = 'Deskripsi wajib diisi'; }
        if (lokasi) formData.append('lokasi', lokasi);
        formData.append('jenis_kerusakan', desk);
    }

    if (!valid) {
        Swal.fire({ icon: 'warning', title: errMsg, confirmButtonColor: '#f59e0b' });
        return;
    }

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('<?= base_url("teknisi_panel/update_ticket/") ?>' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            tutupEditTiket();
            Swal.fire({ icon: 'success', title: res.message || 'Tiket berhasil diperbarui',
                        timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
        } else {
            Swal.fire({ icon: 'error', title: res.message || 'Gagal menyimpan', confirmButtonColor: '#f59e0b' });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan', confirmButtonColor: '#f59e0b' }));
}

// Tutup modal edit tiket jika klik backdrop
document.getElementById('modalEditTiket').addEventListener('click', function(e) {
    if (e.target === this) tutupEditTiket();
});

// ================================================================
// RUANG RAPAT EDIT: Hitung jam selesai
// ================================================================
function editRuangRapatHitungJamSelesai() {
    var jam    = document.getElementById('editRuangRapatJamMulai').value;
    var durasi = parseInt(document.getElementById('editRuangRapatDurasi').value);
    var infoBox = document.getElementById('editRuangRapatInfoJamSelesai');
    var hidden  = document.getElementById('editRuangRapatJamSelesai');
    var tampil  = document.getElementById('editRuangRapatTampilJamSelesai');

    if (!jam || !durasi) {
        infoBox.classList.add('hidden');
        hidden.value = '';
        return;
    }
    var parts = jam.split(':').map(Number);
    var total = parts[0] * 60 + parts[1] + durasi;
    var hasil = String(Math.floor(total / 60) % 24).padStart(2, '0')
              + ':' + String(total % 60).padStart(2, '0');

    hidden.value    = hasil;
    tampil.textContent = hasil;
    infoBox.classList.remove('hidden');

    editRuangRapatMuatRuangan();
}

// ================================================================
// RUANG RAPAT EDIT: Load ruangan
// ================================================================
async function editRuangRapatMuatRuangan() {
    var tanggal    = document.getElementById('editRuangRapatTanggal').value;
    var jamMulai   = document.getElementById('editRuangRapatJamMulai').value;
    var jamSelesai = document.getElementById('editRuangRapatJamSelesai').value;
    var sel        = document.getElementById('editRuangRapatRuanganId');
    var infoEl     = document.getElementById('editRuangRapatInfoRuangan');
    var jadwalEl   = document.getElementById('editRuangRapatJadwalRuangan');

    if (!tanggal) {
        sel.innerHTML = '<option value="">── Pilih tanggal dulu ──</option>';
        return;
    }

    sel.innerHTML = '<option value="">── Memuat ruangan... ──</option>';
    infoEl.classList.add('hidden');
    jadwalEl.classList.add('hidden');

    var url = '<?= base_url("teknisi_panel/get_ruangan_aktif") ?>?tanggal='
              + encodeURIComponent(tanggal);
    if (jamMulai && jamSelesai) {
        url += '&jam_mulai=' + encodeURIComponent(jamMulai)
             + '&jam_selesai=' + encodeURIComponent(jamSelesai);
    }

    try {
        var res  = await fetch(url);
        var list = await res.json();

        if (!list.length) {
            sel.innerHTML = '<option value="">Tidak ada ruangan tersedia</option>';
            return;
        }

        sel.innerHTML = '<option value="">── Pilih ruangan ──</option>';
        list.forEach(function(r) {
            var o           = document.createElement('option');
            o.value         = r.id;
            o.dataset.kapasitas = r.kapasitas;
            o.dataset.lokasi    = r.lokasi    || '-';
            o.dataset.fasilitas = r.fasilitas || '';
            o.dataset.jadwal    = JSON.stringify(r.jadwal_terpakai || []);
            o.dataset.tersedia  = r.tersedia ? '1' : '0';

            o.textContent = r.tersedia
                ? '✓ ' + r.nama_ruangan + '  (Kap. ' + r.kapasitas + ' org)'
                : '✗ ' + r.nama_ruangan + '  (Sudah terpesan)';
            if (!r.tersedia) { o.disabled = true; o.style.color = '#9ca3af'; }
            sel.appendChild(o);
        });
    } catch(e) {
        sel.innerHTML = '<option value="">Gagal memuat — coba refresh</option>';
    }
}

// ================================================================
// RUANG RAPAT EDIT: Tampil info ruangan saat dipilih
// ================================================================
document.getElementById('editRuangRapatRuanganId').addEventListener('change', function() {
    var infoEl  = document.getElementById('editRuangRapatInfoRuangan');
    var jadwalEl = document.getElementById('editRuangRapatJadwalRuangan');
    if (!this.value) { infoEl.classList.add('hidden'); jadwalEl.classList.add('hidden'); return; }

    var o = this.options[this.selectedIndex];
    var jadwal = JSON.parse(o.dataset.jadwal || '[]');

    var html = '📍 ' + o.dataset.lokasi + ' · 👥 Kapasitas: ' + o.dataset.kapasitas + ' orang';
    if (o.dataset.fasilitas) html += '<br>🔧 ' + o.dataset.fasilitas;
    infoEl.innerHTML = html;
    infoEl.classList.remove('hidden');

    if (jadwal.length) {
        var jHtml = '<strong class="block mb-1">📋 Jadwal tanggal ini:</strong>';
        jadwal.forEach(function(j) {
            var icon = j.status === 'Disetujui' ? '🔴' : '🟡';
            jHtml += '<span class="block">' + icon + ' ' + j.jam_mulai
                   + '–' + j.jam_selesai + ' · ' + j.keperluan + '</span>';
        });
        jadwalEl.innerHTML = jHtml;
        jadwalEl.classList.remove('hidden');
    } else {
        jadwalEl.classList.add('hidden');
    }
});

// Event listeners untuk edit ruang rapat
document.getElementById('editRuangRapatJamMulai').addEventListener('change', editRuangRapatHitungJamSelesai);
document.getElementById('editRuangRapatDurasi').addEventListener('change', editRuangRapatHitungJamSelesai);
document.getElementById('editRuangRapatTanggal').addEventListener('change', function() {
    editRuangRapatMuatRuangan();
});

// Snack toggle
document.getElementById('editSnackYa').addEventListener('change', function() {
    document.getElementById('editSnackDetail').classList.remove('hidden');
});
document.getElementById('editSnackTidak').addEventListener('change', function() {
    document.getElementById('editSnackDetail').classList.add('hidden');
});

function uploadDokumenTeknisi(ticketId, input) {
    if (!input.files[0]) return;

    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire({ icon: 'warning', title: 'File terlalu besar', text: 'Maksimal 2MB' });
        input.value = '';
        return;
    }

    const formData = new FormData();
    formData.append('dokumen_peserta', file);

    Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('<?= base_url("teknisi_panel/upload_dokumen_teknisi/") ?>' + ticketId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil diunggah!',
                text: 'Dokumen: ' + file.name,
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengunggah',
                text: res.message || 'Terjadi kesalahan',
                confirmButtonText: 'Tutup'
            });
            input.value = '';
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal mengunggah dokumen',
            text: 'Periksa koneksi dan coba lagi',
            confirmButtonText: 'Tutup'
        });
        input.value = '';
    });
}

function hapusDokumenTeknisi(ticketId) {
    Swal.fire({
        title: 'Hapus dokumen?',
        text: 'Dokumen akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
    }).then(res => {
        if (!res.isConfirmed) return;
        Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        fetch('<?= base_url("teknisi_panel/hapus_dokumen_teknisi/") ?>' + ticketId, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            Swal.fire({ icon: res.status, title: res.message, timer: 1500, showConfirmButton: false })
                .then(() => { if (res.status === 'success') location.reload(); });
        })
        .catch(() => Swal.fire('Error', 'Gagal menghapus dokumen', 'error'));
    });
}
</script>