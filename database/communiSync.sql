-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 03, 2023 at 11:46 AM
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
-- Database: `communiSync`
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
(1, 'AI-1', 'seller', 'PJ-1', '2023-08-26 11:56:26pm', 'UI-1'),
(2, 'AI-2', 'seller', 'PJ-1', '2023-08-26 11:59:14pm', 'UI-1'),
(4, 'AI-4', 'investor', 'PJ-1', '2023-08-27 09:38:13pm', 'UI-1'),
(5, 'AI-5', 'investor', 'PJ-1', '2023-09-01 10:46:15am', 'UI-1'),
(7, 'AI-6', 'seller', 'PJ-1', '2023-09-02 08:08:33pm', 'UI-1'),
(8, 'AI-3', 'investor', 'PJ-1', NULL, NULL);

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
(1, '26-08-2023', 'Colony has been registered by &quot;Abdul Rehman&quot;.', 'super-admin', 'PJ-1', '2023-08-26 09:21:33am', 'super-admin'),
(2, '26-08-2023', 'User &quot;Awais&quot; has been Created.', 'UI-1', 'PJ-1', '2023-08-26 01:09:44pm', 'UI-1'),
(3, '26-08-2023', 'User &quot;Awais&quot; has been Modified.', 'UI-1', 'PJ-1', '2023-08-26 07:31:43pm', 'UI-1'),
(6, '26-08-2023', 'Seller Account &quot;Aleem Anwar&quot; has been Created.', 'UI-1', 'PJ-1', '2023-08-26 11:53:25pm', 'UI-1'),
(10, '27-08-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-27 12:49:01am', 'UI-1'),
(11, '27-08-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-27 01:03:46am', 'UI-1'),
(12, '27-08-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-08-27 01:08:12am', 'UI-1'),
(13, '27-08-2023', 'Investor Account &quot;Khalid Fareed&quot; has been Created.', 'UI-1', 'PJ-1', '2023-08-27 08:53:43pm', 'UI-1'),
(14, '27-08-2023', 'Investor &quot;Khalid Fareed&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-27 09:34:16pm', 'UI-1'),
(15, '27-08-2023', 'Investor Account &quot;Faryad Bhatti&quot; has been Created.', 'UI-1', 'PJ-1', '2023-08-27 09:38:13pm', 'UI-1'),
(16, '27-08-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-27 09:45:34pm', 'UI-1'),
(17, '28-08-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-08-28 01:48:39am', 'UI-1'),
(18, '28-08-2023', 'Abdul Rehman modified His Profile.', 'UI-1', 'PJ-1', '2023-08-28 09:36:27pm', 'UI-1'),
(19, '28-08-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-28 10:19:40pm', 'UI-1'),
(20, '28-08-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-08-28 10:20:20pm', 'UI-1'),
(21, '28-08-2023', 'Investor &quot;Khalid Fareed&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-28 10:21:45pm', 'UI-1'),
(22, '29-08-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-29 01:11:37am', 'UI-1'),
(23, '29-08-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-29 01:12:07am', 'UI-1'),
(24, '29-08-2023', 'Seller &quot;Muhammad Ali&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-08-29 01:15:37am', 'UI-1'),
(25, '29-08-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-29 01:18:01am', 'UI-1'),
(26, '29-08-2023', 'Investor &quot;Faryad Bhatti&quot; has been Deleted from Project.', 'UI-1', 'PJ-1', '2023-08-29 01:18:52am', 'UI-1'),
(27, '29-08-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-29 07:07:41pm', 'UI-1'),
(28, '30-08-2023', 'Seller &quot;Muhammad Ali&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-08-30 10:28:53pm', 'UI-1'),
(29, '30-08-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-30 10:47:15pm', 'UI-1'),
(30, '30-08-2023', 'SELLER Account &quot;Aleem Anwar&quot; has been Updated.', 'UI-1', 'PJ-1', '2023-08-30 11:27:50pm', 'UI-1'),
(31, '30-08-2023', 'Investor &quot;Khalid Fareed&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-30 11:35:05pm', 'UI-1'),
(32, '30-08-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-08-30 11:37:05pm', 'UI-1'),
(33, '30-08-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-08-30 11:41:21pm', 'UI-1'),
(34, '31-08-2023', 'INVESTOR Account &quot;Ali Abdullah&quot; has been Updated.', 'UI-1', 'PJ-1', '2023-08-31 12:55:27pm', 'UI-1'),
(35, '31-08-2023', 'INVESTOR Account &quot;Ali Abdullah&quot; has been Updated.', 'UI-1', 'PJ-1', '2023-08-31 02:07:28pm', 'UI-1'),
(36, '31-08-2023', 'INVESTOR Account &quot;Ali Abdullah&quot; has been Updated.', 'UI-1', 'PJ-1', '2023-08-31 02:08:06pm', 'UI-1'),
(37, '31-08-2023', 'INVESTOR Account &quot;Ali Abdullah&quot; has been Updated.', 'UI-1', 'PJ-1', '2023-08-31 02:08:20pm', 'UI-1'),
(38, '01-09-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-01 10:44:29am', 'UI-1'),
(39, '01-09-2023', 'Investor Account &quot;Abdul Rehman&quot; has been Created.', 'UI-1', 'PJ-1', '2023-09-01 10:46:15am', 'UI-1'),
(40, '01-09-2023', 'Investor &quot;Abdul Rehman&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-01 10:46:32am', 'UI-1'),
(41, '01-09-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-09-01 10:51:33am', 'UI-1'),
(42, '01-09-2023', 'Investor &quot;Ali Abdullah&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-01 10:52:55am', 'UI-1'),
(43, '01-09-2023', 'Account &quot;&quot; with ID &quot;&quot; has been Deleted.', 'UI-1', 'PJ-1', '2023-09-01 12:06:39pm', 'UI-1'),
(44, '02-09-2023', 'INVESTOR Account &quot;Ali Abdullah&quot; with ID &quot;AI-3&quot; has been Deleted.', 'UI-1', 'PJ-1', '2023-09-02 02:10:59pm', 'UI-1'),
(45, '02-09-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-09-02 06:00:44pm', 'UI-1'),
(46, '02-09-2023', 'Investor &quot;Faryad Bhatti&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 07:58:06pm', 'UI-1'),
(47, '02-09-2023', 'Investor &quot;Abdul Rehman&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 07:58:09pm', 'UI-1'),
(48, '02-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 07:58:13pm', 'UI-1'),
(49, '02-09-2023', 'Seller &quot;Muhammad Ali&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 07:58:16pm', 'UI-1'),
(50, '02-09-2023', 'Seller Account &quot;Amena&quot; has been Created.', 'UI-1', 'PJ-1', '2023-09-02 08:08:33pm', 'UI-1'),
(51, '02-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 08:12:58pm', 'UI-1'),
(52, '02-09-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 08:13:44pm', 'UI-1'),
(53, '02-09-2023', 'Investor &quot;Ali Abdullah&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 08:15:05pm', 'UI-1'),
(54, '02-09-2023', 'Project Details Updated.', 'UI-1', 'PJ-1', '2023-09-02 08:17:21pm', 'UI-1'),
(55, '02-09-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 08:18:10pm', 'UI-1'),
(56, '02-09-2023', 'Investor &quot;Faryad Bhatti&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 08:33:30pm', 'UI-1'),
(57, '02-09-2023', 'Investor &quot;Ali Abdullah&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 08:33:33pm', 'UI-1'),
(58, '02-09-2023', 'Seller &quot;Muhammad Ali&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 08:33:37pm', 'UI-1'),
(59, '02-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-02 08:33:39pm', 'UI-1'),
(60, '02-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 10:30:43pm', 'UI-1'),
(61, '02-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 11:00:13pm', 'UI-1'),
(62, '02-09-2023', 'Investor &quot;Faryad Bhatti&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-02 11:48:36pm', 'UI-1'),
(63, '03-09-2023', 'Investor &quot;Faryad Bhatti&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-03 12:04:03am', 'UI-1'),
(64, '03-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-03 01:06:07am', 'UI-1'),
(65, '03-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-03 01:08:01am', 'UI-1'),
(66, '03-09-2023', 'Seller &quot;Aleem Anwar&quot; has been Removed from Project.', 'UI-1', 'PJ-1', '2023-09-03 01:11:52am', 'UI-1'),
(67, '03-09-2023', 'Seller &quot;Muhammad Ali&quot; has been Added in Project.', 'UI-1', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1');

-- --------------------------------------------------------

--
-- Table structure for table `area_investor`
--

CREATE TABLE `area_investor` (
  `id` int(200) NOT NULL,
  `v-id` varchar(200) DEFAULT NULL,
  `kanal` varchar(200) DEFAULT NULL,
  `marla` varchar(200) DEFAULT NULL,
  `feet` varchar(200) DEFAULT NULL,
  `ratio` varchar(200) DEFAULT NULL,
  `acc_id` varchar(200) DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` text DEFAULT NULL,
  `created_by` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_seller`
--

CREATE TABLE `area_seller` (
  `id` int(200) NOT NULL,
  `v-id` varchar(200) DEFAULT NULL,
  `kanal` varchar(200) DEFAULT NULL,
  `marla` varchar(200) DEFAULT NULL,
  `feet` varchar(200) DEFAULT NULL,
  `amount` varchar(200) DEFAULT NULL,
  `period` varchar(200) DEFAULT NULL,
  `paid` varchar(200) DEFAULT '0',
  `acc_id` varchar(200) DEFAULT NULL,
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_seller`
--

INSERT INTO `area_seller` (`id`, `v-id`, `kanal`, `marla`, `feet`, `amount`, `period`, `paid`, `acc_id`, `project_id`, `created_date`, `created_by`) VALUES
(9, NULL, '13', '15', '127', '42000000', '36', '0', 'AI-2', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1');

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
(1, 'AI-2', 'Analog-UNI-64edfbad26e242.15081875.jpg', 'PJ-1', '2023-08-29 07:07:41pm', 'UI-1'),
(2, 'AI-1', 'Rehman_Logo-UNI-64ef80a354f6b9.88188955.png', 'PJ-1', '2023-08-30 10:47:15pm', 'UI-1'),
(3, 'AI-1', 'Front-Side-UNI-64ef80a3574029.12780399.jpg', 'PJ-1', '2023-08-30 10:47:15pm', 'UI-1'),
(4, 'AI-3', 'WhatsApp Image 2023-08-22 at 10.33.14 PM-UNI-64ef8bd955f296.73282318.jpeg', 'PJ-1', '2023-08-30 11:35:05pm', 'UI-1'),
(5, 'AI-3', 'RTD-logo-Wallpaper-UNI-64ef8bd9572ce6.51330909.png', 'PJ-1', '2023-08-30 11:35:05pm', 'UI-1'),
(6, 'AI-2', 'logo-fav-white-UNI-64f17a3dec3a45.36042587.png', 'PJ-1', '2023-09-01 10:44:29am', 'UI-1'),
(7, 'AI-2', 'ScreenShot-123-UNI-64f39b45e5bb83.58052033.png', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1'),
(8, 'AI-2', 'ScreenShot-123-UNI-64f39b45ea84f1.59602809.png', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1'),
(9, 'AI-2', 'ScreenShot-123-UNI-64f39b45eb4c47.69738285.png', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1'),
(10, 'AI-2', 'ScreenShot-123-UNI-64f39b45ec2aa4.45480021.png', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1'),
(15, 'AI-2', 'Screenshot from 2023-08-20 08-25-06-UNI-64f3a663160433.54751699.png', 'PJ-1', '2023-09-03 02:17:23am', 'UI-1'),
(18, 'AI-2', 'Screenshot_2023-09-03_01_23_15-UNI-64f3a6631a5511.82923774.png', 'PJ-1', '2023-09-03 02:17:23am', 'UI-1');

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
(1, 'AI-3', 'Ali Abdullah', 'Mr.', 'Muhammad Abdullah', '3640242523112', 'Ex ab minus deserunt Ex ab minus deserunt', 'Islamabad', 'khyber pakhtunkhwa', 'pakistan', '3331244121', 'aliabdullahkhan@outlook.com', '3331244121', 0, 'AI-3-64f0476fc704f1.27573829.jpg', 'PJ-1', '2023-08-27 08:53:43pm', 'UI-1'),
(2, 'AI-4', 'Faryad Bhatti', 'Mr.', 'Muhammad', '3640223511242', 'Fugiat molestiae qu Fugiat molestiae qu', 'Pakpattan', 'punjab', 'pakistan', '3332551511', 'faryadbhatti@gmail.com', '3332315112', 0, 'AI-4-64eb7bf5cf35b0.97746937.jpg', 'PJ-1', '2023-08-27 09:38:13pm', 'UI-1'),
(3, 'AI-5', 'Abdul Rehman', 'Mr.', 'Zahid Fareed', '3640232523111', 'Blanditiis iure laud Blanditiis iure laud', 'Pakpattan', 'punjab', 'pakistan', '3266619213', 'abdulrehmanzahid721@gmail.com', '3266619213', 120000, 'AI-5-64f17aa71e71d8.88963563.jpg', 'PJ-1', '2023-09-01 10:46:15am', 'UI-1');

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

CREATE TABLE `ledger` (
  `id` int(200) NOT NULL,
  `v-id` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `source` varchar(200) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `credit` varchar(200) DEFAULT '0',
  `debit` varchar(200) DEFAULT '0',
  `project_id` varchar(200) DEFAULT NULL,
  `created_date` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ledger`
--

INSERT INTO `ledger` (`id`, `v-id`, `type`, `source`, `remarks`, `credit`, `debit`, `project_id`, `created_date`, `created_by`) VALUES
(3, '72608735', 'ProjectSeller', 'AI-1', '68,619 Sqft. are purchased from &quotAleem Anwar&quot', '23400000', '0', 'PJ-1', '2023-09-03 01:08:01am', 'UI-1'),
(4, '52301915', 'ProjectSeller', 'AI-1', '68,619 Sqft. are returned to &quotAleem Anwar&quot', '0', '23400000', 'PJ-1', '2023-09-03 01:11:52am', 'UI-1'),
(5, '85596540', 'ProjectSeller', 'AI-2', '74,996 Sqft. are purchased from &quotMuhammad Ali&quot', '42000000', '0', 'PJ-1', '2023-09-03 01:29:57am', 'UI-1');

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
(1, 'PJ-1', 'Bahria Town', 'joint-venture', 'Center of Lahore', 'Lahore', 'pakistan', '3003214141', '3003214141', '', '0', '0', '0', '0', '', '', '', '', '', '2023-08-26 09:21:33am', 'super-admin');

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
(1, 'AI-1', 'Aleem Anwar', 'Mr.', 'Muhammad Anwar', '3640262342421', 'Deserunt anim iusto Deserunt anim iusto', 'Pakpattan', 'punjab', 'pakistan', '3244563623', 'aleemanwar321@outlook.com', '3244636425', 23490000, 'profile.png', 'PJ-1', '2023-08-26 11:56:26pm', 'UI-1'),
(2, 'AI-2', 'Muhammad Ali', 'Mr.', 'Nawaz Wattoo', '3640263623215', 'Do laudantium quo v Do laudantium quo v', 'Pakpattan', 'punjab', 'pakistan', '3252524622', 'alinawazwattoo@gmail.com', '3032346241', 0, 'AI-2-64ea4b82488828.71405171.jpg', 'PJ-1', '2023-08-26 11:59:14pm', 'UI-1'),
(3, 'AI-6', 'Amena', 'Mr.', 'Navarro', '3640235626426', 'Sint voluptas aute s Sint voluptas aute', 'Sargodha', 'khyber pakhtunkhwa', 'pakistan', '3262452323', 'amena123@gmail.com', '3242646222', 2300, 'AI-6-64f34ff1d45742.02965428.jpg', 'PJ-1', '2023-09-02 08:08:33pm', 'UI-1');

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
(1, 'UI-1', 'OVK9D-PXXNO-B58GP-V6C4A', 'Mr.', 'Abdul Rehman', '', 'rehmandeveloper@icloud.com', 'pakistan', '3256661234', 1, 'super-admin', 'rehman', '1234', '04/04/2005', 'male', 'engaged', 'A+', 'profile.png', 'PJ-1', '2023-08-26 09:21:33am', 'super-admin'),
(2, 'UI-2', 'OVK9D-PXXNO-B58GP-V6C4A', 'Mr.', 'Awais', 'Raza', 'awaisraza@icloud.com', 'pakistan', '3245633322', 1, 'admin', 'awais', '1122', '04/08/0189', 'male', NULL, 'AB+', 'profile.png', 'PJ-1', '2023-08-26 01:09:44pm', 'UI-1');

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
-- Indexes for table `area_seller`
--
ALTER TABLE `area_seller`
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
-- Indexes for table `ledger`
--
ALTER TABLE `ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
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
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `area_investor`
--
ALTER TABLE `area_investor`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `area_seller`
--
ALTER TABLE `area_seller`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `investor`
--
ALTER TABLE `investor`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ledger`
--
ALTER TABLE `ledger`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
