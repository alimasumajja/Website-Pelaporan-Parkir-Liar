-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 09:17 AM
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
-- Database: `parkir_liar`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `alamat_lokasi` text DEFAULT NULL,
  `status` enum('Dikirim','Diverifikasi','Diproses','Ditindak','Selesai','Ditolak') DEFAULT 'Dikirim',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `id_user`, `foto`, `deskripsi`, `latitude`, `longitude`, `alamat_lokasi`, `status`, `created_at`) VALUES
(1, 2, 'laporan_1779776126_4311.jpg', 'parkir liar memenuhi trotoar dan mengganggu pejalan kaki', '-6.7138', '108.5509', 'Jl. kh maksum', 'Diproses', '2026-05-26 06:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `status_laporan`
--

CREATE TABLE `status_laporan` (
  `id_status` int(11) NOT NULL,
  `id_laporan` int(11) NOT NULL,
  `status` enum('Dikirim','Diverifikasi','Diproses','Ditindak','Selesai','Ditolak') NOT NULL,
  `catatan` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_laporan`
--

INSERT INTO `status_laporan` (`id_status`, `id_laporan`, `status`, `catatan`, `updated_by`, `created_at`) VALUES
(1, 1, 'Dikirim', 'Laporan berhasil dikirim', NULL, '2026-05-26 06:15:26'),
(2, 1, 'Dikirim', 'Status laporan diperbarui menjadi Dikirim', NULL, '2026-05-26 07:13:50'),
(3, 1, 'Diproses', 'Status laporan diperbarui menjadi Diproses', NULL, '2026-05-26 07:14:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password`, `no_hp`, `alamat`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, NULL, 'admin', '2026-05-26 01:50:24'),
(2, 'Ali Masum', 'ali@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'user', '2026-05-26 02:12:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `status_laporan`
--
ALTER TABLE `status_laporan`
  ADD PRIMARY KEY (`id_status`),
  ADD KEY `id_laporan` (`id_laporan`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status_laporan`
--
ALTER TABLE `status_laporan`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `status_laporan`
--
ALTER TABLE `status_laporan`
  ADD CONSTRAINT `status_laporan_ibfk_1` FOREIGN KEY (`id_laporan`) REFERENCES `laporan` (`id_laporan`) ON DELETE CASCADE,
  ADD CONSTRAINT `status_laporan_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
