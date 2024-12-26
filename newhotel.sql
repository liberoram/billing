-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 09:02 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newhotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE `master` (
  `id` int(11) NOT NULL,
  `billno` int(225) NOT NULL,
  `billdate` date NOT NULL,
  `Subtotal` varchar(225) NOT NULL,
  `total` varchar(225) NOT NULL,
  `cgst` varchar(5) NOT NULL,
  `sgst` varchar(5) NOT NULL,
  `Tax` varchar(225) NOT NULL,
  `accharge` varchar(225) NOT NULL,
  `status` varchar(15) NOT NULL,
  `waitername` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `master`
--

INSERT INTO `master` (`id`, `billno`, `billdate`, `Subtotal`, `total`, `cgst`, `sgst`, `Tax`, `accharge`, `status`, `waitername`) VALUES
(2, 24826, '0000-00-00', '', '', '', '', '', '', 'PENDING', ''),
(3, 24825, '2024-12-19', '15.20', '16.00', '2.5', '2.5', '0.76', '0', 'COMPLETED', ''),
(5, 24827, '2024-12-20', '5.00', '5.00', '2.5', '2.5', '0.25', '0', 'COMPLETED', ''),
(7, 24828, '2024-12-20', '57.00', '60.00', '2.5', '2.5', '2.85', '0', 'COMPLETED', ''),
(9, 24829, '2024-12-20', '50.00', '53.00', '2.5', '2.5', '2.50', '0', 'COMPLETED', ''),
(11, 24830, '2024-12-20', '82.00', '86.00', '2.5', '2.5', '4.10', '0', 'COMPLETED', ''),
(13, 24832, '0000-00-00', '', '', '', '', '', '', 'PENDING', ''),
(14, 24831, '2024-12-21', '6.00', '6.00', '2.5', '2.5', '0.30', '0', 'COMPLETED', 'Choose...tes'),
(18, 24833, '2024-12-21', '69.00', '83.00', '2.5', '2.5', '3.45', '10.87', 'COMPLETED', ''),
(19, 24834, '2024-12-22', '93.00', '98.00', '2.5', '2.5', '4.65', '0', 'COMPLETED', ''),
(23, 24835, '2024-12-22', '242.25', '254.00', '2.5', '2.5', '12.11', '0', 'COMPLETED', ''),
(24, 24836, '2024-12-22', '190.00', '200.00', '2.5', '2.5', '9.50', '0', 'COMPLETED', ''),
(29, 24837, '2024-12-22', '48.00', '50.00', '2.5', '2.5', '2.40', '0', 'COMPLETED', ''),
(30, 24838, '2024-12-22', '57.00', '60.00', '2.5', '2.5', '2.85', '0', 'COMPLETED', ''),
(32, 24839, '2024-12-22', '57.00', '69.00', '2.5', '2.5', '2.85', '8.98', 'COMPLETED', 'R'),
(33, 24840, '0000-00-00', '', '', '', '', '', '', 'NEW', '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amt` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tamil` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product`, `brand`, `amt`, `scode`, `custom`, `tamil`) VALUES
(1, '  Idli', '', '15.20', '1', '', 'இட்லி'),
(2, 'vada', '', '19', '2', '', 'வடை'),
(3, 'vada (Par)', '', '24', '3', '', '(பார்சல்) வடை'),
(4, 'Kalkandu Pongal', '', '47.5', '4', '', 'கல்கண்டூ'),
(5, 'Lemon Rice', '', '47.5', '5', '', 'லெமன்'),
(7, 'Ven Pongal', '', '47.5', '6', '', 'வென்'),
(8, 'Puliyodharai', '', '47.5', '7', '', 'புளி'),
(12, 'Tomato Rice', '', '57', '8', '', 'தக்காளி'),
(13, 'Dosa', '', '47.5', '9', '', 'தோசை'),
(14, 'Oothappam', '', '47.5', '10', '', 'ஊத்தாப்பம்'),
(15, 'Rava Dosa', '', '52.25', '11', '', 'ரவா'),
(16, 'Podi Oothappam', '', '57', '12', '', 'பொடி ஊ'),
(17, 'SPL Dosa', '', '66.5', '13', '', 'ஸ் தோசை'),
(18, 'SPL Oothappam', '', '66.5', '14', '', 'ஸ் ஊத்தப்பம்'),
(19, 'Onion Oothappam', '', '66.5', '15', '', 'ஆனியன்'),
(20, 'Onion Rava Dosa', '', '66.5', '16', '', 'ஆனியன் ரவா'),
(21, 'SPL Rava Dosa', '', '66.5', '18', '', 'ஸ் ரவா தோசை'),
(22, 'Ghee Rava Dosa', '', '85.50', '22', '', 'நெய் ரவா தோசை'),
(23, 'Ghee Oothappam', '', '80.75', '20', '', 'நெய் ஊத்தப்பம்'),
(24, 'Ghee Onion Oothappam', '', '86', '21', '', 'நெய் ஆனியன்'),
(25, 'Ghee Dosa', '', '80.75', '19', '', 'நெய் தோசை'),
(26, 'Ghee Masal Dosa', '', '80.75', '23', '', 'நெய் மசால் தோசை'),
(27, 'Chapathi', '', '38', '24', '', 'சப்பாத்தி'),
(28, 'Chilli parotta', '', '61.75', '26', '', 'சில்லி புரோட்டா'),
(29, 'Chilli Idli', '', '49.40', '27', '', 'சில்லி இட்லி'),
(30, 'Coffee/milk', '', '23.75', '28', '', 'காபி /பால்'),
(31, 'Coffee (PAR)', '', '33.75', '29', '', 'காபி (பார்சல்)'),
(34, 'Choli falar', '', '19', '34', '', 'தோலி ஃப்லார்'),
(36, 'Meals', '', '100', '32', '', 'மீல்'),
(37, 'Parotta', '', '38', '25', '', 'புரோட்டா'),
(40, 'Masal Dosa', '', '66.5', '17', '', 'மசால் தோசை'),
(41, 'Sukku Milk', '', '29.99', '30', '', 'சுக்கு பால்'),
(42, 'Sukku Milk (PAR)', '', '36.91', '31', '', 'சுக்கு பால்(பார்சல்)'),
(45, 'Rose Milk', '', '38', '33', '', 'Rose Milk'),
(46, 'Container Box', '', '0', '35', 'on', 'Container Box'),
(47, 'Ghee Podi Onion Oothappam', '', '105', '36', '', 'நெய் பொடி ஆனியன்'),
(48, 'podi Dosa', '', '57', '37', '', 'பொடி தோசை'),
(50, 'SPL Masal Dosa', '', '95', '38', '', 'ஸ் மசால் தோசை'),
(51, 'podi onion', '', '86', '39', '', 'பொடி ஆனியன்'),
(52, 'Masal Oothappam', '', '66.5', '40', '', 'மசால் ஊத்தாப்பம்'),
(56, 'mineral water', '', '20', '41', 'on', 'மினரல் வாட்டர்'),
(57, 'Parcel Meals', '', '130', '42', '', 'பார்சல் மீல்'),
(58, 'sambar satham', '', '47.5', '43', '', 'சாம்பார் சாதம்'),
(63, 'curd rice', '', '47.5', '44', '', 'தயிர் சாதம்'),
(65, 'ghee podi uthappam', '1', '90.75', '45', '', 'நெய் பொடி ஊ'),
(66, 'ghee podi dosai', '', '90.75', '46', '', 'நெய் பொடி தோசை'),
(67, 'onion dosai', '', '66.5', '47', '', 'ஆனியன் தோசை'),
(68, 'Chilli Chappathi', '', '61.75', '48', '', 'சில்லி சாப்பாத்தி');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `billno` int(225) NOT NULL,
  `bill_date` date NOT NULL,
  `item` varchar(225) NOT NULL,
  `tamil` varchar(225) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amt` varchar(225) NOT NULL,
  `total` varchar(225) NOT NULL,
  `status` varchar(25) NOT NULL,
  `uniqueId` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `billno`, `bill_date`, `item`, `tamil`, `quantity`, `amt`, `total`, `status`, `uniqueId`) VALUES
(14, 24827, '2024-12-20', 'Container Box', 'Container Box', 1, '5', '5', 'ACTIVE', 'temp_1734664240_9202'),
(17, 24829, '2024-12-20', 'Container Box', 'Container Box', 1, '50', '50', 'ACTIVE', 'temp_1734703217_9597'),
(18, 24830, '2024-12-20', 'vada', 'வடை', 2, '19', '38', 'ACTIVE', 'temp_1734703952_1353'),
(19, 24830, '2024-12-20', 'vada', 'வடை', 2, '19', '38', 'ACTIVE', 'temp_1734703954_2615'),
(20, 24830, '2024-12-20', 'Container Box', 'Container Box', 1, '6', '6', 'ACTIVE', 'temp_1734703992_1122'),
(21, 24831, '2024-12-21', 'Container Box', 'Container Box', 1, '6', '6', 'ACTIVE', 'temp_1734760190_3509'),
(22, 24833, '2024-12-21', 'Container Box', 'Container Box', 1, '69', '69', 'ACTIVE', 'temp_1734763381_1788'),
(23, 24834, '2024-12-21', 'vada', 'வடை', 2, '19', '38', 'ACTIVE', 'temp_1734763524_8393'),
(24, 24834, '2024-12-22', 'mineral water', 'மினரல் வாட்டர்', 1, '55', '55', 'ACTIVE', 'temp_1734838177_6022'),
(29, 24835, '2024-12-22', 'Lemon Rice', 'லெமன்', 1, '47.5', '47.5', 'ACTIVE', 'temp_1734845174_3686'),
(30, 24836, '2024-12-22', 'Lemon Rice', 'லெமன்', 2, '47.5', '95', 'ACTIVE', 'temp_1734845928_8067'),
(31, 24836, '2024-12-22', 'Lemon Rice', 'லெமன்', 2, '47.5', '95', 'ACTIVE', 'temp_1734846378_1122'),
(32, 24835, '2024-12-22', 'Lemon Rice', 'லெமன்', 3, '47.5', '142.5', 'ACTIVE', 'temp_1734846879_9514'),
(33, 24835, '2024-12-22', 'Rava Dosa', 'ரவா', 1, '52.25', '52.25', 'ACTIVE', 'temp_1734846920_3654'),
(41, 24837, '2024-12-22', 'vada (Par)', '(பார்சல்) வடை', 2, '24', '48', 'ACTIVE', 'temp_1734850953_3472'),
(42, 24838, '2024-12-22', 'vada', 'வடை', 3, '19', '57', 'ACTIVE', 'temp_1734851006_6289'),
(43, 24839, '2024-12-22', 'vada', 'வடை', 3, '19', '57', 'ACTIVE', 'temp_1734858520_8138');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`) VALUES
(1, 'admin', 'admin', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `waiter`
--

CREATE TABLE `waiter` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `Mobile` varchar(12) NOT NULL,
  `isacpersion` varchar(1) NOT NULL,
  `Status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `waiter`
--

INSERT INTO `waiter` (`id`, `name`, `Mobile`, `isacpersion`, `Status`) VALUES
(1, 'R', '112', '1', 'Active'),
(5, 'B', '4444466', '0', 'Active'),
(6, 'D', '112', '0', 'Active'),
(7, 'l', '99', '0', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waiter`
--
ALTER TABLE `waiter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master`
--
ALTER TABLE `master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waiter`
--
ALTER TABLE `waiter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
