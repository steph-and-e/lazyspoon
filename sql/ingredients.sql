-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2025 at 06:53 PM
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
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `quantity`) VALUES
(1, 'butter', NULL),
(2, 'sugar', NULL),
(3, 'brown sugar', NULL),
(4, 'salt', NULL),
(5, 'milk', NULL),
(6, 'cheese', NULL),
(7, 'lemon', NULL),
(8, 'vanilla extract', NULL),
(9, 'flour', NULL),
(10, 'baking soda', NULL),
(11, 'baking powder', NULL),
(12, 'egg', NULL),
(13, 'soy sauce', NULL),
(14, 'garlic', NULL),
(15, 'red cabbage', NULL),
(16, 'rice', NULL),
(17, 'onion', NULL),
(18, 'chicken', NULL),
(19, 'white rice', NULL),
(20, 'thyme', NULL),
(21, 'oregano', NULL),
(22, 'paprika', NULL),
(23, 'olive oil', NULL),
(24, 'avocado', NULL),
(25, 'pepper', NULL),
(26, 'tomato', NULL),
(27, 'cumin', NULL),
(28, 'chili powder', NULL),
(29, 'raisins', NULL),
(30, 'cheddar', NULL),
(31, 'corn muffin', NULL),
(32, 'sour cream', NULL),
(33, 'sourdough bread', NULL),
(34, 'cream', NULL),
(35, 'italian seasoning', NULL),
(36, 'garlic powder', NULL),
(37, 'baked beans', NULL),
(38, 'bacon', NULL),
(39, 'bell peppers', NULL),
(40, 'marinara sauce', NULL),
(41, 'pepperoni', NULL),
(42, 'coconut milk', NULL),
(43, 'banana pepper', NULL),
(44, 'honey', NULL),
(45, 'dijon mustard', NULL),
(46, 'cucumber', NULL),
(47, 'red onion', NULL),
(48, 'parsley', NULL),
(49, 'salmon', NULL),
(50, 'grape tomatoes', NULL),
(51, 'artichoke', NULL),
(52, 'steak', NULL),
(53, 'milkfat', NULL),
(54, 'curd', NULL),
(55, 'maple syrup', NULL),
(56, 'cocoa powder', NULL),
(57, 'oats', NULL),
(58, 'espresso powder', NULL),
(59, 'almond flour', NULL),
(60, 'cherry tomatoes', NULL),
(61, 'white wine', NULL),
(62, 'spinach', NULL),
(63, 'heavy cream', NULL),
(64, 'fish', NULL),
(65, 'pasta', NULL),
(66, 'vinegar', NULL),
(67, 'sesame oil', NULL),
(68, 'oil', NULL),
(69, 'steaks', NULL),
(70, 'shallot', NULL),
(71, 'beef broth', NULL),
(72, 'tofu', NULL),
(73, 'broccoli', NULL),
(74, 'couscous', NULL),
(75, 'pine nuts', NULL),
(76, 'zucchini', NULL),
(77, 'tomatoes', NULL),
(78, 'orange', NULL),
(79, 'pineapple', NULL),
(80, 'kiwi', NULL),
(81, 'peas', NULL),
(82, 'onions', NULL),
(83, 'celery', NULL),
(84, 'carrots', NULL),
(85, 'potato', NULL),
(86, 'potatoes', NULL),
(87, 'jalapeno', NULL),
(88, 'beans', NULL),
(89, 'corn', NULL),
(90, 'broth', NULL),
(91, 'lime', NULL),
(92, 'turkey', NULL),
(93, 'worcestershire', NULL),
(94, 'tilapia', NULL),
(95, 'cauliflower', NULL),
(96, 'sweet potatoes', NULL),
(97, 'cinnamon', NULL),
(98, 'marshmallows', NULL),
(99, 'vegetable oil', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
