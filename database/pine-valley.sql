-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 20, 2023 at 07:34 AM
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
-- Database: `pine-valley`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(200) NOT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` text DEFAULT NULL,
  `created_by` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `acc_id`, `type`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'AI-1', 'seller', 'PJ-2', '2023-08-17 07:07:24am', 'UI-2'),
(2, 'AI-2', 'investor', 'PJ-2', '2023-08-17 07:26:46am', 'UI-2'),
(5, 'AI-3', 'seller', 'PJ-2', NULL, NULL),
(6, 'AI-3', 'seller', NULL, '2023-08-18 05:54:03am', 'UI-3'),
(7, 'AI-4', 'investor', 'PJ-2', '2023-08-19 04:26:39am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(200) NOT NULL,
  `date` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `UI` varchar(200) DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `date`, `message`, `UI`, `project_id`, `created_date`, `created_by`) VALUES
(1, '15-08-2023', 'Colony has been registered by &quot;Ali Abdullah&quot;.', 'super-admin', 'PJ-1', '2023-08-15 11:04:28am', 'super-admin'),
(2, '15-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 11:09:43am', 'UI-1'),
(3, '15-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 11:09:50am', 'UI-1'),
(4, '15-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 11:11:45am', 'UI-1'),
(5, '15-08-2023', 'Ali Abdullah modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 08:02:07pm', 'UI-1'),
(6, '15-08-2023', 'Ali Abdullah modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 08:03:11pm', 'UI-1'),
(7, '15-08-2023', 'Ali Abdullah modified His Profile.', 'UI-1', 'PJ-1', '2023-08-15 08:04:25pm', 'UI-1'),
(8, '15-08-2023', 'Role &quot;Admin&quot; has been Created.', 'UI-1', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(9, '16-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:12:09am', 'UI-1'),
(10, '16-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:12:11am', 'UI-1'),
(11, '16-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:15:39am', 'UI-1'),
(12, '16-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:16:09am', 'UI-1'),
(13, '16-08-2023', 'Ali Abdullah  modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:16:46am', 'UI-1'),
(14, '16-08-2023', 'Ali Abdullah Khan modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:22:12am', 'UI-1'),
(15, '16-08-2023', 'Ali Abdullah Khan modified His Profile.', 'UI-1', 'PJ-1', '2023-08-16 12:23:39am', 'UI-1'),
(16, '16-08-2023', 'Colony has been registered by &quot;Abdul Rehman&quot;.', 'super-admin', 'PJ-2', '2023-08-16 12:55:12am', 'super-admin'),
(17, '16-08-2023', 'Abdul Rehman  modified His Profile.', 'UI-2', 'PJ-2', '2023-08-16 01:04:17am', 'UI-2'),
(18, '16-08-2023', 'Abdul Rehman modified His Profile.', 'UI-2', 'PJ-2', '2023-08-16 01:05:43am', 'UI-2'),
(19, '16-08-2023', 'Role &quot;Admin&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-16 03:57:43am', 'UI-2'),
(20, '16-08-2023', 'User &quot;Muhammad&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-16 03:58:43am', 'UI-2'),
(22, '16-08-2023', 'User &quot;Muhammad&quot; Status has been changed to Inactive.', 'UI-2', 'PJ-2', '2023-08-16 04:04:01am', 'UI-2'),
(23, '16-08-2023', 'User &quot;Muhammad&quot; Status has been changed to Active.', 'UI-2', 'PJ-2', '2023-08-16 04:04:06am', 'UI-2'),
(24, '16-08-2023', 'User &quot;Muhammad&quot; Status has been changed to Inactive.', 'UI-2', 'PJ-2', '2023-08-16 04:04:08am', 'UI-2'),
(25, '16-08-2023', 'User &quot;Muhammad&quot; Status has been changed to Active.', 'UI-2', 'PJ-2', '2023-08-16 04:07:35am', 'UI-2'),
(26, '16-08-2023', 'Project Details Updated.', 'UI-2', 'PJ-2', '2023-08-16 01:54:51pm', 'UI-2'),
(27, '17-08-2023', 'Seller Account &quot;Rao Aleem&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-17 05:57:09am', 'UI-2'),
(28, '17-08-2023', 'Seller Account &quot;Rao Aleem&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-17 07:07:24am', 'UI-2'),
(29, '17-08-2023', 'Investor Account &quot;Ali Abdullah&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-17 07:26:46am', 'UI-2'),
(30, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:47:09am', 'UI-2'),
(31, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:48:07am', 'UI-2'),
(32, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:50:01am', 'UI-2'),
(33, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:55:37am', 'UI-2'),
(34, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:55:50am', 'UI-2'),
(35, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 02:56:07am', 'UI-2'),
(36, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 03:04:06am', 'UI-2'),
(37, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 03:04:50am', 'UI-2'),
(38, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 03:11:07am', 'UI-2'),
(39, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 04:48:50am', 'UI-2'),
(40, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 05:06:25am', 'UI-2'),
(42, '18-08-2023', 'Seller Account &quot;Awais Raza&quot; has been Created.', 'UI-3', 'PJ-2', '2023-08-18 05:54:03am', 'UI-3'),
(43, '18-08-2023', 'Some Changings has been made in Role &quot;Admin&quot;.', 'UI-2', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(44, '19-08-2023', 'Investor Account &quot;Muhammad Ali&quot; has been Created.', 'UI-2', 'PJ-2', '2023-08-19 04:26:39am', 'UI-2'),
(45, '19-08-2023', 'Project Details Updated.', 'UI-2', 'PJ-2', '2023-08-19 07:23:49am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `area_investor`
--

CREATE TABLE `area_investor` (
  `id` int(200) NOT NULL,
  `kanal` varchar(200) DEFAULT NULL,
  `marla` varchar(200) DEFAULT NULL,
  `feet` varchar(200) DEFAULT NULL,
  `ratio` varchar(200) DEFAULT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` text DEFAULT NULL,
  `created_by` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_investor`
--

INSERT INTO `area_investor` (`id`, `kanal`, `marla`, `feet`, `ratio`, `acc_id`, `project_id`, `created_date`, `created_by`) VALUES
(1, '20', '18', '21', '20', 'AI-4', 'PJ-2', '2023-08-20 09:58:31am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(200) NOT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `acc_id`, `name`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'AI-4', 'Muhammad-Ali-CNIC-Front.pdf', 'PJ-2', '2023-08-20 09:55:55am', 'UI-2'),
(2, 'AI-4', 'Muhammad-Ali-CNIC-Back.pdf', 'PJ-2', '2023-08-20 09:55:55am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `investor`
--

CREATE TABLE `investor` (
  `id` int(200) NOT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `prefix` text DEFAULT NULL,
  `father_name` text DEFAULT NULL,
  `cnic` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `province` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `phone_no` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `whts_no` text DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `img` text DEFAULT 'profile.png',
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investor`
--

