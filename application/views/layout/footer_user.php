</main>

<script>
const btn   = document.getElementById('userMenuBtn');
const menu  = document.getElementById('userMenu');
const arrow = document.getElementById('userArrow');

btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
});

document.addEventListener('click', e => {
    if (!btn.contains(e.target)) {
        menu.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
});

function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: 'Anda akan keluar dari sistem',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                "<?= base_url('auth/logout') ?>";
        }
    });
}
</script>



</body>
</html>
