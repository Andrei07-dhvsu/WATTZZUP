-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 07, 2025 at 04:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_energy_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_access_keys`
--

CREATE TABLE `admin_access_keys` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `access_key` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appliances`
--

CREATE TABLE `appliances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(100) DEFAULT NULL,
  `switch_id` varchar(50) NOT NULL,
  `appliance_name` varchar(100) NOT NULL,
  `status` enum('ON','OFF') DEFAULT 'OFF',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` int(11) NOT NULL,
  `submeter_id` varchar(111) NOT NULL,
  `consumption_date` date NOT NULL,
  `kwh_start` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_end` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_used` decimal(10,3) GENERATED ALWAYS AS (`kwh_end` - `kwh_start`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(145) NOT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'wattzzup2025@gmail.com', 'lpbo qgdt topd cjud\n', '2023-02-18 19:25:24', '2025-10-07 14:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `energy_alerts`
--

CREATE TABLE `energy_alerts` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `submeter_id` varchar(145) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kwh_used` decimal(10,2) NOT NULL,
  `kwh_limit` decimal(10,2) NOT NULL,
  `alert_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_recaptcha_api`
--

CREATE TABLE `google_recaptcha_api` (
  `Id` int(11) NOT NULL,
  `site_key` varchar(145) DEFAULT NULL,
  `site_secret_key` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `google_recaptcha_api`
--

INSERT INTO `google_recaptcha_api` (`Id`, `site_key`, `site_secret_key`, `created_at`, `updated_at`) VALUES
(1, '6LdiQZQhAAAAABpaNFtJpgzGpmQv2FwhaqNj2azh', '6LdiQZQhAAAAAByS6pnNjOs9xdYXMrrW2OeTFlrm', '2023-02-18 08:57:18', '2023-08-11 08:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `kwh_cost`
--

CREATE TABLE `kwh_cost` (
  `id` int(14) NOT NULL,
  `admin_id` int(14) DEFAULT NULL,
  `cost` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_consumption`
--

CREATE TABLE `monthly_consumption` (
  `id` int(11) NOT NULL,
  `submeter_id` varchar(50) NOT NULL,
  `month_year` char(7) NOT NULL,
  `kwh_start_month` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_end_month` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_used` decimal(10,3) GENERATED ALWAYS AS (`kwh_end_month` - `kwh_start_month`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_energy_cost`
--

CREATE TABLE `monthly_energy_cost` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `submeter_id` varchar(145) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `total_kwh` decimal(10,2) NOT NULL,
  `kwh_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `owner_id` int(12) DEFAULT NULL,
  `user_id` varchar(145) DEFAULT NULL,
  `submeter_id` varchar(150) DEFAULT NULL,
  `kwh_limit` int(12) DEFAULT NULL,
  `date_of_installation` date DEFAULT NULL,
  `status` enum('occupied','vacant') DEFAULT 'vacant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `notified_exceed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE `system_config` (
  `id` int(14) NOT NULL,
  `system_name` varchar(145) DEFAULT NULL,
  `system_phone_number` varchar(145) DEFAULT NULL,
  `system_email` varchar(145) DEFAULT NULL,
  `system_logo` varchar(145) DEFAULT NULL,
  `system_favicon` varchar(145) DEFAULT NULL,
  `system_color` varchar(145) DEFAULT NULL,
  `system_copy_right` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`id`, `system_name`, `system_phone_number`, `system_email`, `system_logo`, `system_favicon`, `system_color`, `system_copy_right`, `created_at`, `updated_at`) VALUES
(1, 'WATTZUP - SMART ENERGY MANAGEMENT', '9776621925', 'wattzup2025@gmail.com', 'wattzup-high-resolution-logo-transparent.png', 'smart-energy-logo.png', NULL, 'COPYRIGHT © 2025 - WATTZUP - SMART ENERGY MANAGEMENT. ALL RIGHTS RESERVED.', '2023-02-18 16:16:44', '2025-09-18 13:16:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(145) DEFAULT NULL,
  `middle_name` varchar(145) DEFAULT NULL,
  `last_name` varchar(145) DEFAULT NULL,
  `sex` varchar(145) DEFAULT NULL COMMENT 'male=1, female=2',
  `date_of_birth` varchar(145) DEFAULT NULL,
  `age` varchar(145) DEFAULT NULL,
  `civil_status` varchar(145) DEFAULT NULL,
  `phone_number` varchar(145) DEFAULT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `profile` varchar(1145) NOT NULL DEFAULT 'profile.png',
  `tokencode` varchar(145) DEFAULT NULL,
  `account_status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `user_type` varchar(14) DEFAULT NULL COMMENT 'superadmin=0,\r\nadmin=1,\r\nuser=2\r\n',
  `access_key` varchar(145) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `date_of_birth`, `age`, `civil_status`, `phone_number`, `email`, `password`, `profile`, `tokencode`, `account_status`, `user_type`, `access_key`, `created_at`, `updated_at`) VALUES
(1, 'Juan', 'Santos', 'David', 'FEMALE', NULL, NULL, 'MARRIED', '9776621929', 'wattzzup2025@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', '4cbd8e4ca9f7d509e7a3e86233331bcf', 'active', '1', NULL, '2023-11-18 20:14:08', '2025-10-07 14:16:37');

-- --------------------------------------------------------

--
-- Table structure for table `whole_month_daily_consumption`
--

CREATE TABLE `whole_month_daily_consumption` (
  `id` int(11) NOT NULL,
  `submeter_id` varchar(111) NOT NULL,
  `consumption_date` date NOT NULL,
  `kwh_start` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_end` decimal(10,3) NOT NULL DEFAULT 0.000,
  `kwh_used` decimal(10,3) GENERATED ALWAYS AS (`kwh_end` - `kwh_start`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_access_keys`
--
ALTER TABLE `admin_access_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `appliances`
--
ALTER TABLE `appliances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `switchId` (`switch_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_submeter_date` (`submeter_id`,`consumption_date`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `energy_alerts`
--
ALTER TABLE `energy_alerts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_date` (`room_id`,`alert_date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `kwh_cost`
--
ALTER TABLE `kwh_cost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `monthly_consumption`
--
ALTER TABLE `monthly_consumption`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_submeter_month` (`submeter_id`,`month_year`);

--
-- Indexes for table `monthly_energy_cost`
--
ALTER TABLE `monthly_energy_cost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_monthly` (`admin_id`,`submeter_id`,`year`,`month`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whole_month_daily_consumption`
--
ALTER TABLE `whole_month_daily_consumption`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_submeter_date` (`submeter_id`,`consumption_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_access_keys`
--
ALTER TABLE `admin_access_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appliances`
--
ALTER TABLE `appliances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `energy_alerts`
--
ALTER TABLE `energy_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kwh_cost`
--
ALTER TABLE `kwh_cost`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_consumption`
--
ALTER TABLE `monthly_consumption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_energy_cost`
--
ALTER TABLE `monthly_energy_cost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `whole_month_daily_consumption`
--
ALTER TABLE `whole_month_daily_consumption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_access_keys`
--
ALTER TABLE `admin_access_keys`
  ADD CONSTRAINT `admin_access_keys_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `appliances`
--
ALTER TABLE `appliances`
  ADD CONSTRAINT `appliances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appliances_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `energy_alerts`
--
ALTER TABLE `energy_alerts`
  ADD CONSTRAINT `energy_alerts_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `energy_alerts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kwh_cost`
--
ALTER TABLE `kwh_cost`
  ADD CONSTRAINT `kwh_cost_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


