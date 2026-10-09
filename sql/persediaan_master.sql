-- Master persediaan: kategori + barang (SINGLE FILE, idempoten)
-- Import: mysql -u root -p itservice < sql/persediaan_master.sql
-- atau via phpMyAdmin > Import

CREATE TABLE IF NOT EXISTS `master_kategori_persediaan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `master_kategori_persediaan` (`kode`, `nama`, `deskripsi`) VALUES
('ATK-01', 'Alat Tulis', 'Pena, pensil, stabilo, spidol, dsb.'),
('ATK-02', 'Tinta & Stempel', 'Tinta tulis dan tinta stempel'),
('ATK-03', 'Penjepit Kertas', 'Binder klip, trigonal clip, pelubang kertas'),
('ATK-04', 'Penghapus & Korektor', 'Tipp-ex, penghapus'),
('ATK-05', 'Buku Tulis', 'Buku folio, buku ekspedisi'),
('ATK-06', 'Ordner & Map', 'Map logo BPS, spring file, box file, map biola'),
('ATK-07', 'Cutter', 'Pisau cutter dan anak pisau cutter'),
('ATK-08', 'Alat Perekat', 'Lakban, double tape, lem'),
('ATK-09', 'Staples & Isi Staples', 'Hekter dan anak hekter'),
('ATK-10', 'ATK Lainnya', 'Gunting, push pin, sticky notes, sign hire combo'),
('ATK-11', 'Kertas HVS', 'Kertas HVS A4, HVS kuning, A4 100gr'),
('ATK-12', 'Berbagai Kertas', 'Kertas padi, kertas glossy, concord'),
('ATK-13', 'Amplop', 'Amplop putih, coklat, map plastik'),
('ATK-14', 'Tinta & Toner Printer', 'Tinta Epson, toner HP Laserjet'),
('ARK-01', 'USB & Flash Disk', 'Flashdisk, USB hub'),
('ARK-02', 'Mouse & Keyboard', 'Mouse wireless, keyboard wireless, webcam'),
('ARK-03', 'Bahan Komputer Lainnya', 'Kabel jaringan, konektor RJ45, kabel USB printer'),
('ARK-04', 'Sapu & Sikat', 'Sapu, sikat lantai, sikat kloset'),
('ARK-05', 'Alat Pel & Lap', 'Kain pel, wiper, pel karet'),
('ARK-06', 'Ember & Wadah Air', 'Ember, gayung, slang PVC, keran wastafel'),
('ARK-07', 'Kunci & Kran', 'Gembok, kunci bulat, kran PVC'),
('ARK-08', 'Bahan Kimia Pembersih', 'Sabun lantai, pembersih toilet, karbol'),
('ARK-09', 'Pengharum Ruangan', 'Glade, Bayfresh, Stella, Dahlia'),
('ARK-10', 'Perabot Kantor Lainnya', 'Tissue, spons cuci, sarung tangan'),
('ARK-11', 'Kabel & Lampu Listrik', 'Kabel listrik, lampu TL, LED'),
('ARK-12', 'Stop Kontak & Saklar', 'Stop kontak, saklar, balast, starter'),
('ARK-13', 'Baterai', 'Baterai AA, AAA'),
('ARK-14', 'Alat Listrik Lainnya', 'Tespen, tang, obeng, isolasi'),
('ARK-15', 'Perlengkapan Kantor Lainnya', 'Tatakan mouse, tempat dokumen, lem tikus, plastik');

CREATE TABLE IF NOT EXISTS `master_barang_persediaan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` INT(11) DEFAULT NULL,
  `kode_barang` VARCHAR(50) NULL UNIQUE,
  `nama_barang` VARCHAR(150) NOT NULL,
  `satuan` VARCHAR(50) NOT NULL DEFAULT 'pcs',
  `status` ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `fk_barang_persediaan_kategori` FOREIGN KEY (`kategori_id`)
    REFERENCES `master_kategori_persediaan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 002: tambah kolom kode_barang ke master_barang_persediaan (run sekali)
