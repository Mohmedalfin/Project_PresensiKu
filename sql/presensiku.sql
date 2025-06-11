-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 01:04 PM
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
-- Database: `presensiku`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `jabatan`) VALUES
(8, 'Admin Office'),
(9, 'Fullsatck'),
(11, 'Data Analysist'),
(12, 'UI/UX Design'),
(13, 'Manager ');

-- --------------------------------------------------------

--
-- Table structure for table `ketidakhadiran`
--

CREATE TABLE `ketidakhadiran` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(225) NOT NULL,
  `file` varchar(100) NOT NULL,
  `status_pengajuan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketidakhadiran`
--

INSERT INTO `ketidakhadiran` (`id`, `id_pegawai`, `keterangan`, `tanggal`, `deskripsi`, `file`, `status_pengajuan`) VALUES
(10, 10, 'Izin', '2025-06-03', '                            Ada keperluan keluarga (Istri Lahiran)                        ', 'nama_file', 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(225) NOT NULL,
  `alamat_lokasi` varchar(225) NOT NULL,
  `tipe_lokasi` varchar(225) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(1, 'Kantor Pusat', 'Jl. Kuningan Jawa Barat', 'Pusat', ' -7.748018', ' 110.355391', 10000, 'WIB', '07:30:00', '15:30:00'),
(7, 'Kantor Cabang', 'Jl. Cirebon, Jawa barat', 'Cabang', '-7.746481', '110.360096', 1000, 'WIB', '00:10:00', '02:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `lokasi_presensi` varchar(50) NOT NULL,
  `foto` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `name`, `jenis_kelamin`, `alamat`, `no_handphone`, `jabatan`, `lokasi_presensi`, `foto`) VALUES
(9, 'PEG-0006', 'Fawwaz', 'Laki-Laki', 'Cirebon', '081224389994', 'Admin', 'Kantor Pusat', '1748352582_Iam.jpg'),
(10, 'PEG-0007', 'Alfin', 'Laki-Laki', 'Kuningan', '081224389994', 'Fullsatck', 'Kantor Pusat', '1748353144_Iam.jpg'),
(11, 'PEG-0008', 'Januar', 'Laki-Laki', 'Ciamis Jabar', '081224389994', 'Fullsatck', 'Kantor Pusat', '1748540608_5.jpg'),
(12, 'PEG-0009', 'Khansa', 'Perempuan', 'Bantul', '081224389994', 'Admin', 'Kantor Pusat', '1748540653_Foto Saya.jpg'),
(13, 'PEG-0010', 'Arya', 'Laki-Laki', 'NTB', '081224389994', 'Fullsatck', 'Kantor Cabang', '1748540695_vito.jpg'),
(14, 'PEG-0011', 'Syahrul', 'Laki-Laki', 'Malang', '081224389994', 'Admin Office', 'Kantor Pusat', '1748586099_Kel_Pemuda.png'),
(15, 'PEG-0012', 'Arsy Attar', 'Laki-Laki', 'Medan', '081224389994', 'Data Analysist', 'Kantor Cabang', '1748824250_Prabu.jpg'),
(16, 'PEG-0013', 'Agis Maulana', 'Perempuan', 'Subang', '081224389994', 'Manager ', 'Kantor Cabang', '1749098060_vito.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `foto_masuk` varchar(225) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jam_keluar` time NOT NULL,
  `foto_keluar` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `id_pegawai`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`, `tanggal_keluar`, `jam_keluar`, `foto_keluar`) VALUES
(23, 10, '2025-05-30', '00:31:32', 'masuk_20250529_193136.png', '2025-05-30', '16:15:44', 'keluar_20250530_111551.png'),
(24, 13, '2025-05-30', '00:45:10', 'masuk_20250529_194517.png', '2025-05-30', '00:45:17', 'keluar_20250529_194522.png'),
(25, 11, '2025-05-30', '00:45:34', 'masuk_20250529_194539.png', '2025-05-30', '00:45:39', 'keluar_20250529_194543.png'),
(26, 12, '2025-05-30', '00:45:52', 'masuk_20250529_194558.png', '2025-05-30', '00:45:58', 'keluar_20250529_194604.png'),
(27, 14, '2025-06-02', '13:21:55', 'masuk_20250530_082205.png', '2025-05-30', '13:22:05', 'keluar_20250530_082213.png'),
(28, 10, '2025-06-03', '21:49:50', 'masuk_20250603_164955.png', '0000-00-00', '00:00:00', ''),
(29, 10, '2025-06-05', '11:26:17', 'masuk_20250605_062734.png', '0000-00-00', '00:00:00', ''),
(30, 10, '2025-06-11', '18:01:10', 'masuk_20250611_130119.png', '0000-00-00', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(300) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_pegawai`, `username`, `password`, `status`, `role`) VALUES
(8, 9, 'Fawwaz93', '$2y$10$ofzYRsye1Mb1OgXGlqNf5eM11rfhnft6yWvcmR3qa/EHRC39q/Wqy', 'Aktif', 'admin'),
(11, 10, 'Alfin17', '$2y$10$8JJ6bdOEZ77tdQ64Z0wjOu2/GUj6XkuwV4FL8DoVJDW69/A6QbMtO', 'Aktif', 'pegawai'),
(14, 13, 'Syahrul17', '$2y$10$eGioMBQBo1SIAaMOAD2zMOodn3fNa8uIxbQ5dDpC7RfxFU90gSPVK', ' Aktif', 'pegawai'),
(15, 14, 'Attar17', '$2y$10$tw17FQS0pcD2nd9PmeaqcungnNIFwAe1mzur9JsPtOu6g0dF73Ify', ' Aktif', 'pegawai'),
(16, 15, 'Attar17', '$2y$10$tw17FQS0pcD2nd9PmeaqcungnNIFwAe1mzur9JsPtOu6g0dF73Ify', 'Aktif', 'pegawai'),
(17, 16, 'agisgasss', '$2y$10$8Fc/atSdbD231EWtLGuKWehFq8HoQd/O9G7/vlSsNS8fkkctENxxa', 'Aktif', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_pegawai` (`id_pegawai`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD CONSTRAINT `fk_id_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
