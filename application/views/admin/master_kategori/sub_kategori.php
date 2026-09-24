<!-- PAGE TITLE -->
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-extrabold header-title tracking-tight">
            Sub Kategori
        </h1>
        <p class="text-sm header-subtitle mt-1">
            Pengelolaan sub kategori berdasarkan kategori utama
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
        Tambah Sub Kategori
    </button>
</div>

<!-- TABLE CARD -->
<div class="rounded-2xl border theme-card shadow-xl overflow-hidden">

    <!-- HEADER -->
    <div class="grid grid-cols-[60px_180px_1fr_200px_140px_140px]
                px-6 py-4 text-sm header-subtitle
                border-b border-slate-800/50 bg-slate-900/90">
        <div>No</div>
        <div>Kategori</div>
        <div>Nama Sub Kategori</div>
        <div>Slug</div>
        <div class="text-center">Perlu Jadwal?</div>
        <div class="text-right">Aksi</div>
    </div>

    <!-- BODY -->
    <div id="subKategoriTable" class="divide-y divide-slate-800/50">
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
            Tambah Sub Kategori
        </h3>
        <p class="text-sm header-subtitle mb-5">
            Hubungkan sub kategori ke kategori utama
        </p>

        <label class="text-sm header-subtitle">Kategori</label>
        <select id="addKategori"
            class="w-full mt-2 mb-4 px-4 py-3 rounded-xl
                   bg-slate-800/80 border border-slate-700/50
                   text-white focus:outline-none
                   focus:ring-2 focus:ring-blue-500 transition-all">
            <option value="">-- Pilih Kategori --</option>
        </select>

        <label class="text-sm header-subtitle">Nama Sub Kategori</label>
        <input type="text" id="addNama"
               class="w-full mt-2 mb-4 px-4 py-3 rounded-xl
                      bg-slate-800/80 border border-slate-700/50
                      text-white focus:outline-none
                      focus:ring-2 focus:ring-blue-500 transition-all">

        <!-- TOGGLE PERLU JADWAL -->
        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-800/80 border border-slate-700/50 mb-4">
            <div>
                <label class="text-sm font-medium header-title">Memerlukan Jadwal?</label>
                <p class="text-xs header-subtitle mt-0.5">Aktifkan jika layanan ini butuh penjadwalan</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="addPerluJadwal" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 
                            peer-focus:ring-blue-800 rounded-full peer 
                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                            after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                </div>
            </label>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeAddModal()"
                class="px-4 py-2 header-subtitle hover:text-white transition-colors">
                Batal
            </button>
            <button onclick="storeSubKategori()"
                class="px-5 py-2 rounded-lg
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold transition-all transform hover:scale-105 active:scale-95">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div id="editModal"
     class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur
            flex items-center justify-center">

    <div class="theme-card w-full max-w-md
                rounded-2xl border
                p-6 shadow-2xl">

        <h3 class="text-lg font-semibold header-title mb-1">
            Edit Sub Kategori
        </h3>
        <p class="text-sm header-subtitle mb-5">
            Perbarui data sub kategori
        </p>

        <input type="hidden" id="editId">

        <label class="text-sm header-subtitle">Kategori</label>
        <select id="editKategori"
            class="w-full mt-2 mb-4 px-4 py-3 rounded-xl
                   bg-slate-800/80 border border-slate-700/50
                   text-white focus:outline-none
                   focus:ring-2 focus:ring-yellow-500 transition-all">
            <option value="">-- Pilih Kategori --</option>
        </select>

        <label class="text-sm header-subtitle">Nama Sub Kategori</label>
        <input type="text" id="editNama"
               class="w-full mt-2 mb-4 px-4 py-3 rounded-xl
                      bg-slate-800/80 border border-slate-700/50
                      text-white focus:outline-none
                      focus:ring-2 focus:ring-yellow-500 transition-all">

        <!-- TOGGLE PERLU JADWAL -->
        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-800/80 border border-slate-700/50 mb-4">
            <div>
                <label class="text-sm font-medium header-title">Memerlukan Jadwal?</label>
                <p class="text-xs header-subtitle mt-0.5">Aktifkan jika layanan ini butuh penjadwalan</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="editPerluJadwal" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 
                            peer-focus:ring-yellow-800 rounded-full peer 
                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                            after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500">
                </div>
            </label>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeEditModal()"
                class="px-4 py-2 header-subtitle hover:text-white transition-colors">
                Batal
            </button>
            <button onclick="updateSubKategori()"
                class="px-5 py-2 rounded-lg
                       bg-yellow-500 hover:bg-yellow-600
                       text-black font-semibold transition-all transform hover:scale-105 active:scale-95">
                Update
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const baseUrl = "<?= base_url('sub_kategori/') ?>"; 

// Helper untuk mendapatkan warna SweetAlert berdasarkan theme
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

/* LOAD MASTER KATEGORI */
function loadMasterKategori(selectId){
    fetch("<?= base_url('master_kategori/fetch') ?>")
    .then(r=>r.json())
    .then(data=>{
        let opt = `<option value="">-- Pilih Kategori --</option>`;
        data.forEach(k=>{
            opt += `<option value="${k.id}">${k.nama_kategori}</option>`;
        });
        document.getElementById(selectId).innerHTML = opt;
    });
}

