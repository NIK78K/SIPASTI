-- Master persediaan: kategori + barang
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
