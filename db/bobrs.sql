-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2022 at 03:56 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bobrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdResetId` int(11) NOT NULL,
  `pwdResetEmail` text DEFAULT NULL,
  `pwdResetSelector` text DEFAULT NULL,
  `pwdResetToken` longtext DEFAULT NULL,
  `pwdResetExpires` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblbook`
--

CREATE TABLE `tblbook` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `seat_num` varchar(150) NOT NULL,
  `payment_status` varchar(150) NOT NULL,
  `total` varchar(150) NOT NULL,
  `book_date` datetime NOT NULL DEFAULT current_timestamp(),
  `book_reference` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbook`
--

INSERT INTO `tblbook` (`id`, `schedule_id`, `passenger_id`, `seat_num`, `payment_status`, `total`, `book_date`, `book_reference`) VALUES
(13, 2, 1, '39', 'cancel', '200', '2022-03-15 22:25:27', 'Mad-Ceb_0020039'),
(14, 2, 1, '40', 'cancel', '200', '2022-03-15 22:25:27', 'Mad-Ceb_0020040'),
(15, 1, 1, '1', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001001'),
(16, 1, 1, '4', 'cancel', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001004'),
(17, 1, 1, '2', 'cancel', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001002'),
(18, 1, 1, '3', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001003'),
(19, 1, 1, '5', 'confirmed', '150', '2022-03-25 00:00:23', 'Mad-Ceb_001005'),
(20, 8, 1, '1', 'cancel', '12000', '2022-04-21 14:38:47', 'Mad-Ceb_008001'),
(21, 8, 1, '2', 'cancel', '12000', '2022-04-21 14:40:10', 'Mad-Ceb_008002'),
(22, 8, 1, '3', 'cancel', '12000', '2022-04-21 14:41:14', 'Mad-Ceb_008003'),
(23, 8, 1, '30', 'confirmed', '12000', '2022-04-21 14:42:05', 'Mad-Ceb_0080030'),
(24, 9, 1, '1', 'cancel', '1200', '2022-04-22 10:49:57', 'Ban-Ceb_009001'),
(25, 9, 1, '2', 'pending', '1200', '2022-04-22 11:08:35', 'Ban-Ceb_009002'),
(26, 12, 3, '1', 'cancel', '2200', '2022-04-24 15:19:07', 'Mad-Ceb_0012001'),
(27, 12, 3, '2', 'confirmed', '2200', '2022-04-24 15:26:25', 'Mad-Ceb_0012002'),
(28, 12, 1, '11', 'pending', '2200', '2022-04-24 18:04:21', 'Mad-Ceb_00120011'),
(29, 12, 4, '14', 'confirmed', '4400', '2022-04-24 20:26:23', 'Mad-Ceb_00120014'),
(30, 12, 4, '18', 'pending', '4400', '2022-04-24 20:26:23', 'Mad-Ceb_00120018'),
(31, 15, 6, '5', 'confirmed', '2200', '2022-04-27 11:25:56', 'Ceb-Hag_0015005'),
(32, 15, 6, '23', 'cancel', '4400', '2022-04-27 17:06:16', 'Ceb-Hag_00150023'),
(33, 15, 6, '31', 'confirmed', '4400', '2022-04-27 17:06:16', 'Ceb-Hag_00150031'),
(34, 15, 6, '1', 'confirmed', '2200', '2022-04-27 21:17:58', 'Ceb-Hag_0015001'),
(35, 15, 6, '2', 'confirmed', '2200', '2022-04-27 21:27:15', 'Ceb-Hag_0015002'),
(36, 16, 6, '30', 'pending', '1100', '2022-04-28 21:45:16', 'Ban-Ceb_00160030'),
(37, 16, 6, '22', 'pending', '1100', '2022-04-28 22:43:03', 'Ban-Ceb_00160022');

-- --------------------------------------------------------

--
-- Table structure for table `tblbus`
--

CREATE TABLE `tblbus` (
  `id` int(11) NOT NULL,
  `bus_num` varchar(150) NOT NULL,
  `bus_type` varchar(50) NOT NULL,
  `rate_km` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbus`
--

INSERT INTO `tblbus` (`id`, `bus_num`, `bus_type`, `rate_km`) VALUES
(1, 'bus1', 'Regular', 2.2);

-- --------------------------------------------------------

--
-- Table structure for table `tblconductor`
--

CREATE TABLE `tblconductor` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `liscenseNum` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblconductor`
--

INSERT INTO `tblconductor` (`id`, `name`, `liscenseNum`) VALUES
(2, 'Arjaylyn Leones', '02384508');

-- --------------------------------------------------------

--
-- Table structure for table `tbldriver`
--

CREATE TABLE `tbldriver` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `liscenseNum` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbldriver`
--

INSERT INTO `tbldriver` (`id`, `name`, `liscenseNum`) VALUES
(2, 'SaLING', '03829048');

-- --------------------------------------------------------

--
-- Table structure for table `tbllocation`
--

