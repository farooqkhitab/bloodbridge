-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2025 at 10:15 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodbridge`
--

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

DROP TABLE IF EXISTS `donors`;
CREATE TABLE IF NOT EXISTS `donors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `whatsapp` varchar(15) DEFAULT NULL,
  `blood_group` varchar(5) NOT NULL,
  `district` varchar(100) NOT NULL,
  `tehsil` varchar(100) NOT NULL,
  `vc` varchar(100) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1',
  `until` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3718 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `full_name`, `username`, `email`, `dob`, `password_hash`, `contact`, `whatsapp`, `blood_group`, `district`, `tehsil`, `vc`, `profile_picture`, `created_at`, `status`, `until`) VALUES
(1, 'Winter Booker', 'fesidac', 'tefiramu@mailinator.com', '2000-08-25', '$2y$10$rhrm3/XiXs4EuO0BumRisu9iTDzeCeKcB9sIGnEAOkoVZ3VPfkE9S', '03222222222', '03222222222', 'B-', '21', '59', '3160', 'profile_pictures/1739255912_images.png', '2025-02-11 06:38:32', 1, '2025-08-25'),
(4, 'Garrison Beard', 'gygenogovo', 'wycohoteq@mailinator.com', '1976-12-02', '$2y$10$QH9KWuyIbSVOKwYAhuGQ4OLz59rthzMUyPjhl3utieepFyfGZL2xG', '03035555555', '03035555555', 'O+', '1', '1', '135', 'profile_pictures/1739580794_Screenshot (102).png', '2025-02-15 00:53:14', 1, '2025-05-21'),
(16, 'Tyler Pollard', 'mapyqowy', 'xiwaguniwy@mailinator.com', '1984-04-13', '$2y$10$Z5Q5BwkiVDdeb5pymHQ1GuCP/rFQVP1mn2FRb.zEETxyOrSY1Paja', '03025555555', '03025555555', 'B-', '16', '45', '2061', 'profile_pictures/1742029503_IMG_2350_112956.JPG', '2025-03-15 09:05:03', 1, '2025-09-11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
