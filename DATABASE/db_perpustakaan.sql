-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260130.4d5432a85e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2026 at 01:12 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int NOT NULL,
  `id_penerbit` int NOT NULL,
  `id_kategori` int NOT NULL,
  `judul_buku` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tahun_terbit` year NOT NULL,
  `harga_buku` int NOT NULL DEFAULT '0',
  `stok` int NOT NULL DEFAULT '0',
  `cover` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `id_penerbit`, `id_kategori`, `judul_buku`, `tahun_terbit`, `harga_buku`, `stok`, `cover`) VALUES
(1, 1, 1, 'Pemrograman PHP Dasar', '2022', 85000, 5, ''),
(2, 2, 3, 'Pemrograman Web dengan PHP', '2021', 95000, 4, ''),
(3, 1, 2, 'Belajar MySQL untuk Pemula', '2023', 90000, 6, ''),
(4, 3, 1, 'Pemrograman Berorientasi Objek', '2022', 100000, 9, ''),
(5, 2, 1, 'Dasar-Dasar Laravel', '2024', 110000, 5, ''),
(6, 3, 3, 'Membangun Website Modern', '2023', 105000, 5, ''),
(7, 1, 4, 'Algoritma dan Pemrograman', '2021', 80000, 7, ''),
(8, 4, 2, 'Basis Data untuk Pemula', '2022', 88000, 5, ''),
(9, 4, 3, 'HTML CSS dan JavaScript', '2023', 92000, 6, ''),
(10, 5, 9, 'Belajar Pemrograman Mobile', '2024', 115000, 20, ''),
(21, 12, 12, 'THE PSYCHOLOGY OF MONEY', '2014', 20000, 6, 'cover_6aa0ac6d6c5ca7.87431288.jpg'),
(22, 13, 13, 'Dia Adalah Dilanku Tahun 1990 (Dilan 1990)', '2014', 2000000, 18, 'cover_6aa0ad6cb69dd3.90579731.jpg'),
(23, 13, 13, 'Milea: Suara dari Dilan', '2016', 160000, 10, 'cover_6aa33a0d1a6777.01644088.jpg'),
(24, 13, 13, 'Ancika: Dia yang Bersamaku Tahun 1995', '2021', 159999, 19, 'cover_6aa689535832b0.47884805.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `buku_penulis`
--

CREATE TABLE `buku_penulis` (
  `id_buku` int NOT NULL,
  `id_penulis` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku_penulis`
--

INSERT INTO `buku_penulis` (`id_buku`, `id_penulis`) VALUES
(3, 3),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(21, 12),
(22, 13),
(23, 13),
(24, 13);

-- --------------------------------------------------------

--
-- Table structure for table `denda`
--

CREATE TABLE `denda` (
  `id_denda` int NOT NULL,
  `id_detail` int NOT NULL,
  `jenis_denda` varchar(255) DEFAULT NULL,
  `jumlah_hari_terlambat` int NOT NULL DEFAULT '0',
  `jumlah_denda` int NOT NULL DEFAULT '0',
  `tanggal_denda` date DEFAULT NULL,
  `status_pembayaran` enum('Belum Dibayar','Sudah Dibayar') NOT NULL DEFAULT 'Belum Dibayar',
  `keterangan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `denda`
--

INSERT INTO `denda` (`id_denda`, `id_detail`, `jenis_denda`, `jumlah_hari_terlambat`, `jumlah_denda`, `tanggal_denda`, `status_pembayaran`, `keterangan`) VALUES
(1, 3, 'Terlambat', 2, 10000, '2026-08-14', 'Sudah Dibayar', 'Pengembalian terlambat 2 hari'),
(2, 4, 'Terlambat', 1, 5000, '2026-08-16', 'Sudah Dibayar', 'Pengembalian terlambat 1 hari'),
(3, 5, 'Rusak', 0, 55000, '2026-08-17', 'Belum Dibayar', 'Buku mengalami kerusakan'),
(4, 6, 'Terlambat 22 Hari', 0, 22000, NULL, 'Belum Dibayar', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int NOT NULL,
  `id_peminjaman` int NOT NULL,
  `id_buku` int NOT NULL,
  `jumlah_buku` int NOT NULL,
  `tanggal_pengembalian` date DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `status` enum('Diajukan','Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Diajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_buku`, `jumlah_buku`, `tanggal_pengembalian`, `tanggal_dikembalikan`, `status`) VALUES
