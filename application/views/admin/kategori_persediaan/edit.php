<!-- HEADER -->
<div class="mb-6">
    <a href="<?= base_url('kategori_persediaan') ?>" class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Master Kategori Barang Persediaan
    </a>
    <h1 class="text-3xl font-extrabold header-title tracking-tight">Master Kategori Barang Persediaan</h1>
    <p class="text-sm header-subtitle mt-1">Ubah data kode, nama, dan deskripsi kategori</p>
</div>

<div class="rounded-2xl border theme-card shadow-xl p-6 max-w-2xl">
    <form id="formEdit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium header-subtitle mb-1.5">
                Kode <span class="text-red-400">*</span>
            </label>
            <input type="text" id="kode" name="kode" required
                   value="<?= htmlspecialchars($kategori->kode) ?>"
                   placeholder="Contoh: ATK-15"
                   class="w-full px-4 py-3 rounded-xl
                          bg-slate-800/80 border border-slate-700/50
                          text-white focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
        </div>

        <div>
            <label class="block text-sm font-medium header-subtitle mb-1.5">
                Nama <span class="text-red-400">*</span>
            </label>
            <input type="text" id="nama" name="nama" required
                   value="<?= htmlspecialchars($kategori->nama) ?>"
                   placeholder="Contoh: Kertas Foto"
                   class="w-full px-4 py-3 rounded-xl
                          bg-slate-800/80 border border-slate-700/50
                          text-white focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
        </div>

        <div>
            <label class="block text-sm font-medium header-subtitle mb-1.5">
                Deskripsi
            </label>
            <input type="text" id="deskripsi" name="deskripsi"
                   value="<?= htmlspecialchars($kategori->deskripsi ?? '') ?>"
                   placeholder="Deskripsi (opsional)"
                   class="w-full px-4 py-3 rounded-xl
                          bg-slate-800/80 border border-slate-700/50
                          text-white focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
        </div>

        <div class="flex gap-3 pt-4">
            <a href="<?= base_url('kategori_persediaan') ?>"
               class="flex-1 px-4 py-3 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-800 transition font-medium text-center">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-lg font-medium">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();

    const kode = document.getElementById('kode').value.trim();
    const nama = document.getElementById('nama').value.trim();
    const deskripsi = document.getElementById('deskripsi').value;

    if (!kode || !nama) {
        const colors = getSwalColors();
        return Swal.fire({ title: 'Oops', text: 'Kode dan nama wajib diisi', icon: 'warning', ...colors });
    }

    fetch("<?= base_url('kategori_persediaan/update/' . $kategori->id) ?>", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'kode=' + encodeURIComponent(kode)
            + '&nama=' + encodeURIComponent(nama)
            + '&deskripsi=' + encodeURIComponent(deskripsi)
    })
    .then(r => r.json())
    .then(r => {
        const colors = getSwalColors();
        if (r.status === 'success') {
            Swal.fire({ title: 'Berhasil', text: r.message, icon: 'success', timer: 1500, showConfirmButton: false, ...colors })
            .then(() => { window.location.href = "<?= base_url('kategori_persediaan') ?>"; });
        } else {
            Swal.fire({ title: 'Gagal', text: r.message, icon: 'error', ...colors });
        }
    })
    .catch(() => {
        const colors = getSwalColors();
        Swal.fire({ title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data', icon: 'error', ...colors });
    });
});
</script>

<style>
html.light input {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
</style>
