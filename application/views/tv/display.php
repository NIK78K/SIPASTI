<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TV Display — Jadwal BPS Riau</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin:0; padding:0; }
html, body {
  width:100%; height:100%; overflow:hidden;
  background: linear-gradient(135deg, #0B2E6B 0%, #1557C0 50%, #0A3D8F 100%);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  color: #1e293b;
}

@keyframes pulse-glow { 0%,100%{opacity:.3} 50%{opacity:.5} }
.pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }

/* ═══ TABLE ═══ */
.zt-table { width:100%; border-collapse:collapse; font-size:11px; table-layout:fixed; }
.zt-table thead tr { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
.zt-table thead th {
  padding:7px 8px; text-align:left; font-size:9px; font-weight:700;
  color:#94a3b8; text-transform:uppercase; white-space:nowrap;
  overflow:hidden; text-overflow:ellipsis;
}
.zt-table thead th.th-center { text-align:center; }
.zt-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
.zt-table tbody tr:last-child { border-bottom:none; }
.zt-table tbody tr:hover { background:#eff6ff; }
.zt-table td { padding:7px 8px; vertical-align:middle; overflow:hidden; }
.zt-table td.td-center { text-align:center; }
.rr-table tbody tr:hover { background:#f0fdf4; }
.kd-table tbody tr:hover { background:#fefce8; }

.tc { display:flex; align-items:center; gap:7px; }
.tc-ico { width:28px;height:28px;border-radius:8px;display:grid;place-items:center;font-size:13px;flex-shrink:0; }
.tc-name { font-weight:700;color:#1e293b;line-height:1.3;font-size:12px;white-space:normal;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical; }
.tc-host { font-size:10px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }

.tchip { display:inline-flex;align-items:center;gap:2px;background:#f1f5f9;border-radius:5px;padding:3px 7px;font-size:10px;font-weight:700;color:#475569;white-space:nowrap; }
.tchip-green { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
.tchip-amber { background:#fffbeb;color:#92400e;border:1px solid #fde68a; }
.tdur { font-size:10px;color:#94a3b8;font-weight:600;white-space:nowrap; }

.zbadge { display:inline-flex;align-items:center;gap:3px;padding:3px 8px;border-radius:50px;font-size:10px;font-weight:700;white-space:nowrap; }
.zb-berlangsung { background:#fef2f2;color:#dc2626;border:1px solid #fecaca; }
.zb-segera      { background:#fffbeb;color:#d97706;border:1px solid #fde68a; }
.zb-jadwal      { background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe; }
.zb-selesai     { background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0; }
.zb-belum       { background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0; }
.zdot { width:6px;height:6px;border-radius:50%;background:currentColor; }
.zb-berlangsung .zdot { animation:zblink 1.2s infinite; }
@keyframes zblink { 0%,100%{opacity:1} 50%{opacity:.25} }

.ruangan-chip { display:inline-flex;align-items:center;gap:3px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:5px;padding:2px 7px;font-size:10px;font-weight:700;color:#065f46;white-space:nowrap; }
.kendaraan-chip { display:inline-flex;align-items:center;gap:3px;background:#fef9c3;border:1px solid #fde047;border-radius:5px;padding:2px 7px;font-size:10px;font-weight:700;color:#713f12;white-space:nowrap; }

.zoom-empty { text-align:center;padding:40px 12px;color:#94a3b8; }
.zoom-empty .zi { font-size:30px;margin-bottom:8px; }
.zoom-empty p { font-size:12px;font-weight:600; }

.stats-bar { display:grid;grid-template-columns:repeat(3,1fr);border-bottom:1px solid #f1f5f9;flex-shrink:0; }
.stat-cell { padding:10px 8px;text-align:center;position:relative; }
.stat-cell:not(:last-child)::after { content:'';position:absolute;right:0;top:20%;height:60%;width:1px;background:#f1f5f9; }
.stat-num { font-size:22px;font-weight:900;line-height:1;margin-bottom:3px;font-variant-numeric:tabular-nums; }
.stat-lbl { font-size:10px;color:#94a3b8;font-weight:600; }

.widget-header { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;padding:12px 16px;flex-shrink:0; }
.widget-header-left { display:flex;align-items:center;gap:10px;min-width:0;flex:1; }
.widget-header-title { color:#fff;font-weight:800;font-size:13.5px; }
.widget-header-sub { font-size:10px;color:rgba(255,255,255,.65);margin-top:2px; }

.widget-footer { display:flex;align-items:center;justify-content:space-between;padding:8px 16px;border-top:1px solid #f1f5f9;background:#fafbfc;flex-shrink:0; }
.widget-footer-left { font-size:10px;color:#64748b;font-weight:600;display:flex;align-items:center;gap:5px; }
.widget-footer-right { font-size:9px;color:#cbd5e1;font-weight:500;display:flex;align-items:center;gap:5px; }

.row-live { background:#fef2f2 !important; }
.row-live:hover { background:#fee2e2 !important; }

/* ═══ TV LAYOUT ═══ */
#tv-root { width:100vw;height:100vh;display:flex;flex-direction:column;overflow:hidden;position:relative; }

.tv-topbar {
  flex-shrink:0; height:66px;
  display:flex; align-items:center; justify-content:space-between;
  padding:0 22px;
  background:rgba(255,255,255,0.07);
  border-bottom:1px solid rgba(255,255,255,0.12);
  backdrop-filter:blur(16px);
  position:relative; z-index:20;
  gap:12px;
}

/* ── TOMBOL KEMBALI ── */
.tv-back-btn {
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 14px;border-radius:10px;
  background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);
  color:#fff;font-size:12px;font-weight:700;
  text-decoration:none;transition:all .2s;flex-shrink:0;white-space:nowrap;
}
.tv-back-btn:hover { background:rgba(255,255,255,.22);border-color:rgba(255,255,255,.45);transform:translateY(-1px); }
.tv-back-btn svg { width:14px;height:14px;stroke:currentColor;flex-shrink:0; }

.tv-brand { display:flex;align-items:center;gap:12px;flex-shrink:0; }
.tv-brand-icon { width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);display:grid;place-items:center;font-size:20px; }
.tv-brand-name { font-size:15px;font-weight:800;color:#fff; }
.tv-brand-sub  { font-size:10px;color:rgba(255,255,255,.5);font-weight:400;text-transform:uppercase;letter-spacing:.4px; }

.tv-center { display:flex;align-items:center;gap:10px;flex:1;justify-content:center; }

.tv-tabs { display:flex;align-items:center;gap:6px; }
.tv-tab {
  display:inline-flex;align-items:center;gap:5px;
  padding:7px 16px;border-radius:50px;font-size:11.5px;font-weight:700;
  cursor:pointer;border:1px solid rgba(255,255,255,.25);
  background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);
  transition:all .2s;white-space:nowrap;
}
.tv-tab.active { background:rgba(255,255,255,.25);color:#fff;border-color:rgba(255,255,255,.5);box-shadow:0 2px 8px rgba(0,0,0,.15); }
.tv-tab:hover:not(.active) { background:rgba(255,255,255,.15);color:rgba(255,255,255,.85); }

/* ── DATE PICKER BUTTON ── */
.tv-date-btn {
  display:inline-flex;align-items:center;gap:7px;
  padding:8px 14px;border-radius:10px;
  background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);
  color:#fff;font-family:inherit;font-size:12px;font-weight:700;
  cursor:pointer;transition:all .2s;white-space:nowrap;flex-shrink:0;
}
.tv-date-btn:hover { background:rgba(255,255,255,.22);border-color:rgba(255,255,255,.45);transform:translateY(-1px); }
.tv-date-btn.is-custom { background:rgba(251,191,36,.18);border-color:rgba(251,191,36,.5);color:#fbbf24; }
.tv-date-btn svg { width:14px;height:14px;stroke:currentColor;flex-shrink:0; }
.tv-date-badge {
  display:inline-flex;align-items:center;gap:3px;
  background:rgba(251,191,36,.25);border:1px solid rgba(251,191,36,.4);
  border-radius:5px;padding:1px 6px;font-size:9px;font-weight:800;
  color:#fbbf24;letter-spacing:.3px;
}

.tv-clock-area { text-align:right;flex-shrink:0; }
.tv-clock { font-size:30px;font-weight:900;color:#fff;line-height:1;letter-spacing:-1.5px; }
.tv-date-lbl  { font-size:10px;color:rgba(255,255,255,.55);margin-top:2px; }

.tv-progress { flex-shrink:0;height:3px;background:rgba(255,255,255,.08);overflow:hidden; }
.tv-progress-fill { height:100%;width:0%;background:linear-gradient(90deg,#f59e0b,#fbbf24);transition:width .8s linear; }

.tv-slides { flex:1;overflow:hidden;position:relative; }
.tv-slide {
  position:absolute;inset:0;padding:18px 24px 14px;
  display:flex;flex-direction:column;
  opacity:0;transform:translateX(80px);
  transition:opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1);
  pointer-events:none;
}
.tv-slide.active { opacity:1;transform:translateX(0);pointer-events:auto; }
.tv-slide.out    { opacity:0;transform:translateX(-80px); }

.widget-card { background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,0.18),0 2px 8px rgba(0,0,0,0.08);overflow:hidden;display:flex;flex-direction:column;flex:1; }
.tbl-scroll { width:100%;overflow-x:auto;overflow-y:auto;flex:1;-webkit-overflow-scrolling:touch; }

.car-dots { display:flex;align-items:center;gap:6px; }
.car-dot { width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.35);cursor:pointer;transition:all .25s; }
.car-dot.active { width:22px;border-radius:4px;background:rgba(255,255,255,.85); }

.tv-footer {
  flex-shrink:0;height:38px;
  display:flex;align-items:center;justify-content:space-between;
  padding:0 28px;
  background:rgba(255,255,255,.05);
  border-top:1px solid rgba(255,255,255,.1);
  font-size:11px;color:rgba(255,255,255,.45);
}
.live-dot { width:6px;height:6px;border-radius:50%;background:#22c55e;display:inline-block;margin-right:5px;animation:liveblink 2s ease-in-out infinite; }
@keyframes liveblink { 0%,100%{opacity:1} 50%{opacity:.4} }

/* ═══ DATE PICKER MODAL ═══ */
.dp-overlay {
  position:fixed;inset:0;
  background:rgba(10,20,50,.65);
  backdrop-filter:blur(8px);
  z-index:9999;
  display:none;align-items:center;justify-content:center;
}
.dp-overlay.open { display:flex;animation:dpOverlayIn .2s ease; }
@keyframes dpOverlayIn { from{opacity:0} to{opacity:1} }

.dp-card {
  background:#fff;border-radius:24px;
  box-shadow:0 40px 100px rgba(0,0,0,.4),0 0 0 1px rgba(0,0,0,.05);
  width:340px;overflow:hidden;
  animation:dpCardIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes dpCardIn {
  from{opacity:0;transform:scale(.85) translateY(24px)}
  to{opacity:1;transform:scale(1) translateY(0)}
}

.dp-head {
  background:linear-gradient(135deg,#0d2255 0%,#1a4db8 60%,#2563eb 100%);
  padding:20px 22px 18px;
  display:flex;align-items:center;justify-content:space-between;
}
.dp-head-title { color:#fff;font-size:15px;font-weight:800;display:flex;align-items:center;gap:8px; }
.dp-head-sub   { color:rgba(255,255,255,.6);font-size:10px;margin-top:4px;font-weight:600; }
.dp-close {
  width:32px;height:32px;border-radius:50%;border:none;
  background:rgba(255,255,255,.15);color:#fff;
  font-size:20px;cursor:pointer;display:grid;place-items:center;
  transition:background .15s;line-height:1;font-weight:300;flex-shrink:0;
}
.dp-close:hover { background:rgba(255,255,255,.28); }

.dp-shortcuts { display:flex;gap:6px;padding:14px 16px 0;flex-wrap:wrap; }
.dp-sc-btn {
  display:inline-flex;align-items:center;gap:4px;
  padding:6px 12px;border-radius:8px;
  border:1.5px solid #e2e8f0;background:#f8fafc;
  font-size:11px;font-weight:700;color:#475569;
  cursor:pointer;font-family:inherit;transition:all .15s;white-space:nowrap;
}
.dp-sc-btn:hover { background:#eff6ff;border-color:#bfdbfe;color:#2563eb; }
.dp-sc-btn.active { background:#2563eb;border-color:#2563eb;color:#fff;box-shadow:0 2px 8px rgba(37,99,235,.3); }

.dp-nav { display:flex;align-items:center;justify-content:space-between;padding:14px 16px 8px; }
.dp-nav-btn {
  width:34px;height:34px;border-radius:10px;border:1.5px solid #e2e8f0;
  background:#fff;color:#64748b;font-size:16px;font-weight:700;
  cursor:pointer;display:grid;place-items:center;transition:all .15s;
}
.dp-nav-btn:hover { background:#f1f5f9;border-color:#cbd5e1;color:#1e293b; }
.dp-month-lbl {
  font-size:14px;font-weight:800;color:#1e293b;
  cursor:pointer;padding:6px 12px;border-radius:9px;transition:background .1s;
}
.dp-month-lbl:hover { background:#f1f5f9; }

.dp-grid { display:grid;grid-template-columns:repeat(7,1fr);gap:2px;padding:0 10px 6px; }
.dp-dow { text-align:center;font-size:9px;font-weight:700;color:#94a3b8;padding:4px 0 6px;letter-spacing:.4px; }
.dp-day {
  aspect-ratio:1;display:grid;place-items:center;
  font-size:12.5px;font-weight:600;color:#374151;
  border-radius:9px;cursor:pointer;border:none;
  background:none;font-family:inherit;
  transition:background .1s,color .1s,transform .1s;
}
.dp-day:hover { background:#eff6ff;color:#2563eb;transform:scale(1.1); }
.dp-day.is-today { background:#eff6ff;color:#2563eb;font-weight:800; }
.dp-day.is-selected { background:#2563eb;color:#fff!important;font-weight:800;box-shadow:0 3px 10px rgba(37,99,235,.4); }
.dp-day.is-selected:hover { background:#1d4ed8;transform:scale(1.05); }
.dp-day.other-month { color:#d1d5db; }
.dp-day.other-month:hover { background:#f9fafb;color:#9ca3af;transform:none; }

.dp-foot {
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 16px 16px;border-top:1px solid #f1f5f9;margin-top:4px;
}
.dp-reset-btn {
  display:inline-flex;align-items:center;gap:5px;
  font-size:12px;font-weight:700;color:#ef4444;
  background:none;border:none;cursor:pointer;
  padding:7px 12px;border-radius:8px;font-family:inherit;transition:background .1s;
}
.dp-reset-btn:hover { background:#fef2f2; }
.dp-confirm-btn {
  font-size:12px;font-weight:700;color:#fff;
  background:linear-gradient(135deg,#1a4db8,#2563eb);
  border:none;cursor:pointer;padding:8px 22px;border-radius:9px;
  font-family:inherit;transition:all .15s;box-shadow:0 3px 10px rgba(37,99,235,.3);
}
.dp-confirm-btn:hover { background:linear-gradient(135deg,#1e4fc2,#1d4ed8);transform:translateY(-1px); }

.custom-date-banner {
  background:linear-gradient(90deg,rgba(251,191,36,.15),rgba(251,191,36,.08));
  border-bottom:1px solid rgba(251,191,36,.3);
  padding:5px 16px;
  display:flex;align-items:center;gap:7px;
  font-size:10px;font-weight:700;color:#d97706;flex-shrink:0;
}
.custom-date-banner svg { width:12px;height:12px;stroke:#d97706;flex-shrink:0; }
</style>
</head>
<body>
<div id="tv-root">

  <!-- bg blobs -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none" style="z-index:0;">
    <div class="absolute pulse-glow" style="top:10%;left:5%;width:280px;height:280px;background:rgba(96,165,250,0.08);border-radius:50%;filter:blur(60px);"></div>
    <div class="absolute pulse-glow" style="bottom:10%;right:5%;width:360px;height:360px;background:rgba(167,139,250,0.06);border-radius:50%;filter:blur(80px);animation-delay:2s;"></div>
  </div>

  <!-- ── TOP BAR ── -->
  <div class="tv-topbar">

    <!-- Tombol Kembali ke Login -->
    <a href="<?= base_url('auth/login') ?>" class="tv-back-btn">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>

    <!-- Brand -->
    <div class="tv-brand">
      <div class="tv-brand-icon">🏛️</div>
      <div>
        <div class="tv-brand-name">BPS Provinsi Riau</div>
        <div class="tv-brand-sub">Display Jadwal Harian</div>
      </div>
    </div>

    <!-- Tengah: tab + date btn -->
    <div class="tv-center">
      <div class="tv-tabs">
        <button class="tv-tab active" id="tab0" onclick="goTo(0,true)">📹 Zoom Meeting</button>
        <button class="tv-tab"        id="tab1" onclick="goTo(1,true)">🏢 Ruang Rapat</button>
        <button class="tv-tab"        id="tab2" onclick="goTo(2,true)">🚗 Kendaraan Dinas</button>
      </div>

      <button class="tv-date-btn" id="tvDateBtn" onclick="dpOpen()">
        <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span id="tvDateBtnLabel">Hari Ini</span>
        <span class="tv-date-badge" id="tvDateBadge" style="display:none;">KUSTOM</span>
      </button>
    </div>

    <!-- Kanan: jam -->
    <div class="tv-clock-area">
      <div class="tv-clock" id="clock">--:--</div>
      <div class="tv-date-lbl" id="clockDate">--</div>
    </div>
  </div>

  <!-- ── PROGRESS BAR ── -->
  <div class="tv-progress" style="position:relative;z-index:10;">
    <div class="tv-progress-fill" id="progressFill"></div>
  </div>

  <!-- ── SLIDES ── -->
  <div class="tv-slides" style="z-index:1;">

    <!-- ══════════════════════════════════════
         SLIDE 0 — ZOOM MEETING
    ══════════════════════════════════════ -->
    <div class="tv-slide active" id="slide0">
      <div class="widget-card">
        <div class="widget-header" style="background:linear-gradient(100deg,#0d2255 0%,#1a4db8 60%,#2563eb 100%)">
          <div class="widget-header-left">
            <div style="width:34px;height:34px;border-radius:9px;display:grid;place-items:center;font-size:15px;flex-shrink:0;background:rgba(45,140,255,.4);border:1px solid rgba(255,255,255,.2);">📹</div>
            <div>
              <div class="widget-header-title">Jadwal Zoom Meeting</div>
              <div class="widget-header-sub">Meeting &amp; Webinar BPS Provinsi Riau · <span id="zDateTag">—</span></div>
            </div>
          </div>
          <div class="car-dots" id="dotZ">
            <span class="car-dot active" onclick="goTo(0,true)"></span>
            <span class="car-dot"        onclick="goTo(1,true)"></span>
            <span class="car-dot"        onclick="goTo(2,true)"></span>
          </div>
        </div>

        <div class="custom-date-banner" id="zCustomBanner" style="display:none;">
          <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Menampilkan jadwal untuk: <strong id="zCustomBannerDate">—</strong>
          &nbsp;·&nbsp;<span style="cursor:pointer;text-decoration:underline;" onclick="dpReset()">Kembali ke Hari Ini</span>
        </div>

        <div class="stats-bar">
          <div class="stat-cell"><div id="zSt0" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Berlangsung</div></div>
          <div class="stat-cell"><div id="zSt1" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏰ Segera</div></div>
          <div class="stat-cell"><div id="zSt2" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
        </div>

        <div class="tbl-scroll">
          <table class="zt-table">
            <colgroup>
              <col style="width:28px"><col style="width:34%">
              <col style="width:105px"><col style="width:62px">
              <col style="width:72px"><col style="width:95px">
            </colgroup>
            <thead><tr>
              <th>#</th><th>Topik Meeting</th>
              <th class="th-center">Waktu</th><th class="th-center">Durasi</th>
              <th class="th-center">Peserta</th><th class="th-center">Status</th>
            </tr></thead>
            <tbody id="zTbody">
              <tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal Zoom...</p></div></td></tr>
            </tbody>
          </table>
        </div>

        <div class="widget-footer">
          <span class="widget-footer-left">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span id="zFooter">—</span>
          </span>
          <span class="widget-footer-right"><span class="live-dot"></span>Auto-refresh · Sumber: Tiket Permintaan Zoom</span>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════
         SLIDE 1 — RUANG RAPAT
    ══════════════════════════════════════ -->
    <div class="tv-slide" id="slide1">
      <div class="widget-card">
        <div class="widget-header" style="background:linear-gradient(100deg,#064e3b 0%,#059669 60%,#10b981 100%)">
          <div class="widget-header-left">
            <div style="width:34px;height:34px;border-radius:9px;display:grid;place-items:center;font-size:15px;flex-shrink:0;background:rgba(5,150,105,.4);border:1px solid rgba(255,255,255,.2);">🏢</div>
            <div>
              <div class="widget-header-title">Jadwal Penggunaan Ruang Rapat</div>
              <div class="widget-header-sub">Ketersediaan &amp; Peminjaman Ruangan BPS Provinsi Riau · <span id="rDateTag">—</span></div>
            </div>
          </div>
          <div class="car-dots" id="dotR">
            <span class="car-dot"        onclick="goTo(0,true)"></span>
            <span class="car-dot active" onclick="goTo(1,true)"></span>
            <span class="car-dot"        onclick="goTo(2,true)"></span>
          </div>
        </div>

        <div class="custom-date-banner" id="rCustomBanner" style="display:none;">
          <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Menampilkan jadwal untuk: <strong id="rCustomBannerDate">—</strong>
          &nbsp;·&nbsp;<span style="cursor:pointer;text-decoration:underline;" onclick="dpReset()">Kembali ke Hari Ini</span>
        </div>

        <div class="stats-bar">
          <div class="stat-cell"><div id="rSt0" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Berlangsung</div></div>
          <div class="stat-cell"><div id="rSt1" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏰ Segera</div></div>
          <div class="stat-cell"><div id="rSt2" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai</div></div>
        </div>

        <div class="tbl-scroll">
          <table class="zt-table rr-table">
            <colgroup>
              <col style="width:28px"><col style="width:130px"><col style="width:34%">
              <col style="width:105px"><col style="width:65px"><col style="width:95px">
            </colgroup>
            <thead><tr>
              <th>#</th><th>Ruangan</th><th>Keperluan &amp; Peminjam</th>
              <th class="th-center">Waktu</th><th class="th-center">Peserta</th><th class="th-center">Status</th>
            </tr></thead>
            <tbody id="rTbody">
              <tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal ruang rapat...</p></div></td></tr>
            </tbody>
          </table>
        </div>

        <div class="widget-footer">
          <span class="widget-footer-left">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span id="rFooter">—</span>
          </span>
          <span class="widget-footer-right"><span class="live-dot"></span>Auto-refresh · Sumber: Peminjaman Ruang Rapat</span>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════
         SLIDE 2 — KENDARAAN DINAS
    ══════════════════════════════════════ -->
    <div class="tv-slide" id="slide2">
      <div class="widget-card">
        <div class="widget-header" style="background:linear-gradient(100deg,#713f12 0%,#b45309 60%,#d97706 100%)">
          <div class="widget-header-left">
            <div style="width:34px;height:34px;border-radius:9px;display:grid;place-items:center;font-size:15px;flex-shrink:0;background:rgba(180,83,9,.4);border:1px solid rgba(255,255,255,.2);">🚗</div>
            <div>
              <div class="widget-header-title">Jadwal Kendaraan Dinas</div>
              <div class="widget-header-sub">Peminjaman Kendaraan Dinas BPS Provinsi Riau · <span id="kDateTag">—</span></div>
            </div>
          </div>
          <div class="car-dots" id="dotK">
            <span class="car-dot"        onclick="goTo(0,true)"></span>
            <span class="car-dot"        onclick="goTo(1,true)"></span>
            <span class="car-dot active" onclick="goTo(2,true)"></span>
          </div>
        </div>

        <div class="custom-date-banner" id="kCustomBanner" style="display:none;">
          <svg fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Menampilkan jadwal untuk: <strong id="kCustomBannerDate">—</strong>
          &nbsp;·&nbsp;<span style="cursor:pointer;text-decoration:underline;" onclick="dpReset()">Kembali ke Hari Ini</span>
        </div>

        <div class="stats-bar">
          <div class="stat-cell"><div id="kSt0" class="stat-num" style="color:#dc2626">–</div><div class="stat-lbl">🔴 Sedang Dipinjam</div></div>
          <div class="stat-cell"><div id="kSt1" class="stat-num" style="color:#d97706">–</div><div class="stat-lbl">⏰ Menunggu</div></div>
          <div class="stat-cell"><div id="kSt2" class="stat-num" style="color:#16a34a">–</div><div class="stat-lbl">✅ Selesai / Kembali</div></div>
        </div>

        <div class="tbl-scroll">
          <table class="zt-table kd-table">
            <colgroup>
              <col style="width:28px"><col style="width:150px"><col style="width:32%">
              <col style="width:140px"><col style="width:80px"><col style="width:95px">
            </colgroup>
            <thead><tr>
              <th>#</th><th>Kendaraan</th><th>Keperluan &amp; Peminjam</th>
              <th class="th-center">Tanggal Pinjam</th><th class="th-center">Pengemudi</th><th class="th-center">Status</th>
            </tr></thead>
            <tbody id="kTbody">
              <tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal kendaraan...</p></div></td></tr>
            </tbody>
          </table>
        </div>

        <div class="widget-footer">
          <span class="widget-footer-left">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span id="kFooter">—</span>
          </span>
          <span class="widget-footer-right"><span class="live-dot"></span>Auto-refresh · Sumber: Peminjaman Kendaraan Dinas</span>
        </div>
      </div>
    </div>

  </div><!-- /tv-slides -->

  <!-- ── BOTTOM BAR ── -->
  <div class="tv-footer" style="position:relative;z-index:10;">
    <span style="display:flex;align-items:center;">
      <span class="live-dot"></span>Live — data diperbarui setiap 2 menit
    </span>
    <span>SIPASTI · Sistem Informasi Pengelolaan Aset dan Layanan TI</span>
    <span>Ganti otomatis dalam <strong id="cdLabel" style="color:#fbbf24;font-size:12px;">60</strong>s &nbsp;·&nbsp; ← → untuk geser manual</span>
  </div>

</div><!-- /tv-root -->

<!-- ═══ DATE PICKER MODAL ═══ -->
<div class="dp-overlay" id="dpOverlay" onclick="dpOverlayClick(event)">
  <div class="dp-card" id="dpCard">
    <div class="dp-head">
      <div>
        <div class="dp-head-title">
          <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Pilih Tanggal
        </div>
        <div class="dp-head-sub">Data akan ditampilkan sesuai tanggal yang dipilih</div>
      </div>
      <button class="dp-close" onclick="dpClose()">×</button>
    </div>

    <div class="dp-shortcuts">
      <button class="dp-sc-btn" id="sc-yesterday" onclick="dpShortcut(-1)">◀ Kemarin</button>
      <button class="dp-sc-btn active" id="sc-today" onclick="dpShortcut(0)">📅 Hari Ini</button>
      <button class="dp-sc-btn" id="sc-tomorrow" onclick="dpShortcut(1)">Besok ▶</button>
    </div>

    <div class="dp-nav">
      <button class="dp-nav-btn" onclick="dpNavMonth(-1)">‹</button>
      <span class="dp-month-lbl" id="dpMonthLbl"></span>
      <button class="dp-nav-btn" onclick="dpNavMonth(1)">›</button>
    </div>

    <div id="dpCalendar"></div>

    <div class="dp-foot">
      <button class="dp-reset-btn" onclick="dpReset()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Reset ke Hari Ini
      </button>
      <button class="dp-confirm-btn" onclick="dpConfirm()">Terapkan ✓</button>
    </div>
  </div>
</div>

<script>
/* ════════════════════════════════════════
   HELPERS
════════════════════════════════════════ */
const MONTHS_FULL  = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const DAYS_FULL    = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

const zpad    = n => String(n).padStart(2,'0');
const toStr   = d => `${d.getFullYear()}-${zpad(d.getMonth()+1)}-${zpad(d.getDate())}`;
const samDay  = (a,b) => a.getDate()===b.getDate()&&a.getMonth()===b.getMonth()&&a.getFullYear()===b.getFullYear();
const fmtFull = d => `${DAYS_FULL[d.getDay()]}, ${zpad(d.getDate())} ${MONTHS_FULL[d.getMonth()]} ${d.getFullYear()}`;
const fmtShort= d => `${DAYS_FULL[d.getDay()]}, ${zpad(d.getDate())} ${MONTHS_SHORT[d.getMonth()]} ${d.getFullYear()}`;
const fmtDisp = d => `${zpad(d.getDate())} ${MONTHS_SHORT[d.getMonth()]} ${d.getFullYear()}`;
function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

/* ════════════════════════════════════════
   STATE
════════════════════════════════════════ */
let selectedDate = new Date();
let isCustomDate = false;

/* ════════════════════════════════════════
   DATE PICKER
════════════════════════════════════════ */
let dpViewDate   = new Date();
let dpPickedDate = new Date();

function dpOpen() {
    dpPickedDate = new Date(selectedDate);
    dpViewDate   = new Date(selectedDate);
    dpRenderCal();
    dpUpdateShortcuts();
    document.getElementById('dpOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function dpClose() {
    document.getElementById('dpOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
function dpOverlayClick(e) {
    if (e.target === document.getElementById('dpOverlay')) dpClose();
}
function dpNavMonth(dir) {
    dpViewDate.setMonth(dpViewDate.getMonth() + dir);
    dpRenderCal();
}
function dpRenderCal() {
    const y = dpViewDate.getFullYear(), m = dpViewDate.getMonth();
    const today = new Date();
    document.getElementById('dpMonthLbl').textContent = MONTHS_FULL[m] + ' ' + y;
    const firstDay = new Date(y, m, 1).getDay();
    const daysInMonth = new Date(y, m+1, 0).getDate();
    const prevDays = new Date(y, m, 0).getDate();
    let html = '<div class="dp-grid">';
    ['Min','Sen','Sel','Rab','Kam','Jum','Sab'].forEach(d => { html += `<div class="dp-dow">${d}</div>`; });
    for (let i = firstDay - 1; i >= 0; i--) {
        const d = prevDays - i;
        const dt = new Date(y, m-1, d);
        html += `<button class="dp-day other-month" onclick="dpPickDay(${dt.getTime()})">${d}</button>`;
    }
    for (let d = 1; d <= daysInMonth; d++) {
        const dt = new Date(y, m, d);
        let cls = 'dp-day';
        if (samDay(dt, today))        cls += ' is-today';
        if (samDay(dt, dpPickedDate)) cls += ' is-selected';
        html += `<button class="${cls}" onclick="dpPickDay(${dt.getTime()})">${d}</button>`;
    }
    const filled = firstDay + daysInMonth;
    const rem = filled % 7 === 0 ? 0 : 7 - (filled % 7);
    for (let d = 1; d <= rem; d++) {
        const dt = new Date(y, m+1, d);
        html += `<button class="dp-day other-month" onclick="dpPickDay(${dt.getTime()})">${d}</button>`;
    }
    html += '</div>';
    document.getElementById('dpCalendar').innerHTML = html;
}
function dpPickDay(ts) {
    dpPickedDate = new Date(ts);
    dpViewDate   = new Date(ts);
    dpRenderCal();
    dpUpdateShortcuts();
}
function dpShortcut(offset) {
    const d = new Date();
    d.setDate(d.getDate() + offset);
    dpPickedDate = d;
    dpViewDate   = new Date(d);
    dpRenderCal();
    dpUpdateShortcuts();
}
function dpUpdateShortcuts() {
    const today    = new Date();
    const yest     = new Date(); yest.setDate(today.getDate()-1);
    const tomorrow = new Date(); tomorrow.setDate(today.getDate()+1);
    document.getElementById('sc-yesterday').classList.toggle('active', samDay(dpPickedDate, yest));
    document.getElementById('sc-today').classList.toggle('active',     samDay(dpPickedDate, today));
    document.getElementById('sc-tomorrow').classList.toggle('active',  samDay(dpPickedDate, tomorrow));
}
function dpConfirm() {
    const today = new Date();
    selectedDate = new Date(dpPickedDate);
    isCustomDate = !samDay(selectedDate, today);
    dpClose();
    updateDateUI();
    loadAll();
}
function dpReset() {
    selectedDate = new Date();
    isCustomDate = false;
    dpClose();
    updateDateUI();
    loadAll();
}

function updateDateUI() {
    const d = selectedDate;
    const isToday = samDay(d, new Date());
    const btn   = document.getElementById('tvDateBtn');
    const label = document.getElementById('tvDateBtnLabel');
    const badge = document.getElementById('tvDateBadge');
    if (isToday) {
        label.textContent = 'Hari Ini';
        badge.style.display = 'none';
        btn.classList.remove('is-custom');
    } else {
        label.textContent = fmtDisp(d);
        badge.style.display = '';
        btn.classList.add('is-custom');
    }
    // Banner tiap slide
    [
        ['zCustomBanner','zCustomBannerDate'],
        ['rCustomBanner','rCustomBannerDate'],
        ['kCustomBanner','kCustomBannerDate'],
    ].forEach(([bid, did]) => {
        document.getElementById(bid).style.display = isCustomDate ? '' : 'none';
        document.getElementById(did).textContent   = fmtFull(d);
    });
    // Tag tanggal di header
    const dShort = fmtShort(d);
    document.getElementById('zDateTag').textContent = dShort;
    document.getElementById('rDateTag').textContent = dShort;
    document.getElementById('kDateTag').textContent = dShort;
}

/* ════════════════════════════════════════
   CLOCK
════════════════════════════════════════ */
function tickClock(){
    const n = new Date();
    document.getElementById('clock').textContent     = zpad(n.getHours())+':'+zpad(n.getMinutes());
    document.getElementById('clockDate').textContent = fmtFull(n);
}
setInterval(tickClock, 1000); tickClock();

let _lastDateStr = toStr(new Date());
setInterval(() => {
    const t = toStr(new Date());
    if (t !== _lastDateStr) {
        _lastDateStr = t;
        if (!isCustomDate) { selectedDate = new Date(); updateDateUI(); loadAll(); }
    }
}, 30_000);

/* ════════════════════════════════════════
   CAROUSEL — 3 SLIDE
════════════════════════════════════════ */
let cur = 0;
const TOTAL = 3, AUTO_SEC = 60;
let cd = AUTO_SEC, timer = null;

function updateDots(){
    ['dotZ','dotR','dotK'].forEach(id => {
        document.getElementById(id)?.querySelectorAll('.car-dot').forEach((d,i)=>{
            d.classList.toggle('active', i===cur);
        });
    });
}

function goTo(n, manual=false){
    const prev = cur;
    cur = ((n % TOTAL) + TOTAL) % TOTAL;
    const sp = document.getElementById('slide'+prev);
    const sc = document.getElementById('slide'+cur);
    sp.classList.remove('active'); sp.classList.add('out');
    setTimeout(()=>sp.classList.remove('out'), 600);
    sc.classList.add('active');
    [0,1,2].forEach(i => document.getElementById('tab'+i).classList.toggle('active', i===cur));
    updateDots();
    resetTimer();
}

function resetTimer(){
    clearInterval(timer);
    cd = AUTO_SEC;
    document.getElementById('progressFill').style.width = '0%';
    document.getElementById('cdLabel').textContent = cd;
    timer = setInterval(()=>{
        cd--;
        document.getElementById('cdLabel').textContent = cd;
        document.getElementById('progressFill').style.width = ((AUTO_SEC-cd)/AUTO_SEC*100)+'%';
        if(cd<=0) goTo(cur+1);
    }, 1000);
}
resetTimer();

let tsx=0;
document.addEventListener('touchstart', e=>{ tsx=e.touches[0].clientX; },{passive:true});
document.addEventListener('touchend',   e=>{
    const dx=e.changedTouches[0].clientX-tsx;
    if(Math.abs(dx)>50) goTo(cur+(dx<0?1:-1),true);
},{passive:true});
document.addEventListener('keydown', e=>{
    if(e.key==='ArrowRight'||e.key==='ArrowDown') goTo(cur+1,true);
    if(e.key==='ArrowLeft' ||e.key==='ArrowUp')   goTo(cur-1,true);
    if(e.key==='d'||e.key==='D') dpOpen();
    if(e.key==='Escape') dpClose();
});

/* ════════════════════════════════════════
   STATUS LOGIC
════════════════════════════════════════ */
function zGetStatus(r, dateStr){
    const now=new Date(), jam=r.jam||'', dur=parseFloat(r.durasi)||0;
    if(!jam) return 'jadwal';
    const tM=r.tanggal||dateStr, tS=r.tanggal_selesai||tM;
    if(dateStr<tM||dateStr>tS) return 'jadwal';
    const[h,m]=jam.split(':').map(Number);
    const start=new Date(dateStr+'T'+zpad(h)+':'+zpad(m||0)+':00').getTime();
    const end=start+dur*3600000, nowMs=now.getTime();
    if(nowMs>end) return 'selesai';
    if(nowMs>=start) return 'berlangsung';
    if((start-nowMs)<=3600000) return 'segera';
    return 'jadwal';
}

function rGetStatus(r){
    const now=new Date(), st=(r.status||'').toLowerCase();
    if(st==='ditolak'||st==='dibatalkan') return 'jadwal';
    if(!r.jam_mulai||!r.jam_selesai) return st==='menunggu' ? 'belum' : 'jadwal';
    const[hS,mS]=r.jam_mulai.split(':').map(Number);
    const[hE,mE]=r.jam_selesai.split(':').map(Number);
    const start=new Date(r.tanggal+'T'+zpad(hS)+':'+zpad(mS||0)+':00').getTime();
    const end=new Date(r.tanggal+'T'+zpad(hE)+':'+zpad(mE||0)+':00').getTime();
    const nowMs=now.getTime();
    if(nowMs>end) return 'selesai';
    if(nowMs>=start) return 'berlangsung';
    if((start-nowMs)<=3600000) return 'segera';
    return st==='menunggu' ? 'belum' : 'jadwal';
}

function kGetStatus(r, dateStr){
    const st = (r.status||'').toLowerCase();
    if(st==='selesai') return 'selesai';
    if(st==='ditolak'||st==='dibatalkan') return 'jadwal';
    // Cek apakah hari ini termasuk rentang pinjam
    if(dateStr >= r.tanggal_pinjam && dateStr <= r.tanggal_kembali){
        if(st==='disetujui') return 'berlangsung';
        if(st==='menunggu')  return 'belum';
    }
    return 'jadwal';
}

const BADGE={
    berlangsung:`<span class="zbadge zb-berlangsung"><span class="zdot"></span>🔴 Berlangsung</span>`,
    segera:     `<span class="zbadge zb-segera">⏰ Segera</span>`,
    selesai:    `<span class="zbadge zb-selesai">✅ Selesai</span>`,
    jadwal:     `<span class="zbadge zb-jadwal">🔵 Terjadwal</span>`,
    belum:      `<span class="zbadge zb-belum">⏳ Menunggu</span>`,
};

/* ════════════════════════════════════════
   RENDER ZOOM
════════════════════════════════════════ */
function renderZoom(rows){
    const dateStr = toStr(selectedDate);
    const active  = rows.filter(r=>r.status!=='Dibatalkan');
    let cB=0,cS=0,cSe=0;
    active.forEach(r=>{const s=zGetStatus(r,dateStr);if(s==='berlangsung')cB++;if(s==='segera')cS++;if(s==='selesai')cSe++;});
    document.getElementById('zSt0').textContent=cB;
    document.getElementById('zSt1').textContent=cS;
    document.getElementById('zSt2').textContent=cSe;
    document.getElementById('zFooter').textContent=active.length+' meeting · '+fmtFull(selectedDate);

    const tbody=document.getElementById('zTbody');
    if(!active.length){
        tbody.innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">📭</div><p>Tidak ada jadwal Zoom pada tanggal ini</p></div></td></tr>`;
        return;
    }
    tbody.innerHTML='';
    active.forEach((r,i)=>{
        const st=zGetStatus(r,dateStr);
        let timeStr='—',durStr='—';
        if(r.jam){
            const[h,m]=r.jam.split(':').map(Number);
            const dur=parseFloat(r.durasi)||0;
            const endMin=h*60+(m||0)+dur*60;
            const eH=Math.floor(endMin/60)%24,eM=Math.round(endMin%60);
            timeStr=zpad(h)+':'+zpad(m||0)+'–'+zpad(eH)+':'+zpad(eM);
            durStr=dur>=1?Math.floor(dur)+'j'+(dur%1?Math.round(dur%1*60)+'m':''):Math.round(dur*60)+'m';
        }
        const topik=esc(r.detail_permintaan||r.jenis_kerusakan?.split('\n')[0]||'Zoom Meeting');
        const host=esc(r.pelapor||'—');
        const peserta=r.jumlah_peserta?r.jumlah_peserta+' org':'—';
        const tr=document.createElement('tr');
        if(st==='berlangsung') tr.classList.add('row-live');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td><div class="tc">
                <div class="tc-ico" style="background:#eff6ff">📹</div>
                <div style="min-width:0;">
                    <div class="tc-name" title="${topik}">${topik}</div>
                    <div class="tc-host">${host}</div>
                </div>
            </div></td>
            <td class="td-center"><span class="tchip" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">🕐 ${timeStr}</span></td>
            <td class="td-center"><span class="tdur">${durStr}</span></td>
            <td class="td-center"><span class="tdur">👥 ${esc(peserta)}</span></td>
            <td class="td-center">${BADGE[st]||BADGE.jadwal}</td>
        `;
        tbody.appendChild(tr);
    });
}

/* ════════════════════════════════════════
   RENDER RUANG RAPAT
════════════════════════════════════════ */
function renderRoom(rows){
    const filtered=rows.filter(r=>!['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));
    let cB=0,cS=0,cSe=0;
    filtered.forEach(r=>{const s=rGetStatus(r);if(s==='berlangsung')cB++;if(s==='segera')cS++;if(s==='selesai')cSe++;});
    document.getElementById('rSt0').textContent=cB;
    document.getElementById('rSt1').textContent=cS;
    document.getElementById('rSt2').textContent=cSe;
    document.getElementById('rFooter').textContent=filtered.length+' peminjaman · '+fmtFull(selectedDate);

    const tbody=document.getElementById('rTbody');
    if(!filtered.length){
        tbody.innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">🏢</div><p>Tidak ada peminjaman ruang rapat pada tanggal ini</p></div></td></tr>`;
        return;
    }
    tbody.innerHTML='';
    filtered.forEach((r,i)=>{
        const st=rGetStatus(r);
        let timeStr='—';
        if(r.jam_mulai&&r.jam_selesai) timeStr=r.jam_mulai.substring(0,5)+'–'+r.jam_selesai.substring(0,5);
        const ruangan=esc(r.nama_ruangan||r.ruangan||'—');
        const keperluan=esc(r.keperluan||'—');
        const peminjam=esc(r.nama_peminjam||'—');
        const unit=esc(r.unit_kerja||'');
        const peserta=r.jumlah_peserta?r.jumlah_peserta+' org':'—';
        const tr=document.createElement('tr');
        if(st==='berlangsung') tr.classList.add('row-live');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td><span class="ruangan-chip" title="${ruangan}">🏢 ${ruangan}</span></td>
            <td><div style="min-width:0;">
                <div style="font-weight:700;color:#1e293b;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${keperluan}">${keperluan}</div>
                <div class="tc-host">${peminjam}${unit?' · '+unit:''}</div>
            </div></td>
            <td class="td-center"><span class="tchip tchip-green">🕐 ${timeStr}</span></td>
            <td class="td-center"><span class="tdur">👥 ${esc(peserta)}</span></td>
            <td class="td-center">${BADGE[st]||BADGE.jadwal}</td>
        `;
        tbody.appendChild(tr);
    });
}

/* ════════════════════════════════════════
   RENDER KENDARAAN DINAS
════════════════════════════════════════ */
function renderKendaraan(rows){
    const dateStr = toStr(selectedDate);
    const filtered = rows.filter(r=>!['ditolak','dibatalkan'].includes((r.status||'').toLowerCase()));
    let cB=0,cW=0,cSe=0;
    filtered.forEach(r=>{
        const s=kGetStatus(r,dateStr);
        if(s==='berlangsung') cB++;
        else if(s==='belum')  cW++;
        else if(s==='selesai') cSe++;
    });
    document.getElementById('kSt0').textContent=cB;
    document.getElementById('kSt1').textContent=cW;
    document.getElementById('kSt2').textContent=cSe;
    document.getElementById('kFooter').textContent=filtered.length+' peminjaman · '+fmtFull(selectedDate);

    const tbody=document.getElementById('kTbody');
    if(!filtered.length){
        tbody.innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">🚗</div><p>Tidak ada peminjaman kendaraan pada tanggal ini</p></div></td></tr>`;
        return;
    }
    tbody.innerHTML='';
    filtered.forEach((r,i)=>{
        const st=kGetStatus(r,dateStr);
        const namaKendaraan=esc(r.nama_kendaraan||'—');
        const noPolisi=esc(r.nomor_polisi||'');
        const jenisKend=esc(r.jenis_kendaraan||'');
        const keperluan=esc(r.keperluan||'—');
        const tujuan=esc(r.tujuan||'');
        const peminjam=esc(r.nama_peminjam||'—');
        const unit=esc(r.unit_kerja||'');
        const pengemudi=esc(r.pengemudi||'Sendiri');

        // Format rentang tanggal
        let tglStr = r.tanggal_pinjam || '—';
        if(r.tanggal_kembali && r.tanggal_kembali !== r.tanggal_pinjam){
            tglStr += ' s/d '+r.tanggal_kembali;
        }
        // Tambahkan jam jika per_jam
        if(r.tipe_pinjam==='per_jam' && r.jam_pinjam && r.jam_kembali){
            tglStr += ` (${r.jam_pinjam.substring(0,5)}–${r.jam_kembali.substring(0,5)})`;
        }

        const tr=document.createElement('tr');
        if(st==='berlangsung') tr.classList.add('row-live');
        tr.innerHTML=`
            <td style="color:#cbd5e1;font-weight:700;font-size:10px;text-align:center">${i+1}</td>
            <td>
                <span class="kendaraan-chip" title="${namaKendaraan}">🚗 ${namaKendaraan}</span>
                <div style="font-size:10px;color:#94a3b8;margin-top:3px;">${noPolisi}${jenisKend?' · '+jenisKend:''}</div>
            </td>
            <td><div style="min-width:0;">
                <div style="font-weight:700;color:#1e293b;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${keperluan}">${keperluan}${tujuan?' — '+tujuan:''}</div>
                <div class="tc-host">${peminjam}${unit?' · '+unit:''}</div>
            </div></td>
            <td class="td-center"><span class="tchip tchip-amber" style="font-size:9px;">📅 ${esc(tglStr)}</span></td>
            <td class="td-center"><span class="tdur">🧑‍✈️ ${pengemudi}</span></td>
            <td class="td-center">${BADGE[st]||BADGE.jadwal}</td>
        `;
        tbody.appendChild(tr);
    });
}

/* ════════════════════════════════════════
   FETCH DATA
════════════════════════════════════════ */
const ZOOM_URL     = '<?= base_url("tv/zoom_ajax") ?>';
const ROOM_URL     = '<?= base_url("tv/room_ajax") ?>';
const KENDARAAN_URL= '<?= base_url("tv/kendaraan_ajax") ?>';

function loadAll(){
    const tgl = toStr(selectedDate);

    document.getElementById('zTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    document.getElementById('rTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;
    document.getElementById('kTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⏳</div><p>Memuat jadwal...</p></div></td></tr>`;

    fetch(`${ZOOM_URL}?tanggal=${tgl}`)
        .then(r=>r.json())
        .then(j=>{ if(j.status==='success') renderZoom(j.rows); })
        .catch(()=>{
            document.getElementById('zTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⚠️</div><p>Gagal memuat data</p></div></td></tr>`;
        });

    fetch(`${ROOM_URL}?tanggal=${tgl}`)
        .then(r=>r.json())
        .then(j=>{ if(j.status==='success') renderRoom(j.rows); })
        .catch(()=>{
            document.getElementById('rTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⚠️</div><p>Gagal memuat data</p></div></td></tr>`;
        });

    fetch(`${KENDARAAN_URL}?tanggal=${tgl}`)
        .then(r=>r.json())
        .then(j=>{ if(j.status==='success') renderKendaraan(j.rows); })
        .catch(()=>{
            document.getElementById('kTbody').innerHTML=`<tr><td colspan="6"><div class="zoom-empty"><div class="zi">⚠️</div><p>Gagal memuat data</p></div></td></tr>`;
        });
}

/* Auto-refresh setiap 2 menit (hanya hari ini) */
setInterval(()=>{ if (!isCustomDate) loadAll(); }, 120_000);

/* ════════════════════════════════════════
   INIT
════════════════════════════════════════ */
updateDateUI();
loadAll();
</script>
</body>
</html>