(1, 1, 1, 1, '2026-08-08', '2026-08-07', 'Dikembalikan'),
(2, 2, 2, 1, '2026-08-10', '2026-08-10', 'Dikembalikan'),
(3, 3, 3, 1, '2026-08-12', '2026-08-14', 'Dikembalikan'),
(4, 4, 4, 1, '2026-08-17', '2026-08-16', 'Dikembalikan'),
(5, 4, 5, 1, '2026-08-17', '2026-08-17', 'Dikembalikan'),
(6, 5, 6, 1, '2026-08-22', '2026-09-13', 'Dikembalikan'),
(7, 6, 7, 1, '2026-08-25', '2026-09-09', 'Dipinjam'),
(14, 14, 23, 1, '2026-09-14', NULL, 'Dipinjam'),
(15, 15, 10, 1, '2026-09-13', '2026-09-13', 'Dikembalikan'),
(16, 16, 23, 1, '2026-09-13', '2026-09-13', 'Dikembalikan'),
(17, 16, 22, 1, '2026-09-13', '2026-09-13', 'Dikembalikan'),
(18, 17, 21, 3, '2026-09-13', NULL, 'Dipinjam'),
(19, 18, 24, 1, '2026-09-13', NULL, 'Dipinjam'),
(20, 18, 23, 1, '2026-09-13', NULL, 'Dipinjam'),
(21, 18, 22, 1, '2026-09-13', NULL, 'Dipinjam'),
(22, 18, 21, 1, '2026-09-13', NULL, 'Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `histori_transaksi`
--

CREATE TABLE `histori_transaksi` (
  `id_histori` int NOT NULL,
  `id_peminjaman` int NOT NULL,
  `id_detail` int NOT NULL,
  `nis` int NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `id_buku` int NOT NULL,
  `judul_buku` varchar(70) NOT NULL,
  `harga_buku` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `tanggal_dikembalikan` date NOT NULL,
  `jenis_denda` varchar(255) DEFAULT NULL,
  `denda` int NOT NULL DEFAULT '0',
  `tanggal_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `histori_transaksi`
--

INSERT INTO `histori_transaksi` (`id_histori`, `id_peminjaman`, `id_detail`, `nis`, `nama_siswa`, `id_buku`, `judul_buku`, `harga_buku`, `tanggal_pinjam`, `tanggal_pengembalian`, `tanggal_dikembalikan`, `jenis_denda`, `denda`, `tanggal_selesai`) VALUES
(1, 15, 15, 7532159, 'galih rusli', 10, 'Belajar Pemrograman Mobile', 115000, '2026-09-13', '2026-09-13', '2026-09-13', 'Tidak Ada', 0, '2026-09-13'),
(2, 16, 16, 454545, 'M Eka Santika', 23, 'Milea: Suara dari Dilan', 123132131, '2026-09-13', '2026-09-13', '2026-09-13', 'Tidak Ada', 0, '2026-09-13'),
(3, 16, 17, 454545, 'M Eka Santika', 22, 'Dia Adalah Dilanku Tahun 1990 (Dilan 1990)', 2000000, '2026-09-13', '2026-09-13', '2026-09-13', 'Tidak Ada', 0, '2026-09-13'),
(4, 5, 6, 20260005, 'Eka Putri', 6, 'Membangun Website Modern', 105000, '2026-08-15', '2026-08-22', '2026-09-13', 'Terlambat 22 Hari', 22000, '2026-09-13');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `status`) VALUES
(1, 'Pemrograman', 'Buku mengenai pemrograman komputer', 'aktif'),
(2, 'Basis Data', 'Buku mengenai database dan pengelolaan data', 'aktif'),
(3, 'Web Development', 'Buku mengenai pengembangan website', 'aktif'),
(4, 'Algoritma', 'Buku mengenai algoritma dan logika pemrograman', 'aktif'),
(5, 'Jaringan', 'Buku mengenai jaringan komputer', 'aktif'),
(6, 'Sistem Informasi', 'Buku mengenai sistem informasi', 'aktif'),
(7, 'Desain', 'Buku mengenai desain dan UI/UX', 'aktif'),
(8, 'Komputer Dasar', 'Buku mengenai dasar-dasar komputer', 'aktif'),
(9, 'Mobile Development', 'Buku mengenai pengembangan aplikasi mobile', 'aktif'),
(10, 'Teknologi', 'Buku mengenai teknologi informasi', 'aktif'),
(12, 'KEUANGAN', 'UNTUK BUKU MENGENAI KEUANGAN', 'aktif'),
(13, 'NOVEL', 'MENGENAI NOVEL', 'aktif'),
(14, 'FIKSI', 'untuk buku FIKSI', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `status` enum('Diajukan','Dipinjam','Dikembalikan','Ditolak') NOT NULL DEFAULT 'Diajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `tanggal_pinjam`, `status`) VALUES
(1, 1, '2026-08-01', 'Dikembalikan'),
(2, 2, '2026-08-03', 'Dikembalikan'),
(3, 3, '2026-08-05', 'Dikembalikan'),
(4, 4, '2026-08-10', 'Dikembalikan'),
(5, 5, '2026-08-15', 'Dikembalikan'),
(6, 6, '2026-08-18', 'Dipinjam'),
(14, 14, '2026-09-13', 'Dipinjam'),
(15, 14, '2026-09-13', 'Dikembalikan'),
(16, 12, '2026-09-13', 'Dikembalikan'),
(17, 12, '2026-09-13', 'Dipinjam'),
(18, 10, '2026-09-13', 'Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int NOT NULL,
  `nama_penerbit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `kota` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `nama_penerbit`, `alamat`, `kota`, `telepon`, `email`) VALUES
(1, 'Informatika', 'Jl. Buah Batu No. 10', 'Bandung', '227001001', 'info@informatika.com'),
(2, 'Elex Media Komputindo', 'Jl. Palmerah Barat No. 29', 'Jakarta', '215486088', 'info@elexmedia.id'),
(3, 'Andi Publisher', 'Jl. Beo No. 38', 'Yogyakarta', '274588888', 'info@andipublisher.com'),
(4, 'Wahana Komputer', 'Jl. Imam Bonjol No. 27', 'Semarang', '243512345', 'info@wahanakomputer.com'),
(5, 'Gramedia Pustaka Utama', 'Jl. Palmerah Selatan No. 22', 'Jakarta', '821515546', 'info@gramedia.com'),
(6, 'Agromedia Pustaka', 'Jl. H. Montong No. 57', 'Jakarta', '217821234', 'info@agromedia.com'),
(7, 'Mediakom', 'Jl. Raya Condet No. 5', 'Jakarta', '218001234', 'info@mediakom.id'),
(8, 'Maxikom', 'Jl. Cempaka No. 20', 'Surabaya', '317001234', 'info@maxikom.com'),
(9, 'Lokomedia', 'Jl. Janti No. 45', 'Yogyakarta', '274456789', 'info@lokomedia.com'),
(10, 'Deepublish', 'Jl. Rajawali No. 10', 'Yogyakarta', '274555000', 'info@deepublish.com'),
(12, 'harimman house', 'jln sudirman', 'jakarta', '222', 'hariman@gmail.com'),
(13, 'Pastel Books', 'tidak diketahui', 'tidak diketahui', '23114684', 'PastelBooks@gmail.com'),
(5465464, 'GRAMEDIA', 'JLN JAKARTA', 'JAKARTA', '82156525', 'GRAMED@GMAIL.COM');

-- --------------------------------------------------------

--
-- Table structure for table `penulis`
--

CREATE TABLE `penulis` (
  `id_penulis` int NOT NULL,
  `nama_penulis` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penulis`
--

INSERT INTO `penulis` (`id_penulis`, `nama_penulis`) VALUES
(1, 'Abdul Kodir'),
(2, 'Budi Raharjo'),
(3, 'Rosa A.S.'),
(4, 'M. Shalahuddin'),
(5, 'Jubilee Enterprise'),
(6, 'Agus Saputra'),
(7, 'Kurniawan'),
(8, 'Madcoms'),
(9, 'Wahana Komputer'),
(10, 'Sukamto'),
(11, 'REZKA BAIQ'),
(12, 'MORGAR HOUSEL'),
(13, 'PIDI BAIQ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nis_nip` int NOT NULL,
  `nama_lengkap` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','siswa') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nis_nip`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 20260001, 'Andi Pratama', 'andi', '123456', 'siswa'),
(2, 20260002, 'Budi Santoso', 'budi', '123456', 'siswa'),
(3, 20260003, 'Citra Lestari', 'citra', '123456', 'siswa'),
(4, 20260004, 'Dimas Saputra', 'dimas', '123456', 'siswa'),
(5, 20260005, 'Eka Putri', 'eka', '$2y$12$HTNU1rN3S3ItSe2mmfYxY.iwaOBFG5HTEr/95DsJcOeeYgYmnfNOC', 'siswa'),
(6, 20260006, 'Fajar Maulana', 'fajar', '$2y$12$/F5OWg91eqgoqFZOEPv1lOXpNHtGSEyj7pTD6rwvchlMk94kS/fXi', 'siswa'),
(7, 20260007, 'Gilang Ramadhan', 'gilang', '123456', 'siswa'),
(8, 20260008, 'Hana Salsabila', 'hana', '123456', 'siswa'),
(9, 20260009, 'Intan Permata', 'intan', '$2y$12$p3Hmy7pS7aVqF//RQSHy2OystEtQjblHwxQNp5XAUXJxG8Hcq6E4m', 'siswa'),
(10, 20260010, 'Joko Setiawan', 'joko', '$2y$12$GE3XEMrO7o6bIXHjdaJfAOKXiWVzialhxRlb/UbQEqACMZEvoa2V2', 'siswa'),
(11, 123456, 'Admin Perpustakaan', 'admin', '$2y$12$cLfz55VwME1h1jSTWs5sveaXHr.C4GAozZGDNJJuoL4PC5uawYtMG', 'admin'),
(12, 454545, 'M Eka Santika', 'Ekan just', '$2y$12$uhDN3NYOYW5O3FH3OmWEZe4umVtmP9crnN/yEeOXliS26WZdlBABC', 'siswa'),
(14, 7532159, 'galih rusli', 'galih', '$2y$12$eDy0IOs9S/prMIvUcy.6me3YV8y3/Ata8YvPx51R4Bdjz2opuXeQO', 'siswa'),
(15, 60407, 'REZKA PARSHA AQEELA', 'REZKA SISWA', '$2y$12$cV7Wlpvb6FhG/XqLi3mtIeItBCGtsj9lMzP5tiXgKezwuSlQVL7XO', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `fk_buku_penerbit` (`id_penerbit`),
  ADD KEY `fk_buku_kategori` (`id_kategori`);

--
-- Indexes for table `buku_penulis`
--
ALTER TABLE `buku_penulis`
  ADD PRIMARY KEY (`id_buku`,`id_penulis`),
  ADD KEY `fk_buku_penulis_penulis` (`id_penulis`);

--
-- Indexes for table `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id_denda`),
  ADD KEY `fk_denda_detail` (`id_detail`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_detail_buku` (`id_buku`);

--
-- Indexes for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  ADD PRIMARY KEY (`id_histori`),
  ADD KEY `fk_histori_peminjaman` (`id_peminjaman`),
  ADD KEY `fk_histori_detail` (`id_detail`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_peminjaman_user` (`id_user`);

--
-- Indexes for table `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indexes for table `penulis`
--
ALTER TABLE `penulis`
  ADD PRIMARY KEY (`id_penulis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nis` (`nis_nip`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `denda`
--
ALTER TABLE `denda`
  MODIFY `id_denda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  MODIFY `id_histori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5465465;

--
-- AUTO_INCREMENT for table `penulis`
--
ALTER TABLE `penulis`
  MODIFY `id_penulis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_buku_penerbit` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `buku_penulis`
--
ALTER TABLE `buku_penulis`
  ADD CONSTRAINT `fk_buku_penulis_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_buku_penulis_penulis` FOREIGN KEY (`id_penulis`) REFERENCES `penulis` (`id_penulis`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `fk_denda_detail` FOREIGN KEY (`id_detail`) REFERENCES `detail_peminjaman` (`id_detail`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_detail_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  ADD CONSTRAINT `fk_histori_detail` FOREIGN KEY (`id_detail`) REFERENCES `detail_peminjaman` (`id_detail`),
  ADD CONSTRAINT `fk_histori_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
