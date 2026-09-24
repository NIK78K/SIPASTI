</main>
</div>
</body>
</html>

<script>
function openModal(id){
 fetch('<?= base_url('ticket/detail/') ?>'+id)
 .then(r=>r.text())
 .then(html=>{
   document.getElementById('modal-content').innerHTML=html;
   document.getElementById('modal').classList.remove('hidden');
   document.getElementById('modal').classList.add('flex');
 });
}
function closeModal(){
 document.getElementById('modal').classList.add('hidden');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

