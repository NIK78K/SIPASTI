# ERD Sistem Barang Persediaan SIPASTI

Diagram berikut menggambarkan entitas utama pada modul persediaan, permintaan barang, teknisi, dan transaksi stok.

```plantuml
@startuml
title ERD Sistem Barang Persediaan SIPASTI

hide methods
hide stereotypes
skinparam linetype ortho
skinparam entity {
    BackgroundColor #F8FAFC
    BorderColor #1E3A8A
    FontColor #0F172A
}

entity "users" as users {
    * id : INT <<PK>>
    --
    name : VARCHAR
    email : VARCHAR
    password : VARCHAR
    role : ENUM
    is_verified : TINYINT
    created_at : DATETIME
}

entity "teknisi" as teknisi {
    * id : INT <<PK>>
    --
    user_id : INT <<FK>>
    keahlian : VARCHAR
    status : ENUM
    alasan_offline : TEXT
    created_at : DATETIME
}

entity "master_kategori_persediaan" as kategori {
    * id : INT <<PK>>
    --
    kode : VARCHAR
    nama : VARCHAR
    deskripsi : VARCHAR
    created_at : TIMESTAMP
}

entity "master_barang_persediaan" as barang {
    * id : INT <<PK>>
    --
    kategori_id : INT <<FK>>
    kode_barang : VARCHAR
    nama_barang : VARCHAR
    satuan : VARCHAR
    stok_minimum : INT
    status : ENUM
    created_at : TIMESTAMP
    updated_at : TIMESTAMP
}

entity "stok_awal_persediaan" as stok_awal {
    * id : INT <<PK>>
    --
    barang_id : INT <<FK>>
    bulan : TINYINT
    tahun : SMALLINT
    jumlah : INT
    keterangan : VARCHAR
    created_at : TIMESTAMP
    updated_at : TIMESTAMP
}

entity "transaksi_persediaan" as transaksi {
    * id : INT <<PK>>
    --
    barang_id : INT <<FK>>
    user_id : INT <<FK>>
    ticket_id : INT <<FK>>
    jenis : ENUM
    tanggal : DATE
    jumlah : INT
    uraian : VARCHAR
    penerima_sumber : VARCHAR
    no_dokumen : VARCHAR
    created_at : TIMESTAMP
    updated_at : TIMESTAMP
}

entity "tickets" as tickets {
    * id : INT <<PK>>
    --
    user_id : INT <<FK>>
    teknisi_id : INT <<FK>>
    kode_tiket : VARCHAR
    kategori_id : INT
    sub_kategori_id : INT
    pelapor : VARCHAR
    status : ENUM
    keterangan : TEXT
    created_at : DATETIME
    selesai_at : DATETIME
}

entity "ticket_persediaan_items" as ticket_items {
    * id : INT <<PK>>
    --
    ticket_id : INT <<FK>>
    barang_id : INT <<FK>>
    jumlah : INT
    jumlah_disetujui : INT
    keterangan : VARCHAR
    nama_barang_snapshot : VARCHAR
    satuan_snapshot : VARCHAR
    created_at : TIMESTAMP
}

entity "keranjang_persediaan" as keranjang {
    * id : INT <<PK>>
    --
    user_id : INT <<FK>>
    barang_id : INT <<FK>>
    jumlah : INT
    created_at : TIMESTAMP
    updated_at : TIMESTAMP
}

users ||--o{ teknisi : memiliki
kategori ||--o{ barang : mengelompokkan
barang ||--o{ stok_awal : memiliki
barang ||--o{ transaksi : dicatat_dalam
users ||--o{ transaksi : mencatat
tickets ||--o{ transaksi : menghasilkan
users ||--o{ tickets : membuat
teknisi ||--o{ tickets : menangani
tickets ||--o{ ticket_items : memiliki
barang ||--o{ ticket_items : diminta_dalam
users ||--o{ keranjang : memiliki
barang ||--o{ keranjang : dimasukkan_ke

@enduml
```

## Catatan

- `ticket_persediaan_items` adalah tabel detail yang menghubungkan permintaan dengan barang.
- `jumlah_disetujui` menyimpan jumlah barang yang disetujui teknisi.
- `keterangan` menyimpan alasan penyesuaian jika jumlah disetujui tidak sama dengan yang diminta user.
- Transaksi `keluar` dapat ditautkan ke tiket melalui `ticket_id`.
- Pada alur saat ini, keranjang digunakan melalui session; `keranjang_persediaan` disediakan sebagai tabel keranjang/cadangan.
