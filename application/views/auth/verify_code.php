<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Verifikasi Akun | SIPASTI BPS PROV RIAU</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-[#0b0f14] via-[#0e141b] to-black 
             min-h-screen flex items-center justify-center text-white">

<div class="w-full max-w-md bg-white/5 backdrop-blur-xl
            border border-white/10 rounded-2xl p-8 shadow-2xl
            animate-[fadeIn_0.6s_ease-out]">

    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-blue-400">
            Verifikasi Akun
        </h1>
        <p class="text-gray-400 text-sm mt-2">
            Masukkan kode verifikasi yang dikirim ke email Anda
        </p>
    </div>

    <!-- Error -->
    <?php if($this->session->flashdata('error')): ?>
        <div class="bg-red-500/10 border border-red-500/40 
                    text-red-400 text-sm p-3 rounded-lg mb-4 text-center">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif ?>

    <!-- Success -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="bg-green-500/10 border border-green-500/40 
                    text-green-400 text-sm p-3 rounded-lg mb-4 text-center">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif ?>

    <!-- Form Kode -->
    <form method="post" class="space-y-4">

        <div>
            <label class="text-sm text-gray-400 text-center block">
                Kode Verifikasi (6 digit)
            </label>
            <input type="text" name="code" maxlength="6" required
                   inputmode="numeric"
                   placeholder="Contoh: 123456"
                   class="w-full text-center text-2xl tracking-widest
                          bg-[#0b0f14]/80 border border-gray-700
                          rounded-xl px-4 py-3 mt-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500
                          transition">
        </div>

        <button
            class="w-full py-3 rounded-xl font-semibold text-white
                   bg-gradient-to-r from-blue-600 to-blue-500
                   hover:from-blue-500 hover:to-blue-400
                   transition-all duration-300
                   shadow-lg hover:shadow-blue-500/30
                   active:scale-[0.97]">
            Verifikasi Akun
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-6 text-center">
        Kode berlaku selama <b>24 jam</b>.
        <br>Jika tidak menerima email, cek folder <b>Spam</b>.
    </p>

</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

</body>
</html>
