-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 12, 2024 at 02:07 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u297724503_irrigation`
--

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
(1, 'plantspportsmartirrigation2024@gmail.com', 'bltf uzqq fofl ckmu', '2023-02-19 03:25:24', '2024-10-09 12:37:03');

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
(1, 1, 'Has successfully signed inD', '2024-10-08 13:03:53', '2024-10-12 08:46:42'),
(2, 1, 'Requested a password reset', '2024-10-09 15:00:55', NULL),
(3, 1, 'Has successfully signed in', '2024-10-09 15:01:50', NULL),
(4, 1, 'Has successfully signed in', '2024-10-09 15:20:56', NULL),
(5, 1, 'Has successfully signed in', '2024-10-09 16:47:13', NULL),
(6, 1, 'Has successfully signed in', '2024-10-10 03:26:16', NULL),
(7, 1, 'Has successfully signed in', '2024-10-10 14:01:17', NULL),
(8, 1, 'Has successfully signed out', '2024-10-10 14:19:10', NULL),
(9, 1, 'Requested a password reset', '2024-10-10 14:19:17', NULL),
(10, 1, 'Requested a password reset', '2024-10-10 14:19:59', NULL),
(11, 1, 'Requested a password reset', '2024-10-10 14:20:31', NULL),
(12, 1, 'Has successfully signed in', '2024-10-10 14:21:04', NULL),
(13, 1, 'Has successfully signed in', '2024-10-10 14:21:16', NULL),
(14, 1, 'Has successfully signed out', '2024-10-10 14:22:18', NULL),
(15, 1, 'Has successfully signed in', '2024-10-10 14:22:28', NULL),
(16, 1, 'Has successfully signed out', '2024-10-10 16:16:57', NULL),
(17, 1, 'Has successfully signed in', '2024-10-10 16:17:27', NULL),
(18, 1, 'Has successfully signed in', '2024-10-11 01:01:25', NULL),
(19, 1, 'Has successfully signed in', '2024-10-11 03:51:31', NULL),
(20, 1, 'Profile information is updated', '2024-10-11 03:52:11', NULL),
(21, 1, 'Has successfully signed out', '2024-10-11 05:54:11', NULL),
(22, 1, 'Has successfully signed in', '2024-10-11 05:54:24', NULL),
(23, 1, 'Has successfully signed in', '2024-10-11 05:54:25', NULL),
(24, 1, 'Has successfully signed out', '2024-10-11 05:59:46', NULL),
(25, 1, 'Has successfully signed in', '2024-10-11 08:37:56', NULL),
(26, 1, 'Has successfully signed in', '2024-10-11 09:55:56', NULL),
(27, 1, 'Has successfully signed in', '2024-10-11 12:21:44', NULL),
(28, 1, 'Profile information is updated', '2024-10-11 12:22:31', NULL),
(29, 1, 'Has successfully signed out', '2024-10-11 12:26:34', NULL),
(30, 1, 'Has successfully signed in', '2024-10-11 13:43:01', NULL),
(31, 1, 'Has successfully signed out', '2024-10-11 14:42:58', NULL),
(32, 1, 'Has successfully signed out', '2024-10-11 15:08:26', NULL),
(33, 1, 'Has successfully signed in', '2024-10-11 15:08:39', NULL),
(34, 1, 'Profile information is updated', '2024-10-11 15:12:42', NULL),
(35, 1, 'Profile information is updated', '2024-10-11 15:13:01', NULL),
(36, 1, 'Profile information is updated', '2024-10-11 15:16:25', NULL),
(37, 1, 'Profile information is updated', '2024-10-11 15:16:39', NULL),
(38, 1, 'Profile information is updated', '2024-10-11 15:22:39', NULL),
(39, 1, 'Profile information is updated', '2024-10-11 15:23:06', NULL),
(40, 1, 'Has successfully signed in', '2024-10-11 15:30:55', NULL),
(41, 1, 'Has successfully signed in', '2024-10-11 16:59:41', NULL),
(42, 1, 'Has successfully signed in', '2024-10-12 06:44:25', NULL),
(43, 1, 'Has successfully signed in', '2024-10-12 07:57:57', NULL),
(44, 1, 'Has successfully signed in', '2024-10-12 08:19:23', NULL),
(45, 1, 'Profile information is updated', '2024-10-12 08:23:39', NULL),
(46, 1, 'Profile information is updated', '2024-10-12 16:25:38', NULL),
(47, 1, 'Profile information is updated', '2024-10-12 16:29:10', NULL),
(48, 1, 'Profile information is updated', '2024-10-12 16:30:10', NULL),
(49, 1, 'System Settings has been updated', '2024-10-12 16:30:28', NULL),
(50, 1, 'Profile information is updated', '2024-10-12 08:50:55', NULL),
(51, 1, 'Profile information is updated', '2024-10-12 08:51:01', NULL),
(52, 1, 'Profile information is updated', '2024-10-12 08:52:52', NULL),
(53, 1, 'Profile information is updated', '2024-10-12 08:53:01', NULL),
(54, 1, 'Has successfully signed in', '2024-10-12 12:26:42', NULL),
(55, 1, 'System Settings has been updated', '2024-10-12 12:27:43', NULL),
(56, 1, 'Has successfully signed in', '2024-10-12 13:45:07', NULL),
(57, 1, 'Has successfully signed out', '2024-10-12 14:05:31', NULL),
(58, 1, 'Has successfully signed out', '2024-10-12 14:05:56', NULL),
(59, 1, 'Has successfully signed in', '2024-10-12 14:06:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sensors`
--

