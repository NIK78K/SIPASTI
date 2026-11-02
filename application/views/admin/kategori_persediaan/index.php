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
<div class="rounded-2xl border theme-card shadow-xl overflow-visible relative z-20">

    <!-- TOOLBAR -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-b border-slate-800/50 relative z-30">
        <p id="kategoriCount" class="text-sm header-subtitle">
            Memuat kategori...
        </p>
        <div class="custom-dropdown-container relative z-30 w-full sm:max-w-xs" id="filterKategoriToolbarWrap">
            <input type="hidden" id="filterKategoriSearch" value="">
            
            <!-- Trigger Button -->
            <button type="button" class="dropdown-trigger w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700/50 text-white text-sm hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all flex items-center justify-between text-left">
                <span class="dropdown-label truncate text-slate-300">Semua Kategori</span>
                <svg class="dropdown-chevron w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-panel hidden absolute right-0 top-full mt-1.5 z-50 min-w-[280px] w-full max-w-[340px] rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
                <!-- Search Box (Q Search X) -->
                <div class="flex items-center bg-slate-900/80 border border-slate-700/70 rounded-xl px-3 py-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="dropdown-search w-full bg-transparent text-sm text-slate-200 placeholder-slate-500 focus:outline-none" placeholder="Search" autocomplete="off">
                    <button type="button" class="dropdown-clear hidden text-slate-400 hover:text-slate-200 p-0.5 ml-1 transition" title="Clear">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Options List -->
                <div id="katOptionsList" class="dropdown-options max-h-56 overflow-y-auto space-y-0.5 pr-1 text-sm">
                    <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between is-selected bg-blue-600/20 text-blue-300 font-semibold"
                         data-value=""
                         data-label="Semua Kategori">
                        <span class="truncate">Semua Kategori</span>
                        <svg class="opt-check w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        <div class="mb-3">
            <label for="addKode" class="block text-sm font-medium header-subtitle mb-1.5">
                Kode Kategori <span class="text-red-400">*</span>
            </label>
            <input type="text" id="addKode"
                   placeholder="Contoh: ATK-15"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Format contoh ATK-15, wajib diisi dan unik.</p>
        </div>

        <div class="mb-3">
            <label for="addNama" class="block text-sm font-medium header-subtitle mb-1.5">
                Nama Kategori <span class="text-red-400">*</span>
            </label>
            <input type="text" id="addNama"
                   placeholder="Contoh: Kertas Foto"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Nama lengkap kategori, contoh: Kertas Foto.</p>
        </div>

        <div>
            <label for="addDeskripsi" class="block text-sm font-medium header-subtitle mb-1.5">
                Deskripsi <span class="header-subtitle font-normal">(opsional)</span>
            </label>
            <input type="text" id="addDeskripsi"
                   placeholder="Contoh: Kertas foto glossy ukuran A4"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Isi singkat peruntukan kategori, boleh dikosongkan.</p>
        </div>

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

<!-- ================= MODAL EDIT ================= -->
<div id="editModal"
     class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur
            flex items-center justify-center">

    <div class="theme-card w-full max-w-md
                rounded-2xl border
                p-6 shadow-2xl">

        <h3 class="text-lg font-semibold header-title mb-1">
            Edit Kategori
        </h3>
        <p class="text-sm header-subtitle mb-5">
            Ubah data kategori lalu simpan
        </p>

        <input type="hidden" id="editId">

        <div class="mb-3">
            <label for="editKode" class="block text-sm font-medium header-subtitle mb-1.5">
                Kode Kategori <span class="text-red-400">*</span>
            </label>
            <input type="text" id="editKode"
                   placeholder="Contoh: ATK-15"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Format contoh ATK-15, wajib diisi dan unik.</p>
        </div>

        <div class="mb-3">
            <label for="editNama" class="block text-sm font-medium header-subtitle mb-1.5">
                Nama Kategori <span class="text-red-400">*</span>
            </label>
            <input type="text" id="editNama"
                   placeholder="Contoh: Kertas Foto"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Nama lengkap kategori, contoh: Kertas Foto.</p>
        </div>

        <div>
            <label for="editDeskripsi" class="block text-sm font-medium header-subtitle mb-1.5">
                Deskripsi <span class="header-subtitle font-normal">(opsional)</span>
            </label>
            <input type="text" id="editDeskripsi"
                   placeholder="Contoh: Kertas foto glossy ukuran A4"
                   class="w-full px-4 py-3 rounded-xl mb-1
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs header-subtitle">Isi singkat peruntukan kategori, boleh dikosongkan.</p>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button onclick="closeEditModal()"
                class="px-4 py-2 header-subtitle hover:text-white transition-colors">
                Batal
            </button>
            <button onclick="updateKategori()"
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
function openEditModal(){ showModal(document.getElementById('editModal')); }
function closeEditModal(){ hideModal(document.getElementById('editModal')); }

