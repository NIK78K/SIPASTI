<h2 class="text-lg font-bold mb-3">Detail History <?= $ticket['kode_tiket'] ?></h2>

<p><b>Pelapor:</b> <?= $ticket['pelapor'] ?></p>
<p><b>Perangkat:</b> <?= $ticket['nama_perangkat'] ?></p>
<p><b>Teknisi:</b> <?= $ticket['nama_teknisi'] ?></p>
<p><b>Status:</b> <?= $ticket['status'] ?></p>
<p><b>Rating:</b> <?= $ticket['rating'] ?> ⭐</p>
<p><b>Tanggal Selesai:</b> <?= $ticket['selesai_at'] ?></p>

<script>
function openModal(id){
 fetch('<?= base_url("history/detail/") ?>'+id)
 .then(r=>r.text())
 .then(html=>{
   document.getElementById('modal-content').innerHTML = html;
   document.getElementById('modal').classList.remove('hidden');
   document.getElementById('modal').classList.add('flex');
 });
}
function closeModal(){
 document.getElementById('modal').classList.add('hidden');
}
</script>
