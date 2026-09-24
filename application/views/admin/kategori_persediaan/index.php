<!-- PAGE TITLE -->
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-extrabold header-title tracking-tight">
            Master Kategori Barang Persediaan
        </h1>
        <p class="text-sm header-subtitle mt-1">
            Pengelolaan kategori barang persediaan (ATK & rumah tangga kantor)
        </p>
    </div>

    <button onclick="openAddModal()"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
               bg-blue-600 hover:bg-blue-700
               text-white text-sm font-semibold
               shadow-lg transition-all transform hover:scale-105 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Tambah Kategori
    </button>
</div>

<!-- TABLE CARD -->
<div class="rounded-2xl border theme-card shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="grid grid-cols-[60px_120px_1fr_1fr_160px]
                px-6 py-4 text-sm header-subtitle
                border-b border-slate-800/50 bg-slate-900/90">
        <div>No</div>
        <div>Kode</div>
        <div>Nama</div>
        <div>Deskripsi</div>
        <div class="text-right">Aksi</div>
    </div>

    <!-- BODY -->
    <div id="kategoriTable" class="divide-y divide-slate-800/50">
        <div class="px-6 py-10 text-center header-subtitle">
            Memuat data...
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div id="addModal"
     class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur
            flex items-center justify-center">

    <div class="theme-card w-full max-w-md
                rounded-2xl border
                p-6 shadow-2xl">

        <h3 class="text-lg font-semibold header-title mb-1">
            Tambah Master Kategori Barang Persediaan
        </h3>
        <p class="text-sm header-subtitle mb-5">
            Buat kategori barang persediaan baru
        </p>

        <input type="text" id="addKode"
               placeholder="Contoh: ATK-15"
               class="w-full px-4 py-3 rounded-xl mb-3
                      bg-slate-800/80 border border-slate-700/50
                      text-white focus:outline-none
                      focus:ring-2 focus:ring-blue-500 transition-all">

        <input type="text" id="addNama"
               placeholder="Contoh: Kertas Foto"
               class="w-full px-4 py-3 rounded-xl mb-3
                      bg-slate-800/80 border border-slate-700/50
                      text-white focus:outline-none
                      focus:ring-2 focus:ring-blue-500 transition-all">

        <input type="text" id="addDeskripsi"
               placeholder="Deskripsi (opsional)"
               class="w-full px-4 py-3 rounded-xl
                      bg-slate-800/80 border border-slate-700/50
                      text-white focus:outline-none
                      focus:ring-2 focus:ring-blue-500 transition-all">

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeAddModal()"
                class="px-4 py-2 header-subtitle hover:text-white transition-colors">
                Batal
            </button>
            <button onclick="storeKategori()"
                class="px-5 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold transition-all transform hover:scale-105 active:scale-95">
                Simpan
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const baseUrl = "<?= base_url('kategori_persediaan/') ?>";

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

/* MODAL */
function showModal(el){ el.classList.remove('hidden'); el.classList.add('flex'); }
function hideModal(el){ el.classList.add('hidden'); el.classList.remove('flex'); }
function openAddModal(){ showModal(document.getElementById('addModal')); }
function closeAddModal(){ hideModal(document.getElementById('addModal')); }

function escHtml(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* LOAD */
function loadKategori(){
    fetch(baseUrl+'fetch')
    .then(r=>r.json())
    .then(data=>{
        let html='';
        if(!data.length){
            html = `<div class="px-6 py-10 text-center header-subtitle">
                        Belum ada kategori
                    </div>`;
        } else {
            data.forEach((k,i)=>{
                html += `
                <div class="grid grid-cols-[60px_120px_1fr_1fr_160px]
                            px-6 py-4 items-center
                            hover:bg-slate-800/50 transition-colors">

                    <div class="header-subtitle">${i+1}</div>

                    <div>
                        <span class="inline-flex px-3 py-1 rounded-full
                                     text-xs font-medium
                                     bg-indigo-500/15 text-indigo-400
                                     border border-indigo-500/25">
                            ${k.kode}
                        </span>
                    </div>

                    <div class="font-medium header-title">
                        ${k.nama}
                    </div>

                    <div class="header-subtitle text-sm">
                        ${escHtml(k.deskripsi) || '-'}
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="<?= base_url('kategori_persediaan/edit/') ?>${k.id}"
                            class="w-9 h-9 flex items-center justify-center
                                   rounded-lg bg-yellow-500/15
                                   text-yellow-400 hover:bg-yellow-500/25 transition-all transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </a>

                        <button onclick="hapusKategori(${k.id})"
                            class="w-9 h-9 flex items-center justify-center
                                   rounded-lg bg-red-500/15
                                   text-red-400 hover:bg-red-500/25 transition-all transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </div>`;
            });
        }
        kategoriTable.innerHTML = html;
    });
}

/* CRUD */
function storeKategori(){
    if(!addKode.value.trim() || !addNama.value.trim()) {
        const colors = getSwalColors();
        return Swal.fire({
            title: 'Oops',
            text: 'Kode dan nama wajib diisi',
            icon: 'warning',
            ...colors
        });
    }

    fetch(baseUrl+'store',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'kode='+encodeURIComponent(addKode.value)
            +'&nama='+encodeURIComponent(addNama.value)
            +'&deskripsi='+encodeURIComponent(addDeskripsi.value)
    }).then(r=>r.json()).then(r=>{
        const colors = getSwalColors();
        if(r.status==='success'){
            closeAddModal(); addKode.value=''; addNama.value=''; addDeskripsi.value='';
            loadKategori();
        }
        Swal.fire({
            title: r.status==='success'?'Berhasil':'Gagal',
            text: r.message,
            icon: r.status,
            ...colors
        });
    });
}

function hapusKategori(id){
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus kategori?',
        text: 'Data tidak bisa dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Hapus!',
        ...colors
    }).then(res=>{
        if(res.isConfirmed){
            fetch(baseUrl+'delete/'+id)
            .then(r=>r.json())
            .then(r=>{
                loadKategori();
                const successColors = getSwalColors();
                Swal.fire({
                    title: r.status==='success'?'Terhapus':'Gagal',
                    text: r.message,
                    icon: r.status,
                    ...successColors
                });
            });
        }
    });
}

document.addEventListener('DOMContentLoaded',loadKategori);
</script>

<style>
html.light input {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .hover\:bg-slate-800\/50:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light #addModal {
    background: rgba(0, 0, 0, 0.4) !important;
}

.swal2-popup {
    background-color: var(--bg-card) !important;
    border: 2px solid var(--border-color) !important;
    color: var(--text-primary) !important;
}

html.light .swal2-popup {
    background-color: #ffffff !important;
    border-color: rgba(226, 232, 240, 0.8) !important;
}

.swal2-title {
    color: var(--text-primary) !important;
}

.swal2-html-container {
    color: var(--text-secondary) !important;
}
</style>
