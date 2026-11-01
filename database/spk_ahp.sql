-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2026 at 02:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_ahp`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatifs`
--

CREATE TABLE `alternatifs` (
  `id` int(11) NOT NULL,
  `kode_alternatif` varchar(255) NOT NULL,
  `nama_alternatif` varchar(255) NOT NULL,
  `skor_global` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alternatifs`
--

INSERT INTO `alternatifs` (`id`, `kode_alternatif`, `nama_alternatif`, `skor_global`, `created_at`, `updated_at`) VALUES
(1, 'A1', 'Ahmad Fauzi', NULL, '2026-10-28 19:35:52', '2026-10-28 19:35:52'),
(2, 'A2', 'Budi Santoso', NULL, '2026-10-28 19:35:52', '2026-10-28 19:35:52'),
(3, 'A3', 'Citra Dewi', NULL, '2026-10-28 19:35:52', '2026-10-28 19:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `kriterias`
--

CREATE TABLE `kriterias` (
  `id` int(11) NOT NULL,
  `kode_kriteria` varchar(255) NOT NULL,
  `nama_kriteria` varchar(255) NOT NULL,
  `bobot_prioritas` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriterias`
--

INSERT INTO `kriterias` (`id`, `kode_kriteria`, `nama_kriteria`, `bobot_prioritas`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Prestasi Akademik (IPK)', NULL, '2026-10-28 19:35:51', '2026-10-28 19:35:51'),
(2, 'C2', 'Karya Ilmiah & Inovasi', NULL, '2026-10-28 19:35:51', '2026-10-28 19:35:51'),
(3, 'C3', 'Aktivitas Organisasi & Kepemimpinan', NULL, '2026-10-28 19:35:52', '2026-10-28 19:35:52'),
(4, 'C4', 'Keahlian Bahasa & Komunikasi', NULL, '2026-10-28 19:35:52', '2026-10-28 19:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_matriks`
--

CREATE TABLE `nilai_matriks` (
  `id` int(11) NOT NULL,
  `tipe_matriks` varchar(255) NOT NULL,
  `elemen_baris_id` int(11) NOT NULL,
  `elemen_kolom_id` int(11) NOT NULL,
  `nilai_skala` double NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatifs`
--
ALTER TABLE `alternatifs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alternatifs_kode_alternatif_unique` (`kode_alternatif`);

--
-- Indexes for table `kriterias`
--
ALTER TABLE `kriterias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kriterias_kode_kriteria_unique` (`kode_kriteria`);

--
-- Indexes for table `nilai_matriks`
--
ALTER TABLE `nilai_matriks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilai_matriks_tipe_matriks_elemen_baris_id_elemen_kolom_id_index` (`tipe_matriks`,`elemen_baris_id`,`elemen_kolom_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatifs`
--
ALTER TABLE `alternatifs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kriterias`
--
ALTER TABLE `kriterias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nilai_matriks`
--
ALTER TABLE `nilai_matriks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
