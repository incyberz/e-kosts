-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2023 at 04:53 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ekost`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenis_trx_bayar`
--

CREATE TABLE `tb_jenis_trx_bayar` (
  `id` tinyint(11) NOT NULL,
  `jenis_trx_bayar` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_jenis_trx_bayar`
--

INSERT INTO `tb_jenis_trx_bayar` (`id`, `jenis_trx_bayar`) VALUES
(1, 'Sewa Baru'),
(2, 'Perpanjangan'),
(3, 'Terima Kunci');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenis_trx_kunci`
--

CREATE TABLE `tb_jenis_trx_kunci` (
  `id` tinyint(11) NOT NULL,
  `jenis_trx_kunci` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_jenis_trx_kunci`
--

INSERT INTO `tb_jenis_trx_kunci` (`id`, `jenis_trx_kunci`) VALUES
(-1, 'Ambil Kunci dari Penyewa'),
(1, 'Beri Kunci ke Penyewa');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kamar`
--

CREATE TABLE `tb_kamar` (
  `id` int(11) NOT NULL,
  `no_kamar` tinyint(4) NOT NULL,
  `nama_kamar` varchar(100) DEFAULT NULL,
  `lokasi` varchar(500) DEFAULT NULL,
  `kondisi` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=baik; -1=rusak',
  `tarif` int(11) NOT NULL,
  `deskripsi` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_kamar`
--

INSERT INTO `tb_kamar` (`id`, `no_kamar`, `nama_kamar`, `lokasi`, `kondisi`, `tarif`, `deskripsi`) VALUES
(1, 1, 'Mawar-01', NULL, 1, 500000, 'Kamar mandi di luar'),
(2, 2, 'Mawar-02', NULL, 1, 500000, NULL),
(3, 3, 'Mawar-03', NULL, 1, 500000, NULL),
(4, 4, 'Mawar-04', NULL, 1, 500000, NULL),
(5, 5, 'Mawar-05', NULL, 1, 500000, NULL),
(6, 6, 'Tulip-01', NULL, 1, 500000, NULL),
(7, 7, 'Tulip-02', NULL, 1, 500000, NULL),
(8, 8, 'Tulip-03', NULL, 1, 500000, NULL),
(9, 9, 'Tulip-04', NULL, 1, 500000, NULL),
(10, 10, 'Tulip-05', NULL, 1, 500000, NULL),
(11, 11, 'Rose-01', NULL, 1, 500000, NULL),
(12, 12, 'Rose-02', NULL, 1, 500000, NULL),
(13, 13, 'Rose-03', NULL, 1, 500000, NULL),
(14, 14, 'Rose-04', NULL, 1, 500000, NULL),
(15, 15, 'Rose-05', NULL, -1, 500000, NULL),
(16, 16, 'Bougenville-01', NULL, 1, 500000, NULL),
(17, 17, 'Bougenville-02', NULL, 1, 500000, NULL),
(18, 18, 'Bougenville-03', NULL, -1, 500000, NULL),
(19, 19, 'Bougenville-04', NULL, 1, 500000, NULL),
(20, 20, 'Bougenville-05', NULL, 1, 500000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_penyewa`
--

CREATE TABLE `tb_penyewa` (
  `id` int(11) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `no_ktp` char(16) NOT NULL,
  `no_wa` varchar(13) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_penyewa`
--

INSERT INTO `tb_penyewa` (`id`, `nama_penyewa`, `no_ktp`, `no_wa`, `no_hp`, `date_created`) VALUES
(1, 'Ahmad', '1536614478652761', '1536614478652', '1536614478652', '2023-02-10 01:45:34'),
(2, 'Budi', '6035637280968685', '6035637280968', '6035637280968', '2023-02-10 01:45:34'),
(3, 'Charlie', '4446342671709314', '4446342671709', '4446342671709', '2023-02-10 01:45:34'),
(4, 'Deni', '6718109662868259', '6718109662868', '6718109662868', '2023-02-10 01:45:34'),
(5, 'Erwin Gutawa', '3211111106870004', '087729007318', '087729007318', '2023-02-10 01:45:34'),
(6, 'Fajar', '2549600616476984', '2549600616476', '2549600616476', '2023-02-10 01:45:34'),
(7, 'Gilang', '5478299812631897', '5478299812631', '5478299812631', '2023-02-10 01:45:34'),
(8, 'Haris', '7126376242330163', '7126376242330', '7126376242330', '2023-02-10 01:45:34'),
(9, 'Indah', '5006744001398600', '5006744001398', '5006744001398', '2023-02-10 01:45:34'),
(10, 'Joni', '2807228828427187', '2807228828427', '2807228828427', '2023-02-10 01:45:34'),
(11, 'Kiki', '3121913041267591', '3121913041267', '3121913041267', '2023-02-10 01:45:34'),
(12, 'Lina', '2416355180382324', '2416355180382', '2416355180382', '2023-02-10 01:45:34'),
(13, 'Marni', '3138358874170458', '3138358874170', '3138358874170', '2023-02-10 01:45:34'),
(14, 'Nono', '4022343277501564', '4022343277501', '4022343277501', '2023-02-10 01:45:34'),
(15, 'Oman', '7978441115327433', '7978441115327', '7978441115327', '2023-02-10 01:45:34'),
(16, 'Parjo', '9968397735498331', '9968397735498', '9968397735498', '2023-02-10 01:45:42'),
(17, 'Queen Elizabeth', '5836135722171618', '5836135722171', '5836135722171', '2023-02-10 01:45:34'),
(18, 'Rita', '5727580990346017', '5727580990346', '5727580990346', '2023-02-10 01:45:34'),
(19, 'Sari Latifah Arum', '5293145653430770', '5293145653430', '5293145653430', '2023-02-10 01:45:34'),
(20, 'Titin Sopiah', '5258152821925322', '5258152821925', '5258152821925', '2023-02-10 01:45:34'),
(21, 'Uun Sopiah', '7423661002870012', '6281777666555', '6281777666555', '2023-02-10 02:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `tb_trx`
--

CREATE TABLE `tb_trx` (
  `id` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `id_penyewa` int(11) NOT NULL,
  `id_jenis_trx` tinyint(1) NOT NULL,
  `tanggal_trx` timestamp NOT NULL DEFAULT current_timestamp(),
  `jatuh_tempo` date NOT NULL,
  `id_trx_sebelumnya` int(11) DEFAULT NULL,
  `periode` char(4) NOT NULL,
  `nominal` int(11) NOT NULL,
  `dibayar_oleh` varchar(50) NOT NULL DEFAULT 'Penyewa',
  `last_notif` timestamp NULL DEFAULT NULL,
  `bayar_via` char(1) NOT NULL DEFAULT 'c',
  `kunci_dipinjam` tinyint(1) NOT NULL DEFAULT -1,
  `tanggal_kembali_kunci` timestamp NULL DEFAULT NULL,
  `keterangan_trx_kunci` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_trx`
--

INSERT INTO `tb_trx` (`id`, `id_kamar`, `id_penyewa`, `id_jenis_trx`, `tanggal_trx`, `jatuh_tempo`, `id_trx_sebelumnya`, `periode`, `nominal`, `dibayar_oleh`, `last_notif`, `bayar_via`, `kunci_dipinjam`, `tanggal_kembali_kunci`, `keterangan_trx_kunci`) VALUES
(5, 2, 2, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(6, 3, 3, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(7, 4, 4, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(8, 5, 5, 1, '2023-02-01 06:05:34', '2023-02-04', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(9, 6, 6, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(10, 7, 7, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(11, 8, 8, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(12, 9, 9, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(13, 10, 10, 1, '2023-02-01 06:05:34', '2023-02-01', NULL, '0223', 500000, 'Penyewa', '2023-02-08 19:10:40', 'c', 1, NULL, NULL),
(14, 11, 11, 1, '2023-02-01 06:05:34', '2023-01-31', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(15, 12, 12, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(16, 13, 13, 1, '2023-02-01 06:05:34', '2023-02-13', NULL, '0223', 500000, 'Penyewa', '2023-02-11 02:23:11', 'c', 1, NULL, NULL),
(17, 14, 14, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(18, 15, 15, 1, '2023-02-01 06:05:34', '2023-02-11', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(19, 16, 16, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(20, 17, 17, 1, '2023-02-01 06:05:34', '2023-02-09', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(21, 18, 18, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(22, 19, 19, 1, '2023-02-01 06:05:34', '2023-02-07', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(23, 20, 20, 1, '2023-02-01 06:05:34', '2023-03-01', NULL, '0223', 500000, 'Penyewa', NULL, 'c', 1, NULL, NULL),
(38, 2, 2, 2, '2023-02-10 16:19:09', '2023-04-01', 5, '0423', 500000, 'Budi', NULL, 't', 1, NULL, NULL),
(39, 1, 1, 1, '2023-02-11 02:17:09', '2023-03-11', NULL, '0323', 500000, 'Ahmad', '2023-02-11 02:17:39', 't', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_jenis_trx_bayar`
--
ALTER TABLE `tb_jenis_trx_bayar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_jenis_trx_kunci`
--
ALTER TABLE `tb_jenis_trx_kunci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kamar`
--
ALTER TABLE `tb_kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_penyewa`
--
ALTER TABLE `tb_penyewa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_trx`
--
ALTER TABLE `tb_trx`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kamar` (`id_kamar`),
  ADD KEY `id_penyewa` (`id_penyewa`),
  ADD KEY `id_jenis_trx` (`id_jenis_trx`),
  ADD KEY `id_trx_sebelumnya` (`id_trx_sebelumnya`),
  ADD KEY `kunci_dipinjam` (`kunci_dipinjam`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_kamar`
--
ALTER TABLE `tb_kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_penyewa`
--
ALTER TABLE `tb_penyewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_trx`
--
ALTER TABLE `tb_trx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_trx`
--
ALTER TABLE `tb_trx`
  ADD CONSTRAINT `tb_trx_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `tb_kamar` (`id`),
  ADD CONSTRAINT `tb_trx_ibfk_2` FOREIGN KEY (`id_penyewa`) REFERENCES `tb_penyewa` (`id`),
  ADD CONSTRAINT `tb_trx_ibfk_3` FOREIGN KEY (`id_jenis_trx`) REFERENCES `tb_jenis_trx_bayar` (`id`),
  ADD CONSTRAINT `tb_trx_ibfk_4` FOREIGN KEY (`id_trx_sebelumnya`) REFERENCES `tb_trx` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tb_trx_ibfk_5` FOREIGN KEY (`kunci_dipinjam`) REFERENCES `tb_jenis_trx_kunci` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
