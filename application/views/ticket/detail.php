<h2 class="text-xl font-bold mb-4">Detail Tiket <?= $ticket['kode_tiket'] ?></h2>

<div class="bg-[#0e141b] border border-gray-800 rounded p-4 mb-4 space-y-2">
<p><b>Pelapor:</b> <?= $ticket['pelapor'] ?></p>
<p><b>Perangkat:</b> <?= $ticket['nama_perangkat'] ?></p>
<p><b>Jenis:</b> <?= $ticket['jenis_kerusakan'] ?></p>
<p><b>Tanggal:</b> <?= $ticket['tanggal'] ?></p>
<p><b>Status:</b> <?= $ticket['status'] ?></p>
<p><b>Teknisi:</b> <?= $ticket['teknisi'] ?? '-' ?></p>
</div>

<form method="post" action="<?= base_url('ticket/update/'.$ticket['id']) ?>"
      class="bg-[#0e141b] border border-gray-800 rounded p-4 space-y-4">

<select name="status" class="w-full bg-[#0b0f14] border border-gray-700 rounded px-3 py-2">
<option <?= $ticket['status']=='Menunggu'?'selected':'' ?>>Menunggu</option>
<option <?= $ticket['status']=='On Progress'?'selected':'' ?>>On Progress</option>
<option <?= $ticket['status']=='Selesai'?'selected':'' ?>>Selesai</option>
<option <?= $ticket['status']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
</select>

<select name="teknisi_id" class="w-full bg-[#0b0f14] border border-gray-700 rounded px-3 py-2">
<option value="">-- Pilih Teknisi --</option>
<?php foreach($teknisi as $t): ?>
<option value="<?= $t['id'] ?>"><?= $t['nama'] ?></option>
<?php endforeach ?>
</select>

<button class="bg-blue-600 px-4 py-2 rounded">Update</button>
</form>