function escHtml(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* LOAD */
function loadKategori(){
    fetch(baseUrl+'fetch')
    .then(r=>r.json())
    .then(data=>{
        let countEl = document.getElementById('kategoriCount');
        if (countEl) countEl.textContent = (data.length || 0) + ' kategori terdaftar';

        let html='';
        if(!data.length){
            html = `<div class="px-6 py-10 text-center header-subtitle">
                        Belum ada kategori
                    </div>`;
        } else {
            data.forEach((k,i)=>{
                html += `
                <div class="kategori-row grid grid-cols-[60px_120px_1fr_1fr_160px]
                            px-6 py-4 items-center
                            hover:bg-slate-800/50 transition-colors"
                     data-search="${escHtml((k.kode + ' ' + k.nama + ' ' + (k.deskripsi || '')).toLowerCase())}">

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
                        <button onclick="editKategori(${k.id})"
                            class="w-9 h-9 flex items-center justify-center
                                   rounded-lg bg-yellow-500/15
                                   text-yellow-400 hover:bg-yellow-500/25 transition-all transform hover:scale-110"
                            title="Edit kategori">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                       d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </button>

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
            html += `<div id="kategoriEmptyRow" class="hidden px-6 py-10 text-center header-subtitle">Tidak ada kategori yang cocok</div>`;
        }
        kategoriTable.innerHTML = html;

        // Populate options in searchable dropdown
        let optHtml = `<div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between is-selected bg-blue-600/20 text-blue-300 font-semibold" data-value="" data-label="Semua Kategori"><span class="truncate">Semua Kategori</span><svg class="opt-check w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>`;
        data.forEach(k => {
            optHtml += `<div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between" data-value="${(k.kode + ' ' + k.nama).toLowerCase()}" data-label="${escHtml(k.kode + ' - ' + k.nama)}"><span class="truncate">${escHtml(k.kode + ' - ' + k.nama)}</span><svg class="opt-check hidden w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>`;
        });
        optHtml += `<div class="dropdown-empty hidden py-4 text-center text-xs text-slate-400 font-medium">Kategori tidak ditemukan</div>`;
        const optList = document.getElementById('katOptionsList');
        if (optList) {
            optList.innerHTML = optHtml;
            initKategoriToolbarDropdown();
        }
    });
}

function filterKategoriTable(q) {
    q = (q || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#kategoriTable .kategori-row');
    let shown = 0;
    rows.forEach(r => {
        const text = (r.getAttribute('data-search') || '').toLowerCase();
        if (!q || text.indexOf(q) !== -1) {
            r.classList.remove('hidden');
            shown++;
        } else {
            r.classList.add('hidden');
        }
    });
    const emptyRow = document.getElementById('kategoriEmptyRow');
    if (emptyRow) {
        emptyRow.classList.toggle('hidden', shown > 0 || rows.length === 0);
    }
}

function initKategoriToolbarDropdown() {
    var container = document.getElementById('filterKategoriToolbarWrap');
    if (!container) return;

    var hiddenInput = document.getElementById('filterKategoriSearch');
    var trigger = container.querySelector('.dropdown-trigger');
    var label = container.querySelector('.dropdown-label');
    var chevron = container.querySelector('.dropdown-chevron');
    var panel = container.querySelector('.dropdown-panel');
    var search = container.querySelector('.dropdown-search');
    var clearBtn = container.querySelector('.dropdown-clear');
    var options = container.querySelectorAll('.dropdown-opt');
    var emptyMsg = container.querySelector('.dropdown-empty');

    function closeDropdown() {
        panel.classList.add('hidden');
        chevron.classList.remove('rotate-180');
        container.style.zIndex = '';
    }

    function openDropdown() {
        container.style.zIndex = '60';
        panel.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        setTimeout(function() { search.focus(); }, 50);
    }

    trigger.onclick = function(e) {
        e.stopPropagation();
        if (panel.classList.contains('hidden')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    };

    function filterOptions(q) {
        q = (q || '').trim().toLowerCase();
        var matchCount = 0;
        options.forEach(function(opt) {
            var text = (opt.getAttribute('data-label') || '').toLowerCase();
            if (!q || text.indexOf(q) !== -1) {
                opt.classList.remove('hidden');
                matchCount++;
            } else {
                opt.classList.add('hidden');
            }
        });
        if (matchCount === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }

    search.oninput = function() {
        var val = this.value;
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        filterOptions(val);
    };

    search.onclick = function(e) { e.stopPropagation(); };

    clearBtn.onclick = function(e) {
        e.stopPropagation();
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        search.focus();
    };

    options.forEach(function(opt) {
        opt.onclick = function(e) {
            e.stopPropagation();
            var val = opt.getAttribute('data-value');
            var txt = opt.getAttribute('data-label');

            hiddenInput.value = val;
            label.textContent = txt;
            if (val === '') {
                label.className = 'dropdown-label truncate text-slate-300';
            } else {
                label.className = 'dropdown-label truncate text-white font-medium';
            }

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.add('hidden');
                }
            });

            closeDropdown();
            filterKategoriTable(val);
        };
    });

    document.onclick = function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    };
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

function editKategori(id){
    fetch(baseUrl+'get/'+id)
    .then(r=>r.json())
    .then(d=>{
        const colors = getSwalColors();
        if(d.status==='error' || !d.id){
            return Swal.fire({
                title: 'Gagal',
                text: d.message || 'Data kategori tidak ditemukan',
                icon: 'error',
                ...colors
            });
        }
        editId.value = d.id;
        editKode.value = d.kode || '';
        editNama.value = d.nama || '';
        editDeskripsi.value = d.deskripsi || '';
        openEditModal();
    })
    .catch(()=>{
        const colors = getSwalColors();
        Swal.fire({
            title: 'Gagal',
            text: 'Tidak dapat memuat data kategori',
            icon: 'error',
            ...colors
        });
    });
}

function updateKategori(){
    if(!editKode.value.trim() || !editNama.value.trim()) {
        const colors = getSwalColors();
        return Swal.fire({
            title: 'Oops',
            text: 'Kode dan nama wajib diisi',
            icon: 'warning',
            ...colors
        });
    }

    fetch(baseUrl+'update/'+editId.value,{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'kode='+encodeURIComponent(editKode.value)
            +'&nama='+encodeURIComponent(editNama.value)
            +'&deskripsi='+encodeURIComponent(editDeskripsi.value)
    }).then(r=>r.json()).then(r=>{
        const colors = getSwalColors();
        if(r.status==='success'){
            closeEditModal();
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

document.getElementById('addModal').addEventListener('click', function(e){
    if(e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e){
    if(e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){ closeAddModal(); closeEditModal(); }
});
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

html.light #addModal,
html.light #editModal {
    background: rgba(0, 0, 0, 0.4) !important;
}

html.light input::placeholder { color: #94a3b8 !important; }

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

.dropdown-options::-webkit-scrollbar {
    width: 6px;
}
.dropdown-options::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.4);
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
html.light #filterKategoriToolbarWrap .dropdown-panel {
    background-color: #ffffff !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
html.light #filterKategoriToolbarWrap .dropdown-panel .bg-slate-900\/80 {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
html.light #filterKategoriToolbarWrap .dropdown-search {
    color: #0f172a !important;
}
html.light #filterKategoriToolbarWrap .dropdown-opt {
    color: #334155 !important;
}
html.light #filterKategoriToolbarWrap .dropdown-opt:hover {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
}
html.light #filterKategoriToolbarWrap .dropdown-opt.is-selected {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
}
html.light #filterKategoriToolbarWrap .dropdown-empty {
    color: #94a3b8 !important;
}
html.light #filterKategoriToolbarWrap .dropdown-options::-webkit-scrollbar-track {
    background: #f1f5f9;
}
html.light #filterKategoriToolbarWrap .dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}
</style>
