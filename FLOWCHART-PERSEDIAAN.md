# Flowchart Sistem Barang Persediaan SIPASTI

Flowchart berikut menggambarkan alur utama permintaan barang persediaan, mulai dari user memilih barang sampai stok diperbarui setelah permintaan selesai.

```plantuml
@startuml
title Flowchart Permintaan Barang Persediaan SIPASTI

start

partition "User" {
    :Membuka katalog barang;
    :Mencari atau memilih barang;
    :Menentukan jumlah barang;
    :Menambahkan barang ke keranjang;
    :Mengirim permintaan barang;
}

partition "Sistem" {
    :Memvalidasi data permintaan;
}

if (Data permintaan valid?) then (Ya)
    :Menyimpan permintaan;
    :Menetapkan status "Menunggu";
else (Tidak)
    :Menampilkan pesan kesalahan;
    stop
endif

partition "Admin" {
    :Melihat daftar permintaan;
    :Menugaskan teknisi;
}

partition "Teknisi" {
    :Mengambil atau membuka permintaan;
    :Memeriksa ketersediaan stok;
}

if (Stok tersedia?) then (Ya)
    :Menentukan jumlah yang disetujui;
    :Memproses permintaan;
else (Tidak)
    :Menyesuaikan jumlah atau\nmenolak permintaan;
    :Memperbarui status permintaan;
    stop
endif

partition "Teknisi" {
    :Menyerahkan barang kepada user;
    :Menyelesaikan permintaan;
}

partition "Sistem" {
    :Mengubah status menjadi "Selesai";
    :Mencatat transaksi barang keluar;
    :Mengurangi saldo stok;
    :Menyimpan waktu penyelesaian;
}

partition "User" {
    :Melihat riwayat dan status permintaan;
}

stop
@enduml
```

## Ringkasan alur

1. User memilih barang dan jumlah yang dibutuhkan.
2. User mengirim permintaan melalui keranjang.
3. Sistem memvalidasi dan menyimpan permintaan dengan status **Menunggu**.
4. Admin melihat permintaan dan menugaskan teknisi.
5. Teknisi memeriksa stok, menentukan jumlah yang disetujui, lalu memproses permintaan.
6. Setelah barang diserahkan, teknisi menyelesaikan permintaan.
7. Sistem mencatat transaksi **barang keluar** dan mengurangi saldo stok.
8. User dapat melihat status **Selesai** pada riwayat permintaan.

Jika data permintaan tidak valid, sistem menampilkan pesan kesalahan. Jika stok tidak mencukupi, teknisi dapat menyesuaikan jumlah yang disetujui atau menolak permintaan.
