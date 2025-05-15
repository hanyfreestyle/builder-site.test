-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 09:53 PM
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
-- Database: `on_fire`
--

--
-- Dumping data for table `config_upload_filter_sizes`
--

INSERT INTO `config_upload_filter_sizes` (`id`, `filter_id`, `type`, `width`, `height`, `canvas_back`, `text_state`, `watermark_state`) VALUES
(1, 2, 4, 500, 335, '#ffffff', 0, 0),
(2, 3, 4, 200, 200, '#FFFFFF', 0, 0),
(3, 4, 4, 256, 256, '#FFFFFF', 0, 0),
(4, 5, 4, 128, 128, '#FFFFFF', 0, 0),
(5, 6, 4, 64, 64, '#FFFFFF', 0, 0),
(6, 7, 4, 32, 32, '#FFFFFF', 0, 0),
(7, 8, 4, 400, 300, '#FFFFFF', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
