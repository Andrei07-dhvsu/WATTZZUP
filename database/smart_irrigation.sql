-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2025 at 02:24 AM
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
-- Database: `smart_irrigation`
--

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `id` int(14) NOT NULL,
  `day` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day`
--

INSERT INTO `day` (`id`, `day`, `created_at`, `updated_at`) VALUES
(1, 'Monday', '2023-12-09 17:22:38', NULL),
(2, 'Tuesday', '2023-12-09 17:22:38', '2023-12-09 17:23:06'),
(3, 'Wednesday', '2023-12-09 17:22:38', '2023-12-09 17:23:13'),
(4, 'Thursday', '2023-12-09 17:22:38', '2023-12-09 17:23:19'),
(5, 'Friday', '2023-12-09 17:22:38', '2023-12-09 17:23:23'),
(6, 'Saturday', '2023-12-09 17:22:38', '2023-12-09 17:23:28'),
(7, 'Sunday', '2023-12-09 17:22:38', '2023-12-09 17:23:31');

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
(1, 'plantspportsmartirrigation2024@gmail.com', 'bltf uzqq fofl ckmu', '2023-02-19 03:25:24', '2024-10-09 12:37:25');

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
(1, '6LdiQZQhAAAAABpaNFtJpgzGpmQv2FwhaqNj2azh', '6LdiQZQhAAAAAByS6pnNjOs9xdYXMrrW2OeTFlrm', '2023-02-18 16:57:18', '2023-08-11 16:48:04');

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

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `activity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Profile information is updated', '2024-10-09 06:12:45', NULL),
(2, 1, 'System Settings has been updated', '2024-10-09 06:15:43', NULL),
(3, 1, 'System SMTP has been updated', '2024-10-09 12:34:12', NULL),
(4, 1, 'Requested a password reset', '2024-10-09 12:34:31', NULL),
(5, 1, 'Requested a password reset', '2024-10-09 12:35:46', NULL),
(6, 1, 'Has successfully signed in', '2024-10-09 12:36:21', NULL),
(7, 1, 'System Settings has been updated', '2024-10-09 12:37:13', NULL),
(8, 1, 'System SMTP has been updated', '2024-10-09 12:37:25', NULL),
(9, 1, 'Requested a password reset', '2024-10-09 12:38:39', NULL),
(10, 1, 'Requested a password reset', '2024-10-09 12:39:48', NULL),
(11, 1, 'Requested a password reset', '2024-10-09 12:41:04', NULL),
(12, 1, 'Has successfully signed in', '2024-10-09 12:41:41', NULL),
(13, 1, 'Has successfully signed in', '2024-10-09 12:51:00', NULL),
(14, 1, 'Has successfully signed in', '2024-10-10 04:50:29', NULL),
(15, 1, 'Has successfully signed in', '2024-10-10 10:39:20', NULL),
(16, 1, 'Has successfully signed in', '2024-10-10 10:39:32', NULL),
(17, 1, 'Has successfully signed in', '2024-10-10 10:41:57', NULL),
(18, 1, 'Has successfully signed in', '2024-10-10 10:42:31', NULL),
(19, 1, 'Has successfully signed in', '2024-10-10 10:42:35', NULL),
(20, 1, 'Has successfully signed in', '2024-10-10 10:42:40', NULL),
(21, 1, 'Has successfully signed out', '2024-10-10 10:43:19', NULL),
(22, 1, 'Has successfully signed in', '2024-10-10 10:43:24', NULL),
(23, 1, 'Has successfully signed out', '2024-10-10 14:15:48', NULL),
(24, 1, 'Requested a password reset', '2024-10-10 14:16:00', NULL),
(25, 1, 'Requested a password reset', '2024-10-10 14:16:15', NULL),
(26, 1, 'Requested a password reset', '2024-10-10 14:17:24', NULL),
(27, 1, 'Requested a password reset', '2024-10-10 14:18:15', NULL),
(28, 1, 'Has successfully signed in', '2024-10-10 16:19:18', NULL),
(29, 1, 'Has successfully signed out', '2024-10-11 05:36:41', NULL),
(30, 1, 'Has successfully signed in', '2024-10-11 05:57:06', NULL),
(31, 1, 'Has successfully signed out', '2024-10-11 05:58:06', NULL),
(32, 1, 'Has successfully signed in', '2024-10-11 15:02:01', NULL),
(33, 1, 'Profile information is updated', '2024-10-12 08:24:45', NULL),
(34, 1, 'Has successfully signed in', '2024-10-15 06:20:32', NULL),
(35, 1, 'Has successfully signed in', '2024-10-21 07:00:36', NULL),
(36, 1, 'New Plant has been added \'.TALONG.\'', '2024-10-22 03:40:40', NULL),
(37, 1, 'Plants () has been deleted', '2024-10-22 04:46:33', NULL),
(38, 1, 'Has successfully signed out', '2024-10-23 08:08:11', NULL),
(39, 6, 'Has successfully signed in', '2024-10-23 12:47:38', NULL),
(40, 1, 'Has successfully signed in', '2024-10-23 12:48:04', NULL),
(41, 1, 'Has successfully signed out', '2024-10-23 12:48:14', NULL),
(42, 6, 'Has successfully signed in', '2024-10-23 12:48:28', NULL),
(43, 6, 'Has successfully signed out', '2024-10-25 03:04:45', NULL),
(44, 1, 'Has successfully signed in', '2024-10-25 03:05:03', NULL),
(45, 1, 'Has successfully signed out', '2024-10-25 11:42:21', NULL),
(46, 6, 'Has successfully signed in', '2024-10-25 11:47:39', NULL),
(47, 6, 'Has successfully signed out', '2024-10-25 11:52:16', NULL),
(48, 1, 'Has successfully signed in', '2024-10-25 11:53:13', NULL),
(49, 6, 'Has successfully signed in', '2024-10-25 11:53:49', NULL),
(50, 1, 'Has successfully signed in', '2024-10-31 11:50:19', NULL),
(51, 1, 'Has successfully signed out', '2024-10-31 12:19:02', NULL),
(52, 1, 'Has successfully signed in', '2024-10-31 12:19:16', NULL),
(53, 1, 'Sensor 1 Thresholds successfully updated', '2024-10-31 12:19:45', NULL),
(54, 1, 'Sensor 2 Thresholds successfully updated', '2024-10-31 12:20:29', NULL),
(55, 1, 'Sensor 1 Thresholds successfully updated', '2024-10-31 12:20:48', NULL),
(56, 1, 'Sensor 2 Thresholds successfully updated', '2024-10-31 12:27:39', NULL),
(57, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:25:28', NULL),
(58, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:26:32', NULL),
(59, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:27:18', NULL),
(60, 1, 'Sensor 2 Thresholds successfully updated', '2024-11-01 02:28:21', NULL),
(61, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:28:49', NULL),
(62, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:28:53', NULL),
(63, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:29:21', NULL),
(64, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:30:01', NULL),
(65, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-01 02:35:38', NULL),
(66, 1, 'Has successfully signed in', '2024-11-02 15:14:47', NULL),
(67, 1, 'Has successfully signed out', '2024-11-02 15:32:12', NULL),
(68, 6, 'Has successfully signed in', '2024-11-02 15:32:35', NULL),
(69, 6, 'Sensor 1 Thresholds successfully updated', '2024-11-02 15:32:56', NULL),
(70, 6, 'Sensor 2 Thresholds successfully updated', '2024-11-02 15:33:03', NULL),
(71, 7, 'Has successfully signed in', '2024-11-14 12:37:11', NULL),
(72, 7, 'Has successfully signed out', '2024-11-14 12:37:29', NULL),
(73, 1, 'Requested a password reset', '2024-11-14 12:38:10', NULL),
(74, 7, 'Requested a password reset', '2024-11-14 12:39:04', NULL),
(75, 1, 'Has successfully signed in', '2024-11-14 12:39:40', NULL),
(76, 1, 'Sensor 1 Thresholds successfully updated', '2024-11-14 12:58:30', NULL),
(77, 1, 'Has successfully signed out', '2024-11-14 15:27:27', NULL),
(78, 7, 'Has successfully signed in', '2024-11-14 15:27:48', NULL),
(79, 7, 'Password has been update', '2024-11-14 15:28:02', NULL),
(80, 7, 'Has successfully signed out', '2024-11-14 15:31:40', NULL),
(81, 1, 'Has successfully signed in', '2024-11-14 15:31:49', NULL),
(82, 1, 'Has successfully signed in', '2025-01-25 09:14:41', NULL),
(83, 1, 'Has successfully signed out', '2025-01-25 09:29:58', NULL),
(84, 1, 'Has successfully signed in', '2025-01-25 09:30:08', NULL),
(85, 1, 'Has successfully signed out', '2025-01-25 09:30:42', NULL),
(86, 1, 'Has successfully signed in', '2025-01-25 09:30:52', NULL),
(87, 1, 'Has successfully signed out', '2025-01-25 09:31:25', NULL),
(88, 1, 'Has successfully signed in', '2025-01-25 11:13:34', NULL),
(89, 1, 'Has successfully signed out', '2025-01-25 11:29:46', NULL),
(90, 1, 'Has successfully signed in', '2025-01-25 11:30:04', NULL),
(91, 7, 'Has successfully signed in', '2025-01-29 12:29:37', NULL),
(92, 7, 'Has successfully signed out', '2025-01-29 12:31:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `id` int(145) NOT NULL,
  `plant_name` varchar(145) DEFAULT NULL,
  `dry_threshold` decimal(5,0) DEFAULT NULL,
  `watered_threshold` decimal(5,0) DEFAULT NULL,
  `status` enum('available','disabled') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`id`, `plant_name`, `dry_threshold`, `watered_threshold`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TALONG', 600, 400, 'available', '2024-10-22 03:40:40', '2024-10-22 06:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `sensorIrrigatedStatus`
--

CREATE TABLE `sensorIrrigatedStatus` (
  `id` int(11) NOT NULL,
  `sensor` varchar(255) NOT NULL,
  `status` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensorIrrigatedStatus`
--

INSERT INTO `sensorIrrigatedStatus` (`id`, `sensor`, `status`, `created_at`) VALUES
(1, 'sensor1IrrigatedAM', 'false', '2024-11-01 15:38:15'),
(2, 'sensor1IrrigatedPM', 'false', '2024-11-01 15:38:15'),
(3, 'sensor2IrrigatedAM', 'false', '2024-11-01 15:38:15'),
(4, 'sensor2IrrigatedPM', 'false', '2024-11-01 15:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `sensors`
--

CREATE TABLE `sensors` (
  `sensor_id` int(11) NOT NULL,
  `plant_id` int(14) DEFAULT NULL,
  `mode` varchar(50) NOT NULL,
  `water_amount_am` int(14) DEFAULT NULL,
  `water_amount_pm` int(14) DEFAULT NULL,
  `start_time_am` time DEFAULT NULL,
  `start_time_pm` time DEFAULT NULL,
  `selected_days` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensors`
--

INSERT INTO `sensors` (`sensor_id`, `plant_id`, `mode`, `water_amount_am`, `water_amount_pm`, `start_time_am`, `start_time_pm`, `selected_days`, `created_at`, `updated_at`) VALUES
(1, 1, 'SCHEDULE', 15, 10, '20:58:03', '16:00:00', '1, 2, 3, 4, 5, 6, 7', '2024-10-10 12:20:50', '2024-11-14 12:58:30'),
(2, 1, 'AUTOMATIC', 10, 15, '09:00:00', '18:00:00', '2, 3, 4, 5, 6, 7', '2024-10-10 12:20:50', '2024-11-02 15:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` int(11) NOT NULL,
  `sensor` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_logs`
--

INSERT INTO `sensor_logs` (`id`, `sensor`, `status`, `created_at`) VALUES
(1, 'wifi_status', 'NO DEVICE FOUND', '2024-10-25 11:47:50'),
(2, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-25 11:47:56'),
(3, 'wifi_status', 'CONNECTED', '2024-10-31 11:54:02'),
(4, 'wifi_status', 'NO DEVICE FOUND', '2024-10-31 12:08:19'),
(5, 'wifi_status', 'CONNECTED', '2024-10-31 12:09:18'),
(6, 'wifi_status', 'NO DEVICE FOUND', '2024-10-31 12:11:17'),
(7, 'wifi_status', 'CONNECTED', '2024-10-31 12:11:23'),
(8, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-31 12:11:27'),
(9, 'wifi_status', 'NO DEVICE FOUND', '2024-10-31 12:16:28'),
(10, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-31 12:16:32'),
(11, 'wifi_status', 'CONNECTED', '2024-10-31 12:16:38'),
(12, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-31 12:16:41'),
(13, 'alertMessage1', 'Starting Irrigating your plant null in Sensor 1', '2024-10-31 12:16:45'),
(14, 'alertMessage1', '', '2024-11-01 02:29:35'),
(15, 'wifi_status', 'NO DEVICE FOUND', '2024-11-14 12:37:12'),
(16, 'waterStatus', 'WATER LEVEL IS LOW', '2024-11-14 12:37:12'),
(17, 'wifi_status', 'CONNECTED', '2024-11-14 12:47:07'),
(18, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-11-14 12:47:11'),
(19, 'wifi_status', 'NO DEVICE FOUND', '2024-11-14 14:17:10'),
(20, 'waterStatus', 'WATER LEVEL IS LOW', '2024-11-14 14:17:15'),
(21, 'wifi_status', 'CONNECTED', '2024-11-14 14:18:10'),
(22, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-11-14 14:18:14'),
(23, 'wifi_status', 'NO DEVICE FOUND', '2024-11-14 14:29:10'),
(24, 'waterStatus', 'WATER LEVEL IS LOW', '2024-11-14 14:29:15'),
(25, 'wifi_status', 'CONNECTED', '2024-11-14 14:30:10'),
(26, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-11-14 14:30:14');

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
(1, 'PLANT SUPPORT', '9776621925', 'plantspportsmartirrigation2024@gmail.com', 'plant-support-logo.svg', 'plant-support-icon.png', NULL, 'COPYRIGHT © 2024 - PLANTSUPPORT. ALL RIGHTS RESERVED.', '2023-02-19 00:16:44', '2024-10-10 14:04:44');

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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `date_of_birth`, `age`, `civil_status`, `phone_number`, `email`, `password`, `profile`, `tokencode`, `account_status`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Andreis', 'manalansan', 'viscayno', 'FEMALE', NULL, NULL, 'MARRIED', '9776621929', 'andreis.m.viscayno@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', '4cbd8e4ca9f7d509e7a3e86233331bcf', 'active', '1', '2023-11-19 04:14:08', '2024-11-14 12:34:25'),
(3, 'andrei', 'wg l1', 'k sgf', NULL, NULL, NULL, NULL, NULL, 'amviscayno@dhvsu.edu.phs', 'b88173de52b025d4b26ddcf554d0b9b2', 'profile.png', '2697a66c2870c3171930dd703a89d82a', 'active', '2', '2024-10-23 12:29:40', '2024-10-23 12:38:43'),
(4, 'andrei', 'manalansan', 'viscayno', NULL, NULL, NULL, NULL, NULL, 'amviscayno@dhvsu.edu.phs', '3bc2e6a0abbed11f629d718981f24111', 'profile.png', '2ab37eab89c0b1021ff35f5d025114fc', 'disabled', '2', '2024-10-23 12:40:36', '2024-11-02 15:24:32'),
(5, 'asdf', 'sdfsd', 'ssf', NULL, NULL, NULL, NULL, NULL, 'amviscayno@dhvsu.edu.phs', 'c9522772becd60a076f2954d57533cec', 'profile.png', 'f9ff227471a37263dc3ed747ee24a45a', 'disabled', '2', '2024-10-23 12:42:08', '2024-11-02 15:31:11'),
(6, 'ds', 'sd', 'sd', NULL, NULL, NULL, NULL, NULL, 'amviscayno@dhvsu.edu.ph', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', '44c200af09e01ec5a132a793fb3acb10', 'active', '2', '2024-10-23 12:44:57', '2024-10-25 11:47:32'),
(7, 'andrei', 'manalnasna', 'viscayno', NULL, NULL, NULL, NULL, NULL, 'andrei.m.viscayno@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', 'b430282e4e2d6bebf8a6b013d90490b0', 'active', '2', '2024-11-14 12:35:50', '2024-11-14 15:28:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensorIrrigatedStatus`
--
ALTER TABLE `sensorIrrigatedStatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`sensor_id`),
  ADD KEY `plant_id` (`plant_id`);

--
-- Indexes for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `id` int(145) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sensorIrrigatedStatus`
--
ALTER TABLE `sensorIrrigatedStatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sensors`
--
ALTER TABLE `sensors`
  MODIFY `sensor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sensors`
--
ALTER TABLE `sensors`
  ADD CONSTRAINT `sensors_ibfk_1` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