CREATE TABLE `tbllocation` (
  `id` int(11) NOT NULL,
  `location_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbllocation`
--

INSERT INTO `tbllocation` (`id`, `location_name`) VALUES
(1, 'Madridejos'),
(2, 'Bantayan'),
(5, 'Cebu'),
(6, 'Carmen'),
(8, 'Bogo City'),
(9, 'Hagnaya'),
(10, 'Consolacion');

-- --------------------------------------------------------

--
-- Table structure for table `tblpassenger`
--

CREATE TABLE `tblpassenger` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `verification_code` text NOT NULL,
  `email_verified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpassenger`
--

INSERT INTO `tblpassenger` (`id`, `first_name`, `last_name`, `email`, `address`, `password`, `verification_code`, `email_verified_at`) VALUES
(2, 'Ezel', 'Dela Pena', 'delapenaezel847+test1@gmail.com', 'Tarong', '$2y$10$73/hEp3qUegbzQX.WYRmtetli4RJelF4wcZ9FXPThaq/j7du4CEmG', '239576', '0000-00-00 00:00:00'),
(3, 'Jossin', 'Ygoña', 'jossygonia@gmail.com', 'Kaongkod, New York', '$2y$10$bb3sCZQCHglZSzvBcuQmXOrHSyK6GbHmvodzxHWdB4LJ86CohUAse', '547899', '2022-04-24 15:16:15'),
(4, 'Niño', 'Sevillejo', 'sevillejoonin18@gmail.com', 'Ticad, Bantayan, Cebu', '$2y$10$RlDd49hi8gdq0DyZpHjR4un30Na5zIi3Nj1z/84oDXKfpdAtGizW.', '275893', '2022-04-24 20:23:46'),
(6, 'Ezel', 'Pena', 'delapenaezel847@gmail.com', 'Tarong', '$2y$10$CbWn0aPi2myl6urTk3yJruVUElsLa8o7I2yBACUmR8UZ7VAAfjlWq', '191318', '2022-04-27 11:24:22');

-- --------------------------------------------------------

--
-- Table structure for table `tblroute`
--

CREATE TABLE `tblroute` (
  `id` int(11) NOT NULL,
  `route_from` int(11) NOT NULL,
  `route_to` int(11) NOT NULL,
  `distance` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblroute`
--

INSERT INTO `tblroute` (`id`, `route_from`, `route_to`, `distance`) VALUES
(4, 5, 9, '1000'),
(5, 2, 5, '500');

-- --------------------------------------------------------

--
-- Table structure for table `tblschedule`
--

CREATE TABLE `tblschedule` (
  `id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `departure` time NOT NULL,
  `arrival` time NOT NULL,
  `bus_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `conductor_id` int(11) NOT NULL,
  `fare` decimal(10,0) NOT NULL,
  `status` varchar(150) NOT NULL,
  `vessel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblschedule`
--

INSERT INTO `tblschedule` (`id`, `route_id`, `schedule_date`, `departure`, `arrival`, `bus_id`, `driver_id`, `conductor_id`, `fare`, `status`, `vessel_id`) VALUES
(13, 4, '2022-04-26', '03:13:00', '19:16:00', 1, 2, 0, '2200', 'cancelled', 3),
(14, 4, '2022-04-27', '17:12:00', '14:15:00', 1, 2, 0, '2200', 'in-transit', 4),
(16, 5, '2022-04-29', '19:30:00', '21:32:00', 1, 2, 0, '1100', 'waiting', 4),
(30, 4, '2022-04-29', '23:32:00', '21:34:00', 1, 2, 0, '12', 'waiting', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `verification_code` text NOT NULL,
  `email_verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`id`, `fullname`, `username`, `email`, `password`, `status`, `date_created`, `verification_code`, `email_verified_at`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$orIyzP.kfA9tLzuHCh.MS.7JFlVroxvpOBDCFFOPGIRFYieblZlL.', 1, '2022-03-22 14:50:51', '331016', '2022-03-22 14:53:04'),
(2, 'test', 'test', 'test@gmail.com', '$2y$10$8Tp31i0/1.CMhb1.F.RpieZ.7QTviaN7bXYlp4SP5KH0dbn5LMNy6', 1, '2022-03-22 16:56:43', '353416', NULL),
(5, 'dela pena', 'saling', 'delapenaezel847@gmail.com', '$2y$10$oHHekKN2wZzETil1G6CdE.UC/HSoxpvdp.CTHAgr8AamRSWKhcVWa', 1, '2022-04-25 13:48:59', '328219', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvessel`
--

CREATE TABLE `tblvessel` (
  `id` int(11) NOT NULL,
  `vessel_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblvessel`
--

INSERT INTO `tblvessel` (`id`, `vessel_name`) VALUES
(3, 'Island shipping'),
(4, 'Shuttle');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdResetId`);

--
-- Indexes for table `tblbook`
--
ALTER TABLE `tblbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbus`
--
ALTER TABLE `tblbus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblconductor`
--
ALTER TABLE `tblconductor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldriver`
--
ALTER TABLE `tbldriver`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbllocation`
--
ALTER TABLE `tbllocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpassenger`
--
ALTER TABLE `tblpassenger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblroute`
--
ALTER TABLE `tblroute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvessel`
--
ALTER TABLE `tblvessel`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblbook`
--
ALTER TABLE `tblbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tblbus`
--
ALTER TABLE `tblbus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblconductor`
--
ALTER TABLE `tblconductor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbldriver`
--
ALTER TABLE `tbldriver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbllocation`
--
ALTER TABLE `tbllocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblpassenger`
--
ALTER TABLE `tblpassenger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblroute`
--
ALTER TABLE `tblroute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblvessel`
--
ALTER TABLE `tblvessel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