CREATE TABLE `sensors` (
  `sensor_id` int(11) NOT NULL,
  `plant_name` varchar(145) DEFAULT NULL,
  `mode` varchar(50) NOT NULL,
  `dry_threshold` decimal(5,0) DEFAULT NULL,
  `watered_threshold` decimal(5,0) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensors`
--

INSERT INTO `sensors` (`sensor_id`, `plant_name`, `mode`, `dry_threshold`, `watered_threshold`, `start_date`, `end_date`) VALUES
(1, 'TALONG', 'AUTOMATIC', 600, 400, '2024-10-12 15:49:10', '2024-10-12 15:49:20'),
(2, 'OKRA', 'AUTOMATIC', 600, 400, '2024-10-12 15:53:05', '2024-10-12 15:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` int(11) NOT NULL,
  `sensor` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sensor_logs`
--

INSERT INTO `sensor_logs` (`id`, `sensor`, `status`, `created_at`) VALUES
(1, 'wifi_status', 'NO DEVICE FOUND', '2024-10-11 15:07:35'),
(2, 'pumpStatus', 'OFF', '2024-10-11 15:07:37'),
(3, 'valve1Status', 'CLOSED', '2024-10-11 15:07:39'),
(4, 'valve2Status', 'CLOSED', '2024-10-11 15:07:41'),
(5, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-11 15:07:43'),
(6, 'wifi_status', 'CONNECTED', '2024-10-11 16:59:42'),
(7, 'pumpStatus', 'ON', '2024-10-11 17:03:02'),
(8, 'valve1Status', 'OPEN', '2024-10-11 17:03:05'),
(9, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-11 17:03:08'),
(10, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 03:23:06'),
(11, 'pumpStatus', 'OFF', '2024-10-12 06:44:27'),
(12, 'valve1Status', 'CLOSED', '2024-10-12 06:44:31'),
(13, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 06:44:33'),
(14, 'wifi_status', 'CONNECTED', '2024-10-12 06:46:06'),
(15, 'pumpStatus', 'ON', '2024-10-12 06:48:51'),
(16, 'valve1Status', 'OPEN', '2024-10-12 06:48:54'),
(17, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 06:48:58'),
(18, 'pumpStatus', 'OFF', '2024-10-12 06:58:06'),
(19, 'valve1Status', 'CLOSED', '2024-10-12 06:58:08'),
(20, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 06:58:10'),
(21, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 07:03:59'),
(22, 'wifi_status', 'CONNECTED', '2024-10-12 07:15:44'),
(23, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:15:46'),
(24, 'pumpStatus', 'ON', '2024-10-12 07:16:28'),
(25, 'valve1Status', 'OPEN', '2024-10-12 07:16:30'),
(26, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 07:18:02'),
(27, 'pumpStatus', 'OFF', '2024-10-12 07:18:04'),
(28, 'valve1Status', 'CLOSED', '2024-10-12 07:18:06'),
(29, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:18:08'),
(30, 'wifi_status', 'CONNECTED', '2024-10-12 07:19:42'),
(31, 'pumpStatus', 'ON', '2024-10-12 07:19:45'),
(32, 'valve1Status', 'OPEN', '2024-10-12 07:19:48'),
(33, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:19:51'),
(34, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 07:21:54'),
(35, 'pumpStatus', 'OFF', '2024-10-12 07:21:57'),
(36, 'valve1Status', 'CLOSED', '2024-10-12 07:22:00'),
(37, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:22:04'),
(38, 'wifi_status', 'CONNECTED', '2024-10-12 07:22:22'),
(39, 'pumpStatus', 'ON', '2024-10-12 07:22:25'),
(40, 'valve1Status', 'OPEN', '2024-10-12 07:22:28'),
(41, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:22:31'),
(42, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 07:29:52'),
(43, 'pumpStatus', 'OFF', '2024-10-12 07:29:54'),
(44, 'valve1Status', 'CLOSED', '2024-10-12 07:29:55'),
(45, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:29:57'),
(46, 'wifi_status', 'CONNECTED', '2024-10-12 07:30:12'),
(47, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:30:14'),
(48, 'pumpStatus', 'ON', '2024-10-12 07:30:47'),
(49, 'valve1Status', 'OPEN', '2024-10-12 07:30:49'),
(50, 'pumpStatus', 'OFF', '2024-10-12 07:31:19'),
(51, 'valve1Status', 'CLOSED', '2024-10-12 07:31:21'),
(52, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:31:23'),
(53, 'pumpStatus', 'ON', '2024-10-12 07:31:31'),
(54, 'valve1Status', 'OPEN', '2024-10-12 07:31:33'),
(55, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:31:37'),
(56, 'pumpStatus', 'OFF', '2024-10-12 07:31:45'),
(57, 'valve1Status', 'CLOSED', '2024-10-12 07:31:47'),
(58, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:31:49'),
(59, 'pumpStatus', 'ON', '2024-10-12 07:31:51'),
(60, 'valve1Status', 'OPEN', '2024-10-12 07:31:53'),
(61, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:31:55'),
(62, 'pumpStatus', 'OFF', '2024-10-12 07:32:05'),
(63, 'valve1Status', 'CLOSED', '2024-10-12 07:32:07'),
(64, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:32:09'),
(65, 'pumpStatus', 'ON', '2024-10-12 07:32:11'),
(66, 'valve1Status', 'OPEN', '2024-10-12 07:32:13'),
(67, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:32:14'),
(68, 'pumpStatus', 'OFF', '2024-10-12 07:32:21'),
(69, 'valve1Status', 'CLOSED', '2024-10-12 07:32:24'),
(70, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:32:26'),
(71, 'pumpStatus', 'ON', '2024-10-12 07:35:09'),
(72, 'valve1Status', 'OPEN', '2024-10-12 07:35:12'),
(73, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:35:14'),
(74, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 07:36:18'),
(75, 'pumpStatus', 'OFF', '2024-10-12 07:36:20'),
(76, 'valve1Status', 'CLOSED', '2024-10-12 07:36:21'),
(77, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:36:23'),
(78, 'wifi_status', 'CONNECTED', '2024-10-12 07:38:06'),
(79, 'pumpStatus', 'ON', '2024-10-12 07:38:16'),
(80, 'valve1Status', 'OPEN', '2024-10-12 07:38:17'),
(81, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:38:19'),
(82, 'pumpStatus', 'OFF', '2024-10-12 07:38:30'),
(83, 'valve1Status', 'CLOSED', '2024-10-12 07:38:31'),
(84, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:38:33'),
(85, 'pumpStatus', 'ON', '2024-10-12 07:38:50'),
(86, 'valve1Status', 'OPEN', '2024-10-12 07:38:51'),
(87, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:38:54'),
(88, 'pumpStatus', 'OFF', '2024-10-12 07:38:56'),
(89, 'valve1Status', 'CLOSED', '2024-10-12 07:38:58'),
(90, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:39:00'),
(91, 'pumpStatus', 'ON', '2024-10-12 07:41:00'),
(92, 'valve1Status', 'OPEN', '2024-10-12 07:41:01'),
(93, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:41:03'),
(94, 'pumpStatus', 'OFF', '2024-10-12 07:41:10'),
(95, 'valve1Status', 'CLOSED', '2024-10-12 07:41:11'),
(96, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:41:13'),
(97, 'pumpStatus', 'ON', '2024-10-12 07:41:15'),
(98, 'valve1Status', 'OPEN', '2024-10-12 07:41:17'),
(99, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:41:19'),
(100, 'pumpStatus', 'OFF', '2024-10-12 07:43:19'),
(101, 'valve1Status', 'CLOSED', '2024-10-12 07:43:21'),
(102, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:44:27'),
(103, 'pumpStatus', 'ON', '2024-10-12 07:46:11'),
(104, 'valve1Status', 'OPEN', '2024-10-12 07:46:13'),
(105, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:46:14'),
(106, 'pumpStatus', 'OFF', '2024-10-12 07:46:17'),
(107, 'valve1Status', 'CLOSED', '2024-10-12 07:46:19'),
(108, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:46:20'),
(109, 'pumpStatus', 'ON', '2024-10-12 07:47:01'),
(110, 'valve1Status', 'OPEN', '2024-10-12 07:47:02'),
(111, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:47:05'),
(112, 'pumpStatus', 'OFF', '2024-10-12 07:47:11'),
(113, 'valve1Status', 'CLOSED', '2024-10-12 07:47:13'),
(114, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:47:15'),
(115, 'pumpStatus', 'ON', '2024-10-12 07:47:49'),
(116, 'valve1Status', 'OPEN', '2024-10-12 07:47:50'),
(117, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:47:52'),
(118, 'pumpStatus', 'OFF', '2024-10-12 07:47:54'),
(119, 'valve1Status', 'CLOSED', '2024-10-12 07:47:56'),
(120, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:47:58'),
(121, 'waterStatus', 'WATER LEVEL IS NORMAL', '2024-10-12 07:50:50'),
(122, 'waterStatus', 'WATER LEVEL IS LOW', '2024-10-12 07:57:58'),
(123, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 08:19:24'),
(124, 'wifi_status', 'CONNECTED', '2024-10-12 13:34:13'),
(125, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 13:37:32'),
(126, 'wifi_status', 'CONNECTED', '2024-10-12 13:45:08'),
(127, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 13:46:49'),
(128, 'wifi_status', 'CONNECTED', '2024-10-12 13:48:57'),
(129, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 13:51:31'),
(130, 'wifi_status', 'CONNECTED', '2024-10-12 13:51:46'),
(131, 'wifi_status', 'NO DEVICE FOUND', '2024-10-12 13:55:11');

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
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`id`, `system_name`, `system_phone_number`, `system_email`, `system_logo`, `system_favicon`, `system_color`, `system_copy_right`, `created_at`, `updated_at`) VALUES
(1, 'PLANT SUPPORT', '9776621925', 'plantspportsmartirrigation2024@gmail.com', 'plant-support-logo.svg', 'plant-support-icon.png', NULL, 'COPYRIGHT © 2024 - PLANTSUPPORT. ALL RIGHTS RESERVED.', '2023-02-19 00:16:44', '2024-10-12 12:27:43');

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
  `user_type` varchar(14) DEFAULT NULL COMMENT 'superadmin=0,\r\nadmin=1,\r\nagent=2,\r\nuser=3',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `date_of_birth`, `age`, `civil_status`, `phone_number`, `email`, `password`, `profile`, `tokencode`, `account_status`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Mark', 'Suba', 'Mendoza', 'MALE', NULL, NULL, 'SINGLE', '9776621929', 'marklawrens821@gmail.com', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', '4a4a9422e51800d30745849397fe7a00', 'active', '1', '2023-11-19 04:14:08', '2024-10-12 08:53:01');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`sensor_id`);

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
-- AUTO_INCREMENT for table `email_config`
--
ALTER TABLE `email_config`
  MODIFY `id` int(145) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `sensors`
--
ALTER TABLE `sensors`
  MODIFY `sensor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `system_config`
--
ALTER TABLE `system_config`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
