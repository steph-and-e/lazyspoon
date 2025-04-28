-- phpMyAdmin SQL Dump
-- version 5.2.2-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 28, 2025 at 02:01 PM
-- Server version: 9.1.0-commercial
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `faghanim_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','moderator','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`, `last_activity`, `last_login`, `reset_token`, `reset_token_expires`) VALUES
(1, 'admin', 'admin@mcmaster.ca', '$2y$10$iUc6cuf.3Z5hhPs7sbrMU.rCszwbRRbq3fkavtWCvOJdmSLD0n7li', 'admin', '2025-04-28 14:00:28', NULL, NULL, NULL, NULL),
(2, 'faghanim', '13882m.mostafa@gmail.com', '$2y$10$32ImGb8joShy7Ha766svK.U9vmZuEGXTLhtfjRROun3JyhBBhWs1a', 'user', '2025-04-26 23:28:02', NULL, NULL, NULL, NULL),
(3, 'steph', 'stephande@gmail.com', '$2y$10$9kEvb4Jk5ypYuTzzechEmureIbMjKKfsT./m9PY3PZLQhcvdb8Ll2', 'user', '2025-04-27 03:34:56', NULL, NULL, NULL, NULL),
(4, 'Mosi', '1382m.mostafa@gmail.com', '$2y$10$xh0uDkjQ7ufqruxYtNTLI.9cJIv574tZgYFZD4SerJuCUD5sVZhSC', 'user', '2025-04-27 03:35:21', NULL, NULL, '859691428986acb49a077d8e6e5b965ef2ea8683ac74ccd76297aa0328614f28', '2025-04-27 22:05:58'),
(5, 'dsadlksadlsa', 'mosi@m.c', '$2y$10$yJh0DBkEQAyXiH4WMI5oRuVRhKmFcjEDf68w7/6iPZzghevSbJ0Me', 'user', '2025-04-27 03:37:11', NULL, NULL, NULL, NULL),
(6, 'dsadlsa', 'sajdlsadjlsa@dsadsa.ca', '$2y$10$u6EFapyFbUgvTanG1e/veupi5mYPOdbIo1A5eGqkICgrzmmas492m', 'user', '2025-04-27 03:38:24', NULL, NULL, NULL, NULL),
(7, 'Ali', 'ali@gmail.com', '$2y$10$uC2JjedE81CekjnZBlMyveQ2bvbtCbALiAb57PeTVP3AFrNtyp1RW', 'user', '2025-04-27 03:40:05', NULL, NULL, NULL, NULL),
(8, 'mamali', 'mamali@ksakd.c', '$2y$10$AJ9iFK3u/HLxafN2qfYuyuuv8JfEMhLJaH.K/vUTbaz1XcUlnn8VW', 'user', '2025-04-27 03:40:32', NULL, NULL, NULL, NULL),
(9, 'parsa', 'parsa138275@outlook.com', '$2y$10$a0vJpQCz.udA5D4DnxmUzOgqup3cD0DojC.8pGooYkbO.h3YTXS0S', 'user', '2025-04-27 03:40:55', NULL, NULL, NULL, NULL),
(10, 'amiir', 'amir.dadashi.1384.0@gmail.com', '$2y$10$Kpuj7GT.gqJbPWzVP265IOn9w9uG2m.vK3lCNhNnnh0l543W60.l6', 'user', '2025-04-27 03:41:37', NULL, NULL, NULL, NULL),
(11, 'BOOOQ', 'JDALSDJSKD@G.CA', '$2y$10$sqHiW0hl52MzOrPuGPNGFeUvJbLSC/2CDRUd9TljAz2e8b8hVn9g.', 'user', '2025-04-27 03:42:08', NULL, NULL, NULL, NULL),
(12, 'aliparvin', 'flksadjlsa@gmail.co', '$2y$10$y0tb8Ve1jD9hOFF//n8ZSuemoWwpwMNFsBecMtXVKlFWEWqX4Fn3G', 'user', '2025-04-27 03:42:41', NULL, NULL, NULL, NULL),
(13, 'dasdasd', 'Aliq@gmail.com', '$2y$10$EiDzdfIsE2BN1GJWPQ96UeGENFxi/PkmVedVcCKcubL5z9zJ0dyyW', 'user', '2025-04-27 03:43:10', NULL, NULL, NULL, NULL),
(119, 'faghanimیسشی', 'Faghanim@mcmaster.ca', '$2y$10$.qhwPKezF2chZBJrsz4bLuN9xT4c/KCJDad2COA.k1i0COQ/YAYWa', 'user', '2025-04-27 18:01:27', NULL, NULL, 'b5104e39febf6899ab180f85e3e0c72d9be12384479af78fce384e4a7986301e', '2025-04-27 22:13:10'),
(120, 'rezaaaa', 'darambeza2@gmail.com', '$2y$10$vvEetXiXwAAgQKj3ZAWTgOGmf3kERptjjku2jSoavuG6tz3pbaC1q', 'user', '2025-04-27 18:03:00', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