INSERT INTO `investor` (`id`, `acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `balance`, `img`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'AI-2', 'Ali Abdullah', 'Mr.', 'Abdullah', '36402-6243242-1', 'Qui consectetur dol Qui consectetur dol', 'Lahore', 'Punjab', 'Pakistan', '+9225236232', 'aliabdullah@outlook.com', '+9225236232', 3000, NULL, 'PJ-2', '2023-08-17 07:26:46am', 'UI-2'),
(2, 'AI-4', 'Muhammad Ali', 'Mr.', 'Nawaz Wattoo', '3640234623461', 'Assumenda impedit p Assumenda impedit p', 'Pakpattan', 'punjab', 'pakistan', '3543623212', 'alinawazwattoo@gmail.com', '3252555151', 230, 'AI-4-64dffe2fa42099.78624341.jpg', 'PJ-2', '2023-08-19 04:26:39am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(200) NOT NULL,
  `pro_id` varchar(200) DEFAULT NULL,
  `name` text NOT NULL,
  `category` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `country` text NOT NULL,
  `phone_no` text NOT NULL DEFAULT '000',
  `whatsapp_no` text NOT NULL DEFAULT '000',
  `helpline_no` text NOT NULL DEFAULT '000',
  `commercial_sqft` varchar(200) NOT NULL DEFAULT '0',
  `residential_sqft` varchar(200) NOT NULL DEFAULT '0',
  `wastage_sqft` varchar(200) NOT NULL DEFAULT '0',
  `sqft_per_marla` varchar(200) DEFAULT NULL,
  `website` text NOT NULL,
  `fb_link` text DEFAULT NULL,
  `yt_link` text DEFAULT NULL,
  `inst_link` text DEFAULT NULL,
  `tw_link` text DEFAULT NULL,
  `created_date` text NOT NULL,
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `pro_id`, `name`, `category`, `address`, `city`, `country`, `phone_no`, `whatsapp_no`, `helpline_no`, `commercial_sqft`, `residential_sqft`, `wastage_sqft`, `sqft_per_marla`, `website`, `fb_link`, `yt_link`, `inst_link`, `tw_link`, `created_date`, `created_by`) VALUES
(1, 'PJ-1', 'Pine Valley', 'purchased', 'Corner of Pakpattan', 'Pakpattan', 'pakistan', '1231325131', '', '', '0', '0', '0', NULL, '', NULL, NULL, NULL, NULL, '2023-08-15 11:04:28am', 'super-admin'),
(2, 'PJ-2', 'Bahria Town', 'joint-venture', 'Center of Lahore', 'Lahore', 'pakistan', '312436534', '312436534', '3124323443', '0', '0', '0', NULL, 'bahriatown.com', 'https://facebook.com/bahriatown', 'https://youtube.com/bahriatown', 'https://instagram.com/bahriatown', 'https://twitter.com/bahriatown', '2023-08-16 12:55:12am', 'super-admin');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(200) NOT NULL,
  `name` text DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'super-admin', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(2, 'Admin', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(3, 'super-admin', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(4, 'Admin', 'PJ-2', '2023-08-16 03:57:43am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(200) NOT NULL,
  `role` varchar(200) DEFAULT NULL,
  `permission` text DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role`, `permission`, `project_id`, `created_date`, `created_by`) VALUES
