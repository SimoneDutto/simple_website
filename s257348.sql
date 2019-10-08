-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 17, 2019 at 12:12 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Exemption`
--

-- --------------------------------------------------------

--
-- Table structure for table `Seat`
--

DROP TABLE IF EXISTS `Seat`;
CREATE TABLE `Seat` (
  `email` varchar(100) NOT NULL,
  `seat` varchar(5) NOT NULL,
  `status` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Seat`
--

INSERT INTO `Seat` (`email`, `seat`, `status`) VALUES
('u1@p.it', 'A4', 'r'),
('u2@p.it', 'B2', 'b'),
('u2@p.it', 'B3', 'b'),
('u2@p.it', 'B4', 'b'),
('u1@p.it', 'D4', 'r'),
('u2@p.it', 'F4', 'r');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `email` varchar(100) NOT NULL,
  `password` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`email`, `password`) VALUES
('u1@p.it', 'ec6ef230f1828039ee794566b9c58adc'),
('u2@p.it', '1d665b9b1467944c128a5575119d1cfd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Seat`
--
ALTER TABLE `Seat`
  ADD PRIMARY KEY (`seat`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
