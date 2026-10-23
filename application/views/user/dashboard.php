<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan IT BPS Riau</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; }
            50%       { opacity: 0.5; }
        }
        .pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }

        /* ══ SHARED TABLE STYLES ══ */
        .zt-table { width:100%; border-collapse:collapse; font-size:10px; table-layout:fixed; min-width:0; }
        .zt-table thead tr { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
        .zt-table thead th {
            padding:5px 5px; text-align:left;
            font-size:8px; font-weight:700;
            color:#94a3b8; letter-spacing:0;
            text-transform:uppercase; white-space:nowrap;
            overflow:hidden; text-overflow:ellipsis;
        }
        .zt-table thead th.th-center { text-align:center; }
        .zt-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
        .zt-table tbody tr:last-child { border-bottom:none; }
        .zt-table tbody tr:hover { background:#eff6ff; }
        .zt-table td { padding:5px 5px; vertical-align:middle; overflow:hidden; }
        .zt-table td:first-child { vertical-align:middle; }
        .zt-table td.td-center { text-align:center; }
        .rr-table tbody tr:hover { background:#f0fdf4; }
        .kv-table tbody tr:hover { background:#fff7ed; }
        .bmn-table tbody tr:hover { background:#faf5ff; }

        .tc { display:flex; align-items:center; justify-content:flex-start; gap:6px; }
        .tc-ico { width:24px;height:24px;border-radius:7px;display:grid;place-items:center;font-size:11px;flex-shrink:0; }
        .tc-name {
            font-weight:700; color:#1e293b; line-height:1.3;
            white-space:normal; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
            max-width:100%; text-align:left; font-size:10.5px;
        }
        .tc-host { font-size:9px; color:#94a3b8; text-align:left; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; line-height:1.3; }

        .tchip {
            display:inline-flex; align-items:center; justify-content:center;
            gap:2px; background:#f1f5f9; border-radius:5px;
            padding:2px 4px; font-size:8.5px; font-weight:700;
            color:#475569; white-space:nowrap;
        }
        .tchip-green { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .tchip-amber { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .tchip-purple { background:#faf5ff; color:#6d28d9; border:1px solid #ddd6fe; }
        .tdur { font-size:9px;color:#94a3b8;font-weight:600;white-space:nowrap; }

        /* ══ BADGES ══ */
        .zbadge { display:inline-flex;align-items:center;gap:2px;padding:2px 5px;border-radius:50px;font-size:8.5px;font-weight:700;white-space:nowrap; }
        .zb-berlangsung { background:#fef2f2;color:#dc2626;border:1px solid #fecaca; }
        .zb-segera      { background:#fffbeb;color:#d97706;border:1px solid #fde68a; }
        .zb-jadwal      { background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe; }
        .zb-selesai     { background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0; }
        .zb-belum       { background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0; }
        .zb-dipinjam    { background:#fef2f2;color:#dc2626;border:1px solid #fecaca; }
        .zb-menunggu    { background:#fffbeb;color:#d97706;border:1px solid #fde68a; }
        .zdot { width:5px;height:5px;border-radius:50%;background:currentColor; }
        .zb-berlangsung .zdot,
        .zb-dipinjam .zdot { animation:zblink 1.2s infinite; }
        @keyframes zblink { 0%,100%{opacity:1} 50%{opacity:.25} }

        /* ══ MISC ══ */
        .zoom-empty { text-align:center;padding:28px 12px;color:#94a3b8; }
        .zoom-empty .zi { font-size:24px;margin-bottom:6px; }
        .zoom-empty p { font-size:11px;font-weight:600; }

        .z-date-btn {
            display:inline-flex;align-items:center;gap:4px;
            background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);
            color:#fff;font-family:inherit;font-size:10px;font-weight:700;
            padding:5px 9px;border-radius:7px;cursor:pointer;white-space:nowrap;
            transition:all .15s; flex-shrink:0;
        }
        .z-date-btn:hover { background:rgba(255,255,255,.28); transform:translateY(-1px); }
        .z-date-btn svg { width:11px;height:11px;stroke:rgba(255,255,255,.85);flex-shrink:0; }

        .history-bar {
            background:rgba(255,255,255,.1);
            border:1px solid rgba(255,255,255,.2);
            border-radius:12px;
            backdrop-filter:blur(8px);
        }

        .cap-bar { height:3px;border-radius:2px;background:#e2e8f0;overflow:hidden;margin-top:2px; }
        .cap-fill { height:100%;border-radius:2px;background:#059669;transition:width .3s; }
        .cap-fill.cap-warn { background:#d97706; }
        .cap-fill.cap-full { background:#dc2626; }

        /* Breakout badge */
        .zbo-badge {
            display:inline-flex;flex-direction:column;align-items:flex-start;
            gap:1px;background:#faf5ff;border:1px solid #e9d5ff;
            border-radius:6px;padding:2px 6px;
            font-size:8.5px;color:#7c3aed;line-height:1.35;text-align:left;
            max-width:106px;
        }
        .zbo-badge strong {
            font-size:9px;color:#6d28d9;display:block;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
            max-width:96px;
        }
        .ruangan-chip {
            display:inline-flex;align-items:center;gap:2px;
            background:#ecfdf5;border:1px solid #a7f3d0;border-radius:4px;
            padding:1px 5px;font-size:9.5px;font-weight:700;color:#065f46;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .snack-badge {
            display:inline-flex;align-items:center;gap:2px;
            background:#fef3c7;border:1px solid #fde68a;border-radius:5px;
            padding:1px 5px;font-size:9px;font-weight:700;color:#92400e;white-space:nowrap;
        }
        .bmn-chip {
            display:inline-flex;align-items:center;gap:2px;
            background:#faf5ff;border:1px solid #ddd6fe;border-radius:4px;
            padding:1px 5px;font-size:9.5px;font-weight:700;color:#6d28d9;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }

        /* ══ DATE PICKER MODAL ══ */
        .zdp-overlay {
            position:fixed;inset:0;background:rgba(15,23,42,.55);
            backdrop-filter:blur(4px);z-index:99998;
            display:none;align-items:center;justify-content:center;padding:16px;
        }
        .zdp-overlay.open { display:flex; animation:overlayIn .18s ease; }
        @keyframes overlayIn { from{opacity:0} to{opacity:1} }
        .zdp-card {
            background:#fff;border-radius:22px;
            box-shadow:0 32px 80px rgba(0,0,0,.28),0 0 0 1px rgba(0,0,0,.04);
            width:100%;max-width:320px;overflow:hidden;
            animation:cardIn .22s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes cardIn {
            from{opacity:0;transform:scale(.88) translateY(16px)}
            to{opacity:1;transform:scale(1) translateY(0)}
        }
        .zdp-head { padding:18px 20px 16px;display:flex;align-items:center;justify-content:space-between; }
        .zdp-head.zoom-head  { background:linear-gradient(135deg,#0d2255 0%,#1a4db8 100%); }
        .zdp-head.rapat-head { background:linear-gradient(135deg,#064e3b 0%,#059669 100%); }
        .zdp-head.kend-head  { background:linear-gradient(135deg,#78350f 0%,#d97706 100%); }
        .zdp-head.bmn-head   { background:linear-gradient(135deg,#4c1d95 0%,#7c3aed 100%); }
        .zdp-head-title { color:#fff;font-size:14px;font-weight:800; }
        .zdp-head-sub   { color:rgba(255,255,255,.6);font-size:10px;font-weight:600;margin-top:2px; }
        .zdp-close {
            width:30px;height:30px;border-radius:50%;border:none;
            background:rgba(255,255,255,.12);color:#fff;
            font-size:18px;cursor:pointer;display:grid;place-items:center;
            transition:background .15s;line-height:1;font-weight:300;
        }
        .zdp-close:hover { background:rgba(255,255,255,.25); }
        .zdp-nav { display:flex;align-items:center;justify-content:space-between;padding:16px 16px 10px; }
        .zdp-nav-btn {
            width:32px;height:32px;border-radius:9px;
            border:1.5px solid #e2e8f0;background:#fff;color:#64748b;
            font-size:16px;cursor:pointer;display:grid;place-items:center;transition:all .1s;font-weight:600;
        }
        .zdp-nav-btn:hover { background:#f8fafc;border-color:#cbd5e1;color:#1e293b; }
        .zdp-month-lbl {
            font-size:14px;font-weight:800;color:#1e293b;
            cursor:pointer;padding:5px 10px;border-radius:8px;
            transition:background .1s;display:flex;align-items:center;gap:5px;
        }
        .zdp-month-lbl:hover { background:#f1f5f9; }
        .zdp-caret { width:14px;height:14px;stroke:#94a3b8;transition:transform .2s; }
        .zdp-caret.up { transform:rotate(180deg); }
        .zdp-grid { display:grid;grid-template-columns:repeat(7,1fr);gap:1px;padding:0 10px 4px; }
        .zdp-dow { text-align:center;font-size:9px;font-weight:700;color:#94a3b8;padding:4px 0 6px;letter-spacing:.4px; }
        .zdp-day {
            aspect-ratio:1;display:grid;place-items:center;
            font-size:12px;font-weight:600;color:#374151;
            border-radius:8px;cursor:pointer;border:none;
            background:none;font-family:inherit;transition:background .1s,color .1s,transform .1s;
        }
        .zdp-day:hover       { background:#eff6ff;color:#2563eb;transform:scale(1.12); }
        .zdp-day.today       { background:#eff6ff;color:#2563eb;font-weight:800; }
        .zdp-day.selected    { background:#2563eb;color:#fff!important;font-weight:800;box-shadow:0 2px 8px rgba(37,99,235,.4); }
        .zdp-day.selected:hover { background:#1d4ed8;transform:scale(1.08); }
        .zdp-day.other-month { color:#d1d5db; }
        .zdp-day.other-month:hover { background:#f9fafb;color:#9ca3af;transform:none; }
        .rapat-picker .zdp-day.selected { background:#059669;box-shadow:0 2px 8px rgba(5,150,105,.4); }
        .rapat-picker .zdp-day.selected:hover { background:#047857; }
        .rapat-picker .zdp-day:hover { background:#ecfdf5;color:#059669; }
        .rapat-picker .zdp-day.today { background:#ecfdf5;color:#059669; }
        .kend-picker .zdp-day.selected { background:#d97706;box-shadow:0 2px 8px rgba(217,119,6,.4); }
        .kend-picker .zdp-day.selected:hover { background:#b45309; }
        .kend-picker .zdp-day:hover { background:#fef3c7;color:#d97706; }
        .kend-picker .zdp-day.today { background:#fef3c7;color:#d97706; }
        .bmn-picker .zdp-day.selected { background:#7c3aed;box-shadow:0 2px 8px rgba(124,58,237,.4); }
        .bmn-picker .zdp-day.selected:hover { background:#6d28d9; }
        .bmn-picker .zdp-day:hover { background:#faf5ff;color:#7c3aed; }
        .bmn-picker .zdp-day.today { background:#faf5ff;color:#7c3aed; }
        .zdp-my-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:6px;padding:4px 10px 8px; }
        .zdp-my-btn {
            padding:9px 4px;border-radius:9px;border:1.5px solid #f1f5f9;background:#fafafa;
            font-size:11px;font-weight:600;color:#374151;cursor:pointer;font-family:inherit;
            text-align:center;transition:all .1s;
        }
        .zdp-my-btn:hover  { background:#eff6ff;color:#2563eb;border-color:#bfdbfe; }
        .zdp-my-btn.active { background:#2563eb;color:#fff;border-color:#2563eb;font-weight:800; }
        .rapat-picker .zdp-my-btn:hover  { background:#ecfdf5;color:#059669;border-color:#6ee7b7; }
        .rapat-picker .zdp-my-btn.active { background:#059669;border-color:#059669; }
        .kend-picker .zdp-my-btn:hover   { background:#fef3c7;color:#d97706;border-color:#fbbf24; }
        .kend-picker .zdp-my-btn.active  { background:#d97706;border-color:#d97706; }
        .bmn-picker .zdp-my-btn:hover    { background:#faf5ff;color:#7c3aed;border-color:#c4b5fd; }
        .bmn-picker .zdp-my-btn.active   { background:#7c3aed;border-color:#7c3aed; }
        .zdp-foot { display:flex;align-items:center;justify-content:space-between;padding:10px 16px 16px;border-top:1px solid #f1f5f9;margin-top:4px; }
        .zdp-today-btn {
            font-size:12px;font-weight:700;color:#2563eb;background:none;border:none;
            cursor:pointer;padding:7px 12px;border-radius:8px;font-family:inherit;
            transition:background .1s;display:flex;align-items:center;gap:5px;
        }
        .zdp-today-btn:hover { background:#eff6ff; }
        .zdp-confirm-btn {
            font-size:12px;font-weight:700;color:#fff;background:#2563eb;
            border:none;cursor:pointer;padding:7px 20px;border-radius:8px;
            font-family:inherit;transition:background .1s;box-shadow:0 2px 8px rgba(37,99,235,.3);
        }
        .zdp-confirm-btn:hover { background:#1d4ed8; }

        /* ══ CAROUSEL ══ */
        .car-wrap { position:relative;overflow:hidden;width:100%; }
        .car-track {
            display:flex;transition:transform .42s cubic-bezier(.4,0,.2,1);
            will-change:transform;width:100%;align-items:stretch;
        }
        .car-slide {
            min-width:100%;max-width:100%;flex-shrink:0;
            box-sizing:border-box;overflow:hidden;
            display:flex;flex-direction:column;
        }
        .car-slide > .widget-card {
            width:100%;box-sizing:border-box;overflow:hidden;
            display:flex;flex-direction:column;flex:1;
        }
        .car-arrow {
            position:absolute;top:50%;transform:translateY(-50%);z-index:20;
            width:30px;height:30px;border-radius:50%;
            background:rgba(255,255,255,.95);border:1px solid rgba(255,255,255,.6);
            color:#374151;font-size:16px;font-weight:700;cursor:pointer;
            display:grid;place-items:center;line-height:1;
            box-shadow:0 2px 12px rgba(0,0,0,.18);transition:all .15s;
        }
        .car-arrow:hover { background:#fff;box-shadow:0 4px 16px rgba(0,0,0,.22); transform:translateY(-50%) scale(1.08); }
        .car-arrow.prev { left:6px; }
        .car-arrow.next { right:6px; }

        /* Tab pills */
        .car-tabs { display:flex;justify-content:center;gap:6px;margin-bottom:10px;flex-wrap:wrap; }
        .car-tab {
            display:inline-flex;align-items:center;gap:5px;
            padding:6px 14px;border-radius:50px;font-size:11px;font-weight:700;
            cursor:pointer;border:1px solid rgba(255,255,255,.25);
            background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);
            transition:all .2s;white-space:nowrap;
        }
        .car-tab.active {
            background:rgba(255,255,255,.25);color:#fff;
            border-color:rgba(255,255,255,.5);
            box-shadow:0 2px 8px rgba(0,0,0,.15);
        }
        .car-tab:hover:not(.active) { background:rgba(255,255,255,.15);color:rgba(255,255,255,.85); }

        .tbl-scroll { width:100%;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;flex:1; }

        /* widget header */
        .widget-header {
            display:flex;align-items:center;justify-content:space-between;
            flex-wrap:wrap;gap:6px;padding:10px 14px;
        }
        .widget-header-left { display:flex;align-items:center;gap:8px;min-width:0;flex:1; }
        .widget-header-left > div { min-width:0; }
        .widget-header-title {
            color:#fff;font-weight:800;font-size:11.5px;line-height:1.3;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .widget-header-sub {
            font-size:9px;line-height:1.3;color:rgba(255,255,255,.65);
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }

        /* ══ STATS BAR ══ */
        .stats-bar { display:grid;grid-template-columns:repeat(3,1fr);border-bottom:1px solid #f1f5f9; }
        .stat-cell { padding:8px 6px;text-align:center;position:relative; }
        .stat-cell:not(:last-child)::after {
            content:'';position:absolute;right:0;top:20%;height:60%;width:1px;background:#f1f5f9;
        }
        .stat-num { font-size:18px;font-weight:900;line-height:1;margin-bottom:3px;font-variant-numeric:tabular-nums; }
        .stat-lbl { font-size:9px;color:#94a3b8;font-weight:600;letter-spacing:.2px; }

        /* ══ FOOTER BAR ══ */
        .widget-footer {
            display:flex;align-items:center;justify-content:space-between;
            padding:7px 14px;border-top:1px solid #f1f5f9;background:#fafbfc;
        }
        .widget-footer-left { font-size:9.5px;color:#64748b;font-weight:600;display:flex;align-items:center;gap:5px; }
        .widget-footer-right { font-size:8.5px;color:#cbd5e1;font-weight:500; }

        /* info banner */
        .info-banner {
            background:#fffbeb;border-bottom:1px solid #fde68a;
            padding:6px 14px;display:flex;align-items:center;gap:6px;
            font-size:10px;color:#92400e;font-weight:600;
        }

        /* dot indicator */
        .car-dot {
            width:8px;height:8px;border-radius:50%;
            background:rgba(255,255,255,.35);cursor:pointer;
            transition:all .25s;display:inline-block;
        }
        .car-dot.active { width:24px;border-radius:4px;background:rgba(255,255,255,.85); }

        /* ══ ZOOM COLGROUP WIDTHS ══ */
        .zoom-col-no      { width:18px; }
        .zoom-col-topik   { width:28%; }
        .zoom-col-waktu   { width:72px; }
        .zoom-col-durasi  { width:28px; }
        .zoom-col-peserta { width:44px; }
        .zoom-col-breakout{ width:72px; }
        .zoom-col-status  { width:72px; }

        /* ══ RR COLGROUP WIDTHS ══ */
        .rr-col-no      { width:22px; }
        .rr-col-ruangan { width:130px; }
        .rr-col-waktu   { width:90px; }
        .rr-col-durasi  { width:34px; }
        .rr-col-peserta { width:52px; }
        .rr-col-snack   { width:40px; }
        .rr-col-status  { width:82px; }

        /* ══ KV COLGROUP WIDTHS ══ */
        .kv-col-no      { width:22px; }
        .kv-col-kend    { width:140px; }
        .kv-col-waktu   { width:90px; }
        .kv-col-durasi  { width:34px; }
        .kv-col-peminjam{ width:72px; }
        .kv-col-status  { width:82px; }

        /* ══ BMN COLGROUP WIDTHS ══ */
        .bmn-col-no      { width:22px; }
        .bmn-col-barang  { width:32%; }
        .bmn-col-peminjam{ width:28%; }
        .bmn-col-periode { width:90px; }
        .bmn-col-status  { width:82px; }
    </style>
</head>
<body class="min-h-screen">

<!-- ═══ MODAL DATE PICKER — ZOOM ═══ -->
<div class="zdp-overlay" id="zdpOverlay" onclick="zdpOverlayClick(event,'zoom')">
    <div class="zdp-card" id="zdpCard">
        <div class="zdp-head zoom-head">
            <div><div class="zdp-head-title">📅 Pilih Tanggal</div><div class="zdp-head-sub">Lihat jadwal Zoom meeting</div></div>
            <button class="zdp-close" onclick="zdpClose('zoom')">×</button>
        </div>
        <div class="zdp-nav">
            <button class="zdp-nav-btn" onclick="zdpNavMonth(-1,'zoom')">‹</button>
            <span class="zdp-month-lbl" id="zdpMonthLbl" onclick="zdpToggleMY('zoom')">
                <span id="zdpMonthLblText"></span>
                <svg class="zdp-caret" id="zdpCaret" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </span>
            <button class="zdp-nav-btn" onclick="zdpNavMonth(1,'zoom')">›</button>
        </div>
        <div id="zdpCalendar"></div>
        <div id="zdpMyPicker" style="display:none"></div>
        <div class="zdp-foot">
            <button class="zdp-today-btn" onclick="zdpSelectToday('zoom')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hari Ini
            </button>
            <button class="zdp-confirm-btn" onclick="zdpClose('zoom')">Selesai ✓</button>
        </div>
    </div>
</div>

<!-- ═══ MODAL DATE PICKER — RUANG RAPAT ═══ -->
<div class="zdp-overlay" id="rrpOverlay" onclick="zdpOverlayClick(event,'rapat')">
    <div class="zdp-card rapat-picker" id="rrpCard">
        <div class="zdp-head rapat-head">
            <div><div class="zdp-head-title">📅 Pilih Tanggal</div><div class="zdp-head-sub">Lihat jadwal peminjaman ruang rapat</div></div>
            <button class="zdp-close" onclick="zdpClose('rapat')">×</button>
        </div>
        <div class="zdp-nav">
            <button class="zdp-nav-btn" onclick="zdpNavMonth(-1,'rapat')">‹</button>
            <span class="zdp-month-lbl" id="rrpMonthLbl" onclick="zdpToggleMY('rapat')">
                <span id="rrpMonthLblText"></span>
                <svg class="zdp-caret" id="rrpCaret" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </span>
            <button class="zdp-nav-btn" onclick="zdpNavMonth(1,'rapat')">›</button>
        </div>
        <div id="rrpCalendar"></div>
        <div id="rrpMyPicker" style="display:none"></div>
        <div class="zdp-foot">
            <button class="zdp-today-btn" style="color:#059669" onclick="zdpSelectToday('rapat')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hari Ini
            </button>
            <button class="zdp-confirm-btn" style="background:#059669;box-shadow:0 2px 8px rgba(5,150,105,.3)" onclick="zdpClose('rapat')">Selesai ✓</button>
        </div>
    </div>
</div>

<!-- ═══ MODAL DATE PICKER — KENDARAAN ═══ -->
<div class="zdp-overlay" id="kvpOverlay" onclick="zdpOverlayClick(event,'kendaraan')">
    <div class="zdp-card kend-picker" id="kvpCard">
        <div class="zdp-head kend-head">
            <div><div class="zdp-head-title">📅 Pilih Tanggal</div><div class="zdp-head-sub">Lihat jadwal peminjaman kendaraan</div></div>
            <button class="zdp-close" onclick="zdpClose('kendaraan')">×</button>
        </div>
        <div class="zdp-nav">
            <button class="zdp-nav-btn" onclick="zdpNavMonth(-1,'kendaraan')">‹</button>
            <span class="zdp-month-lbl" id="kvpMonthLbl" onclick="zdpToggleMY('kendaraan')">
                <span id="kvpMonthLblText"></span>
                <svg class="zdp-caret" id="kvpCaret" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </span>
            <button class="zdp-nav-btn" onclick="zdpNavMonth(1,'kendaraan')">›</button>
        </div>
        <div id="kvpCalendar"></div>
        <div id="kvpMyPicker" style="display:none"></div>
        <div class="zdp-foot">
            <button class="zdp-today-btn" style="color:#d97706" onclick="zdpSelectToday('kendaraan')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hari Ini
            </button>
            <button class="zdp-confirm-btn" style="background:#d97706;box-shadow:0 2px 8px rgba(217,119,6,.3)" onclick="zdpClose('kendaraan')">Selesai ✓</button>
        </div>
    </div>
</div>

<!-- ═══ MODAL DATE PICKER — BMN ═══ -->
<div class="zdp-overlay" id="bmnpOverlay" onclick="zdpOverlayClick(event,'bmn')">
    <div class="zdp-card bmn-picker" id="bmnpCard">
        <div class="zdp-head bmn-head">
            <div><div class="zdp-head-title">📅 Pilih Tanggal</div><div class="zdp-head-sub">Lihat jadwal peminjaman BMN</div></div>
            <button class="zdp-close" onclick="zdpClose('bmn')">×</button>
        </div>
        <div class="zdp-nav">
            <button class="zdp-nav-btn" onclick="zdpNavMonth(-1,'bmn')">‹</button>
            <span class="zdp-month-lbl" id="bmnpMonthLbl" onclick="zdpToggleMY('bmn')">
                <span id="bmnpMonthLblText"></span>
                <svg class="zdp-caret" id="bmnpCaret" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </span>
            <button class="zdp-nav-btn" onclick="zdpNavMonth(1,'bmn')">›</button>
        </div>
        <div id="bmnpCalendar"></div>
        <div id="bmnpMyPicker" style="display:none"></div>
        <div class="zdp-foot">
            <button class="zdp-today-btn" style="color:#7c3aed" onclick="zdpSelectToday('bmn')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hari Ini
            </button>
            <button class="zdp-confirm-btn" style="background:#7c3aed;box-shadow:0 2px 8px rgba(124,58,237,.3)" onclick="zdpClose('bmn')">Selesai ✓</button>
        </div>
    </div>
</div>

<!-- ═══ MAIN PAGE ═══ -->
<div class="relative flex flex-col items-center justify-center
            px-6 py-16 md:py-20
            bg-gradient-to-br from-[#0B2E6B] via-[#1557C0] to-[#0A3D8F]
            text-white overflow-hidden">

    <!-- BG blobs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pulse-glow"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl pulse-glow" style="animation-delay:2s"></div>
    </div>

    <!-- ══ HEADER ══ -->
    <div class="relative text-center mb-8 max-w-3xl z-10">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Butuh Bantuan IT?</h1>
        <p class="text-base md:text-lg text-blue-100/90 leading-relaxed">
            Ajukan permintaan atau laporkan gangguan IT Anda melalui sistem layanan IT BPS Provinsi Riau
        </p>
    </div>

    <!-- ══ ACTION CARDS ══ -->
    <div class="relative grid grid-cols-1 lg:grid-cols-3 gap-6 w-full max-w-5xl mb-5 z-10">
        <a href="<?= base_url('user_ticket/create_permintaan') ?>"
           class="group relative rounded-2xl p-8 bg-white border border-blue-100/50
                  shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                  hover:shadow-[0_20px_50px_rgba(37,99,235,0.25)]
                  hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col h-full">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-blue-50/50 via-white to-cyan-50/50 transition-opacity duration-300"></div>
            <div class="relative mb-5 inline-flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Permintaan</span>
            </div>
            <h3 class="relative text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Permintaan Layanan IT &amp; Umum</h3>
            <p class="relative text-sm text-gray-600 leading-relaxed mb-6">
                Ajukan kebutuhan IT seperti <span class="font-semibold text-gray-800">perangkat baru</span>,
                <span class="font-semibold text-gray-800">upgrade RAM/SSD</span>,
                <span class="font-semibold text-gray-800">akses sistem</span>,
                <span class="font-semibold text-gray-800">instalasi aplikasi</span>,
                serta <span class="font-semibold text-gray-800">Zoom &amp; meeting online</span>.
                Termasuk kebutuhan Umum seperti <span class="font-semibold text-gray-800">Penggunaan ruang rapat</span>.
            </p>
            <div class="relative mt-auto flex items-center justify-between pt-5 border-t border-blue-100">
                <span class="font-semibold text-blue-600 group-hover:text-blue-700">Buat Permintaan</span>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:translate-x-1 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        <a href="<?= base_url('user_ticket/create_gangguan') ?>"
           class="group relative rounded-2xl p-8 bg-white border border-red-100/50
                  shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                  hover:shadow-[0_20px_50px_rgba(239,68,68,0.25)]
                  hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col h-full">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-red-50/50 via-white to-orange-50/50 transition-opacity duration-300"></div>
            <div class="relative mb-5 inline-flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 shadow-lg shadow-red-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Gangguan</span>
            </div>
            <h3 class="relative text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors duration-300">Gangguan IT &amp; Umum</h3>
           <p class="relative text-sm text-gray-600 leading-relaxed mb-6">
    Laporkan gangguan seperti <span class="font-semibold text-gray-800">PC/laptop mati</span>,
    <span class="font-semibold text-gray-800">komputer lambat</span>,
    <span class="font-semibold text-gray-800">printer error</span>,
    <span class="font-semibold text-gray-800">internet putus</span>,
    serta <span class="font-semibold text-gray-800">aplikasi bermasalah</span>.
    Termasuk gangguan Umum seperti <span class="font-semibold text-gray-800">AC rusak</span>,
    <span class="font-semibold text-gray-800">lampu mati</span>,
    <span class="font-semibold text-gray-800">kebocoran</span>,
    dan <span class="font-semibold text-gray-800">gangguan rumah tangga kantor</span> lainnya.
</p>
            <div class="relative mt-auto flex items-center justify-between pt-5 border-t border-red-100">
                <span class="font-semibold text-red-600 group-hover:text-red-700">Laporkan Gangguan</span>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white group-hover:translate-x-1 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        <a href="<?= base_url('user_ticket/create_persediaan') ?>"
           class="group relative rounded-2xl p-8 bg-white border border-emerald-100/50
                  shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                  hover:shadow-[0_20px_50px_rgba(16,185,129,0.25)]
                  hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col h-full">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-gradient-to-br from-emerald-50/50 via-white to-green-50/50 transition-opacity duration-300"></div>
            <div class="relative mb-5 inline-flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Persediaan</span>
            </div>
            <h3 class="relative text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors duration-300">Barang Persediaan</h3>
            <p class="relative text-sm text-gray-600 leading-relaxed mb-6">
                <span class="font-semibold text-gray-800">Minta</span> barang dari katalog seperti
                <span class="font-semibold text-gray-800">ATK</span>,
                <span class="font-semibold text-gray-800">toner printer</span>,
                <span class="font-semibold text-gray-800">kertas</span>,
                atau <span class="font-semibold text-gray-800">ajukan</span> barang baru yang belum tersedia.
            </p>
            <div class="relative mt-auto flex items-center justify-between pt-5 border-t border-emerald-100">
                <span class="font-semibold text-emerald-600 group-hover:text-emerald-700">Pilih Layanan</span>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white group-hover:translate-x-1 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
    </div>
    <!-- ══ HISTORY BAR ══ -->
    <div class="relative w-full max-w-5xl mb-6 z-10">
        <div class="history-bar flex flex-col items-center gap-3 px-5 py-4 text-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-semibold text-white/90">Pantau status tiket yang sudah kamu buat</span>
            </div>
            <a href="<?= base_url('user_ticket/history') ?>"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-orange-400 hover:bg-orange-300
                      text-white font-bold text-sm transition-all duration-200 hover:-translate-y-0.5
                      shadow-lg shadow-orange-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lihat Riwayat Tiket Saya
            </a>
        </div>
    </div>

    <!-- ══ CAROUSEL JADWAL ══ -->
    <div class="relative w-full max-w-4xl mb-10 z-10" style="min-width:0;">

        <!-- Tab Pills -->
        <div class="car-tabs" id="carTabs">
            <button class="car-tab active" id="ctab0" onclick="carGoTo(0)">📹 Zoom Meeting</button>
            <button class="car-tab" id="ctab1" onclick="carGoTo(1)">🏢 Ruang Rapat</button>
            <button class="car-tab" id="ctab2" onclick="carGoTo(2)">🚗 Kendaraan</button>
            <button class="car-tab" id="ctab3" onclick="carGoTo(3)">📦 Peminjaman BMN</button>
        </div>

        <!-- Carousel Wrapper -->
        <div class="car-wrap" id="jadwalCarousel">
            <button class="car-arrow prev" onclick="carSlide(-1)" aria-label="Sebelumnya">‹</button>
            <button class="car-arrow next" onclick="carSlide(1)"  aria-label="Berikutnya">›</button>

            <div class="car-track" id="carTrack">

                <!-- ── SLIDE 0 — ZOOM ── -->
                <div class="car-slide">
                    <div class="widget-card bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] overflow-hidden">
                        <div class="widget-header" style="background:linear-gradient(100deg,#0d2255 0%,#1a4db8 60%,#2563eb 100%)">
                            <div class="widget-header-left">
                                <div style="width:32px;height:32px;border-radius:9px;display:grid;place-items:center;font-size:14px;flex-shrink:0;background:rgba(45,140,255,.45);border:1px solid rgba(255,255,255,.2);">📹</div>
                                <div style="min-width:0;">
                                    <div class="widget-header-title">Jadwal Zoom Meeting</div>
                                    <div class="widget-header-sub">Meeting &amp; webinar BPS Provinsi Riau</div>
                                </div>
                            </div>
                            <button class="z-date-btn" id="zDateBtn" onclick="zdpOpen('zoom')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="zDateLabel">–</span>
                            </button>
                        </div>
                        <div class="stats-bar">
                            <div class="stat-cell"><div id="zStBerlangsung" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Berlangsung</div></div>
                            <div class="stat-cell"><div id="zStSegera" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏰ Segera</div></div>
                            <div class="stat-cell"><div id="zStSelesai" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
                        </div>
                        <div class="tbl-scroll">
                            <table class="zt-table">
                                <colgroup>
                                    <col class="zoom-col-no">
                                    <col class="zoom-col-topik">
                                    <col class="zoom-col-waktu">
                                    <col class="zoom-col-durasi">
                                    <col class="zoom-col-peserta">
                                    <col class="zoom-col-breakout">
                                    <col class="zoom-col-status">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Topik Meeting</th>
                                        <th class="th-center">Waktu</th>
                                        <th class="th-center">Durasi</th>
                                        <th class="th-center">Peserta</th>
                                        <th class="th-center">Breakout</th>
                                        <th class="th-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="zTbody">
                                    <tr><td colspan="7"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-footer">
                            <span class="widget-footer-left">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span id="zFooter">—</span>
                            </span>
                            <span class="widget-footer-right">Sumber: Tiket Permintaan Zoom</span>
                        </div>
                    </div>
                </div>

                <!-- ── SLIDE 1 — RUANG RAPAT ── -->
                <div class="car-slide">
                    <div class="widget-card bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] overflow-hidden">
                        <div class="widget-header" style="background:linear-gradient(100deg,#064e3b 0%,#059669 60%,#10b981 100%)">
                            <div class="widget-header-left">
                                <div style="width:32px;height:32px;border-radius:9px;display:grid;place-items:center;font-size:14px;flex-shrink:0;background:rgba(5,150,105,.4);border:1px solid rgba(255,255,255,.2);">🏢</div>
                                <div style="min-width:0;">
                                    <div class="widget-header-title">Jadwal Penggunaan Ruang Rapat</div>
                                    <div class="widget-header-sub">Ketersediaan &amp; peminjaman ruangan BPS Provinsi Riau</div>
                                </div>
                            </div>
                            <button class="z-date-btn" id="rrDateBtn" onclick="zdpOpen('rapat')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="rrDateLabel">–</span>
                            </button>
                        </div>
                        <div class="info-banner">
                            <svg width="13" height="13" fill="none" stroke="#b45309" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Peminjaman ruang rapat harus melalui pengajuan tiket permintaan terlebih dahulu.</span>
                        </div>
                        <div class="stats-bar">
                            <div class="stat-cell"><div id="rrStBerlangsung" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Berlangsung</div></div>
                            <div class="stat-cell"><div id="rrStSegera" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏰ Segera</div></div>
                            <div class="stat-cell"><div id="rrStSelesai" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
                        </div>
                        <div class="tbl-scroll">
                            <table class="zt-table rr-table">
                                <colgroup>
                                    <col class="rr-col-no">
                                    <col class="rr-col-ruangan">
                                    <col class="rr-col-waktu">
                                    <col class="rr-col-durasi">
                                    <col class="rr-col-peserta">
                                    <col class="rr-col-snack">
                                    <col class="rr-col-status">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ruangan &amp; Keperluan</th>
                                        <th class="th-center">Waktu</th>
                                        <th class="th-center">Durasi</th>
                                        <th class="th-center">Peserta</th>
                                        <th class="th-center">Snack</th>
                                        <th class="th-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="rrTbody">
                                    <tr><td colspan="7"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-footer">
                            <span class="widget-footer-left">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span id="rrFooter">—</span>
                            </span>
                            <span class="widget-footer-right">Sumber: Peminjaman Ruang Rapat</span>
                        </div>
                    </div>
                </div>

                <!-- ── SLIDE 2 — KENDARAAN ── -->
                <div class="car-slide">
                    <div class="widget-card bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] overflow-hidden">
                        <div class="widget-header" style="background:linear-gradient(100deg,#78350f 0%,#d97706 60%,#f59e0b 100%)">
                            <div class="widget-header-left">
                                <div style="width:32px;height:32px;border-radius:9px;display:grid;place-items:center;font-size:14px;flex-shrink:0;background:rgba(217,119,6,.4);border:1px solid rgba(255,255,255,.2);">🚗</div>
                                <div style="min-width:0;">
                                    <div class="widget-header-title">Jadwal Peminjaman Kendaraan Dinas</div>
                                    <div class="widget-header-sub">Kendaraan dinas BPS Provinsi Riau</div>
                                </div>
                            </div>
                            <button class="z-date-btn" id="kvDateBtn" onclick="zdpOpen('kendaraan')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="kvDateLabel">–</span>
                            </button>
                        </div>
                        <div class="info-banner">
                            <svg width="13" height="13" fill="none" stroke="#b45309" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Peminjaman kendaraan dinas harus mendapat persetujuan dari pengelola kendaraan terlebih dahulu.</span>
                        </div>
                        <div class="stats-bar">
                            <div class="stat-cell"><div id="kvStBerlangsung" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Dipinjam</div></div>
                            <div class="stat-cell"><div id="kvStMenunggu" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏳ Menunggu</div></div>
                            <div class="stat-cell"><div id="kvStSelesai" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
                        </div>
                        <div class="tbl-scroll">
                            <table class="zt-table kv-table">
                                <colgroup>
                                    <col class="kv-col-no">
                                    <col class="kv-col-kend">
                                    <col class="kv-col-waktu">
                                    <col class="kv-col-durasi">
                                    <col class="kv-col-peminjam">
                                    <col class="kv-col-status">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kendaraan &amp; Keperluan</th>
                                        <th class="th-center">Waktu Peminjaman</th>
                                        <th class="th-center">Durasi</th>
                                        <th class="th-center">Peminjam</th>
                                        <th class="th-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="kvTbody">
                                    <tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-footer">
                            <span class="widget-footer-left">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span id="kvFooter">—</span>
                            </span>
                            <span class="widget-footer-right">Sumber: Peminjaman Kendaraan Dinas</span>
                        </div>
                    </div>
                </div>

                <!-- ── SLIDE 3 — BMN ── -->
                <div class="car-slide">
                    <div class="widget-card bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] overflow-hidden">
                        <div class="widget-header" style="background:linear-gradient(100deg,#4c1d95 0%,#7c3aed 60%,#8b5cf6 100%)">
                            <div class="widget-header-left">
                                <div style="width:32px;height:32px;border-radius:9px;display:grid;place-items:center;font-size:14px;flex-shrink:0;background:rgba(124,58,237,.4);border:1px solid rgba(255,255,255,.2);">📦</div>
                                <div style="min-width:0;">
                                    <div class="widget-header-title">Jadwal Peminjaman Barang BMN</div>
                                    <div class="widget-header-sub">Barang Milik Negara BPS Provinsi Riau</div>
                                </div>
                            </div>
                            <button class="z-date-btn" id="bmnDateBtn" onclick="zdpOpen('bmn')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="bmnDateLabel">–</span>
                            </button>
                        </div>
                        <div class="info-banner" style="background:#faf5ff;border-color:#e9d5ff;">
                            <svg width="13" height="13" fill="none" stroke="#6d28d9" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span style="color:#6d28d9;">Peminjaman BMN harus mendapat persetujuan pengelola aset terlebih dahulu.</span>
                        </div>
                        <div class="stats-bar">
                            <div class="stat-cell"><div id="bmnStDipinjam" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Dipinjam</div></div>
                            <div class="stat-cell"><div id="bmnStMenunggu" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏳ Menunggu</div></div>
                            <div class="stat-cell"><div id="bmnStSelesai" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
                        </div>
                        <div class="tbl-scroll">
                            <table class="zt-table bmn-table">
                                <colgroup>
                                    <col class="bmn-col-no">
                                    <col class="bmn-col-barang">
                                    <col class="bmn-col-peminjam">
                                    <col class="bmn-col-periode">
                                    <col class="bmn-col-status">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Barang &amp; Tujuan</th>
                                        <th>Peminjam</th>
                                        <th class="th-center">Periode</th>
                                        <th class="th-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bmnTbody">
                                    <tr><td colspan="5"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="widget-footer">
                            <span class="widget-footer-left">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span id="bmnFooter">—</span>
                            </span>
                            <span class="widget-footer-right">Sumber: Peminjaman BMN</span>
                        </div>
                    </div>
                </div>

            </div><!-- /car-track -->
        </div><!-- /car-wrap -->

        <!-- Dot indicator — 4 dots -->
        <div style="display:flex;justify-content:center;gap:6px;margin-top:10px;" id="carDots">
            <span class="car-dot active" onclick="carGoTo(0)"></span>
            <span class="car-dot" onclick="carGoTo(1)"></span>
            <span class="car-dot" onclick="carGoTo(2)"></span>
            <span class="car-dot" onclick="carGoTo(3)"></span>
        </div>

    </div><!-- /carousel container -->

</div><!-- /main page -->

<script>
/* ═══ CONSTANTS ═══ */
const MONTHS_FULL  = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const DAYS_SHORT   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
const zpad   = n  => String(n).padStart(2,'0');
const zdpFmt = d  => `${zpad(d.getDate())} ${MONTHS_SHORT[d.getMonth()]} ${d.getFullYear()}`;
const zdpStr = d  => `${d.getFullYear()}-${zpad(d.getMonth()+1)}-${zpad(d.getDate())}`;
const zdpSame= (a,b) => a.getDate()===b.getDate()&&a.getMonth()===b.getMonth()&&a.getFullYear()===b.getFullYear();

/* ═══ CAROUSEL — 4 slides ═══ */
let carCur = 0;

function carEqualizeHeight() {
    const slides = document.querySelectorAll('.car-slide');
    slides.forEach(s => {
        s.style.height = 'auto';
        s.style.overflow = 'hidden';
        const card = s.querySelector('.widget-card');
        if (card) card.style.height = 'auto';
    });
    requestAnimationFrame(() => {
        let maxH = 0;
        slides.forEach(s => { const h = s.scrollHeight; if (h > maxH) maxH = h; });
        if (maxH > 0) {
            slides.forEach(s => {
                s.style.height = maxH + 'px';
                s.style.overflow = 'hidden';
                const card = s.querySelector('.widget-card');
                if (card) card.style.height = '100%';
            });
        }
    });
}

function carGoTo(n) {
    carCur = n;
    document.getElementById('carTrack').style.transform = `translateX(-${n * 100}%)`;
    [0,1,2,3].forEach(i => {
        document.getElementById('ctab'+i).classList.toggle('active', i===n);
    });
    document.querySelectorAll('.car-dot').forEach((d,i) => d.classList.toggle('active', i===n));
}
function carSlide(dir) { carGoTo((carCur + dir + 4) % 4); }

(function(){
    let sx = 0;
    const el = document.getElementById('jadwalCarousel');
    el.addEventListener('touchstart', e => { sx = e.touches[0].clientX; }, { passive:true });
    el.addEventListener('touchend',   e => {
        const dx = e.changedTouches[0].clientX - sx;
        if (Math.abs(dx) > 40) carSlide(dx < 0 ? 1 : -1);
    }, { passive:true });
})();

/* ═══ DATE PICKER STATE ═══ */
const dpState = {
    zoom:      { selected: new Date(), view: new Date(), myMode: null },
    rapat:     { selected: new Date(), view: new Date(), myMode: null },
    kendaraan: { selected: new Date(), view: new Date(), myMode: null },
    bmn:       { selected: new Date(), view: new Date(), myMode: null },
};
const dpIds = {
    zoom:      { overlay:'zdpOverlay',  calendar:'zdpCalendar',  myPicker:'zdpMyPicker',  monthLblText:'zdpMonthLblText',  caret:'zdpCaret',  dateLabel:'zDateLabel'  },
    rapat:     { overlay:'rrpOverlay',  calendar:'rrpCalendar',  myPicker:'rrpMyPicker',  monthLblText:'rrpMonthLblText',  caret:'rrpCaret',  dateLabel:'rrDateLabel'  },
    kendaraan: { overlay:'kvpOverlay',  calendar:'kvpCalendar',  myPicker:'kvpMyPicker',  monthLblText:'kvpMonthLblText',  caret:'kvpCaret',  dateLabel:'kvDateLabel'  },
    bmn:       { overlay:'bmnpOverlay', calendar:'bmnpCalendar', myPicker:'bmnpMyPicker', monthLblText:'bmnpMonthLblText', caret:'bmnpCaret', dateLabel:'bmnDateLabel' },
};

function zdpOpen(type) {
    const s = dpState[type]; s.view = new Date(s.selected); s.myMode = null;
    zdpRenderCal(type);
    document.getElementById(dpIds[type].overlay).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function zdpClose(type) {
    document.getElementById(dpIds[type].overlay).classList.remove('open');
    document.body.style.overflow = '';
}
function zdpOverlayClick(e, type) {
    if (e.target === document.getElementById(dpIds[type].overlay)) zdpClose(type);
}
function zdpRenderCal(type) {
    const s = dpState[type], ids = dpIds[type];
    const today = new Date(), y = s.view.getFullYear(), m = s.view.getMonth();
    document.getElementById(ids.monthLblText).textContent = MONTHS_FULL[m] + ' ' + y;
    document.getElementById(ids.caret).classList.remove('up');
    const first = new Date(y,m,1).getDay(), dim = new Date(y,m+1,0).getDate(), dprev = new Date(y,m,0).getDate();
    let html = '<div class="zdp-grid">';
    DAYS_SHORT.forEach(d => { html += `<div class="zdp-dow">${d}</div>`; });
    for (let i = first-1; i >= 0; i--) {
        const d = dprev-i, ts = new Date(y,m-1,d).getTime();
        html += `<button class="zdp-day other-month" onclick="zdpPickDay(${ts},'${type}')">${d}</button>`;
    }
    for (let d = 1; d <= dim; d++) {
        const cur = new Date(y,m,d);
        let cls = 'zdp-day';
        if (zdpSame(cur,today))      cls += ' today';
        if (zdpSame(cur,s.selected)) cls += ' selected';
        html += `<button class="${cls}" onclick="zdpPickDay(${cur.getTime()},'${type}')">${d}</button>`;
    }
    const total = first + dim, rem = total % 7 === 0 ? 0 : 7 - (total % 7);
    for (let d = 1; d <= rem; d++) {
        const ts = new Date(y,m+1,d).getTime();
        html += `<button class="zdp-day other-month" onclick="zdpPickDay(${ts},'${type}')">${d}</button>`;
    }
    html += '</div>';
    const cal = document.getElementById(ids.calendar);
    cal.innerHTML = html; cal.style.display = '';
    document.getElementById(ids.myPicker).style.display = 'none';
}
function zdpPickDay(ts, type) {
    const s = dpState[type];
    s.selected = new Date(ts); s.view = new Date(ts);
    document.getElementById(dpIds[type].dateLabel).textContent = zdpFmt(s.selected);
    zdpRenderCal(type); zdpClose(type);
    if      (type==='zoom')      zLoad();
    else if (type==='rapat')     rrLoad();
    else if (type==='kendaraan') kvLoad();
    else if (type==='bmn')       bmnLoad();
}
function zdpNavMonth(dir, type) {
    dpState[type].view.setMonth(dpState[type].view.getMonth() + dir);
    dpState[type].myMode ? zdpRenderMY(type) : zdpRenderCal(type);
}
function zdpToggleMY(type) {
    const s = dpState[type], ids = dpIds[type];
    if (!s.myMode) s.myMode = 'month';
    else if (s.myMode==='month') s.myMode = 'year';
    else { s.myMode = null; zdpRenderCal(type); return; }
    document.getElementById(ids.caret).classList.toggle('up', !!s.myMode);
    zdpRenderMY(type);
}
function zdpRenderMY(type) {
    const s = dpState[type], ids = dpIds[type];
    const y = s.view.getFullYear(), m = s.view.getMonth();
    document.getElementById(ids.monthLblText).textContent = s.myMode==='month' ? 'Pilih Bulan · '+y : 'Pilih Tahun';
    let html = '<div class="zdp-my-grid">';
    if (s.myMode==='month') {
        MONTHS_SHORT.forEach((mn,i) => { html += `<button class="zdp-my-btn${i===m?' active':''}" onclick="zdpPickMonth(${i},'${type}')">${mn}</button>`; });
    } else {
        const sy = y-4;
        for (let yi=sy; yi<=sy+11; yi++) html += `<button class="zdp-my-btn${yi===y?' active':''}" onclick="zdpPickYear(${yi},'${type}')">${yi}</button>`;
    }
    html += '</div>';
    const p = document.getElementById(ids.myPicker);
    p.innerHTML = html; p.style.display = '';
    document.getElementById(ids.calendar).style.display = 'none';
}
function zdpPickMonth(m, type) { dpState[type].view.setMonth(m); dpState[type].myMode=null; zdpRenderCal(type); }
function zdpPickYear(y, type)  { dpState[type].view.setFullYear(y); dpState[type].myMode='month'; zdpRenderMY(type); }
function zdpSelectToday(type) {
    dpState[type].selected = new Date(); dpState[type].view = new Date();
    document.getElementById(dpIds[type].dateLabel).textContent = zdpFmt(dpState[type].selected);
    zdpRenderCal(type); zdpClose(type);
    if      (type==='zoom')      zLoad();
    else if (type==='rapat')     rrLoad();
    else if (type==='kendaraan') kvLoad();
    else if (type==='bmn')       bmnLoad();
}
function escHtml(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ═══ LOAD TRACKER ═══ */
const loadDone = { zoom: false, rapat: false, kendaraan: false, bmn: false };
function checkAllLoaded() {
    if (loadDone.zoom && loadDone.rapat && loadDone.kendaraan && loadDone.bmn)
        setTimeout(carEqualizeHeight, 50);
}

/* ═══ ZOOM WIDGET ═══ */
function zGetStatus(row) {
    const now=new Date(), jam=row.jam||'';
    const durasi=parseFloat(row.durasi)||0, link=(row.layanan_link||'').trim();
    if (!jam) return 'jadwal';
    const tanggalTampil  = zdpStr(dpState.zoom.selected);
    const tanggalMulai   = row.tanggal || tanggalTampil;
    const tanggalSelesai = row.tanggal_selesai || tanggalMulai;
    if (tanggalTampil < tanggalMulai || tanggalTampil > tanggalSelesai) return 'jadwal';
    const [h,mnt]=jam.split(':').map(Number);
    const startMs=new Date(tanggalTampil+'T'+zpad(h)+':'+zpad(mnt||0)+':00').getTime();
    const endMs=startMs+durasi*3600000, nowMs=now.getTime();
    if (nowMs>endMs) return 'selesai';
    if (nowMs>=startMs&&nowMs<=endMs) return 'berlangsung';
    if (link&&link!=='#'&&(startMs-nowMs)<=3600000) return 'segera';
    return 'jadwal';
}
const Z_BADGE = {
    berlangsung:{cls:'zb-berlangsung',label:'🔴 Berlangsung',dot:true},
    segera:{cls:'zb-segera',label:'⏰ Segera',dot:false},
    selesai:{cls:'zb-selesai',label:'✅ Selesai',dot:false},
    jadwal:{cls:'zb-jadwal',label:'🔵 Terjadwal',dot:false},
};
function isOverlapJS(a,b) {
    if (!a.jam||!b.jam||!a.durasi||!b.durasi) return false;
    const toMin=t=>{const[h,m]=t.split(':').map(Number);return h*60+(m||0);};
    const sA=toMin(a.jam),eA=sA+parseFloat(a.durasi)*60;
    const sB=toMin(b.jam),eB=sB+parseFloat(b.durasi)*60;
    return sA<eB&&sB<eA;
}
function detectBreakouts(rows) {
    const byAkun={};
    rows.forEach(r=>{const k=r.akun_zoom||1;(byAkun[k]=byAkun[k]||[]).push(r);});
    const breakoutOf={};
    Object.values(byAkun).forEach(grp=>{
        grp.sort((a,b)=>(a.jam||'').localeCompare(b.jam||''));
        for(let i=0;i<grp.length;i++) for(let j=i+1;j<grp.length;j++)
            if(!breakoutOf[grp[j].id]&&isOverlapJS(grp[i],grp[j])) breakoutOf[grp[j].id]=grp[i];
    });
    return breakoutOf;
}
function zRender(rows, dateObj) {
    const tbody=document.getElementById('zTbody');
    const ds=DAYS_SHORT[dateObj.getDay()]+', '+zpad(dateObj.getDate())+' '+MONTHS_FULL[dateObj.getMonth()]+' '+dateObj.getFullYear();
    
    // ✅ FIX: filter Ditolak & Dibatalkan di sini (sebelumnya hanya di zLoad)
    const filtered=rows.filter(r=>!['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));
    
    let cB=0,cS=0,cSe=0;
    filtered.forEach(r=>{const s=zGetStatus(r);if(s==='berlangsung')cB++;if(s==='segera')cS++;if(s==='selesai')cSe++;});
    document.getElementById('zStBerlangsung').textContent=cB;
    document.getElementById('zStSegera').textContent=cS;
    document.getElementById('zStSelesai').textContent=cSe;
    document.getElementById('zFooter').textContent=filtered.length+' meeting · '+ds;
    if (!filtered.length) {
        tbody.innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">📭</div><p>Tidak ada jadwal Zoom pada tanggal ini</p></div></td></tr>`;
        loadDone.zoom=true; checkAllLoaded(); return;
    }
    const breakoutOf=detectBreakouts(filtered);
    tbody.innerHTML='';
    filtered.forEach((r,i)=>{
        const st=zGetStatus(r),bi=Z_BADGE[st]||Z_BADGE.jadwal;
        let timeStr='-',durStr='-';
        if(r.jam){
            const[h,m]=r.jam.split(':').map(Number);
            const dur=parseFloat(r.durasi)||0;
            const endMin=h*60+(m||0)+dur*60;
            const eH=Math.floor(endMin/60)%24,eM=Math.round(endMin%60);
            timeStr=zpad(h)+':'+zpad(m||0)+'–'+zpad(eH)+':'+zpad(eM);
            durStr=dur>=1?Math.floor(dur)+'j'+(dur%1?Math.round(dur%1*60)+'m':''):Math.round(dur*60)+'m';
        }
        const pesertaTeks=r.jumlah_peserta?r.jumlah_peserta+' org':'-';
        const peserta=r.dokumen_url
            ?`<div style="display:inline-flex;flex-direction:column;align-items:center;gap:2px;">
                 <span class="tdur">👥 ${escHtml(pesertaTeks)}</span>
                 <a href="${escHtml(r.dokumen_url)}" target="_blank" style="display:inline-flex;align-items:center;gap:2px;background:#f5f3ff;border:1px solid #c4b5fd;color:#7c3aed;border-radius:20px;padding:1px 5px;font-size:8.5px;font-weight:700;text-decoration:none;white-space:nowrap;line-height:1.6;">📄 PDF</a>
               </div>`
            :`<span class="tdur">👥 ${escHtml(pesertaTeks)}</span>`;
        const topik=r.detail_permintaan||r.jenis_kerusakan?.split('\n')[0]||'Meeting Zoom';
        const host=r.pelapor||'-';
        const mainRow=breakoutOf[r.id];
        const mainTopik=mainRow?(mainRow.detail_permintaan||mainRow.jenis_kerusakan?.split('\n')[0]||'Rapat Utama'):'';
        const breakoutCell=mainRow
            ?`<span class="zbo-badge"><span>🔀 Breakout dari:</span><strong title="${escHtml(mainTopik)}">${escHtml(mainTopik)}</strong></span>`
            :`<span style="color:#d1d5db;font-size:10px">—</span>`;
        const tr=document.createElement('tr');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:9.5px;text-align:center">${i+1}</td>
            <td><div class="tc">
                <div class="tc-ico" style="background:#eff6ff">📹</div>
                <div style="min-width:0;">
                    <div class="tc-name" title="${escHtml(topik)}">${escHtml(topik)}</div>
                    <div class="tc-host" title="${escHtml(host)}">${escHtml(host)}</div>
                </div>
            </div></td>
            <td class="td-center"><span class="tchip" style="font-size:9.5px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">🕐 ${timeStr}</span></td>
            <td class="td-center"><span class="tdur">${durStr}</span></td>
            <td class="td-center">${peserta}</td>
            <td class="td-center">${breakoutCell}</td>
            <td class="td-center"><span class="zbadge ${bi.cls}">${bi.dot?'<span class="zdot"></span>':''}${bi.label}</span></td>
        `;
        tbody.appendChild(tr);
    });
    loadDone.zoom=true; checkAllLoaded();
}
function zLoad() {
    document.getElementById('zTbody').innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    ['zStBerlangsung','zStSegera','zStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.zoom=false;
    fetch(`<?= base_url('user/zoom_jadwal_ajax') ?>?tanggal=${zdpStr(dpState.zoom.selected)}`)
        .then(r=>{if(!r.ok)throw new Error('HTTP '+r.status);return r.json();})
        .then(json=>{if(json.status==='success')zRender(json.rows.filter(r=>r.status!=='Dibatalkan'),dpState.zoom.selected);else zShowError(json.message||'Terjadi kesalahan');})
        .catch(err=>zShowError('Gagal memuat data: '+err.message));
}
function zShowError(msg) {
    document.getElementById('zTbody').innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">⚠️</div><p>${escHtml(msg)}</p></div></td></tr>`;
    ['zStBerlangsung','zStSegera','zStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.zoom=true; checkAllLoaded();
}

/* ═══ RUANG RAPAT WIDGET ═══ */
function rrGetStatus(row) {
    const now = new Date();
    const tanggal    = row.tanggal    || '';
    const jamMulai   = row.jam_mulai  || '';
    const jamSelesai = row.jam_selesai|| '';
    const rowStatus  = (row.status || '').toLowerCase();

    if (rowStatus === 'ditolak' || rowStatus === 'dibatalkan') return 'jadwal';

    // Kalau jam belum diisi, fallback ke status DB
    if (!jamMulai || !jamSelesai) return rowStatus === 'menunggu' ? 'belum' : 'jadwal';

    const [hS, mS] = jamMulai.split(':').map(Number);
    const [hE, mE] = jamSelesai.split(':').map(Number);
    const startMs  = new Date(tanggal + 'T' + zpad(hS) + ':' + zpad(mS||0) + ':00').getTime();
    const endMs    = new Date(tanggal + 'T' + zpad(hE) + ':' + zpad(mE||0) + ':00').getTime();
    const nowMs    = now.getTime();

    // ── TIME-BASED (sama persis seperti Zoom) ──
    if (nowMs > endMs)                                    return 'selesai';
    if (nowMs >= startMs && nowMs <= endMs)               return 'berlangsung';
    if ((startMs - nowMs) <= 3600000 && nowMs < startMs) return 'segera';

    // Belum mulai — kalau masih menunggu persetujuan, tampilkan menunggu
    return rowStatus === 'menunggu' ? 'belum' : 'jadwal';
}
const RR_BADGE = {
    berlangsung:{cls:'zb-berlangsung',label:'🔴 Berlangsung',dot:true},
    segera:{cls:'zb-segera',label:'⏰ Segera',dot:false},
    selesai:{cls:'zb-selesai',label:'✅ Selesai',dot:false},
    jadwal:{cls:'zb-jadwal',label:'🔵 Terjadwal',dot:false},
    belum:{cls:'zb-belum',label:'⏳ Menunggu',dot:false},
};
function rrRender(rows, dateObj) {
    const tbody=document.getElementById('rrTbody');
    const ds=DAYS_SHORT[dateObj.getDay()]+', '+zpad(dateObj.getDate())+' '+MONTHS_FULL[dateObj.getMonth()]+' '+dateObj.getFullYear();
    
    // ✅ FIX: filter Ditolak & Dibatalkan di sini
    const filtered=rows.filter(r=>!['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));
    
    let cB=0,cS=0,cSe=0;
    filtered.forEach(r=>{const s=rrGetStatus(r);if(s==='berlangsung')cB++;if(s==='segera')cS++;if(s==='selesai')cSe++;});
    document.getElementById('rrStBerlangsung').textContent=cB;
    document.getElementById('rrStSegera').textContent=cS;
    document.getElementById('rrStSelesai').textContent=cSe;
    document.getElementById('rrFooter').textContent=filtered.length+' peminjaman · '+ds;
    if(!filtered.length){
        tbody.innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">🏢</div><p>Tidak ada jadwal peminjaman ruang rapat pada tanggal ini</p></div></td></tr>`;
        loadDone.rapat=true; checkAllLoaded(); return;
    }
    tbody.innerHTML='';
    filtered.forEach((r,i)=>{
        const st=rrGetStatus(r),bi=RR_BADGE[st]||RR_BADGE.jadwal;
        let timeStr='-',durStr='-';
        if(r.jam_mulai&&r.jam_selesai){
            const[hS,mS]=r.jam_mulai.split(':').map(Number);
            const[hE,mE]=r.jam_selesai.split(':').map(Number);
            const totalMnt=(hE*60+(mE||0))-(hS*60+(mS||0));
            const durH=Math.floor(totalMnt/60),durM=totalMnt%60;
            timeStr=zpad(hS)+':'+zpad(mS||0)+'–'+zpad(hE)+':'+zpad(mE||0);
            durStr=(durH>0?durH+'j ':'')+(durM>0?durM+'m':'');
            if(!durStr.trim()) durStr='—';
        }
        const namaRuangan=r.nama_ruangan||r.ruangan||'-';
        const lokasi=r.lokasi_ruangan||'';
        const keperluan=r.keperluan||'-';
        const peminjam=r.nama_peminjam||'-';
        const unitKerja=r.unit_kerja||'';
        const jumlahPeserta=r.jumlah_peserta?r.jumlah_peserta+' org':'-';
        const kapasitas=r.kapasitas||0;
        let capPct=0,capCls='';
        if(kapasitas&&r.jumlah_peserta){
            capPct=Math.min(100,Math.round((r.jumlah_peserta/kapasitas)*100));
            capCls=capPct>=100?'cap-full':capPct>=80?'cap-warn':'';
        }
        const snack=r.snack_corner==='Ya'
            ?`<span class="snack-badge">☕${r.jumlah_snack?' ('+r.jumlah_snack+')':''}</span>`
            :`<span style="color:#d1d5db;font-size:10px">—</span>`;
     // Multi-dokumen rapat — GANTI SELURUH BLOK INI
let dokumenHtml = '';
if (r.dokumen_list && r.dokumen_list.length) {
    const docsJson = escHtml(JSON.stringify(r.dokumen_list));
    dokumenHtml = `<button onclick="openDokModal(${docsJson}, '${escHtml(r.keperluan)}')"
        style="margin-top:3px;display:inline-flex;align-items:center;gap:3px;
               background:#f5f3ff;border:1px solid #c4b5fd;color:#7c3aed;
               border-radius:20px;padding:2px 7px;font-size:8px;font-weight:700;
               cursor:pointer;font-family:inherit;white-space:nowrap;line-height:1.8;
               transition:all .15s;"
        onmouseover="this.style.background='#ede9fe'"
        onmouseout="this.style.background='#f5f3ff'">
        📄 ${r.dokumen_list.length} Dok
    </button>`;
}
const pesertaRR = `<div style="display:inline-flex;flex-direction:column;align-items:center;gap:3px;">
    <span class="tdur">👥 ${escHtml(jumlahPeserta)}</span>
    ${dokumenHtml}
</div>`;
        const tr=document.createElement('tr');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td><div class="tc">
                <div class="tc-ico" style="background:#ecfdf5;flex-shrink:0;">🏢</div>
                <div style="min-width:0;max-width:100%;overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:3px;margin-bottom:2px;overflow:hidden;">
                        <span class="ruangan-chip" style="max-width:95px;overflow:hidden;text-overflow:ellipsis;flex-shrink:0;" title="${escHtml(namaRuangan)}">🏢 ${escHtml(namaRuangan)}</span>
                        ${lokasi?`<span style="font-size:9px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:55px;" title="${escHtml(lokasi)}">📍${escHtml(lokasi)}</span>`:''}
                    </div>
                    <div style="font-weight:700;color:#1e293b;font-size:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(keperluan)}">${escHtml(keperluan)}</div>
                    <div class="tc-host" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(peminjam+(unitKerja?' · '+unitKerja:''))}">${escHtml(peminjam)}${unitKerja?' · '+escHtml(unitKerja):''}</div>
                </div>
            </div></td>
            <td class="td-center"><span class="tchip tchip-green" style="font-size:9.5px;">🕐 ${timeStr}</span></td>
            <td class="td-center"><span class="tdur">${durStr.trim()}</span></td>
            <td class="td-center">${pesertaRR}</td>
            <td class="td-center">${snack}</td>
            <td class="td-center"><span class="zbadge ${bi.cls}">${bi.dot?'<span class="zdot"></span>':''}${bi.label}</span></td>
        `;
        tbody.appendChild(tr);
    });
    loadDone.rapat=true; checkAllLoaded();
}
function rrLoad() {
    document.getElementById('rrTbody').innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    ['rrStBerlangsung','rrStSegera','rrStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.rapat=false;
    fetch(`<?= base_url('ruang_rapat/jadwal_ajax') ?>?tanggal=${zdpStr(dpState.rapat.selected)}`)
        .then(r=>{if(!r.ok)throw new Error('HTTP '+r.status);return r.json();})
        .then(json=>{if(json.status==='success')rrRender(json.rows,dpState.rapat.selected);else rrShowError(json.message||'Terjadi kesalahan');})
        .catch(err=>rrShowError('Gagal memuat data: '+err.message));
}
function rrShowError(msg) {
    document.getElementById('rrTbody').innerHTML=`<tr><td colspan="7"><div class="zoom-empty"><div class="zi">⚠️</div><p>${escHtml(msg)}</p></div></td></tr>`;
    ['rrStBerlangsung','rrStSegera','rrStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.rapat=true; checkAllLoaded();
}

/* ═══ KENDARAAN WIDGET ═══ */
function kvGetStatus(row, tanggal) {
    const status=(row.status||'').toLowerCase();
    if(status==='selesai') return 'selesai';
    if(status==='menunggu') return 'menunggu';
    if(status!=='disetujui') return 'menunggu';
    if(row.tipe_pinjam==='per_jam'&&row.jam_pinjam&&row.jam_kembali){
        const nowMs=new Date().getTime();
        const startMs=new Date(tanggal+'T'+row.jam_pinjam).getTime();
        const endMs=new Date(tanggal+'T'+row.jam_kembali).getTime();
        if(nowMs>endMs) return 'selesai';
        if(nowMs>=startMs&&nowMs<=endMs) return 'berlangsung';
        return 'jadwal';
    }
    if(row.tanggal_pinjam<=tanggal&&row.tanggal_kembali>=tanggal) return 'berlangsung';
    return 'jadwal';
}
const KV_BADGE = {
    berlangsung:{cls:'zb-berlangsung',label:'🔴 Dipinjam',dot:true},
    menunggu:{cls:'zb-segera',label:'⏳ Menunggu',dot:false},
    selesai:{cls:'zb-selesai',label:'✅ Selesai',dot:false},
    jadwal:{cls:'zb-jadwal',label:'🔵 Terjadwal',dot:false},
};
function kvRender(rows, dateObj) {
    const tbody=document.getElementById('kvTbody');
    const tglStr=zdpStr(dateObj);
    const ds=DAYS_SHORT[dateObj.getDay()]+', '+zpad(dateObj.getDate())+' '+MONTHS_FULL[dateObj.getMonth()]+' '+dateObj.getFullYear();
    
    // ✅ FIX: filter Ditolak & Dibatalkan di sini
    const filtered=rows.filter(r=>!['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));
    
    let cB=0,cM=0,cS=0;
    filtered.forEach(r=>{const s=kvGetStatus(r,tglStr);if(s==='berlangsung')cB++;if(s==='menunggu')cM++;if(s==='selesai')cS++;});
    document.getElementById('kvStBerlangsung').textContent=cB;
    document.getElementById('kvStMenunggu').textContent=cM;
    document.getElementById('kvStSelesai').textContent=cS;
    document.getElementById('kvFooter').textContent=filtered.length+' peminjaman · '+ds;
    if(!filtered.length){
        tbody.innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">🚗</div><p>Tidak ada peminjaman kendaraan pada tanggal ini</p></div></td></tr>`;
        loadDone.kendaraan=true; checkAllLoaded(); return;
    }
    tbody.innerHTML='';
    filtered.forEach((r,i)=>{
        const st=kvGetStatus(r,tglStr),bi=KV_BADGE[st]||KV_BADGE.jadwal;
        const icon=r.jenis==='Mobil'?'🚗':'🏍️';
        let waktuStr='',durStr='-';
        if(r.tipe_pinjam==='per_jam'&&r.jam_pinjam&&r.jam_kembali){
            const jp=r.jam_pinjam.substring(0,5),jk=r.jam_kembali.substring(0,5);
            waktuStr=`<span class="tchip tchip-amber" style="font-size:9.5px;">🕐 ${jp}–${jk}</span>`;
            const[hS,mS]=r.jam_pinjam.split(':').map(Number);
            const[hE,mE]=r.jam_kembali.split(':').map(Number);
            const mnt=(hE*60+(mE||0))-(hS*60+(mS||0));
            const dH=Math.floor(mnt/60),dM=mnt%60;
            durStr=(dH>0?dH+'j ':'')+(dM>0?dM+'m':'');
        } else {
            const tP=new Date(r.tanggal_pinjam+'T00:00:00'),tK=new Date(r.tanggal_kembali+'T00:00:00');
            const hari=Math.round((tK-tP)/86400000)+1;
            const tPFmt=zpad(tP.getDate())+' '+MONTHS_SHORT[tP.getMonth()];
            const tKFmt=zpad(tK.getDate())+' '+MONTHS_SHORT[tK.getMonth()];
            waktuStr=`<span class="tchip tchip-amber" style="font-size:9.5px;">📅 ${tPFmt}–${tKFmt}</span>`;
            durStr=hari+' hari';
        }
        const tr=document.createElement('tr');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td><div class="tc">
                <div class="tc-ico" style="background:#fff7ed">${icon}</div>
                <div style="min-width:0;">
                    <div class="tc-name" title="${escHtml(r.nama_kendaraan+' ('+r.nomor_polisi+')')}">${escHtml(r.nama_kendaraan)} <span style="font-size:9px;color:#94a3b8;">(${escHtml(r.nomor_polisi)})</span></div>
                    <div class="tc-host" title="${escHtml(r.keperluan+(r.tujuan?' → '+r.tujuan:''))}">${escHtml(r.keperluan)}${r.tujuan?' → '+escHtml(r.tujuan):''}</div>
                </div>
            </div></td>
            <td class="td-center">${waktuStr}</td>
            <td class="td-center"><span class="tdur">${durStr.trim()}</span></td>
            <td class="td-center">
                <div style="font-size:10.5px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;" title="${escHtml(r.nama_peminjam)}">${escHtml(r.nama_peminjam)}</div>
                ${r.unit_kerja?`<div style="font-size:8.5px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;" title="${escHtml(r.unit_kerja)}">${escHtml(r.unit_kerja)}</div>`:''}
            </td>
            <td class="td-center"><span class="zbadge ${bi.cls}">${bi.dot?'<span class="zdot"></span>':''}${bi.label}</span></td>
        `;
        tbody.appendChild(tr);
    });
    loadDone.kendaraan=true; checkAllLoaded();
}
function kvLoad() {
    document.getElementById('kvTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    ['kvStBerlangsung','kvStMenunggu','kvStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.kendaraan=false;
    fetch(`<?= base_url('ruang_rapat/jadwal_kendaraan_ajax') ?>?tanggal=${zdpStr(dpState.kendaraan.selected)}`)
        .then(r=>{if(!r.ok)throw new Error('HTTP '+r.status);return r.json();})
        .then(json=>{if(json.status==='success')kvRender(json.rows,dpState.kendaraan.selected);else kvShowError(json.message||'Terjadi kesalahan');})
        .catch(err=>kvShowError('Gagal memuat data: '+err.message));
}
function kvShowError(msg) {
    document.getElementById('kvTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⚠️</div><p>${escHtml(msg)}</p></div></td></tr>`;
    ['kvStBerlangsung','kvStMenunggu','kvStSelesai'].forEach(id=>{document.getElementById(id).textContent='–';});
    loadDone.kendaraan=true; checkAllLoaded();
}

/* ═══════════════════════════════════
   BMN WIDGET
═══════════════════════════════════ */
function bmnGetStatus(row, tanggal) {
    const status = (row.status || '').toLowerCase();
    if (status === 'selesai')                            return 'selesai';
    if (status === 'ditolak' || status === 'dibatalkan') return 'selesai';
    if (status === 'menunggu')                           return 'menunggu';
    if (status === 'disetujui')                          return 'dipinjam'; // ✅ teknisi approve = dipinjam
    return 'jadwal';
}
const BMN_BADGE = {
    dipinjam: { cls:'zb-dipinjam', label:'🔴 Dipinjam',  dot:true  },
    menunggu: { cls:'zb-menunggu', label:'⏳ Menunggu',  dot:false },
    selesai:  { cls:'zb-selesai',  label:'✅ Selesai',   dot:false },
    jadwal:   { cls:'zb-jadwal',   label:'🔵 Terjadwal', dot:false },
};
function bmnRender(rows, dateObj) {
    const tbody  = document.getElementById('bmnTbody');
    const tglStr = zdpStr(dateObj);
    const ds     = DAYS_SHORT[dateObj.getDay()]+', '+zpad(dateObj.getDate())+' '+MONTHS_FULL[dateObj.getMonth()]+' '+dateObj.getFullYear();

    // filter buang yang ditolak/dibatalkan dari tampilan
    const filtered = rows.filter(r => !['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));

    let cD=0, cM=0, cS=0;
    filtered.forEach(r => {
        const s = bmnGetStatus(r, tglStr);
        if (s==='dipinjam') cD++;
        if (s==='menunggu') cM++;
        if (s==='selesai')  cS++;
    });
    document.getElementById('bmnStDipinjam').textContent = cD;
    document.getElementById('bmnStMenunggu').textContent = cM;
    document.getElementById('bmnStSelesai').textContent  = cS;
    document.getElementById('bmnFooter').textContent     = filtered.length + ' peminjaman · ' + ds;

    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="zoom-empty"><div class="zi">📦</div><p>Tidak ada peminjaman BMN pada tanggal ini</p></div></td></tr>`;
        loadDone.bmn = true; checkAllLoaded(); return;
    }

    tbody.innerHTML = '';
    filtered.forEach((r, i) => {
        const st = bmnGetStatus(r, tglStr);
        const bi = BMN_BADGE[st] || BMN_BADGE.jadwal;

        const namaBarang = r.nama_barang || '-';
        const merek      = r.merek       || '';
        const kode       = r.kode_barang ? `${r.kode_barang}${r.nup?'/'+r.nup:''}` : '';
        const tujuan     = r.tujuan_peminjaman || '-';
        const peminjam   = r.nama_peminjam || '-';
        const unitKerja  = r.unit_kerja   || '';

        // Format periode
        const tP    = new Date(r.tanggal_pinjam  + 'T00:00:00');
        const tK    = new Date(r.tanggal_kembali + 'T00:00:00');
        const hari  = Math.round((tK - tP) / 86400000) + 1;
        const tPFmt = zpad(tP.getDate()) + ' ' + MONTHS_SHORT[tP.getMonth()];
        const tKFmt = zpad(tK.getDate()) + ' ' + MONTHS_SHORT[tK.getMonth()];
        const periodeStr = hari === 1
            ? `<span class="tchip tchip-purple" style="font-size:9px;">📅 ${tPFmt}</span>`
            : `<span class="tchip tchip-purple" style="font-size:9px;">📅 ${tPFmt}–${tKFmt}</span>
               <div style="font-size:8.5px;color:#94a3b8;margin-top:1px;">${hari} hari</div>`;

        // Terlambat?
        const today = zdpStr(new Date());
        const terlambat = (st === 'dipinjam' || st === 'menunggu') && r.tanggal_kembali < today;
        const terlambatBadge = terlambat
            ? `<div style="font-size:8px;color:#dc2626;font-weight:700;margin-top:1px;">⚠ Terlambat!</div>`
            : '';

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td><div class="tc">
                <div class="tc-ico" style="background:#faf5ff;flex-shrink:0;">📦</div>
                <div style="min-width:0;max-width:100%;overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:3px;margin-bottom:2px;overflow:hidden;">
                        <span class="bmn-chip" style="max-width:110px;overflow:hidden;text-overflow:ellipsis;flex-shrink:0;" title="${escHtml(namaBarang)}">
                            📦 ${escHtml(namaBarang)}
                        </span>
                    </div>
                    ${merek ? `<div style="font-size:9px;color:#7c3aed;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(merek)}</div>` : ''}
                    ${kode  ? `<div style="font-size:8.5px;color:#94a3b8;font-family:monospace;white-space:nowrap;">${escHtml(kode)}</div>` : ''}
                    <div class="tc-host" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(tujuan)}">${escHtml(tujuan)}</div>
                </div>
            </div></td>
            <td>
                <div style="font-size:10.5px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(peminjam)}">${escHtml(peminjam)}</div>
                ${unitKerja ? `<div style="font-size:8.5px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escHtml(unitKerja)}">${escHtml(unitKerja)}</div>` : ''}
            </td>
            <td class="td-center">
                ${periodeStr}
                ${terlambatBadge}
            </td>
            <td class="td-center"><span class="zbadge ${bi.cls}">${bi.dot?'<span class="zdot"></span>':''}${bi.label}</span></td>
        `;
        tbody.appendChild(tr);
    });
    loadDone.bmn = true; checkAllLoaded();
}
function bmnLoad() {
    document.getElementById('bmnTbody').innerHTML = `<tr><td colspan="5"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    ['bmnStDipinjam','bmnStMenunggu','bmnStSelesai'].forEach(id => { document.getElementById(id).textContent = '–'; });
    loadDone.bmn = false;
    fetch(`<?= base_url('ruang_rapat/jadwal_bmn_ajax') ?>?tanggal=${zdpStr(dpState.bmn.selected)}`)
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(json => {
            if (json.status === 'success') bmnRender(json.rows, dpState.bmn.selected);
            else bmnShowError(json.message || 'Terjadi kesalahan');
        })
        .catch(err => bmnShowError('Gagal memuat data: ' + err.message));
}
function bmnShowError(msg) {
    document.getElementById('bmnTbody').innerHTML = `<tr><td colspan="5"><div class="zoom-empty"><div class="zi">⚠️</div><p>${escHtml(msg)}</p></div></td></tr>`;
    ['bmnStDipinjam','bmnStMenunggu','bmnStSelesai'].forEach(id => { document.getElementById(id).textContent = '–'; });
    loadDone.bmn = true; checkAllLoaded();
}

/* ═══ INIT ═══ */
document.getElementById('zDateLabel').textContent   = zdpFmt(dpState.zoom.selected);
document.getElementById('rrDateLabel').textContent  = zdpFmt(dpState.rapat.selected);
document.getElementById('kvDateLabel').textContent  = zdpFmt(dpState.kendaraan.selected);
document.getElementById('bmnDateLabel').textContent = zdpFmt(dpState.bmn.selected);

let resizeTimer;
window.addEventListener('resize', () => { clearTimeout(resizeTimer); resizeTimer = setTimeout(carEqualizeHeight, 100); });

zLoad(); rrLoad(); kvLoad(); bmnLoad();
</script>
<!-- ═══ MODAL DOKUMEN RAPAT ═══ -->
<div id="dokModal"
     style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);
            backdrop-filter:blur(6px);z-index:99999;
            align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:24px;width:100%;max-width:380px;
                overflow:hidden;box-shadow:0 40px 100px rgba(0,0,0,.28),0 0 0 1px rgba(0,0,0,.05);
                animation:cardIn .24s cubic-bezier(.34,1.56,.64,1);">

        <!-- Header -->
        <div style="padding:20px 22px 16px;border-bottom:1px solid #f1f5f9;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;border-radius:12px;background:#f1f5f9;border:1px solid #e2e8f0;
                                display:grid;place-items:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#475569" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:14px;color:#1e293b;line-height:1.3;">Dokumen Rapat</div>
                        <div id="dokModalSub" style="font-size:11px;color:#64748b;font-weight:600;margin-top:2px;
                             white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:210px;"></div>
                    </div>
                </div>
                <button onclick="closeDokModal()"
                        style="width:32px;height:32px;border-radius:50%;border:1px solid #e2e8f0;cursor:pointer;
                               background:#f8fafc;color:#64748b;font-size:18px;line-height:1;font-weight:300;
                               display:grid;place-items:center;flex-shrink:0;transition:all .15s;"
                        onmouseover="this.style.background='#f1f5f9';this.style.borderColor='#cbd5e1';"
                        onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0';">×</button>
            </div>
            <div id="dokModalCount" style="margin-top:12px;display:inline-flex;align-items:center;gap:6px;
                 background:#f0fdf4;border:1px solid #bbf7d0;border-radius:50px;
                 padding:4px 12px;font-size:11px;font-weight:700;color:#15803d;">
                <svg width="12" height="12" fill="none" stroke="#15803d" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span id="dokModalCountText"></span>
            </div>
        </div>

        <!-- List -->
        <div id="dokModalList" style="padding:16px 20px;display:flex;flex-direction:column;gap:10px;max-height:320px;overflow-y:auto;"></div>

        <!-- Footer -->
        <div style="padding:10px 20px 18px;border-top:1px solid #f1f5f9;background:#fafbfc;">
            <button onclick="closeDokModal()"
                    style="width:100%;padding:10px;border-radius:12px;border:1.5px solid #e2e8f0;
                           background:#fff;color:#64748b;font-size:13px;font-weight:700;cursor:pointer;
                           font-family:inherit;transition:all .15s;"
                    onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1';"
                    onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0';">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
const JENIS_CFG = {
    'Undangan': {
        stroke:'#2563eb', bg:'#eff6ff', border:'#bfdbfe', tagBg:'#dbeafe', tagText:'#1d4ed8',
        icon:'<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
    },
    'Notulensi': {
        stroke:'#059669', bg:'#ecfdf5', border:'#a7f3d0', tagBg:'#dcfce7', tagText:'#15803d',
        icon:'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
    },
    'Dokumentasi': {
        stroke:'#d97706', bg:'#fffbeb', border:'#fde68a', tagBg:'#fef9c3', tagText:'#b45309',
        icon:'<path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>'
    },
    'Daftar Hadir': {
        stroke:'#0891b2', bg:'#ecfeff', border:'#a5f3fc', tagBg:'#cffafe', tagText:'#0e7490',
        icon:'<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'
    },
    'Lampiran Lain': {
        stroke:'#475569', bg:'#f8fafc', border:'#e2e8f0', tagBg:'#f1f5f9', tagText:'#334155',
        icon:'<path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>'
    },
};

function openDokModal(docs, judul) {
    if (typeof docs === 'string') docs = JSON.parse(docs);

    document.getElementById('dokModalSub').textContent = judul || 'Dokumen Rapat';
    document.getElementById('dokModalCountText').textContent = docs.length + ' dokumen tersedia';

    const list = document.getElementById('dokModalList');
    list.innerHTML = '';

    if (!docs || !docs.length) {
        list.innerHTML = `<div style="text-align:center;padding:28px 16px;">
            <svg width="36" height="36" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div style="color:#94a3b8;font-size:12px;font-weight:600;">Belum ada dokumen tersedia</div>
        </div>`;
    } else {
        docs.forEach(d => {
            const cfg = JENIS_CFG[d.jenis] || JENIS_CFG['Lampiran Lain'];
            const item = document.createElement('a');
            item.href   = d.url;
            item.target = '_blank';
            item.style.cssText = `
                display:flex;align-items:center;gap:14px;padding:13px 16px;
                border-radius:14px;border:1.5px solid ${cfg.border};
                background:${cfg.bg};text-decoration:none;
                transition:all .18s;
            `;
            item.onmouseover = () => {
                item.style.transform = 'translateY(-2px)';
                item.style.boxShadow = '0 6px 20px rgba(0,0,0,.09)';
                item.style.borderColor = cfg.stroke;
            };
            item.onmouseout = () => {
                item.style.transform = '';
                item.style.boxShadow = '';
                item.style.borderColor = cfg.border;
            };
            item.innerHTML = `
                <div style="width:42px;height:42px;border-radius:11px;border:1.5px solid ${cfg.border};background:#fff;
                            display:grid;place-items:center;flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="${cfg.stroke}" stroke-width="1.8" viewBox="0 0 24 24">
                        ${cfg.icon}
                    </svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:13px;color:#1e293b;margin-bottom:5px;">${escHtml(d.jenis)}</div>
                    <div style="display:inline-flex;align-items:center;gap:5px;background:${cfg.tagBg};
                         border-radius:20px;padding:3px 9px;font-size:10px;font-weight:700;color:${cfg.tagText};">
                        <svg width="11" height="11" fill="none" stroke="${cfg.tagText}" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Buka PDF
                    </div>
                </div>
                <div style="width:30px;height:30px;border-radius:8px;background:${cfg.tagBg};border:1px solid ${cfg.border};
                            display:grid;place-items:center;flex-shrink:0;">
                    <svg width="13" height="13" fill="none" stroke="${cfg.stroke}" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
            `;
            list.appendChild(item);
        });
    }

    const m = document.getElementById('dokModal');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDokModal() {
    document.getElementById('dokModal').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
</body>
</html>