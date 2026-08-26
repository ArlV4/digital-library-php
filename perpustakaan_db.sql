-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for perpustakaan_db
CREATE DATABASE IF NOT EXISTS `perpustakaan_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `perpustakaan_db`;

-- Dumping structure for table perpustakaan_db.buku
DROP TABLE IF EXISTS `buku`;
CREATE TABLE IF NOT EXISTS `buku` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `pengarang` varchar(150) NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `tahun_terbit` int DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_db.buku: ~4 rows (approximately)
INSERT INTO `buku` (`id`, `judul`, `pengarang`, `kategori_id`, `tahun_terbit`, `cover`, `file_pdf`, `deskripsi`, `created_at`) VALUES
	(1, 'Atomic Habits', 'James Clear', 4, 2018, '1778426150_40121378.jpg', 'atomic-habits-versi-indonesiajames-cleartagt-pdf_text.pdf', 'Perubahan-perubahan kecil yang memberikan hasil luar biasa dalam membangun kebiasaan baik.', '2026-08-26 14:23:28'),
	(2, 'Belajar Meningkatkan Kualitas Diri', 'Alvi Syahrin', 4, 2023, '1778426981_Cuplikan layar 2026-05-10 222714.png', '1778426981_Belajar Meningkatkan Kualitas Diri - Alvi Syahrin.pdf', 'Buku panduan praktis untuk pengembangan diri dan motivasi.', '2026-08-26 14:23:28'),
	(3, 'Sebuah Seni untuk Meyakinkan Orang', 'Richard Shell & Mario Moussa', 4, 2021, '1778427885_Cuplikan layar 2026-05-10 223955.png', '1778427885_Sebuah Seni untuk Meyakinkan Orang G Richard Shell & Mario Moussa.pdf', 'Teknik komunikasi dan negosiasi yang efektif di dunia profesional.', '2026-08-26 14:23:28'),
	(4, 'Buku Geografi SMA Kelas X', 'Kemdikbud', 2, 2022, '1778429358_Cuplikan layar 2026-05-10 230518.png', '1778429358_BUKU_GEOGRAFI_UNTUK_KELAS_X_SMA.pdf', 'Buku pegangan siswa untuk mata pelajaran Geografi kelas 10.', '2026-08-26 14:23:28');

-- Dumping structure for table perpustakaan_db.kategori
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_db.kategori: ~8 rows (approximately)
INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
	(1, 'Teknologi & Komputer'),
	(2, 'Sains & Matematika'),
	(3, 'Fiksi & Sastra'),
	(4, 'Pengembangan Diri'),
	(5, 'Sejarah'),
	(6, 'Kesehatan'),
	(7, 'Fiksi'),
	(8, 'Pelajaran');

-- Dumping structure for table perpustakaan_db.peminjaman
DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `buku_id` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam',
  `denda` int DEFAULT '0',
  `status_denda` enum('belum_lunas','lunas') DEFAULT 'lunas',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `buku_id` (`buku_id`),
  CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_db.peminjaman: ~2 rows (approximately)
INSERT INTO `peminjaman` (`id`, `user_id`, `buku_id`, `tanggal_pinjam`, `tanggal_kembali`, `tanggal_dikembalikan`, `status`, `denda`, `status_denda`) VALUES
	(1, 3, 1, '2026-08-26', '2026-09-02', NULL, 'dipinjam', 0, 'lunas'),
	(2, 6, 1, '2026-08-26', '2026-09-02', NULL, 'dipinjam', 0, 'lunas');

-- Dumping structure for table perpustakaan_db.unblock_request
DROP TABLE IF EXISTS `unblock_request`;
CREATE TABLE IF NOT EXISTS `unblock_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `unblock_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_db.unblock_request: ~0 rows (approximately)

-- Dumping structure for table perpustakaan_db.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','member') COLLATE utf8mb4_general_ci DEFAULT 'member',
  `status` enum('aktif','diblokir') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table perpustakaan_db.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `status`, `created_at`) VALUES
	(3, 'aril', 'aril@gmail.com', '$2y$10$wbqeV2u9uLxaogX6nl77n.SeEu9Aws4EuovXycH9J9//MeNkE/j3S', 'admin', 'aktif', '2026-08-26 13:35:22'),
	(6, 'Ahbeng', 'ahbeng@gaming.com', '$2y$10$vaUXs04I/T1XsFCddjF1V.FCyHYrFPZyXglleRRptXAh1OTyOG16i', 'member', 'aktif', '2026-08-26 13:42:40'),
	(9, 'Administrator', 'admin@perpus.com', '$2y$10$iMZZ0xK9uN8xR7lK3g9wqumS8C0k5u9hW.cZ.j4qEaMhJom6m0F7e', 'admin', 'aktif', '2026-08-26 13:59:42');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
