-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2022 at 11:41 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mcc_bus`
--

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
(13, 2, 1, '39', 'pending', '200', '2022-03-15 22:25:27', 'Mad-Ceb_0020039'),
(14, 2, 1, '40', 'pending', '200', '2022-03-15 22:25:27', 'Mad-Ceb_0020040'),
(15, 1, 1, '1', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001001'),
(16, 1, 1, '4', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001004'),
(17, 1, 1, '2', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001002'),
(18, 1, 1, '3', 'pending', '400', '2022-03-22 13:46:25', 'Mad-Ceb_001003');

-- --------------------------------------------------------

--
-- Table structure for table `tblbus`
--

CREATE TABLE `tblbus` (
  `id` int(11) NOT NULL,
  `bus_num` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbus`
--

INSERT INTO `tblbus` (`id`, `bus_num`) VALUES
(1, 'abc123');

-- --------------------------------------------------------

--
-- Table structure for table `tblconductor`
--

CREATE TABLE `tblconductor` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `liscenseNum` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbldriver`
--

CREATE TABLE `tbldriver` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `liscenseNum` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(3, 'Cebu');

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
(1, 'Ardie', 'Derrayal', 'ardiederrayal06@gmail.com', 'Madridejos, Cebu', '$2y$10$vNWuZ0mYibvXYqGbxA1KjOnMEOqz/zKEkP2buV/xpUdF11LMmPQYO', '669854', '2022-03-12 22:24:21');

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
(1, 1, 3, '1000km'),
(2, 2, 3, '1000km');

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
  `status` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblschedule`
--

INSERT INTO `tblschedule` (`id`, `route_id`, `schedule_date`, `departure`, `arrival`, `bus_id`, `driver_id`, `conductor_id`, `fare`, `status`) VALUES
(1, 1, '2022-03-10', '08:00:00', '09:00:00', 1, 0, 0, '100', 'waiting'),
(2, 1, '2022-03-10', '10:00:00', '11:00:00', 1, 0, 0, '100', 'waiting');

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
(2, 'test', 'test', 'test@gmail.com', '$2y$10$8Tp31i0/1.CMhb1.F.RpieZ.7QTviaN7bXYlp4SP5KH0dbn5LMNy6', 1, '2022-03-22 16:56:43', '353416', NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbook`
--
ALTER TABLE `tblbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblbus`
--
ALTER TABLE `tblbus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblpassenger`
--
ALTER TABLE `tblpassenger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblroute`
--
ALTER TABLE `tblroute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
