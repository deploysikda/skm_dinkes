-- =====================================================================
-- Database: db_skm_dinkes
-- Aplikasi Survei Kepuasan Masyarakat (SKM)
-- Dinas Kesehatan Kota Bandung
-- Mengacu pada Permenpan RB No. 14 Tahun 2017 (9 Unsur Pelayanan)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `db_skm_dinkes` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_skm_dinkes`;

-- ---------------------------------------------------------------------
-- Tabel: tb_pengaturan
-- Pengaturan umum instansi (ditampilkan di header/footer & laporan)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_pengaturan`;
CREATE TABLE `tb_pengaturan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_instansi` VARCHAR(150) NOT NULL,
  `nama_layanan` VARCHAR(150) DEFAULT NULL,
  `alamat` VARCHAR(255) DEFAULT NULL,
  `kepala_instansi` VARCHAR(150) DEFAULT NULL,
  `nip_kepala` VARCHAR(50) DEFAULT NULL,
  `logo` VARCHAR(150) DEFAULT NULL,
  `deskripsi_survei` TEXT,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengaturan` (`id`, `nama_instansi`, `nama_layanan`, `alamat`, `kepala_instansi`, `nip_kepala`, `logo`, `deskripsi_survei`, `updated_at`) VALUES
(1, 'Dinas Kesehatan Kota Bandung', 'eSKM - Survei Kepuasan Masyarakat', 'Jl. Supratman No. 73, Kota Bandung, Jawa Barat',
 'Kepala Dinas Kesehatan Kota Bandung', '-', 'logo.png',
 'Kuesioner ini digunakan untuk mengukur tingkat kepuasan masyarakat terhadap layanan yang diberikan oleh Dinas Kesehatan Kota Bandung, sesuai dengan Peraturan Menteri PANRB Nomor 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat. Mohon kesediaan Bapak/Ibu/Saudara untuk mengisi survei ini dengan jujur dan objektif. Jawaban Anda bersifat rahasia dan hanya digunakan untuk keperluan peningkatan mutu pelayanan.',
 NOW());

-- ---------------------------------------------------------------------
-- Tabel: tb_admin
-- Akun untuk mengakses dashboard admin
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default login: username = admin | password = admin123
-- (hash di bawah dibuat dengan password_hash('admin123', PASSWORD_BCRYPT))
INSERT INTO `tb_admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `is_active`, `created_at`) VALUES
(1, 'admin', '$2y$12$0xTnmwcLchRmNeZfE37GFOVhyVaXoLvqiejYbpt7r.VeI29lLsJrO', 'Administrator SKM', 'admin@bandung.go.id', 1, NOW());

-- ---------------------------------------------------------------------
-- Tabel: tb_unsur
-- 9 Unsur Pelayanan sesuai Permenpan RB No. 14 Tahun 2017
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_unsur`;
CREATE TABLE `tb_unsur` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode` VARCHAR(5) NOT NULL,
  `pertanyaan` TEXT NOT NULL,
  `urutan` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_unsur` (`id`, `kode`, `pertanyaan`, `urutan`) VALUES
(1, 'U1', 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya?', 1),
(2, 'U2', 'Bagaimana pemahaman Saudara tentang kemudahan prosedur pelayanan di unit ini?', 2),
(3, 'U3', 'Bagaimana pendapat Saudara tentang kecepatan waktu dalam memberikan pelayanan?', 3),
(4, 'U4', 'Bagaimana pendapat Saudara tentang kewajaran biaya/tarif dalam pelayanan?', 4),
(5, 'U5', 'Bagaimana pendapat Saudara tentang kesesuaian produk pelayanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan?', 5),
(6, 'U6', 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam pelayanan?', 6),
(7, 'U7', 'Bagaimana pendapat Saudara tentang perilaku petugas dalam pelayanan terkait kesopanan dan keramahan?', 7),
(8, 'U8', 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana?', 8),
(9, 'U9', 'Bagaimana pendapat Saudara tentang penanganan pengaduan, saran dan masukan pengguna layanan?', 9);

-- ---------------------------------------------------------------------
-- Tabel: tb_unsur_pilihan
-- Pilihan jawaban (nilai 1-4) untuk masing-masing unsur, persis Google Form
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_unsur_pilihan`;
CREATE TABLE `tb_unsur_pilihan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `unsur_id` INT(11) NOT NULL,
  `nilai` TINYINT(1) NOT NULL,
  `label` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unsur_id` (`unsur_id`),
  CONSTRAINT `fk_pilihan_unsur` FOREIGN KEY (`unsur_id`) REFERENCES `tb_unsur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_unsur_pilihan` (`unsur_id`, `nilai`, `label`) VALUES
(1,1,'Tidak Sesuai'), (1,2,'Kurang Sesuai'), (1,3,'Sesuai'), (1,4,'Sangat Sesuai'),
(2,1,'Tidak Mudah'), (2,2,'Kurang Mudah'), (2,3,'Mudah'), (2,4,'Sangat Mudah'),
(3,1,'Tidak Cepat'), (3,2,'Kurang Cepat'), (3,3,'Cepat'), (3,4,'Sangat Cepat'),
(4,1,'Sangat Mahal'), (4,2,'Cukup Mahal'), (4,3,'Murah'), (4,4,'Gratis'),
(5,1,'Tidak Sesuai'), (5,2,'Kurang Sesuai'), (5,3,'Sesuai'), (5,4,'Sangat Sesuai'),
(6,1,'Tidak Kompeten'), (6,2,'Kurang Kompeten'), (6,3,'Kompeten'), (6,4,'Sangat Kompeten'),
(7,1,'Tidak Sopan dan Ramah'), (7,2,'Kurang Sopan dan Ramah'), (7,3,'Sopan dan Ramah'), (7,4,'Sangat Sopan dan Ramah'),
(8,1,'Buruk'), (8,2,'Cukup'), (8,3,'Baik'), (8,4,'Sangat Baik'),
(9,1,'Tidak Ada'), (9,2,'Ada tetapi Tidak Berfungsi'), (9,3,'Berfungsi Kurang Maksimal'), (9,4,'Dikelola dengan Baik');

-- ---------------------------------------------------------------------
-- Tabel: tb_responden
-- Data responden yang mengisi survei
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_responden`;
CREATE TABLE `tb_responden` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_responden` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(150) DEFAULT NULL,
  `jenis_kelamin` ENUM('L','P') DEFAULT NULL,
  `usia` INT(3) DEFAULT NULL,
  `pendidikan` VARCHAR(50) DEFAULT NULL,
  `pekerjaan` VARCHAR(100) DEFAULT NULL,
  `unit_layanan` VARCHAR(150) DEFAULT NULL,
  `saran` TEXT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_responden` (`kode_responden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Tabel: tb_jawaban
-- Jawaban responden untuk setiap unsur (nilai 1-4)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `tb_jawaban`;
CREATE TABLE `tb_jawaban` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `responden_id` INT(11) NOT NULL,
  `unsur_id` INT(11) NOT NULL,
  `nilai` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `responden_id` (`responden_id`),
  KEY `unsur_id` (`unsur_id`),
  CONSTRAINT `fk_jawaban_responden` FOREIGN KEY (`responden_id`) REFERENCES `tb_responden` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jawaban_unsur` FOREIGN KEY (`unsur_id`) REFERENCES `tb_unsur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
