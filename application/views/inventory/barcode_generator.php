<h1 class="text-2xl font-bold mb-2">Generator Barcode Perangkat</h1>
<p class="text-gray-400 mb-4">
    Barcode dibuat dari <b>kode barang | NUP</b>
</p>

<div class="max-w-lg mx-auto bg-[#0e141b] border border-gray-700 rounded p-4">

    <!-- INPUT -->
    <div class="space-y-3">
        <input type="text"
               id="kode_barang"
               placeholder="Kode Barang"
               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded">

        <input type="text"
               id="nup"
               placeholder="NUP"
               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded">
    </div>

    <!-- PREVIEW -->
    <div class="mt-4 text-center">
        <svg id="barcode"></svg>
        <div id="barcodeText" class="text-xs text-gray-400 mt-1"></div>
    </div>

    <!-- AKSI -->
    <div class="flex gap-2 mt-4 justify-center">
        <button onclick="generateBarcode()"
                class="bg-blue-600 px-4 py-2 rounded">
            Generate
        </button>

        <button onclick="window.print()"
                class="bg-green-600 px-4 py-2 rounded">
            Print
        </button>
    </div>

</div>

<!-- LIBRARY BARCODE -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
function generateBarcode() {
    const kode = document.getElementById('kode_barang').value.trim();
    const nup  = document.getElementById('nup').value.trim();

    if (!kode || !nup) {
        alert('Kode Barang dan NUP wajib diisi');
        return;
    }

    const value = kode + '|' + nup;

    JsBarcode("#barcode", value, {
        format: "CODE128",
        lineColor: "#ffffff",
        background: "#0e141b",
        width: 2,
        height: 60,
        displayValue: false
    });

    document.getElementById('barcodeText').innerText = value;
}
</script>
