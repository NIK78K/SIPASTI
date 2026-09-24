<h1 class="text-2xl font-bold mb-2">QR Code Perangkat</h1>
<p class="text-gray-400 mb-4">
    QR Code ini berisi <b>kode barang | NUP</b>
</p>

<div class="max-w-md mx-auto bg-[#0e141b] border border-gray-700 rounded p-4 text-center">

    <!-- QR CODE -->
    <div id="qrcode" class="flex justify-center mb-3"></div>

    <!-- TEKS -->
    <div class="text-xs text-gray-400">
        <?= $barcode ?? '3100101007|1' ?>
    </div>

    <!-- AKSI -->
    <div class="flex gap-2 justify-center mt-4">
        <button onclick="window.print()"
                class="bg-green-600 px-4 py-2 rounded">
            Print QR
        </button>

        <a href="<?= base_url('inventory') ?>"
           class="bg-gray-600 px-4 py-2 rounded">
            Kembali
        </a>
    </div>
</div>

<!-- QR LIBRARY -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
new QRCode(document.getElementById("qrcode"), {
    text: "3100101007|1",
    width: 220,
    height: 220
});
</script>
