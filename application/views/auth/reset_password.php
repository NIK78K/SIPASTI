<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-[#0B2E6B] to-[#1557C0]">

<div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-xl">

    <h1 class="text-2xl font-bold text-[#0B2E6B] text-center">
        Reset Password
    </h1>

    <p class="text-sm text-gray-600 text-center mt-2">
        Masukkan kode yang dikirim ke email Anda
    </p>

    <form method="post"
          action="<?= base_url('auth/update_password') ?>"
          class="mt-6 space-y-5">

        <!-- KODE -->
        <div>
            <label class="text-sm font-semibold">Kode Verifikasi</label>
            <input type="text" name="code" required
                   placeholder="6 digit kode"
                   class="w-full border rounded-xl px-4 py-2 mt-1">
        </div>

        <!-- PASSWORD BARU -->
        <div>
            <label class="text-sm font-semibold">Password Baru</label>
            <input type="password" name="password" required
                   placeholder="Minimal 6 karakter"
                   class="w-full border rounded-xl px-4 py-2 mt-1">
        </div>

        <!-- BUTTON -->
        <button class="w-full py-3 rounded-xl
                       bg-orange-500 hover:bg-orange-600
                       text-white font-bold transition">
            Simpan Password
        </button>
    </form>

    <!-- RESEND -->
    <div class="text-center mt-4">
        <a href="<?= base_url('auth/resend_reset_code') ?>"
           class="text-sm text-[#0B2E6B] font-semibold hover:underline">
            Kirim ulang kode
        </a>
    </div>

</div>

<!-- ===== SWEET ALERT ===== -->
<?php if ($this->session->flashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: '<?= $this->session->flashdata('error') ?>',
    confirmButtonColor: '#ef4444'
});
</script>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= $this->session->flashdata('success') ?>',
    confirmButtonColor: '#22c55e'
});
</script>
<?php endif; ?>

</body>
</html>
