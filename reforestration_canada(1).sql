-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 14, 2021 at 08:50 PM
-- Server version: 5.7.33-0ubuntu0.16.04.1
-- PHP Version: 5.6.40-47+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reforestration_canada`
--

-- --------------------------------------------------------

--
-- Table structure for table `rc_data_log`
--

CREATE TABLE `rc_data_log` (
  `id` int(11) NOT NULL,
  `formdata` text COLLATE utf8_unicode_ci,
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `method_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rc_data_log`
--

INSERT INTO `rc_data_log` (`id`, `formdata`, `ip`, `method_name`, `created_at`) VALUES
(1, '[]', '127.0.0.1', 'index', '2021-06-12 21:02:49'),
(2, '[]', '127.0.0.1', 'index', '2021-06-12 21:06:03'),
(3, '[]', '127.0.0.1', 'index', '2021-06-12 21:06:41'),
(4, '[]', '127.0.0.1', 'index', '2021-06-12 21:09:06'),
(5, '[]', '::1', 'register', '2021-06-13 17:23:35'),
(6, '[]', '::1', 'register', '2021-06-13 17:25:24'),
(7, '[]', '::1', 'register', '2021-06-13 17:25:36'),
(8, '[]', '::1', 'register', '2021-06-13 17:27:00'),
(9, '[]', '::1', 'register', '2021-06-13 17:30:17'),
(10, '[]', '::1', 'register', '2021-06-13 17:32:16'),
(11, '[]', '::1', 'register', '2021-06-13 17:32:24'),
(12, '[]', '::1', 'register', '2021-06-13 17:32:25'),
(13, '[]', '127.0.0.1', 'index', '2021-06-13 17:42:11'),
(14, '[]', '::1', 'register', '2021-06-13 18:35:23'),
(15, '[]', '::1', 'index', '2021-06-13 18:36:05'),
(16, '[]', '::1', 'index', '2021-06-13 18:38:09'),
(17, '{"name":"Maninder Singh","email":"admin@maninder.xyz","password":"123456"}', '::1', 'index', '2021-06-13 18:40:51'),
(18, '{"name":"Maninder Singh","email":"admin@maninder.xyz","password":"123456","confirm_password":"12345"}', '::1', 'index', '2021-06-13 18:41:35'),
(19, '{"name":"Maninder Singh","email":"admin@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 18:41:40'),
(20, '{"name":"Maninder Singh","email":"admin@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 18:49:24'),
(21, '{"name":"Maninder Singh","email":"admin1@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:04:45'),
(22, '{"name":"Maninder Singh","email":"admin1@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:07:11'),
(23, '{"name":"Maninder Singh","email":"admin2@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:11'),
(24, '{"name":"Maninder Singh","email":"admin2@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:16'),
(25, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:25'),
(26, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:28'),
(27, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:56'),
(28, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:40:59'),
(29, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:42:22'),
(30, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:43:04'),
(31, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:43:21'),
(32, '{"name":"Maninder Singh","email":"admin3@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:43:37'),
(33, '{"name":"Maninder Singh","email":"admin5@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:43:47'),
(34, '{"name":"Maninder Singh","email":"admin5@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:44:22'),
(35, '{"name":"Maninder Singh","email":"admin5@maninder.xyz","password":"123456"}', '::1', 'index', '2021-06-13 19:49:08'),
(36, '{"name":"Maninder Singh","email":"admin5@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 19:49:13'),
(37, '{"name":"Maninder Singh","email":"admin5@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 20:11:58'),
(38, '{"name":"Maninder Singh","email":"admin6@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 21:30:10'),
(39, '{"name":"Maninder Singh","email":"admin6@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 21:35:31'),
(40, '{"name":"Maninder Singh","email":"admin8@maninder.xyz","password":"123456","confirm_password":"123456"}', '::1', 'index', '2021-06-13 21:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `rc_users`
--

CREATE TABLE `rc_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `login_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'native' COMMENT 'native,facebook,google',
  `facebook_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verify` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `profile_pic` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rc_users`
--

INSERT INTO `rc_users` (`id`, `name`, `email`, `password`, `login_type`, `facebook_id`, `google_id`, `email_verify`, `status`, `profile_pic`, `created_at`) VALUES
(1, 'Maninder Singh', 'admin@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', '1234562sfs', '1234562sfs', 1, 1, NULL, '2021-06-13 18:41:41'),
(2, 'Maninder Singh', 'admin1@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 19:04:46'),
(3, 'Maninder Singh', 'admin2@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 19:40:11'),
(4, 'Maninder Singh', 'admin3@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 19:40:25'),
(5, 'Maninder Singh', 'admin5@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 19:43:47'),
(6, 'Maninder Singh', 'admin6@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 21:30:11'),
(7, 'Maninder Singh', 'admin8@maninder.xyz', 'e10adc3949ba59abbe56e057f20f883e', 'native', NULL, NULL, 0, 1, NULL, '2021-06-13 21:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `rc_users_devices`
--

CREATE TABLE `rc_users_devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app',
  `device_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'android',
  `device_id` text COLLATE utf8_unicode_ci NOT NULL,
  `app_auth_token` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rc_users_devices`
--

INSERT INTO `rc_users_devices` (`id`, `user_id`, `login_type`, `device_type`, `device_id`, `app_auth_token`, `created_at`) VALUES
(1, 1, 'app', 'android', 'android', 'aef93f37fc39975ae65a0e084095286e', '2021-06-13 20:20:35'),
(2, 1, 'app', 'android', '123456', 'a127a2c52b9e9c2519eae1a274627c1a', '2021-06-13 20:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `rc_users_tokens`
--

CREATE TABLE `rc_users_tokens` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `token` text COLLATE utf8_unicode_ci,
  `token_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'email_verify',
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rc_users_tokens`
--

INSERT INTO `rc_users_tokens` (`id`, `customer_id`, `token`, `token_type`, `ip_address`, `status`, `created_at`) VALUES
(1, 1, '077f5ca2d106429a8c3cac3b4b8b21a7', 'email_verify', '::1', 0, '2021-06-13 18:41:41'),
(2, 2, '17281484', 'email_verify', '::1', 0, '2021-06-13 19:04:46'),
(3, 3, '9899964', 'email_verify', '::1', 0, '2021-06-13 19:40:11'),
(4, 4, '2090430', 'email_verify', '::1', 0, '2021-06-13 19:40:25'),
(5, 4, '65970888', 'email_verify', '::1', 0, '2021-06-13 19:43:21'),
(6, 5, '62386920', 'email_verify', '::1', 0, '2021-06-13 19:43:47'),
(7, 6, '17433356', 'email_verify', '::1', 0, '2021-06-13 21:30:11'),
(8, 7, '57247382', 'email_verify', '::1', 0, '2021-06-13 21:35:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rc_data_log`
--
ALTER TABLE `rc_data_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rc_users`
--
ALTER TABLE `rc_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rc_users_devices`
--
ALTER TABLE `rc_users_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rc_users_tokens`
--
ALTER TABLE `rc_users_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rc_data_log`
--
ALTER TABLE `rc_data_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `rc_users`
--
ALTER TABLE `rc_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `rc_users_devices`
--
ALTER TABLE `rc_users_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rc_users_tokens`
--
ALTER TABLE `rc_users_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
