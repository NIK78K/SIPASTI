# Use Case Diagram Sistem Barang Persediaan SIPASTI

Diagram berikut menggambarkan fungsi utama User, Admin, Superadmin, dan Teknisi pada sistem barang persediaan SIPASTI secara ringkas.

```plantuml
@startuml
left to right direction

skinparam packageStyle rectangle
skinparam shadowing false
skinparam actorStyle awesome
skinparam usecase {
    BackgroundColor #F8FAFC
    BorderColor #1E3A8A
}

title Use Case Diagram Sistem Barang Persediaan SIPASTI

actor "User" as User
actor "Admin" as Admin
actor "Superadmin" as Superadmin
actor "Teknisi" as Teknisi

Superadmin --|> Admin

rectangle "Sistem Barang Persediaan SIPASTI" {
    usecase "Mengajukan Permintaan\nBarang" as UC01
    usecase "Melihat Riwayat dan Status\nPermintaan" as UC02

    usecase "Mengelola Data Persediaan" as UC03
    usecase "Mengelola Permintaan Barang" as UC04
    usecase "Melihat Laporan Persediaan" as UC05

    usecase "Mengelola Akun dan Role" as UC06

    usecase "Memproses Permintaan Barang" as UC07
    usecase "Mengelola Transaksi Stok" as UC08
    usecase "Menyelesaikan Permintaan" as UC09

    usecase "Memperbarui Stok" as UC10
}

User --> UC01
User --> UC02

Admin --> UC03
Admin --> UC04
Admin --> UC05

Superadmin --> UC06

Teknisi --> UC07
Teknisi --> UC08
Teknisi --> UC09

UC01 ..> UC10 : <<include>>
UC07 ..> UC10 : <<include>>
UC08 ..> UC10 : <<include>>
UC09 ..> UC10 : <<include>>

@enduml
```

## Catatan

- `Superadmin --|> Admin` berarti Superadmin mewarisi hak akses Admin.
- **User** mengajukan permintaan dan melihat status permintaan.
- **Admin** mengelola data persediaan, permintaan barang, dan laporan.
- **Superadmin** mengelola akun serta role pengguna.
- **Teknisi** memproses permintaan, mengelola transaksi stok, dan menyelesaikan permintaan.
- `<<include>> Memperbarui Stok` menunjukkan bahwa proses permintaan atau transaksi dapat memperbarui stok.
