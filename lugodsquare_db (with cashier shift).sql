-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2026 at 02:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lugodsquare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `membership_id` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `court_type` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `total_amount` varchar(50) NOT NULL,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gcash_receipt` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `membership_id`, `first_name`, `last_name`, `contact_number`, `email`, `court_type`, `date`, `start_time`, `end_time`, `total_amount`, `booked_at`, `gcash_receipt`, `status`, `synced`) VALUES
(1, 'CBMS-2026-0001', 'Elizabeth', 'Rebonza', '09092853634', 'rebonzaelizabeth@gmail.com', 3, '2026-01-31', '09:00', '11:00', '180.00', '2026-01-29 20:45:34', 'gcash_696763061b0c6.jpg', 1, 1),
(2, 'CBMS-2026-0001', 'Elizabeth', 'Rebonza', '09092853634', 'rebonzaelizabeth@gmail.com', 1, '2026-01-31', '08:00', '09:00', '945.00', '2026-01-29 20:46:41', 'gcash_69676421914b4.jpg', 1, 1),
(3, 'CBMS-2026-0001', 'Elizabeth', 'Rebonza', '09092853634', 'rebonzaelizabeth@gmail.com', 1, '2026-01-30', '13:00', '15:00', '236.25', '2026-01-30 11:50:04', 'gcash_69676421914b4.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cashier_shifts`
--

CREATE TABLE `cashier_shifts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashier_shifts`
--

INSERT INTO `cashier_shifts` (`id`, `user_id`, `start_time`, `end_time`, `total_sales`, `status`, `created_at`) VALUES
(1, 1, '2026-02-17 18:28:22', '2026-02-17 20:38:38', 230.00, 'closed', '2026-02-17 10:28:22'),
(2, 12, '2026-02-16 18:47:49', '2026-02-16 20:40:43', 100.00, 'closed', '2026-02-17 10:47:49'),
(3, 12, '2026-02-17 20:58:26', '2026-02-17 21:04:24', 180.00, 'closed', '2026-02-17 12:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `id` int(11) NOT NULL,
  `court_type` varchar(50) NOT NULL,
  `capacity` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`id`, `court_type`, `capacity`, `amount`, `is_deleted`, `deleted_by`, `created_at`, `synced`) VALUES
(1, 'Pickleball Court', '30', 350, 0, 0, '2026-01-11 12:23:11', 1),
(2, 'Table Tennis Court', '20', 300, 0, 0, '2026-01-11 12:23:11', 1),
(3, 'Badminton Court', '10', 400, 0, 0, '2026-01-11 12:23:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `membership_id` varchar(50) NOT NULL,
  `card_number` varchar(10) NOT NULL,
  `pin` varchar(100) NOT NULL,
  `wallet` varchar(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `last_name`, `address`, `birth_date`, `contact_number`, `email`, `membership_id`, `card_number`, `pin`, `wallet`, `joined_at`, `synced`) VALUES
(6, 'Elizabeth', 'Rebonza', 'Barangay Bakid-Bakid, Gingoog City', '2005-11-22', '09092853634', 'rebonzaelizabeth@gmail.com', 'CBMS-2026-0001', '', '$2y$10$sjarMnLwYW7UnvXlJQ5XiOJ1sUiZPwdNnADjE1CPoNgQO8/kfNf/e', '0.00', '2026-01-13 18:33:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_category` varchar(50) NOT NULL,
  `product_number` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `price` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `created_at` varchar(255) DEFAULT current_timestamp(),
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_category`, `product_number`, `product_name`, `price`, `qty`, `status`, `is_deleted`, `deleted_by`, `created_at`, `synced`) VALUES
(1, 'Foods', 'TEST-FOODS-0001', 'Burgers', '100', 7, '', 0, 0, '2026-01-11 14:07:53', 0),
(2, 'Merch', 'TEST-MERCH-0001', 'Plain Shirt', '150', 4, '', 0, 0, '2026-01-14 03:06:25', 0),
(3, 'Foods', 'LS20250003', 'Fries', '80', 42, '', 0, 0, '2026-01-14 03:08:03', 0),
(7, 'Merch', '34543', 'Update Test', '45', 3, '', 0, 0, '2026-01-21 07:06:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `transaction_no` varchar(50) DEFAULT NULL,
  `sub_total` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `membership_card` varchar(20) NOT NULL,
  `final_total` decimal(10,2) DEFAULT NULL,
  `cash_amount` varchar(255) DEFAULT NULL,
  `cash_change` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` varchar(255) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `transaction_no`, `sub_total`, `discount`, `membership_card`, `final_total`, `cash_amount`, `cash_change`, `payment_method`, `created_at`, `is_deleted`, `user_id`, `synced`) VALUES
(30, 'TXN-6991280426C97', 100.00, 0.00, 'N/A', 100.00, '200.00', '100.00', 'Cash', '2026-02-15 10:19:04', '0', 1, 0),
(31, 'TXN-69912D5CC5465', 100.00, 0.00, 'N/A', 100.00, '100.00', '0.00', 'Cash', '2026-02-15 10:20:26', '0', 12, 0),
(32, 'TXN-69944755523C5', 100.00, 0.00, 'N/A', 100.00, '200.00', '100.00', 'Cash', '2026-02-16 18:48:15', '0', 12, 0),
(33, 'TXN-699454E9D6BCF', 80.00, 0.00, 'N/A', 80.00, '100.00', '20.00', 'Cash', '2026-02-17 19:46:19', '0', 1, 0),
(34, 'TXN-6994610326902', 150.00, 0.00, 'N/A', 150.00, '200.00', '50.00', 'Cash', '2026-02-17 20:37:39', '0', 1, 0),
(35, 'TXN-699466FC624EA', 180.00, 0.00, 'N/A', 180.00, '200.00', '20.00', 'Cash', '2026-02-17 21:03:09', '0', 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `sale_id`, `item_name`, `qty`, `price`, `total`, `created_at`, `synced`) VALUES
(2, 30, 'Burgers', 1, 100.00, 100.00, '2026-02-15 10:19:04', 0),
(3, 31, 'Burgers', 1, 100.00, 100.00, '2026-02-15 10:20:26', 0),
(4, 32, 'Burgers', 1, 100.00, 100.00, '2026-02-16 18:48:15', 0),
(5, 33, 'Fries', 1, 80.00, 80.00, '2026-02-17 19:46:19', 0),
(6, 34, 'Plain Shirt', 1, 150.00, 150.00, '2026-02-17 20:37:39', 0),
(7, 35, 'Burgers', 1, 100.00, 100.00, '2026-02-17 21:03:09', 0),
(8, 35, 'Fries', 1, 80.00, 80.00, '2026-02-17 21:03:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` tinyint(4) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `synced` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `username`, `password`, `first_name`, `last_name`, `is_deleted`, `deleted_by`, `synced`) VALUES
(1, 1, 'super', '$2y$10$V//UOLLGRb22sd241tgMcu7/U4GvoMy6kRMTCESmMAEWwK/mWDX/q', 'super', 'admin', 0, 0, 1),
(12, 2, 'test', '$2y$10$eNQEI9GXHrDIC8GSNm2IpuIyu9mDbEdF56Pchy9n8HvbbYWRrLEYe', 'test', 'test', 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashier_shifts`
--
ALTER TABLE `cashier_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cashier_shifts`
--
ALTER TABLE `cashier_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
