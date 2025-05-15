-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 12:55 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_core`
--

--
-- Dumping data for table `config_meta_tag`
--

INSERT INTO `config_meta_tag` (`id`, `cat_id`, `photo`, `photo_thumbnail`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'home', NULL, NULL, '2023-08-16 12:18:40', '2023-08-16 12:18:40', NULL),
(2, 'about', NULL, NULL, '2023-08-16 14:16:16', '2024-04-11 01:29:43', NULL),
(3, 'trems', NULL, NULL, '2023-10-29 10:05:33', '2024-04-11 01:26:17', NULL),
(4, 'err_404', NULL, NULL, '2024-01-25 15:35:18', '2024-01-25 15:35:18', NULL),
(5, 'contact_us', NULL, NULL, '2025-01-05 06:59:26', '2025-01-05 06:59:26', NULL),
(6, 'price', NULL, NULL, '2025-01-12 14:32:00', '2025-01-12 14:32:00', NULL),
(7, 'subject_list', NULL, NULL, '2025-01-13 10:48:24', '2025-01-13 10:48:24', NULL),
(8, 'clasess_list', NULL, NULL, '2025-01-13 11:37:33', '2025-01-13 11:37:33', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
