-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 09:06 AM
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
-- Database: `umnevent`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `email`, `username`, `password`, `role`) VALUES
(1, 'admin@umn.ac.id.com', 'adminUMN', '$2a$12$2qdaDkgAyBgtFD9sejn4z.wsVoWK3KoayQD10LcZkTu7isLvgxYHm', 'admin'),
(13, 'howard@student.umn.ac.id', 'Howard', '$2y$10$Sfk5ZoPJqmzZpmEYE9FyoucxbZJp0FkCcqs1.rTRJER.zF/7Lgt.a', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `eventlist`
--

CREATE TABLE `eventlist` (
  `id` int(11) NOT NULL,
  `namaEvent` varchar(100) DEFAULT NULL,
  `tanggalEvent` varchar(100) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `namaGambar` varchar(100) DEFAULT NULL,
  `waktu` varchar(30) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `maksimum_participant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventlist`
--

INSERT INTO `eventlist` (`id`, `namaEvent`, `tanggalEvent`, `description`, `namaGambar`, `waktu`, `lokasi`, `maksimum_participant`) VALUES
(1, 'UMN Hackfest 2024', '2024-10-03', 'UMN Hackfest 2024 adalah rangkaian acara tentang kompetisi bidang IT yang mencakupi Capture the flag, Business Case, dan Competitive Programming', 'UMNHackfest2024.jpeg', '13:55', 'Aula UMN', 10);

-- --------------------------------------------------------

--
-- Table structure for table `howard@student.umn.ac.id`
--

CREATE TABLE `howard@student.umn.ac.id` (
  `id` int(11) NOT NULL,
  `history` varchar(100) DEFAULT NULL,
  `idEVENT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `howard@student.umn.ac.id`
--

INSERT INTO `howard@student.umn.ac.id` (`id`, `history`, `idEVENT`) VALUES
(1, 'UMN Hackfest 2024', 1);

-- --------------------------------------------------------

--
-- Table structure for table `umn hackfest 2024`
--

CREATE TABLE `umn hackfest 2024` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umn hackfest 2024`
--

INSERT INTO `umn hackfest 2024` (`id`, `email`) VALUES
(23, 'howard@student.umn.ac.id');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventlist`
--
ALTER TABLE `eventlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `howard@student.umn.ac.id`
--
ALTER TABLE `howard@student.umn.ac.id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `umn hackfest 2024`
--
ALTER TABLE `umn hackfest 2024`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `eventlist`
--
ALTER TABLE `eventlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `howard@student.umn.ac.id`
--
ALTER TABLE `howard@student.umn.ac.id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `umn hackfest 2024`
--
ALTER TABLE `umn hackfest 2024`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
