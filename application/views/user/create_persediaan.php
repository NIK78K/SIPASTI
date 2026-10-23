<div class="max-w-5xl mx-auto mt-12 mb-24 space-y-8 px-4">

    <!-- ================= HEADER ================= -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Barang Persediaan</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">
            Layanan Barang Persediaan
        </h1>
        <p class="text-base text-slate-600 max-w-2xl mx-auto">
            Minta barang yang sudah ada di katalog, atau ajukan barang baru yang belum tersedia
        </p>
    </div>

    <!-- ================= INFO CARDS ================= -->
    <div class="grid md:grid-cols-3 gap-5">
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 border border-emerald-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">🛒</div>
            <div class="text-emerald-700 font-bold mb-2">Permintaan</div>
            <p class="text-sm text-emerald-600/90 leading-relaxed">
                Pilih barang yang sudah ada di daftar/katalog, masukkan ke keranjang, lalu buat permintaan
            </p>
        </div>
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 border border-amber-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">📦</div>
            <div class="text-amber-700 font-bold mb-2">Pengajuan</div>
            <p class="text-sm text-amber-600/90 leading-relaxed">
                Ajukan barang baru yang belum ada di daftar/katalog agar dapat diverifikasi dan disediakan
            </p>
        </div>
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">📊</div>
            <div class="text-blue-700 font-bold mb-2">Terpantau</div>
            <p class="text-sm text-blue-600/90 leading-relaxed">
                Setiap permintaan dan pengajuan tercatat dan dapat dipantau melalui riwayat tiket Anda
            </p>
        </div>
    </div>

    <!-- ================= PILIH JENIS LAYANAN ================= -->
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-gray-100 space-y-5">
        <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
            Pilih Jenis Layanan Barang Persediaan
        </label>
        <div class="grid md:grid-cols-2 gap-5">
            <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>"
               class="group relative rounded-2xl p-6 bg-gradient-to-br from-emerald-50 to-white border-2 border-emerald-200 hover:border-emerald-500 hover:shadow-lg transition-all flex flex-col">
                <div class="text-4xl mb-3">🛒</div>
                <p class="font-bold text-emerald-800 text-lg">Permintaan Barang</p>
                <p class="text-sm text-slate-600 leading-relaxed mt-1 mb-4">
                    Barang <strong>sudah ada</strong> di daftar/katalog. Pilih, masukkan keranjang, lalu buat permintaan.
                </p>
                <span class="mt-auto inline-flex items-center gap-2 font-bold text-emerald-700 group-hover:gap-3 transition-all">
                    Buka daftar barang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
            <a href="<?= base_url('user_ticket/pengajuan_persediaan') ?>"
               class="group relative rounded-2xl p-6 bg-gradient-to-br from-amber-50 to-white border-2 border-amber-200 hover:border-amber-500 hover:shadow-lg transition-all flex flex-col">
                <div class="text-4xl mb-3">📦</div>
                <p class="font-bold text-amber-800 text-lg">Pengajuan Barang</p>
                <p class="text-sm text-slate-600 leading-relaxed mt-1 mb-4">
                    Barang <strong>belum ada</strong> di daftar/katalog. Tulis dan ajukan agar diverifikasi.
                </p>
                <span class="mt-auto inline-flex items-center gap-2 font-bold text-amber-700 group-hover:gap-3 transition-all">
                    Buka form pengajuan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            </a>
        </div>
    </div>

</div>
