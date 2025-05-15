-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 08:45 PM
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
-- Dumping data for table `config_setting`
--

INSERT INTO `config_setting` (`id`, `web_url`, `web_status`, `web_status_date`, `switch_lang`, `users_login`, `phone_num`, `whatsapp_num`, `phone_call`, `whatsapp_send`, `email`, `def_url`, `facebook`, `youtube`, `twitter`, `instagram`, `linkedin`, `google_api`, `telegram_send`, `telegram_phone`, `telegram_group`, `schema_type`, `schema_lat`, `schema_long`, `schema_postal_code`, `schema_country`) VALUES
(1, 'https://on-fire.test/admin/site-settings', 1, NULL, 0, 0, '01221-000-002', '01221-000-002', '01221000002', '201221000002', 'info@web-site-name.com', 'https://web-site-name.com', 'https://www.facebook.com/', 'https://www.youtube.com', 'https://www.x.com/', 'https://www.Instagram.com/', 'https://www.linkedin.com/', NULL, 0, NULL, NULL, 'Store', NULL, NULL, '21111', 'EG');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