/* LOAD DATA */
function loadSubKategori(){
    fetch(baseUrl+'fetch')
    .then(r=>r.json())
    .then(data=>{
        let html='';
        if(!data.length){
            html=`<div class="px-6 py-10 text-center header-subtitle">
                    Belum ada sub kategori
                  </div>`;
        } else {
            data.forEach((s,i)=>{
                const badgeJadwal = s.perlu_jadwal == 1 
                    ? `<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-green-500/15 text-green-400 border border-green-500/25">
                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                         </svg>
                         Ya
                       </span>`
                    : `<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-500/15 text-gray-400 border border-gray-500/25">
                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                         </svg>
                         Tidak
                       </span>`;

                html+=`
                <div class="grid grid-cols-[60px_180px_1fr_200px_140px_140px]
                            px-6 py-4 items-center hover:bg-slate-800/50 transition-colors">

                    <div class="header-subtitle">${i+1}</div>

                    <div class="text-gray-300">${s.nama_kategori}</div>

                    <div class="font-medium header-title">${s.nama_sub_kategori}</div>

                    <div>
                        <span class="inline-flex px-3 py-1 rounded-full
                                     text-xs font-medium
                                     bg-indigo-500/15 text-indigo-400
                                     border border-indigo-500/25">
                            ${s.slug}
                        </span>
                    </div>

                    <div class="flex justify-center">
                        ${badgeJadwal}
                    </div>

                    <div class="flex justify-end gap-2">
                        <button onclick='openEditModal(${JSON.stringify(s)})'
                            class="w-9 h-9 flex items-center justify-center
                                   rounded-lg bg-yellow-500/15
                                   text-yellow-400 hover:bg-yellow-500/25 transition-all transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </button>

                        <button onclick="hapusSubKategori(${s.id})"
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
        subKategoriTable.innerHTML = html;
    });
}

/* MODAL */
function openAddModal(){
    loadMasterKategori('addKategori');
    document.getElementById('addNama').value = '';
    document.getElementById('addPerluJadwal').checked = false;
    addModal.classList.remove('hidden');
}
function closeAddModal(){ addModal.classList.add('hidden'); }

function openEditModal(s){
    editId.value = s.id;
    editNama.value = s.nama_sub_kategori;
    document.getElementById('editPerluJadwal').checked = s.perlu_jadwal == 1;
    
    loadMasterKategori('editKategori');
    setTimeout(() => editKategori.value = s.kategori_id, 200);
    editModal.classList.remove('hidden');
}
function closeEditModal(){ editModal.classList.add('hidden'); }

/* CRUD */
function storeSubKategori(){
    const kategoriId = document.getElementById('addKategori').value;
    const nama = document.getElementById('addNama').value.trim();
    const perluJadwal = document.getElementById('addPerluJadwal').checked ? 1 : 0;

    if(!kategoriId || !nama) {
        const colors = getSwalColors();
        return Swal.fire({
            title: 'Oops',
            text: 'Lengkapi semua field',
            icon: 'warning',
            ...colors
        });
    }

    fetch(baseUrl+'store',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`kategori_id=${kategoriId}&nama_sub_kategori=${encodeURIComponent(nama)}&perlu_jadwal=${perluJadwal}`
    }).then(r=>r.json()).then(r=>{
        closeAddModal();
        loadSubKategori();
        const colors = getSwalColors();
        Swal.fire({
            title: 'Berhasil',
            text: r.message,
            icon: 'success',
            ...colors
        });
    });
}

function updateSubKategori(){
    const kategoriId = document.getElementById('editKategori').value;
    const nama = document.getElementById('editNama').value.trim();
    const perluJadwal = document.getElementById('editPerluJadwal').checked ? 1 : 0;
    const id = document.getElementById('editId').value;

    if(!kategoriId || !nama) {
        const colors = getSwalColors();
        return Swal.fire({
            title: 'Oops',
            text: 'Lengkapi semua field',
            icon: 'warning',
            ...colors
        });
    }

    fetch(baseUrl+'update/'+id,{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`kategori_id=${kategoriId}&nama_sub_kategori=${encodeURIComponent(nama)}&perlu_jadwal=${perluJadwal}`
    }).then(r=>r.json()).then(r=>{
        closeEditModal();
        loadSubKategori();
        const colors = getSwalColors();
        Swal.fire({
            title: 'Berhasil',
            text: r.message,
            icon: 'success',
            ...colors
        });
    });
}

function hapusSubKategori(id){
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus sub kategori?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        ...colors
    }).then(res=>{
        if(res.isConfirmed){
            fetch(baseUrl+'delete/'+id)
            .then(r=>r.json())
            .then(r=>{
                loadSubKategori();
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

document.addEventListener('DOMContentLoaded',loadSubKategori);
</script>

<style>
/* Light mode specific adjustments */
html.light input,
html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .hover\:bg-slate-800\/50:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

/* Modal backdrop */
html.light #addModal,
html.light #editModal {
    background: rgba(0, 0, 0, 0.4) !important;
}

/* SweetAlert2 customization */
.swal2-popup {
    background-color: var(--bg-card) !important;
    border: 2px solid var(--border-color) !important;
    color: var(--text-primary) !important;
}

html.light .swal2-popup {
    background-color: #ffffff !important;
    border-color: rgba(226, 232, 240, 0.8) !important;
}
</style>