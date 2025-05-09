-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2025 at 06:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lazy_spoon`
--

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`) VALUES
(28, 1),
(28, 2),
(28, 3),
(28, 4),
(28, 5),
(28, 6),
(28, 7),
(28, 8),
(28, 9),
(28, 10),
(29, 1),
(29, 2),
(29, 4),
(29, 5),
(29, 8),
(29, 9),
(29, 11),
(29, 12),
(30, 3),
(30, 13),
(30, 14),
(30, 15),
(30, 16),
(30, 17),
(31, 1),
(31, 4),
(31, 14),
(31, 16),
(31, 17),
(31, 18),
(31, 19),
(31, 20),
(31, 21),
(31, 22),
(31, 23),
(32, 4),
(32, 5),
(32, 6),
(32, 12),
(32, 14),
(32, 17),
(32, 21),
(32, 23),
(32, 24),
(32, 25),
(32, 26),
(32, 27),
(32, 28),
(32, 29),
(32, 30),
(32, 31),
(32, 32),
(33, 1),
(33, 6),
(33, 26),
(33, 30),
(33, 33),
(33, 34),
(33, 35),
(33, 36),
(35, 1),
(35, 3),
(35, 17),
(35, 37),
(35, 38),
(36, 6),
(36, 39),
(36, 40),
(36, 41),
(37, 2),
(37, 4),
(37, 8),
(37, 42),
(38, 4),
(38, 6),
(38, 13),
(38, 14),
(38, 16),
(38, 18),
(38, 21),
(38, 22),
(38, 23),
(38, 25),
(38, 43),
(38, 44),
(38, 45),
(38, 46),
(38, 47),
(38, 48),
(39, 4),
(39, 7),
(39, 14),
(39, 23),
(39, 25),
(39, 47),
(39, 48),
(39, 49),
(39, 50),
(39, 51),
(40, 1),
(40, 4),
(40, 14),
(40, 25),
(40, 52),
(41, 1),
(41, 4),
(41, 6),
(41, 8),
(41, 53),
(41, 54),
(41, 55),
(41, 56),
(41, 57),
(41, 58),
(41, 59),
(42, 4),
(42, 14),
(42, 16),
(42, 17),
(42, 23),
(42, 25),
(42, 48),
(42, 60),
(42, 61),
(42, 62),
(42, 63),
(42, 64),
(42, 65),
(43, 2),
(43, 4),
(43, 13),
(43, 14),
(43, 16),
(43, 46),
(43, 66),
(43, 67),
(43, 68),
(44, 1),
(44, 4),
(44, 14),
(44, 20),
(44, 23),
(44, 25),
(44, 69),
(44, 70),
(44, 71),
(45, 13),
(45, 25),
(45, 36),
(45, 67),
(45, 72),
(45, 73),
(46, 1),
(46, 4),
(46, 14),
(46, 74),
(46, 75),
(46, 76),
(46, 77),
(47, 3),
(47, 7),
(47, 8),
(47, 78),
(47, 79),
(47, 80),
(48, 4),
(48, 25),
(48, 81),
(48, 82),
(48, 83),
(48, 84),
(48, 85),
(49, 4),
(49, 9),
(49, 12),
(49, 86),
(50, 4),
(50, 14),
(50, 17),
(50, 21),
(50, 23),
(50, 25),
(50, 27),
(50, 28),
(50, 39),
(50, 77),
(50, 83),
(50, 87),
(50, 88),
(50, 89),
(51, 4),
(51, 13),
(51, 14),
(51, 25),
(51, 49),
(51, 55),
(52, 4),
(52, 23),
(52, 25),
(52, 86),
(53, 14),
(53, 17),
(53, 19),
(53, 23),
(53, 25),
(53, 27),
(53, 88),
(53, 90),
(54, 4),
(54, 14),
(54, 27),
(54, 28),
(54, 68),
(54, 88),
(54, 91),
(55, 4),
(55, 14),
(55, 17),
(55, 21),
(55, 22),
(55, 23),
(55, 25),
(55, 27),
(55, 28),
(55, 77),
(55, 88),
(55, 92),
(56, 3),
(56, 4),
(56, 14),
(56, 25),
(56, 93),
(57, 1),
(57, 4),
(57, 7),
(57, 14),
(57, 25),
(57, 73),
(57, 94),
(57, 95),
(58, 14),
(58, 18),
(58, 23),
(58, 25),
(58, 65),
(58, 77),
(59, 4),
(59, 13),
(59, 14),
(59, 23),
(59, 25),
(59, 91),
(60, 1),
(60, 2),
(60, 3),
(60, 96),
(60, 97),
(60, 98),
(61, 4),
(61, 17),
(61, 25),
(61, 26),
(61, 27),
(61, 28),
(61, 36),
(61, 88),
(61, 92),
(61, 99);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