(1, '1', 'view-user-management', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(2, '1', 'add-user', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(3, '1', 'view-user', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(4, '1', 'edit-user', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(5, '1', 'delete-user', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(6, '1', 'add-user-role', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(7, '1', 'view-user-role', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(8, '1', 'edit-user-role', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(9, '1', 'delete-user-role', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(10, '1', 'view-project', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(11, '1', 'edit-project', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(12, '1', 'add-account', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(13, '1', 'view-account', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(14, '1', 'edit-account', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(15, '1', 'delete-account', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(16, '1', 'add-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(17, '1', 'edit-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(18, '1', 'view-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(19, '1', 'delete-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(20, '1', 'dashboard', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(21, '1', 'print', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(22, '1', 'view-ledger', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(23, '1', 'view-activity', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(24, '1', 'add-sale-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(25, '1', 'view-sale-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(26, '1', 'edit-sale-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(27, '1', 'delete-sale-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(28, '1', 'add-transfer-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(29, '1', 'view-transfer-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(30, '1', 'add-return-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(31, '1', 'view-return-property', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(32, '1', 'add-payment-pay', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(33, '1', 'view-payment-pay', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(34, '1', 'delete-payment-pay', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(35, '1', 'add-payment-receive', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(36, '1', 'view-payment-receive', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(37, '1', 'delete-payment-receive', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(38, '1', 'add-payment-transfer', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(39, '1', 'view-payment-transfer', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(40, '1', 'delete-payment-transfer', 'PJ-1', '2023-08-15 11:04:28am', 'UI-1'),
(41, '2', 'add-user', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(42, '2', 'view-user', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(43, '2', 'edit-user', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(44, '2', 'delete-user', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(45, '2', 'add-user-role', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(46, '2', 'view-user-role', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(47, '2', 'edit-user-role', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(48, '2', 'delete-user-role', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(49, '2', 'add-account', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(50, '2', 'view-account', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(51, '2', 'edit-account', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(52, '2', 'delete-account', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(53, '2', 'add-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(54, '2', 'edit-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(55, '2', 'view-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(56, '2', 'delete-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(57, '2', 'print', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(58, '2', 'view-ledger', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(59, '2', 'view-activity', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(60, '2', 'add-sale-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(61, '2', 'view-sale-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(62, '2', 'edit-sale-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(63, '2', 'delete-sale-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(64, '2', 'add-transfer-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(65, '2', 'view-transfer-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(66, '2', 'add-return-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(67, '2', 'view-return-property', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(68, '2', 'add-payment-pay', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(69, '2', 'view-payment-pay', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(70, '2', 'delete-payment-pay', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(71, '2', 'add-payment-receive', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(72, '2', 'view-payment-receive', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(73, '2', 'delete-payment-receive', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(74, '2', 'add-payment-transfer', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(75, '2', 'view-payment-transfer', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(76, '2', 'delete-payment-transfer', 'PJ-1', '2023-08-15 08:05:03pm', 'UI-1'),
(77, '3', 'view-user-management', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(78, '3', 'add-user', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(79, '3', 'view-user', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(80, '3', 'edit-user', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(81, '3', 'delete-user', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(82, '3', 'add-user-role', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(83, '3', 'view-user-role', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(84, '3', 'edit-user-role', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(85, '3', 'delete-user-role', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(86, '3', 'view-project', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(87, '3', 'edit-project', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(88, '3', 'add-account', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(89, '3', 'view-account', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(90, '3', 'edit-account', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(91, '3', 'delete-account', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(92, '3', 'add-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(93, '3', 'edit-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(94, '3', 'view-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(95, '3', 'delete-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(96, '3', 'dashboard', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(97, '3', 'print', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(98, '3', 'view-ledger', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(99, '3', 'view-activity', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(100, '3', 'add-sale-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(101, '3', 'view-sale-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(102, '3', 'edit-sale-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(103, '3', 'delete-sale-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(104, '3', 'add-transfer-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(105, '3', 'view-transfer-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(106, '3', 'add-return-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(107, '3', 'view-return-property', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(108, '3', 'add-payment-pay', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(109, '3', 'view-payment-pay', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(110, '3', 'delete-payment-pay', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(111, '3', 'add-payment-receive', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(112, '3', 'view-payment-receive', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(113, '3', 'delete-payment-receive', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(114, '3', 'add-payment-transfer', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(115, '3', 'view-payment-transfer', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(116, '3', 'delete-payment-transfer', 'PJ-2', '2023-08-16 12:55:12am', 'UI-2'),
(533, '4', 'add-user', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(534, '4', 'view-user', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(535, '4', 'edit-user', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(536, '4', 'delete-user', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(537, '4', 'add-user-role', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(538, '4', 'view-user-role', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(539, '4', 'edit-user-role', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(540, '4', 'delete-user-role', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(541, '4', 'add-account', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(542, '4', 'view-account', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(543, '4', 'edit-account', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(544, '4', 'delete-account', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(545, '4', 'add-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(546, '4', 'edit-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(547, '4', 'view-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(548, '4', 'delete-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(549, '4', 'print', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(550, '4', 'view-ledger', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(551, '4', 'view-activity', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(552, '4', 'add-sale-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(553, '4', 'view-sale-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(554, '4', 'edit-sale-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(555, '4', 'delete-sale-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(556, '4', 'add-transfer-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(557, '4', 'view-transfer-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(558, '4', 'add-return-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(559, '4', 'view-return-property', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(560, '4', 'add-payment-pay', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(561, '4', 'view-payment-pay', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(562, '4', 'delete-payment-pay', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(563, '4', 'add-payment-receive', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(564, '4', 'view-payment-receive', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(565, '4', 'delete-payment-receive', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(566, '4', 'add-payment-transfer', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(567, '4', 'view-payment-transfer', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2'),
(568, '4', 'delete-payment-transfer', 'PJ-2', '2023-08-18 09:27:43am', 'UI-2');

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `id` int(200) NOT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `prefix` text DEFAULT NULL,
  `father_name` text DEFAULT NULL,
  `cnic` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `province` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `phone_no` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `whts_no` text DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `img` text DEFAULT 'profile.png',
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `acc_id`, `name`, `prefix`, `father_name`, `cnic`, `address`, `city`, `province`, `country`, `phone_no`, `email`, `whts_no`, `balance`, `img`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'AI-1', 'Rao Aleem', 'Mr.', 'Muhammad Anwar', '36402-6234242-1', 'Qui consectetur dol Qui consectetur dol', 'Pakpattan', 'Punjab', 'Pakistan', '+9225236232', 'raoaleem@gmail.com', '', 3000, NULL, 'PJ-2', '2023-08-17 07:07:24am', 'UI-2'),
(3, 'AI-3', 'Awais Raza', 'Mr.', 'Muhammad Raza', '36402-6243753-1', 'Qui consectetur dol Qui consectetur dol', 'Unknown', 'Balochistan', 'Pakistan', '+92574574353', 'Awaisraza843@gmail.com', '', 0, 'AI-3-64dec12bf1dc76.27407883.jpg', 'PJ-2', '2023-08-18 05:54:03am', 'UI-3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(200) NOT NULL,
  `u_id` varchar(200) DEFAULT NULL,
  `license` text NOT NULL,
  `prefix` text DEFAULT NULL,
  `f_name` varchar(200) NOT NULL DEFAULT '',
  `s_name` varchar(200) NOT NULL DEFAULT '',
  `email` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `phone_no` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `role` varchar(30) DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `date_of_birth` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `martial_status` text DEFAULT NULL,
  `blood_group` text DEFAULT NULL,
  `img` text DEFAULT 'profile.png',
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` text DEFAULT '',
  `created_by` text DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `u_id`, `license`, `prefix`, `f_name`, `s_name`, `email`, `country`, `phone_no`, `status`, `role`, `username`, `password`, `date_of_birth`, `gender`, `martial_status`, `blood_group`, `img`, `project_id`, `created_date`, `created_by`) VALUES
(1, 'UI-1', 'DUMEI-FDTI1-15ES8-F9P7E', 'Mr.', 'Ali Abdullah', 'Khan', 'aliabdullah@gmail.com', 'pakistan', '+9232413511', 1, 'super-admin', 'abdullah', '1234', '03/02/1999', 'male', 'married', 'A+', 'UI-1-64dbd0bb1dfb76.15749109.jpg', 'PJ-1', '2023-08-15 11:04:28am', 'super-admin'),
(2, 'UI-2', '9G58V-Q3RO5-6WWYZ-INC7Q', 'Mr.', 'Abdul Rehman', 'Baloch', 'abdulrehman721@gmail.com', 'pakistan', '+92321325234', 1, 'super-admin', 'rehman', '3814', '04/28/2003', 'male', 'engaged', 'A+', 'UI-2-64dbda418df484.11914036.jpg', 'PJ-2', '2023-08-16 12:55:12am', 'super-admin'),
(3, 'UI-3', '9G58V-Q3RO5-6WWYZ-INC7Q', 'Mr.', 'Muhammad', 'Awais', 'muhammadawais@gmail.com', 'pakistan', '+92325231231', 1, '4', 'awais', '1122', '1998-12-23', 'male', 'single', 'A+', 'profile.png', 'PJ-2', '2023-08-16 03:59:34am', 'UI-2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_investor`
--
ALTER TABLE `area_investor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investor`
--
ALTER TABLE `investor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
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
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `area_investor`
--
ALTER TABLE `area_investor`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `investor`
--
ALTER TABLE `investor`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=569;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
