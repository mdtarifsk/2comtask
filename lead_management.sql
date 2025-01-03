-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2025 at 05:31 PM
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
-- Database: `lead_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `status` enum('New','In Progress','Closed') NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `email`, `phone`, `status`, `assigned_to`, `date_added`, `last_updated`) VALUES
(1, 'tarif sk', 'tarifskgcet@gmail.com', '8777276060', 'New', 1, '2025-01-03 11:42:04', '2025-01-03 11:43:04'),
(3, 'Tarif sk', 'tarif@gmail.com', '87442154554', 'In Progress', NULL, '2025-01-03 19:37:06', '2025-01-03 20:08:43'),
(7, 'Alex', 'alax@gmail.com', '6063587545', 'New', NULL, '2025-01-03 20:53:11', '2025-01-03 20:53:11'),
(9, 'ARIF SK', 'arifskgcet@gmail.com', '08760635658', 'Closed', NULL, '2025-01-03 20:59:54', '2025-01-03 21:00:13'),
(10, 'TARIF SK', 'tarifcet@gmail.com', '08777276063', 'In Progress', NULL, '2025-01-03 21:51:24', '2025-01-03 21:52:57'),
(11, 'AcceCart', 'accecart2021@gmail.com', '08777276063', 'New', NULL, '2025-01-03 21:51:30', '2025-01-03 21:51:30'),
(12, 'RIF SK', 'kgcet@gmail.com', '08777276020', 'In Progress', NULL, '2025-01-03 21:52:00', '2025-01-03 21:53:04'),
(13, 'vhbnkbhbj', 'bhbu@gmail.com', '12345687784', 'New', NULL, '2025-01-03 21:52:25', '2025-01-03 21:52:25'),
(14, 'TAIF SK', 'tacet@gmail.com', '08777276063', 'In Progress', NULL, '2025-01-03 21:53:36', '2025-01-03 21:53:36'),
(15, 'rahul', 'taricet@gmail.com', '08777276063', 'New', NULL, '2025-01-03 21:54:25', '2025-01-03 21:54:25'),
(16, 'neher', 'accecart20@gmail.com', '08777276063', 'New', NULL, '2025-01-03 21:54:38', '2025-01-03 21:54:38'),
(17, 'sohel', 'accecart2@gmail.com', '08777276063', 'New', NULL, '2025-01-03 21:54:50', '2025-01-03 21:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `lead_history`
--

CREATE TABLE `lead_history` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `status_from` enum('New','In Progress','Closed') DEFAULT NULL,
  `status_to` enum('New','In Progress','Closed') DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `update_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lead_history`
--

INSERT INTO `lead_history` (`id`, `lead_id`, `status_from`, `status_to`, `updated_by`, `update_time`) VALUES
(1, 1, 'New', 'New', 1, '2025-01-03 11:43:04'),
(2, 3, 'New', 'In Progress', 1, '2025-01-03 20:08:43'),
(4, 9, 'New', 'Closed', 1, '2025-01-03 21:00:13'),
(5, 10, 'New', 'In Progress', 1, '2025-01-03 21:52:57'),
(6, 12, 'New', 'In Progress', 1, '2025-01-03 21:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `role` enum('Admin','Employee') NOT NULL,
  `can_add_lead` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete_lead` tinyint(1) NOT NULL DEFAULT 0,
  `can_view_leads` tinyint(1) NOT NULL DEFAULT 0,
  `can_export_leads` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Admin'),
(2, 'user1', '0192023a7bbd73250516f069df18b500', ''),
(3, 'Testuser', '6ad14ba9986e3615423dfca256d04e3f', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `lead_history`
--
ALTER TABLE `lead_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `lead_history_ibfk_1` (`lead_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `lead_history`
--
ALTER TABLE `lead_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `lead_history`
--
ALTER TABLE `lead_history`
  ADD CONSTRAINT `lead_history_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
