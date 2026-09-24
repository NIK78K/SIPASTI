<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-[#0B2E6B] to-[#1557C0]">

<div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-xl">
    <h1 class="text-2xl font-bold text-[#0B2E6B] text-center">
        Lupa Password
    </h1>

    <p class="text-sm text-gray-600 mt-3 text-center">
        Masukkan email terdaftar untuk menerima kode verifikasi
    </p>

    <!-- Notifikasi Error -->
    <?php if ($this->session->flashdata('error')): ?>
    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">
        <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Notifikasi Success -->
    <?php if ($this->session->flashdata('success')): ?>
    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm">
        <?= $this->session->flashdata('success') ?>
    </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/send_reset_code') ?>" class="mt-6 space-y-5">

        <div>
            <label class="text-sm font-semibold">Email</label>
            <input type="email" name="email" required
                   class="w-full border rounded-xl px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-[#0B2E6B]">
        </div>

        <button type="submit" class="w-full py-3 rounded-xl
                       bg-orange-500 text-white font-bold hover:bg-orange-600 transition">
            Kirim Kode
        </button>

        <a href="<?= base_url('auth/login') ?>"
           class="block text-center text-sm text-gray-600 hover:underline">
           Kembali ke Login
        </a>
    </form>
</div>

</body>
</html>