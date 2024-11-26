-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2024 at 09:13 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mounac53_centraldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `aadhar_transaction_all`
--

CREATE TABLE `aadhar_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL DEFAULT '0.00',
  `price` float(10,2) NOT NULL,
  `aadhar_otp_transaction_id` longtext NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(10) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(10) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aadhar_transaction_all`
--

INSERT INTO `aadhar_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `aadhar_otp_transaction_id`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`, `web_link_number`) VALUES
(1, 'AGN-00002', '', 'REQ-00163', 'APP-00001', 'MEM-00001', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-01 21:32:28', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(2, 'AGN-00002', '', 'REQ-00164', 'APP-00001', 'MEM-00001', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-01 21:33:28', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(3, 'AGN-00002', '', 'REQ-00165', 'APP-00001', 'MEM-00001', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-01 21:36:45', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(4, 'AGN-00002', '', 'REQ-00166', 'APP-00001', 'MEM-00001', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-01 21:43:53', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(5, 'AGN-00001', '', 'REQ-00167', 'APP-00001', 'MEM-00017', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-02 14:11:11', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(6, 'AGN-00002', '', 'REQ-00169', 'APP-00001', 'MEM-00015', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-03 10:42:52', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(7, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-03 11:30:34', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(8, 'AGN-00002', '', 'REQ-00174', 'APP-00001', 'MEM-00044', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00002/MEM-00044/aadhar20240806174633.pdf', '2024-07-05 16:57:00', '2024-08-06 17:46:33', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(9, 'AGN-00005', '', 'REQ-00178', 'APP-00001', 'MEM-00038', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-06 11:07:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(10, 'AGN-00005', '', 'REQ-00181', 'APP-00001', 'MEM-00043', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-09 12:17:13', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(11, 'AGN-00005', '', 'REQ-00183', 'APP-00001', 'MEM-00084', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-16 11:32:13', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(12, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00016/MEM-00085/aadhar20240717121214.pdf', '2024-07-16 16:49:19', '2024-07-17 12:12:03', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(13, 'AGN-00005', '', 'REQ-00186', 'APP-00001', 'MEM-00088', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-19 16:49:38', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(14, 'AGN-00005', '', 'REQ-00191', 'APP-00001', 'MEM-00092', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-21 00:08:42', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(15, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(16, 'AGN-00005', '', 'REQ-00195', 'APP-00001', 'MEM-00100', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00005/MEM-00100/aadhar20240722131814.pdf', '2024-07-22 13:08:06', '2024-07-22 13:18:14', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(17, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(18, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-24 11:23:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(19, 'AGN-00002', '', 'REQ-00204', 'APP-00001', 'MEM-00103', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00002/MEM-00103/aadhar20240726115128.pdf', '2024-07-24 11:36:44', '2024-07-26 11:51:27', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(20, 'AGN-00075', '', 'REQ-00207', 'APP-00001', 'MEM-00105', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-07-30 15:07:53', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(21, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-08-02 22:13:48', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(22, 'AGN-00002', '', 'REQ-00210', 'APP-00001', 'MEM-00103', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-08-05 18:07:35', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(23, 'AGN-00076', '', 'REQ-00211', 'APP-00001', 'MEM-00112', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-08-05 18:09:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(24, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00005/MEM-00113/aadhar20240808144345.pdf', '2024-08-06 12:37:03', '2024-08-08 14:43:45', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(25, 'AGN-00076', '', 'REQ-00220', 'APP-00001', 'MEM-00116', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00076/MEM-00116/aadhar20240806132036.pdf', '2024-08-06 13:17:27', '2024-08-06 13:20:35', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(26, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00005/MEM-00130/aadhar20240808174446.pdf', '2024-08-08 17:34:02', '2024-08-08 17:44:45', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(27, 'AGN-00062', '', 'REQ-00231', 'APP-00001', 'MEM-00136', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-08-23 11:41:08', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(28, 'AGN-00076', '', 'REQ-00232', 'APP-00001', 'MEM-00137', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00076/MEM-00137/aadhar20240829171752.pdf', '2024-08-29 17:13:50', '2024-08-29 17:17:52', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(29, 'AGN-00062', '', 'REQ-00236', 'APP-00001', 'MEM-00141', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-09-05 12:04:09', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(30, 'AGN-00076', '', 'REQ-00240', 'APP-00001', 'MEM-00138', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-09-30 15:18:55', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(31, 'AGN-00096', '', 'REQ-00242', 'APP-00001', 'MEM-00143', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00096/MEM-00143/aadhar20241003153813.pdf', '2024-10-03 15:29:36', '2024-10-03 15:38:13', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(32, 'AGN-00096', '', 'REQ-00243', 'APP-00001', 'MEM-00143', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-10-03 15:45:14', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(33, 'AGN-00096', '', 'REQ-00244', 'APP-00001', 'MEM-00143', '', 'DVF-00001', '', 20.00, '', '0', '', '2024-10-03 15:45:31', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(34, 'AGN-00005', '', 'REQ-00248', 'APP-00001', 'MEM-00146', '', 'DVF-00001', '', 20.00, '', '2', 'https://mounarchtech.com/central_wp/verification_data/aadhar/voco_xp/AGN-00005/MEM-00146/aadhar20241107225323.pdf', '2024-11-07 17:44:51', '2024-11-07 22:53:23', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '');

-- --------------------------------------------------------

--
-- Table structure for table `application_category_product_combination_all`
--

CREATE TABLE `application_category_product_combination_all` (
  `id` int(10) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `date_on_linking` datetime NOT NULL,
  `date_of_unlinking` datetime NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=Active/0=Inactive',
  `base_amount` int(100) NOT NULL COMMENT 'ex. 100',
  `sgst_percentage` int(10) NOT NULL,
  `cgst_percentage` int(10) NOT NULL,
  `discount_percentage` int(100) NOT NULL COMMENT '20'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `application_header_all`
--

CREATE TABLE `application_header_all` (
  `id` int(10) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `application_name` varchar(30) NOT NULL,
  `application_storage` varchar(25) NOT NULL COMMENT 'value@gb/tb',
  `status` int(11) NOT NULL COMMENT '1=Active, 0=Inactive',
  `direct_verification_permittable` int(11) NOT NULL COMMENT '1=Yes/0=No',
  `system_settings` longtext NOT NULL,
  `server_url` varchar(50) NOT NULL COMMENT 'example -> https://premisafe.com/vocoxp',
  `server_db_credentials` varchar(200) NOT NULL COMMENT 'csv (ip_address,username,password,database_name)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `application_header_all`
--

INSERT INTO `application_header_all` (`id`, `application_id`, `application_name`, `application_storage`, `status`, `direct_verification_permittable`, `system_settings`, `server_url`, `server_db_credentials`) VALUES
(1, 'APP-00001', 'VOCOxP', '100@GB', 1, 0, '', '', 'smartwrist_vocodevelop'),
(2, 'APP-00002', 'VOCOxS', '100@GB', 0, 0, '', '', ''),
(3, 'APP-00003', 'VOCOxE', '100@GB', 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `application_help_videos`
--

CREATE TABLE `application_help_videos` (
  `id` int(11) NOT NULL,
  `application_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_description_label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `video_link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `video_uploaded_on` datetime NOT NULL,
  `description_pdf` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if there is no video',
  `status` int(11) NOT NULL COMMENT '1= Active, 2=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_storage_plan_all`
--

CREATE TABLE `application_storage_plan_all` (
  `id` int(10) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `plan_id` varchar(25) NOT NULL,
  `plan_name` varchar(50) NOT NULL,
  `plan_start_date` date NOT NULL,
  `plan_end_date` date NOT NULL,
  `status` int(11) NOT NULL,
  `storage_size` int(11) NOT NULL,
  `no_of_lisence` int(11) NOT NULL,
  `amount` int(50) NOT NULL,
  `tax_amount` int(50) NOT NULL,
  `tax_in_percentage` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `application_storage_plan_all`
--

INSERT INTO `application_storage_plan_all` (`id`, `application_id`, `plan_id`, `plan_name`, `plan_start_date`, `plan_end_date`, `status`, `storage_size`, `no_of_lisence`, `amount`, `tax_amount`, `tax_in_percentage`) VALUES
(1, 'APP-00001', 'PLAN-0001', 'sdf', '2024-03-02', '2024-03-26', 1, 1, 0, 200, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `category_header_all`
--

CREATE TABLE `category_header_all` (
  `id` int(11) NOT NULL,
  `category_id` varchar(10) NOT NULL COMMENT '06 digit no start with “C”',
  `category_code` varchar(50) NOT NULL COMMENT 'generated by marketing department',
  `category_name` varchar(50) NOT NULL,
  `category_description` longtext NOT NULL,
  `category_type` varchar(100) NOT NULL COMMENT 'Eg: smart_wrist',
  `category_icon` longtext NOT NULL,
  `applied_from` datetime NOT NULL,
  `created_on` date NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1-active,2=Suspended'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_header_all`
--

INSERT INTO `category_header_all` (`id`, `category_id`, `category_code`, `category_name`, `category_description`, `category_type`, `category_icon`, `applied_from`, `created_on`, `created_by`, `status`) VALUES
(1, 'CAT-00001', 'C531379', 'Fire-Boltt Phoenix Smart Watch', 'Fire-Boltt Phoenix Smart Watch with Bluetooth Calling 1.3\",120+ Sports Modes, 240 * 240 PX High Res with SpO2, Heart Rate Monitoring & IP67 Rating (Gold Pink)', 'Smart Wrist', 'https://mounarchtech.com/central_wp/category/CAT-00001/1716363053_091a3595e64365ae2998.jpg', '2024-05-26 00:00:00', '2024-05-22', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `courier_charges_details`
--

CREATE TABLE `courier_charges_details` (
  `id` int(11) NOT NULL,
  `start_km` int(11) NOT NULL COMMENT 'in kilo meters ',
  `end_km` int(11) NOT NULL COMMENT 'in kilo meters',
  `chargable_amount` float NOT NULL,
  `gst_amount` float NOT NULL,
  `old_calculations` varchar(50) NOT NULL COMMENT 'from_date-to_date>data',
  `applied_from` date NOT NULL COMMENT 'date from whch this rates and calculations are active',
  `appllication_id` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `driving_license_transaction_all`
--

CREATE TABLE `driving_license_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `price` float(10,2) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL COMMENT '1-Yes/0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(10) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(10) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `driving_license_transaction_all`
--

INSERT INTO `driving_license_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`, `web_link_number`) VALUES
(1, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-03 11:30:34', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(2, 'AGN-00005', '', 'REQ-00173', 'APP-00001', 'MEM-00045', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-05 14:57:21', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(3, 'AGN-00005', '', 'REQ-00178', 'APP-00001', 'MEM-00038', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-06 11:07:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(4, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-16 16:49:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(5, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(6, 'AGN-00005', '', 'REQ-00196', 'APP-00001', 'MEM-00100', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00005/MEM-00100/dl20240722132558.pdf', '2024-07-22 13:08:26', '2024-07-22 13:25:58', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(7, 'AGN-00051', '', 'REQ-00200', 'APP-00001', 'MEM-00102', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00051/MEM-00102/dl20240723121352.pdf', '2024-07-23 12:11:21', '2024-07-23 12:13:51', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(8, 'AGN-00051', '', 'REQ-00201', 'APP-00001', 'MEM-00102', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-23 12:43:38', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(9, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(10, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00004', '', 20.00, '0', '', '2024-07-24 11:23:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(11, 'AGN-00002', '', 'REQ-00205', 'APP-00001', 'MEM-00103', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00002/MEM-00103/dl20240724115207.pdf', '2024-07-24 11:50:07', '2024-07-24 11:52:06', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(12, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00004', '', 20.00, '0', '', '2024-08-02 22:13:48', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(13, 'AGN-00076', '', 'REQ-00211', 'APP-00001', 'MEM-00112', '', 'DVF-00004', '', 20.00, '0', '', '2024-08-05 18:09:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(14, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00004', '', 20.00, '0', '', '2024-08-06 12:37:03', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(15, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00005/MEM-00130/dl20240808174229.pdf', '2024-08-08 17:34:02', '2024-08-08 17:42:29', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(16, 'AGN-00049', '', 'REQ-00227', 'APP-00001', 'MEM-00128', '', 'DVF-00004', '', 20.00, '0', '', '2024-08-10 13:32:14', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(17, 'AGN-00005', '', 'REQ-00234', 'APP-00001', 'MEM-00139', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00005/MEM-00139/dl20240903113944.pdf', '2024-09-03 11:38:16', '2024-09-03 11:39:43', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(18, 'AGN-00076', '', 'REQ-00235', 'APP-00001', 'MEM-00138', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00076/MEM-00138/dl20240904221737.pdf', '2024-09-04 22:14:07', '2024-09-04 22:17:36', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(19, 'AGN-00005', '', 'REQ-00251', 'APP-00001', 'MEM-00146', '', 'DVF-00004', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/dl/voco_xp/AGN-00005/MEM-00146/dl20241108000403.pdf', '2024-11-08 00:00:17', '2024-11-08 00:04:03', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '');

-- --------------------------------------------------------

--
-- Table structure for table `ecrime_transaction_all`
--

CREATE TABLE `ecrime_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `price` float(10,2) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL COMMENT '1=Yes/0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(11) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(11) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `ecrime_request_id` varchar(50) NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ecrime_transaction_all`
--

INSERT INTO `ecrime_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`, `ecrime_request_id`, `web_link_number`) VALUES
(1, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-03 11:30:34', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(2, 'AGN-00005', '', 'REQ-00173', 'APP-00001', 'MEM-00045', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-05 14:57:21', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(3, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-16 16:49:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(4, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(5, 'AGN-00005', '', 'REQ-00196', 'APP-00001', 'MEM-00100', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-22 13:08:26', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(6, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(7, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00003', '', 20.00, '0', '', '2024-07-24 11:23:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(8, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00003', '', 20.00, '0', '', '2024-08-02 22:13:48', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(9, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00003', '', 20.00, '0', '', '2024-08-06 12:37:03', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(10, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00003', '', 20.00, '0', '', '2024-08-08 17:34:02', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(11, 'AGN-00049', '', 'REQ-00228', 'APP-00001', 'MEM-00128', '', 'DVF-00003', '', 20.00, '0', '', '2024-08-10 13:34:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', ''),
(12, 'AGN-00005', '', 'REQ-00250', 'APP-00001', 'MEM-00146', '', 'DVF-00003', '', 20.00, '0', '', '2024-11-07 17:58:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `faulty_items_details_all`
--

CREATE TABLE `faulty_items_details_all` (
  `id` int(10) NOT NULL,
  `item_no` varchar(30) NOT NULL,
  `faulty_declared_by` varchar(30) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `declared_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_diagnostic_report_header_all`
--

CREATE TABLE `item_diagnostic_report_header_all` (
  `id` int(11) NOT NULL,
  `diagnosis_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'from product_item_header_all',
  `mic` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `audio` int(11) NOT NULL COMMENT 'device speaker\r\n1=Working/0=Not working',
  `heart_rate` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `blood_pressure` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `body_temperature` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `sp_o2` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `bluetooth` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `wifi` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `sim` int(11) NOT NULL COMMENT '0=not_working/1=working',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_diagnostic_report_header_all`
--

INSERT INTO `item_diagnostic_report_header_all` (`id`, `diagnosis_id`, `item_id`, `mic`, `audio`, `heart_rate`, `blood_pressure`, `body_temperature`, `sp_o2`, `bluetooth`, `wifi`, `sim`, `created_on`, `modified_on`) VALUES
(13, 'DGN-00009', 'ITM-00042', 0, 0, 0, 0, 0, 0, 0, 1, 0, '2024-05-17 15:52:57', '2024-05-17 15:54:07'),
(14, 'DGN-00010', 'ITM-00045', 1, 1, 1, 1, 1, 1, 0, 1, 1, '2024-05-17 16:09:50', '2024-05-17 17:10:29'),
(15, 'DGN-00001', '2155722002', 1, 1, 1, 1, 1, 0, 0, 1, 0, '2024-08-22 21:46:19', '2024-08-23 01:15:37'),
(16, 'DGN-00002', '2561734004', 1, 1, 1, 1, 1, 0, 0, 1, 1, '2024-08-23 01:19:10', '2024-08-23 01:28:44'),
(17, 'DGN-00003', 'ITM-00054', 0, 0, 0, 0, 0, 0, 0, 1, 0, '2024-09-10 12:15:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `item_master_all`
--

CREATE TABLE `item_master_all` (
  `id` int(11) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `oem_code` longtext NOT NULL,
  `oem_item_no` varchar(500) NOT NULL,
  `item_no` varchar(50) NOT NULL,
  `item_no_generated_on` datetime NOT NULL,
  `watch_code` varchar(10) NOT NULL COMMENT '4-digit pin to be entered in watch - to configure',
  `watch_code_generated_on` datetime NOT NULL,
  `ref_code` varchar(10) NOT NULL,
  `reference_code_generated_on` datetime NOT NULL,
  `employee_id` varchar(100) NOT NULL COMMENT 'emlpoyee id to know who is configuring the item',
  `time_taken` varchar(100) NOT NULL COMMENT 'time taken by employee to complete the item configuration from step 1 to last step he has done.',
  `current_step` int(11) NOT NULL,
  `current_status` int(11) NOT NULL COMMENT '1:in stock, 2:On Sales, 3:Sold but not dispatched, 4:Faulty (under QA), 5:Dispatched, 6:Under Dispatched Process, 7:At End Client, 8:In Return Process, 9:Return Received, 10:Inspection at QA, 11:Permanent Faulty',
  `last_status_updated_on` datetime NOT NULL,
  `last_transaction_history` varchar(100) NOT NULL COMMENT 'in CSV :"app_id,agency_id,order_id" (if item status sold or at client)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_master_all`
--

INSERT INTO `item_master_all` (`id`, `item_id`, `oem_code`, `oem_item_no`, `item_no`, `item_no_generated_on`, `watch_code`, `watch_code_generated_on`, `ref_code`, `reference_code_generated_on`, `employee_id`, `time_taken`, `current_step`, `current_status`, `last_status_updated_on`, `last_transaction_history`) VALUES
(1, 'ITM-00001', 'OEM1234256', '2562236001', '2562236001', '2024-05-22 08:22:36', '1198', '2024-05-22 08:23:29', '2236', '2024-05-22 08:22:36', 'supe_123', '', 4, 1, '0000-00-00 00:00:00', ''),
(5, 'ITM-00003', 'OEM1234256', '1234', '2565919003', '2024-08-22 21:25:26', '9113', '2024-08-22 21:28:25', '2526', '2024-08-22 21:25:26', 'EMP-00001', '', 4, 1, '0000-00-00 00:00:00', ''),
(6, 'ITM-00002', 'OEM1234256', '1234', '2155722002', '2024-08-22 21:44:37', '6286', '2024-08-22 21:45:23', '4437', '2024-08-22 21:44:37', 'EMP-00001', '', 4, 1, '0000-00-00 00:00:00', ''),
(7, 'ITM-00004', 'OEM1234256', '12345', '2561734004', '2024-08-23 01:17:34', '8981', '2024-08-23 01:18:50', '1734', '2024-08-23 01:17:34', 'EMP-00001', '', 4, 1, '0000-00-00 00:00:00', ''),
(8, 'ITM-00005', 'OEM124215', '445566', '2152242005', '2024-08-23 03:22:42', '', '0000-00-00 00:00:00', '2242', '2024-08-23 03:22:42', 'EMP-00001', '', 3, 0, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `item_qa_transaction_all`
--

CREATE TABLE `item_qa_transaction_all` (
  `id` int(11) NOT NULL,
  `item_no` varchar(11) NOT NULL,
  `return_on` int(11) NOT NULL,
  `mark_faulty` text NOT NULL,
  `done_by` varchar(11) NOT NULL COMMENT 'employee_id',
  `images_clicked_by_customer` longtext NOT NULL COMMENT 'image url in csv',
  `reason_of_arrival` text NOT NULL COMMENT 'comment by stock / retrun manager "why item is under QA"',
  `qa_status` int(11) NOT NULL COMMENT '1:review pending, 2:under review, 3:reviewed',
  `final_item_status` int(11) NOT NULL COMMENT '1:fit, 2:permanent faulty',
  `repairs` int(11) NOT NULL COMMENT '1=Y /0= N',
  `item_moves_in` varchar(20) NOT NULL COMMENT 'category_id=product_id / permanent faulty',
  `item_moved_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `oem_header_all`
--

CREATE TABLE `oem_header_all` (
  `id` int(10) NOT NULL,
  `oem_code` varchar(50) NOT NULL,
  `oem_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oem_header_all`
--

INSERT INTO `oem_header_all` (`id`, `oem_code`, `oem_name`) VALUES
(1, 'OEM1234256', 'Fitbit'),
(2, 'OEM123451', 'Garmin'),
(3, 'OEM112123\n', 'Huawei'),
(4, 'OEM112124', 'Samsung'),
(5, 'OEM112123', 'Xiaomi'),
(6, 'OEM124215', 'Smart Watch'),
(7, 'OEM1454252', 'Amazfit'),
(8, 'OEM1242214', 'Apple'),
(9, 'OEM142252', 'Fossil'),
(10, 'OEM00001', 'NoOEM');

-- --------------------------------------------------------

--
-- Table structure for table `order_header_all`
--

CREATE TABLE `order_header_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `agency_id` varchar(50) NOT NULL COMMENT 'who placed order',
  `branch_id` varchar(100) NOT NULL,
  `order_status` int(11) NOT NULL COMMENT '1-order placed, 2-order confirmed, 3-requested to sm, 4-ready to collect, 5-collected, 6-ready to dispatch, 7-dispatched, 8-delivered, 9-pre-cancelled, 10-rejected, 11-post-cancelled',
  `order_placed_on` datetime NOT NULL,
  `order_item_nos` longtext NOT NULL COMMENT 'in CSV format',
  `item_quantity` int(11) NOT NULL COMMENT 'total count',
  `order_amount` int(11) NOT NULL,
  `tax_amount` int(11) NOT NULL,
  `mode_of_payment` varchar(50) NOT NULL COMMENT 'wallet /option name which show by razor pay(for online)',
  `payment_transaction_id` varchar(100) NOT NULL COMMENT 'transection id in string',
  `payment_gateway_response` varchar(150) NOT NULL COMMENT 'additional response given by payment gateway (when status = pending / failed) eg- "you have entered wrong pin"',
  `payment_status` int(10) NOT NULL COMMENT '1:pending, 2:success, 3:failed',
  `order_invoice` longtext NOT NULL COMMENT 'invoice file name/modified invoice file name in CSV',
  `order_recieve_pin` int(4) NOT NULL COMMENT '4-digit pin for order confirm at customer end',
  `courier_details` varchar(100) NOT NULL COMMENT 'courier_partner_name@id_given_by_courier_partner=expected_delivery_time!image_url(optional)',
  `shipping_address` text NOT NULL,
  `shipment_no` varchar(100) NOT NULL,
  `cancellation_reason` longtext NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_header_all`
--

INSERT INTO `order_header_all` (`id`, `application_id`, `order_id`, `agency_id`, `branch_id`, `order_status`, `order_placed_on`, `order_item_nos`, `item_quantity`, `order_amount`, `tax_amount`, `mode_of_payment`, `payment_transaction_id`, `payment_gateway_response`, `payment_status`, `order_invoice`, `order_recieve_pin`, `courier_details`, `shipping_address`, `shipment_no`, `cancellation_reason`, `web_link_number`) VALUES
(402, '132', 'O6415681999', 'ROH_38b', '', 7, '2024-02-14 12:00:54', 'I3E2E5733BBE', 1, 0, 0, '', '', '', 0, 'https://premisafe.com/production/vocoxp_2.0/verification/S8652511708/65c0f24a0769d.pdf', 1111, '', '', '', '', ''),
(405, '132', 'O4805644446', 'ROH_38b', '', 8, '2024-02-14 12:06:11', 'I8DB7D436D8F', 1, 0, 0, '', '', '', 0, '', 1111, '', '', '', '', ''),
(407, '132', 'O8593376027', 'ROH_38b', '', 11, '2024-02-14 12:11:11', 'I0A46466E586,IEE64C675D89', 1, 8000, 0, 'wallet', '', '', 0, 'https://premisafe.com/production/vocoxp_2.0/verification/S8652511708/65c0f24a0769d.pdf', 1111, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', 'svygjh', ''),
(412, '132', 'O8179331531', 'ROH_38b', '', 11, '2024-02-15 15:35:45', 'I025547FD1CD', 1, 1461, 0, 'wallet', '', '', 0, '', 0, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', 'folt', ''),
(413, '132', 'O7589617754', 'ROH_38b', '', 11, '2024-02-15 15:42:27', 'IAA030D1BDA9,I0F9EBEFCCA4,IB633358D683', 4, 0, 0, '', '', '', 0, '', 0, '', '', '', 'not work', ''),
(414, '132', 'O1438795165', 'ROH_38b', '', 7, '2024-02-15 15:43:00', 'I161F114151E', 4, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(415, '132', 'O7135440275', 'ROH_38b', '', 11, '2024-02-15 15:43:03', 'I6E435FF1F29', 4, 18412, 0, 'wallet', '', '', 0, '', 0, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', 'faulty', ''),
(416, '132', 'O7156656990', 'ROH_38b', '', 11, '2024-02-16 11:48:25', 'IAF5ACBB7F58', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'roga', ''),
(418, '132', 'O8923191035', 'ROH_38b', '', 11, '2024-02-16 11:50:07', 'I4D96F958BEA', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'jssjjsj', ''),
(419, '132', 'O4526348675', 'ROH_38b', '', 11, '2024-02-16 11:50:16', '', 0, 720, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', 'fffgf', ''),
(420, '132', 'O1416838532', 'ROH_38b', '', 11, '2024-02-16 11:54:07', 'I3E2E5733BBE', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'rohan', ''),
(422, '132', 'O1967669192', 'ROH_38b', '', 11, '2024-02-16 11:55:43', 'I8DB7D436D8F', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'rohan', ''),
(424, '132', 'O3234759489', 'ROH_38b', '', 8, '2024-02-16 12:44:20', 'IF93886F064E,I286928EBF0C,IEDDFA7881D5,IA82BBCF3774,I99A9EB1603A,IB09736DF620', 5, 0, 0, '', '', '', 0, '', 1111, '', '', '', '', ''),
(425, '132', 'O2881127331', 'ROH_38b', '', 11, '2024-02-16 15:38:03', 'IA41FF34D418,IEC0DA2889D3,I09FB1486441,I52E31EF91B6', 9, 0, 0, '', '', '', 0, '', 0, '', '', '', 'fhyffyfy', ''),
(426, '132', 'O6761372131', 'ROH_38b', '', 11, '2024-02-16 15:39:51', '', 6, 0, 0, '', '', '', 0, '', 0, '', '', '', 'hdydchhc', ''),
(429, '132', 'O6279875494', 'PET_28b', '', 11, '2024-02-19 15:38:35', 'I03DF2C0DF20', 1, 855, 0, 'wallet', '', '', 0, '', 0, '', 'Deccan Temple Pune Maharashtra 413114~Sahil Chavan', '', 'folt', ''),
(430, '132', 'O8961907259', 'PET_28b', '', 11, '2024-02-19 15:50:06', 'IB998F4ADE7E', 1, 855, 0, 'wallet', '', '', 0, '', 0, '', 'Deccan Temple Pune Maharashtra 413114~Sahil Chavan', '', 'bzgshsgsg', ''),
(431, '132', 'O2431324378', 'PET_28b', '', 11, '2024-02-19 15:50:28', 'I40BB261BB46,I99A9EB1603V,I99A9EB1603A,I99A9EB1603X,I769A9EB1603,I99A9VAZ1603,I93VI9EB1603,AZX99A9EB160,I99A984HJ03A,I99A9EB1688V', 10, 7200, 0, 'wallet', '', '', 0, '', 0, '', 'Deccan Temple Pune Maharashtra 413114~Sahil Chavan', '', 'vsgshsgs', ''),
(432, '132', 'O3979413934', 'PET_28b', '', 11, '2024-02-19 15:50:46', 'I99ABC65903A', 1, 720, 0, 'wallet', '', '', 0, '', 0, '', 'Deccan Temple Pune Maharashtra 413114~Sahil Chavan', '', 'ewtgre', ''),
(433, '132', 'O1096005399', 'ROH_38b', '', 11, '2024-02-23 12:32:42', 'I0A46466E586', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'rohan', ''),
(434, '132', 'O4981269376', 'ROH_38b', '', 1, '2024-02-23 12:32:56', '', 0, 8000, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(435, '132', 'O8378950675', 'ROH_38b', '', 11, '2024-02-23 12:34:14', 'IEE64C675D89', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'ddff', ''),
(436, '132', 'O1287423469', 'ROH_38b', '', 1, '2024-02-23 12:34:43', '', 0, 8000, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(437, '132', 'O1396486485', 'ROH_38b', '', 11, '2024-02-23 12:36:49', 'I3E2E5733BBE', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'rohsn', ''),
(438, '132', 'O1026096265', 'ROH_38b', '', 1, '2024-02-23 12:38:25', 'I8DB7D436D8F', 1, 9000, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(439, '132', 'O5602138012', 'ROH_38b', '', 1, '2024-02-23 12:41:34', 'I3E2E5733BBE', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(440, '132', 'O2605727347', 'ROH_38b', '', 1, '2024-02-23 12:42:42', 'I3E2E5733BBE,I8DB7D436D8F', 2, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(441, '132', 'O3379836077', 'ROH_38b', '', 1, '2024-02-23 12:47:23', 'I025547FD1CD,I161F114151E,I6E435FF1F29', 3, 4386, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(442, '132', 'O5584000056', 'ROH_38b', '', 1, '2024-02-23 16:53:00', 'I0A46466E586', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(443, '132', 'O3535026535', 'ROH_38b', '', 11, '2024-02-23 16:53:12', '', 0, 8000, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', 'zyz', ''),
(444, '132', 'O7765760220', 'ROH_38b', '', 1, '2024-02-23 17:42:51', '', 3, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(445, '132', 'O7968825667', 'ROH_38b', '', 1, '2024-02-23 17:43:02', '', 6, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(446, '132', 'O7354487248', 'ROH_38b', '', 1, '2024-02-23 17:43:09', '', 9, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(447, '132', 'O9118315584', 'ROH_38b', '', 11, '2024-02-25 01:36:36', 'IB633358D683', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'svsgsgeheh', ''),
(448, '132', 'O5353749246', 'ROH_38b', '', 11, '2024-02-25 01:36:54', '', 0, 855, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', 'wvhchc', ''),
(449, '132', 'O8172142040', 'ROH_38b', '', 11, '2024-02-25 01:38:08', '', 6, 14466, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', 'rohan', ''),
(450, '132', 'O7252629347', 'ROH_38b', '', 1, '2024-02-25 01:39:08', 'IAF5ACBB7F58,I4D96F958BEA,I09FB1486441,I40BB261BB46,I99A9EB1603V,I99A9EB1603A,I99A9EB1603X,I769A9EB1603,I99A9VAZ1603,I93VI9EB1603,AZX99A9EB160,I99A984HJ03A,I99A9EB1688V,I99ABC65903A', 14, 10080, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(451, '132', 'O3085291550', 'ROH_38b', '', 1, '2024-03-01 10:30:48', 'IEE64C675D89', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(452, '132', 'O7819988987', 'ROH_38b', '', 1, '2024-03-01 10:38:54', 'IAA030D1BDA9', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(453, '132', 'O5689326321', 'ROH_38b', '', 1, '2024-03-01 11:06:58', 'IA41FF34D418', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(454, '132', 'O8588921818', 'ROH_38b', '', 1, '2024-03-01 11:12:16', 'I0F9EBEFCCA4', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(455, '132', 'O1992868993', 'ROH_38b', '', 1, '2024-03-01 11:20:48', 'IEC0DA2889D3', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(456, '132', 'O4256903025', 'ROH_38b', '', 1, '2024-03-01 11:35:24', 'I03DF2C0DF20', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(457, '132', 'O8025981474', 'ROH_38b', '', 1, '2024-03-01 11:36:35', 'IB998F4ADE7E,IB998F4ADE7E', 2, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(458, '132', 'O9195722483', 'ROH_38b', '', 1, '2024-03-01 12:02:55', 'IA43B8947927', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(459, '132', 'O3593618253', 'ROH_38b', '', 1, '2024-03-01 12:03:26', 'I3EE4070D991,I3EE4070D991', 2, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(460, '132', 'O3621552728', 'ROH_38b', '', 1, '2024-03-01 12:04:37', '', 4, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(461, '132', 'O9416824039', 'ROH_38b', '', 1, '2024-03-01 12:05:42', 'IF93886F064E', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(462, '132', 'O1458337544', 'ROH_38b', '', 1, '2024-03-01 12:08:06', 'I286928EBF0C', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(463, '132', 'O8016358338', 'ROH_38b', '', 1, '2024-03-01 12:10:45', 'IB09736DF620', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(464, '132', 'O6366943768', 'ROH_38b', '', 11, '2024-03-01 12:11:34', 'I52E31EF91B6', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'folt', ''),
(465, '132', 'O6919417521', 'ROH_38b', '', 11, '2024-03-01 12:12:05', 'IEDDFA7881D5', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'iusse ', ''),
(466, '132', 'O2848891068', 'ROH_38b', '', 1, '2024-03-01 12:17:29', 'IA82BBCF3774', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(467, '132', 'O9369464766', 'ROH_38b', '', 1, '2024-03-04 11:23:16', 'I0A46466E586', 1, 8000, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(468, '132', 'O2031362172', 'SUS_33b', '', 11, '2024-03-04 12:09:53', 'IB09736DF620', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'faulty ', ''),
(469, '132', 'O3622119962', 'SUS_33b', '', 11, '2024-03-04 12:11:06', '', 0, 0, 0, '', '', '', 0, '', 0, '', '', '', 'tftu', ''),
(470, '132', 'O9825667766', 'SUS_33b', '', 11, '2024-03-04 12:11:36', 'I0F9EBEFCCA4', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', 'fault you ', ''),
(471, '132', 'O4756755974', 'SUS_33b', '', 1, '2024-03-04 12:11:48', '', 0, 950, 0, 'wallet', '', '', 0, '', 0, '', 'Deccan  warje  Pune  Maharashtra  413132~Sahil', '', '', ''),
(472, '132', 'O1579489873', 'SUS_33b', '', 1, '2024-03-05 00:40:41', '', 0, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(473, '132', 'O2378677524', 'ROH_38b', '', 1, '2024-03-06 11:24:12', 'IB633358D683', 1, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(474, '132', 'O5632378117', 'SUS_33b', '', 1, '2024-03-06 16:57:54', 'IEE64C675D89,IEE64C675D89', 2, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(475, '132', 'O6251457115', 'SUS_33b', '', 1, '2024-03-06 16:58:07', 'IAA030D1BDA9,IAA030D1BDA9,IAA030D1BDA9', 3, 0, 0, '', '', '', 0, '', 0, '', '', '', '', ''),
(476, '132', 'O2563159474', 'ROH_38b', '', 1, '2024-03-06 23:41:07', 'IEC0DA2889D3,I03DF2C0DF20,IEC0DA2889D3,I03DF2C0DF20', 4, 15210, 0, 'wallet', '', '', 0, '', 0, '', 'Pune Pune pune maharashtra 413420~Rohan', '', '', ''),
(477, '132', 'O8629779515', 'ROH_38b', '', 11, '2024-03-15 14:44:15', 'IAF5ACBB7F58', 1, 720, 0, 'online', 'pay_NmZsa8nMhDoted', '', 0, '', 0, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', 'damage ', ''),
(478, '132', 'O1324009420', 'ROH_38b', '', 1, '2024-03-19 15:55:18', 'I3E2E5733BBE', 1, 4500, 0, 'online', '', '', 0, '', 0, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', '', ''),
(479, '132', 'O3548213723', 'ROH_38b', '', 1, '2024-03-19 15:55:44', 'I8DB7D436D8F', 1, 4500, 0, 'wallet', '', '', 0, '', 0, '', 'Karanji Karanji Angar Maharashtra 414106~Susmita Dharurkar', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_life_cycle_all`
--

CREATE TABLE `order_life_cycle_all` (
  `id` int(11) NOT NULL,
  `order_id` varchar(11) NOT NULL,
  `generated_on` datetime NOT NULL,
  `confirmed_on` datetime NOT NULL,
  `rejected_on` datetime NOT NULL COMMENT 'dateTime=reason',
  `requested_to_sm` datetime NOT NULL,
  `ready_to_collect` datetime NOT NULL,
  `collected_on` datetime NOT NULL,
  `ready_to_dispatch` datetime NOT NULL,
  `dispatched_on` datetime NOT NULL,
  `pre_dispatch_cancel` varchar(100) NOT NULL COMMENT 'date-time=reason',
  `returned_by_courier` varchar(100) NOT NULL COMMENT 'date-time=reason',
  `delivered_on` datetime NOT NULL,
  `returned_to_warehouse` varchar(100) NOT NULL COMMENT 'packet_id=dateTime',
  `order_current_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_life_cycle_all`
--

INSERT INTO `order_life_cycle_all` (`id`, `order_id`, `generated_on`, `confirmed_on`, `rejected_on`, `requested_to_sm`, `ready_to_collect`, `collected_on`, `ready_to_dispatch`, `dispatched_on`, `pre_dispatch_cancel`, `returned_by_courier`, `delivered_on`, `returned_to_warehouse`, `order_current_status`) VALUES
(14, 'O9326744505', '2024-02-05 12:53:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(15, 'O6098202226', '2024-02-05 12:55:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(16, 'O7951553476', '2024-02-05 13:00:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(17, 'O2303275510', '2024-02-07 15:44:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(18, 'O8473515576', '2024-02-07 15:53:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(19, 'O9316004473', '2024-02-07 15:54:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(20, 'O1508671056', '2024-02-09 11:22:33', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(21, 'O2512843266', '2024-02-09 11:22:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(22, 'O1062832206', '2024-02-09 11:22:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(23, 'O3801652736', '2024-02-09 11:23:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(24, 'O8928614923', '2024-02-09 11:23:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(25, 'O1246829599', '2024-02-13 16:25:04', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(26, 'O4894482170', '2024-02-13 16:26:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(27, 'O5962123338', '2024-02-13 16:26:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(28, 'O3209011968', '2024-02-13 16:27:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(29, 'O2255860579', '2024-02-13 16:30:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(30, 'O7431418251', '2024-02-14 10:59:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(31, 'O5685801846', '2024-02-14 10:59:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(32, 'O5810756527', '2024-02-14 11:02:44', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(33, 'O2808152095', '2024-02-14 11:02:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(34, 'O5401959297', '2024-02-14 11:05:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(35, 'O8743870975', '2024-02-14 11:05:18', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(36, 'O1300391644', '2024-02-14 11:07:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(37, 'O7958212272', '2024-02-14 11:10:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(38, 'O7544867770', '2024-02-14 11:10:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(39, 'O4566821815', '2024-02-14 11:11:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(40, 'O3956565694', '2024-02-14 11:13:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(41, 'O6890923448', '2024-02-14 11:13:15', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(42, 'O2117972540', '2024-02-14 11:14:13', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(43, 'O4517464863', '2024-02-14 11:14:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(44, 'O5645351730', '2024-02-14 11:14:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(45, 'O4931018725', '2024-02-14 11:14:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(46, 'O5749908061', '2024-02-14 11:15:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(47, 'O3676032383', '2024-02-14 11:16:29', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(48, 'O6857788708', '2024-02-14 11:17:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(49, 'O8342830069', '2024-02-14 11:18:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(50, 'O7617950546', '2024-02-14 11:18:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(51, 'O2851554727', '2024-02-14 11:18:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(52, 'O5029581737', '2024-02-14 11:19:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(53, 'O1283545978', '2024-02-14 11:30:38', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(54, 'O7634809138', '2024-02-14 11:32:55', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(55, 'O1740084889', '2024-02-14 11:33:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(56, 'O8815243628', '2024-02-14 11:34:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(57, 'O4829645010', '2024-02-14 11:39:21', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(58, 'O5628837734', '2024-02-14 11:39:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(59, 'O4404024400', '2024-02-14 11:43:04', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(60, 'O2846464868', '2024-02-14 11:43:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(61, 'O1102751793', '2024-02-14 11:47:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(62, 'O3192744925', '2024-02-14 11:47:49', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(63, 'O3316091853', '2024-02-14 11:48:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(64, 'O4864169361', '2024-02-14 11:51:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(65, 'O2914854611', '2024-02-14 11:55:17', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(66, 'O6415681999', '2024-02-14 12:00:54', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(67, 'O1305235805', '2024-02-14 12:01:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(68, 'O5737108188', '2024-02-14 12:01:24', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(69, 'O4805644446', '2024-02-14 12:06:11', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(70, 'O3499659242', '2024-02-14 12:06:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(71, 'O8593376027', '2024-02-14 12:11:11', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2024-02-21 11:08:28', '', '', '0000-00-00 00:00:00', '', 'dispatched'),
(72, 'O3187580398', '2024-02-14 12:17:52', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(73, 'O5325927445', '2024-02-14 12:18:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(74, 'O6083464046', '2024-02-14 17:55:02', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(75, 'O4409292197', '2024-02-14 17:55:12', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order_placed'),
(76, 'O8179331531', '2024-02-15 15:35:45', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(77, 'O7589617754', '2024-02-15 15:42:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(78, 'O1438795165', '2024-02-15 15:43:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(79, 'O7135440275', '2024-02-15 15:43:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(80, 'O7156656990', '2024-02-16 11:48:25', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(81, 'O5240376250', '2024-02-16 11:48:39', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(82, 'O8923191035', '2024-02-16 11:50:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(83, 'O4526348675', '2024-02-16 11:50:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(84, 'O1416838532', '2024-02-16 11:54:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(85, 'O5612651902', '2024-02-16 11:54:17', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(86, 'O1967669192', '2024-02-16 11:55:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(87, 'O5277250011', '2024-02-16 11:55:52', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(88, 'O3234759489', '2024-02-16 12:44:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(89, 'O2881127331', '2024-02-16 15:38:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(90, 'O6761372131', '2024-02-16 15:39:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(91, 'O9249494108', '2024-02-16 15:40:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(92, 'O9606420124', '2024-02-16 15:40:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(93, 'O6279875494', '2024-02-19 15:38:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(94, 'O8961907259', '2024-02-19 15:50:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(95, 'O2431324378', '2024-02-19 15:50:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(96, 'O3979413934', '2024-02-19 15:50:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(97, 'O1096005399', '2024-02-23 12:32:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(98, 'O4981269376', '2024-02-23 12:32:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(99, 'O8378950675', '2024-02-23 12:34:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(100, 'O1287423469', '2024-02-23 12:34:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(101, 'O1396486485', '2024-02-23 12:36:49', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(102, 'O1026096265', '2024-02-23 12:38:25', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(103, 'O5602138012', '2024-02-23 12:41:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(104, 'O2605727347', '2024-02-23 12:42:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(105, 'O3379836077', '2024-02-23 12:47:23', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(106, 'O5584000056', '2024-02-23 16:53:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(107, 'O3535026535', '2024-02-23 16:53:12', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(108, 'O7765760220', '2024-02-23 17:42:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(109, 'O7968825667', '2024-02-23 17:43:02', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(110, 'O7354487248', '2024-02-23 17:43:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(111, 'O9118315584', '2024-02-25 01:36:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(112, 'O5353749246', '2024-02-25 01:36:54', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(113, 'O8172142040', '2024-02-25 01:38:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(114, 'O7252629347', '2024-02-25 01:39:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(115, 'O3085291550', '2024-03-01 10:30:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(116, 'O7819988987', '2024-03-01 10:38:54', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(117, 'O5689326321', '2024-03-01 11:06:58', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(118, 'O8588921818', '2024-03-01 11:12:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(119, 'O1992868993', '2024-03-01 11:20:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(120, 'O4256903025', '2024-03-01 11:35:24', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(121, 'O8025981474', '2024-03-01 11:36:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(122, 'O9195722483', '2024-03-01 12:02:55', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(123, 'O3593618253', '2024-03-01 12:03:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(124, 'O3621552728', '2024-03-01 12:04:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(125, 'O9416824039', '2024-03-01 12:05:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(126, 'O1458337544', '2024-03-01 12:08:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(127, 'O8016358338', '2024-03-01 12:10:45', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(128, 'O6366943768', '2024-03-01 12:11:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(129, 'O6919417521', '2024-03-01 12:12:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(130, 'O2848891068', '2024-03-01 12:17:29', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(131, 'O9369464766', '2024-03-04 11:23:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(132, 'O2031362172', '2024-03-04 12:09:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(133, 'O3622119962', '2024-03-04 12:11:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(134, 'O9825667766', '2024-03-04 12:11:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(135, 'O4756755974', '2024-03-04 12:11:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(136, 'O1579489873', '2024-03-05 00:40:41', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(137, 'O2378677524', '2024-03-06 11:24:12', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(138, 'O5632378117', '2024-03-06 16:57:54', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(139, 'O6251457115', '2024-03-06 16:58:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(140, 'O2563159474', '2024-03-06 23:41:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(141, 'O8629779515', '2024-03-15 14:44:15', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(142, 'O1324009420', '2024-03-19 15:55:18', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed'),
(143, 'O3548213723', '2024-03-19 15:55:44', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'Order Placed');

-- --------------------------------------------------------

--
-- Table structure for table `order_post_dipatch_cancelled_all`
--

CREATE TABLE `order_post_dipatch_cancelled_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(11) NOT NULL,
  `order_id` varchar(11) NOT NULL,
  `packet_id` varchar(11) NOT NULL,
  `return_type` int(10) NOT NULL COMMENT '1=courier / 2=customer',
  `total_packet_amount` float NOT NULL,
  `packet_tax_amount` float NOT NULL,
  `return_initiated_on` datetime NOT NULL COMMENT 'by customer',
  `return_courier_details` varchar(100) NOT NULL COMMENT 'Courier description',
  `refund_requested_in` int(10) NOT NULL COMMENT '1=wallet / 2=bank',
  `actual_refund_done_in` int(10) NOT NULL COMMENT '1=wallet / 2=bank',
  `status_of_refund` int(10) NOT NULL COMMENT '0=pending /1=completed / rejected',
  `refund_amount` float NOT NULL,
  `refund_comment` text NOT NULL,
  `refund_transferred_on` datetime NOT NULL,
  `replacement_requested_on` datetime NOT NULL COMMENT 'by customer',
  `replacement_done` varchar(30) NOT NULL DEFAULT 'No' COMMENT 'Y/N if yes=new order_id',
  `packet_item_nos` longtext NOT NULL COMMENT 'item numbers in CSV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_pre_dipatch_cancelled_all`
--

CREATE TABLE `order_pre_dipatch_cancelled_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(11) NOT NULL,
  `order_id` varchar(11) NOT NULL,
  `cancelled_by` int(10) NOT NULL COMMENT '1=purchaser / 2=employee',
  `total_amount` float NOT NULL,
  `base_amount` float NOT NULL,
  `tax_amount` float NOT NULL,
  `refund_to` int(10) NOT NULL COMMENT '1=wallet / 2=bank',
  `status_of_refund` int(10) NOT NULL COMMENT '0=pending /1= completed',
  `refund_done_on` datetime NOT NULL,
  `refund_transection_id` int(11) NOT NULL,
  `refund_done_by` varchar(11) NOT NULL COMMENT 'employee_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pan_transaction_all`
--

CREATE TABLE `pan_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `price` float(10,2) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL COMMENT '1=yes/0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(11) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(11) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pan_transaction_all`
--

INSERT INTO `pan_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`) VALUES
(1, 'AGN-00002', '', 'REQ-00163', 'APP-00001', 'MEM-00001', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-01 21:32:28', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(2, 'AGN-00002', '', 'REQ-00164', 'APP-00001', 'MEM-00001', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-01 21:33:28', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(3, 'AGN-00002', '', 'REQ-00165', 'APP-00001', 'MEM-00001', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-01 21:36:45', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(4, 'AGN-00002', '', 'REQ-00166', 'APP-00001', 'MEM-00001', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-01 21:43:53', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(5, 'AGN-00001', '', 'REQ-00168', 'APP-00001', 'MEM-00017', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00001/MEM-00017/pan20240703065629.pdf', '2024-07-03 06:55:33', '2024-07-03 06:56:24', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(6, 'AGN-00002', '', 'REQ-00170', 'APP-00001', 'MEM-00015', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00002/MEM-00015/pan20240703105049.pdf', '2024-07-03 10:49:59', '2024-07-03 10:50:44', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(7, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00037/pan20240703113903.pdf', '2024-07-03 11:30:34', '2024-07-03 11:38:57', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(8, 'AGN-00005', '', 'REQ-00172', 'APP-00001', 'MEM-00043', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00043/pan20240706013119.pdf', '2024-07-05 14:42:01', '2024-07-06 01:31:13', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(9, 'AGN-00005', '', 'REQ-00173', 'APP-00001', 'MEM-00045', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00045/pan20240706014729.pdf', '2024-07-05 14:57:21', '2024-07-06 01:47:24', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(10, 'AGN-00005', '', 'REQ-00175', 'APP-00001', 'MEM-00043', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00043/pan20240706013727.pdf', '2024-07-06 01:35:59', '2024-07-06 01:37:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(11, 'AGN-00005', '', 'REQ-00176', 'APP-00001', 'MEM-00043', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00043/pan20240706014552.pdf', '2024-07-06 01:37:48', '2024-07-06 01:45:47', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(12, 'AGN-00005', '', 'REQ-00177', 'APP-00001', 'MEM-00045', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00045/pan20240706014909.pdf', '2024-07-06 01:48:10', '2024-07-06 01:49:03', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(13, 'AGN-00005', '', 'REQ-00178', 'APP-00001', 'MEM-00038', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-06 11:07:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(14, 'AGN-00001', '', 'REQ-00179', 'APP-00001', 'MEM-00017', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00001/MEM-00017/pan20240706161149.pdf', '2024-07-06 16:09:13', '2024-07-06 16:11:43', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(15, 'AGN-00005', '', 'REQ-00184', 'APP-00001', 'MEM-00084', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-16 11:47:31', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(16, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-16 16:49:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(17, 'AGN-00005', '', 'REQ-00187', 'APP-00001', 'MEM-00088', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00088/pan20240719165054.pdf', '2024-07-19 16:49:58', '2024-07-19 16:50:53', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(18, 'AGN-00002', '', 'REQ-00189', 'APP-00001', 'MEM-00057', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-20 11:00:16', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(19, 'AGN-00002', '', 'REQ-00190', 'APP-00001', 'MEM-00044', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00002/MEM-00044/pan20240806174237.pdf', '2024-07-20 11:00:44', '2024-08-06 17:42:37', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(20, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(21, 'AGN-00005', '', 'REQ-00193', 'APP-00001', 'MEM-00097', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00097/pan20240722122009.pdf', '2024-07-22 12:10:00', '2024-07-22 12:20:08', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(22, 'AGN-00005', '', 'REQ-00194', 'APP-00001', 'MEM-00098', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-22 12:55:33', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(23, 'AGN-00005', '', 'REQ-00196', 'APP-00001', 'MEM-00100', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00100/pan20240722132023.pdf', '2024-07-22 13:08:26', '2024-07-22 13:20:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(24, 'AGN-00051', '', 'REQ-00197', 'APP-00001', 'MEM-00102', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00051/MEM-00102/pan20240723115457.pdf', '2024-07-23 11:39:58', '2024-07-23 11:54:57', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(25, 'AGN-00051', '', 'REQ-00198', 'APP-00001', 'MEM-00102', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00051/MEM-00102/pan20240723120752.pdf', '2024-07-23 11:59:35', '2024-07-23 12:07:52', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(26, 'AGN-00051', '', 'REQ-00199', 'APP-00001', 'MEM-00102', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00051/MEM-00102/pan20240723120928.pdf', '2024-07-23 12:08:42', '2024-07-23 12:09:28', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(27, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00002', '', 20.00, '0', '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(28, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00104/pan20240805183151.pdf', '2024-07-24 11:23:54', '2024-08-05 18:31:49', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(29, 'AGN-00002', '', 'REQ-00204', 'APP-00001', 'MEM-00103', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00002/MEM-00103/pan20240724114643.pdf', '2024-07-24 11:36:44', '2024-07-24 11:46:43', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(30, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00062/MEM-00111/pan20240807133356.pdf', '2024-08-02 22:13:48', '2024-08-07 13:33:55', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(31, 'AGN-00002', '', 'REQ-00209', 'APP-00001', 'MEM-00103', '', 'DVF-00002', '', 20.00, '0', '', '2024-08-05 18:04:50', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(32, 'AGN-00076', '', 'REQ-00211', 'APP-00001', 'MEM-00112', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00112/pan20240806124844.pdf', '2024-08-05 18:09:47', '2024-08-06 12:48:44', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(33, 'AGN-00005', '', 'REQ-00212', 'APP-00001', 'MEM-00104', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00104/pan20240805183408.pdf', '2024-08-05 18:33:03', '2024-08-05 18:34:08', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(34, 'AGN-00005', '', 'REQ-00213', 'APP-00001', 'MEM-00104', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00104/pan20240805183723.pdf', '2024-08-05 18:35:36', '2024-08-05 18:37:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(35, 'AGN-00005', '', 'REQ-00214', 'APP-00001', 'MEM-00104', '', 'DVF-00002', '', 20.00, '0', '', '2024-08-05 18:37:57', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(36, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00113/pan20240806124023.pdf', '2024-08-06 12:37:03', '2024-08-06 12:40:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(37, 'AGN-00076', '', 'REQ-00217', 'APP-00001', 'MEM-00112', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00112/pan20240806125112.pdf', '2024-08-06 12:49:15', '2024-08-06 12:51:11', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(38, 'AGN-00076', '', 'REQ-00218', 'APP-00001', 'MEM-00115', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00115/pan20240806130505.pdf', '2024-08-06 13:03:57', '2024-08-06 13:05:05', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(39, 'AGN-00076', '', 'REQ-00219', 'APP-00001', 'MEM-00115', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00115/pan20240806130826.pdf', '2024-08-06 13:05:37', '2024-08-06 13:08:26', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(40, 'AGN-00076', '', 'REQ-00220', 'APP-00001', 'MEM-00116', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00116/pan20240806131832.pdf', '2024-08-06 13:17:27', '2024-08-06 13:18:31', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(41, 'AGN-00076', '', 'REQ-00221', 'APP-00001', 'MEM-00116', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00116/pan20240806132405.pdf', '2024-08-06 13:21:14', '2024-08-06 13:24:05', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(42, 'AGN-00002', '', 'REQ-00222', 'APP-00001', 'MEM-00044', '', 'DVF-00002', '', 20.00, '0', '', '2024-08-06 17:43:01', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(43, 'AGN-00076', '', 'REQ-00223', 'APP-00001', 'MEM-00112', '', 'DVF-00002', '', 20.00, '0', '', '2024-08-07 13:32:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(44, 'AGN-00062', '', 'REQ-00224', 'APP-00001', 'MEM-00119', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00062/MEM-00119/pan20240807174523.pdf', '2024-08-07 17:43:10', '2024-08-07 17:45:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(45, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00130/pan20240808173607.pdf', '2024-08-08 17:34:02', '2024-08-08 17:36:07', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(46, 'AGN-00076', '', 'REQ-00226', 'APP-00001', 'MEM-00132', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00132/pan20240813105253.pdf', '2024-08-09 16:38:03', '2024-08-13 10:52:53', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(47, 'AGN-00005', '', 'REQ-00229', 'APP-00001', 'MEM-00113', '', 'DVF-00002', '', 20.00, '0', '', '2024-08-12 12:50:29', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(48, 'AGN-00089', '', 'REQ-00230', 'APP-00001', 'MEM-00134', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00089/MEM-00134/pan20240822005842.pdf', '2024-08-22 00:57:35', '2024-08-22 00:58:42', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(49, 'AGN-00062', '', 'REQ-00237', 'APP-00001', 'MEM-00141', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00062/MEM-00141/pan20240905120953.pdf', '2024-09-05 12:08:50', '2024-09-05 12:09:52', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(50, 'AGN-00094', '', 'REQ-00238', 'APP-00001', 'MEM-00142', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00094/MEM-00142/pan20240913180411.pdf', '2024-09-13 18:01:24', '2024-09-13 18:04:11', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(51, 'AGN-00094', '', 'REQ-00239', 'APP-00001', 'MEM-00142', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00094/MEM-00142/pan20240913181840.pdf', '2024-09-13 18:13:29', '2024-09-13 18:18:39', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(52, 'AGN-00076', '', 'REQ-00241', 'APP-00001', 'MEM-00138', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00076/MEM-00138/pan20240930152120.pdf', '2024-09-30 15:19:50', '2024-09-30 15:21:20', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(53, 'AGN-00005', '', 'REQ-00245', 'APP-00001', 'MEM-00127', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00127/pan20241018164719.pdf', '2024-10-18 16:45:12', '2024-10-18 16:47:19', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(54, 'AGN-00005', '', 'REQ-00249', 'APP-00001', 'MEM-00146', '', 'DVF-00002', '', 20.00, '2', 'https://mounarchtech.com/central_wp/verification_data/pan/voco_xp/AGN-00005/MEM-00146/pan20241107231407.pdf', '2024-11-07 17:49:14', '2024-11-07 23:14:07', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80),
(55, 'AGN-00005', '', 'REQ-00255', 'APP-00001', 'MEM-00145', '', 'DVF-00002', '', 20.00, '0', '', '2024-11-08 00:16:25', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80);

-- --------------------------------------------------------

--
-- Table structure for table `passport_transaction_all`
--

CREATE TABLE `passport_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `price` float(10,2) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(10) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(10) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `passport_transaction_all`
--

INSERT INTO `passport_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`, `web_link_number`) VALUES
(1, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-03 11:30:34', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(2, 'AGN-00005', '', 'REQ-00178', 'APP-00001', 'MEM-00038', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-06 11:07:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(3, 'AGN-00005', '', 'REQ-00182', 'APP-00001', 'MEM-00043', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-09 12:30:13', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(4, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-16 16:49:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(5, 'AGN-00005', '', 'REQ-00188', 'APP-00001', 'MEM-00088', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-19 16:52:46', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(6, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(7, 'AGN-00005', '', 'REQ-00196', 'APP-00001', 'MEM-00100', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-22 13:08:26', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(8, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(9, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00006', '', 20.00, '0', '', '2024-07-24 11:23:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(10, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00006', '', 20.00, '0', '', '2024-08-02 22:13:48', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(11, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00006', '', 20.00, '0', '', '2024-08-06 12:37:03', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(12, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00006', '', 20.00, '0', '', '2024-08-08 17:34:02', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(13, 'AGN-00005', '', 'REQ-00253', 'APP-00001', 'MEM-00146', '', 'DVF-00006', '', 20.00, '0', '', '2024-11-08 00:14:49', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(14, 'AGN-00005', '', 'REQ-00254', 'APP-00001', 'MEM-00146', '', 'DVF-00006', '', 20.00, '0', '', '2024-11-08 00:15:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '');

-- --------------------------------------------------------

--
-- Table structure for table `product_header_all`
--

CREATE TABLE `product_header_all` (
  `id` int(11) NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `product_id` varchar(10) NOT NULL COMMENT '06 digit start with “P”',
  `product_code` varchar(14) NOT NULL COMMENT 'generated by marketing department',
  `product_name` varchar(100) NOT NULL,
  `product_tag_line` text NOT NULL COMMENT 'Max 50 Words',
  `product_shape` int(10) NOT NULL COMMENT '1=round, 2=square',
  `short_description` text NOT NULL COMMENT 'Max 300 words',
  `product_features` text NOT NULL COMMENT 'Max 700 words',
  `product_specification` text NOT NULL COMMENT 'Max 700 words',
  `product_MRP` float NOT NULL,
  `product_discount_per` float NOT NULL COMMENT 'in %',
  `sgst_percentage` int(11) NOT NULL,
  `cgst_percentage` int(11) NOT NULL,
  `highlights` text NOT NULL,
  `app_coupons` int(11) NOT NULL COMMENT '1=Y/0=N',
  `warranty` int(11) NOT NULL COMMENT ' in number of month - 0-no warranty , 1-one month, 3-three month ',
  `min_stock_lock` varchar(20) NOT NULL DEFAULT '0' COMMENT 'if entered qty is less than equal to (instock \r\n+ onsales) then sales of product will be stopped\r\n',
  `notify_me_qty` int(11) NOT NULL COMMENT 'total quantity(instock + onsales) get below this generate every day notifiation/sms',
  `on_sales_alert` int(100) NOT NULL COMMENT 'if defined qty is equal to or less than on_sales qty then daily alert will be generated',
  `images` text NOT NULL COMMENT 'CSV based image urls',
  `videos` text NOT NULL COMMENT 'CSV based video numbers',
  `offered_storage` varchar(100) NOT NULL COMMENT 'in MB',
  `is_health` int(10) NOT NULL COMMENT '1=Y,0=N',
  `status` int(11) NOT NULL COMMENT '1=active, 2=suspended',
  `created_on` date DEFAULT NULL,
  `modified_on` datetime NOT NULL,
  `is_returnable` int(10) NOT NULL COMMENT '1=Y/0=N',
  `permitted_days_for_return` int(11) NOT NULL COMMENT 'no of days from order recieved date, in between product can be returned',
  `applied_from` datetime NOT NULL,
  `upcoming_rates` longtext NOT NULL COMMENT 'single entry only\r\napplied date= mrp$discount^tax',
  `old_applied_rates` longtext NOT NULL COMMENT 'CSV value permitted\r\nfrom_date>to_date= mrp$discount^tax'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_header_all`
--

INSERT INTO `product_header_all` (`id`, `category_id`, `product_id`, `product_code`, `product_name`, `product_tag_line`, `product_shape`, `short_description`, `product_features`, `product_specification`, `product_MRP`, `product_discount_per`, `sgst_percentage`, `cgst_percentage`, `highlights`, `app_coupons`, `warranty`, `min_stock_lock`, `notify_me_qty`, `on_sales_alert`, `images`, `videos`, `offered_storage`, `is_health`, `status`, `created_on`, `modified_on`, `is_returnable`, `permitted_days_for_return`, `applied_from`, `upcoming_rates`, `old_applied_rates`) VALUES
(1, 'CAT-00001', 'PRO-00001', 'P12344', 'Fire-Boltt Fire-Boltt Gladiator 1.96\" Biggest Display Smart Watch', 'Fire-Boltt Phoenix Smart Watch with Bluetooth Calling 1.3\",120+ Sports Modes, 240 * 240 PX High Res with SpO2, Heart Rate Monitoring & IP67 Rating (Gold Pink)', 1, 'Fire-Boltt Phoenix Smart Watch with Bluetooth Calling 1.3\",120+ Sports Modes, 240 * 240 PX High Res with SpO2, Heart Rate Monitoring & IP67 Rating (Gold Pink)', 'Fire-Boltt Phoenix Smart Watch with Bluetooth Calling 1.3\",120+ Sports Modes, 240 * 240 PX High Res with SpO2, Heart Rate Monitoring & IP67 Rating (Gold Pink)', 'Fire-Boltt Phoenix Smart Watch with Bluetooth Calling 1.3\",120+ Sports Modes, 240 * 240 PX High Res with SpO2, Heart Rate Monitoring & IP67 Rating (Gold Pink)', 9999, 88, 8, 8, '', 0, 0, '0', 0, 0, 'https://mounarchtech.com/central_wp/category/CAT-00001/PRO-00001/1716365277_cac112857468cd029275.jpg,', '', '', 0, 1, '2024-05-22', '0000-00-00 00:00:00', 0, 0, '2024-05-22 00:00:00', '', ''),
(17, 'CAT-00003', 'PRO-00002', 'P12344', 'Smart Watch', 'Orange WatchOrange Watch', 1, 'Orange WatchOrange Watch', 'Orange WatchOrange Watch', 'Orange WatchOrange Watch', 2000, 10, 0, 0, '', 0, 0, '0', 0, 0, 'http://localhost/server/central_wp/category/CAT-00003/PRO-00002/1715435064_c587462dbc73aa1846b2.jpg,', '', '', 0, 1, '2024-05-11', '0000-00-00 00:00:00', 0, 0, '2024-05-29 00:00:00', '', ''),
(18, 'CAT-00004', 'PRO-00004', 'P12349', 'Smart Watch', 'name=\"product_shape\"name=\"product_shape\"', 2, 'name=\"product_shape\"name=\"product_shape\"name=\"product_shape\"', 'name=\"product_shape\"name=\"product_shape\"', 'name=\"product_shape\"name=\"product_shape\"', 2000, 16, 0, 0, '', 0, 0, '0', 0, 0, 'http://localhost/server/central_wp/category/CAT-00004/PRO-00004/1715453912_a4ca4dfa687202f4ec3a.jpg,', '', '', 0, 1, '2024-05-11', '0000-00-00 00:00:00', 0, 0, '2024-05-31 00:00:00', '', ''),
(19, 'CAT-00003', 'PRO-00005', 'P12350', 'Apple Watch Series 9', 'Apple Watch Series 9', 1, 'Apple Watch Series 9', 'Apple Watch Series 9', 'Apple Watch Series 9', 20000, 52, 8, 8, '', 0, 0, '0', 0, 0, 'http://localhost/server/central_wp/category/CAT-00003/PRO-00005/1716282009_1eae37a0898e3509874f.jpg,', '', '', 0, 1, '2024-05-21', '0000-00-00 00:00:00', 0, 0, '2024-05-26 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_item_archive_all`
--

CREATE TABLE `product_item_archive_all` (
  `id` int(11) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `category_id` varchar(20) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `item_no` varchar(12) NOT NULL,
  `old_warranty` varchar(100) NOT NULL,
  `old_replacement` varchar(100) NOT NULL,
  `warranty_applied_on` date NOT NULL,
  `replacement_applied_on` date NOT NULL,
  `status_operational` int(20) NOT NULL COMMENT '	1:in stock, 2:On Sales, 3:Sold but not dispatched, 4:Faulty (under QA), 5:Dispatched, 6:Under Dispatched Process, 7:At End Client, 8:In Return Process, 9:Return Received, 10:Inspection at QA, 11:Permanent Faulty',
  `pre_dispatch_tests` varchar(150) NOT NULL COMMENT 'in CSV (if status == "under QA =dateTime" )',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_item_header_all`
--

CREATE TABLE `product_item_header_all` (
  `id` int(11) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `category_id` varchar(20) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `item_no` varchar(12) NOT NULL,
  `display_diagnostic_tool` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `diagnostic_time_limit` datetime NOT NULL COMMENT 'Buffering time for restart watch after diagnostic tool request',
  `is_item_configuration` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `old_warranty` varchar(100) NOT NULL,
  `old_replacement` varchar(100) NOT NULL,
  `warranty_applied_on` date NOT NULL,
  `replacement_applied_on` date NOT NULL,
  `status_operational` int(20) NOT NULL COMMENT '	1:in stock, 2:On Sales, 3:Sold but not dispatched, 4:Faulty (under QA), 5:Dispatched, 6:Under Dispatched Process, 7:At End Client, 8:In Return Process, 9:Return Received, 10:Inspection at QA, 11:Permanent Faulty',
  `pre_dispatch_tests` varchar(150) NOT NULL COMMENT 'in CSV (if status == "under QA =dateTime" )',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_item_header_all`
--

INSERT INTO `product_item_header_all` (`id`, `product_id`, `category_id`, `item_id`, `item_no`, `display_diagnostic_tool`, `diagnostic_time_limit`, `is_item_configuration`, `old_warranty`, `old_replacement`, `warranty_applied_on`, `replacement_applied_on`, `status_operational`, `pre_dispatch_tests`, `created_on`, `modified_on`) VALUES
(1, 'PRO-00001', 'CAT-00001', 'ITM-00001', '2562236001', 0, '0000-00-00 00:00:00', 0, '', '', '0000-00-00', '0000-00-00', 1, '', '2024-05-22 08:23:29', '0000-00-00 00:00:00'),
(2, 'PRO-00001', 'CAT-00001', 'ITM-00003', '2565919003', 1, '0000-00-00 00:00:00', 0, '', '', '0000-00-00', '0000-00-00', 1, '', '2024-08-22 21:28:25', '0000-00-00 00:00:00'),
(3, 'PRO-00001', 'CAT-00001', 'ITM-00002', '2155722002', 1, '0000-00-00 00:00:00', 0, '', '', '0000-00-00', '0000-00-00', 1, '', '2024-08-22 21:45:23', '0000-00-00 00:00:00'),
(4, 'PRO-00001', 'CAT-00001', 'ITM-00004', '2561734004', 1, '0000-00-00 00:00:00', 0, '', '', '0000-00-00', '0000-00-00', 1, '', '2024-08-23 01:18:50', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `profile_header_all`
--

CREATE TABLE `profile_header_all` (
  `id` int(10) NOT NULL,
  `profile_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `profile_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'display name',
  `form_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV / all - means all forms are accessable',
  `process_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV / all - means all processes are accessable',
  `profile_status` int(10) NOT NULL COMMENT '1=active/2=inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `profile_header_all`
--

INSERT INTO `profile_header_all` (`id`, `profile_id`, `profile_name`, `form_id`, `process_id`, `profile_status`) VALUES
(3, 'PRO_0001', 'Member Management', 'F_MAN_001,F_MEM_001,F_MEM_002,F_MEM_003,F_MEM_004,F_MBM_001,F_MEM_005,F_PRO_001\n\n\n', 'P_MAN_0001,P_MAN_0005,P_MAN_0006,P_MAN_0011,P_MAN_0012,P_MEM_0001,P_MEM_0003,P_MEM_0004,P_MEM_0006,P_MEM_0007,P_MEM_0008,P_MEM_0009,P_MBM_0001,P_MAN_0013,P_MEM_0011\n', 1),
(4, 'PRO_0002', 'Digital Verification', 'F_MAN_001,F_DIG_001,F_TIH_001,F_VER_001\r\n', 'P_MAN_0001,P_MAN_0003,P_MAN_0005,P_MAN_0006,P_MAN_0008,P_MAN_0009,P_MAN_0013,P_MEM_0002\n', 1),
(5, 'PRO_0003', 'Wallet Management', 'F_MAN_001,F_WAL_001,F_WAR_001\r\n', 'P_MAN_0001,P_MAN_0002,P_MAN_0005,P_MAN_0006,P_MAN_0008,P_WAL_0001,P_MAN_0013', 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_intransit_details_all`
--

CREATE TABLE `return_intransit_details_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(11) NOT NULL,
  `agency_id` varchar(11) NOT NULL,
  `branch_id` varchar(11) NOT NULL,
  `packet_id` varchar(11) NOT NULL,
  `packet_item_nos` longtext NOT NULL COMMENT 'item numbers in csv',
  `dispatch_date` datetime NOT NULL,
  `courier_name` varchar(50) NOT NULL,
  `tracking_id` varchar(16) NOT NULL,
  `courier_slip_image` longtext NOT NULL,
  `status` int(11) NOT NULL COMMENT '1:in transit, 2:recieved',
  `recieved_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `table_inside_table_all`
--

CREATE TABLE `table_inside_table_all` (
  `id` int(11) NOT NULL,
  `table_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_alert_on` int(11) NOT NULL,
  `maximum_row_limit` int(11) NOT NULL,
  `is_stopable` int(11) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes ',
  `stop_generated_on` datetime NOT NULL,
  `error_code` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unique_id_header_all`
--

CREATE TABLE `unique_id_header_all` (
  `id` int(10) NOT NULL,
  `table_name` varchar(100) NOT NULL COMMENT 'table where is id is stored',
  `id_for` varchar(50) NOT NULL COMMENT 'name, for which we are creating this ID',
  `prefix` varchar(20) NOT NULL COMMENT 'set by us, such as for Item Number we can set prefix as IN',
  `last_id` varchar(15) NOT NULL COMMENT 'last id generated for this id, if this column is empty then next id will be 00001, final id mst be IN_00001',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `unique_id_header_all`
--

INSERT INTO `unique_id_header_all` (`id`, `table_name`, `id_for`, `prefix`, `last_id`, `created_on`, `modified_on`) VALUES
(1, 'category_header_all', 'category_id', 'CAT', 'CAT-00001', '2024-05-22 07:30:53', '0000-00-00 00:00:00'),
(2, 'product_header_all', 'product_id', 'PRO', 'PRO-00001', '2024-05-22 08:07:57', '0000-00-00 00:00:00'),
(3, 'item_master_all', 'item_id', 'ITM', 'ITM-00005', '2024-05-22 08:22:36', '2024-08-23 03:22:42'),
(4, 'verification_header_all', 'verification_id', 'DVF', 'DVF-00006', '2024-05-22 12:44:49', '2024-05-22 12:44:49'),
(5, 'application_header_all', 'application_id', 'APP', 'APP-00003', '2024-05-22 12:45:59', '2024-05-22 12:45:59'),
(6, 'application_storage_plan_all', 'plan_id', 'PLAN', 'PLAN-00001', '2024-05-22 12:47:10', '2024-05-22 12:47:10'),
(7, 'web_user_header_all', 'employee_id', 'EMP', 'EMP-00001', '2024-05-22 12:49:56', '2024-05-22 12:49:56'),
(8, 'verification_payment_transaction_all', 'request_id', 'REQ', 'REQ-00255', '2024-05-28 15:33:04', '2024-11-08 00:16:25'),
(21, 'direct_verification_details_all', 'direct_id', 'DIR', 'DIR-00417', '2024-06-12 14:37:06', '2024-07-02 18:01:41'),
(22, 'item_diagnostic_report_header_all', '', 'DGN', 'DGN-00003', '2024-08-22 21:46:19', '2024-09-10 12:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `verification_configuration_all`
--

CREATE TABLE `verification_configuration_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `is_national` int(5) NOT NULL DEFAULT '0' COMMENT '0=this is not national, 1= this is national',
  `city_id` varchar(50) NOT NULL COMMENT 'CSV Value of city ids',
  `rate` float(10,2) NOT NULL DEFAULT '0.00',
  `created_on` datetime NOT NULL,
  `applied_from` datetime NOT NULL,
  `valid_till` datetime NOT NULL,
  `sgst_percentage` int(10) NOT NULL COMMENT 'government gst tax',
  `cgst_percentage` int(10) NOT NULL COMMENT 'government gst tax',
  `line_no` int(10) NOT NULL,
  `operational_status` int(10) NOT NULL COMMENT '0=past, 1=current, 2= upcoming',
  `ver_type` int(11) NOT NULL COMMENT '1=direct_all, 2=member, 3=Weblink'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verification_configuration_all`
--

INSERT INTO `verification_configuration_all` (`id`, `application_id`, `verification_id`, `is_national`, `city_id`, `rate`, `created_on`, `applied_from`, `valid_till`, `sgst_percentage`, `cgst_percentage`, `line_no`, `operational_status`, `ver_type`) VALUES
(1, 'APP-00001', 'DVF-00001', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(2, 'APP-00001', 'DVF-00002', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(3, 'APP-00001', 'DVF-00003', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(4, 'APP-00001', 'DVF-00004', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(5, 'APP-00001', 'DVF-00005', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(6, 'APP-00001', 'DVF-00006', 1, '', 20.00, '2023-02-14 11:14:08', '2024-02-01 09:15:43', '2025-08-31 09:16:31', 9, 9, 0, 1, 2),
(7, 'APP-00001', 'DVF-00001', 1, '', 33.00, '2024-06-19 21:11:07', '2024-06-19 21:11:07', '2025-08-31 09:16:31', 9, 9, 0, 1, 1),
(8, 'APP-00001', 'DVF-00002', 1, '', 17.00, '2024-06-19 21:11:28', '2024-06-19 21:11:28', '2025-08-31 09:16:31', 9, 9, 0, 1, 1),
(9, 'APP-00001', 'DVF-00003', 1, '', 20.00, '2024-06-19 21:11:41', '2024-06-19 21:11:41', '2025-08-31 09:16:31', 9, 9, 0, 1, 1),
(10, 'APP-00001', 'DVF-00004', 1, '', 20.00, '2024-06-19 21:12:03', '2024-06-19 21:12:03', '2025-08-31 09:16:31', 9, 9, 0, 1, 1),
(12, 'APP-00001', 'DVF-00006', 1, '', 20.00, '2024-06-20 12:59:16', '2024-06-20 12:59:16', '2025-08-31 09:16:31', 9, 9, 0, 1, 1),
(13, 'APP-00001', 'DVF-00007', 1, '', 20.00, '2024-10-10 13:05:38', '2024-10-10 13:05:38', '2027-03-27 13:05:38', 9, 9, 0, 1, 1),
(14, 'APP-00001', 'DVF-00001', 1, '', 33.00, '2024-06-19 21:11:07', '2024-06-19 21:11:07', '2025-08-31 09:16:31', 9, 9, 0, 1, 3),
(15, 'APP-00001', 'DVF-00002', 1, '', 17.00, '2024-06-19 21:11:28', '2024-06-19 21:11:28', '2025-08-31 09:16:31', 9, 9, 0, 1, 3),
(16, 'APP-00001', 'DVF-00004', 1, '', 20.00, '2024-06-19 21:12:03', '2024-06-19 21:12:03', '2025-08-31 09:16:31', 9, 9, 0, 1, 3),
(17, 'APP-00001', 'DVF-00005', 1, '', 20.00, '2024-06-19 21:12:03', '2024-06-19 21:12:03', '2025-08-31 09:16:31', 9, 9, 0, 1, 3),
(18, 'APP-00001', 'DVF-00008', 1, '', 2.00, '2024-06-19 21:12:03', '2024-06-19 21:12:03', '2025-08-31 09:16:31', 9, 9, 0, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `verification_header_all`
--

CREATE TABLE `verification_header_all` (
  `id` bigint(20) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `name` longtext NOT NULL,
  `abbreviations` varchar(50) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `type` longtext NOT NULL,
  `req_document` longtext NOT NULL,
  `advantages` longtext NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=Active/0=Inactive',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `with_otp` varchar(10) NOT NULL COMMENT 'yes/no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verification_header_all`
--

INSERT INTO `verification_header_all` (`id`, `verification_id`, `name`, `abbreviations`, `table_name`, `image`, `type_id`, `type`, `req_document`, `advantages`, `status`, `created_on`, `modified_on`, `with_otp`) VALUES
(1, 'DVF-00001', 'Aadhar Card', 'AAV', 'aadhar_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/aadhar/adhar.jpeg', '', '', 'adhar card, Mobile no. which you have registered with aadhar', 'to verify person is genuine or not ', 1, '2023-02-13 18:05:56', '2023-02-20 13:00:14', ''),
(2, 'DVF-00002', 'Pan Card', 'PC', 'pan_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/pan/pan.jpeg', '', '', 'Original Pan Card', 'to verify person is Genuine or not ', 1, '2023-02-13 18:11:57', '2023-02-20 12:59:16', ''),
(3, 'DVF-00003', 'Criminal Record Verification', 'CR', 'ecrime_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/e_crime/e_crime.jpeg', '', '', 'name, Date of Birth, Address proof,mobile', 'To check criminal record       ', 1, '2023-02-13 18:13:44', '2023-05-06 10:43:23', ''),
(49, 'DVF-00004', 'Driving license', 'DL', 'driving_license_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/driving/driving_lic.jpeg', '', '', 'Driving License hard copy', 'Verify Driving License details', 1, '2023-06-09 12:56:44', '2023-06-09 12:56:44', ''),
(50, 'DVF-00005', 'Voter ID', 'VC', 'voter_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/voter/voter_id.jpeg', '', '', 'Voter ID hard Copy document', 'Verify Voter ID', 1, '2023-06-09 21:33:15', '2023-06-09 21:33:15', ''),
(51, 'DVF-00006', 'Domestic Passport', 'PV', 'passport_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/passport/passport.jpeg', '', '', 'Hard Copy of passport', 'Verify Passport', 1, '2023-06-11 11:28:39', '2023-06-11 11:28:39', ''),
(52, 'DVF-00007', 'International Passport', 'IPV', 'passport_transaction_all', 'https://mounarchtech.com/central_wp/verification_data/passport/passport.jpeg', '', '', 'Hard Copy of passport', 'Verify International Passport', 1, '2024-10-10 11:28:39', '2024-10-10 11:28:39', ''),
(53, 'DVF-00008', 'Mobile Verification', 'MOB', '', '', '', '', 'Mobile Number', 'Verify Number', 1, '2024-10-10 11:28:39', '2024-10-10 11:28:39', '');

-- --------------------------------------------------------

--
-- Table structure for table `verification_payment_archive_all`
--

CREATE TABLE `verification_payment_archive_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `branch_id` varchar(100) NOT NULL,
  `person_id` varchar(50) NOT NULL,
  `request_for` int(10) NOT NULL DEFAULT '1' COMMENT '1=other_person, 0=guard',
  `request_id` varchar(50) NOT NULL,
  `sub_amount` float(10,2) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `net_amount` float(10,2) NOT NULL,
  `payment_trans_id` varchar(100) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-Deactive,1-Active',
  `otp_of_confirmation` int(1) NOT NULL DEFAULT '0' COMMENT '1-yes, 0-no',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `check_payment_terms` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `payment_via` varchar(100) NOT NULL,
  `verification_ids` varchar(200) NOT NULL COMMENT 'verifications requested in this',
  `gst_no` varchar(50) NOT NULL,
  `invoice_url` longtext NOT NULL,
  `is_invoice_sent` varchar(200) NOT NULL COMMENT 'yes/no and email id',
  `if_refund` int(20) NOT NULL COMMENT '1=yes/0=no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `verification_payment_transaction_all`
--

CREATE TABLE `verification_payment_transaction_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `branch_id` varchar(100) NOT NULL,
  `person_id` varchar(50) NOT NULL,
  `request_for` int(10) NOT NULL DEFAULT '1' COMMENT '1=other_person, 0=guard',
  `request_id` varchar(50) NOT NULL,
  `sub_amount` float(10,2) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `net_amount` float(10,2) NOT NULL,
  `payment_trans_id` varchar(100) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-Deactive,1-Active',
  `otp_of_confirmation` int(1) NOT NULL DEFAULT '0' COMMENT '1-yes, 0-no',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `check_payment_terms` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `payment_via` varchar(100) NOT NULL,
  `verification_ids` varchar(200) NOT NULL COMMENT 'verifications requested in this',
  `gst_no` varchar(50) NOT NULL,
  `invoice_url` longtext NOT NULL,
  `is_invoice_sent` varchar(200) NOT NULL COMMENT 'yes/no and email id',
  `if_refund` int(20) NOT NULL COMMENT '1=yes/0=no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verification_payment_transaction_all`
--

INSERT INTO `verification_payment_transaction_all` (`id`, `application_id`, `agency_id`, `branch_id`, `person_id`, `request_for`, `request_id`, `sub_amount`, `sgst_amount`, `cgst_amount`, `net_amount`, `payment_trans_id`, `payment_status`, `status`, `otp_of_confirmation`, `created_on`, `modified_on`, `check_payment_terms`, `payment_via`, `verification_ids`, `gst_no`, `invoice_url`, `is_invoice_sent`, `if_refund`) VALUES
(1, 'APP-00001', 'AGN-00002', '', 'MEM-00001', 0, 'REQ-00163', 40.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-01 21:32:28', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00001/invoice_20240701213228_6.pdf', '', 0),
(2, 'APP-00001', 'AGN-00002', '', 'MEM-00001', 0, 'REQ-00164', 40.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-01 21:33:28', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00001/invoice_20240701213328_7.pdf', '', 0),
(3, 'APP-00001', 'AGN-00002', '', 'MEM-00001', 0, 'REQ-00165', 40.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-01 21:36:45', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00001/invoice_20240701213646_8.pdf', '', 0),
(4, 'APP-00001', 'AGN-00002', '', 'MEM-00001', 0, 'REQ-00166', 40.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-01 21:43:53', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00001/invoice_20240701214353_9.pdf', '', 0),
(5, 'APP-00001', 'AGN-00001', '', 'MEM-00017', 0, 'REQ-00167', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-02 14:11:11', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00017/invoice_20240702141111_17.pdf', '', 0),
(6, 'APP-00001', 'AGN-00001', '', 'MEM-00017', 0, 'REQ-00168', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-03 06:55:33', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00017/invoice_20240703065534_18.pdf', '', 0),
(7, 'APP-00001', 'AGN-00002', '', 'MEM-00015', 0, 'REQ-00169', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-03 10:42:52', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00015/invoice_20240703104252_19.pdf', '', 0),
(8, 'APP-00001', 'AGN-00002', '', 'MEM-00015', 0, 'REQ-00170', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-03 10:49:59', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00015/invoice_20240703104959_20.pdf', '', 0),
(9, 'APP-00001', 'AGN-00005', '', 'MEM-00037', 0, 'REQ-00171', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-07-03 11:30:34', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00037/invoice_20240703113035_4.pdf', '', 0),
(10, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00172', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-05 14:42:01', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240705144202_5.pdf', '', 0),
(11, 'APP-00001', 'AGN-00005', '', 'MEM-00045', 0, 'REQ-00173', 80.00, 7.20, 7.20, 94.40, '', 'payment_success', 1, 1, '2024-07-05 14:57:21', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00045/invoice_20240705145721_4.pdf', '', 0),
(12, 'APP-00001', 'AGN-00002', '', 'MEM-00044', 0, 'REQ-00174', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-05 16:57:00', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00044/invoice_20240705165701_5.pdf', '', 0),
(13, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00175', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-06 01:35:59', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240706013559_6.pdf', '', 0),
(14, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00176', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-06 01:37:48', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240706013748_7.pdf', '', 0),
(15, 'APP-00001', 'AGN-00005', '', 'MEM-00045', 0, 'REQ-00177', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-06 01:48:10', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00045/invoice_20240706014810_5.pdf', '', 0),
(16, 'APP-00001', 'AGN-00005', '', 'MEM-00038', 0, 'REQ-00178', 100.00, 9.00, 9.00, 118.00, '', 'payment_success', 1, 1, '2024-07-06 11:07:47', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00038/invoice_20240706110747_3.pdf', '', 0),
(17, 'APP-00001', 'AGN-00001', '', 'MEM-00017', 0, 'REQ-00179', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-06 16:09:13', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00017/invoice_20240706160913_19.pdf', '', 0),
(18, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00180', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-09 12:16:33', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240709121633_8.pdf', '', 0),
(19, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00181', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-09 12:17:13', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240709121713_9.pdf', '', 0),
(20, 'APP-00001', 'AGN-00005', '', 'MEM-00043', 0, 'REQ-00182', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-09 12:30:13', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00043/invoice_20240709123013_10.pdf', '', 0),
(21, 'APP-00001', 'AGN-00005', '', 'MEM-00084', 0, 'REQ-00183', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-16 11:32:13', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00084/invoice_20240716113213_3.pdf', '', 0),
(22, 'APP-00001', 'AGN-00005', '', 'MEM-00084', 0, 'REQ-00184', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-16 11:47:31', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00084/invoice_20240716114731_4.pdf', '', 0),
(23, 'APP-00001', 'AGN-00016', '', 'MEM-00085', 0, 'REQ-00185', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-07-16 16:49:19', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00085/invoice_20240716164920_3.pdf', '', 0),
(24, 'APP-00001', 'AGN-00005', '', 'MEM-00088', 0, 'REQ-00186', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-19 16:49:38', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00088/invoice_20240719164938_5.pdf', '', 0),
(25, 'APP-00001', 'AGN-00005', '', 'MEM-00088', 0, 'REQ-00187', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-19 16:49:58', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00088/invoice_20240719164958_6.pdf', '', 0),
(26, 'APP-00001', 'AGN-00005', '', 'MEM-00088', 0, 'REQ-00188', 40.00, 3.60, 3.60, 47.20, '', 'payment_success', 1, 1, '2024-07-19 16:52:46', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00088/invoice_20240719165246_7.pdf', '', 0),
(27, 'APP-00001', 'AGN-00002', '', 'MEM-00057', 0, 'REQ-00189', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-20 11:00:16', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00057/invoice_20240720110016_9.pdf', '', 0),
(28, 'APP-00001', 'AGN-00002', '', 'MEM-00044', 0, 'REQ-00190', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-20 11:00:44', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00044/invoice_20240720110044_6.pdf', '', 0),
(29, 'APP-00001', 'AGN-00005', '', 'MEM-00092', 0, 'REQ-00191', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-21 00:08:42', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00092/invoice_20240721000842_6.pdf', '', 0),
(30, 'APP-00001', 'AGN-00001', '', 'MEM-00046', 0, 'REQ-00192', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-07-22 10:53:47', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00046/invoice_20240722105348_35.pdf', '', 0),
(31, 'APP-00001', 'AGN-00005', '', 'MEM-00097', 0, 'REQ-00193', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-22 12:10:00', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00097/invoice_20240722121000_1.pdf', '', 0),
(32, 'APP-00001', 'AGN-00005', '', 'MEM-00098', 0, 'REQ-00194', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-22 12:55:33', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00098/invoice_20240722125533_1.pdf', '', 0),
(33, 'APP-00001', 'AGN-00005', '', 'MEM-00100', 0, 'REQ-00195', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-22 13:08:06', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00100/invoice_20240722130806_1.pdf', '', 0),
(34, 'APP-00001', 'AGN-00005', '', 'MEM-00100', 0, 'REQ-00196', 100.00, 9.00, 9.00, 118.00, '', 'payment_success', 1, 1, '2024-07-22 13:08:26', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00100/invoice_20240722130826_2.pdf', '', 0),
(35, 'APP-00001', 'AGN-00051', '', 'MEM-00102', 0, 'REQ-00197', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-23 11:39:58', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00102/invoice_20240723113959_2.pdf', '', 0),
(36, 'APP-00001', 'AGN-00051', '', 'MEM-00102', 0, 'REQ-00198', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-23 11:59:35', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00102/invoice_20240723115936_3.pdf', '', 0),
(37, 'APP-00001', 'AGN-00051', '', 'MEM-00102', 0, 'REQ-00199', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-23 12:08:42', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00102/invoice_20240723120843_4.pdf', '', 0),
(38, 'APP-00001', 'AGN-00051', '', 'MEM-00102', 0, 'REQ-00200', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-23 12:11:21', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00102/invoice_20240723121121_5.pdf', '', 0),
(39, 'APP-00001', 'AGN-00051', '', 'MEM-00102', 0, 'REQ-00201', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-23 12:43:38', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00102/invoice_20240723124339_6.pdf', '', 0),
(40, 'APP-00001', 'AGN-00005', '', 'MEM-00101', 0, 'REQ-00202', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 0, '2024-07-24 11:22:17', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00101/invoice_20240724112217_2.pdf', '', 0),
(41, 'APP-00001', 'AGN-00005', '', 'MEM-00104', 0, 'REQ-00203', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-07-24 11:23:54', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00104/invoice_20240724112354_1.pdf', '', 0),
(42, 'APP-00001', 'AGN-00002', '', 'MEM-00103', 0, 'REQ-00204', 40.00, 3.60, 3.60, 47.20, '', 'payment_success', 1, 1, '2024-07-24 11:36:44', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00103/invoice_20240724113644_2.pdf', '', 0),
(43, 'APP-00001', 'AGN-00002', '', 'MEM-00103', 0, 'REQ-00205', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-24 11:50:07', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00103/invoice_20240724115008_3.pdf', '', 0),
(44, 'APP-00001', 'AGN-00002', '', 'MEM-00103', 0, 'REQ-00206', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-07-26 12:42:27', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00103/invoice_20240726124228_4.pdf', '', 0),
(45, 'APP-00001', 'AGN-00075', '', 'MEM-00105', 0, 'REQ-00207', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-07-30 15:07:53', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00105/invoice_20240730150753_3.pdf', '', 0),
(46, 'APP-00001', 'AGN-00062', '', 'MEM-00111', 0, 'REQ-00208', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-08-02 22:13:48', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00111/invoice_20240802221348_3.pdf', '', 0),
(47, 'APP-00001', 'AGN-00002', '', 'MEM-00103', 0, 'REQ-00209', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-05 18:04:50', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00103/invoice_20240805180450_5.pdf', '', 0),
(48, 'APP-00001', 'AGN-00002', '', 'MEM-00103', 0, 'REQ-00210', 40.00, 3.60, 3.60, 47.20, '', 'payment_success', 1, 1, '2024-08-05 18:07:35', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00103/invoice_20240805180736_6.pdf', '', 0),
(49, 'APP-00001', 'AGN-00076', '', 'MEM-00112', 0, 'REQ-00211', 80.00, 7.20, 7.20, 94.40, '', 'payment_success', 1, 1, '2024-08-05 18:09:47', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00112/invoice_20240805180947_2.pdf', '', 0),
(50, 'APP-00001', 'AGN-00005', '', 'MEM-00104', 0, 'REQ-00212', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-05 18:33:03', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00104/invoice_20240805183303_2.pdf', '', 0),
(51, 'APP-00001', 'AGN-00005', '', 'MEM-00104', 0, 'REQ-00213', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-05 18:35:36', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00104/invoice_20240805183536_3.pdf', '', 0),
(52, 'APP-00001', 'AGN-00005', '', 'MEM-00104', 0, 'REQ-00214', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-05 18:37:57', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00104/invoice_20240805183758_4.pdf', '', 0),
(53, 'APP-00001', 'AGN-00005', '', 'MEM-00104', 0, 'REQ-00215', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-06 06:28:02', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00104/invoice_20240806062802_5.pdf', '', 0),
(54, 'APP-00001', 'AGN-00005', '', 'MEM-00113', 0, 'REQ-00216', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-08-06 12:37:03', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00113/invoice_20240806123703_1.pdf', '', 0),
(55, 'APP-00001', 'AGN-00076', '', 'MEM-00112', 0, 'REQ-00217', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-06 12:49:15', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00112/invoice_20240806124915_3.pdf', '', 0),
(56, 'APP-00001', 'AGN-00076', '', 'MEM-00115', 0, 'REQ-00218', 40.00, 3.60, 3.60, 47.20, '', 'payment_success', 1, 1, '2024-08-06 13:03:57', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00115/invoice_20240806130357_6.pdf', '', 0),
(57, 'APP-00001', 'AGN-00076', '', 'MEM-00115', 0, 'REQ-00219', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-06 13:05:37', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00115/invoice_20240806130537_7.pdf', '', 0),
(58, 'APP-00001', 'AGN-00076', '', 'MEM-00116', 0, 'REQ-00220', 40.00, 3.60, 3.60, 47.20, '', 'payment_success', 1, 1, '2024-08-06 13:17:27', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00116/invoice_20240806131727_5.pdf', '', 0),
(59, 'APP-00001', 'AGN-00076', '', 'MEM-00116', 0, 'REQ-00221', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-06 13:21:14', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00116/invoice_20240806132114_6.pdf', '', 0),
(60, 'APP-00001', 'AGN-00002', '', 'MEM-00044', 0, 'REQ-00222', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-06 17:43:01', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00044/invoice_20240806174301_7.pdf', '', 0),
(61, 'APP-00001', 'AGN-00076', '', 'MEM-00112', 0, 'REQ-00223', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-08-07 13:32:00', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00112/invoice_20240807133200_4.pdf', '', 0),
(62, 'APP-00001', 'AGN-00062', '', 'MEM-00119', 0, 'REQ-00224', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-07 17:43:10', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00119/invoice_20240807174310_10.pdf', '', 0),
(63, 'APP-00001', 'AGN-00005', '', 'MEM-00130', 0, 'REQ-00225', 120.00, 10.80, 10.80, 141.60, '', 'payment_success', 1, 1, '2024-08-08 17:34:02', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00130/invoice_20240808173402_1.pdf', '', 0),
(64, 'APP-00001', 'AGN-00076', '', 'MEM-00132', 0, 'REQ-00226', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-09 16:38:03', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00132/invoice_20240809163804_4.pdf', '', 0),
(65, 'APP-00001', 'AGN-00049', '', 'MEM-00128', 0, 'REQ-00227', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-08-10 13:32:14', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00128/invoice_20240810133215_5.pdf', '', 0),
(66, 'APP-00001', 'AGN-00049', '', 'MEM-00128', 0, 'REQ-00228', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-08-10 13:34:19', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00128/invoice_20240810133419_6.pdf', '', 0),
(67, 'APP-00001', 'AGN-00005', '', 'MEM-00113', 0, 'REQ-00229', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-12 12:50:29', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00113/invoice_20240812125029_2.pdf', '', 0),
(68, 'APP-00001', 'AGN-00089', '', 'MEM-00134', 0, 'REQ-00230', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-22 00:57:35', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00134/invoice_20240822005735_1.pdf', '', 0),
(69, 'APP-00001', 'AGN-00062', '', 'MEM-00136', 0, 'REQ-00231', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-23 11:41:08', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00136/invoice_20240823114108_1.pdf', '', 0),
(70, 'APP-00001', 'AGN-00076', '', 'MEM-00137', 0, 'REQ-00232', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-08-29 17:13:50', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00137/invoice_20240829171350_2.pdf', '', 0),
(71, 'APP-00001', 'AGN-00005', '', 'MEM-00113', 0, 'REQ-00233', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-03 11:33:47', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00113/invoice_20240903113347_3.pdf', '', 0),
(72, 'APP-00001', 'AGN-00005', '', 'MEM-00139', 0, 'REQ-00234', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-03 11:38:16', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00139/invoice_20240903113816_1.pdf', '', 0),
(73, 'APP-00001', 'AGN-00076', '', 'MEM-00138', 0, 'REQ-00235', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-04 22:14:07', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00138/invoice_20240904221408_1.pdf', '', 0),
(74, 'APP-00001', 'AGN-00062', '', 'MEM-00141', 0, 'REQ-00236', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-05 12:04:09', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00141/invoice_20240905120409_1.pdf', '', 0),
(75, 'APP-00001', 'AGN-00062', '', 'MEM-00141', 0, 'REQ-00237', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-05 12:08:50', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00141/invoice_20240905120850_2.pdf', '', 0),
(76, 'APP-00001', 'AGN-00094', '', 'MEM-00142', 0, 'REQ-00238', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-13 18:01:24', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00142/invoice_20240913180125_1.pdf', '', 0),
(77, 'APP-00001', 'AGN-00094', '', 'MEM-00142', 0, 'REQ-00239', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-13 18:13:29', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00142/invoice_20240913181329_2.pdf', '', 0),
(78, 'APP-00001', 'AGN-00076', '', 'MEM-00138', 0, 'REQ-00240', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-30 15:18:55', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00138/invoice_20240930151855_2.pdf', '', 0),
(79, 'APP-00001', 'AGN-00076', '', 'MEM-00138', 0, 'REQ-00241', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-09-30 15:19:50', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00138/invoice_20240930151950_3.pdf', '', 0),
(80, 'APP-00001', 'AGN-00096', '', 'MEM-00143', 0, 'REQ-00242', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-10-03 15:29:36', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00143/invoice_20241003152937_1.pdf', '', 0),
(81, 'APP-00001', 'AGN-00096', '', 'MEM-00143', 0, 'REQ-00243', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-10-03 15:45:14', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00143/invoice_20241003154514_2.pdf', '', 0),
(82, 'APP-00001', 'AGN-00096', '', 'MEM-00143', 0, 'REQ-00244', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-10-03 15:45:31', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00143/invoice_20241003154531_3.pdf', '', 0),
(83, 'APP-00001', 'AGN-00005', '', 'MEM-00127', 0, 'REQ-00245', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-10-18 16:45:12', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00127/invoice_20241018164513_6.pdf', '', 0),
(84, 'APP-00001', 'AGN-00005', '', 'MEM-00113', 0, 'REQ-00246', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-10-26 09:09:11', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00113/invoice_20241026090912_4.pdf', '', 0),
(85, 'APP-00001', 'AGN-00005', '', 'MEM-00113', 0, 'REQ-00247', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-10-30 05:39:13', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00113/invoice_20241030053913_5.pdf', '', 0),
(86, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00248', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-07 17:44:51', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241107174451_1.pdf', '', 0),
(87, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00249', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-07 17:49:14', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241107174914_2.pdf', '', 0),
(88, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00250', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-07 17:58:54', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241107175854_3.pdf', '', 0),
(89, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00251', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-08 00:00:17', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241108000017_4.pdf', '', 0),
(90, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00252', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-08 00:06:08', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241108000608_5.pdf', '', 0),
(91, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00253', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-11-08 00:14:49', '0000-00-00 00:00:00', 'agree', 'wallet', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241108001450_6.pdf', '', 0),
(92, 'APP-00001', 'AGN-00005', '', 'MEM-00146', 0, 'REQ-00254', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 0, '2024-11-08 00:15:17', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00146/invoice_20241108001517_7.pdf', '', 0),
(93, 'APP-00001', 'AGN-00005', '', 'MEM-00145', 0, 'REQ-00255', 20.00, 1.80, 1.80, 23.60, '', 'payment_success', 1, 1, '2024-11-08 00:16:25', '0000-00-00 00:00:00', 'agree', 'online', '', '', 'https://mounarchtech.com/vocoxp/active_folder/agency/invoice/verifications/MEM-00145/invoice_20241108001625_1.pdf', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `version_control_details_all`
--

CREATE TABLE `version_control_details_all` (
  `id` int(10) NOT NULL,
  `version_id` varchar(15) NOT NULL COMMENT 'for internal use',
  `main_version_id` varchar(100) NOT NULL COMMENT 'if it is blank means it is main version data, else it is sub version data. \r\nIt''s the main version id will insert here. \r\neg: \r\nif\r\nMain version = 3.0, \r\nversion_id = ver_00001 \r\nmain_version_id = ''''\r\nthen\r\nsub version = 3.0.1\r\nversion_id = ver_00002 \r\nmain_version_id = ver_00001 \r\n',
  `version_code` varchar(100) NOT NULL,
  `version_name` varchar(50) NOT NULL COMMENT 'name of version (vocoXP 3.0.1)',
  `uploaded_on_date` datetime NOT NULL COMMENT 'date when this version enters',
  `mandatory_from_date` datetime NOT NULL COMMENT 'date from which this update is mendetory',
  `remind_user_from_date` datetime NOT NULL COMMENT 'date when to remind users about this update',
  `current_user_count` int(11) NOT NULL COMMENT 'total no of app downloads till date',
  `status` int(11) NOT NULL COMMENT '0 means active 1 means passed 2 means upcomming',
  `operating_system` int(11) NOT NULL COMMENT '0 means android 1 means IOS',
  `version_type` int(11) NOT NULL COMMENT '0 means minor 1 means major',
  `required` int(11) NOT NULL COMMENT '0 means optional 1 means compulsory',
  `playstore_updated_on` int(11) NOT NULL,
  `data_migration_on` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voter_transaction_all`
--

CREATE TABLE `voter_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `branch_id` varchar(25) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` varchar(100) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `type_id` varchar(100) NOT NULL DEFAULT '0.00',
  `price` int(100) NOT NULL,
  `verification_status` int(10) NOT NULL COMMENT '1=Active, 0=Pending, 2=Fail',
  `verification_report` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `is_refund` int(10) NOT NULL COMMENT '1=Yes/ 0=No',
  `date_of_refund` datetime NOT NULL,
  `sgst_percentage` int(11) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(11) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `web_link_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voter_transaction_all`
--

INSERT INTO `voter_transaction_all` (`id`, `agency_id`, `branch_id`, `request_id`, `application_id`, `person_id`, `request_for`, `verification_id`, `type_id`, `price`, `verification_status`, `verification_report`, `created_on`, `modified_on`, `is_refund`, `date_of_refund`, `sgst_percentage`, `sgst_amount`, `cgst_percentage`, `cgst_amount`, `web_link_number`) VALUES
(1, 'AGN-00005', '', 'REQ-00171', 'APP-00001', 'MEM-00037', '', 'DVF-00005', '', 20, 0, '', '2024-07-03 11:30:34', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(2, 'AGN-00005', '', 'REQ-00173', 'APP-00001', 'MEM-00045', '', 'DVF-00005', '', 20, 0, '', '2024-07-05 14:57:21', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(3, 'AGN-00005', '', 'REQ-00178', 'APP-00001', 'MEM-00038', '', 'DVF-00005', '', 20, 0, '', '2024-07-06 11:07:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(4, 'AGN-00005', '', 'REQ-00180', 'APP-00001', 'MEM-00043', '', 'DVF-00005', '', 20, 0, '', '2024-07-09 12:16:33', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(5, 'AGN-00016', '', 'REQ-00185', 'APP-00001', 'MEM-00085', '', 'DVF-00005', '', 20, 0, '', '2024-07-16 16:49:19', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(6, 'AGN-00005', '', 'REQ-00188', 'APP-00001', 'MEM-00088', '', 'DVF-00005', '', 20, 0, '', '2024-07-19 16:52:46', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(7, 'AGN-00001', '', 'REQ-00192', 'APP-00001', 'MEM-00046', '', 'DVF-00005', '', 20, 0, '', '2024-07-22 10:53:47', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(8, 'AGN-00005', '', 'REQ-00196', 'APP-00001', 'MEM-00100', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00100/voter20240805174006.pdf', '2024-07-22 13:08:26', '2024-08-05 17:40:06', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(9, 'AGN-00005', '', 'REQ-00202', 'APP-00001', 'MEM-00101', '', 'DVF-00005', '', 20, 0, '', '2024-07-24 11:22:17', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(10, 'AGN-00005', '', 'REQ-00203', 'APP-00001', 'MEM-00104', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00104/voter20240806062524.pdf', '2024-07-24 11:23:54', '2024-08-06 06:25:23', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(11, 'AGN-00002', '', 'REQ-00206', 'APP-00001', 'MEM-00103', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00002/MEM-00103/voter20240726124943.pdf', '2024-07-26 12:42:27', '2024-07-26 12:49:42', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(12, 'AGN-00062', '', 'REQ-00208', 'APP-00001', 'MEM-00111', '', 'DVF-00005', '', 20, 0, '', '2024-08-02 22:13:48', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(13, 'AGN-00002', '', 'REQ-00210', 'APP-00001', 'MEM-00103', '', 'DVF-00005', '', 20, 0, '', '2024-08-05 18:07:35', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(14, 'AGN-00076', '', 'REQ-00211', 'APP-00001', 'MEM-00112', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00076/MEM-00112/voter20240806125649.pdf', '2024-08-05 18:09:47', '2024-08-06 12:56:49', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(15, 'AGN-00005', '', 'REQ-00215', 'APP-00001', 'MEM-00104', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00104/voter20240806063454.pdf', '2024-08-06 06:28:02', '2024-08-06 06:34:54', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(16, 'AGN-00005', '', 'REQ-00216', 'APP-00001', 'MEM-00113', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00113/voter20240806124206.pdf', '2024-08-06 12:37:03', '2024-08-06 12:42:06', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(17, 'AGN-00076', '', 'REQ-00218', 'APP-00001', 'MEM-00115', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00076/MEM-00115/voter20240806131005.pdf', '2024-08-06 13:03:57', '2024-08-06 13:10:05', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(18, 'AGN-00005', '', 'REQ-00225', 'APP-00001', 'MEM-00130', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00130/voter20240808174028.pdf', '2024-08-08 17:34:02', '2024-08-08 17:40:28', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(19, 'AGN-00005', '', 'REQ-00233', 'APP-00001', 'MEM-00113', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00113/voter20240903113614.pdf', '2024-09-03 11:33:47', '2024-09-03 11:36:13', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(20, 'AGN-00005', '', 'REQ-00246', 'APP-00001', 'MEM-00113', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00113/voter20241030053816.pdf', '2024-10-26 09:09:11', '2024-10-30 05:38:16', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(21, 'AGN-00005', '', 'REQ-00247', 'APP-00001', 'MEM-00113', '', 'DVF-00005', '', 20, 0, '', '2024-10-30 05:39:13', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, ''),
(22, 'AGN-00005', '', 'REQ-00252', 'APP-00001', 'MEM-00146', '', 'DVF-00005', '', 20, 2, 'https://mounarchtech.com/central_wp/verification_data/voter/voco_xp/AGN-00005/MEM-00146/voter20241108000922.pdf', '2024-11-08 00:06:08', '2024-11-08 00:09:22', 0, '0000-00-00 00:00:00', 9, 1.80, 9, 1.80, '');

-- --------------------------------------------------------

--
-- Table structure for table `web_user_header_all`
--

CREATE TABLE `web_user_header_all` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(100) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `mobile_no1` varchar(50) NOT NULL,
  `mobile_no2` varchar(50) NOT NULL,
  `role_id` longtext NOT NULL,
  `serviciable_application` longtext NOT NULL,
  `serviceable_city` longtext NOT NULL,
  `pin` int(11) NOT NULL,
  `employee_code` int(11) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `profile_picture` varchar(500) NOT NULL,
  `status` int(20) NOT NULL COMMENT '1=Active/0=Inactive',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `web_user_header_all`
--

INSERT INTO `web_user_header_all` (`id`, `employee_id`, `employee_name`, `date_of_birth`, `mobile_no1`, `mobile_no2`, `role_id`, `serviciable_application`, `serviceable_city`, `pin`, `employee_code`, `email_id`, `profile_picture`, `status`, `created_on`, `modified_on`) VALUES
(1, 'EMP-00001', 'Ayush Saxsena', '2013-09-12', '9820898379-v', '9820898379-v', 'superadmin', 'all', 'all', 7777, 1, 'admin@miscos.in', '', 1, '2023-12-30 06:05:23', '2023-12-30 06:05:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aadhar_transaction_all`
--
ALTER TABLE `aadhar_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_category_product_combination_all`
--
ALTER TABLE `application_category_product_combination_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_header_all`
--
ALTER TABLE `application_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_help_videos`
--
ALTER TABLE `application_help_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_storage_plan_all`
--
ALTER TABLE `application_storage_plan_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_header_all`
--
ALTER TABLE `category_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier_charges_details`
--
ALTER TABLE `courier_charges_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driving_license_transaction_all`
--
ALTER TABLE `driving_license_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ecrime_transaction_all`
--
ALTER TABLE `ecrime_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faulty_items_details_all`
--
ALTER TABLE `faulty_items_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_diagnostic_report_header_all`
--
ALTER TABLE `item_diagnostic_report_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_master_all`
--
ALTER TABLE `item_master_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_qa_transaction_all`
--
ALTER TABLE `item_qa_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oem_header_all`
--
ALTER TABLE `oem_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_header_all`
--
ALTER TABLE `order_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_life_cycle_all`
--
ALTER TABLE `order_life_cycle_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_post_dipatch_cancelled_all`
--
ALTER TABLE `order_post_dipatch_cancelled_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_pre_dipatch_cancelled_all`
--
ALTER TABLE `order_pre_dipatch_cancelled_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pan_transaction_all`
--
ALTER TABLE `pan_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passport_transaction_all`
--
ALTER TABLE `passport_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_header_all`
--
ALTER TABLE `product_header_all`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_item_archive_all`
--
ALTER TABLE `product_item_archive_all`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_item_header_all`
--
ALTER TABLE `product_item_header_all`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `profile_header_all`
--
ALTER TABLE `profile_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_intransit_details_all`
--
ALTER TABLE `return_intransit_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_inside_table_all`
--
ALTER TABLE `table_inside_table_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unique_id_header_all`
--
ALTER TABLE `unique_id_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_configuration_all`
--
ALTER TABLE `verification_configuration_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_header_all`
--
ALTER TABLE `verification_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_payment_archive_all`
--
ALTER TABLE `verification_payment_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_payment_transaction_all`
--
ALTER TABLE `verification_payment_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `version_control_details_all`
--
ALTER TABLE `version_control_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voter_transaction_all`
--
ALTER TABLE `voter_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_user_header_all`
--
ALTER TABLE `web_user_header_all`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aadhar_transaction_all`
--
ALTER TABLE `aadhar_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `application_category_product_combination_all`
--
ALTER TABLE `application_category_product_combination_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_header_all`
--
ALTER TABLE `application_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `application_help_videos`
--
ALTER TABLE `application_help_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_storage_plan_all`
--
ALTER TABLE `application_storage_plan_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category_header_all`
--
ALTER TABLE `category_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courier_charges_details`
--
ALTER TABLE `courier_charges_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driving_license_transaction_all`
--
ALTER TABLE `driving_license_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ecrime_transaction_all`
--
ALTER TABLE `ecrime_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `faulty_items_details_all`
--
ALTER TABLE `faulty_items_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_diagnostic_report_header_all`
--
ALTER TABLE `item_diagnostic_report_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `item_master_all`
--
ALTER TABLE `item_master_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `item_qa_transaction_all`
--
ALTER TABLE `item_qa_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oem_header_all`
--
ALTER TABLE `oem_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_header_all`
--
ALTER TABLE `order_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=480;

--
-- AUTO_INCREMENT for table `order_life_cycle_all`
--
ALTER TABLE `order_life_cycle_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `order_post_dipatch_cancelled_all`
--
ALTER TABLE `order_post_dipatch_cancelled_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_pre_dipatch_cancelled_all`
--
ALTER TABLE `order_pre_dipatch_cancelled_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pan_transaction_all`
--
ALTER TABLE `pan_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `passport_transaction_all`
--
ALTER TABLE `passport_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_header_all`
--
ALTER TABLE `product_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_item_archive_all`
--
ALTER TABLE `product_item_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_item_header_all`
--
ALTER TABLE `product_item_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `profile_header_all`
--
ALTER TABLE `profile_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `return_intransit_details_all`
--
ALTER TABLE `return_intransit_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unique_id_header_all`
--
ALTER TABLE `unique_id_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `verification_configuration_all`
--
ALTER TABLE `verification_configuration_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `verification_header_all`
--
ALTER TABLE `verification_header_all`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `verification_payment_archive_all`
--
ALTER TABLE `verification_payment_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_payment_transaction_all`
--
ALTER TABLE `verification_payment_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `version_control_details_all`
--
ALTER TABLE `version_control_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voter_transaction_all`
--
ALTER TABLE `voter_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `web_user_header_all`
--
ALTER TABLE `web_user_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
