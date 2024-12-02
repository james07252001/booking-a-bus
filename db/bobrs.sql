-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 10:29 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pwdreset`
--

INSERT INTO `pwdreset` (`pwdResetId`, `pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpires`) VALUES
(6, 'jvpastorillo@gmail.com', '75b4de3115e811e7', '$2y$10$Y0wccGbGymKGK3uPoxB5WeKytTIeEojc7u7hhhjo8FqEnh51uAHnO', '1720274827');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooks`
--

CREATE TABLE `tblbooks` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `passenger_type` varchar(150) NOT NULL,
  `seat_num` varchar(250) NOT NULL,
  `payment_status` varchar(150) NOT NULL,
  `fare` decimal(10,2) NOT NULL,
  `luggage_fee` decimal(10,2) NOT NULL,
  `luggage_count` int(30) NOT NULL,
  `luggage_weight` varchar(250) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `uploadi_id` varchar(250) NOT NULL,
  `upload_id_name` varchar(250) NOT NULL,
  `upload_id_path` varchar(250) NOT NULL,
  `book_date` datetime NOT NULL,
  `book_reference` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblbus`
--

CREATE TABLE `tblbus` (
  `id` int(11) NOT NULL,
  `bus_num` varchar(150) NOT NULL,
  `bus_type` varchar(150) NOT NULL,
  `bus_code` varchar(250) NOT NULL,
  `rate_km` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbus`
--

INSERT INTO `tblbus` (`id`, `bus_num`, `bus_type`, `bus_code`, `rate_km`) VALUES
(1, 'Ceres Bus', 'Regular', '001', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblconductor`
--

CREATE TABLE `tblconductor` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `liscenseNum` varchar(100) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `address` varchar(250) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblconductor`
--

INSERT INTO `tblconductor` (`id`, `name`, `liscenseNum`, `phone`, `address`, `driver_id`) VALUES
(1, 'Robert Azarcon', '', '09874563214', 'Kodia', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbldriver`
--

CREATE TABLE `tbldriver` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `liscenseNum` varchar(100) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldriver`
--

INSERT INTO `tbldriver` (`id`, `name`, `liscenseNum`, `phone`, `address`) VALUES
(1, 'John Carlo Jagdon', '', '09153312395', 'Mancilang');

-- --------------------------------------------------------

--
-- Table structure for table `tbllocation`
--

CREATE TABLE `tbllocation` (
  `id` int(11) NOT NULL,
  `location_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbllocation`
--

INSERT INTO `tbllocation` (`id`, `location_name`) VALUES
(1, 'Bantayan'),
(2, 'Hagnaya');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpassenger`
--

INSERT INTO `tblpassenger` (`id`, `first_name`, `last_name`, `email`, `address`, `password`, `verification_code`, `email_verified_at`) VALUES
(1, 'James', 'Pastorillo', 'jvpastorillo@gmail.com', 'Kabac, Bantayan, Cebu', '$2y$10$aAVSL44qv0n5mEP/TN037u7Ffmp5ouS2Wtsv3nSeZzuyCypr/gBRe', '352181', '2024-09-13 21:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `tblroute`
--

CREATE TABLE `tblroute` (
  `id` int(11) NOT NULL,
  `route_from` int(11) NOT NULL,
  `route_to` int(11) NOT NULL,
  `distance` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblroute`
--

INSERT INTO `tblroute` (`id`, `route_from`, `route_to`, `distance`) VALUES
(1, 1, 2, '');

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
  `fare` decimal(10,2) NOT NULL,
  `status` varchar(150) NOT NULL,
  `vessel_id` int(11) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `bus_code_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblschedule`
--

INSERT INTO `tblschedule` (`id`, `route_id`, `schedule_date`, `departure`, `arrival`, `bus_id`, `driver_id`, `conductor_id`, `fare`, `status`, `vessel_id`, `discount`, `bus_code_id`) VALUES
(1, 1, '2024-10-25', '21:56:00', '00:56:00', 1, 1, 1, 300.00, 'waiting', 0, 0.00, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`id`, `fullname`, `username`, `email`, `password`, `status`, `date_created`, `verification_code`, `email_verified_at`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$orIyzP.kfA9tLzuHCh.MS.7JFlVroxvpOBDCFFOPGIRFYieblZlL.', 0, '2022-03-22 14:50:51', '331016', '2022-03-22 14:53:04'),
(2, 'test', 'test', 'test@gmail.com', '$2y$10$8Tp31i0/1.CMhb1.F.RpieZ.7QTviaN7bXYlp4SP5KH0dbn5LMNy6', 0, '2022-03-22 16:56:43', '353416', NULL),
(11, 'James Pastorillo', 'james', 'pastorillo.james25@gmail.com', '$2y$10$PaCZN8y6XFi7JckHZ7.w..QzOD.pGjgU2bzWxUqe.JK3zgGdzg/22', 1, '2024-10-04 15:46:52', '321973', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvessel`
--

CREATE TABLE `tblvessel` (
  `id` int(11) NOT NULL,
  `vessel_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `tblbooks`
--
ALTER TABLE `tblbooks`
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
  MODIFY `pwdResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblbooks`
--
ALTER TABLE `tblbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbus`
--
ALTER TABLE `tblbus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblconductor`
--
ALTER TABLE `tblconductor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbldriver`
--
ALTER TABLE `tbldriver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbllocation`
--
ALTER TABLE `tbllocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpassenger`
--
ALTER TABLE `tblpassenger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblroute`
--
ALTER TABLE `tblroute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblvessel`
--
ALTER TABLE `tblvessel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