-- MySQL tidak dukung IF NOT EXISTS untuk ADD COLUMN, jadi pakai guard INFORMATION_SCHEMA.
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'master_barang_persediaan'
    AND COLUMN_NAME = 'kode_barang'
);
SET @alter_sql := IF(@col_exists = 0,
  'ALTER TABLE `master_barang_persediaan` ADD COLUMN `kode_barang` VARCHAR(50) NULL UNIQUE AFTER `kategori_id`',
  'SELECT 1'
);
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 003: seed master_barang_persediaan dari BarangSeeder.php (104 baris)
-- Run: mysql -u root itservice < sql/003_barang_persediaan_seed.sql
-- Idempotent via INSERT IGNORE (kode_barang UNIQUE).
INSERT IGNORE INTO `master_barang_persediaan` (`kode_barang`, `nama_barang`, `satuan`, `kategori_id`, `status`) VALUES
('ATK-01-002', 'Stabilo', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100128', 'Pena Unibal signo-biru', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100129', 'Spidol Permanen', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100141', 'Pena Meja', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100146', 'Penggaris Besi 30 cm', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100150', 'Pena uniball signo-hitam', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100163', 'Pena merah', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100165', 'Pena Kenko Easy Gell', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100171', 'Spidol Non Permanent', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100172', 'Pena 4 warna', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100173', 'Pena Kenko 2', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100174', 'Pensil 2B', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100175', 'Pena Standar', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100181', 'Pena Kenko K1', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-01-100182', 'Pena Kenko K-1', 'Lusin', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-01'), 'Aktif'),
('ATK-02-001', 'Tinta Stempel', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-02'), 'Aktif'),
('ATK-03-002', 'BINDER KLIP NO.155', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-03-003', 'BINDER KLIP NO.107', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-03-005', 'Pelubang Kertas', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-03-3011', 'Binder Klip no 260', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-03-3016', 'Trigonal Clip', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-03-3021', 'BINDER KLIP 111', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-03'), 'Aktif'),
('ATK-04-002', 'TIPP EX', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-04'), 'Aktif'),
('ATK-04-003', 'Penghapus', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-04'), 'Aktif'),
('ATK-05-100510', 'Buku Folio', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-05'), 'Aktif'),
('ATK-05-100514', 'Buku Expedisi Surat', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-05'), 'Aktif'),
('ATK-06-005', 'MAP LOGO BPS', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100607', 'Spring File', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100618', 'Box file', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100621', 'Map Biola', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100637', 'Map Bantex Real', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100638', 'Map Combo 401', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100643', 'Map Lidah', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100647', 'MAP COMBO 402', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-06-100649', 'Business File Merah', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-06'), 'Aktif'),
('ATK-07-001', 'PISAU CUTTER', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-07'), 'Aktif'),
('ATK-07-002', 'Anak Pisau Cutter', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-07'), 'Aktif'),
('ATK-08-003', 'Lakban Bening 2''''', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-004', 'Lakban Bening Kecil 1''''', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-10008', 'Lakban Cokelat', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-10014', 'Lakban Hitam 2''''', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-10027', 'Double Tape 3M', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-10028', 'Double Tape 3M (Merah)', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-08-10029', 'Lem Povinal', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-08'), 'Aktif'),
('ATK-09-003', 'Staple Remover', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-09-H001', 'Hekter Tembak kangguru', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-09-H002', 'Hekter no 10', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-09-H003', 'HEKTER BESAR', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-09-I002', 'Anak Hekter no 10', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-09-I003', 'ANAK HEKTER NO.03', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-09'), 'Aktif'),
('ATK-10-999011', 'Gunting sedang', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-10'), 'Aktif'),
('ATK-10-999035', 'Push pin', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-10'), 'Aktif'),
('ATK-10-999056', 'Sign Hire Combo', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-10'), 'Aktif'),
('ATK-10-999065', 'Mark & Notes', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-10'), 'Aktif'),
('ATK-10-999066', 'Sticky Notes', 'Bungkus', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-10'), 'Aktif'),
('ATK-11-019', 'Kertas HVS A4 Sidu, 70 Gram', 'Rim', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-11'), 'Aktif'),
('ATK-11-020', 'HVS KUNING', 'Rim', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-11'), 'Aktif'),
('ATK-11-022', 'Kertas HVS A4 100 gr', 'Rim', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-11'), 'Aktif'),
('ATK-12-2005', 'Kertas Padi', 'Lembar', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-12'), 'Aktif'),
('ATK-12-2007', 'Kertas Glossy', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-12'), 'Aktif'),
('ATK-12-001', 'Kertas Concord 90gr', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-12'), 'Aktif'),
('ATK-13-012', 'Amplop putih pendek', 'Kotak', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-13'), 'Aktif'),
('ATK-13-016', 'Map Plastik ukuran F4', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-13'), 'Aktif'),
('ATK-13-018', 'AMPLOP PUTIH PANJANG LOGO', 'Lbr', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-13'), 'Aktif'),
('ATK-13-019', 'AMPLOP COKLAT LOGO', 'Lbr', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-13'), 'Aktif'),
('ATK-14-4043', 'Toner HP Laserjet 83 A', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4137', 'Tinta Epson L3110 003 Black', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4138', 'Tinta Epson L3110 003 Cyan', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4139', 'Tinta Epson L3110 003 Magenta', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4140', 'Tinta Epson L3110 003 Yellow', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4162', 'Tinta Refill Epson 008 Cyan', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4185', 'Epson Ink 008 Magenta', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4189', 'Epson Ink 008 hitam', 'Botol', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ATK-14-4213', 'Tinta Epson 008 Yellow', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ATK-14'), 'Aktif'),
('ARK-01-002', 'Flasdisk', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-01'), 'Aktif'),
('ARK-01-003', 'Flashdisk 16 GB', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-01'), 'Aktif'),
('ARK-01-013', 'USB Hub', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-01'), 'Aktif'),
('ARK-02-004', 'Mouse Logiteck Warless', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-02'), 'Aktif'),
('ARK-02-010', 'Keyboard Warless', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-02'), 'Aktif'),
('ARK-08-086', 'PORSTEX FC BRU BTL 1', 'Bh/Btl', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-08'), 'Aktif'),
('ARK-08-101', 'Cling GC LMN RF', 'Buah/Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-08'), 'Aktif'),
('ARK-08-126', 'Superpell YL RF 770', 'Bungkus', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-08'), 'Aktif'),
('ARK-08-136', 'WIPOL SEREH & JERUK 750ML', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-08'), 'Aktif'),
('ARK-08-141', 'Mama Lemon Pouch 680ML', 'Bungkus', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-08'), 'Aktif'),
('ARK-09-040', 'Dahlia Toilet Ball', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-09'), 'Aktif'),
('ARK-09-046', 'Bayfresh Diff VB Reffil', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-09'), 'Aktif'),
('ARK-09-048', 'Glade Matic Spray', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-09'), 'Aktif'),
('ARK-10-028', 'NICE FACIAL TISSUE NP 900 G', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-10'), 'Aktif'),
('ARK-10-093', 'Nice Tisu Gulung Toilet', 'Bks', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-10'), 'Aktif'),
('ARK-10-096', 'MULTIFOLD TISSUE', 'Bks', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-10'), 'Aktif'),
('ARK-11-010', 'Fitting Keramik', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-11'), 'Aktif'),
('ARK-11-026', 'Lampu Hanoc 9 w Kuning', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-11'), 'Aktif'),
('ARK-11-029', 'TL Philip 36 W', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-11'), 'Aktif'),
('ARK-12-002', 'Stop Kontak AC', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-12'), 'Aktif'),
('ARK-12-007', 'STOP KONTAK UTICON 5 LUBANG', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-12'), 'Aktif'),
('ARK-12-S001', 'SAKLAR', 'Pcs', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-12'), 'Aktif'),
('ARK-12-ST001', 'STARTER', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-12'), 'Aktif'),
('ARK-12-VT001', 'Fitting TL Kap RM T8 YLI', 'Set', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-12'), 'Aktif'),
('ARK-13-003', 'Bateray Alkaline AA', 'Set', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-13'), 'Aktif'),
('ARK-13-004', 'Bateray Alkaline AAA', 'Set', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-13'), 'Aktif'),
('ARK-15-431', 'Tempat Dokumen Meja', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-15'), 'Aktif'),
('ARK-15-638', 'Tatakan Mouse', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-15'), 'Aktif'),
('ARK-15-858', 'Lem Tikus', 'Buah', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-15'), 'Aktif'),
('ARK-15-859', 'PLASTIK MATAHARI JUMBO', 'Bungkus', (SELECT id FROM master_kategori_persediaan WHERE kode='ARK-15'), 'Aktif');

-- 004: stok awal persediaan Januari 2026 (dari StokAwalSeeder.php, 104 baris)
-- Idempoten: CREATE IF NOT EXISTS + INSERT IGNORE ... SELECT (skip kode hilang, tiru firstOrCreate).
CREATE TABLE IF NOT EXISTS `stok_awal_persediaan` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `barang_id` INT NOT NULL,
  `bulan` TINYINT NOT NULL DEFAULT 1,
  `tahun` SMALLINT NOT NULL DEFAULT 2026,
  `jumlah` INT NOT NULL DEFAULT 0,
  `keterangan` VARCHAR(255) DEFAULT 'Stok awal Januari 2026',
  `created_at` TIMESTAMP DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_barang_periode` (`barang_id`, `bulan`, `tahun`),
  CONSTRAINT `fk_stok_awal_barang` FOREIGN KEY (`barang_id`)
    REFERENCES `master_barang_persediaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 17, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 98, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100128';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 50, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100129';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 6, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100141';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 2, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100146';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 151, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100150';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 10, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100163';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 180, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100165';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 68, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100171';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 11, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100172';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 102, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100173';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 20, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100174';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 255, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100175';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 79, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100181';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 240, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-01-100182';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 3, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-02-001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 86, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 113, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-005';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 6, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-3011';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 168, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-3016';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 119, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-03-3021';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 8, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-04-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 76, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-04-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-05-100510';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 2, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-05-100514';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 1668, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-005';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100607';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 6, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100618';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 600, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100621';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100637';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 24, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100638';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 491, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100643';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100647';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 35, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-06-100649';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 42, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-07-001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 24, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-07-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 23, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 32, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-004';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 38, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-10008';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 12, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-10014';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 25, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-10027';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 8, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-10028';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-08-10029';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 7, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 1, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-H001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 19, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-H002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-H003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 13, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-I002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-09-I003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 49, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-10-999011';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 3, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-10-999035';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 57, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-10-999056';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 24, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-10-999065';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 39, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-10-999066';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 414, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-11-019';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 2, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-11-020';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 12, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-11-022';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 112, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-12-2005';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 7, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-12-2007';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 905, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-12-001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-13-012';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 512, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-13-016';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 1300, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-13-018';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 1175, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-13-019';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 10, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4043';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4137';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 11, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4138';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 11, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4139';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 10, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4140';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 7, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4162';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4185';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 8, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4189';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ATK-14-4213';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 3, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-01-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 5, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-01-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 9, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-01-013';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 30, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-02-004';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 12, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-02-010';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 6, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-08-086';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 15, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-08-101';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 21, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-08-126';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 14, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-08-136';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 19, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-08-141';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 34, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-09-040';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 33, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-09-046';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 18, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-09-048';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 12, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-10-028';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 6, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-10-093';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-10-096';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-11-010';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-11-026';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-11-029';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-12-002';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-12-007';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-12-S001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-12-ST001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-12-VT001';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-13-003';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-13-004';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-15-431';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-15-638';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-15-858';
INSERT IGNORE INTO `stok_awal_persediaan` (`barang_id`, `bulan`, `tahun`, `jumlah`, `keterangan`) SELECT `id`, 1, 2026, 0, 'Stok awal Januari 2026' FROM `master_barang_persediaan` WHERE `kode_barang`='ARK-15-859';


-- 005: transaksi persediaan dari TransaksiSeeder.php (105 baris)
-- Idempoten: INSERT ... SELECT ... WHERE NOT EXISTS (skip nama hilang, cegah duplikat re-import).
CREATE TABLE IF NOT EXISTS `transaksi_persediaan` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `barang_id` INT NOT NULL,
  `jenis` ENUM('masuk','keluar') NOT NULL,
  `tanggal` DATE NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 0,
  `uraian` VARCHAR(255) DEFAULT NULL,
  `penerima_sumber` VARCHAR(100) DEFAULT NULL,
  `no_dokumen` VARCHAR(100) DEFAULT NULL,
  `user_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `barang_id` (`barang_id`),
  KEY `tanggal` (`tanggal`),
  CONSTRAINT `fk_transaksi_barang` FOREIGN KEY (`barang_id`) REFERENCES `master_barang_persediaan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 005a: tambah kolom no_dokumen ke transaksi_persediaan (run sekali, idempoten)
-- MySQL tidak dukung IF NOT EXISTS untuk ADD COLUMN, jadi pakai guard INFORMATION_SCHEMA.
-- DB existing: cukup re-import file ini, atau manual:
-- ALTER TABLE `transaksi_persediaan` ADD COLUMN `no_dokumen` VARCHAR(100) NULL AFTER `penerima_sumber`;
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'transaksi_persediaan'
    AND COLUMN_NAME = 'no_dokumen'
);
SET @alter_sql := IF(@col_exists = 0,
  'ALTER TABLE `transaksi_persediaan` ADD COLUMN `no_dokumen` VARCHAR(100) NULL AFTER `penerima_sumber`',
  'SELECT 1'
);
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'masuk', '2026-01-29', 36, 'Gusmela (AZKO Living World Pekanbaru)', 'Gudang', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='MULTIFOLD TISSUE'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='MULTIFOLD TISSUE' AND t.`jenis`='masuk' AND t.`tanggal`='2026-01-29' AND t.`jumlah`=36 AND t.`uraian`='Gusmela (AZKO Living World Pekanbaru)' AND t.`penerima_sumber`='Gudang');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 2, 'Umum', 'Sariningsih', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Cokelat'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Cokelat' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Sariningsih');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Sariningsih', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Sariningsih');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Sariningsih', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AAA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AAA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Sariningsih');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Sariningsih', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Kertas Glossy'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Kertas Glossy' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Sariningsih');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 2, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Cokelat'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Cokelat' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='SAKLAR'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='SAKLAR' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='TL Philip 36 W'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='TL Philip 36 W' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Double Tape 3M (Merah)'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Double Tape 3M (Merah)' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-02', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Kertas HVS A4 Sidu, 70 Gram'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Kertas HVS A4 Sidu, 70 Gram' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-02' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 4, 'CS Lantai 3', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bayfresh Diff VB Reffil'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bayfresh Diff VB Reffil' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=4 AND t.`uraian`='CS Lantai 3' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Superpell YL RF 770'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Superpell YL RF 770' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 2, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Cling GC LMN RF'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Cling GC LMN RF' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=2 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Mama Lemon Pouch 680ML'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Mama Lemon Pouch 680ML' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 4, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Glade Matic Spray'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Glade Matic Spray' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=4 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Cling GC LMN RF'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Cling GC LMN RF' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 5, 'CS Lantai 2', 'Mulyani', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bayfresh Diff VB Reffil'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bayfresh Diff VB Reffil' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=5 AND t.`uraian`='CS Lantai 2' AND t.`penerima_sumber`='Mulyani');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='HEKTER BESAR'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='HEKTER BESAR' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 8, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Bening Kecil 1'''''
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Bening Kecil 1''''' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=8 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 2, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Double Tape 3M'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Double Tape 3M' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=2 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Hekter no 10'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Hekter no 10' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 1, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Staple Remover'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Staple Remover' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=1 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 12, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Standar'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Standar' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=12 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 12, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Kenko K-1'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Kenko K-1' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=12 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 100, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Map Plastik ukuran F4'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Map Plastik ukuran F4' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=100 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 3, 'IPDS', 'IPDS', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tatakan Mouse'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tatakan Mouse' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=3 AND t.`uraian`='IPDS' AND t.`penerima_sumber`='IPDS');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 2, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='STARTER'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='STARTER' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-05', 2, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='STOP KONTAK UTICON 5 LUBANG'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='STOP KONTAK UTICON 5 LUBANG' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-05' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='STARTER'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='STARTER' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 1, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Fitting TL Kap RM T8 YLI'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Fitting TL Kap RM T8 YLI' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 2, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sticky Notes'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sticky Notes' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=2 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 3, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sign Hire Combo'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sign Hire Combo' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=3 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 6, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Anak Hekter no 10'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Anak Hekter no 10' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=6 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 3, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Gunting sedang'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Gunting sedang' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=3 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 2, 'Umum', 'Hasan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Mouse Logiteck Warless'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Mouse Logiteck Warless' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hasan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-06', 1, 'Statistik Sosial', 'Azhari Andria', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Mouse Logiteck Warless'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Mouse Logiteck Warless' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-06' AND t.`jumlah`=1 AND t.`uraian`='Statistik Sosial' AND t.`penerima_sumber`='Azhari Andria');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-07', 3, 'Sekretaris', 'Ghea Madya', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AAA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AAA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-07' AND t.`jumlah`=3 AND t.`uraian`='Sekretaris' AND t.`penerima_sumber`='Ghea Madya');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-07', 12, 'Sekretaris', 'Ghea Madya', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Standar'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Standar' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-07' AND t.`jumlah`=12 AND t.`uraian`='Sekretaris' AND t.`penerima_sumber`='Ghea Madya');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-07', 1, 'Sekretaris', 'Ghea Madya', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Mouse Logiteck Warless'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Mouse Logiteck Warless' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-07' AND t.`jumlah`=1 AND t.`uraian`='Sekretaris' AND t.`penerima_sumber`='Ghea Madya');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-07', 1, 'Sekretaris', 'Ghea Madya', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Bening Kecil 1'''''
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Bening Kecil 1''''' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-07' AND t.`jumlah`=1 AND t.`uraian`='Sekretaris' AND t.`penerima_sumber`='Ghea Madya');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-07', 100, 'Sekretaris', 'Ghea Madya', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='MAP LOGO BPS'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='MAP LOGO BPS' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-07' AND t.`jumlah`=100 AND t.`uraian`='Sekretaris' AND t.`penerima_sumber`='Ghea Madya');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-08', 2, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Bening Kecil 1'''''
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Bening Kecil 1''''' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-08' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-08', 2, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Bening 2'''''
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Bening 2''''' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-08' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-08', 4, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Cokelat'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Cokelat' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-08' AND t.`jumlah`=4 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-08', 1, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Spidol Permanen'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Spidol Permanen' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-08' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-08', 2, 'Umum', 'Hadi Siswanto', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='TL Philip 36 W'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='TL Philip 36 W' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-08' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Hadi Siswanto');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Umum', 'Nurul Elisa', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sticky Notes'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sticky Notes' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Nurul Elisa');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Nurul Elisa', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Standar'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Standar' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Nurul Elisa');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Umum', 'Nurul Elisa', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena uniball signo-hitam'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena uniball signo-hitam' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Nurul Elisa');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Umum', 'Nurul Elisa', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Kenko 2'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Kenko 2' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Nurul Elisa');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 3, 'Umum', 'Nurul Elisa', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='BINDER KLIP NO.107'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='BINDER KLIP NO.107' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=3 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Nurul Elisa');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 12, 'SDM & Hukum', 'Ira Maya Susanti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena uniball signo-hitam'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena uniball signo-hitam' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=12 AND t.`uraian`='SDM & Hukum' AND t.`penerima_sumber`='Ira Maya Susanti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 12, 'SDM & Hukum', 'Ira Maya Susanti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Standar'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Standar' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=12 AND t.`uraian`='SDM & Hukum' AND t.`penerima_sumber`='Ira Maya Susanti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 12, 'SDM & Hukum', 'Ira Maya Susanti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pensil 2B'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pensil 2B' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=12 AND t.`uraian`='SDM & Hukum' AND t.`penerima_sumber`='Ira Maya Susanti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 12, 'SDM & Hukum', 'Ira Maya Susanti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Map Plastik ukuran F4'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Map Plastik ukuran F4' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=12 AND t.`uraian`='SDM & Hukum' AND t.`penerima_sumber`='Ira Maya Susanti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pensil 2B'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pensil 2B' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Trigonal Clip'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Trigonal Clip' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sign Hire Combo'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sign Hire Combo' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Gunting sedang'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Gunting sedang' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 12, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='MAP LOGO BPS'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='MAP LOGO BPS' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=12 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 10, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Kertas HVS A4 Sidu, 70 Gram'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Kertas HVS A4 Sidu, 70 Gram' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=10 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Black'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Black' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Cyan'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Cyan' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Yellow'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Yellow' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Magenta'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Magenta' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Cling GC LMN RF'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Cling GC LMN RF' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 4, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=4 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='MULTIFOLD TISSUE'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='MULTIFOLD TISSUE' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='PLASTIK MATAHARI JUMBO'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='PLASTIK MATAHARI JUMBO' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Glade Matic Spray'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Glade Matic Spray' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Superpell YL RF 770'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Superpell YL RF 770' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 3, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Dahlia Toilet Ball'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Dahlia Toilet Ball' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=3 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 2, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='WIPOL SEREH & JERUK 750ML'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='WIPOL SEREH & JERUK 750ML' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-09', 1, 'Umum', 'Jefri Afriawan', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='PORSTEX FC BRU BTL 1'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='PORSTEX FC BRU BTL 1' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-09' AND t.`jumlah`=1 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Jefri Afriawan');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 6, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Spidol Permanen'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Spidol Permanen' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=6 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 2, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Hekter no 10'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Hekter no 10' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=2 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 1, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Box file'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Box file' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=1 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 1, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lem Povinal'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lem Povinal' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=1 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 1, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Lakban Cokelat'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Lakban Cokelat' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=1 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 12, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Unibal signo-biru'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Unibal signo-biru' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=12 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 100, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='AMPLOP PUTIH PANJANG LOGO'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='AMPLOP PUTIH PANJANG LOGO' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=100 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 2, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 2, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AAA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AAA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=2 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-14', 3, 'Umum', 'Yusri', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Kenko 2'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Kenko 2' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-14' AND t.`jumlah`=3 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Yusri');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 12, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='MAP LOGO BPS'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='MAP LOGO BPS' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=12 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Trigonal Clip'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Trigonal Clip' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Anak Hekter no 10'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Anak Hekter no 10' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Kenko Easy Gell'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Kenko Easy Gell' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Hekter no 10'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Hekter no 10' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Black'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Black' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Cyan'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Cyan' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Yellow'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Yellow' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Tinta Epson L3110 003 Magenta'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Tinta Epson L3110 003 Magenta' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sign Hire Combo'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sign Hire Combo' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Gunting sedang'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Gunting sedang' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Unibal signo-biru'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Unibal signo-biru' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena uniball signo-hitam'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena uniball signo-hitam' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 2, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Bateray Alkaline AA'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Bateray Alkaline AA' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=2 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-22', 1, 'Produksi', 'Produksi', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Mouse Logiteck Warless'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Mouse Logiteck Warless' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-22' AND t.`jumlah`=1 AND t.`uraian`='Produksi' AND t.`penerima_sumber`='Produksi');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-23', 2, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Pena Unibal signo-biru'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Pena Unibal signo-biru' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-23' AND t.`jumlah`=2 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-23', 1, 'Newrilis', 'Newrilis', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Amplop putih pendek'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Amplop putih pendek' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-23' AND t.`jumlah`=1 AND t.`uraian`='Newrilis' AND t.`penerima_sumber`='Newrilis');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-23', 100, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Map Biola'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Map Biola' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-23' AND t.`jumlah`=100 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-23', 5, 'Keuangan', 'Dewi Astuti', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Sign Hire Combo'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Sign Hire Combo' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-23' AND t.`jumlah`=5 AND t.`uraian`='Keuangan' AND t.`penerima_sumber`='Dewi Astuti');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-27', 12, 'Umum', 'Umum', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Spidol Non Permanent'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Spidol Non Permanent' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-27' AND t.`jumlah`=12 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Umum');
INSERT INTO `transaksi_persediaan` (`barang_id`,`jenis`,`tanggal`,`jumlah`,`uraian`,`penerima_sumber`,`user_id`)
SELECT m.`id`, 'keluar', '2026-01-27', 3, 'Umum', 'Umum', (SELECT `id` FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1)
FROM `master_barang_persediaan` m
WHERE m.`nama_barang`='Kertas Padi'
AND NOT EXISTS (SELECT 1 FROM `transaksi_persediaan` t JOIN `master_barang_persediaan` b ON b.`id`=t.`barang_id` WHERE b.`nama_barang`='Kertas Padi' AND t.`jenis`='keluar' AND t.`tanggal`='2026-01-27' AND t.`jumlah`=3 AND t.`uraian`='Umum' AND t.`penerima_sumber`='Umum');

-- 006: sub kategori permintaan barang persediaan (tiket user -> teknisi proses manual)
-- Idempoten: hanya insert bila slug belum ada. Ikut kategori slug 'permintaan'.
INSERT INTO `master_sub_kategori` (`kategori_id`, `nama_sub_kategori`, `slug`, `perlu_jadwal`, `created_at`)
SELECT k.`id`, 'Permintaan Barang Persediaan', 'permintaan-barang-persediaan', 0, NOW()
FROM `master_kategori` k
WHERE k.`slug` = 'permintaan'
AND NOT EXISTS (SELECT 1 FROM `master_sub_kategori` s WHERE s.`slug` = 'permintaan-barang-persediaan');

-- 007: alur permintaan barang persediaan end-to-end (idempoten, re-import aman)
-- (a) seed sub kategori slug 'permintaan-persediaan' (kategori 'permintaan', perlu_jadwal 0)
INSERT INTO `master_sub_kategori` (`kategori_id`, `nama_sub_kategori`, `slug`, `perlu_jadwal`, `created_at`)
SELECT k.`id`, 'Permintaan Persediaan', 'permintaan-persediaan', 0, NOW()
FROM `master_kategori` k
WHERE k.`slug` = 'permintaan'
AND NOT EXISTS (SELECT 1 FROM `master_sub_kategori` s WHERE s.`slug` = 'permintaan-persediaan');

-- (b) keranjang cadangan/future (session cart tetap dipakai, tabel ini cadangan)
CREATE TABLE IF NOT EXISTS `keranjang_persediaan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `barang_id` INT(11) NOT NULL,
  `jumlah` INT(11) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_barang` (`user_id`, `barang_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_keranjang_barang` FOREIGN KEY (`barang_id`) REFERENCES `master_barang_persediaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- (c) detail per item permintaan persediaan per tiket
CREATE TABLE IF NOT EXISTS `ticket_persediaan_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` INT(11) NOT NULL,
  `barang_id` INT(11) NOT NULL,
  `jumlah` INT(11) NOT NULL DEFAULT 1,
  `nama_barang_snapshot` VARCHAR(150) DEFAULT NULL,
  `satuan_snapshot` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `fk_tpi_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tpi_barang` FOREIGN KEY (`barang_id`) REFERENCES `master_barang_persediaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- (d) taut tiket di transaksi_persediaan (guard INFORMATION_SCHEMA, pola 005a)
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'transaksi_persediaan'
    AND COLUMN_NAME = 'ticket_id'
);
SET @alter_sql := IF(@col_exists = 0,
  'ALTER TABLE `transaksi_persediaan` ADD COLUMN `ticket_id` INT NULL AFTER `user_id`',
  'SELECT 1'
);
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET @fk_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'transaksi_persediaan'
    AND CONSTRAINT_NAME = 'fk_transaksi_ticket'
);
SET @alter_sql := IF(@fk_exists = 0,
  'ALTER TABLE `transaksi_persediaan` ADD CONSTRAINT `fk_transaksi_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE SET NULL',
  'SELECT 1'
);
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- (e) ACC teknisi per item: jumlah_disetujui INT NULL (guard INFORMATION_SCHEMA, pola 005a)
-- NULL=belum ACC, default ikut jumlah saat ACC.
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'ticket_persediaan_items'
    AND COLUMN_NAME = 'jumlah_disetujui'
);
SET @alter_sql := IF(@col_exists = 0,
  'ALTER TABLE `ticket_persediaan_items` ADD COLUMN `jumlah_disetujui` INT NULL AFTER `jumlah`',
  'SELECT 1'
);
PREPARE stmt FROM @alter_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

