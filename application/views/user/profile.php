<div class="min-h-[calc(100vh-120px)] flex items-center justify-center
            px-6 py-24
            bg-gradient-to-b
            from-[#0B2E6B]
            via-[#0A3D8F]
            to-[#1557C0]
            text-white">

<div class="w-full max-w-xl
            bg-white/90 backdrop-blur
            border border-white/60
            rounded-2xl p-8
            shadow-[0_20px_45px_rgba(0,0,0,0.2)]">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-[#0B2E6B]">Profil Saya</h1>
        <p class="text-blue-700/80 mt-2">
            Kelola informasi akun Anda
        </p>
    </div>

    <?php if ($this->session->flashdata('error')) : ?>
    <div class="mb-6 rounded-xl
                bg-red-100 border border-red-300
                px-4 py-3 text-red-700
                flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
        </svg>
        <span>
            Password yang Anda masukkan <b>salah</b>,
            tidak bisa mengganti password.
        </span>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')) : ?>
    <div class="mb-6 rounded-xl
                bg-green-100 border border-green-300
                px-4 py-3 text-green-700
                flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 13l4 4L19 7"/>
        </svg>
        <span><?= $this->session->flashdata('success') ?></span>
    </div>
<?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="space-y-6">

        <!-- AVATAR -->
        <div class="flex flex-col items-center gap-4">
            <div class="relative group">
                <img
                    src="<?= $user['avatar']
                        ? base_url('uploads/avatar/'.$user['avatar'])
                        : 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&background=0B2E6B&color=fff' ?>"
                    class="w-24 h-24 rounded-full object-cover
                           border-4 border-white shadow-lg">

                <label
                    class="absolute inset-0 rounded-full
                           bg-black/60 opacity-0
                           group-hover:opacity-100
                           flex items-center justify-center
                           text-sm font-semibold text-white
                           cursor-pointer transition">
                    Ganti
                    <input type="file" name="avatar" class="hidden">
                </label>
            </div>

            <div class="text-center">
                <div class="text-[#0B2E6B] font-bold">
                    <?= $user['name'] ?>
                </div>
                <div class="text-xs text-orange-500 uppercase tracking-wide">
                    <?= $this->session->userdata('role') ?>
                </div>
            </div>
        </div>

        <!-- NAMA -->
        <div>
            <label class="block text-[#0B2E6B] mb-1 font-semibold">
                Nama Lengkap
            </label>
            <input type="text" name="name"
                   value="<?= $user['name'] ?>"
                   required
                   class="w-full
                          bg-white
                          border border-gray-300
                          rounded-xl px-4 py-3
                          text-gray-800
                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-[#0B2E6B] mb-1 font-semibold">
                Email
            </label>
            <input type="email" name="email"
                   value="<?= $user['email'] ?>"
                   required
                   class="w-full
                          bg-white
                          border border-gray-300
                          rounded-xl px-4 py-3
                          text-gray-800
                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- PASSWORD LAMA -->
        <div>
            <label class="block text-[#0B2E6B] mb-1 font-semibold">
                Password Lama
            </label>
            <input type="password" name="old_password"
                   placeholder="Wajib diisi jika ingin ganti password"
                   class="w-full
                          bg-white
                          border border-gray-300
                          rounded-xl px-4 py-3
                          text-gray-800
                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- PASSWORD BARU -->
        <div>
            <label class="block text-[#0B2E6B] mb-1 font-semibold">
                Password Baru
            </label>
            <input type="password" name="new_password"
                   placeholder="Kosongkan jika tidak diganti"
                   class="w-full
                          bg-white
                          border border-gray-300
                          rounded-xl px-4 py-3
                          text-gray-800
                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>

        <!-- ACTION -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                class="flex-1 py-3 rounded-xl
                       bg-orange-500 hover:bg-orange-600
                       text-white font-bold
                       shadow-lg transition">
                Simpan Perubahan
            </button>

          <button type="button"
        onclick="confirmCancel()"
        class="px-6 py-3 rounded-xl
               border border-gray-300
               text-gray-700 hover:bg-gray-100 transition">
    Batal
</button>

        </div>

    </form>
</div>
</div>

<script>
function confirmCancel() {
    Swal.fire({
        title: 'Yakin akan batal?',
        text: 'Perubahan yang belum disimpan akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316', // orange
        cancelButtonColor: '#6b7280',  // gray
        confirmButtonText: 'Ya, batal',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('user/dashboard') ?>";
        }
    });
}
</script>

<?php if ($this->session->flashdata('success')) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Profil berhasil diperbarui',
    confirmButtonColor: '#f97316', // orange
    confirmButtonText: 'OK'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "<?= base_url('user/dashboard') ?>";
    }
});
</script>
<?php endif; ?>

