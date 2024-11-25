-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 25, 2024 at 04:44 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mounac53_vocoxp3.0`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_header_all`
--

CREATE TABLE `admin_header_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `admin_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mobile_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `linked_profile` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv format',
  `PRO_0001` date NOT NULL COMMENT 'fromdate',
  `PRO_0002` date NOT NULL COMMENT 'fromdate',
  `PRO_0003` date NOT NULL COMMENT 'fromdate',
  `PRO_0004` date NOT NULL COMMENT 'fromdate',
  `PRO_0005` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV hotelid@fromdate, hotelid2@fromdate',
  `PRO_0006` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'siteid@fromdate~todate, siteid2@fromdate~todate,etc',
  `PRO_0007` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'campusid@fromdate~todate, campusid2@fromdate~todate,etc',
  `old_linked_profile` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(csv format)profile_id1>(fromdate@todate),profile_id2>(hotel_id=fromdate@todate),etc',
  `created_on` datetime NOT NULL,
  `otp` int(11) NOT NULL,
  `otp_datetime` datetime NOT NULL,
  `status` int(20) NOT NULL COMMENT '1=active/0=deactive',
  `deactivated_on` datetime NOT NULL,
  `permitted_dwnlds` int(11) NOT NULL COMMENT 'how much downloads is permitted to admin',
  `current_dwnlds` int(11) NOT NULL COMMENT 'how much is currently downloaded',
  `permitted_emails` int(11) NOT NULL,
  `current_no_of_emails` int(11) NOT NULL COMMENT 'how much emails is used'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin_header_all`
--

INSERT INTO `admin_header_all` (`id`, `agency_id`, `admin_id`, `admin_name`, `mobile_no`, `email_id`, `linked_profile`, `PRO_0001`, `PRO_0002`, `PRO_0003`, `PRO_0004`, `PRO_0005`, `PRO_0006`, `PRO_0007`, `old_linked_profile`, `created_on`, `otp`, `otp_datetime`, `status`, `deactivated_on`, `permitted_dwnlds`, `current_dwnlds`, `permitted_emails`, `current_no_of_emails`) VALUES
(1, 'AGN-00001', 'ADM-00001', 'Ashish Khandare', '7777777777', 'ashish.and.miscos@gmail.com', 'PRO_0010', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '', '', '', '2024-11-15 16:09:29', 0, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, 0, 0, 0),
(2, 'AGN-00001', 'ADM-00002', 'Ashish Khandare', '9922352834', 'ashish.and.miscos@gmail.com', 'PRO_0001,PRO_0002,PRO_0003,PRO_0004,PRO_0005,PRO_0006,PRO_0007,PRO_0008,PRO_0009,PRO_0010', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '', '', '', '2024-11-17 15:08:06', 0, '0000-00-00 00:00:00', 0, '2024-11-19 05:19:38', 50, 0, 69, 0),
(3, 'AGN-00004', 'ADM-00003', 'Kumar Singh', '9922352834', 'ashish.and.miscos@gmail.com', 'PRO_0003', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '', '', '', '2024-11-18 00:00:23', 0, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, 0, 0, 0),
(4, 'AGN-00001', 'ADM-00004', 'Abhijeet Sutar', '8766481053', 'akashbhive093@gmail.com', 'PRO_0003,PRO_0002', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '', '', '', '2024-11-19 15:24:41', 0, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `agency_amount_collection`
--

CREATE TABLE `agency_amount_collection` (
  `id` int(11) NOT NULL,
  `collection_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `collected_from` int(11) NOT NULL COMMENT '1=weblink, 2=visitor',
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `reference_detail` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'visitor_id=v_transaction_id, weblink_id=end_user_id',
  `amount` float NOT NULL COMMENT 'excluding gst',
  `disbursed_to_agency` int(11) NOT NULL DEFAULT '0' COMMENT '1=yes, 0=no',
  `date_of_disbursement` datetime NOT NULL,
  `mode` int(11) NOT NULL COMMENT '1= cheque, 2= online',
  `inserted_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agency_cart_header_all`
--

CREATE TABLE `agency_cart_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `product_id` longtext NOT NULL COMMENT 'CSV of product id (productId_1-quntity,...)',
  `status` int(30) NOT NULL COMMENT '1=active/0=deactive',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agency_groups_header_all`
--

CREATE TABLE `agency_groups_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_members` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CSV of (member_ids=datetime)',
  `old_group_member_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'in csv format (member_id=from_date@todate)',
  `status` int(10) NOT NULL COMMENT '1=active 0=deactive',
  `created_on` datetime NOT NULL,
  `created_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'if single user then mobile_no or multi user employee)',
  `suspended_by` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suspended_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agency_header_all`
--

CREATE TABLE `agency_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(20) NOT NULL COMMENT 'individual/organization',
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL COMMENT 'organization_name',
  `business_type` varchar(50) NOT NULL COMMENT 'functional area',
  `city` varchar(100) NOT NULL,
  `address` varchar(250) CHARACTER SET utf16 NOT NULL,
  `profession` varchar(50) NOT NULL,
  `no_of_employees` varchar(100) NOT NULL,
  `gps_location` varchar(25) NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `created_on` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `type` int(10) NOT NULL COMMENT '1= organization, 0=individual',
  `login_pin` int(4) NOT NULL,
  `password` varchar(15) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `login_status` varchar(5) NOT NULL,
  `device_os` varchar(20) NOT NULL,
  `device_version` varchar(20) NOT NULL,
  `token_id` longtext NOT NULL COMMENT 'parent_token_id=child_token_id_1,child_token_id_2',
  `30_storage_notification` varchar(500) NOT NULL,
  `30_storage_notification_date` datetime NOT NULL,
  `20_storage_notification` varchar(500) NOT NULL,
  `20_storage_notification_date` datetime NOT NULL,
  `total_storage` double NOT NULL,
  `available_storage` double NOT NULL,
  `archieve_storage` double NOT NULL,
  `used_storage` double NOT NULL,
  `current_wallet_bal` double NOT NULL,
  `tentative_amount` float NOT NULL COMMENT 'total estimated Amount for active weblink.',
  `coupan_add_on_amount` float NOT NULL,
  `employee_type` int(10) NOT NULL COMMENT '1=owner\r\n0=working_as\r\nthe value will be inserted when registering as organization and the value will be owner / working_with_owner\r\n\r\n',
  `employee_designation` varchar(100) NOT NULL COMMENT 'value will insert when register as orgnization ',
  `work_type` int(10) NOT NULL COMMENT 'professional_working = 1\r\nstudent = 0\r\nvalue will insert when register as individual and value will be student/ professional_working',
  `owning_company` int(10) NOT NULL DEFAULT '0' COMMENT 'value will insert when register as individual and value will Y/N\r\nY=1, N=0',
  `is_get_benifit_of_offer` int(10) NOT NULL COMMENT 'Y=1/ N=0',
  `agency_gst_no` varchar(16) NOT NULL,
  `last_direct_report_generate_on` date NOT NULL,
  `direct_invoice` int(11) NOT NULL COMMENT '1= once in 24hrs, 2= per verification',
  `in_app_invoice` int(11) NOT NULL COMMENT '1= once in 24hrs, 2= per verification',
  `web_link_invoice` int(11) NOT NULL COMMENT '1= once in 24hrs, 2= per verification',
  `direct_report_generate` int(11) NOT NULL COMMENT '1= as and when requested, 2= per verification',
  `in_app_report_generation` int(11) NOT NULL COMMENT '1= once in 24hrs, 2= per verification',
  `web_link_report_generation` int(11) NOT NULL COMMENT '1= once in 24hrs, 2= per verification',
  `agency_logo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agency_header_all`
--

INSERT INTO `agency_header_all` (`id`, `agency_id`, `name`, `mobile_no`, `email_id`, `company_name`, `business_type`, `city`, `address`, `profession`, `no_of_employees`, `gps_location`, `status`, `created_on`, `updated`, `type`, `login_pin`, `password`, `device_id`, `login_status`, `device_os`, `device_version`, `token_id`, `30_storage_notification`, `30_storage_notification_date`, `20_storage_notification`, `20_storage_notification_date`, `total_storage`, `available_storage`, `archieve_storage`, `used_storage`, `current_wallet_bal`, `tentative_amount`, `coupan_add_on_amount`, `employee_type`, `employee_designation`, `work_type`, `owning_company`, `is_get_benifit_of_offer`, `agency_gst_no`, `last_direct_report_generate_on`, `direct_invoice`, `in_app_invoice`, `web_link_invoice`, `direct_report_generate`, `in_app_report_generation`, `web_link_report_generation`, `agency_logo`) VALUES
(1, 'AGN-00001', 'Ashish Khandare ', '9011420559', 'ashish.and.miscos@gmail.com', 'MTSS Pvt Ltd', 'IT Services', 'Pune', ' Cybernex IT Park, Swargate ', '', 'Between 21 to 100', '', 1, '2024-11-14 15:34:21', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, 256.6, 0, 0, 1, '', 0, 0, 0, '23IOBPK5360E1Z5', '0000-00-00', 2, 2, 2, 1, 1, 1, 'https://mounarchtech.com/vocoxp/uploads/20241116_135027.png'),
(2, 'AGN-00002', 'Abhijit sutar', '8007376521', 'abhijitsutar9458@gmail.com', 'Mttss', 'IT Services', 'pune', ' pune', '', 'Between 5 to 20', '', 1, '2024-11-16 10:18:27', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, 1976.4, 0, 0, 1, '', 0, 0, 0, '', '0000-00-00', 0, 0, 0, 0, 0, 0, 'https://mounarchtech.com/vocoxp/uploads/20241118_150517.png'),
(3, 'AGN-00003', 'Namrata Sharma', '9820898379', 'namrata.r.shrivas@gmail.com', 'Zkazooooo Pvt ltd ', 'IT Services', 'Vashi', ' Vashi', '', 'Between 21 to 100', '', 1, '2024-11-16 16:42:04', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, -2.36, 2.36, 0, 1, '', 0, 0, 0, '', '0000-00-00', 0, 0, 0, 0, 0, 0, 'https://mounarchtech.com/vocoxp/uploads/20241118_150517.png'),
(4, 'AGN-00004', 'Akash Mishra ', '9405287798', 'ashish.and.miscos@gmail.com', 'MI Invertor Pvt Ltd ', 'Education', 'Pune', ' FC Road, Shivaji Nagar ', '', 'More than 100', '', 1, '2024-11-17 23:58:34', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, 2000, 0, 0, 1, '', 0, 0, 0, '27IOBPK5260E1Z5', '0000-00-00', 0, 0, 0, 0, 0, 0, ''),
(5, 'AGN-00005', 'Khayum', '8850887983', 'mujawarkhayum786@gmail.com', 'Test', 'Others', 'Latur ', ' Latur', '', 'Less than 5', '', 1, '2024-11-19 12:47:04', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 470.82, 0, 1, '', 0, 0, 0, '', '0000-00-00', 0, 0, 0, 0, 0, 0, ''),
(6, 'AGN-00006', 'Sanket', '7798491744', 'jadhavsanket798@gmail.com', 'MTSS', 'IT Services', 'Pune', ' Swargate', '', 'Between 5 to 20', '', 1, '2024-11-22 17:52:56', '0000-00-00 00:00:00', 1, 0, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, 0, 0, 1, '', 0, 0, 0, '', '0000-00-00', 0, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `agency_setting_all`
--

CREATE TABLE `agency_setting_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `add_family_member` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `app_setting` int(10) NOT NULL COMMENT '1=ON/0=OFF',
  `is_univarsal` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `geo_fancing_margin` int(11) DEFAULT NULL COMMENT 'if yes then insert distance ',
  `geo_location_auto_update` int(11) DEFAULT NULL COMMENT 'if yes then insert value in minute',
  `manual_recording` int(11) DEFAULT NULL COMMENT 'if yes then insert value in minute',
  `e_crime_reminder` int(11) DEFAULT NULL COMMENT 'if yes then insert value in days',
  `sos` int(10) NOT NULL COMMENT '1=ON/0=OFF',
  `created_on` datetime(6) NOT NULL,
  `modified_on` datetime NOT NULL,
  `watch_reset_pin` int(5) NOT NULL,
  `mark_watch_stolen` varchar(20) NOT NULL,
  `fsn_mark_watch_stolen` varchar(20) NOT NULL COMMENT 'full screen notification',
  `watch_stolen_audio_file` longtext NOT NULL COMMENT 'sample audion file which will be plaed on watch when watch marked as stolen',
  `watch_stolen_msg` varchar(100) NOT NULL COMMENT 'common msg',
  `holidays` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `alert_when_watch_remove` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `alert_on_watch_remove_from_wrist` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `alrert_on_sim_remove` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `fsn_for_sim_remove` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `alert_on_watch_to_reconnect_server` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `pre_schedule_alert` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `alert_when_watch_out_from_gps_range` varchar(50) NOT NULL COMMENT 'N or range',
  `manual_recording_notification` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `heart_rate` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `spo2` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `body_temp` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `bluetooth` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `carrier` int(10) NOT NULL COMMENT '1=Y/0=N0',
  `email_for_direct` varchar(100) NOT NULL,
  `email_for_inapp` varchar(100) NOT NULL,
  `email_for_weblink` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `agency_setting_all`
--

INSERT INTO `agency_setting_all` (`id`, `agency_id`, `add_family_member`, `app_setting`, `is_univarsal`, `geo_fancing_margin`, `geo_location_auto_update`, `manual_recording`, `e_crime_reminder`, `sos`, `created_on`, `modified_on`, `watch_reset_pin`, `mark_watch_stolen`, `fsn_mark_watch_stolen`, `watch_stolen_audio_file`, `watch_stolen_msg`, `holidays`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `pre_schedule_alert`, `alert_when_watch_out_from_gps_range`, `manual_recording_notification`, `heart_rate`, `spo2`, `body_temp`, `bluetooth`, `carrier`, `email_for_direct`, `email_for_inapp`, `email_for_weblink`) VALUES
(1, 'AGN-00001', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '2024-11-16 12:25:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', ''),
(2, 'AGN-00002', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', ''),
(3, 'AGN-00003', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', ''),
(4, 'AGN-00004', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', ''),
(5, 'AGN-00005', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', ''),
(6, 'AGN-00006', 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00', 0, '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, '', '', '', 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `agency_storage_transaction_all`
--

CREATE TABLE `agency_storage_transaction_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `storage_order_id` varchar(50) NOT NULL,
  `storage_plan` varchar(100) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `order_status` int(10) NOT NULL COMMENT '1=success, 0=pending',
  `transaction_id` varchar(60) NOT NULL,
  `payment_status` int(10) NOT NULL COMMENT '1=payment_success, 0=payment_pending, 2=payment_fail',
  `payment_mode` int(10) NOT NULL COMMENT '1=online/0=wallet',
  `created_on` datetime NOT NULL,
  `total_amount_paid` int(100) NOT NULL,
  `sgst_percentage` int(11) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_percentage` int(11) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agency_visitor_location_header_all`
--

CREATE TABLE `agency_visitor_location_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_location_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `location_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `operational_from` date NOT NULL,
  `location_admins` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv',
  `location_state` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `location_city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `location_pincode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `location_radius` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `location_coordinates` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inserted_on` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 - active, 2- close, 3- suspended',
  `close_from` date NOT NULL,
  `close_reason` longtext COLLATE utf8_unicode_ci NOT NULL,
  `avs_status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '1-on, 2-off',
  `printer_status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '1-on, 2-off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `agency_visitor_location_header_all`
--

INSERT INTO `agency_visitor_location_header_all` (`id`, `agency_id`, `visitor_location_id`, `location_name`, `operational_from`, `location_admins`, `location_state`, `location_city`, `location_pincode`, `location_radius`, `location_coordinates`, `inserted_on`, `status`, `close_from`, `close_reason`, `avs_status`, `printer_status`) VALUES
(1, 'AGN-00001', 'VIS-00001', 'Cybernex Gate1', '2024-11-15', 'ADM-00001', 'Maharashtra', 'Pune', '411042', '500', '18.49996640027956@73.86209182441235', '0000-00-00 00:00:00', '1', '0000-00-00', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `alert_archive_all`
--

CREATE TABLE `alert_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `alert_id` varchar(100) NOT NULL,
  `name` longtext NOT NULL,
  `type` int(10) NOT NULL COMMENT '1=text/2=audio/3=both',
  `message` longtext NOT NULL,
  `audio_file_path` varchar(500) NOT NULL,
  `delivery_type` int(10) NOT NULL COMMENT '1=autoplay/0=tap_on_play',
  `frequency` int(10) NOT NULL COMMENT '1=once/0=repeat',
  `repeat_count` int(11) NOT NULL DEFAULT '1',
  `is_vibration_on` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `text_visibility_till` int(11) NOT NULL DEFAULT '10' COMMENT 'in sec',
  `days` varchar(5000) NOT NULL COMMENT 'in CSV format',
  `alert_start_date` date NOT NULL,
  `alert_time` time NOT NULL,
  `notify_me` varchar(5000) NOT NULL COMMENT 'in CSV format',
  `status` int(10) NOT NULL COMMENT '1=Active/0=inactive',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `alert_header_all`
--

CREATE TABLE `alert_header_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `alert_id` varchar(100) NOT NULL,
  `name` longtext NOT NULL,
  `type` int(10) NOT NULL COMMENT '1=text/2=audio/3=both',
  `message` longtext NOT NULL,
  `audio_file_path` varchar(500) NOT NULL,
  `delivery_type` int(10) NOT NULL COMMENT '1=autoplay/0=tap_on_play',
  `frequency` int(10) NOT NULL COMMENT '1=once/0=repeat',
  `repeat_count` int(11) NOT NULL DEFAULT '1',
  `is_vibration_on` int(10) NOT NULL DEFAULT '0' COMMENT '1=Yes/0=No',
  `text_visibility_till` int(11) NOT NULL DEFAULT '10' COMMENT 'in sec',
  `days` varchar(5000) NOT NULL COMMENT 'in CSV format',
  `alert_start_date` date NOT NULL,
  `alert_time` time NOT NULL,
  `notify_me` varchar(5000) NOT NULL COMMENT 'in CSV format',
  `status` int(10) NOT NULL COMMENT '1=Active/0=inactive',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `alert_transaction_all`
--

CREATE TABLE `alert_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(25) NOT NULL,
  `alert_transaction_id` varchar(100) NOT NULL,
  `alert_id` varchar(100) NOT NULL,
  `alert_type` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `watch_status` int(10) NOT NULL COMMENT '1=weared/0=not_weared',
  `report_status` int(10) NOT NULL COMMENT '1=deliver/0=not_deliver',
  `location` varchar(100) NOT NULL COMMENT 'lattitude,longitude'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `alert_transaction_archive_all`
--

CREATE TABLE `alert_transaction_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(25) NOT NULL,
  `alert_transaction_id` varchar(100) NOT NULL,
  `alert_id` varchar(100) NOT NULL,
  `alert_type` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `watch_status` int(10) NOT NULL COMMENT '1=weared/0=not_weared',
  `report_status` int(10) NOT NULL COMMENT '1=deliver/0=not_deliver',
  `location` varchar(100) NOT NULL COMMENT 'lattitude,longitude'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_user_token_details_all`
--

CREATE TABLE `app_user_token_details_all` (
  `id` int(10) NOT NULL,
  `reg_mobile_no` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `current_version` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `linked_agency_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv format',
  `app_device_token` longtext COLLATE utf8_unicode_ci NOT NULL,
  `operating_system` int(1) NOT NULL COMMENT 'android=1, ios=2',
  `os_version` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `login_pin` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `last_logged_in_on` datetime NOT NULL,
  `registered_on` datetime NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `deactivated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `app_user_token_details_all`
--

INSERT INTO `app_user_token_details_all` (`id`, `reg_mobile_no`, `current_version`, `linked_agency_id`, `app_device_token`, `operating_system`, `os_version`, `login_pin`, `last_logged_in_on`, `registered_on`, `status`, `deactivated_on`) VALUES
(1, '9011420559', '2.0.6', 'AGN-00001', 'tQKYDi6AyF,fe0aa7eb1390ec49', 1, '33', '1111', '0000-00-00 00:00:00', '2024-11-14 15:34:21', 1, '0000-00-00 00:00:00'),
(3, '8007376521', '2.0.6', 'AGN-00002', 'lcEw0JR8IW,741ba451100b5558', 1, '31', '1111', '0000-00-00 00:00:00', '2024-11-16 10:18:27', 1, '0000-00-00 00:00:00'),
(4, '9820898379', '2.0.6', 'AGN-00003', 'c8wUKuRoqY,bd2ffbce529241af', 1, '34', '1111', '0000-00-00 00:00:00', '2024-11-16 16:42:04', 1, '0000-00-00 00:00:00'),
(5, '9922352834', '2.0.6', 'AGN-00001,AGN-00004', 'LpNBlHxj5N,598600474238e8d2', 1, '28', '1111', '0000-00-00 00:00:00', '2024-11-17 15:08:06', 1, '0000-00-00 00:00:00'),
(6, '9405287798', '2.0.6', 'AGN-00004', 'd0OttvVH4U,fe0aa7eb1390ec49', 1, '33', '1111', '0000-00-00 00:00:00', '2024-11-17 23:58:34', 1, '0000-00-00 00:00:00'),
(7, '8850887983', '2.0.6', 'AGN-00005', '7baRl6cIpm,eef9a0fb29e1eb9d', 1, '33', '1234', '0000-00-00 00:00:00', '2024-11-19 12:47:04', 1, '0000-00-00 00:00:00'),
(8, '8766481053', '', 'AGN-00001', '', 0, '', '', '0000-00-00 00:00:00', '2024-11-19 15:24:41', 1, '0000-00-00 00:00:00'),
(9, '7798491744', '2.0.6', 'AGN-00006', 'ByiD5k9QeT,509f867576f4b3c5', 1, '28', '1111', '0000-00-00 00:00:00', '2024-11-22 17:52:56', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `battery_status_header_all`
--

CREATE TABLE `battery_status_header_all` (
  `id` int(11) NOT NULL,
  `battery_status_id` varchar(100) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `item_no` varchar(100) NOT NULL,
  `turn_off_type` int(10) NOT NULL COMMENT '1=auto/0=manual',
  `turn_off_datetime` datetime NOT NULL,
  `turn_off_gps` varchar(100) NOT NULL,
  `turn_off_battery` float NOT NULL,
  `turn_on_datetime` datetime NOT NULL,
  `turn_on_gps` varchar(100) NOT NULL,
  `turn_on_battery` float NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_end_user_transaction_all`
--

CREATE TABLE `bulk_end_user_transaction_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bulk_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `upload_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `end_user_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `excel_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '1034-parent only, 1035-student only, 1036 - parent with student, 1037 - custom ',
  `obj_no` int(10) NOT NULL COMMENT '1 or 2 or 3 ',
  `obj_name` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Father, Mother, gaurdian, child,etc',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if entry is primary then it can be filed(if secondary entry it can be blank)',
  `email_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if entry is primary then it can be filed(if secondary entry it can be blank)',
  `ref_enduser_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV of linked enduser_ids',
  `enroll_no` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Roll_no/enroll_no/employeeID of students\r\nfor only excel_no 1034,1035,1040,1036',
  `sms_sent` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 Yes / 0 No',
  `email_sent` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 Yes / 0 No',
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '0= not initiated, 1 = opened,\r\n2= partial verifications 3= completed',
  `verification_report` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'End_user_id.pdf',
  `verification_details` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV\r\nDVF-00001=date&time>payment_transaction_id(Kindly note if the user paid all\r\nthe verification by transaction id\r\nthen transaction Id will be same\r\nin all the verifications)',
  `scheduled_verifications` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001, DVF-00002, DVF-00004, DVF-00005, DVF-00008',
  `verification_done` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001, DVF-00002, DVF-00004, DVF-00005, DVF-00008',
  `weblink_opened_on` datetime NOT NULL,
  `reminder_email` int(11) NOT NULL COMMENT '1= daily 2= every alternate daty 0= No reminder(If value is 0 then except the welcome email no further reminder email will be shoot to the end user)',
  `reminder_sms` int(11) NOT NULL COMMENT '1= daily 2= every alternate daty 0= No reminder(If value is 0 then except the welcome email no further reminder email will be shoot to the end user)',
  `payment_from` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '1=wallet , 2= by the end user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bulk_end_user_transaction_all`
--

INSERT INTO `bulk_end_user_transaction_all` (`id`, `agency_id`, `bulk_id`, `upload_id`, `end_user_id`, `excel_no`, `obj_no`, `obj_name`, `name`, `mobile`, `email_id`, `ref_enduser_id`, `enroll_no`, `sms_sent`, `email_sent`, `status`, `verification_report`, `verification_details`, `scheduled_verifications`, `verification_done`, `weblink_opened_on`, `reminder_email`, `reminder_sms`, `payment_from`) VALUES
(1, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00655', '1034', 1, 'Student', 'Chintu', '', '', 'END-00656', '55', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(2, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00656', '1034', 2, 'Father', 'Shyaam', '9853167420', 'shyaam@gmail.com', 'END-00655', '', '', '', '3', '', '', 'DVF-00002', 'DVF-00002', '0000-00-00 00:00:00', 1, 1, '2'),
(3, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00657', '1034', 1, 'Student', 'Salman', '', '', 'END-00658', '63', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(4, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00658', '1034', 2, 'Father', 'Khalim', '9896574123', 'khalim@gmail.com', 'END-00657', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(5, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00659', '1034', 1, 'Student', 'Guddu', '', '', 'END-00660', '57', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(6, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00660', '1034', 2, 'Father', 'Shiv', '9536142785', 'shiv@gmail.com', 'END-00659', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(7, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00661', '1034', 1, 'Student', 'Gudiya', '', '', 'END-00662', '58', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(8, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00662', '1034', 2, 'Father', 'Gautam', '9856321472', 'gautam@gmail.com', 'END-00661', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(9, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00663', '1034', 1, 'Student', 'Bhola', '', '', 'END-00664', '59', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(10, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00664', '1034', 2, 'Father', 'Shlokh', '9865742137', 'shlokh@gmail.com', 'END-00663', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(11, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00665', '1034', 1, 'Student', 'Bhuvan', '', '', 'END-00666', '60', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(12, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00666', '1034', 2, 'Father', 'Bam', '9865742314', 'bam@gmail.com', 'END-00665', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(13, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00667', '1034', 1, 'Student', 'Badshah', '', '', 'END-00668', '61', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(14, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00668', '1034', 2, 'Father', 'Daya', '9785463124', 'daya@gmail.com', 'END-00667', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(15, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00669', '1034', 1, 'Student', 'Sharukh', '', '', 'END-00670', '62', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(16, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00670', '1034', 2, 'Father', 'Mohmmad', '9658742315', 'mohmmad@gmail.com', 'END-00669', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(17, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00671', '1034', 1, 'Student', 'Pintu', '', '', 'END-00672', '56', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(18, 'AGN-00003', 'BUL-00003', 'UPL-00038', 'END-00672', '1034', 2, 'Father', 'Shyaam', '9822356589', 'shyaam@gmail.com', 'END-00671', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(19, 'AGN-00005', 'BUL-00006', 'UPL-00039', 'END-00673', '1034', 1, 'Student', 'Hasnain Mujawar', '', '', 'END-00674,END-00675', '1001', '', '', '3', '', '', 'DVF-00001', 'DVF-00001', '0000-00-00 00:00:00', 1, 1, '1'),
(20, 'AGN-00005', 'BUL-00006', 'UPL-00039', 'END-00674', '1034', 2, 'Father', 'Khayum Mujawar', '8009357008', 'mujawarkhayum786@gmail.com', 'END-00673,END-00675', '', '', '', '0', '', '', 'DVF-00001,DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(21, 'AGN-00005', 'BUL-00006', 'UPL-00039', 'END-00675', '1034', 3, 'Mother', 'Farana Mujawar', '8009357008', 'mujawarkhayum786@gmail.com', 'END-00673,END-00674', '', '', '', '1', '', '', 'DVF-00001,DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(22, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00676', '1034', 1, 'Student', 'Abhijit sutar', '', '', 'END-00677,END-00678', '12', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(23, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00677', '1034', 2, 'Father', 'Ashish sutar', '9011420559', 'ashish.and.miscos@gmail.com', 'END-00676,END-00678', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(24, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00678', '1034', 3, 'Mother', 'Pallavi sutar', '8007376521', 'abhijitsutar9458@gmail.com', 'END-00676,END-00677', '', '', '', '0', '', '', 'DVF-00004,DVF-00005', '', '0000-00-00 00:00:00', 1, 1, '2'),
(25, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00679', '1034', 1, 'Student', 'Ashish sutar', '', '', 'END-00680,END-00681', '3', '', '', '3', '', '', 'DVF-00001', 'DVF-00001', '0000-00-00 00:00:00', 1, 1, '2'),
(26, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00680', '1034', 2, 'Father', 'Ashish sutar', '9011420559', 'ashish.and.miscos@gmail.com', 'END-00679,END-00681', '', '', '', '3', '', '', 'DVF-00002', 'DVF-00002', '0000-00-00 00:00:00', 1, 1, '2'),
(27, 'AGN-00002', 'BUL-00007', 'UPL-00040', 'END-00681', '1034', 3, 'Mother', 'Pallavi sutar', '8007376521', 'abhijitsutar9458@gmail.com', 'END-00679,END-00680', '', '', '', '1', '', '', 'DVF-00004,DVF-00005', '', '0000-00-00 00:00:00', 1, 1, '2'),
(28, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00682', '1034', 1, 'Student', 'Abhijit sutar', '', '', 'END-00683,END-00684', '12', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(29, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00683', '1034', 2, 'Father', 'Ashish sutar', '9011420559', 'ashish.and.miscos@gmail.com', 'END-00682,END-00684', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(30, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00684', '1034', 3, 'Mother', 'Pallavi sutar', '8007376521', 'abhijitsutar9458@gmail.com', 'END-00682,END-00683', '', '', '', '0', '', '', 'DVF-00004,DVF-00005', '', '0000-00-00 00:00:00', 1, 1, '2'),
(31, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00685', '1034', 1, 'Student', 'Ashish sutar', '', '', 'END-00686,END-00687', '3', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '2'),
(32, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00686', '1034', 2, 'Father', 'Ashish sutar', '9011420559', 'ashish.and.miscos@gmail.com', 'END-00685,END-00687', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '2'),
(33, 'AGN-00002', 'BUL-00007', 'UPL-00041', 'END-00687', '1034', 3, 'Mother', 'Pallavi sutar', '8007376521', 'abhijitsutar9458@gmail.com', 'END-00685,END-00686', '', '', '', '2', '', '', 'DVF-00004,DVF-00005,DVF-00002', 'DVF-00005,DVF-00002', '0000-00-00 00:00:00', 1, 1, '2'),
(34, 'AGN-00006', 'BUL-00010', 'UPL-00042', 'END-00688', '1036', 1, 'Employees / Teachers', 's j', '7798491744', 'sanketjadhav192@gmail.com', '', '1', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(35, 'AGN-00006', 'BUL-00010', 'UPL-00042', 'END-00689', '1036', 1, 'Employees / Teachers', 'j s', '7798491744', 'jadhavsanket798@gmail.com', '', '2', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(36, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00690', '1034', 1, 'Student', 'Chintu', '', '', 'END-00691,END-00692', '55', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(37, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00691', '1034', 2, 'Father', 'Shyaam', '9853167420', 'shyaam@gmail.com', 'END-00690,END-00692', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(38, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00692', '1034', 3, 'Mother', 'Shyaama', '9865742138', 'shyaama@gmail.com', 'END-00690,END-00691', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(39, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00693', '1034', 1, 'Student', 'Salman', '', '', 'END-00694,END-00695', '63', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(40, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00694', '1034', 2, 'Father', 'Khalim', '9896574123', 'khalim@gmail.com', 'END-00693,END-00695', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(41, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00695', '1034', 3, 'Mother', 'Heena', '9768451236', 'heena@gmail.com', 'END-00693,END-00694', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(42, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00696', '1034', 1, 'Student', 'Guddu', '', '', 'END-00697,END-00698', '57', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(43, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00697', '1034', 2, 'Father', 'Shiv', '9536142785', 'shiv@gmail.com', 'END-00696,END-00698', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(44, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00698', '1034', 3, 'Mother', 'Sundari', '9865321473', 'sundari@gmail.com', 'END-00696,END-00697', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(45, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00699', '1034', 1, 'Student', 'Gudiya', '', '', 'END-00700,END-00701', '58', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(46, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00700', '1034', 2, 'Father', 'Shiv', '9536142785', 'shiv@gmail.com', 'END-00699,END-00701', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(47, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00701', '1034', 3, 'Mother', 'Sundari', '9865321473', 'sundari@gmail.com', 'END-00699,END-00700', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(48, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00702', '1034', 1, 'Student', 'Bhola', '', '', 'END-00703,END-00704', '59', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(49, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00703', '1034', 2, 'Father', 'Shlokh', '9865742137', 'shlokh@gmail.com', 'END-00702,END-00704', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(50, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00704', '1034', 3, 'Mother', 'Neha', '9874512364', 'neha@gmail.com', 'END-00702,END-00703', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(51, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00705', '1034', 1, 'Student', 'Bhuvan', '', '', 'END-00706,END-00707', '60', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(52, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00706', '1034', 2, 'Father', 'Bam', '9865742314', 'bam@gmail.com', 'END-00705,END-00707', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(53, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00707', '1034', 3, 'Mother', 'Snehal', '9856321471', 'snehal@gmail.com', 'END-00705,END-00706', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(54, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00708', '1034', 1, 'Student', 'Badshah', '', '', 'END-00709,END-00710', '61', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(55, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00709', '1034', 2, 'Father', 'Daya', '9785463124', 'daya@gmail.com', 'END-00708,END-00710', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(56, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00710', '1034', 3, 'Mother', 'Khushi', '9685742315', 'khushi@gmail.com', 'END-00708,END-00709', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(57, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00711', '1034', 1, 'Student', 'Sharukh', '', '', 'END-00712,END-00713', '62', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(58, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00712', '1034', 2, 'Father', 'Mohmmad', '9658742315', 'mohmmad@gmail.com', 'END-00711,END-00713', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(59, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00713', '1034', 3, 'Mother', 'Yasmin', '9758463215', 'yasmin@gmail.com', 'END-00711,END-00712', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(60, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00714', '1034', 1, 'Student', 'Pintu', '', '', 'END-00715,END-00716', '56', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(61, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00715', '1034', 2, 'Father', 'Shyaam', '9822356589', 'shyaam@gmail.com', 'END-00714,END-00716', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(62, 'AGN-00003', 'BUL-00011', 'UPL-00043', 'END-00716', '1034', 3, 'Mother', 'Shyaama', '8899886665', 'shyaama@gmail.com', 'END-00714,END-00715', '', '', '', '0', '', '', 'DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(63, 'AGN-00006', 'BUL-00013', 'UPL-00044', 'END-00717', '1035', 1, 'Student', 's j', '7798491744', 'sanketjadhav192@gmail.com', 'END-00718', '1', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(64, 'AGN-00006', 'BUL-00013', 'UPL-00044', 'END-00718', '1035', 2, 'Parent', 'j s', '7798491744', 'jadhavsanket798@gmail.com', 'END-00717', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(65, 'AGN-00006', 'BUL-00013', 'UPL-00045', 'END-00719', '1035', 1, 'Student', 's j', '7798491744', 'sanketjadhav192@gmail.com', 'END-00720', '1', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(66, 'AGN-00006', 'BUL-00013', 'UPL-00045', 'END-00720', '1035', 2, 'Parent', 'j s', '7798491744', 'jadhavsanket798@gmail.com', 'END-00719', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(67, 'AGN-00003', 'BUL-00015', 'UPL-00046', 'END-00721', '1043', 1, 'Resident', 'Samarth', '9898989898', 'nama@gmail.com', 'END-00722', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(68, 'AGN-00003', 'BUL-00015', 'UPL-00046', 'END-00722', '1043', 3, 'Local Guardian', 'Raju', '9820898379', '', 'END-00721', '', '', '', '0', '', '', 'DVF-00008', '', '0000-00-00 00:00:00', 1, 1, '1'),
(69, 'AGN-00003', 'BUL-00015', 'UPL-00046', 'END-00723', '1043', 1, 'Resident', 'Nama', '9898989898', 'namda@gmail.com', 'END-00724', '', '', '', '3', '', '', 'DVF-00001', 'DVF-00001', '0000-00-00 00:00:00', 1, 1, '1'),
(70, 'AGN-00003', 'BUL-00015', 'UPL-00046', 'END-00725', '1043', 1, 'Resident', 'Aadesh', '9898989898', 'nama@gmail.com', 'END-00726', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(71, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00727', '1043', 1, 'Resident', 'Ramesh', '9898989898', 'ramesh@gmail.com', 'END-00728', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(72, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00728', '1043', 3, 'Local Guardian', 'Aadesh', '9820898379', '', 'END-00727', '', '', '', '0', '', '', 'DVF-00008', '', '0000-00-00 00:00:00', 1, 1, '1'),
(73, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00729', '1043', 1, 'Resident', 'Suresh', '9835612476', 'suresh@gmail.com', 'END-00730', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(74, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00730', '1043', 3, 'Local Guardian', 'Gandhi', '9864573215', '', 'END-00729', '', '', '', '', '', '', 'DVF-00008', 'DVF-00008', '0000-00-00 00:00:00', 1, 1, '1'),
(75, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00731', '1043', 1, 'Resident', 'Dinesh', '9653487125', 'dinesh@gmail.com', 'END-00732', '', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(76, 'AGN-00003', 'BUL-00016', 'UPL-00047', 'END-00732', '1043', 3, 'Local Guardian', 'Parwati', '9898976581', '', 'END-00731', '', '', '', '0', '', '', 'DVF-00008', '', '0000-00-00 00:00:00', 1, 1, '1'),
(77, 'AGN-00005', 'BUL-00017', 'UPL-00048', 'END-00733', '1034', 1, 'Student', 'Hasnain Mujawar', '', '', 'END-00734,END-00735', '1001', '', '', '0', '', '', 'DVF-00001', '', '0000-00-00 00:00:00', 1, 1, '1'),
(78, 'AGN-00005', 'BUL-00017', 'UPL-00048', 'END-00734', '1034', 2, 'Father', 'Khayum Mujawar', '8009357008', 'mujawarkhayum786@gmail.com', 'END-00733,END-00735', '', '', '', '0', '', '', 'DVF-00001,DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1'),
(79, 'AGN-00005', 'BUL-00017', 'UPL-00048', 'END-00735', '1034', 3, 'Mother', 'Farana Mujawar', '8009357008', 'mujawarkhayum786@gmail.com', 'END-00733,END-00734', '', '', '', '0', '', '', 'DVF-00001,DVF-00002', '', '0000-00-00 00:00:00', 1, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_upload_file_information_all`
--

CREATE TABLE `bulk_upload_file_information_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bulk_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `upload_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_datetime` datetime NOT NULL,
  `weblink_generated` datetime NOT NULL COMMENT 'datetime',
  `weblink_activated_from` datetime NOT NULL COMMENT 'datetime',
  `weblink_valid_till` datetime NOT NULL COMMENT 'datetime',
  `total_end_user` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'count',
  `total_partial_done` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'count',
  `total_completed` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'count',
  `paid_by` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '1= Wallet 2= by End user',
  `reminder_email` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '1= daily 2= every alternate daty 0=\r\nNo reminder(If value is 0 then except the\r\nwelcome email no further\r\nreminder email will be shoot to\r\nthe end user)',
  `reminder_sms` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '1= daily 2= every alternate daty 0=\r\nNo reminder(If value is 0 then except the\r\nwelcome email no further\r\nreminder email will be shoot to\r\nthe end user)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bulk_upload_file_information_all`
--

INSERT INTO `bulk_upload_file_information_all` (`id`, `agency_id`, `bulk_id`, `upload_id`, `status`, `uploaded_datetime`, `weblink_generated`, `weblink_activated_from`, `weblink_valid_till`, `total_end_user`, `total_partial_done`, `total_completed`, `paid_by`, `reminder_email`, `reminder_sms`) VALUES
(1, 'AGN-00003', 'BUL-00003', 'UPL-00038', '1', '2024-11-21 12:31:33', '2024-11-20 17:24:29', '2024-11-22 00:00:00', '2024-11-30 00:00:00', '18', '0', '1', '2', '1', '1'),
(2, 'AGN-00005', 'BUL-00006', 'UPL-00039', '1', '2024-11-21 15:15:04', '2024-11-21 14:53:44', '2024-11-21 00:00:00', '2024-11-23 00:00:00', '3', '0', '1', '1', '1', '1'),
(3, 'AGN-00002', 'BUL-00007', 'UPL-00040', '1', '2024-11-21 16:44:46', '2024-11-21 16:43:10', '2024-11-21 00:00:00', '2024-11-23 00:00:00', '12', '1', '2', '2', '1', '1'),
(4, 'AGN-00002', 'BUL-00007', 'UPL-00041', '1', '2024-11-21 16:46:43', '2024-11-21 16:43:10', '2024-11-21 00:00:00', '2024-11-23 00:00:00', '12', '1', '2', '2', '1', '1'),
(5, 'AGN-00006', 'BUL-00010', 'UPL-00042', '1', '2024-11-22 18:04:10', '2024-11-22 17:57:59', '2024-11-22 00:00:00', '2024-11-30 00:00:00', '2', '0', '0', '1', '1', '1'),
(6, 'AGN-00003', 'BUL-00011', 'UPL-00043', '1', '2024-11-23 15:57:13', '2024-11-23 15:38:52', '2024-11-23 00:00:00', '2024-12-05 00:00:00', '27', '0', '0', '1', '1', '1'),
(7, 'AGN-00006', 'BUL-00013', 'UPL-00044', '1', '2024-11-23 18:08:49', '2024-11-23 17:50:17', '2024-11-23 00:00:00', '2024-11-30 00:00:00', '4', '0', '0', '1', '1', '1'),
(8, 'AGN-00006', 'BUL-00013', 'UPL-00045', '1', '2024-11-23 18:08:56', '2024-11-23 17:50:17', '2024-11-23 00:00:00', '2024-11-30 00:00:00', '4', '0', '0', '1', '1', '1'),
(9, 'AGN-00003', 'BUL-00015', 'UPL-00046', '1', '2024-11-23 23:59:25', '2024-11-23 20:56:28', '2024-11-23 00:00:00', '2024-12-06 00:00:00', '4', '0', '1', '1', '1', '1'),
(10, 'AGN-00003', 'BUL-00016', 'UPL-00047', '1', '2024-11-24 16:03:54', '2024-11-24 14:45:48', '2024-11-24 00:00:00', '2024-12-08 00:00:00', '', '', '', '1', '1', '1'),
(11, 'AGN-00005', 'BUL-00017', 'UPL-00048', '1', '2024-11-24 20:52:01', '2024-11-24 20:45:34', '2024-11-24 00:00:00', '2024-11-26 00:00:00', '3', '0', '0', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_weblink_closed_all`
--

CREATE TABLE `bulk_weblink_closed_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bulk_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `upload_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `data_col1` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'One end user\r\none data(End_user_id <pdf name $ name - mob ! email #\r\nstatus + ( 0= not opened /1 = partial/2=completed )\r\n> result ~(1=amount / 2 =amount /))',
  `data_col2` longtext COLLATE utf8_unicode_ci,
  `data_col3` longtext COLLATE utf8_unicode_ci,
  `data_col4` longtext COLLATE utf8_unicode_ci,
  `data_col5` longtext COLLATE utf8_unicode_ci,
  `data_col6` longtext COLLATE utf8_unicode_ci,
  `data_col7` longtext COLLATE utf8_unicode_ci,
  `data_col8` longtext COLLATE utf8_unicode_ci,
  `data_col9` longtext COLLATE utf8_unicode_ci,
  `data_col10` longtext COLLATE utf8_unicode_ci,
  `data_col11` longtext COLLATE utf8_unicode_ci,
  `data_col12` longtext COLLATE utf8_unicode_ci,
  `data_col13` longtext COLLATE utf8_unicode_ci,
  `data_col14` longtext COLLATE utf8_unicode_ci,
  `data_col15` longtext COLLATE utf8_unicode_ci,
  `data_col16` longtext COLLATE utf8_unicode_ci,
  `data_col17` longtext COLLATE utf8_unicode_ci,
  `data_col18` longtext COLLATE utf8_unicode_ci,
  `data_col19` longtext COLLATE utf8_unicode_ci,
  `data_col20` longtext COLLATE utf8_unicode_ci,
  `data_col21` longtext COLLATE utf8_unicode_ci,
  `data_col22` longtext COLLATE utf8_unicode_ci,
  `data_col23` longtext COLLATE utf8_unicode_ci,
  `data_col24` longtext COLLATE utf8_unicode_ci,
  `data_col25` longtext COLLATE utf8_unicode_ci,
  `data_col26` longtext COLLATE utf8_unicode_ci,
  `data_col27` longtext COLLATE utf8_unicode_ci,
  `data_col28` longtext COLLATE utf8_unicode_ci,
  `data_col29` longtext COLLATE utf8_unicode_ci,
  `data_col30` longtext COLLATE utf8_unicode_ci,
  `data_col31` longtext COLLATE utf8_unicode_ci,
  `data_col32` longtext COLLATE utf8_unicode_ci,
  `data_col33` longtext COLLATE utf8_unicode_ci,
  `data_col34` longtext COLLATE utf8_unicode_ci,
  `data_col35` longtext COLLATE utf8_unicode_ci,
  `data_col36` longtext COLLATE utf8_unicode_ci,
  `data_col37` longtext COLLATE utf8_unicode_ci,
  `data_col38` longtext COLLATE utf8_unicode_ci,
  `data_col39` longtext COLLATE utf8_unicode_ci,
  `data_col40` longtext COLLATE utf8_unicode_ci,
  `data_col41` longtext COLLATE utf8_unicode_ci,
  `data_col42` longtext COLLATE utf8_unicode_ci,
  `data_col43` longtext COLLATE utf8_unicode_ci,
  `data_col44` longtext COLLATE utf8_unicode_ci,
  `data_col45` longtext COLLATE utf8_unicode_ci,
  `data_col46` longtext COLLATE utf8_unicode_ci,
  `data_col47` longtext COLLATE utf8_unicode_ci,
  `data_col48` longtext COLLATE utf8_unicode_ci,
  `data_col49` longtext COLLATE utf8_unicode_ci,
  `data_col50` longtext COLLATE utf8_unicode_ci,
  `data_col51` longtext COLLATE utf8_unicode_ci,
  `data_col52` longtext COLLATE utf8_unicode_ci,
  `data_col53` longtext COLLATE utf8_unicode_ci,
  `data_col54` longtext COLLATE utf8_unicode_ci,
  `data_col55` longtext COLLATE utf8_unicode_ci,
  `data_col56` longtext COLLATE utf8_unicode_ci,
  `data_col57` longtext COLLATE utf8_unicode_ci,
  `data_col58` longtext COLLATE utf8_unicode_ci,
  `data_col59` longtext COLLATE utf8_unicode_ci,
  `data_col60` longtext COLLATE utf8_unicode_ci,
  `data_col61` longtext COLLATE utf8_unicode_ci,
  `data_col62` longtext COLLATE utf8_unicode_ci,
  `data_col63` longtext COLLATE utf8_unicode_ci,
  `data_col64` longtext COLLATE utf8_unicode_ci,
  `data_col65` longtext COLLATE utf8_unicode_ci,
  `data_col66` longtext COLLATE utf8_unicode_ci,
  `data_col67` longtext COLLATE utf8_unicode_ci,
  `data_col68` longtext COLLATE utf8_unicode_ci,
  `data_col69` longtext COLLATE utf8_unicode_ci,
  `data_col70` longtext COLLATE utf8_unicode_ci,
  `data_col71` longtext COLLATE utf8_unicode_ci,
  `data_col72` longtext COLLATE utf8_unicode_ci,
  `data_col73` longtext COLLATE utf8_unicode_ci,
  `data_col74` longtext COLLATE utf8_unicode_ci,
  `data_col75` longtext COLLATE utf8_unicode_ci,
  `data_col76` longtext COLLATE utf8_unicode_ci,
  `data_col77` longtext COLLATE utf8_unicode_ci,
  `data_col78` longtext COLLATE utf8_unicode_ci,
  `data_col79` longtext COLLATE utf8_unicode_ci,
  `data_col80` longtext COLLATE utf8_unicode_ci,
  `data_col81` longtext COLLATE utf8_unicode_ci,
  `data_col82` longtext COLLATE utf8_unicode_ci,
  `data_col83` longtext COLLATE utf8_unicode_ci,
  `data_col84` longtext COLLATE utf8_unicode_ci,
  `data_col85` longtext COLLATE utf8_unicode_ci,
  `data_col86` longtext COLLATE utf8_unicode_ci,
  `data_col87` longtext COLLATE utf8_unicode_ci,
  `data_col88` longtext COLLATE utf8_unicode_ci,
  `data_col89` longtext COLLATE utf8_unicode_ci,
  `data_col90` longtext COLLATE utf8_unicode_ci,
  `line_no` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `inserted_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_weblink_request_all`
--

CREATE TABLE `bulk_weblink_request_all` (
  `id` int(10) NOT NULL,
  `bulk_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `request_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'iIs the no for the agency owner',
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '1= Generated , 2= uploaded 3= End Users Link Generated , 4 = force Close , 5= completed',
  `request_email_sent` datetime NOT NULL,
  `upload_weblink` longtext COLLATE utf8_unicode_ci NOT NULL,
  `upload_weblink_generated_on` datetime NOT NULL,
  `excel_no` int(20) NOT NULL COMMENT '1036',
  `successful_upload_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Upload id ',
  `custom_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'generated against the custom, ',
  `premises_location` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'For which location this bulk link is created\r\neg: Vasundhara Hostel for Thane location',
  `obj_1` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the object (ex. student)',
  `obj_1_verifications` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv of verifications type (DVF-00001, DVF-00002, DVF-00004, DVF-00005, DVF-00008)',
  `obj_1_mi_amt` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=amount+gst amount , DVF-00002= amount + gst amount',
  `obj_1_addon_amount` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001= amount+gst amount – commission,(Commission is the 4% of the total sum amount (amount+gst). End user has to pay the Amount + gst amont + client_add_on_amount + commission amount)',
  `obj_2` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the object (ex. Father)',
  `obj_2_verifications` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv of verifications type (DVF-00001, DVF-00002, DVF-00004, DVF-00005, DVF-00008)',
  `obj_2_mi_amt` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=amount+gst amount , DVF-00002= amount + gst amount',
  `obj_2_addon_amount` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001= amount+gst amount – commission,(Commission is the 4% of the total sum amount (amount+gst). End user has to pay the Amount + gst amont + client_add_on_amount + commission amount)',
  `obj_3` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the object (ex. Mother, Guardian)',
  `obj_3_verifications` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv of verifications type (DVF-00001, DVF-00002, DVF-00004, DVF-00005, DVF-00008)',
  `obj_3_mi_amt` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=amount+gst amount , DVF-00002= amount + gst amount',
  `obj_3_addon_amount` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001= amount+gst amount – commission,(Commission is the 4% of the total sum amount (amount+gst). End user has to pay the Amount + gst amont + client_add_on_amount + commission amount)',
  `tentative_amount` float NOT NULL COMMENT 'If payment for this weblink is from wallet and payment is low than current wallet balance then all total amount for this weblinkwill come in this column. should minus everytime whenever verification is done by any user.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bulk_weblink_request_all`
--

INSERT INTO `bulk_weblink_request_all` (`id`, `bulk_id`, `agency_id`, `request_no`, `status`, `request_email_sent`, `upload_weblink`, `upload_weblink_generated_on`, `excel_no`, `successful_upload_id`, `custom_id`, `premises_location`, `obj_1`, `obj_1_verifications`, `obj_1_mi_amt`, `obj_1_addon_amount`, `obj_2`, `obj_2_verifications`, `obj_2_mi_amt`, `obj_2_addon_amount`, `obj_3`, `obj_3_verifications`, `obj_3_mi_amt`, `obj_3_addon_amount`, `tentative_amount`) VALUES
(1, 'BUL-00001', 'AGN-00003', 'RUS-00001', '1', '2024-11-20 17:20:18', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-20 17:20:18', 1034, '', '', 'Thane', 'Student', 'DVF-00001', '', '', 'Father', 'DVF-00001', '', '', 'Mother', 'DVF-00001', '', '', 0),
(2, 'BUL-00002', 'AGN-00002', 'RUS-00002', '1', '2024-11-20 17:22:13', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-20 17:22:13', 1034, '', '', 'Pune', 'Student', 'DVF-00001', '', '', 'Father', 'DVF-00001,DVF-00002', '', '', 'Mother', 'DVF-00001,DVF-00002', '', '', 0),
(3, 'BUL-00003', 'AGN-00003', 'RUS-00003', '3', '2024-11-20 17:24:29', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-20 17:24:29', 1034, 'UPL-00038', '', 'Thane', 'Student', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=100+18-82', 'Father', 'DVF-00002', 'DVF-00002=17+3.06', 'DVF-00002=100+18-82', '', '', '', '', 0),
(4, 'BUL-00004', 'AGN-00003', 'RUS-00004', '1', '2024-11-20 17:37:03', 'https://mounarchtech.com/vocoxp/Student_above16yrs.xlsx', '2024-11-20 17:37:03', 1035, '', '', 'Thane', 'Student', 'DVF-00001', '', '', 'Parent', 'DVF-00002', '', '', '', '', '', '', 0),
(5, 'BUL-00005', 'AGN-00002', 'RUS-00005', '1', '2024-11-21 14:51:02', 'https://mounarchtech.com/vocoxp/Hostel_Registration.xlsx', '2024-11-21 14:51:02', 1043, '', '', 'Pune', 'Resident', 'DVF-00001', '', '', 'Parent', 'DVF-00001', '', '', 'Local Guardian', 'DVF-00001', '', '', 0),
(6, 'BUL-00006', 'AGN-00005', 'RUS-00006', '3', '2024-11-21 14:53:44', 'https://mounarchtech/vocoxp/Student_And_Parent (2).xlsx', '2024-11-21 14:53:44', 1034, 'UPL-00039', '', '', 'Student', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', 'Father', 'DVF-00001,DVF-00002', 'DVF-00001=33+5.94,DVF-00002=17+3.06', 'DVF-00001=0+0-0,DVF-00002=0+0-0', 'Mother', 'DVF-00001,DVF-00002', 'DVF-00001=33+5.94,DVF-00002=17+3.06', 'DVF-00001=0+0-0,DVF-00002=0+0-0', 0),
(7, 'BUL-00007', 'AGN-00002', 'RUS-00007', '1', '2024-11-22 16:23:25', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-22 16:23:25', 1034, 'UPL-00041', '', 'Pune', 'Student', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', 'Father', 'DVF-00002', 'DVF-00002=17+3.06', 'DVF-00002=0+0-0', 'Mother', 'DVF-00004,DVF-00005', 'DVF-00002=20+3.6,DVF-00002=20+3.6', 'DVF-00002=0+0-0,DVF-00002=0+0-0', 0),
(8, 'BUL-00008', 'AGN-00002', 'RUS-00008', '1', '2024-11-22 14:58:15', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-22 14:58:15', 1034, '', '', 'Pune', 'Student', 'DVF-00001', '', '', 'Father', 'DVF-00001,DVF-00002', '', '', 'Mother', 'DVF-00004,DVF-00005', '', '', 0),
(9, 'BUL-00009', 'AGN-00002', 'RUS-00009', '1', '2024-11-22 16:32:22', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-22 16:32:22', 1034, '', '', 'Pune', 'Student', 'DVF-00001', '', '', 'Father', 'DVF-00001,DVF-00002', '', '', 'Mother', 'DVF-00001,DVF-00002', '', '', 0),
(10, 'BUL-00010', 'AGN-00006', 'RUS-00010', '3', '2024-11-22 17:57:59', 'https://mounarchtech/vocoxp/Employes_Or_Teachers.xlsx', '2024-11-22 17:57:59', 1036, 'UPL-00042', '', 'company', 'Employees/Teachers', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', '', '', '', '', '', '', '', '', 0),
(11, 'BUL-00011', 'AGN-00003', 'RUS-00011', '3', '2024-11-23 15:38:52', 'https://mounarchtech/vocoxp/Student_And_Parent.xlsx', '2024-11-23 15:38:52', 1034, 'UPL-00043', '', 'Thane', 'Student', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', 'Father', 'DVF-00002', 'DVF-00002=17+3.06', 'DVF-00002=0+0-0', 'Mother', 'DVF-00002', 'DVF-00002=17+3.06', 'DVF-00002=0+0-0', 2134.62),
(12, 'BUL-00012', 'AGN-00005', 'RUS-00012', '1', '2024-11-23 15:49:10', 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '2024-11-23 15:49:10', 1034, '', '', '', 'Student', 'DVF-00001', '', '', '', '', '', '', 'Mother', 'DVF-00001,DVF-00002', '', '', 0),
(13, 'BUL-00013', 'AGN-00006', 'RUS-00013', '3', '2024-11-23 17:50:17', 'https://mounarchtech/vocoxp/Student_above16yrs.xlsx', '2024-11-23 17:50:17', 1035, 'UPL-00045', '', 'company', 'Student', 'DVF-00001', 'DVF-00001=33+5.94,DVF-00001=33+5.94,DVF-00001=33+5.94,DVF-00001=33+5.94', 'DVF-00001=0+0-0,DVF-00001=0+0-0,DVF-00001=0+0-0,DVF-00001=0+0-0', 'Parent', 'DVF-00001', 'DVF-00001=33+5.94,DVF-00001=33+5.94,DVF-00001=33+5.94,DVF-00001=33+5.94', 'DVF-00001=0+0-0,DVF-00001=0+0-0,DVF-00001=0+0-0,DVF-00001=0+0-0', '', '', '', '', 0),
(14, 'BUL-00014', 'AGN-00006', 'RUS-00014', '1', '2024-11-23 17:53:36', 'https://mounarchtech.com/vocoxp/Employes_Or_Teachers.xlsx', '2024-11-23 17:53:36', 1036, '', '', 'company', 'Employees/Teachers', 'DVF-00001', '', '', '', '', '', '', '', '', '', '', 0),
(15, 'BUL-00015', 'AGN-00003', 'RUS-00015', '3', '2024-11-23 20:56:28', 'https://mounarchtech.com/vocoxp/Hostel_Registration.xlsx', '2024-11-23 20:56:28', 1043, '', '', 'Thane', 'Resident', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', '', '', '', '', 'Local Guardian', 'DVF-00008', 'DVF-00001=2+0.36', 'DVF-00001=0+0-0', 116.82),
(16, 'BUL-00016', 'AGN-00003', 'RUS-00016', '3', '2024-11-24 14:45:48', 'https://mounarchtech/vocoxp/Hostel_Registration.xlsx', '2024-11-24 14:45:48', 1043, 'UPL-00047', '', 'Thane', 'Resident', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', '', '', '', '', 'Local Guardian', 'DVF-00008', 'DVF-00001=2+0.36', 'DVF-00001=0+0-0', 245.44),
(17, 'BUL-00017', 'AGN-00005', 'RUS-00017', '3', '2024-11-24 20:45:34', 'https://mounarchtech/vocoxp/Student_And_Parent (2).xlsx', '2024-11-24 20:45:34', 1034, 'UPL-00048', '', '', 'Student', 'DVF-00001', 'DVF-00001=33+5.94', 'DVF-00001=0+0-0', 'Father', 'DVF-00001,DVF-00002', 'DVF-00001=33+5.94,DVF-00002=17+3.06', 'DVF-00001=0+0-0,DVF-00002=0+0-0', 'Mother', 'DVF-00001,DVF-00002', 'DVF-00001=33+5.94,DVF-00002=17+3.06', 'DVF-00001=0+0-0,DVF-00002=0+0-0', 470.82);

-- --------------------------------------------------------

--
-- Table structure for table `cibil_active_transaction_all`
--

CREATE TABLE `cibil_active_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` int(10) NOT NULL DEFAULT '1' COMMENT '1=other_person/2=guard',
  `specification_id` varchar(100) NOT NULL,
  `process_id` varchar(100) NOT NULL DEFAULT '0.00',
  `type_id` varchar(100) NOT NULL DEFAULT '0.00',
  `price` float(10,2) NOT NULL,
  `is_permitted` int(10) NOT NULL DEFAULT '0' COMMENT '1= Yes/ 0= No',
  `permission_source` varchar(100) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_file` varchar(255) NOT NULL,
  `verification_match_in_percent` int(11) NOT NULL,
  `verification_mismatch_data` varchar(255) NOT NULL,
  `uploaded_documents` varchar(255) NOT NULL,
  `continued_with_mismatch` int(10) NOT NULL DEFAULT '0' COMMENT '1= Yes/ 0= No',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cibil_archive_transaction_all`
--

CREATE TABLE `cibil_archive_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `request_id` varchar(100) NOT NULL,
  `application_id` varchar(50) NOT NULL,
  `person_id` varchar(255) NOT NULL,
  `request_for` int(10) NOT NULL DEFAULT '1' COMMENT '1=other_person/2=guard',
  `specification_id` varchar(100) NOT NULL,
  `process_id` varchar(100) NOT NULL DEFAULT '0.00',
  `type_id` varchar(100) NOT NULL DEFAULT '0.00',
  `price` float(10,2) NOT NULL,
  `is_permitted` int(10) NOT NULL DEFAULT '0' COMMENT '1= Yes/ 0= No',
  `permission_source` varchar(100) NOT NULL,
  `verification_status` varchar(20) NOT NULL,
  `verification_file` varchar(255) NOT NULL,
  `verification_match_in_percent` int(11) NOT NULL,
  `verification_mismatch_data` varchar(255) NOT NULL,
  `uploaded_documents` varchar(255) NOT NULL,
  `continued_with_mismatch` int(10) NOT NULL DEFAULT '0' COMMENT '1= Yes/ 0= No',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `construction_site_header_all`
--

CREATE TABLE `construction_site_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `site_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `site_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `operational_from` date NOT NULL,
  `site_admins` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'csv',
  `site_state` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `site_city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `site_pincode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `site_radius` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `site_coordinates` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inserted_on` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 - active, 2- close, 3- suspended',
  `registered_workers_count` int(10) NOT NULL,
  `close_from` date NOT NULL,
  `close_reason` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `construction_site_worker_header_all`
--

CREATE TABLE `construction_site_worker_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `site_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `worker_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `login_code` int(6) NOT NULL,
  `gender` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `inserted_on` datetime NOT NULL,
  `blocked_or_removed_on` datetime NOT NULL,
  `blocked_or_removed_reason` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custome_duty_archive_all`
--

CREATE TABLE `custome_duty_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `applicable_from_date` datetime NOT NULL,
  `applicable_to_date` datetime NOT NULL,
  `custom_duty` varchar(500) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `custom_header_all`
--

CREATE TABLE `custom_header_all` (
  `id` int(50) NOT NULL,
  `custom_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `custom_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `weblink_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agency_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `weblink_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_creation` date NOT NULL,
  `excel_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `table_status` int(11) NOT NULL COMMENT '1=created,2=deleted',
  `custom_status` int(11) NOT NULL COMMENT '1=weblink created,\r\n2=deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `direct_aadhar_details_all`
--

CREATE TABLE `direct_aadhar_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `aadhar_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no\r\ndata will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_aadhar_details_all`
--

INSERT INTO `direct_aadhar_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `front_photo`, `back_photo`, `user_photo`, `generated_by`, `is_athenticate`) VALUES
(4, '', '', '', '', '2024-11-16 14:43:57', '2024-11-16 14:43:57', 0, '', '', '0000-00-00', '', '', '', '', '', 1, 1),
(7, 'DIR-00013', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:16:28', '2024-11-18 11:16:28', 1, '', 'Ashish Ravindra Khandare', '2001-05-22', 'MALE', '', '', '', '', 2, 1),
(8, 'DIR-00015', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:25:38', '2024-11-18 11:25:38', 2, '730642080992', 'ASHISH RAVINDRA KHANDARE', '1970-01-01', 'MALE', 'ADDRESS: AMBEDKAR VASTIGRUH DESHMUKH WADI WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00015/user_photo/DIR-00015.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00015/user_photo/DIR-00015.jpg', '', 1, 1),
(9, 'DIR-00069', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 14:41:40', '2024-11-18 14:41:40', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00069/user_photo/DIR-00069.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00069/user_photo/DIR-00069.jpg', '', 1, 1),
(10, 'DIR-00070', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 14:59:43', '2024-11-18 14:59:43', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'qq', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00070/user_photo/DIR-00070.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00070/user_photo/DIR-00070.jpg', '', 1, 1),
(11, 'DIR-00071', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:03:20', '2024-11-18 15:03:20', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00071/user_photo/DIR-00071.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00071/user_photo/DIR-00071.jpg', '', 1, 1),
(12, 'DIR-00072', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:13:46', '2024-11-18 15:13:46', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00072/user_photo/DIR-00072.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00072/user_photo/DIR-00072.jpg', '', 1, 1),
(13, 'DIR-00073', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:26:43', '2024-11-18 15:26:43', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'qq', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00073/user_photo/DIR-00073.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00073/user_photo/DIR-00073.jpg', '', 1, 1),
(14, 'DIR-00074', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:26:48', '2024-11-18 15:26:48', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'qq', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00074/user_photo/DIR-00074.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00074/user_photo/DIR-00074.jpg', '', 1, 1),
(15, 'DIR-00076', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:42:24', '2024-11-18 15:42:24', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00076/user_photo/DIR-00076.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00076/user_photo/DIR-00076.jpg', '', 1, 1),
(16, 'DIR-00077', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:45:15', '2024-11-18 15:45:15', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00077/user_photo/DIR-00077.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00077/user_photo/DIR-00077.jpg', '', 1, 1),
(17, 'DIR-00078', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 16:15:29', '2024-11-18 16:15:29', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00078/user_photo/DIR-00078.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00078/user_photo/DIR-00078.jpg', '', 1, 1),
(18, 'DIR-00082', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 17:46:39', '2024-11-18 17:46:39', 2, '546188549613', 'Ashish sutar', '2024-11-18', 'male', 'aa', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00082/user_photo/DIR-00082.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00082/user_photo/DIR-00082.jpg', '', 1, 1),
(19, 'DIR-00106', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:39:13', '2024-11-19 12:39:13', 1, '730642080992', 'ASHISH RAVINDRA KHANDARE', '1970-01-01', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00106/user_photo/DIR-00106.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00106/user_photo/DIR-00106.jpg', '', 1, 1),
(20, 'DIR-00109', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:45:35', '2024-11-19 12:45:35', 1, '730642080992', 'ASHISH RAVINDRA KHANDARE', '1970-01-01', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00109/user_photo/DIR-00109.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00109/user_photo/DIR-00109.jpg', '', 1, 1),
(21, 'DIR-00110', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:54:30', '2024-11-19 12:54:30', 1, '730642080992', '', '1970-01-01', '', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00110/user_photo/DIR-00110.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00110/user_photo/DIR-00110.jpg', '', 1, 1),
(22, 'DIR-00111', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:56:01', '2024-11-19 12:56:01', 2, '730642080992', 'ASHISH RAVINDRA KHANDARE', '1970-01-01', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00111/user_photo/DIR-00111.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00111/user_photo/DIR-00111.jpg', '', 1, 1),
(23, 'DIR-00115', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 13:00:58', '2024-11-19 13:00:58', 1, '', 'Ashish Ravindra Khandare', '2001-05-22', 'MALE', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00115/doc_photo/docs-673c3eb5accc5.jpg', 2, 1),
(24, 'DIR-00117', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 13:05:08', '2024-11-19 13:05:08', 1, '', 'Ashish Ravindra Khandare', '2001-05-22', 'MALE', '', '', '', '', 2, 1),
(25, 'DIR-00144', 'APP-00001', 'AGN-00005', 'END-00478', '2024-11-19 14:08:21', '2024-11-19 14:08:21', 2, '546188549613', 'mujawar khayum', '2024-11-19', 'male', 'l', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00144/user_photo/DIR-00144.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00144/user_photo/DIR-00144.jpg', '', 1, 1),
(26, 'DIR-00147', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:15:26', '2024-11-19 14:15:26', 1, '', 'Ashish Ravindra Khandare', '2001-05-22', 'MALE', '', '', '', '', 2, 1),
(27, 'DIR-00152', 'APP-00001', 'AGN-00005', 'END-00477', '2024-11-19 14:27:23', '2024-11-19 14:27:23', 2, '546188549613', 'mujawar hasnain', '2024-11-12', 'male', 'll', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00152/user_photo/DIR-00152.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00152/user_photo/DIR-00152.jpg', '', 1, 1),
(28, 'DIR-00161', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 15:29:45', '2024-11-19 15:29:45', 1, '730642080992', 'ASHISH RAVINDRA KHANDARE', '1970-01-01', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00161/user_photo/DIR-00161.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00161/user_photo/DIR-00161.jpg', '', 1, 1),
(29, 'DIR-00169', 'APP-00001', 'AGN-00003', 'END-00521', '2024-11-19 17:48:25', '2024-11-19 17:48:25', 2, '423130569644', 'Salman', '2024-11-20', 'male', 'Vashi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00169/user_photo/DIR-00169.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00169/user_photo/DIR-00169.jpg', '', 1, 1),
(30, 'DIR-00178', 'APP-00001', 'AGN-00003', 'END-00518', '2024-11-19 18:11:27', '2024-11-19 18:11:27', 2, '423130569644', 'Sharukh', '2021-12-23', 'male', 'Vashi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00178/user_photo/DIR-00178.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00178/user_photo/DIR-00178.jpg', '', 1, 1),
(31, 'DIR-00181', 'APP-00001', 'AGN-00003', 'END-00515', '2024-11-19 18:28:31', '2024-11-19 18:28:31', 2, '423130569644', 'Badshah', '3333-02-23', 'male', 'Vashi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00181/user_photo/DIR-00181.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00181/user_photo/DIR-00181.jpg', '', 1, 1),
(32, 'DIR-00182', 'APP-00001', 'AGN-00003', 'END-00569', '2024-11-20 11:45:14', '2024-11-20 11:45:14', 2, '423130569644', 'Salman', '1992-01-23', 'female', 'Vashi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00182/user_photo/DIR-00182.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00182/user_photo/DIR-00182.jpg', '', 1, 1),
(33, 'DIR-00200', 'APP-00001', 'AGN-00002', 'END-00572', '2024-11-20 15:14:53', '2024-11-20 15:14:53', 2, '266923465264', 'Abhijit sutar', '2024-11-20', 'male', 'pune', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00200/user_photo/DIR-00200.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00200/user_photo/DIR-00200.jpg', '', 1, 1),
(34, 'DIR-00208', 'APP-00001', 'AGN-00003', 'END-00607', '2024-11-21 11:58:58', '2024-11-21 11:58:58', 2, '546188549613', 'Gudiya', '2024-11-21', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00208/doc_photo/docs-673ed32a105f0.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00208/doc_photo/docs-673ed32a458cb.jpg', '', 1, 1),
(35, 'DIR-00209', 'APP-00001', 'AGN-00003', 'END-00607', '2024-11-21 12:05:28', '2024-11-21 12:05:28', 2, '546188549613', 'Gudiya', '2024-11-21', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00209/doc_photo/docs-673ed4b059497.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00209/doc_photo/docs-673ed4b08c96e.jpg', '', 1, 1),
(36, 'DIR-00210', 'APP-00001', 'AGN-00003', 'END-00607', '2024-11-21 12:19:40', '2024-11-21 12:19:40', 2, '546188549613', 'Gudiya', '2024-11-21', 'male', 'S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00210/doc_photo/docs-673ed80431822.png', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00210/doc_photo/docs-673ed804627fb.png', '', 1, 1),
(37, 'DIR-00211', 'APP-00001', 'AGN-00005', 'END-00673', '2024-11-21 15:27:55', '2024-11-21 15:27:55', 2, '546188549613', 'Hasnain Mujawar', '1992-12-27', 'male', 's/o pasha mujawar,khadgaon,latur,413531', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00211/doc_photo/docs-673f04234040e.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00211/doc_photo/docs-673f04236244a.jpg', '', 1, 1),
(38, 'DIR-00212', 'APP-00001', 'AGN-00002', 'END-00679', '2024-11-21 16:52:29', '2024-11-21 16:52:29', 2, '266923465264', 'Ashish sutar', '2024-11-21', 'male', 'pune', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00212/doc_photo/docs-673f17f51e2fb.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00212/doc_photo/docs-673f17f54f28c.jpg', '', 1, 1),
(39, 'DIR-00214', 'APP-00001', 'AGN-00005', 'END-00673', '2024-11-22 15:17:21', '2024-11-22 15:17:21', 2, '546188549613', 'Hasnain Mujawar', '2024-11-22', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00214/doc_photo/docs-67405328e31b5.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00214/doc_photo/docs-6740532912aa7.jpg', '', 1, 1),
(40, 'DIR-00215', 'APP-00001', 'AGN-00005', 'END-00673', '2024-11-22 15:25:09', '2024-11-22 15:25:09', 2, '546188549613', 'Hasnain Mujawar', '2024-11-22', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00215/doc_photo/docs-674054fca21c3.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00215/doc_photo/docs-674054fcd2cf7.jpg', '', 1, 1),
(41, 'DIR-00219', 'APP-00001', 'AGN-00005', 'END-00673', '2024-11-22 16:56:42', '2024-11-22 16:56:42', 2, '546188549613', 'Hasnain Mujawar', '2024-11-22', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00219/doc_photo/docs-67406a7206d8a.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00219/doc_photo/docs-67406a7225fdd.jpg', '', 1, 1),
(42, 'DIR-00220', 'APP-00001', 'AGN-00005', 'END-00673', '2024-11-22 16:59:49', '2024-11-22 16:59:49', 2, '546188549613', 'Hasnain Mujawar', '2024-11-22', 'male', 'latur', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00220/doc_photo/docs-67406b2d40202.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00220/doc_photo/docs-67406b2d712d6.jpg', '', 1, 1),
(43, 'DIR-00226', 'APP-00001', 'AGN-00003', 'END-00723', '2024-11-24 00:12:31', '2024-11-24 00:12:31', 2, '423130569644', 'Nama', '1992-11-23', 'female', 'Vashi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00226/doc_photo/docs-6742221751c23.jpeg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00226/doc_photo/docs-674222177da26.jpeg', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `direct_details_all`
--

CREATE TABLE `direct_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `document_type` int(20) NOT NULL COMMENT '1=Aadhar\r\n 2=Pan \r\n3=DL \r\n4=Voter \r\n5=Passport',
  `aadhar_number` varchar(20) NOT NULL,
  `pan_number` varchar(20) NOT NULL,
  `passport_number` varchar(20) NOT NULL,
  `dl_number` varchar(20) NOT NULL,
  `voter_number` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `father_name` varchar(20) NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `country_code` int(10) NOT NULL,
  `state_name` text NOT NULL,
  `nationality` text NOT NULL,
  `front_photo` varchar(100) NOT NULL,
  `back_photo` varchar(100) NOT NULL,
  `user_photo` varchar(100) NOT NULL,
  `cover_photo` varchar(100) NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` text NOT NULL,
  `classes_of_vehicle` varchar(20) NOT NULL COMMENT 'Classes of Vehicles(LMV,MCWG, etc)	',
  `polling_details` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=1, verified=2, not verified=2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `direct_dl_details_all`
--

CREATE TABLE `direct_dl_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `dl_number` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `father_name` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `date_of_issue` date NOT NULL,
  `classes_of_vehicle` varchar(20) NOT NULL COMMENT 'Classes of Vehicles(LMV,MCWG, etc)',
  `state_name` text NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_dl_details_all`
--

INSERT INTO `direct_dl_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `dl_number`, `name`, `father_name`, `address`, `dob`, `date_of_expiry`, `date_of_issue`, `classes_of_vehicle`, `state_name`, `blood_group`, `user_photo`, `front_photo`, `back_photo`, `generated_by`, `is_athenticate`) VALUES
(1, 'DIR-00035', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 12:21:12', '2024-11-18 12:21:12', 1, 'BR0120170396813', 'NIRAJ KUMAR', '', '16-PARSA PO- PARSA,PS-FATUHA,PATNA', '0000-00-00', '2037-01-16', '2017-01-17', '', 'Bihar', 'B_POSITIVE', '', '', '', 2, 1),
(2, 'DIR-00040', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 12:33:38', '2024-11-18 12:33:38', 1, 'PB0320190003802', 'KOMAL PREET KAUR', '', 'HOUSE NO 16641-B STREET NO 9,BASANT VIHAR,BATHINDA,PB', '0000-00-00', '2039-05-15', '2019-05-16', '', 'Punjab', 'B_POSITIVE', '', '', '', 2, 1),
(3, 'DIR-00051', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 12:48:08', '2024-11-18 12:48:08', 1, 'MH1020200011730', 'SANKET JADHAV', '', 'GHUMATMAL NEAR SCHOOL NO 7,Vita (M Cl),Khanapur,Sangli,MH', '0000-00-00', '2038-09-06', '2020-10-08', '', 'Maharashtra', 'A_POSITIVE', '', '', '', 2, 1),
(4, 'DIR-00151', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:26:22', '2024-11-19 14:26:22', 1, 'PB0320190003802', 'KOMAL PREET KAUR', '', 'HOUSE NO 16641-B STREET NO 9,BASANT VIHAR,BATHINDA,PB', '0000-00-00', '2039-05-15', '2019-05-16', '', 'Punjab', 'B_POSITIVE', '', '', '', 2, 1),
(5, 'DIR-00156', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:31:05', '2024-11-19 14:31:05', 1, 'PB0320190003802', 'KOMAL PREET KAUR', '', 'HOUSE NO 16641-B STREET NO 9,BASANT VIHAR,BATHINDA,PB', '0000-00-00', '2039-05-15', '2019-05-16', '', 'Punjab', 'B_POSITIVE', '', '', '', 2, 1),
(6, 'DIR-00166', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 15:35:13', '2024-11-19 15:35:13', 1, 'BR0120170396813', 'NIRAJ KUMAR', '', '16-PARSA PO- PARSA,PS-FATUHA,PATNA', '0000-00-00', '2037-01-16', '2017-01-17', '', 'Bihar', 'B_POSITIVE', '', '', '', 2, 1),
(7, 'DIR-00167', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 15:38:11', '2024-11-19 15:38:11', 1, 'HR2620170020292', 'RAVI GULATI', '', 'D-1301 PARK VIEW CITY-2,SOHNA ROAD SEC 49,GURUGRAM (M CORP. + OG),GURUGRAM,HR', '0000-00-00', '2031-04-10', '2021-04-11', '', 'Haryana', 'B_POSITIVE', '', '', '', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `direct_international_passport_details_all`
--

CREATE TABLE `direct_international_passport_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `visa_photo` longtext NOT NULL,
  `landing_date` date NOT NULL,
  `visa_validity` date NOT NULL,
  `country` varchar(50) NOT NULL,
  `visa_type` varchar(100) NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_international_passport_details_all`
--

INSERT INTO `direct_international_passport_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `passport_number`, `surname`, `name`, `gender`, `dob`, `place_of_birth`, `father_name`, `mother_name`, `spouse_name`, `address`, `republic_of_india`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `country_code`, `nationality`, `passport_type`, `file_number`, `cover_photo`, `user_photo`, `front_photo`, `back_photo`, `visa_photo`, `landing_date`, `visa_validity`, `country`, `visa_type`, `generated_by`, `is_athenticate`) VALUES
(1, 'DIR-00068', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 14:18:55', '2024-11-18 14:18:55', 2, '340003955', 'TRAVELER', 'HAPPY', '', '1970-01-01', '', '', '', '', '', '', '0000-00-00', '1970-01-01', '', 0, '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00068/doc_photo/docs-673aff775aad1.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00068/doc_photo/docs-673aff76d9101.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00068/doc_photo/docs-673aff77188c9.jpg', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00068/doc_photo/docs-673aff77ae249.jpg', '1970-01-01', '1970-01-01', '', 'Work Visa', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `direct_pan_details_all`
--

CREATE TABLE `direct_pan_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `pan_number` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/husband name',
  `dob` date NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_pan_details_all`
--

INSERT INTO `direct_pan_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `pan_number`, `name`, `father_name`, `dob`, `user_photo`, `front_photo`, `generated_by`, `is_athenticate`) VALUES
(3, 'DIR-00017', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:46:58', '2024-11-18 11:46:58', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', '', '', 2, 1),
(4, 'DIR-00018', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:47:37', '2024-11-18 11:47:37', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00018/doc_photo/docs-673adc0311dc3.jpg', '', 2, 1),
(5, 'DIR-00020', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:47:57', '2024-11-18 11:47:57', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', '', '', 2, 0),
(6, 'DIR-00075', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 15:38:17', '2024-11-18 15:38:17', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00075/doc_photo/docs-673b1211aa655.jpg', 1, 1),
(7, 'DIR-00079', 'APP-00001', 'AGN-00001', 'END-00003', '2024-11-18 17:16:37', '2024-11-18 17:16:37', 2, 'BCFPM2764P', 'Pallavi sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00079/doc_photo/docs-673b291d5f038.jpg', 1, 1),
(8, 'DIR-00080', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 17:27:15', '2024-11-18 17:27:15', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00080/doc_photo/docs-673b2b9b29917.jpg', 1, 1),
(9, 'DIR-00081', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 17:43:58', '2024-11-18 17:43:58', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00081/doc_photo/docs-673b2f85ef774.jpg', 1, 1),
(10, 'DIR-00083', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 17:51:43', '2024-11-18 17:51:43', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00083/doc_photo/docs-673b3157c6b30.jpg', 1, 1),
(11, 'DIR-00084', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:02:57', '2024-11-18 18:02:57', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00084/doc_photo/docs-673b33f94f546.jpg', 1, 1),
(12, 'DIR-00085', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:04:00', '2024-11-18 18:04:00', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00085/doc_photo/docs-673b343874054.jpg', 1, 1),
(13, 'DIR-00086', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:05:27', '2024-11-18 18:05:27', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00086/doc_photo/docs-673b348f0c1e1.jpg', 1, 1),
(14, 'DIR-00087', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:08:23', '2024-11-18 18:08:23', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00087/doc_photo/docs-673b353f79173.jpg', 1, 1),
(15, 'DIR-00088', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:10:45', '2024-11-18 18:10:45', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00088/doc_photo/docs-673b35cce5bcc.jpg', 1, 1),
(16, 'DIR-00089', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:28:16', '2024-11-18 18:28:16', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00089/doc_photo/docs-673b39e8b41e1.jpg', 1, 1),
(17, 'DIR-00090', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:34:37', '2024-11-18 18:34:37', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00090/doc_photo/docs-673b3b6517718.jpg', 1, 1),
(18, 'DIR-00091', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:37:24', '2024-11-18 18:37:24', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00091/doc_photo/docs-673b3c0c18db6.jpg', 1, 1),
(19, 'DIR-00092', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:39:01', '2024-11-18 18:39:01', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00092/doc_photo/docs-673b3c6d8c209.jpg', 1, 1),
(20, 'DIR-00093', 'APP-00001', 'AGN-00001', 'END-00002', '2024-11-18 18:44:12', '2024-11-18 18:44:12', 2, 'BCFPM2764P', 'Ashish sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00093/doc_photo/docs-673b3da45989b.jpg', 1, 1),
(21, 'DIR-00099', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:25:13', '2024-11-19 12:25:13', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00099/doc_photo/docs-673c3653db5b2.jpg', '', 2, 1),
(22, 'DIR-00100', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:26:05', '2024-11-19 12:26:05', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00100/doc_photo/docs-673c3687cce64.jpg', '', 2, 1),
(23, 'DIR-00101', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:26:35', '2024-11-19 12:26:35', 1, 'MKNPK9042B', 'AKANSHA RAVINDRA KHANDARE', '', '0000-00-00', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00101/doc_photo/docs-673c36a65fac9.jpg', '', 2, 1),
(24, 'DIR-00103', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:27:54', '2024-11-19 12:27:54', 1, 'MKNPK9042B', 'AKANSHA RAVINDRA KHANDARE', '', '0000-00-00', '', '', 2, 0),
(25, 'DIR-00105', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:28:40', '2024-11-19 12:28:40', 1, 'IOBPK5360E', 'ASHISH RAVINDRA KHANDARE', '', '0000-00-00', '', '', 2, 1),
(26, 'DIR-00145', 'APP-00001', 'AGN-00005', 'END-00478', '2024-11-19 14:15:05', '2024-11-19 14:15:05', 2, 'BCFPM2764P', 'mujawar khayum', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00145/doc_photo/docs-673c5011a7f69.jpg', 1, 1),
(27, 'DIR-00162', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 15:30:54', '2024-11-19 15:30:54', 1, 'CQIPK3042G', 'NIRAJ KUMAR', '', '0000-00-00', '', '', 2, 1),
(28, 'DIR-00164', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 15:31:50', '2024-11-19 15:31:50', 1, 'CQIPK3042G', 'NIRAJ KUMAR', '', '0000-00-00', '', '', 2, 0),
(29, 'DIR-00174', 'APP-00001', 'AGN-00003', 'END-00522', '2024-11-19 17:58:17', '2024-11-19 17:58:17', 2, 'BCFPM2764P', 'Khalim', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00174/doc_photo/docs-673c84612671e.jpg', 1, 1),
(30, 'DIR-00179', 'APP-00001', 'AGN-00003', 'END-00519', '2024-11-19 18:12:33', '2024-11-19 18:12:33', 2, 'DRSPS2534B', 'Mohmmad', 'Randhir Paul', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00179/doc_photo/docs-673c87b903945.jpeg', 1, 1),
(31, 'DIR-00180', 'APP-00001', 'AGN-00003', 'END-00516', '2024-11-19 18:19:20', '2024-11-19 18:19:20', 2, 'DRSPS2534B', 'Daya', 'Randhir Paul', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00180/doc_photo/docs-673c8950aa546.jpeg', 1, 1),
(32, 'DIR-00213', 'APP-00001', 'AGN-00002', 'END-00680', '2024-11-21 16:55:07', '2024-11-21 16:55:07', 2, 'IOBPK5360E', 'Ashish sutar', 'Ravindra khandare', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00213/doc_photo/docs-673f189326daa.jpg', 1, 1),
(33, 'DIR-00222', 'APP-00001', 'AGN-00002', 'END-00687', '2024-11-23 11:31:24', '2024-11-23 11:31:24', 2, 'BCFPM2764P', 'Pallavi sutar', 'Pasharajak Mujawar', '0000-00-00', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00222/doc_photo/docs-67416fb42702a.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `direct_passport_details_all`
--

CREATE TABLE `direct_passport_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_passport_details_all`
--

INSERT INTO `direct_passport_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `passport_number`, `surname`, `name`, `gender`, `dob`, `place_of_birth`, `father_name`, `mother_name`, `spouse_name`, `address`, `republic_of_india`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `country_code`, `nationality`, `passport_type`, `file_number`, `cover_photo`, `user_photo`, `front_photo`, `back_photo`, `generated_by`, `is_athenticate`) VALUES
(1, 'DIR-00060', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 13:22:13', '2024-11-18 13:22:13', 1, 'x7529049', 'KUMAR', 'NI RAJ', '', '1992-01-07', '', '', '', '', 'DEVI\nName of Faher /Legal Guardian\nf Name of Spouse\n302 MALHAR HEIGHTs, DATTA COLONY\nVISHAL NAGAR, P', '', '0000-00-00', '0000-00-00', '', 0, '', '', 'PN1067586113823', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00060/doc_photo/docs-673af22d89884.jpg', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00060/doc_photo/docs-673af22cd6b08.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00060/doc_photo/docs-673af22d37499.jpg', 1, 0),
(2, 'DIR-00063', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 14:04:45', '2024-11-18 14:04:45', 1, 'X7529049', 'KUMAR', 'NIRAJ', '', '1992-01-07', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 0, '', 'PASSPORT', 'PN1067586113823', '', '', '', '', 2, 1),
(3, 'DIR-00065', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 14:05:51', '2024-11-18 14:05:51', 1, 'X7529049', 'KUMAR', 'NIRAJ', '', '1992-01-07', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 0, '', 'PASSPORT', 'PN1067586113823', '', '', '', '', 2, 0),
(4, 'DIR-00066', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 14:14:06', '2024-11-18 14:14:06', 1, 'X7529049', 'KUMAR', 'NIRAJ', '', '1992-01-07', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 0, '', 'PASSPORT', 'PN1067586113823', '', '', '', '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `direct_verification_details_all`
--

CREATE TABLE `direct_verification_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `verification_id` varchar(100) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `linked_table` varchar(100) NOT NULL COMMENT 'Name of the table in which detailed data is stored',
  `deducted_base_amount` float(10,2) NOT NULL COMMENT 'Base amount deducted from wallet',
  `sgst_amount` float(10,2) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `invoice_sent` int(11) NOT NULL COMMENT '0= Not sent , 1 = sent',
  `invoice_url` longtext NOT NULL,
  `report_url` longtext NOT NULL,
  `source_from` int(10) NOT NULL COMMENT '1= construction (superviser)\r\n2=hotel (reception_manger)\r\n3=school\r\n4=nbsp',
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2',
  `ambiguity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_verification_details_all`
--

INSERT INTO `direct_verification_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `verification_id`, `initiated_on`, `completed_on`, `activity_status`, `linked_table`, `deducted_base_amount`, `sgst_amount`, `cgst_amount`, `invoice_sent`, `invoice_url`, `report_url`, `source_from`, `generated_by`, `is_athenticate`, `ambiguity`) VALUES
(8, 'DIR-00013', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 11:16:28', '2024-11-18 11:16:28', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', '', 0, 2, 1, ''),
(9, 'DIR-00015', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 11:25:38', '2024-11-18 11:25:38', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00015/verification_report/DIR-00015.pdf', 4, 1, 1, 'name@!ASHISH RAVINDRA KHANDARE,dob@!01-01-1970'),
(10, 'DIR-00017', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 11:46:58', '2024-11-18 11:46:58', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(11, 'DIR-00018', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 11:47:37', '2024-11-18 11:47:37', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(12, 'DIR-00020', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 11:47:57', '2024-11-18 11:47:57', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(13, 'DIR-00035', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-18 12:21:12', '2024-11-18 12:21:12', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(14, 'DIR-00040', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-18 12:33:38', '2024-11-18 12:33:38', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(15, 'DIR-00042', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-18 12:40:48', '2024-11-18 12:40:48', 1, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00042/verification_report/DIR-00042.pdf', 4, 1, 1, 'name@!ASHISH RAVINDRA KHANDARE,gender@!MALE,guardian_name@!LAXMI KHANDARE,polling_station@!'),
(16, 'DIR-00051', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-18 12:48:08', '2024-11-18 12:48:08', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(17, 'DIR-00055', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-18 12:55:09', '2024-11-18 12:55:09', 0, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(18, 'DIR-00057', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-18 13:05:03', '2024-11-18 13:05:03', 0, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(19, 'DIR-00059', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-18 13:17:09', '2024-11-18 13:17:09', 1, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00059/verification_report/DIR-00059.pdf', 4, 1, 1, 'name@!AYUSH SAXENA,gender@!MALE,guardian_name@!JIGYASA SHRIVASTAVA,polling_station@!'),
(20, 'DIR-00060', 'APP-00001', 'AGN-00001', 'DVF-00006', '2024-11-18 13:22:13', '2024-11-18 13:22:13', 1, 'direct_passport_details_all', 20.00, 1.80, 1.80, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00060/verification_report/DIR-00060.pdf', 4, 1, 1, ''),
(21, 'DIR-00063', 'APP-00001', 'AGN-00001', 'DVF-00006', '2024-11-18 14:04:45', '2024-11-18 14:04:45', 1, 'direct_passport_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(22, 'DIR-00065', 'APP-00001', 'AGN-00001', 'DVF-00006', '2024-11-18 14:05:51', '2024-11-18 14:05:51', 1, 'direct_passport_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(23, 'DIR-00066', 'APP-00001', 'AGN-00001', 'DVF-00006', '2024-11-18 14:14:06', '2024-11-18 14:14:06', 1, 'direct_passport_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(24, 'DIR-00068', 'APP-00001', 'AGN-00001', 'DVF-00007', '2024-11-18 14:18:55', '2024-11-18 14:18:55', 2, 'direct_international_passport_details_all', 20.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00068/verification_report/DIR-00068.pdf', 4, 1, 1, ''),
(25, 'DIR-00069', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 14:41:40', '2024-11-18 14:41:40', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00069/verification_report/DIR-00069.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(26, 'DIR-00070', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 14:59:43', '2024-11-18 14:59:43', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00070/verification_report/DIR-00070.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(27, 'DIR-00071', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:03:20', '2024-11-18 15:03:20', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00071/verification_report/DIR-00071.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(28, 'DIR-00072', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:13:46', '2024-11-18 15:13:46', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00072/verification_report/DIR-00072.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(29, 'DIR-00073', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:26:43', '2024-11-18 15:26:43', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00073/verification_report/DIR-00073.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(30, 'DIR-00074', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:26:48', '2024-11-18 15:26:48', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00074/verification_report/DIR-00074.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(31, 'DIR-00075', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 15:38:17', '2024-11-18 15:38:17', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00075/verification_report/DIR-00075.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(32, 'DIR-00076', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:42:24', '2024-11-18 15:42:24', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00076/verification_report/DIR-00076.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(33, 'DIR-00077', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 15:45:15', '2024-11-18 15:45:15', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00077/verification_report/DIR-00077.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(34, 'DIR-00078', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 16:15:29', '2024-11-18 16:15:29', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00078/verification_report/DIR-00078.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(35, 'DIR-00079', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 17:16:37', '2024-11-18 17:16:37', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00079/verification_report/DIR-00079.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Pallavi sutar,'),
(36, 'DIR-00080', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 17:27:15', '2024-11-18 17:27:15', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00080/verification_report/DIR-00080.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(37, 'DIR-00081', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 17:43:58', '2024-11-18 17:43:58', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00081/verification_report/DIR-00081.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(38, 'DIR-00082', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-18 17:46:39', '2024-11-18 17:46:39', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00082/verification_report/DIR-00082.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Ashish sutar,dob@1991-12-27!18-11-2024'),
(39, 'DIR-00083', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 17:51:43', '2024-11-18 17:51:43', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00083/verification_report/DIR-00083.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(40, 'DIR-00084', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:02:57', '2024-11-18 18:02:57', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00084/verification_report/DIR-00084.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(41, 'DIR-00085', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:04:00', '2024-11-18 18:04:00', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00085/verification_report/DIR-00085.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(42, 'DIR-00086', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:05:27', '2024-11-18 18:05:27', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00086/verification_report/DIR-00086.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(43, 'DIR-00087', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:08:23', '2024-11-18 18:08:23', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00087/verification_report/DIR-00087.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(44, 'DIR-00088', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:10:45', '2024-11-18 18:10:45', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00088/verification_report/DIR-00088.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(45, 'DIR-00089', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:28:16', '2024-11-18 18:28:16', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00089/verification_report/DIR-00089.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(46, 'DIR-00090', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:34:37', '2024-11-18 18:34:37', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00090/verification_report/DIR-00090.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(47, 'DIR-00091', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:37:24', '2024-11-18 18:37:24', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00091/verification_report/DIR-00091.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(48, 'DIR-00092', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:39:01', '2024-11-18 18:39:01', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00092/verification_report/DIR-00092.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(49, 'DIR-00093', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-18 18:44:12', '2024-11-18 18:44:12', 2, 'direct_pan_details_all', 20.06, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00093/verification_report/DIR-00093.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Ashish sutar,'),
(50, 'DIR-00099', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 12:25:13', '2024-11-19 12:25:13', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(51, 'DIR-00100', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 12:26:05', '2024-11-19 12:26:05', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(52, 'DIR-00101', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 12:26:35', '2024-11-19 12:26:35', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(53, 'DIR-00103', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 12:27:54', '2024-11-19 12:27:54', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(54, 'DIR-00105', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 12:28:40', '2024-11-19 12:28:40', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(55, 'DIR-00106', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 12:39:13', '2024-11-19 12:39:13', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00106/verification_report/DIR-00106.pdf', 4, 1, 1, 'name@!ASHISH RAVINDRA KHANDARE,dob@!01-01-1970'),
(56, 'DIR-00109', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 12:45:35', '2024-11-19 12:45:35', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00109/verification_report/DIR-00109.pdf', 4, 1, 1, 'name@!ASHISH RAVINDRA KHANDARE,dob@!01-01-1970'),
(57, 'DIR-00110', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 12:54:30', '2024-11-19 12:54:30', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00110/verification_report/DIR-00110.pdf', 4, 1, 1, 'name@Ashish Ravindra Khandare!,dob@2001-05-22!01-01-1970'),
(58, 'DIR-00111', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 12:56:01', '2024-11-19 12:56:01', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00111/verification_report/DIR-00111.pdf', 4, 1, 1, 'name@!ASHISH RAVINDRA KHANDARE,dob@!01-01-1970'),
(59, 'DIR-00115', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 13:00:58', '2024-11-19 13:00:58', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', '', 0, 2, 1, ''),
(60, 'DIR-00117', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 13:05:08', '2024-11-19 13:05:08', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', '', 0, 2, 1, ''),
(61, 'DIR-00135', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-19 13:19:06', '2024-11-19 13:19:06', 1, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/verification_report/DIR-00135.pdf', 4, 1, 1, 'name@!KPUNDRA KHANDARE,gender@!,guardian_name@!,polling_station@!'),
(62, 'DIR-00144', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-19 14:08:21', '2024-11-19 14:08:21', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00144/verification_report/DIR-00144.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!mujawar khayum,dob@1991-12-27!19-11-2024'),
(63, 'DIR-00145', 'APP-00001', 'AGN-00005', 'DVF-00002', '2024-11-19 14:15:05', '2024-11-19 14:15:05', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00145/verification_report/DIR-00145.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!mujawar khayum,'),
(64, 'DIR-00147', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 14:15:26', '2024-11-19 14:15:26', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', '', 0, 2, 1, ''),
(65, 'DIR-00148', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-19 14:19:31', '2024-11-19 14:19:31', 1, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00148/verification_report/DIR-00148.pdf', 4, 1, 1, ',,,polling_station@Sri Guru Harkrishan Public Senior Secondary School - 34!'),
(66, 'DIR-00149', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-19 14:20:31', '2024-11-19 14:20:31', 0, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(67, 'DIR-00151', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-19 14:26:22', '2024-11-19 14:26:22', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(68, 'DIR-00152', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-19 14:27:23', '2024-11-19 14:27:23', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00152/verification_report/DIR-00152.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!mujawar hasnain,dob@1991-12-27!12-11-2024'),
(69, 'DIR-00156', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-19 14:31:05', '2024-11-19 14:31:05', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(70, 'DIR-00159', 'APP-00001', 'AGN-00001', 'DVF-00005', '2024-11-19 14:36:51', '2024-11-19 14:36:51', 0, 'direct_voter_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(71, 'DIR-00161', 'APP-00001', 'AGN-00001', 'DVF-00001', '2024-11-19 15:29:45', '2024-11-19 15:29:45', 1, 'direct_aadhar_details_all', 33.00, 2.97, 2.97, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00161/verification_report/DIR-00161.pdf', 4, 1, 1, 'ok=all'),
(72, 'DIR-00162', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 15:30:54', '2024-11-19 15:30:54', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(73, 'DIR-00164', 'APP-00001', 'AGN-00001', 'DVF-00002', '2024-11-19 15:31:50', '2024-11-19 15:31:50', 1, 'direct_pan_details_all', 17.00, 1.53, 1.53, 0, '', '', 0, 2, 1, ''),
(74, 'DIR-00166', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-19 15:35:13', '2024-11-19 15:35:13', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(75, 'DIR-00167', 'APP-00001', 'AGN-00001', 'DVF-00004', '2024-11-19 15:38:11', '2024-11-19 15:38:11', 1, 'direct_dl_details_all', 20.00, 1.80, 1.80, 0, '', '', 0, 2, 1, ''),
(76, 'DIR-00169', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-19 17:48:25', '2024-11-19 17:48:25', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00169/verification_report/DIR-00169.pdf', 5, 1, 1, 'name@Namrata Sunil Sharma!Salman,dob@1992-01-23!20-11-2024'),
(77, 'DIR-00174', 'APP-00001', 'AGN-00003', 'DVF-00002', '2024-11-19 17:58:17', '2024-11-19 17:58:17', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00174/verification_report/DIR-00174.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Khalim,'),
(78, 'DIR-00178', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-19 18:11:27', '2024-11-19 18:11:27', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00178/verification_report/DIR-00178.pdf', 5, 1, 1, 'name@Namrata Sunil Sharma!Sharukh,dob@1992-01-23!23-12-2021'),
(79, 'DIR-00179', 'APP-00001', 'AGN-00003', 'DVF-00002', '2024-11-19 18:12:33', '2024-11-19 18:12:33', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00179/verification_report/DIR-00179.pdf', 5, 1, 1, 'name@NAMRATA SUNIL SHARMA!Mohmmad,'),
(80, 'DIR-00180', 'APP-00001', 'AGN-00003', 'DVF-00002', '2024-11-19 18:19:20', '2024-11-19 18:19:20', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00180/verification_report/DIR-00180.pdf', 5, 1, 1, 'name@NAMRATA SUNIL SHARMA!Daya,'),
(81, 'DIR-00181', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-19 18:28:31', '2024-11-19 18:28:31', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00181/verification_report/DIR-00181.pdf', 5, 1, 1, 'name@Namrata Sunil Sharma!Badshah,dob@1992-01-23!23-02-3333'),
(82, 'DIR-00182', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-20 11:45:14', '2024-11-20 11:45:14', 2, 'direct_aadhar_details_all', 38.94, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00182/verification_report/DIR-00182.pdf', 5, 1, 1, 'name@Namrata Sunil Sharma!Salman,'),
(83, 'DIR-00200', 'APP-00001', 'AGN-00002', 'DVF-00008', '2024-11-20 15:14:53', '2024-11-20 15:14:53', 2, 'direct_aadhar_details_all', 0.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00200/verification_report/DIR-00200.pdf', 5, 1, 1, 'name@Abhijit Vishnu Sutar!Abhijit sutar,dob@2002-07-15!20-11-2024,address@,Kanur Bk.,kanur BK,Kolhap'),
(84, 'DIR-00208', 'APP-00001', 'AGN-00003', 'DVF-00008', '2024-11-21 11:58:58', '2024-11-21 11:58:58', 2, 'direct_aadhar_details_all', 2.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00208/verification_report/DIR-00208.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Gudiya,dob@1991-12-27!21-11-2024,address@S/O Pasha Mujawar,Khadgaon,A'),
(85, 'DIR-00209', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-21 12:05:28', '2024-11-21 12:05:28', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00209/verification_report/DIR-00209.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Gudiya,dob@1991-12-27!21-11-2024,address@S/O Pasha Mujawar,Khadgaon,A'),
(86, 'DIR-00210', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-21 12:19:40', '2024-11-21 12:19:40', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00210/verification_report/DIR-00210.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Gudiya,dob@1991-12-27!21-11-2024,'),
(87, 'DIR-00211', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-21 15:27:55', '2024-11-21 15:27:55', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00211/verification_report/DIR-00211.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!27-12-1992,address@S/O Pasha Mujawar,K'),
(88, 'DIR-00212', 'APP-00001', 'AGN-00002', 'DVF-00001', '2024-11-21 16:52:29', '2024-11-21 16:52:29', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00212/verification_report/DIR-00212.pdf', 5, 1, 1, 'name@Abhijit Vishnu Sutar!Ashish sutar,dob@2002-07-15!21-11-2024,address@,Kanur Bk.,kanur BK,Kolhapu'),
(89, 'DIR-00213', 'APP-00001', 'AGN-00002', 'DVF-00002', '2024-11-21 16:55:07', '2024-11-21 16:55:07', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00213/verification_report/DIR-00213.pdf', 5, 1, 1, 'name@ASHISH RAVINDRA KHANDARE!Ashish sutar,'),
(90, 'DIR-00214', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-22 15:17:21', '2024-11-22 15:17:21', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00214/verification_report/DIR-00214.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,K'),
(91, 'DIR-00215', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-22 15:25:09', '2024-11-22 15:25:09', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00215/verification_report/DIR-00215.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,K'),
(92, 'DIR-00219', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-22 16:56:42', '2024-11-22 16:56:42', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00219/verification_report/DIR-00219.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,K'),
(93, 'DIR-00220', 'APP-00001', 'AGN-00005', 'DVF-00001', '2024-11-22 16:59:49', '2024-11-22 16:59:49', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/DIR-00220/verification_report/DIR-00220.pdf', 5, 1, 1, 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,K'),
(94, 'DIR-00221', 'APP-00001', 'AGN-00002', 'DVF-00005', '2024-11-23 11:00:38', '2024-11-23 11:00:38', 2, 'direct_voter_details_all', 20.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00221/verification_report/DIR-00221.pdf', 5, 1, 1, 'name@KHAYUM MUJAWAR!Pallavi sutar,,guardian_name@PASHARAJAK MUJAWAR!pasha,polling_station@Gra. P. Ka'),
(95, 'DIR-00222', 'APP-00001', 'AGN-00002', 'DVF-00002', '2024-11-23 11:31:24', '2024-11-23 11:31:24', 2, 'direct_pan_details_all', 17.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00222/verification_report/DIR-00222.pdf', 5, 1, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Pallavi sutar,'),
(96, 'DIR-00226', 'APP-00001', 'AGN-00003', 'DVF-00001', '2024-11-24 00:12:31', '2024-11-24 00:12:31', 2, 'direct_aadhar_details_all', 33.00, 0.00, 0.00, 0, '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/DIR-00226/verification_report/DIR-00226.pdf', 5, 1, 1, 'name@Namrata Sunil Sharma!Nama,dob@1992-01-23!23-11-1992,address@,Navi Mumbai,Vashi,Thane,400703!Vas');

-- --------------------------------------------------------

--
-- Table structure for table `direct_voter_details_all`
--

CREATE TABLE `direct_voter_details_all` (
  `id` int(10) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `admin_id` varchar(25) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL,
  `voter_number` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/guardian name',
  `address` longtext NOT NULL,
  `polling_details` varchar(100) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1= OCR, 2= Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `direct_voter_details_all`
--

INSERT INTO `direct_voter_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `voter_number`, `name`, `dob`, `gender`, `father_name`, `address`, `polling_details`, `user_photo`, `front_photo`, `back_photo`, `generated_by`, `is_athenticate`) VALUES
(1, 'DIR-00042', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 12:40:48', '2024-11-18 12:40:48', 1, 'RSO1726777', 'ASHISH RAVINDRA KHANDARE', '2001-05-22', 'MALE', '', 'DR. AMBEDKAR VASTIGRUH, WADI BK, JALGAON JAMOD, JALGAON JAMOD, JALGAONJAMOD, BULDHANA, MAHARASHTRA- 443402', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00042/doc_photo/docs-673ae8774c5f5.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00042/doc_photo/docs-673ae877a3258.jpg', 1, 1),
(2, 'DIR-00055', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 12:55:09', '2024-11-18 12:55:09', 1, 'RCT8027781', 'AYUSH SAXENA', '0000-00-00', 'MALE', '', 'Chitalasar manpada', 'Cosmos Launch,  - 88', '', '', '', 2, 1),
(3, 'DIR-00057', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 13:05:03', '2024-11-18 13:05:03', 1, 'SMF7210420', 'AJIT MADHUKAR BODKHE', '0000-00-00', 'MALE', 'MADHUKAR MARUTI BODKHE', 'Vadzari Bu.', 'Zillha Parishad Primary School - 30', '', '', '', 2, 1),
(4, 'DIR-00059', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 13:17:09', '2024-11-18 13:17:09', 1, 'RCT80278R', 'AYUSH SAXENA', '1978-09-06', 'MALE', '', 'T-2/303, MOMING GLORY SOHAM GARDAN, CHITALSAR MANPADA, TEH. THANE, DIST- THANE, MH-400607', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00059/doc_photo/docs-673af0fc69178.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00059/doc_photo/docs-673af0fcbed0c.jpg', 1, 1),
(5, 'DIR-00135', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 13:19:06', '2024-11-19 13:19:06', 1, 'RSO1726777', 'KPUNDRA KHANDARE', '2001-05-22', '', '', 'DR. AMBEDKAR VASTIGRUH, WADI BK, JALGAON JAMOD, JALGAON JAMOD, JALGAONJAMOD, BULDHANA, MAHARASHTRA- 443402', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f18246a.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f1b7ea3.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f21c5d4.jpg', 1, 1),
(6, 'DIR-00148', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:19:31', '2024-11-19 14:19:31', 1, 'SRY2978914', 'KOMALPREET KAUR', '1996-05-28', 'FEMALE', '', 'H. NO. 16641-B, BASEMENT VIJAY NAGAR, H. NO. 10/2 TO 16824 G, BATHINDA, TEH-BATHINDA, DIST-BATHINDA-151003', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00148/doc_photo/docs-673c511a57c66.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00148/doc_photo/docs-673c511ab25a5.jpg', 1, 1),
(7, 'DIR-00149', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:20:31', '2024-11-19 14:20:31', 1, 'SMF6889919', 'SANKET BHAUSAHEB KANWADE', '0000-00-00', 'MALE', 'BHAUSAHEB KANWADE', 'Nimgaon Bu.', 'Zillha Parishad Primary School - 213', '', '', '', 2, 1),
(8, 'DIR-00159', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 14:36:51', '2024-11-19 14:36:51', 1, 'SMF7210420', 'AJIT MADHUKAR BODKHE', '0000-00-00', 'MALE', 'MADHUKAR MARUTI BODKHE', 'Vadzari Bu.', 'Zillha Parishad Primary School - 30', '', '', '', 2, 0),
(9, 'DIR-00221', 'APP-00001', 'AGN-00002', 'END-00687', '2024-11-23 11:00:38', '2024-11-23 11:00:38', 1, 'avb6205918', 'Pallavi sutar', '2024-11-16', 'male', '', 'latur', 'latur', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00221/doc_photo/docs-6741687e4f77a.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/DIR-00221/doc_photo/docs-6741687e77d02.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `disaster_header_all`
--

CREATE TABLE `disaster_header_all` (
  `id` int(11) NOT NULL,
  `disaster_value` varchar(10) NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_aadhar_details_all`
--

CREATE TABLE `edited_direct_aadhar_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `aadhar_number` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `edited_direct_aadhar_details_all`
--

INSERT INTO `edited_direct_aadhar_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `aadhar_number`, `name`, `dob`, `gender`, `address`, `front_photo`, `back_photo`, `user_photo`) VALUES
(1, 'DIR-00015', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-18 11:25:38', '2024-11-18 11:25:38', 2, '730642080992', 'ASHISH RAVINDRA KHAN', '0000-00-00', 'MALE', 'ADDRESS: AMBEDKAR VASTIGRUH DESHMUKH WADI WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00015/user_photo/DIR-00015.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00015/user_photo/DIR-00015.jpg', ''),
(2, 'DIR-00106', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:39:13', '2024-11-19 12:39:13', 1, '730642080992', 'ASHISH RAVINDRA KHAN', '0000-00-00', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00106/user_photo/DIR-00106.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00106/user_photo/DIR-00106.jpg', ''),
(3, 'DIR-00109', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:45:35', '2024-11-19 12:45:35', 1, '730642080992', 'ASHISH RAVINDRA KHAN', '0000-00-00', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00109/user_photo/DIR-00109.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00109/user_photo/DIR-00109.jpg', ''),
(4, 'DIR-00110', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:54:30', '2024-11-19 12:54:30', 1, '730642080992', 'ASHISH K', '0000-00-00', '', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443402', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00110/user_photo/DIR-00110.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00110/user_photo/DIR-00110.jpg', ''),
(5, 'DIR-00111', 'APP-00001', 'AGN-00001', 'AGN-00001', '2024-11-19 12:56:01', '2024-11-19 12:56:01', 2, '730642080992', 'SANKET', '0000-00-00', 'MALE', 'AMBEDKAR VASTIGRUH DESHMUKH WADI, WADI KHURD, JALGAON JAMOD, BULDANA, MAHARASHTRA, 443401', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00111/user_photo/DIR-00111.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00111/user_photo/DIR-00111.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_dl_details_all`
--

CREATE TABLE `edited_direct_dl_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `dl_number` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `father_name` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `date_of_issue` date NOT NULL,
  `classes_of_vehicle` varchar(20) NOT NULL COMMENT 'Classes of Vehicles(LMV,MCWG, etc)',
  `state_name` text NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_international_passport_details_all`
--

CREATE TABLE `edited_direct_international_passport_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_detail_photo` longtext NOT NULL,
  `back_detail_photo` longtext NOT NULL,
  `visa_photo` longtext NOT NULL,
  `landing_date` date NOT NULL,
  `visa_validity` date NOT NULL,
  `country` varchar(50) NOT NULL,
  `visa_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_pan_details_all`
--

CREATE TABLE `edited_direct_pan_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=1, verified=2, not verified=2',
  `pan_number` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/husband name',
  `dob` date NOT NULL,
  `user_photo` varchar(100) NOT NULL,
  `front_photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_passport_details_all`
--

CREATE TABLE `edited_direct_passport_details_all` (
  `id` int(11) NOT NULL,
  `direct_id` varchar(25) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `edited_direct_voter_details_all`
--

CREATE TABLE `edited_direct_voter_details_all` (
  `id` int(10) NOT NULL,
  `direct_id` varchar(10) NOT NULL,
  `application_id` varchar(10) NOT NULL,
  `agency_id` varchar(10) NOT NULL,
  `admin_id` varchar(10) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL,
  `voter_number` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/guardian name',
  `address` varchar(100) NOT NULL,
  `polling_details` varchar(100) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `edited_direct_voter_details_all`
--

INSERT INTO `edited_direct_voter_details_all` (`id`, `direct_id`, `application_id`, `agency_id`, `admin_id`, `initiated_on`, `completed_on`, `activity_status`, `voter_number`, `name`, `dob`, `gender`, `father_name`, `address`, `polling_details`, `user_photo`, `front_photo`, `back_photo`) VALUES
(1, 'DIR-00059', 'APP-00001', 'AGN-00001', '', '2024-11-18 13:17:09', '2024-11-18 13:17:09', 1, 'RCT8027781', 'AYUSH SAXENA', '1978-09-06', 'MALE', 'JIGYASA SHRIVASTAVA', 'T-2/303, MOMING GLORY SOHAM GARDAN, CHITALSAR MANPADA, TEH. THANE, DIST- THANE, MH-400607', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00059/doc_photo/docs-673af0fc69178.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00059/doc_photo/docs-673af0fcbed0c.jpg'),
(2, 'DIR-00135', 'APP-00001', 'AGN-00001', '', '2024-11-19 13:19:06', '2024-11-19 13:19:06', 1, 'RSO1726777', 'KPUNDRA KHANDARE', '2001-05-22', 'FEMALE', 'ASHISH KHANDAEW', 'DR. AMBEDKAR VASTIGRUH, WADI BK, JALGAON JAMOD, JALGAON JAMOD, JALGAONJAMOD, BULDHANA, MAHARASHTRA- ', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f18246a.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f1b7ea3.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00135/doc_photo/docs-673c42f21c5d4.jpg'),
(3, 'DIR-00148', 'APP-00001', 'AGN-00001', '', '2024-11-19 14:19:31', '2024-11-19 14:19:31', 1, 'SRY2978914', 'KOMAL PREET KAUR', '1996-05-28', 'FEMALE', 'CHARANJEET PAL SINGH', 'H. NO. 16641-B, BASEMENT VIJAY NAGAR, H. NO. 10/2 TO 16824 G, BATHINDA, TEH-BATHINDA, DIST-BATHINDA-', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00148/doc_photo/docs-673c511a57c66.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/DIR-00148/doc_photo/docs-673c511ab25a5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employee_header_all`
--

CREATE TABLE `employee_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `emp_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `emp_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0-inactive/1-active',
  `visitor_approval_required` int(11) NOT NULL COMMENT '0-no/1-yes',
  `visiting_charges` int(11) NOT NULL COMMENT '0-no/1-yes',
  `verification_paid_by` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'W-Wallet   E-end user',
  `inserted_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee_header_all`
--

INSERT INTO `employee_header_all` (`id`, `agency_id`, `emp_id`, `emp_code`, `name`, `contact`, `department`, `designation`, `email_id`, `status`, `visitor_approval_required`, `visiting_charges`, `verification_paid_by`, `inserted_on`) VALUES
(1, 'AGN-00001', 'VIEMP-00001', '', 'Ramesh Sharma', '9898989891', 'IT', 'PHP Developer', 'ramesh1@gmail.com', '1', 1, 0, 'W', '2024-11-14 18:44:35'),
(2, 'AGN-00001', 'VIEMP-00002', '', 'Suresh Sharma', '9898989892', 'IT', 'Sr. PHP Developer', 'suresh2@gmail.com', '1', 0, 1, 'W', '2024-11-14 18:44:35'),
(3, 'AGN-00001', 'VIEMP-00003', '', 'Ramesh Sharma', '9898989893', 'IT', 'PHP Developer', 'ramesh3@gmail.com', '1', 1, 1, 'W', '2024-11-14 18:44:35'),
(4, 'AGN-00001', 'VIEMP-00004', '', 'Ramesh Sharma', '9898989894', 'IT', 'PHP Developer', 'ramesh4@gmail.com', '1', 0, 0, 'W', '2024-11-14 18:44:35'),
(5, 'AGN-00001', 'VIEMP-00005', '', 'Ramesh Sharma', '9898989895', 'IT', 'PHP Developer', 'ramesh5@gmail.com', '0', 1, 1, 'E', '2024-11-14 18:44:35'),
(6, 'AGN-00002', 'VIEMP-00006', '', 'Ramesh Sharma', '9898989896', 'IT', 'PHP Developer', 'ramesh6@gmail.com', '1', 1, 0, 'E', '2024-11-15 10:48:01'),
(7, 'AGN-00001', 'VIEMP-00007', '', 'Suresh Sharma', '9898989897', 'IT', 'Sr. PHP Developer', 'suresh7@gmail.com', '1', 0, 1, 'E', '2024-11-15 10:48:01'),
(8, 'AGN-00001', 'VIEMP-00008', '', 'Ramesh Sharma', '9898989898', 'IT', 'PHP Developer', 'ramesh8@gmail.com', '1', 1, 1, 'E', '2024-11-15 10:48:01'),
(9, 'AGN-00001', 'VIEMP-00009', '', 'Ramesh Sharma', '9898989899', 'IT', 'PHP Developer', 'ramesh9@gmail.com', '1', 0, 0, 'E', '2024-11-15 10:48:01'),
(10, 'AGN-00001', 'VIEMP-00010', '', 'Ramesh Sharma', '9898989810', 'IT', 'PHP Developer', 'ramesh10@gmail.com', '1', 1, 1, 'E', '2024-11-15 10:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `end_user_payment_transaction_all`
--

CREATE TABLE `end_user_payment_transaction_all` (
  `id` int(11) NOT NULL,
  `end_user_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'entry in CSV if payment id paid for multiple user\r\neg: END-00001, END-00002, END-00003',
  `paid_transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `paid_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'end_user_id who has done this payment',
  `gateway_transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'from razorpay id',
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gst_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `paid_amount` float NOT NULL,
  `cgst_amount` float NOT NULL,
  `sgst_amount` float NOT NULL,
  `mi_amount` float NOT NULL COMMENT 'companies amount(micro integrated)',
  `mi_cgst_amount` float NOT NULL,
  `mi_sgst_amount` float NOT NULL,
  `agency_amount` float NOT NULL,
  `agency_cgst_amount` float NOT NULL,
  `agency_sgst_amount` float NOT NULL,
  `inserted_on` datetime NOT NULL,
  `bulk_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_url` longtext COLLATE utf8_unicode_ci NOT NULL,
  `verification_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'verification ids like DVF-00001, DVF-00002, etc',
  `payment_type` int(11) NOT NULL COMMENT '1=W, 2=E'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `end_user_payment_transaction_all`
--

INSERT INTO `end_user_payment_transaction_all` (`id`, `end_user_id`, `paid_transaction_id`, `paid_by`, `gateway_transaction_id`, `status`, `gst_number`, `paid_amount`, `cgst_amount`, `sgst_amount`, `mi_amount`, `mi_cgst_amount`, `mi_sgst_amount`, `agency_amount`, `agency_cgst_amount`, `agency_sgst_amount`, `inserted_on`, `bulk_id`, `invoice_url`, `verification_id`, `payment_type`) VALUES
(1, 'END-00607, END-00608', 'txn_1732170202703470', 'END-00608', 'pay_PNr2lXZrI8KWHf', 'paid', '', 259, 0, 0, 0, 0, 0, 0, 0, 0, '2024-11-21 11:53:22', 'BUL-00003', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00003/END-00608/invoice/END-00608-20241121115322.pdf', '', 0),
(2, 'END-00673', 'txn_1732183075491742', 'END-00673', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00006', '', 'DVF-00001', 1),
(3, 'END-00656', 'txn_1732185435841310', 'END-00656', 'pay_PNvMx3lYhlUQg8', 'paid', '', 120, 10.53, 10.53, 17, 1.53, 1.53, 82, 9, 9, '2024-11-21 16:07:15', 'BUL-00003', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00003/END-00656/invoice/END-00656-20241121160715.pdf', '', 0),
(4, 'END-00679, END-00680, END-00681', 'txn_1732187982540091', 'END-00681', 'pay_PNw5nQdih3YM6s', 'paid', '', 106, 8.1, 8.1, 90, 8.1, 8.1, 0, 0, 0, '2024-11-21 16:49:42', 'BUL-00007', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00681/invoice/END-00681-20241121164942.pdf', '', 0),
(5, 'END-00673', 'txn_1732268841340027', 'AGN-00005', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00006', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006//invoice/-20241122165949.pdf', 'DVF-00001', 1),
(6, 'END-00673', 'txn_1732269309812845', 'AGN-00005', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00006', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006//invoice/-20241122165949.pdf', 'DVF-00001', 1),
(7, 'END-00673', 'txn_1732274802227700', 'AGN-00005', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00006', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006//invoice/-20241122165949.pdf', 'DVF-00001', 1),
(8, 'END-00673', 'txn_1732274989565444', 'AGN-00005', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00006', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006//invoice/-20241122165949.pdf', 'DVF-00001', 1),
(9, 'END-00685', 'txn_1732284127111105', 'END-00687', 'pay_PONOSse8qgOqGB', 'paid', '', 38, 0, 0, 0, 0, 0, 0, 0, 0, '2024-11-22 19:32:07', 'BUL-00007', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00687/invoice/END-00687-20241122193207.pdf', '', 0),
(10, 'END-00723', 'txn_1732387351345680', 'AGN-00003', '', '', '', 38.94, 2.97, 2.97, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00015', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00015//invoice/-20241124001232.pdf', 'DVF-00001', 1),
(11, 'END-00730', 'txn_1732469076301131', 'AGN-00003', '', '', '', 2.36, 0.18, 0.18, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'BUL-00016', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00016//invoice/-20241124225436.pdf', 'DVF-00008', 1);

-- --------------------------------------------------------

--
-- Table structure for table `end_user_verification_transaction_all`
--

CREATE TABLE `end_user_verification_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `weblink_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `end_user_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aadhar_number` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `mobile_number` float NOT NULL,
  `pan_number` text COLLATE utf8_unicode_ci NOT NULL,
  `dl_number` text COLLATE utf8_unicode_ci NOT NULL,
  `voter_number` text COLLATE utf8_unicode_ci NOT NULL,
  `passport_number` text COLLATE utf8_unicode_ci NOT NULL,
  `aadhar_ambiguity` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'ok= all ok, else name=not match, address=not match, etc',
  `pan_ambiguity` longtext COLLATE utf8_unicode_ci NOT NULL,
  `dl_ambiguity` longtext COLLATE utf8_unicode_ci NOT NULL,
  `voter_ambiguity` longtext COLLATE utf8_unicode_ci NOT NULL,
  `passport_ambiguity` longtext COLLATE utf8_unicode_ci NOT NULL,
  `document_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=Aadhar DVF-00002=Pan DVF-00004=DL DVF-00005=Voter DVF-00006=Passport',
  `father_name` text COLLATE utf8_unicode_ci NOT NULL,
  `mother_name` text COLLATE utf8_unicode_ci NOT NULL,
  `spouse_name` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_ci NOT NULL,
  `dob` datetime NOT NULL,
  `blood_group` text COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `country_code` text COLLATE utf8_unicode_ci NOT NULL,
  `state_name` text COLLATE utf8_unicode_ci NOT NULL,
  `nationality` text COLLATE utf8_unicode_ci NOT NULL,
  `front_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `back_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `user_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `cover_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `date_of_issue` text COLLATE utf8_unicode_ci NOT NULL,
  `date_of_expiry` text COLLATE utf8_unicode_ci NOT NULL,
  `place_of_issue` text COLLATE utf8_unicode_ci NOT NULL,
  `classes_of_vehicle` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Classes of Vehicles(LMV,MCWG, etc)',
  `polling_details` text COLLATE utf8_unicode_ci NOT NULL,
  `republic_of_india` text COLLATE utf8_unicode_ci NOT NULL,
  `passport_type` text COLLATE utf8_unicode_ci NOT NULL,
  `file_number` text COLLATE utf8_unicode_ci NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `report_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=1, verified=2, not verified=2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `end_user_verification_transaction_all`
--

INSERT INTO `end_user_verification_transaction_all` (`id`, `agency_id`, `weblink_id`, `end_user_id`, `aadhar_number`, `first_name`, `last_name`, `mobile_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `aadhar_ambiguity`, `pan_ambiguity`, `dl_ambiguity`, `voter_ambiguity`, `passport_ambiguity`, `document_type`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `initiated_on`, `completed_on`, `report_url`, `activity_status`) VALUES
(1, 'AGN-00003', 'BUL-00003', 'END-00607', '546188549613', 'Gudiya', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Gudiya,dob@1991-12-27!21-11-2024,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!latur', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 12:05:28', '2024-11-21 12:05:28', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00003/END-00607/aadhar_report//END-00607.pdf', 2),
(2, 'AGN-00003', 'BUL-00003', 'END-00607', '546188549613', 'Gudiya', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Gudiya,dob@1991-12-27!21-11-2024,', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 12:19:40', '2024-11-21 12:19:40', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00003/END-00607/aadhar_report//END-00607.pdf', 2),
(3, 'AGN-00005', 'BUL-00006', 'END-00673', '546188549613', 'Hasnain Mujawar', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!27-12-1992,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!s/o pasha mujawar,khadgaon,latur,413531', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 15:27:55', '2024-11-21 15:27:55', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006/END-00673/aadhar_report//END-00673.pdf', 2),
(4, 'AGN-00002', 'BUL-00007', 'END-00679', '266923465264', 'Ashish sutar', '', 0, '', '', '', '', 'name@Abhijit Vishnu Sutar!Ashish sutar,dob@2002-07-15!21-11-2024,address@,Kanur Bk.,kanur BK,Kolhapur,416507!pune', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 16:52:29', '2024-11-21 16:52:29', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00679/aadhar_report//END-00679.pdf', 2),
(5, 'AGN-00002', 'BUL-00007', 'END-00680', '', 'Ashish sutar', '', 0, 'IOBPK5360E', '', '', '', '', 'name@ASHISH RAVINDRA KHANDARE!Ashish sutar,', '', '', '', 'DVF-00002', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 16:55:07', '2024-11-21 16:55:07', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00680/pan_report/END-00680.pdf', 2),
(6, 'AGN-00005', 'BUL-00006', 'END-00673', '546188549613', 'Hasnain Mujawar', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!latur', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-22 15:17:21', '2024-11-22 15:17:21', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006/END-00673/aadhar_report//END-00673.pdf', 2),
(7, 'AGN-00005', 'BUL-00006', 'END-00673', '546188549613', 'Hasnain Mujawar', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!latur', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-22 15:25:09', '2024-11-22 15:25:09', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006/END-00673/aadhar_report//END-00673.pdf', 2),
(8, 'AGN-00005', 'BUL-00006', 'END-00673', '546188549613', 'Hasnain Mujawar', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!latur', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-22 16:56:42', '2024-11-22 16:56:42', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006/END-00673/aadhar_report//END-00673.pdf', 2),
(9, 'AGN-00005', 'BUL-00006', 'END-00673', '546188549613', 'Hasnain Mujawar', '', 0, '', '', '', '', 'name@Khayum Pasharajak Mujawar!Hasnain Mujawar,dob@1991-12-27!22-11-2024,address@S/O Pasha Mujawar,Khadgaon,At Post Khadgaon,Latur,413531!latur', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-22 16:59:49', '2024-11-22 16:59:49', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/Weblink/BUL-00006/END-00673/aadhar_report//END-00673.pdf', 2),
(10, 'AGN-00003', 'BUL-00003', 'END-00656', '', 'Shyaam', '', 0, 'DRSPS2534B', '', '', '', '', 'name@Namrata Sunil Sharma!Shyaam,dob@1992-01-23!21-11-2014,', '', '', '', 'DVF-00002', '', '', '', '', '2092-11-23 00:14:40', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-21 12:19:40', '2024-11-21 12:19:40', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00003/END-00607/aadhar_report//END-00607.pdf', 2),
(11, 'AGN-00002', 'BUL-00007', 'END-00687', '', 'Pallavi sutar', '', 0, '', '', 'avb6205918', '', '', '', '', 'name@KHAYUM MUJAWAR!Pallavi sutar,,guardian_name@PASHARAJAK MUJAWAR!pasha,polling_station@Gra. P. Karyalay Anganwadi No. 2 - 37!latur', '', 'DVF-00005', 'pasha', '', '', 'male', '0000-00-00 00:00:00', '', 'latur', '', '', '', '', '', '', '', '', '', '', '', 'latur', '', '', '', '2024-11-23 11:00:38', '2024-11-23 11:00:38', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00687/voter_report/END-00687.pdf', 2),
(12, 'AGN-00002', 'BUL-00007', 'END-00687', '', 'Pallavi sutar', '', 0, 'BCFPM2764P', '', '', '', '', 'name@KHAYUM PASHARAJAK MUJAWAR!Pallavi sutar,', '', '', '', 'DVF-00002', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-23 11:31:24', '2024-11-23 11:31:24', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00002/Weblink/BUL-00007/END-00687/pan_report/END-00687.pdf', 2),
(13, 'AGN-00003', 'BUL-00015', 'END-00723', '423130569644', 'Nama', '', 0, '', '', '', '', 'name@Namrata Sunil Sharma!Nama,dob@1992-01-23!23-11-1992,address@,Navi Mumbai,Vashi,Thane,400703!Vashi', '', '', '', '', 'DVF-00001', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-24 00:12:31', '2024-11-24 00:12:31', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00015/END-00723/aadhar_report//END-00723.pdf', 2),
(14, 'AGN-00003', 'BUL-00016', 'END-00730', '', '', '', 9864570000, '', '', '', '', '', '', '', '', '', 'DVF-00008', '', '', '', '', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2024-11-24 22:54:25', '2024-11-24 22:54:25', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00003/Weblink/BUL-00016/END-00730/mob_report/END-00730.pdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `factory_setting_header_all`
--

CREATE TABLE `factory_setting_header_all` (
  `id` int(11) NOT NULL,
  `online_store` int(10) NOT NULL COMMENT 'ON/OFF',
  `digital_verification` int(10) NOT NULL COMMENT 'ON/OFF',
  `smart_watch_operations` int(10) NOT NULL COMMENT 'ON/OFF',
  `main_power_switch` int(10) NOT NULL COMMENT '1=ON/0=OFF, if it is off then user cannot login to app.',
  `power_switch_message` varchar(100) NOT NULL COMMENT 'reason for making application off.',
  `pause_switch` int(11) NOT NULL COMMENT '1=pause, 0=unpaused',
  `pause_message` varchar(100) NOT NULL COMMENT 'Pause message which user will see after registration',
  `maximum_permitted_user` int(10) NOT NULL DEFAULT '1' COMMENT 'no_of_users_permitted. Maximum user(agency) will be 4',
  `multiple_role_permitted` int(11) NOT NULL COMMENT '1=Yes/0=No',
  `maximum_no_allottment` int(100) NOT NULL COMMENT 'maximum number of role allotment can extend till 2-10',
  `max_permitted_admin` int(100) NOT NULL DEFAULT '1' COMMENT 'max 1-10 extra admin can allot... the original admin is the registered one with application',
  `alert_schedule` varchar(50) NOT NULL COMMENT 'if this col contain value it means alert schedule in ON....value store in minute How long from the current time should the alert be scheduled',
  `recording_schedule` int(11) NOT NULL COMMENT 'ON/OFF',
  `recording_chunk` varchar(50) NOT NULL COMMENT 'in minute',
  `sos` int(10) NOT NULL COMMENT 'ON/OFF',
  `sos_trigger_time` varchar(100) NOT NULL COMMENT 'when sos is generated then How long from message will send in second',
  `sos_time_limit` varchar(255) NOT NULL COMMENT 'in minute',
  `sos_recording_chunk` int(50) NOT NULL COMMENT 'in seconds',
  `sos_gps_location_auto_update` int(11) NOT NULL,
  `low_battery_alert1` longtext NOT NULL COMMENT 'battery_per@message1',
  `low_battery_alert2` longtext NOT NULL COMMENT 'battery_per@message',
  `factory_geo_fence_margin` int(11) NOT NULL COMMENT 'in distance',
  `geo_location_auto_update` int(11) NOT NULL COMMENT 'in minute',
  `manual_recording` int(11) NOT NULL COMMENT 'ON/OFF',
  `manual_recording_chunk` varchar(500) NOT NULL COMMENT 'recording in chunk duration',
  `manual_recording_maxi_time_limit` varchar(500) NOT NULL COMMENT 'maximum time duration for recording',
  `setting_update_time` datetime NOT NULL,
  `first_offer_amount` int(11) NOT NULL DEFAULT '0' COMMENT 'if there is a change in amount then current download count will be restore to 0',
  `first_offer_validity` datetime NOT NULL COMMENT 'if there is a change in validity then ask admin - do u want to reinitialize the current download count to 0 ?',
  `first_offer_count` int(11) NOT NULL DEFAULT '0',
  `first_current_downloads` int(5) NOT NULL,
  `first_offer_image` varchar(100) NOT NULL,
  `second_offer_amount` int(11) NOT NULL DEFAULT '0',
  `second_offer_validity` datetime NOT NULL,
  `second_offer_count` int(11) NOT NULL DEFAULT '0',
  `second_current_downloads` int(5) NOT NULL,
  `second_offer_image` varchar(100) NOT NULL,
  `default_welcome_image` varchar(100) NOT NULL,
  `daily_images` longtext NOT NULL COMMENT '1~image_1_url,2~imgae_2_url,3~image_url_3 (CSV)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `factory_setting_header_all`
--

INSERT INTO `factory_setting_header_all` (`id`, `online_store`, `digital_verification`, `smart_watch_operations`, `main_power_switch`, `power_switch_message`, `pause_switch`, `pause_message`, `maximum_permitted_user`, `multiple_role_permitted`, `maximum_no_allottment`, `max_permitted_admin`, `alert_schedule`, `recording_schedule`, `recording_chunk`, `sos`, `sos_trigger_time`, `sos_time_limit`, `sos_recording_chunk`, `sos_gps_location_auto_update`, `low_battery_alert1`, `low_battery_alert2`, `factory_geo_fence_margin`, `geo_location_auto_update`, `manual_recording`, `manual_recording_chunk`, `manual_recording_maxi_time_limit`, `setting_update_time`, `first_offer_amount`, `first_offer_validity`, `first_offer_count`, `first_current_downloads`, `first_offer_image`, `second_offer_amount`, `second_offer_validity`, `second_offer_count`, `second_current_downloads`, `second_offer_image`, `default_welcome_image`, `daily_images`) VALUES
(1, 1, 1, 1, 1, '', 0, '', 3, 1, 3, 5, '2', 0, '0', 1, '6', '2', 121, 0, '40', '19', 20, 0, 1, '183', '4', '2024-08-22 14:41:04', 2000, '2025-07-31 14:05:34', 200, 0, 'https://mounarchtech.com/vocoxp/setting/daily_image/daily1.jpeg', 2000, '2025-07-31 20:46:52', 200, 0, 'https://mounarchtech.com/vocoxp/setting/daily_image/daily2.jpeg', 'https://mounarchtech.com/vocoxp/setting/welcome_image/1723399932_e1d4ddb69efeabffa9af.jpeg', '1~https://mounarchtech.com/vocoxp/setting/daily_image/daily1.jpeg,2~https://mounarchtech.com/vocoxp/setting/daily_image/daily2.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `form_contract_details_all`
--

CREATE TABLE `form_contract_details_all` (
  `id` int(10) NOT NULL,
  `from_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `process_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contract_link` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `contract_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `line_number` int(11) NOT NULL,
  `contract_file` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `form_contract_details_all`
--

INSERT INTO `form_contract_details_all` (`id`, `from_id`, `process_id`, `contract_link`, `status`, `from_date`, `to_date`, `contract_id`, `line_number`, `contract_file`) VALUES
(1, 'F_SIN_001', 'P_SIN_0001', 'https://mounarchtech.com/vocoxp/terms_&_condition.php', 1, '2024-04-17', '2024-04-30', 'C-123456', 0, ''),
(2, 'F_SIN_001', 'P_SIN_0002', 'https://mounarchtech.com/vocoxp/terms_&_condition.php', 1, '2024-04-17', '2024-04-30', 'C-223456', 0, ''),
(3, 'F_SIN_001', 'P_DIG_0001', 'https://mounarchtech.com/vocoxp/terms_&_condition.php', 1, '2024-04-17', '2024-04-30', 'C-253490', 0, ''),
(7, 'F_SIN_001', 'P_WAR_0001', 'https://mounarchtech.com/vocoxp/terms_&_condition.php', 1, '2024-04-17', '2024-04-30', 'C-253461', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `form_process_header_all`
--

CREATE TABLE `form_process_header_all` (
  `id` int(10) NOT NULL,
  `form_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'form_id',
  `process_ids` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV of process_ids',
  `status` int(20) NOT NULL COMMENT '1=active/0=deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `form_process_header_all`
--

INSERT INTO `form_process_header_all` (`id`, `form_id`, `process_ids`, `status`) VALUES
(1, 'F_MAN_001', 'P_MAN_0001,P_MAN_0002,P_MAN_0003,P_MAN_0004,P_MAN_0005,P_MAN_0006,P_MAN_0007,P_MAN_0008,P_MAN_0009,P_MAN_0010,P_MAN_0011,P_MAN_0013,P_MAN_0012,P_MAN_0017', 1),
(2, 'F_WAL_001', 'P_WAL_0001', 1),
(3, 'F_WAR_001', 'P_WAR_0001', 1),
(4, 'F_SET_001', 'P_SET_0001', 1),
(5, 'F_MEM_003', 'P_MEM_0009', 1),
(6, 'F_VER_001', '', 1),
(7, 'F_DIG_001', 'P_DIG_0002', 1),
(8, 'F_DIG_002', '', 1),
(9, 'F_TIH_001', 'P_TIH_0001', 1),
(10, 'F_MEM_001', 'P_MEM_0001,P_MEM_0002,P_MEM_0003,P_MEM_0004,P_MEM_0005,P_MEM_0006,P_MEM_0007,P_MEM_0010,P_MEM_0010,P_MEM_0011', 1),
(11, 'F_MEM_002', 'P_MEM_0008', 1),
(12, 'F_MEM_005', 'P_MEM_0012', 1),
(13, 'F_PRO_001', 'P_PRO_0001,P_PRO_0002', 1),
(14, 'F_MBM_001', 'P_MBM_0001', 1),
(15, 'F_DRV_001', 'P_MAN_0014', 1),
(16, 'F_SCM_001', 'P_MAN_0016', 1),
(17, 'F_HRM_001', 'P_MAN_0015', 1);

-- --------------------------------------------------------

--
-- Table structure for table `help_and_support_category_header_all`
--

CREATE TABLE `help_and_support_category_header_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(255) NOT NULL,
  `application_name` varchar(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL COMMENT '0-active 1-deactive',
  `create_on` datetime NOT NULL,
  `row_for` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `help_and_support_category_header_all`
--

INSERT INTO `help_and_support_category_header_all` (`id`, `application_id`, `application_name`, `cat_id`, `category_name`, `status`, `create_on`, `row_for`) VALUES
(21, 'Thir_8160', 'Third party', '', '', '0', '2022-09-30 11:05:51', 'application'),
(24, 'Shli_1556', 'Shlite', '', '', '1', '2022-10-07 11:53:24', 'application'),
(27, 'Secu_9245', 'Security Agency', '', '', '0', '2022-10-08 14:11:38', 'application'),
(32, 'Secr_3488', 'SecratoryPanel', '', '', '0', '2022-10-31 14:43:38', 'application'),
(34, 'Voco_5454', 'Premisafe', '', '', '0', '2022-10-31 15:00:47', 'application'),
(37, 'sale_5661', 'salesmanager', '', '', '0', '2022-11-08 23:01:26', 'application'),
(39, 'Voco_5454', 'Premisafe', 'My F_2845', 'My Family', '0', '2022-11-25 18:49:57', 'category'),
(40, 'Voco_5454', 'Premisafe', 'Visi_3041', 'Visitor List', '0', '2022-11-25 18:51:03', 'category'),
(41, 'Voco_5454', 'Premisafe', 'Trac_7489', 'Track Family', '0', '2022-11-25 18:51:03', 'category'),
(42, 'Secr_3488', 'SecratoryPanel', 'Mana_7903', 'Manage Staff', '0', '2022-11-25 19:00:08', 'category'),
(43, 'Secr_3488', 'SecratoryPanel', 'Conf_6110', 'Configure Device', '0', '2022-11-25 19:00:49', 'category'),
(44, 'sale_5661', 'salesmanager', 'Adve_3956', 'Advertisement', '1', '2022-11-25 19:08:32', 'category'),
(45, 'Secu_9245', 'Security Agency', 'Guar_6935', 'Guard Management ', '0', '2022-12-12 17:01:02', 'category');

-- --------------------------------------------------------

--
-- Table structure for table `help_and_support_generate_ticket`
--

CREATE TABLE `help_and_support_generate_ticket` (
  `id` int(11) NOT NULL,
  `application_id` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `issue_id` varchar(255) NOT NULL,
  `ticket_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `society_id` varchar(50) NOT NULL,
  `user_type` varchar(1000) NOT NULL,
  `chat1` varchar(1000) NOT NULL COMMENT '0-ticket initiate datetime / 1 reply-answer-datetime 15limit for column',
  `chat2` varchar(1000) NOT NULL,
  `status` varchar(255) NOT NULL COMMENT '0-active 1-deactive',
  `generate_date_time` datetime NOT NULL,
  `last_action_date_time` datetime NOT NULL,
  `taken_by_action` varchar(1000) DEFAULT NULL,
  `request_city_id` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `help_and_support_generate_ticket`
--

INSERT INTO `help_and_support_generate_ticket` (`id`, `application_id`, `category_id`, `issue_id`, `ticket_id`, `user_id`, `society_id`, `user_type`, `chat1`, `chat2`, `status`, `generate_date_time`, `last_action_date_time`, `taken_by_action`, `request_city_id`) VALUES
(1, 'Voco_5454', 'My F_2845', 'How _3441', '74713', 'J P _beeb', 'MIT_d4f9', 'resident', '0#NA#2023-08-29 14:37:24,0#VC g in#2023-08-29 14:37:55', '', 'Ticket close', '2023-08-29 14:37:24', '2023-08-29 14:38:00', '', 'Pun_9278'),
(2, 'Voco_5454', 'My F_2845', '', '78964', 'J P _beeb', 'MIT_d4f9', 'resident', '0#No add family#2023-08-29 14:39:09', '', 'pending', '2023-08-29 14:39:09', '2023-08-29 14:39:09', '', 'Pun_9278'),
(3, 'Voco_5454', 'My F_2845', '', '71607', 'J P _beeb', 'MIT_d4f9', 'resident', '0#Not#2023-08-29 15:06:08,0#done#2023-08-29 15:06:17', '', 'Ticket close', '2023-08-29 15:06:08', '2023-08-29 15:06:25', '', 'Pun_9278'),
(4, 'Secr_3488', 'Mana_7903', '', '83942', 'J P _beeb', 'MIT_d4f9', 'secretary', '0#how to manage staff#2023-08-29 16:41:39', '', 'pending', '2023-08-29 16:41:39', '2023-08-29 16:41:39', '', 'Pun_9278'),
(5, 'Voco_5454', 'Trac_7489', '', '18930', 'Gau_89ew', 'MIT_d4f9', 'resident', '0#n#2023-09-21 14:34:32', '', 'pending', '2023-09-21 14:34:32', '2023-09-21 14:34:32', '', 'Pun_9278');

-- --------------------------------------------------------

--
-- Table structure for table `help_and_support_issue_header_all`
--

CREATE TABLE `help_and_support_issue_header_all` (
  `id` int(11) NOT NULL,
  `application_id` varchar(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `issue_id` varchar(355) NOT NULL,
  `issue_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-active 1-deactive',
  `created_on` date NOT NULL,
  `general_issues` varchar(1000) NOT NULL,
  `row_for` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `help_and_support_issue_header_all`
--

INSERT INTO `help_and_support_issue_header_all` (`id`, `application_id`, `cat_id`, `issue_id`, `issue_name`, `status`, `created_on`, `general_issues`, `row_for`) VALUES
(1, 'Voco_5454', 'My F_2845', 'How _3441', 'How to add family member?', 0, '2022-11-25', '', 'issue'),
(2, 'Voco_5454', 'My F_2845', 'How _3441', 'How to add family member?', 0, '2022-11-25', 'On the click of My Family module user can see Owner info i.e Owner name, mobile number, profile image & Priority as head of the family', 'general_answer'),
(3, 'Voco_5454', 'My F_2845', 'How _3441', 'How to add family member?', 0, '2022-11-25', 'Click on + logo and enter details of the member', 'general_answer'),
(4, 'Voco_5454', 'My F_2845', 'How _3441', 'How to add family member?', 0, '2022-11-25', 'click of Add button, we can see poup as added successful & it displays list of family members.', 'general_answer'),
(5, 'Voco_5454', 'My F_2845', 'How _3441', 'How to add family member?', 0, '2022-11-25', 'Credentials of login will be send on members mobile number entered.', 'general_answer'),
(6, 'Voco_5454', 'Visi_3041', 'What_5585', 'What is Visitor list For?', 0, '2022-11-25', '', 'issue'),
(7, 'Voco_5454', 'Visi_3041', 'What_5585', 'What is Visitor list For?', 0, '2022-11-25', 'When guard add new visitor from guard application then notification goes in the visitor list for approval.', 'general_answer'),
(8, 'Voco_5454', 'Visi_3041', 'What_5585', 'What is Visitor list For?', 0, '2022-11-25', 'Resident can allow visitor by using allow button', 'general_answer'),
(9, 'Voco_5454', 'Visi_3041', 'What_5585', 'What is Visitor list For?', 0, '2022-11-25', 'resident can also see previous report of unknown, invited guest & delivery by search date', 'general_answer'),
(10, 'Voco_5454', 'Trac_7489', 'What_9769', 'What is Track Family Module?', 0, '2022-11-25', '', 'issue'),
(11, 'Voco_5454', 'Trac_7489', 'What_9769', 'What is Track Family Module?', 0, '2022-11-25', 'Resident family member list is shown in Track family Module', 'general_answer'),
(12, 'Voco_5454', 'Trac_7489', 'What_9769', 'What is Track Family Module?', 0, '2022-11-25', 'On click on Particular Family member from the list and Map screen is displayed with option for multiple points to get location', 'general_answer'),
(13, 'Voco_5454', 'Trac_7489', 'What_9769', 'What is Track Family Module?', 0, '2022-11-25', 'According to your requirement you can see last 10 ,20 points of the member', 'general_answer'),
(14, 'Secr_3488', 'Mana_7903', 'What_3128', 'What is Manage Staff module used For?', 0, '2022-11-25', '', 'issue'),
(15, 'Secr_3488', 'Mana_7903', 'What_3128', 'What is Manage Staff module used For?', 0, '2022-11-25', 'By this module we can add support staff,guard ,helper ', 'general_answer'),
(16, 'Secr_3488', 'Mana_7903', 'What_3128', 'What is Manage Staff module used For?', 0, '2022-11-25', 'in Helper ,On right corner by click we can approve request.', 'general_answer'),
(17, 'Secr_3488', 'Conf_6110', 'What_2008', 'What is Configure Device module?', 0, '2022-11-25', '', 'issue'),
(18, 'Secr_3488', 'Conf_6110', 'What_2008', 'What is Configure Device module?', 0, '2022-11-25', 'click on plus logo user can add guard device name .', 'general_answer'),
(19, 'Secr_3488', 'Conf_6110', 'What_2008', 'What is Configure Device module?', 0, '2022-11-25', 'Device Id id generated and is visible in list use to login in guard app', 'general_answer'),
(20, 'sale_5661', 'Adve_3956', 'How _2553', 'How to register Shopkeeper,Builder,CityWise?', 0, '2022-11-25', '', 'issue'),
(21, 'sale_5661', 'Adve_3956', 'How _2553', 'How to register Shopkeeper,Builder,CityWise?', 0, '2022-11-25', 'it open a dialog of (Select Advertiser type) according to selection ', 'general_answer'),
(22, 'sale_5661', 'Adve_3956', 'How _2553', 'How to register Shopkeeper,Builder,CityWise?', 0, '2022-11-25', 'When click on map image it will move to map page and when select a marker it show save button in bottom of map and when click on save button it show address details and pin code . when click on ok it return back to form and fill map address into a Enter address field this field is also editable than add a images After All the fields are fill than click on submit button it register a shopkeeper and show a toast of shopkeeper successfully register.', 'general_answer'),
(23, 'Secu_9245', 'Guar_6935', 'How _6367', 'How To add Gurad ?', 0, '2022-12-12', '', 'issue'),
(24, 'Secu_9245', 'Guar_6935', 'How _6367', 'How To add Gurad ?', 0, '2022-12-12', 'Click on + logo and enter details of the Gurad', 'general_answer'),
(25, 'Secu_9245', 'Guar_6935', 'How _6367', 'How To add Gurad ?', 0, '2022-12-12', 'Click on + logo and enter details of the Guard', 'general_answer');

-- --------------------------------------------------------

--
-- Table structure for table `help_and_support_videos_all`
--

CREATE TABLE `help_and_support_videos_all` (
  `id` int(10) NOT NULL,
  `video_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `video_url` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 active 2 inactive',
  `inserted_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `help_and_support_videos_all`
--

INSERT INTO `help_and_support_videos_all` (`id`, `video_id`, `title`, `description`, `video_url`, `status`, `inserted_on`) VALUES
(1, 'VID-00001', 'how to check status ?', 'check UI and feel', 'https://mounarchtech.com/vocoxp/help_videos/vid1.mp4', '1', '2024-07-16 09:16:33'),
(2, 'VID-00001', 'how to create employee ?', 'employee management', 'https://mounarchtech.com/vocoxp/help_videos/vid2.mp4', '1', '2024-07-16 09:16:33'),
(3, 'VID-00001', 'how to create agency ?', 'agency setting', 'https://mounarchtech.com/vocoxp/help_videos/vid3.mp4', '1', '2024-07-16 09:16:33'),
(4, 'VID-00001', 'how to use bulk weblink ?', 'agency setting', 'https://www.youtube.com/watch?v=98fmYx6abVE', '1', '2024-07-16 09:16:33'),
(5, 'VID-00001', 'how to use generate bulk weblink ?', 'agency setting', 'https://youtu.be/7CrvOYJ7EW4', '1', '2024-07-16 09:16:33'),
(6, 'VID-00001', 'How Aadhar Verification Works ?', 'agency setting', 'https://youtu.be/MrrKAOg1ka4', '1', '2024-07-16 09:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `instant_messaging_detail_all`
--

CREATE TABLE `instant_messaging_detail_all` (
  `id` int(11) NOT NULL,
  `comm_id` varchar(50) NOT NULL,
  `sender_id` varchar(100) NOT NULL,
  `mode` int(11) NOT NULL COMMENT '1=A-W/0=W-W',
  `message_voice` varchar(200) NOT NULL,
  `group_id` longtext NOT NULL COMMENT 'if blank then it will be W_W communication or individual',
  `total_member` longtext NOT NULL COMMENT 'member_id in CSV format	',
  `total_receiver_member` longtext NOT NULL COMMENT 'member_id in CSV format',
  `acknowledge_member` longtext NOT NULL COMMENT 'member_id=datetime!no of repeation@(all body parameter)',
  `not_acknowledge_member` longtext NOT NULL COMMENT 'member_id=datetime@(all body parameter)',
  `no_of_repeatation` int(11) NOT NULL COMMENT 'optional',
  `generated_on` datetime NOT NULL,
  `unreachable` longtext NOT NULL COMMENT 'if any member not received msg (member_id in csv)',
  `level` int(11) NOT NULL COMMENT '1.sos 2.normal msg',
  `generated_with_groups` longtext NOT NULL COMMENT 'if msg generated for multiple groups (group_id csv)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `internal_mgmt_table`
--

CREATE TABLE `internal_mgmt_table` (
  `id` int(10) NOT NULL,
  `table_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `reserve_row_count` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hold_row_count` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'process will be stopped when current_count reaches equal to this column',
  `current_row_count` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `alert_generated_details` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'datetime=R/B CSV... R=Reserve/B=block',
  `alert_contacts` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV',
  `alert_emails` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manual_recording_archive_all`
--

CREATE TABLE `manual_recording_archive_all` (
  `id` int(11) NOT NULL DEFAULT '0',
  `manual_recording_id` varchar(100) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `gps_location` varchar(100) NOT NULL COMMENT 'lattitude,logitude',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `stop_time` time NOT NULL,
  `audio_file` longtext NOT NULL COMMENT 'start_time@stop_time@file_path,start_time@stop_time@file_path.........'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manual_recording_header_all`
--

CREATE TABLE `manual_recording_header_all` (
  `id` int(11) NOT NULL,
  `manual_recording_id` varchar(100) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `gps_location` varchar(100) NOT NULL COMMENT 'lattitude,logitude',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `stop_time` time NOT NULL,
  `audio_file` longtext NOT NULL COMMENT 'start_time@stop_time@file_path,start_time@stop_time@file_path.........'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_contract_combination_all`
--

CREATE TABLE `member_contract_combination_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `member_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contract_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contract_accepted_on` datetime NOT NULL,
  `acceptance_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `member_contract_combination_all`
--

INSERT INTO `member_contract_combination_all` (`id`, `agency_id`, `member_id`, `contract_id`, `contract_accepted_on`, `acceptance_no`) VALUES
(1, 'AGN-00001', '', 'C-123456', '2024-11-14 16:06:18', '1'),
(2, 'AGN-00001', '', 'C-223456', '2024-11-14 16:06:18', '1'),
(3, 'AGN-00001', '', 'C-253490', '2024-11-14 16:06:18', '1'),
(4, 'AGN-00001', '', 'C-253461', '2024-11-14 16:06:18', '1'),
(5, 'AGN-00002', '', 'C-123456', '2024-11-16 10:20:29', '1'),
(6, 'AGN-00002', '', 'C-223456', '2024-11-16 10:20:29', '1'),
(7, 'AGN-00002', '', 'C-253490', '2024-11-16 10:20:29', '1'),
(8, 'AGN-00002', '', 'C-253461', '2024-11-16 10:20:29', '1'),
(9, 'AGN-00003', '', 'C-123456', '2024-11-16 16:43:28', '1'),
(10, 'AGN-00003', '', 'C-223456', '2024-11-16 16:43:28', '1'),
(11, 'AGN-00003', '', 'C-253490', '2024-11-16 16:43:28', '1'),
(12, 'AGN-00003', '', 'C-253461', '2024-11-16 16:43:28', '1'),
(13, 'AGN-00004', '', 'C-123456', '2024-11-17 23:59:22', '1'),
(14, 'AGN-00004', '', 'C-223456', '2024-11-17 23:59:22', '1'),
(15, 'AGN-00004', '', 'C-253490', '2024-11-17 23:59:22', '1'),
(16, 'AGN-00004', '', 'C-253461', '2024-11-17 23:59:22', '1'),
(17, 'AGN-00005', '', 'C-123456', '2024-11-19 12:48:02', '1'),
(18, 'AGN-00005', '', 'C-223456', '2024-11-19 12:48:02', '1'),
(19, 'AGN-00005', '', 'C-253490', '2024-11-19 12:48:02', '1'),
(20, 'AGN-00005', '', 'C-253461', '2024-11-19 12:48:02', '1'),
(21, 'AGN-00006', '', 'C-123456', '2024-11-22 17:55:17', '1'),
(22, 'AGN-00006', '', 'C-223456', '2024-11-22 17:55:17', '1'),
(23, 'AGN-00006', '', 'C-253490', '2024-11-22 17:55:17', '1'),
(24, 'AGN-00006', '', 'C-253461', '2024-11-22 17:55:17', '1');

-- --------------------------------------------------------

--
-- Table structure for table `member_header_all`
--

CREATE TABLE `member_header_all` (
  `id` int(11) NOT NULL,
  `registration_id` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `member_id` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `contact_no` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `email_id` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `dob_or_doj` date NOT NULL COMMENT 'if employee then date of joining and if family member then date of birth',
  `gender` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `city` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `address` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `relation` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `designation` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `type` int(10) NOT NULL COMMENT '1=member/0=employee',
  `employee_type` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `duty_type` int(10) NOT NULL COMMENT '1=full time/0= custom',
  `duty_applicable_from` datetime NOT NULL,
  `duty_time` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `is_admin` int(10) NOT NULL COMMENT '1=Y/0=N',
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `profile_image` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `is_watch_wear` int(10) NOT NULL COMMENT '1=Yes/0=No	',
  `geo_fancing_margin` int(11) NOT NULL COMMENT 'if yes then insert distance	',
  `manual_recording` varchar(100) NOT NULL COMMENT 'if yes then insert value in minute	',
  `e_crime_reminder` int(11) NOT NULL COMMENT 'if yes then insert value in days	',
  `cibil_reminder` int(11) NOT NULL COMMENT 'if yes then insert value in days	',
  `sos` int(10) NOT NULL COMMENT '1=ON/0=OFF',
  `sos_details` longtext NOT NULL COMMENT 'name=mobile,name=mobile.....',
  `member_setting_set` int(10) NOT NULL COMMENT '1=Y/0=N',
  `universal_report` varchar(500) NOT NULL,
  `web_link` varchar(100) NOT NULL COMMENT 'weblink_number=weblink, eg:\r\nWEB_00001=weblink',
  `web_link_type` int(11) NOT NULL COMMENT '1=details_only\r\n2=with verification',
  `web_link_status` int(11) NOT NULL COMMENT '0=weblink initiated, 1= weblink opened, 2= weblink completed, 3= cancelled',
  `web_link_date` datetime NOT NULL COMMENT 'active only till 24hrs',
  `web_link_verifications` text NOT NULL COMMENT 'CSV if verification is aadhar,pan then data is(1,2) here\r\n1= Aadhar\r\n2= Pan\r\n3= Voter\r\n4= DL\r\n5= Indian Passport\r\n6= international Passport\r\n7= Crime check',
  `web_link_amount_detail` text NOT NULL COMMENT '1(Aadhar) = base amount + tax@ transaction_id, 2 (PAN) = base amount + tax@ transaction_id,etc',
  `web_link_total_amount` varchar(100) NOT NULL,
  `web_link_actual_amount` varchar(100) NOT NULL COMMENT '1(aadhar) = total rs, etc',
  `completed_verifications` text NOT NULL COMMENT 'CSV if verification is done, aadhar,pan then data is(1,2) here 1= Aadhar 2= Pan 3= Voter 4= DL 5= Indian Passport 6= international Passport 7= Crime check\r\neg: 1@date&time,5@date&time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_header_all`
--

INSERT INTO `member_header_all` (`id`, `registration_id`, `member_id`, `name`, `contact_no`, `email_id`, `dob_or_doj`, `gender`, `city`, `address`, `relation`, `designation`, `type`, `employee_type`, `duty_type`, `duty_applicable_from`, `duty_time`, `is_admin`, `status`, `profile_image`, `created_on`, `updated_on`, `is_watch_wear`, `geo_fancing_margin`, `manual_recording`, `e_crime_reminder`, `cibil_reminder`, `sos`, `sos_details`, `member_setting_set`, `universal_report`, `web_link`, `web_link_type`, `web_link_status`, `web_link_date`, `web_link_verifications`, `web_link_amount_detail`, `web_link_total_amount`, `web_link_actual_amount`, `completed_verifications`) VALUES
(3, 'AGN-00001', 'MEM-00003', 'Niraj kumar', '8109471732', 'niraj07.k@gmail.com', '2024-11-18', '', 'Pune', 'Swargate, Pune', '', 'Android Developer ', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, '', '2024-11-18 16:43:54', '0000-00-00 00:00:00', 0, 0, '', 0, 0, 0, '', 0, '', '', 0, 0, '0000-00-00 00:00:00', '', '', '', '', ''),
(4, 'AGN-00001', 'MEM-00004', 'Ashish Khandare', '9011420559', 'ashish.and.miscos@gmail.com', '0000-00-00', '', '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, '', '2024-11-19 18:12:49', '0000-00-00 00:00:00', 0, 0, '', 0, 0, 0, '', 0, '', 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=TUVNLTAwMDA0fDE5LTExLTIwMjQgMT', 1, 0, '2024-11-19 18:12:50', '', '', '', '', ''),
(5, 'AGN-00001', 'MEM-00005', 'Ashish Kanna', '9922713514', 'ashish.and.miscos@gmail.com', '0000-00-00', '', '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, '', '2024-11-21 11:27:16', '0000-00-00 00:00:00', 0, 0, '', 0, 0, 0, '', 0, '', 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=TUVNLTAwMDA1fDIxLTExLTIwMjQgMT', 1, 0, '2024-11-21 11:27:17', '', '', '', '', ''),
(6, 'AGN-00002', 'MEM-00006', 'Abhijeet', '9011420559', 'akashbhive093@gmail.com', '0000-00-00', '', '', '', '', '', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, '', '2024-11-21 11:29:40', '0000-00-00 00:00:00', 0, 0, '', 0, 0, 0, '', 0, '', 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=TUVNLTAwMDA2fDIxLTExLTIwMjQgMT', 1, 0, '2024-11-21 11:29:41', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `member_header_archive_all`
--

CREATE TABLE `member_header_archive_all` (
  `id` int(11) NOT NULL,
  `registration_id` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `member_id` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `contact_no` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `email_id` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `dob_or_doj` date NOT NULL COMMENT 'if employee then date of joining and if family member then date of birth',
  `gender` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `city` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `address` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `relation` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `designation` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `type` int(10) NOT NULL COMMENT '1=member/0=employee',
  `employee_type` varchar(25) CHARACTER SET utf8mb4 NOT NULL,
  `duty_type` int(10) NOT NULL COMMENT '1=full time/0= custom',
  `duty_applicable_from` datetime NOT NULL,
  `duty_time` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `is_admin` int(10) NOT NULL COMMENT '1=Y/0=N',
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `profile_image` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `is_watch_wear` int(10) NOT NULL COMMENT '1=Yes/0=No	',
  `geo_fancing_margin` int(11) NOT NULL COMMENT 'if yes then insert distance	',
  `manual_recording` varchar(100) NOT NULL COMMENT 'if yes then insert value in minute	',
  `e_crime_reminder` int(11) NOT NULL COMMENT 'if yes then insert value in days	',
  `cibil_reminder` int(11) NOT NULL COMMENT 'if yes then insert value in days	',
  `sos` int(10) NOT NULL COMMENT '1=ON/0=OFF',
  `sos_details` longtext NOT NULL COMMENT 'name=mobile,name=mobile.....',
  `member_setting_set` int(10) NOT NULL COMMENT '1=Y/0=N',
  `universal_report` varchar(500) NOT NULL,
  `web_link` varchar(100) NOT NULL,
  `web_link_type` int(11) NOT NULL COMMENT '1=details_only\n2=with verification',
  `web_link_status` int(11) NOT NULL COMMENT '0=weblink initiated, 1= weblink opened, 2= weblink completed, 3= cancelled',
  `web_link_date` datetime NOT NULL COMMENT 'active only till 24hrs',
  `web_link_verifications` text NOT NULL COMMENT 'CSV if verification is aadhar,pan then data is(1,2) here\n1= Aadhar\n2= Pan\n3= Voter\n4= DL\n5= Indian Passport\n6= international Passport\n7= Crime check',
  `web_link_amount_detail` text NOT NULL COMMENT '1(Aadhar) = base amount + tax@ transaction_id, 2 (PAN) = base amount + tax@ transaction_id,etc',
  `web_link_total_amount` varchar(100) NOT NULL,
  `web_link_actual_amount` varchar(100) NOT NULL COMMENT '1(aadhar) = total rs, etc',
  `completed_verifications` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_header_archive_all`
--

INSERT INTO `member_header_archive_all` (`id`, `registration_id`, `member_id`, `name`, `contact_no`, `email_id`, `dob_or_doj`, `gender`, `city`, `address`, `relation`, `designation`, `type`, `employee_type`, `duty_type`, `duty_applicable_from`, `duty_time`, `is_admin`, `status`, `profile_image`, `created_on`, `updated_on`, `is_watch_wear`, `geo_fancing_margin`, `manual_recording`, `e_crime_reminder`, `cibil_reminder`, `sos`, `sos_details`, `member_setting_set`, `universal_report`, `web_link`, `web_link_type`, `web_link_status`, `web_link_date`, `web_link_verifications`, `web_link_amount_detail`, `web_link_total_amount`, `web_link_actual_amount`, `completed_verifications`) VALUES
(1, 'AGN-00001', 'MEM-00001', 'Namrata Sharma', '9820898379', 'ashish.and.miscos@gmail.com', '2024-03-01', '', 'Vashi', 'Vashi', '', 'Senior PHP Developer', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, 'https://mounarchtech.com/vocoxp/active_folder/agency/member/profile_picture/6735d97105d01.png', '2024-11-14 16:14:14', '2024-11-15 17:37:40', 0, 0, '', 0, 0, 0, '', 0, '', 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=TUVNLTAwMDAxfDE0LTExLTIwMjQgMT', 2, 1, '2024-11-14 16:30:01', '1,2', '', '', '', ''),
(2, 'AGN-00001', 'MEM-00002', 'Ashish Khandare', '9011420559', 'ashish.and.miscos@gmail.com', '2024-11-19', '', 'Hehe', 'Dheh adress', '', 'Sjjs', 0, '', 0, '0000-00-00 00:00:00', '', 0, 1, 'https://mounarchtech.com/vocoxp/active_folder/agency/member/profile_picture/673b21fc482d7.png', '2024-11-15 17:39:13', '2024-11-19 16:08:27', 0, 0, '', 0, 0, 0, '', 0, '', 'https://mounarchtech.com/vocoxp/member_self_data_entry_form.php?token=TUVNLTAwMDAyfDE1LTExLTIwMjQgMT', 1, 1, '2024-11-15 17:39:14', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `member_old_weblink_detail_all`
--

CREATE TABLE `member_old_weblink_detail_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `member_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `web_link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_type` int(11) NOT NULL COMMENT '1=details_only 2=with verification',
  `web_link_status` int(11) NOT NULL COMMENT '0=weblink initiated, 1= weblink opened, 2= weblink completed, 3= cancelled',
  `web_link_date` datetime NOT NULL COMMENT 'active only till 24hrs',
  `web_link_verifications` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV if verification is aadhar,pan then data is(1,2) here 1= Aadhar 2= Pan 3= Voter 4= DL 5= Indian Passport 6= international Passport 7= Crime check',
  `web_link_amount_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '1(Aadhar) = base amount + tax @ transaction_id, 2 (PAN) = base amount + tax@transaction_id, etc	',
  `web_link_total_amount` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_actual_amount` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '1(aadhar) = total rs, etc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_role_header_all`
--

CREATE TABLE `member_role_header_all` (
  `id` int(11) NOT NULL,
  `registration_id` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `member_id` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `member_management` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `watch_purchase` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `watch_communication` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `settings_and_reports` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `verifications` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `wallet_and_transaction` int(10) NOT NULL DEFAULT '0' COMMENT '1=Y/0=N',
  `report_verifications` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_settings_all`
--

CREATE TABLE `member_settings_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(25) NOT NULL,
  `app_setting_on_off` int(10) NOT NULL COMMENT '1=on/0=off',
  `sos` int(10) NOT NULL COMMENT '1=on/0=off',
  `geo_location_auto_update` varchar(100) NOT NULL COMMENT 'N or store duration/interval in minutes',
  `manual_recording` varchar(100) NOT NULL COMMENT 'N or store duration/interval in minutes',
  `manual_recording_notification` int(10) NOT NULL COMMENT '1=Y/0=N',
  `ecrime_reminder` varchar(50) NOT NULL COMMENT 'N or  if yes is present then direct provide value e.g.  30-day/30-month ',
  `alert_when_watch_out_from_gps_range` varchar(100) NOT NULL COMMENT 'N or range',
  `fsn_when_out_of_gps_location` int(10) NOT NULL COMMENT '1=Y/0=N',
  `bluetooth` int(10) NOT NULL COMMENT '1=Y/0=N',
  `carrier` varchar(100) NOT NULL COMMENT 'N or if Y then cellular/wifi/both',
  `heart_rate` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `spo2` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `body_temp` varchar(100) NOT NULL COMMENT 'Y/N, if yes then save comman heart rate data',
  `alert_when_watch_remove` int(10) NOT NULL COMMENT '1=Y/0=N',
  `alert_on_watch_remove_from_wrist` int(10) NOT NULL COMMENT '1=Y/0=N',
  `alert_on_watch_removal_after_every` varchar(30) NOT NULL COMMENT 'e.g 20 mins',
  `alrert_on_sim_remove` int(10) NOT NULL COMMENT '1=Y/0=N',
  `fsn_for_sim_remove` int(10) NOT NULL COMMENT '1=Y/0=N',
  `alert_on_watch_to_reconnect_server` int(10) NOT NULL COMMENT '1=Y/0=N',
  `fsn_watch_server_reconnect` int(10) NOT NULL COMMENT '1=Y/0=N',
  `pre_schedule_alert` int(10) NOT NULL COMMENT '1=Y/0=N',
  `fsn_pre_schedule_alert_not_delivered` int(10) NOT NULL COMMENT '1=Y/0=N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `member_settings_all`
--

INSERT INTO `member_settings_all` (`id`, `agency_id`, `member_id`, `app_setting_on_off`, `sos`, `geo_location_auto_update`, `manual_recording`, `manual_recording_notification`, `ecrime_reminder`, `alert_when_watch_out_from_gps_range`, `fsn_when_out_of_gps_location`, `bluetooth`, `carrier`, `heart_rate`, `spo2`, `body_temp`, `alert_when_watch_remove`, `alert_on_watch_remove_from_wrist`, `alert_on_watch_removal_after_every`, `alrert_on_sim_remove`, `fsn_for_sim_remove`, `alert_on_watch_to_reconnect_server`, `fsn_watch_server_reconnect`, `pre_schedule_alert`, `fsn_pre_schedule_alert_not_delivered`) VALUES
(1, 'AGN-00001', 'MEM-00001', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0),
(2, 'AGN-00001', 'MEM-00002', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0),
(3, 'AGN-00001', 'MEM-00003', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0),
(4, 'AGN-00001', 'MEM-00004', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0),
(5, 'AGN-00001', 'MEM-00005', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0),
(6, 'AGN-00002', 'MEM-00006', 0, 0, '', '', 0, '', '', 0, 0, '', '', '', '', 0, 0, '', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification_header_all`
--

CREATE TABLE `notification_header_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `notification_title` varchar(1000) NOT NULL,
  `body` longtext NOT NULL,
  `customize_data` longtext NOT NULL,
  `success_cnt` int(11) NOT NULL,
  `failure_cnt` int(11) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offer_information_all`
--

CREATE TABLE `offer_information_all` (
  `id` int(11) NOT NULL,
  `offer_id` varchar(50) NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `offer_name` varchar(100) NOT NULL,
  `recharge_amount` float NOT NULL,
  `add_on_amount` float NOT NULL,
  `applicable_from` date NOT NULL,
  `valid_till` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_address_header_all`
--

CREATE TABLE `order_address_header_all` (
  `id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `mobile_no` bigint(20) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `locality` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `agency_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_archive_all`
--

CREATE TABLE `order_archive_all` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `agency_id` varchar(50) NOT NULL COMMENT 'who placed order',
  `order_status` int(10) NOT NULL COMMENT 'placed-1/2-ready to dispatch/3-partially dispatched/4-payment_failed/5-payment_pending',
  `order_placed_on` datetime NOT NULL,
  `order_item_nos` longtext NOT NULL COMMENT 'in CSV format',
  `item_quantity` int(11) NOT NULL COMMENT 'total count',
  `order_transaction_id` varchar(100) NOT NULL,
  `order_amount` int(11) NOT NULL,
  `mode_payment` varchar(50) NOT NULL COMMENT 'option name which show by razor pay',
  `corrier_details` text NOT NULL COMMENT '“Corrier_name = Invoice_no@ Date of collections',
  `shipping_address` text NOT NULL,
  `shipment_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_header_all`
--

CREATE TABLE `order_header_all` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `agency_id` varchar(50) NOT NULL COMMENT 'who placed order',
  `order_status` varchar(50) NOT NULL COMMENT 'placed-1/2-ready to dispatch/3-partially dispatched/4-payment_failed/5-payment_pending',
  `order_placed_on` datetime NOT NULL,
  `order_item_nos` longtext NOT NULL COMMENT 'in CSV format',
  `item_quantity` int(11) NOT NULL COMMENT 'total count',
  `order_transaction_id` varchar(100) NOT NULL,
  `order_amount` int(11) NOT NULL,
  `mode_payment` varchar(50) NOT NULL COMMENT 'option name which show by razor pay',
  `corrier_details` text NOT NULL COMMENT '“Corrier_name = Invoice_no@ Date of collections',
  `shipping_address` text NOT NULL,
  `shipment_no` varchar(100) NOT NULL,
  `cancellation_reason` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_item_transaction_all`
--

CREATE TABLE `order_item_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `item_no` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'family/employee',
  `allocated_to` varchar(100) NOT NULL,
  `allocated_on` datetime NOT NULL,
  `status` varchar(100) NOT NULL COMMENT '1. available 2. allocated',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `reason` varchar(100) NOT NULL,
  `lock_details` longtext NOT NULL,
  `memory` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_return_header_all`
--

CREATE TABLE `order_return_header_all` (
  `id` bigint(20) NOT NULL,
  `return_id` varbinary(100) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `reason` longtext NOT NULL,
  `status` varchar(100) NOT NULL COMMENT 'Request to return/Refunded',
  `refund` longtext NOT NULL COMMENT 'amount= where amount is refund',
  `return_date` datetime NOT NULL,
  `refund_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_transaction_all`
--

CREATE TABLE `order_transaction_all` (
  `id` int(11) NOT NULL,
  `order_id` varchar(15) NOT NULL COMMENT '10 digit unique no start with “O”',
  `agency_id` varchar(15) NOT NULL,
  `agency_mobile_no` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` varchar(50) NOT NULL COMMENT '1=Pending/2=under process/3=shipped/ 4=delivered/5=return',
  `order_total` double NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `payment_mode` varchar(100) NOT NULL,
  `payment_transaction_id` varchar(100) NOT NULL COMMENT 'Transaction_id of Payment_information_all',
  `order_details` text NOT NULL COMMENT 'CSV values in “Product_id=Qty”',
  `sold_to` varchar(100) NOT NULL,
  `shipping_address` longtext NOT NULL,
  `shipping_status` varchar(20) NOT NULL,
  `shipment_no` varchar(50) NOT NULL,
  `corrier_details` text NOT NULL COMMENT '“Corrier_name = Invoice_no@ Date of collections',
  `deliver_date` datetime NOT NULL,
  `return_status` int(11) NOT NULL COMMENT '1=Complete /2=item/2=items',
  `return_items` text NOT NULL COMMENT 'CSV values of\r\n“product_id = item1,item2,item3”',
  `return_pickup_id` text NOT NULL COMMENT 'Return_id ref for return table',
  `cancellation_reason` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_transaction_archive_all`
--

CREATE TABLE `order_transaction_archive_all` (
  `id` int(11) NOT NULL,
  `order_id` varchar(15) NOT NULL COMMENT '10 digit unique no start with “O”',
  `agency_id` varchar(15) NOT NULL,
  `agency_mobile_no` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` int(10) NOT NULL COMMENT '1=Pending/2=under process/3=shipped/ 4=delivered/5=return',
  `order_total` double NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `payment_mode` varchar(100) NOT NULL,
  `payment_transaction_id` varchar(100) NOT NULL COMMENT 'Transaction_id of Payment_information_all',
  `order_details` text NOT NULL COMMENT 'CSV values in “Product_id=Qty”',
  `sold_to` varchar(100) NOT NULL,
  `shipping_address` longtext NOT NULL,
  `shipping_status` varchar(20) NOT NULL,
  `shipment_no` varchar(50) NOT NULL,
  `corrier_details` text NOT NULL COMMENT '“Corrier_name = Invoice_no@ Date of collections',
  `deliver_date` datetime NOT NULL,
  `return_status` int(11) NOT NULL COMMENT '1=Complete /2=item/2=items',
  `return_items` text NOT NULL COMMENT 'CSV values of\r\n“product_id = item1,item2,item3”',
  `return_pickup_id` text NOT NULL COMMENT 'Return_id ref for return table'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pending_enquiry_header_all`
--

CREATE TABLE `pending_enquiry_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(20) NOT NULL COMMENT 'individual/organization',
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL COMMENT 'organization_name',
  `business_type` varchar(50) NOT NULL COMMENT 'functional area',
  `city` varchar(100) NOT NULL,
  `address` varchar(250) CHARACTER SET utf16 NOT NULL,
  `profession` varchar(50) NOT NULL,
  `no_of_employees` varchar(100) NOT NULL,
  `gps_location` varchar(25) NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `created_on` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `type` int(10) NOT NULL COMMENT '1= organization, 0=individual',
  `login_pin` int(4) NOT NULL,
  `password` varchar(15) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `login_status` varchar(5) NOT NULL,
  `device_os` varchar(20) NOT NULL,
  `device_version` varchar(20) NOT NULL,
  `token_id` longtext NOT NULL COMMENT 'parent_token_id=child_token_id_1,child_token_id_2',
  `30_storage_notification` varchar(500) NOT NULL,
  `30_storage_notification_date` datetime NOT NULL,
  `20_storage_notification` varchar(500) NOT NULL,
  `20_storage_notification_date` datetime NOT NULL,
  `total_storage` double NOT NULL,
  `available_storage` double NOT NULL,
  `archieve_storage` double NOT NULL,
  `used_storage` double NOT NULL,
  `current_wallet_bal` double NOT NULL,
  `coupan_add_on_amount` float NOT NULL,
  `employee_type` int(10) NOT NULL COMMENT '1=owner\r\n0=working_as\r\nthe value will be inserted when registering as organization and the value will be owner / working_with_owner\r\n\r\n',
  `employee_designation` varchar(100) NOT NULL COMMENT 'value will insert when register as orgnization ',
  `work_type` int(10) NOT NULL COMMENT 'professional_working = 1\r\nstudent = 0\r\nvalue will insert when register as individual and value will be student/ professional_working',
  `owning_company` int(10) NOT NULL DEFAULT '0' COMMENT 'value will insert when register as individual and value will Y/N\r\nY=1, N=0',
  `is_get_benifit_of_offer` int(10) NOT NULL COMMENT 'Y=1/ N=0',
  `agency_gst_no` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `policy_webpage_details_all`
--

CREATE TABLE `policy_webpage_details_all` (
  `id` int(10) NOT NULL,
  `form_id` varchar(15) NOT NULL,
  `process_id` varchar(15) NOT NULL,
  `contract_id` varchar(20) NOT NULL,
  `webpage_url` varchar(50) NOT NULL,
  `status` int(10) NOT NULL COMMENT '1=active/0=deactive',
  `applied_from` date NOT NULL,
  `applied_till` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_header_all`
--

CREATE TABLE `profile_header_all` (
  `id` int(10) NOT NULL,
  `profile_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `profile_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'display name',
  `form_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV / all - means all forms are accessable',
  `process_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV / all - means all processes are accessable',
  `profile_status` int(10) NOT NULL COMMENT '1=active/2=inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `profile_header_all`
--

INSERT INTO `profile_header_all` (`id`, `profile_id`, `profile_name`, `form_id`, `process_id`, `profile_status`) VALUES
(3, 'PRO_0001', 'Member Management', 'F_MAN_001,F_MEM_001,F_MEM_002,F_MEM_003,F_MEM_004,F_MBM_001,F_MEM_005,F_PRO_001\n\n\n', 'P_MAN_0001,P_MAN_0005,P_MAN_0006,P_MAN_0011,P_MAN_0012,P_MEM_0001,P_MEM_0003,P_MEM_0004,P_MEM_0006,P_MEM_0007,P_MEM_0008,P_MEM_0009,P_MBM_0001,P_MAN_0013,P_MEM_0011\n', 1),
(4, 'PRO_0002', 'In APP verifications', 'F_MAN_001,F_DIG_001,F_TIH_001,F_VER_001\r\n', 'P_MAN_0001,P_MAN_0003,P_MAN_0005,P_MAN_0006,P_MAN_0008,P_MAN_0009,P_MAN_0013,P_MEM_0002\n', 1),
(5, 'PRO_0003', 'Wallet & accounts', 'F_MAN_001,F_WAL_001,F_WAR_001', 'P_MAN_0001,P_MAN_0002,P_MAN_0005,P_MAN_0006,P_MAN_0008,P_WAL_0001,P_MAN_0013', 1),
(6, 'PRO_0004', 'NBFC (Banking)', 'F_DRV_001,F_NBF_001', 'P_MAN_0014,P_MAN_0006,P_MAN_0009', 1),
(7, 'PRO_0005', 'Hotel Reception', 'F_HRM_001,F_HAR_001', 'P_MAN_0015,P_MAN_0006', 1),
(8, 'PRO_0006', 'Construction supervisor', 'F_SCM_001,F_CTR_001', 'P_MAN_0016,P_MAN_0006', 1),
(9, 'PRO_0007', 'Educational Campus', 'F_CAM_001', 'P_MAN_0017', 1),
(10, 'PRO_0008', 'Organization', 'F_ORG_001', 'P_MAN_0018', 1),
(11, 'PRO_0009', 'Weblink', 'F_BWL_001,F_BWL_002,F_BWL_003,F_BWL_004,F_BWL_005,F_BWL_006,F_BWL_007', 'P_BWL_0001,P_BWL_0002,P_BWL_0003,P_BWL_0004,P_BWL_0005,P_BWL_0006,P_BWL_0007,P_BWL_0008', 1),
(12, 'PRO_0010', 'Visitor', 'F_AVS_001,F_AVS_002,F_AVS_003,F_AVS_004,F_AVS_005,F_AVS_006,F_AVS_007,F_AVS_008,F_AVS_009,F_AVS_010,F_AVS_011,F_AVS_012,F_AVS_013,F_AVS_014', 'P_AVS_0001,P_AVS_0002,P_AVS_0003,P_AVS_0004,P_AVS_0005,P_AVS_0006,P_AVS_0007,P_AVS_0008,P_AVS_0009,P_AVS_0010,P_AVS_0011,P_AVS_0012,P_AVS_0013,P_AVS_0014,P_AVS_0015,P_AVS_0016,P_AVS_0017,P_AVS_0018,P_AVS_0019,P_AVS_0020,P_AVS_0021,P_AVS_0022,P_AVS_0023,P_AVS_0024,P_AVS_0025', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pulse_info_all`
--

CREATE TABLE `pulse_info_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `item_no` varchar(50) NOT NULL,
  `month_year` varchar(50) NOT NULL COMMENT '01-2023',
  `data_1` longtext NOT NULL COMMENT 'datetime=gpslat#gpslong@heartrate*body_temp$bp!address^spo2(othersencor~    csv',
  `data_2` longtext NOT NULL,
  `data_3` longtext NOT NULL,
  `data_4` longtext NOT NULL,
  `data_5` longtext NOT NULL,
  `data_6` longtext NOT NULL,
  `data_7` longtext NOT NULL,
  `data_8` longtext NOT NULL,
  `data_9` longtext NOT NULL,
  `data_10` longtext NOT NULL,
  `data_11` longtext NOT NULL,
  `data_12` longtext NOT NULL,
  `data_13` longtext NOT NULL,
  `data_14` longtext NOT NULL,
  `data_15` longtext NOT NULL,
  `data_16` longtext NOT NULL,
  `data_17` longtext NOT NULL,
  `data_18` longtext NOT NULL,
  `data_19` longtext NOT NULL,
  `data_20` longtext NOT NULL,
  `data_21` longtext NOT NULL,
  `data_22` longtext NOT NULL,
  `data_23` longtext NOT NULL,
  `data_24` longtext NOT NULL,
  `data_25` longtext NOT NULL,
  `data_26` longtext NOT NULL,
  `data_27` longtext NOT NULL,
  `data_28` longtext NOT NULL,
  `data_29` longtext NOT NULL,
  `data_30` longtext NOT NULL,
  `data_31` longtext NOT NULL,
  `diff_1` longtext NOT NULL,
  `diff_2` longtext NOT NULL,
  `diff_3` longtext NOT NULL,
  `diff_4` longtext NOT NULL,
  `diff_5` longtext NOT NULL,
  `diff_6` longtext NOT NULL,
  `diff_7` longtext NOT NULL,
  `diff_8` longtext NOT NULL,
  `diff_9` longtext NOT NULL,
  `diff_10` longtext NOT NULL,
  `diff_11` longtext NOT NULL,
  `diff_12` longtext NOT NULL,
  `diff_13` longtext NOT NULL,
  `diff_14` longtext NOT NULL,
  `diff_15` longtext NOT NULL,
  `diff_16` longtext NOT NULL,
  `diff_17` longtext NOT NULL,
  `diff_18` longtext NOT NULL,
  `diff_19` longtext NOT NULL,
  `diff_20` longtext NOT NULL,
  `diff_21` longtext NOT NULL,
  `diff_22` longtext NOT NULL,
  `diff_23` longtext NOT NULL,
  `diff_24` longtext NOT NULL,
  `diff_25` longtext NOT NULL,
  `diff_26` longtext NOT NULL,
  `diff_27` longtext NOT NULL,
  `diff_28` longtext NOT NULL,
  `diff_29` longtext NOT NULL,
  `diff_30` longtext NOT NULL,
  `diff_31` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `recording_archive_all`
--

CREATE TABLE `recording_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `recording_id` varchar(100) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `name` longtext NOT NULL,
  `frequency` int(10) NOT NULL COMMENT '1=once/0=repeat',
  `start_date` date NOT NULL,
  `start_time` time NOT NULL COMMENT '24 hrs',
  `stop_time` time NOT NULL COMMENT '24 hrs',
  `days` varchar(5000) NOT NULL,
  `notify_me` longtext NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recording_header_all`
--

CREATE TABLE `recording_header_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `recording_id` varchar(100) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `name` longtext NOT NULL,
  `frequency` int(10) NOT NULL COMMENT '1=once/0=repeat',
  `start_date` date NOT NULL,
  `start_time` time NOT NULL COMMENT '24 hrs',
  `stop_time` time NOT NULL COMMENT '24 hrs',
  `days` varchar(5000) NOT NULL,
  `notify_me` longtext NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recording_transaction_all`
--

CREATE TABLE `recording_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(25) NOT NULL,
  `recording_transaction_id` longtext NOT NULL,
  `recording_id` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `audio_file_path` longtext NOT NULL COMMENT 'in CSV format,  start_time@stop_time@file_path,start_time@stop_time@file_path.........'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recording_transaction_archive_all`
--

CREATE TABLE `recording_transaction_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `member_id` varchar(25) NOT NULL,
  `recording_transaction_id` varchar(100) NOT NULL,
  `recording_id` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `audio_file_path` longtext NOT NULL COMMENT 'in CSV format,  start_time@stop_time@file_path,start_time@stop_time@file_path.........'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `return_confirmation_details_all`
--

CREATE TABLE `return_confirmation_details_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `packet_id` varchar(100) NOT NULL,
  `item_no` longtext NOT NULL COMMENT 'csv(item_no=reason)',
  `return_type` int(10) NOT NULL COMMENT '1=replacement/0=refund',
  `refund_type` int(10) NOT NULL COMMENT '1=wallet/2=bank',
  `date_of_dispatched` datetime NOT NULL,
  `courier_name` varchar(200) NOT NULL,
  `courier_slip` longtext NOT NULL,
  `courier_track_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `return_initiated_details_all`
--

CREATE TABLE `return_initiated_details_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `item_no` varchar(100) NOT NULL,
  `img_url` longtext NOT NULL COMMENT 'csv img_url',
  `reason` varchar(150) NOT NULL,
  `return_initiated_on` datetime NOT NULL,
  `initiated_by` varchar(100) NOT NULL COMMENT 'who initiated the retuen',
  `final_status` int(10) NOT NULL COMMENT '1=initiated/2=confirmed',
  `packect_id` varchar(100) NOT NULL COMMENT 'after final status is confirmed then update packet id and will insert in confirm table'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `revoke_transaction_all`
--

CREATE TABLE `revoke_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `item_no` varchar(100) NOT NULL,
  `line_no` int(11) NOT NULL COMMENT 'it contain 50 count if count is greater is than 50 insert line no 2',
  `type` varchar(50) NOT NULL,
  `revoke_details` longtext NOT NULL COMMENT 'member_id@allocated_datetime@revoke_datetime@reason,member_id@allocated_datetime@revoke_datetime@reason............',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sample_excel_definations_all`
--

CREATE TABLE `sample_excel_definations_all` (
  `id` int(10) NOT NULL,
  `excel_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'of understanding',
  `excel_no` int(10) NOT NULL,
  `excel_url` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_validation_url` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'validations or rules which is uploaded from us (backend), and send to user so that to understand what kind of data to be entered in excel [pdf file]',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'student only, paret only, student parent both,',
  `stake_holder` int(11) NOT NULL COMMENT 'count of pointers in excel\r\neg: student excel will have 1 pointer that is student\r\nstudent and Parent excel will have 3 pointer, Mother/Father/Student',
  `excel_verification_rules_1` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 1 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel. we can also put regex here so that validations can be put easily.',
  `excel_verification_rules_2` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 2 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_3` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 3 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_4` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 4 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_5` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 5 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_6` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 6 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_7` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 7 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_8` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 8 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_9` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 9 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `excel_verification_rules_10` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule for the coumn 10 if applied e.g A=NUM,A=5 digit(this will be used to validate the uploaded excel file)\r\nNote: if this is blank meansno validation is required in uploaded excel.',
  `obj_1` json NOT NULL COMMENT 'obj_1_name:verification_1,verification_2,verification_3....',
  `obj_2` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'obj_2_name:verification_1,verification_2,verification_3....',
  `obj_3` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'obj_3_name:verification_1,verification_2,verification_3....',
  `type_for_excel` int(11) NOT NULL COMMENT '1=Weblink, 2=Visitor\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sample_excel_definations_all`
--

INSERT INTO `sample_excel_definations_all` (`id`, `excel_name`, `excel_no`, `excel_url`, `user_validation_url`, `type`, `stake_holder`, `excel_verification_rules_1`, `excel_verification_rules_2`, `excel_verification_rules_3`, `excel_verification_rules_4`, `excel_verification_rules_5`, `excel_verification_rules_6`, `excel_verification_rules_7`, `excel_verification_rules_8`, `excel_verification_rules_9`, `excel_verification_rules_10`, `obj_1`, `obj_2`, `obj_3`, `type_for_excel`) VALUES
(1, 'Student above16yrs', 1035, 'https://mounarchtech.com/vocoxp/Student_above16yrs.xlsx', 'https://mounarchtech.com/vocoxp/pdf2.pdf', 'Students - Above 16 ', 2, 'C>DIGITS', 'C>ALPHABET', 'O>DIGITS\n', 'C>MOBILE=IN', 'C>EMAIL', 'O>ALPHABET', 'O>MOBILE=IN', 'O>EMAIL', '', '', '{\"for\": \"Student\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{\n    \"for\": \"Parent\",\n    \"is_required\": \"C\",\n    \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"\n}', '{}', 1),
(2, 'Student And Parent', 1034, 'https://mounarchtech.com/vocoxp/Student_And_Parent.xlsx', '', 'Student & Parent', 3, 'C>DIGITS', 'C>ALPHABET', 'O>DIGITS', 'O>ALPHABET', 'O>MOBILE=IN', 'O>EMAIL', 'O>ALPHABET', 'O>MOBILE=IN', 'O>EMAIL\n', '', '{\"for\": \"Student\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001\"}', '{\"for\": \"Father\",\"is_required\": \"O\",\"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{\"for\": \"Mother\",\"is_required\": \"O\",\"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', 1),
(3, 'Employes Or Teachers', 1036, 'https://mounarchtech.com/vocoxp/Employes_Or_Teachers.xlsx', '', 'Employees / Teachers', 1, 'C>DIGITS', 'C>ALPHABET', 'O>DIGITS', 'C>MOBILE=IN', 'C>EMAIL', '', '', '', '', '', '{\"for\": \"Employees/Teachers\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{}', '{}', 1),
(4, 'Hostel Registration', 1043, 'https://mounarchtech.com/vocoxp/Hostel_Registration.xlsx', '', 'Hostel Registration', 3, 'C>DIGITS', 'C>ALPHABET', 'C>MOBILE=IN', 'C>EMAIL', 'O>ALPHABETS', 'O>MOBILE=IN', 'O>EMAIL', 'O>ALPHABETS', 'O>MOBILE=IN', '', '{\"for\": \"Resident\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{\"for\": \"Parent\",\"is_required\": \"O\",\"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{\"for\": \"Local Guardian\",\"is_required\": \"O\",\"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005,DVF-00008\"}', 1),
(5, 'Candidate onboarding', 1041, 'https://mounarchtech.com/vocoxp/Candidate_onboarding.xlsx', '', 'On-Boarding', 1, 'C>DIGITS', 'C>ALPHABET', 'C>MOBILE=IN', 'C>EMAIL\n', '', '', '', '', '', '', '{\"for\": \"Candidate / Event Participant Onboarding\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{}', '{}', 1),
(6, 'Students only', 1040, 'https://mounarchtech.com/vocoxp/Students.xlsx', '', 'Students Only', 1, 'C>DIGITS', 'C>ALPHABET', 'O>DIGITS', 'C>MOBILE=IN', 'C>EMAIL', '', '', '', '', '', '{\"for\": \"Student\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{}', '{}', 1),
(7, 'Parents', 1042, 'https://mounarchtech.com/vocoxp/Parents.xlsx', '', 'Parents', 1, 'C>DIGITS', 'C>ALPHABET', 'C>MOBILE=IN', 'C>EMAIL', '', '', '', '', '', '', '{\"for\": \"Parent\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{}', '{}', 1),
(8, 'Employees  details uploading', 1051, 'https://mounarchtech.com/vocoxp/Employee_excel_for_Visitor.xlsx', '', 'Visitor Employees Upload', 1, 'C>DIGITS', 'C>ALPHABET', 'O>ALPHANUMERIC', 'C>MOBILE=IN', 'C>ALPHABETS', 'O>ALPHABETS', 'C>EMAIL', 'C>ALPHABETS', 'C>ALPHABETS', 'C>ALPHABETS', '{\"for\": \"Visitor Employee\", \"is_required\": \"C\", \"verification_type\": \"DVF-00001,DVF-00002,DVF-00004,DVF-00005\"}', '{}', '{}', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sos_archive_all`
--

CREATE TABLE `sos_archive_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `is_watch_were` varchar(100) NOT NULL,
  `audio_file` longtext NOT NULL,
  `sos_gps_coordinates` longtext NOT NULL,
  `sos_notificatiob_sent_on` datetime NOT NULL,
  `sos_last_audio_received` datetime NOT NULL,
  `sos_last_gps_received` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sos_emergency_header_all`
--

CREATE TABLE `sos_emergency_header_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) NOT NULL,
  `member_id` varchar(100) NOT NULL,
  `is_watch_were` varchar(100) NOT NULL,
  `audio_file` longtext NOT NULL,
  `sos_gps_coordinates` longtext NOT NULL COMMENT 'CSV(lat#long)',
  `sos_notificatiob_sent_on` datetime NOT NULL,
  `sos_last_audio_received` datetime NOT NULL,
  `sos_last_gps_received` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sos_emergency_transaction_all`
--

CREATE TABLE `sos_emergency_transaction_all` (
  `id` int(11) NOT NULL,
  `sos_id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `gps_location` longtext NOT NULL,
  `watch_status` longtext NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sos_transaction_archive_all`
--

CREATE TABLE `sos_transaction_archive_all` (
  `id` int(11) NOT NULL DEFAULT '0',
  `sos_id` int(11) NOT NULL,
  `agency_id` varchar(50) NOT NULL,
  `member_id` varchar(50) NOT NULL,
  `gps_location` longtext NOT NULL,
  `watch_status` longtext NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `table_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'table where is id is stored',
  `id_for` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name, for which we are creating this ID',
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'set by us, such as for Item Number we can set prefix as IN',
  `last_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'last id generated for this id, if this column is empty then next id will be 00001, final id mst be IN_00001',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `id_prefix` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if return 999 here it means AGN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `unique_id_header_all`
--

INSERT INTO `unique_id_header_all` (`id`, `table_name`, `id_for`, `prefix`, `last_id`, `created_on`, `modified_on`, `id_prefix`) VALUES
(1, 'agency_header_all', 'agency_id', 'AGN', 'AGN-00006', '2024-11-14 15:34:21', '2024-11-22 17:52:56', ''),
(2, 'member_header_all', 'member_id', 'MEM', 'MEM-00006', '2024-11-14 16:14:14', '2024-11-21 11:29:40', ''),
(3, 'visitor_emp_weblink_details_all', '', 'EMP', 'EMP-00006', '2024-11-14 18:13:07', '2024-11-14 18:16:58', ''),
(4, 'employee_header_all', 'emp_id', 'VIEMP', 'VIEMP-00010', '2024-11-14 18:44:35', '2024-11-15 10:48:01', ''),
(5, 'visitor_temp_activity_detail_all', '', 'VIS', 'VIS-00011', '2024-11-15 10:56:08', '2024-11-25 10:11:48', ''),
(10, 'direct_verification_details_all', '', 'DIR', 'DIR-00226', '2024-11-15 14:50:40', '2024-11-24 00:12:31', ''),
(11, 'agency_visitor_location_header_all', 'visitor_location_id', 'VIS', 'VIS-00001', '2024-11-15 15:55:14', '0000-00-00 00:00:00', ''),
(12, 'admin_header_all', 'admin_id', 'ADM', 'ADM-00004', '2024-11-15 16:09:29', '2024-11-19 15:24:41', ''),
(13, 'visitor_header_all', '', 'VIS', 'VIS-00017', '2024-11-15 20:58:34', '2024-11-23 18:39:49', ''),
(14, 'bulk_weblink_request_all', 'request_no', 'RUS', 'RUS-00017', '2024-11-20 17:20:18', '2024-11-24 20:45:34', ''),
(15, 'bulk_weblink_request_all', 'bulk_id', 'BUL', 'BUL-00017', '2024-11-20 17:20:18', '2024-11-24 20:45:34', ''),
(16, 'bulk_end_user_transaction_all', 'upload_id', 'UPL', 'UPL-00048', '2024-11-20 18:43:19', '2024-11-24 20:52:01', ''),
(17, 'bulk_end_user_transaction_all', 'end_user_id', 'END', 'END-00735', '2024-11-20 18:43:19', '2024-11-24 20:52:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `version_control_details_all`
--

CREATE TABLE `version_control_details_all` (
  `id` int(10) NOT NULL,
  `version_id` varchar(15) NOT NULL COMMENT 'for internal use',
  `main_version_id` varchar(100) NOT NULL COMMENT 'if it is blank means it is main version data, else it is sub version data. \r\nIt''s the main version id will insert here. \r\neg: \r\nif\r\nMain version = 3.0, \r\nversion_id = ver_00001 \r\nmain_version_id = ''''\r\nthen\r\nsub version = 3.0.1\r\nversion_id = ver_00002 \r\nmain_version_id = ver_00001 \r\n',
  `version_name` varchar(50) NOT NULL COMMENT 'name of version (vocoXP 3.0.1)',
  `version_code` varchar(100) NOT NULL COMMENT 'eg: 3.0/3.0.1/4.0',
  `data_migration_on` datetime NOT NULL,
  `uploaded_on_date` datetime NOT NULL COMMENT 'date when this version enters',
  `mandatory_from_date` datetime NOT NULL COMMENT 'date from which this update is mendetory',
  `playstore_updated_on` datetime NOT NULL,
  `remind_user_from_date` datetime NOT NULL COMMENT 'date when to remind users about this update',
  `operating_system` int(11) NOT NULL COMMENT '0 means android 1 means IOS',
  `version_type` int(11) NOT NULL COMMENT '0 means minor 1 means major',
  `required` int(11) NOT NULL COMMENT '0 means optional 1 means compulsory',
  `status` int(11) NOT NULL COMMENT '0 means active 1 means passed 2 means upcomming',
  `current_user_count` int(11) NOT NULL COMMENT 'total no of app downloads till date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `version_control_details_all`
--

INSERT INTO `version_control_details_all` (`id`, `version_id`, `main_version_id`, `version_name`, `version_code`, `data_migration_on`, `uploaded_on_date`, `mandatory_from_date`, `playstore_updated_on`, `remind_user_from_date`, `operating_system`, `version_type`, `required`, `status`, `current_user_count`) VALUES
(1, 'VER-00001', '', 'VocoXp', '3.0', '2024-05-16 12:19:41', '2024-05-17 12:19:41', '2024-05-18 12:19:41', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, 1, 0, 0),
(2, 'VER-00002', '', 'VocoXp', '3.0', '2024-05-16 18:31:09', '2024-05-17 18:31:09', '2024-05-18 18:31:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vhss_help_header_all`
--

CREATE TABLE `vhss_help_header_all` (
  `id` int(20) NOT NULL COMMENT 'this is auto incremented value and primary key for this table	',
  `help_id` varchar(15) NOT NULL COMMENT 'this is unique help_id for this table a',
  `related_menu` varchar(200) NOT NULL COMMENT 'help_related_which_menu',
  `submenu` varchar(200) NOT NULL,
  `device_type` varchar(100) NOT NULL,
  `help_picture_1` varchar(500) NOT NULL COMMENT 'this column is used to store image of help',
  `help_description` varchar(1000) NOT NULL COMMENT 'this column is used to store description of help',
  `created_on` datetime NOT NULL COMMENT 'datetime on which table row is inserted',
  `modified_on` datetime NOT NULL COMMENT 'datetime on which table row is updated'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Here we are storing help images uploaded by miscos user';

-- --------------------------------------------------------

--
-- Table structure for table `visitor_aadhar_details_all`
--

CREATE TABLE `visitor_aadhar_details_all` (
  `id` int(11) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `aadhar_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no\r\ndata will come only when generated_by is Manually = 2',
  `aadhar_ambiguity` longtext NOT NULL,
  `report_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_dl_details_all`
--

CREATE TABLE `visitor_dl_details_all` (
  `id` int(11) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `dl_number` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `father_name` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `date_of_issue` date NOT NULL,
  `classes_of_vehicle` varchar(20) NOT NULL COMMENT 'Classes of Vehicles(LMV,MCWG, etc)',
  `state_name` text NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2',
  `dl_ambiguity` longtext NOT NULL,
  `report_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_dl_details_all`
--

INSERT INTO `visitor_dl_details_all` (`id`, `visitor_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `dl_number`, `name`, `father_name`, `address`, `dob`, `date_of_expiry`, `date_of_issue`, `classes_of_vehicle`, `state_name`, `blood_group`, `user_photo`, `front_photo`, `back_photo`, `generated_by`, `is_athenticate`, `dl_ambiguity`, `report_url`) VALUES
(1, 'VIS-00008', 'AGN-00005', '2024-11-23 18:22:41', '2024-11-23 18:22:41', 1, '', 'Pankaj Tripathi', '', 'Swargate,Pune', '0000-00-00', '0000-00-00', '0000-00-00', '', '', '', '', '', '', 0, 1, 'name@!Pankaj Tripathi,dob@!0000-00-00,address@!Swargate,Pune,date_of_issue@!-0001-11-30,date_of_expiry@!-0001-11-30', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/visitor/VIEMP-00067/dl_report/VIS-00008.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_emp_weblink_details_all`
--

CREATE TABLE `visitor_emp_weblink_details_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `emp_upload_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `weblink_url` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email_ids` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV',
  `uploaded_excel_url` longtext COLLATE utf8_unicode_ci NOT NULL,
  `function_for` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '1-append/2-replace/3-remove',
  `weblink_generated_on` datetime NOT NULL,
  `weblink_valid_till` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `visitor_emp_weblink_details_all`
--

INSERT INTO `visitor_emp_weblink_details_all` (`id`, `agency_id`, `emp_upload_id`, `weblink_url`, `email_ids`, `uploaded_excel_url`, `function_for`, `weblink_generated_on`, `weblink_valid_till`) VALUES
(1, 'AGN-00001', 'EMP-00001', 'https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id=AGN-00001&emp_id=EMP-00001', 'namrata.r.shrivas@gmail.com', '', '', '2024-11-14 18:13:07', '2024-11-14 18:13:07'),
(2, 'AGN-00005', 'EMP-00003', 'https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id=AGN-00005&emp_id=EMP-00003', 'ashish.and.miscos@gmail.com,ashishkhandare04@gmail.com', '', '', '2024-11-14 18:14:03', '2024-11-14 18:14:03'),
(3, 'AGN-00005', 'EMP-00004', 'https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id=AGN-00005&emp_id=EMP-00004', 'ashish.and.miscos@gmail.com', '', '', '2024-11-14 18:14:39', '2024-11-14 18:14:39'),
(4, 'AGN-00001', 'EMP-00006', 'https://mounarchtech.com/vocoxp/upload_link/add_employee.php?agency_id=AGN-00001&emp_id=EMP-00006', 'namrata.r.shrivas@gmail.com', '', '', '2024-11-14 18:16:58', '2024-11-14 18:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_header_all`
--

CREATE TABLE `visitor_header_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_location_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_email` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `people_with_visitor` int(11) NOT NULL,
  `approval_weblink_generated` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '0-no/weblink=datetime',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '2=approved, 3=rejected,\r\n5=Exit\r\n(1=requested, 2=approved, 3=rejected, 4=not opened\r\nthese status is from temprory table)',
  `emp_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `input_from` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=Aadhar, DVF-00002=Pan, DVF-00003 = Ecrime, DVF-00004=DL, DVF-00005=Voter, DVF-00006=Passport, 0=Manual Entry',
  `printout_generated` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0-No/1-yes',
  `visitor_pass_link` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'link of visitor''s pass',
  `inserted_on` datetime NOT NULL,
  `pass_valid_till` datetime NOT NULL,
  `exited_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_international_passport_details_all`
--

CREATE TABLE `visitor_international_passport_details_all` (
  `id` int(11) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `visa_photo` longtext NOT NULL,
  `landing_date` date NOT NULL,
  `visa_validity` date NOT NULL,
  `country` varchar(50) NOT NULL,
  `visa_type` varchar(100) NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_location_setting_details_all`
--

CREATE TABLE `visitor_location_setting_details_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_location_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `line_no` int(10) NOT NULL COMMENT 'for every change in setting against agency->location, new line will be inserted',
  `inserted_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid_till` datetime NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ntry only, 2-exit only, 3-both (by default -> 3)',
  `printer_provided` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Y/N',
  `visitor_mgmt` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Y/N',
  `employee_upload_link` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Y/N',
  `emp_email_ids` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'if above col is Y then CSV of email id will be here',
  `approval_required` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Y/N if Y = If it’s yes means on select of \r\nemployee weblink will be \r\ngenerated for approval',
  `approval_weblink_expired_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if above col is Y then store value here (in mimnutes)',
  `visitor_id_required` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Y/N If it’s "Y" means visitor has \r\nto give any one ID (PAN, \r\nVoter, Aadhar, DL) to guard \r\n& guard has to scan that ID \r\nin AVS Guard app',
  `verification_amt` float NOT NULL DEFAULT '0',
  `amt_paid_by` int(1) NOT NULL COMMENT '1=agency wallet, 2 = by end user (visitor)',
  `visiting_charges` float NOT NULL DEFAULT '0' COMMENT 'if 0, it means no visiting charges is applied',
  `visiting_hours` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'from_time=to_time',
  `visitor_pass_valid_upto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `weekoffs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '1=sunday,2=monday and so on.... CSV will be stored here like 1,2,3 means monday,tuesday,wednesday will be week off',
  `failed_intimation_to` int(11) NOT NULL COMMENT '1=admin, 2=employee',
  `fail_admin_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fail_emp_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'emp_id from employee_header_all table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `visitor_location_setting_details_all`
--

INSERT INTO `visitor_location_setting_details_all` (`id`, `agency_id`, `visitor_location_id`, `line_no`, `inserted_on`, `valid_till`, `type`, `printer_provided`, `visitor_mgmt`, `employee_upload_link`, `emp_email_ids`, `approval_required`, `approval_weblink_expired_in`, `visitor_id_required`, `verification_amt`, `amt_paid_by`, `visiting_charges`, `visiting_hours`, `visitor_pass_valid_upto`, `weekoffs`, `failed_intimation_to`, `fail_admin_id`, `fail_emp_id`) VALUES
(1, 'AGN-00001', 'VIS-00001', 0, '2024-11-15 15:55:14', '0000-00-00 00:00:00', '2', '', '', '', '', '', '', '', 0, 0, 0, '', '', '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_pan_details_all`
--

CREATE TABLE `visitor_pan_details_all` (
  `id` int(11) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `pan_number` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/husband name',
  `dob` date NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2',
  `pan_ambiguity` longtext NOT NULL,
  `report_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_pan_details_all`
--

INSERT INTO `visitor_pan_details_all` (`id`, `visitor_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `pan_number`, `name`, `father_name`, `dob`, `user_photo`, `front_photo`, `generated_by`, `is_athenticate`, `pan_ambiguity`, `report_url`) VALUES
(1, 'VIS-00008', 'AGN-00005', '2024-11-23 17:52:27', '2024-11-23 17:52:27', 1, '', 'Pankaj Tripathi', '', '0000-00-00', '', '', 0, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Pankaj Tripathi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/visitor/VIEMP-00067/pan_card_report/VIS-00008.pdf'),
(2, 'VIS-00008', 'AGN-00005', '2024-11-23 17:53:45', '2024-11-23 17:53:45', 1, '', 'Pankaj Tripathi', '', '0000-00-00', '', '', 0, 1, 'name@KHAYUM PASHARAJAK MUJAWAR!Pankaj Tripathi', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/visitor/VIEMP-00067/pan_card_report/VIS-00008.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_passport_details_all`
--

CREATE TABLE `visitor_passport_details_all` (
  `id` int(11) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL COMMENT 'Initated=0, verified=1, not verified=2',
  `passport_number` varchar(20) NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` text NOT NULL,
  `father_name` text NOT NULL,
  `mother_name` text NOT NULL,
  `spouse_name` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `republic_of_india` text NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `place_of_issue` varchar(20) NOT NULL,
  `country_code` int(10) NOT NULL,
  `nationality` text NOT NULL,
  `passport_type` text NOT NULL,
  `file_number` text NOT NULL,
  `cover_photo` longtext NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1=OCR, 2=Manually',
  `is_athenticate` int(11) NOT NULL,
  `passport_ambiguity` longtext NOT NULL,
  `report_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_payment_transaction_all`
--

CREATE TABLE `visitor_payment_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL COMMENT 'this is today''s table',
  `visitor_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `paid_amount` float NOT NULL COMMENT '200-30(verification_amount)+170(visiting amount) \r\ninc tax',
  `payment_status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `verification_amount` float NOT NULL,
  `visiting_amount` float NOT NULL,
  `v_transaction_id` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'For the reference to wallet amount deduction if paid from wallet\r\n\r\nif paid by user then transaction_id will reference in the collection table',
  `paid_from` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'from wallet or online\r\n1=wallet, 0=online',
  `paid_via` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'eg: qrcode, upi id, etc',
  `gateway_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'razorpay id, etc',
  `inserted_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_temp_activity_detail_all`
--

CREATE TABLE `visitor_temp_activity_detail_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_location_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `visitor_mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `people_with_visitor` int(11) NOT NULL,
  `person_arrival_date` date DEFAULT NULL,
  `requested_on` datetime NOT NULL,
  `verification_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'DVF-00001=Aadhar, DVF-00002=Pan, DVF-00003 = Ecrime, DVF-00004=DL, DVF-00005=Voter, DVF-00006=Passport, MVF-00000=Manual entry',
  `mode` int(11) NOT NULL COMMENT '1=OCR, 2=Manual',
  `meeting_with` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'employee_id to whom he is going to meet',
  `meeting_status` int(11) NOT NULL COMMENT '1=requested, 2=approved, 3=rejected, 4=not opened',
  `request_link_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'weblink_id',
  `sms_status` int(11) NOT NULL COMMENT '0=not sent, 1=sent',
  `email_status` int(11) NOT NULL COMMENT '0=not sent, 1=sent',
  `approved_on` datetime NOT NULL,
  `rejected_on` datetime NOT NULL,
  `final_status` int(11) NOT NULL COMMENT '6=run away',
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aadhar_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pan_number` text COLLATE utf8_unicode_ci NOT NULL,
  `dl_number` text COLLATE utf8_unicode_ci NOT NULL,
  `voter_number` text COLLATE utf8_unicode_ci NOT NULL,
  `passport_number` text COLLATE utf8_unicode_ci NOT NULL,
  `father_name` text COLLATE utf8_unicode_ci NOT NULL,
  `mother_name` text COLLATE utf8_unicode_ci NOT NULL,
  `spouse_name` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `blood_group` text COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `country_code` text COLLATE utf8_unicode_ci NOT NULL,
  `state_name` text COLLATE utf8_unicode_ci NOT NULL,
  `nationality` text COLLATE utf8_unicode_ci NOT NULL,
  `front_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `back_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `user_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `cover_photo` text COLLATE utf8_unicode_ci NOT NULL,
  `date_of_issue` datetime NOT NULL,
  `date_of_expiry` datetime NOT NULL,
  `place_of_issue` text COLLATE utf8_unicode_ci NOT NULL,
  `classes_of_vehicle` text COLLATE utf8_unicode_ci NOT NULL,
  `polling_details` text COLLATE utf8_unicode_ci NOT NULL,
  `republic_of_india` text COLLATE utf8_unicode_ci NOT NULL,
  `passport_type` text COLLATE utf8_unicode_ci NOT NULL,
  `file_number` text COLLATE utf8_unicode_ci NOT NULL,
  `visa_photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visa_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visa_expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `visitor_temp_activity_detail_all`
--

INSERT INTO `visitor_temp_activity_detail_all` (`id`, `agency_id`, `visitor_id`, `visitor_location_id`, `visitor_name`, `visitor_email`, `visitor_mobile`, `people_with_visitor`, `person_arrival_date`, `requested_on`, `verification_type`, `mode`, `meeting_with`, `meeting_status`, `request_link_url`, `sms_status`, `email_status`, `approved_on`, `rejected_on`, `final_status`, `first_name`, `last_name`, `aadhar_number`, `pan_number`, `dl_number`, `voter_number`, `passport_number`, `father_name`, `mother_name`, `spouse_name`, `gender`, `dob`, `blood_group`, `address`, `country_code`, `state_name`, `nationality`, `front_photo`, `back_photo`, `user_photo`, `cover_photo`, `date_of_issue`, `date_of_expiry`, `place_of_issue`, `classes_of_vehicle`, `polling_details`, `republic_of_india`, `passport_type`, `file_number`, `visa_photo`, `country`, `visa_type`, `visa_expiry_date`) VALUES
(1, 'AGN-00005', 'VIS-00001', 'VIS-00027', 'Pankaj Tripathi', '', '9865456413', 2, NULL, '2024-11-15 10:56:08', 'MVF-00000', 0, 'VIEMP-00067', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Swargate,Pune', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(2, 'AGN-00005', 'VIS-00002', 'VIS-00027', 'Pankaj Tripathi', '', '9865456413', 2, NULL, '2024-11-15 10:56:28', 'MVF-00000', 0, 'VIEMP-00067', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Swargate,Pune', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(3, 'AGN-00005', 'VIS-00003', 'VIS-00027', 'Pankaj Tripathi', '', '9865456413', 2, NULL, '2024-11-15 10:57:04', 'MVF-00000', 0, 'VIEMP-00067', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Swargate,Pune', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(4, 'AGN-00001', 'VIS-00006', 'VIS-00001', 'ASHISH KHNADAAE', '', '9011420559', 0, NULL, '2024-11-15 16:36:56', 'MVF-00000', 1, 'VIEMP-00006', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'ADDDRSSS WA', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(5, 'AGN-00001', 'VIS-00007', 'VIS-00001', 'ASHISH RAVINDRA KHANDARE', 'ashish.and.miscos@gmail.com', '9011420559', 2, NULL, '2024-11-15 16:45:03', 'DVF-00005', 1, 'VIEMP-00009', 1, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 'ASHISH RAVINDRA KHANDARE', '', '', '', '', 'RSO1726777', '', 'LAXMI KHANDARE', '', '', '', '2001-05-22', '', 'DR. AMBEDKAR VASTIGRUH, WADI BK, JALGAON JAMOD, BULDHANA, MAHARASHTRA- 443402', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/VIEMP-00009/doc_photo67372d37adf99.jpg', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00001/VIEMP-00009/doc_photo67372d380a3c9.jpg', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(8, 'AGN-00005', 'VIS-00008', 'VIS-00027', 'Pankaj Tripathi', '', '9865456413', 2, NULL, '2024-11-23 17:31:20', 'MVF-00000', 2, 'VIEMP-00010', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Swargate,Pune', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(9, 'AGN-00005', 'VIS-00004', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:28:35', 'DVF-00001', 0, 'EMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '546188549613', '', '', '', '', '', '', '', '', '0000-00-00', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/EMP-00001/VIS-00004/user_photo/6741d17b9fea6.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(10, 'AGN-00005', 'VIS-00005', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:29:37', 'DVF-00001', 0, 'VIEMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '546188549613', '', '', '', '', '', '', '', '', '0000-00-00', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/VIEMP-00001/VIS-00005/user_photo/6741d1b979397.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(11, 'AGN-00005', 'VIS-00006', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:29:41', 'DVF-00001', 0, 'VIEMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '546188549613', '', '', '', '', '', '', '', '', '0000-00-00', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/VIEMP-00001/VIS-00006/user_photo/6741d1bdd1711.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(12, 'AGN-00005', 'VIS-00009', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:31:59', 'DVF-00002', 0, 'VIEMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', 'BCFPM2764P', '', '', '', '', '', '', '', '0000-00-00', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/VIEMP-00001/VIS-00009/user_photo/6741d24776016.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(13, 'AGN-00005', 'VIS-00012', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:36:49', 'DVF-00004', 0, 'VIEMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', 'MH2420180013894', '', '', '', '', '', '', '1992-12-27', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/VIEMP-00001/VIS-00012/user_photo/6741d3692e06b.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(14, 'AGN-00005', 'VIS-00016', 'VIS-00018', 'visito', '', '8009357008', 0, '2024-11-23', '2024-11-23 18:39:40', 'DVF-00005', 0, 'VIEMP-00001', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', 'avb6205918', '', '', '', '', '', '0000-00-00', '', 'sd', '', '', '', '', '', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/VIEMP-00001/VIS-00016/user_photo/6741d414a8c1b.png', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(15, 'AGN-00005', 'VIS-00010', 'VIS-00027', 'Pankaj Tripathi', '', '9865456413', 2, NULL, '2024-11-25 10:10:34', 'MVF-00000', 0, 'VIEMP-00067', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Swargate,Pune', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visitor_voter_details_all`
--

CREATE TABLE `visitor_voter_details_all` (
  `id` int(10) NOT NULL,
  `visitor_id` varchar(25) NOT NULL,
  `agency_id` varchar(25) NOT NULL,
  `initiated_on` datetime NOT NULL,
  `completed_on` datetime NOT NULL,
  `activity_status` int(11) NOT NULL,
  `voter_number` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `father_name` text NOT NULL COMMENT 'father/guardian name',
  `address` longtext NOT NULL,
  `polling_details` varchar(100) NOT NULL,
  `user_photo` longtext NOT NULL,
  `front_photo` longtext NOT NULL,
  `back_photo` longtext NOT NULL,
  `generated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1= OCR, 2= Manually',
  `is_athenticate` int(11) NOT NULL DEFAULT '1' COMMENT '1=yes, 0=no data will come only when generated_by is Manually = 2',
  `voter_ambiguity` longtext NOT NULL,
  `report_url` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_voter_details_all`
--

INSERT INTO `visitor_voter_details_all` (`id`, `visitor_id`, `agency_id`, `initiated_on`, `completed_on`, `activity_status`, `voter_number`, `name`, `dob`, `gender`, `father_name`, `address`, `polling_details`, `user_photo`, `front_photo`, `back_photo`, `generated_by`, `is_athenticate`, `voter_ambiguity`, `report_url`) VALUES
(1, 'VIS-00008', 'AGN-00005', '2024-11-23 17:59:27', '2024-11-23 17:59:27', 1, '', 'Pankaj Tripathi', '0000-00-00', '', '', 'Swargate,Pune', '', '', '', '', 0, 1, 'name@KHAYUM MUJAWAR!Pankaj Tripathi,father_name@PASHARAJAK MUJAWAR!,gender@MALE!,state_name@Maharashtra!', 'https://mounarchtech.com/central_wp/verification_data/voco_xp/AGN-00005/visitor/VIEMP-00067/voter_id_card_report/VIS-00008.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_payment_transaction_all`
--

CREATE TABLE `wallet_payment_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'member_id or direct_id',
  `requested_from` int(11) NOT NULL COMMENT '1=in_app, 2=direct, 3=web_link, 4=visitor',
  `purchase_type` int(11) NOT NULL COMMENT '1=verification, 2=smart_wrist',
  `verification_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `base_amount` float(10,2) NOT NULL,
  `cgst_amount` float(10,2) NOT NULL,
  `sgst_amount` float(10,2) NOT NULL,
  `transaction_on` datetime NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `line_type` int(11) NOT NULL COMMENT '1=normal, 2= settled',
  `quantity` int(11) NOT NULL COMMENT 'how many line',
  `settled_for` date NOT NULL COMMENT 'applicable in case of visitor and direct',
  `ref_transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'this id is a reference of financial data of the base module(visitor/weblink/direct)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wallet_payment_transaction_all`
--

INSERT INTO `wallet_payment_transaction_all` (`id`, `agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `transaction_id`, `line_type`, `quantity`, `settled_for`, `ref_transaction_id`) VALUES
(1, 'AGN-00001', 'MEM-00001', 1, 1, 'DVF-00001', 20.00, 1.80, 1.80, '2024-11-14 17:11:58', '', 1, 0, '0000-00-00', ''),
(2, 'AGN-00001', 'END-00001', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-15 14:50:40', 'txn_1731662446280415', 1, 0, '0000-00-00', 'BUL-00002'),
(5, 'AGN-00001', 'MEM-00002', 1, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-16 16:54:14', '', 1, 0, '0000-00-00', ''),
(6, 'AGN-00001', 'AGN-00001', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-16 00:00:00', '', 2, 0, '2024-11-16', ''),
(7, 'AGN-00001', 'DIR-00013', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-18 11:16:28', '', 1, 0, '0000-00-00', ''),
(8, 'AGN-00001', 'DIR-00017', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-18 11:46:58', '', 1, 0, '0000-00-00', ''),
(9, 'AGN-00001', 'DIR-00018', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-18 11:47:37', '', 1, 0, '0000-00-00', ''),
(10, 'AGN-00001', 'DIR-00020', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-18 11:47:57', '', 1, 0, '0000-00-00', ''),
(11, 'AGN-00001', 'DIR-00035', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-18 12:21:12', '', 1, 0, '0000-00-00', ''),
(12, 'AGN-00001', 'DIR-00040', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-18 12:33:38', '', 1, 0, '0000-00-00', ''),
(13, 'AGN-00001', 'DIR-00042', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-18 12:40:48', '', 1, 0, '0000-00-00', ''),
(14, 'AGN-00001', 'DIR-00051', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-18 12:48:08', '', 1, 0, '0000-00-00', ''),
(15, 'AGN-00001', 'DIR-00055', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-18 12:55:09', '', 1, 0, '0000-00-00', ''),
(16, 'AGN-00001', 'DIR-00057', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-18 13:05:03', '', 1, 0, '0000-00-00', ''),
(17, 'AGN-00001', 'DIR-00059', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-18 13:17:09', '', 1, 0, '0000-00-00', ''),
(18, 'AGN-00001', 'DIR-00060', 2, 1, 'DVF-00006', 20.00, 1.80, 1.80, '2024-11-18 13:22:13', '', 1, 0, '0000-00-00', ''),
(19, 'AGN-00001', 'DIR-00063', 2, 1, 'DVF-00006', 20.00, 1.80, 1.80, '2024-11-18 14:04:45', '', 1, 0, '0000-00-00', ''),
(20, 'AGN-00001', 'DIR-00065', 2, 1, 'DVF-00006', 20.00, 1.80, 1.80, '2024-11-18 14:05:51', '', 1, 0, '0000-00-00', ''),
(21, 'AGN-00001', 'DIR-00066', 2, 1, 'DVF-00006', 20.00, 1.80, 1.80, '2024-11-18 14:14:06', '', 1, 0, '0000-00-00', ''),
(22, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 14:41:40', 'txn_1731921111323660', 1, 0, '0000-00-00', 'BUL-00002'),
(23, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 14:59:43', 'txn_1731922194727751', 1, 0, '0000-00-00', 'BUL-00002'),
(24, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:03:20', 'txn_1731922410363604', 1, 0, '0000-00-00', 'BUL-00002'),
(25, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:13:46', 'txn_1731923036677844', 1, 0, '0000-00-00', 'BUL-00002'),
(26, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:26:43', 'txn_1731923814392747', 1, 0, '0000-00-00', 'BUL-00002'),
(27, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:26:48', 'txn_1731923818518186', 1, 0, '0000-00-00', 'BUL-00002'),
(28, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 15:38:17', 'txn_1731924503200605', 1, 0, '0000-00-00', 'BUL-00002'),
(29, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:42:24', 'txn_1731924755190291', 1, 0, '0000-00-00', 'BUL-00002'),
(30, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 15:45:15', 'txn_1731924925266122', 1, 0, '0000-00-00', 'BUL-00002'),
(31, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 16:15:29', 'txn_1731926740604506', 1, 0, '0000-00-00', 'BUL-00002'),
(32, 'AGN-00001', 'MEM-00003', 1, 1, 'DVF-00002', 20.00, 1.80, 1.80, '2024-11-18 16:50:57', '', 1, 0, '0000-00-00', ''),
(33, 'AGN-00001', 'END-00003', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 17:16:37', 'txn_1731930408440511', 1, 0, '0000-00-00', 'BUL-00002'),
(34, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 17:27:15', 'txn_1731931046808991', 1, 0, '0000-00-00', 'BUL-00002'),
(35, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 17:43:58', 'txn_1731932048830515', 1, 0, '0000-00-00', 'BUL-00002'),
(36, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-18 17:46:39', 'txn_1731932210166048', 1, 0, '0000-00-00', 'BUL-00002'),
(37, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 17:51:43', 'txn_1731932509703984', 1, 0, '0000-00-00', 'BUL-00002'),
(38, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:02:57', 'txn_1731933188259306', 1, 0, '0000-00-00', 'BUL-00002'),
(39, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:04:00', 'txn_1731933251290046', 1, 0, '0000-00-00', 'BUL-00002'),
(40, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:05:27', 'txn_1731933337514140', 1, 0, '0000-00-00', 'BUL-00002'),
(41, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:08:23', 'txn_1731933514539120', 1, 0, '0000-00-00', 'BUL-00002'),
(42, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:10:45', 'txn_1731933655733136', 1, 0, '0000-00-00', 'BUL-00002'),
(43, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:28:16', 'txn_1731934707721765', 1, 0, '0000-00-00', 'BUL-00002'),
(44, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:34:37', 'txn_1731935087394804', 1, 0, '0000-00-00', 'BUL-00002'),
(45, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:37:24', 'txn_1731935255240327', 1, 0, '0000-00-00', 'BUL-00002'),
(46, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:39:01', 'txn_1731935352489028', 1, 0, '0000-00-00', 'BUL-00002'),
(47, 'AGN-00001', 'END-00002', 3, 1, 'DVF-00002', 20.06, 1.53, 1.53, '2024-11-18 18:44:12', 'txn_1731935663923881', 1, 0, '0000-00-00', 'BUL-00002'),
(48, 'AGN-00001', 'DIR-00099', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 12:25:13', '', 1, 0, '0000-00-00', ''),
(49, 'AGN-00001', 'DIR-00100', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 12:26:05', '', 1, 0, '0000-00-00', ''),
(50, 'AGN-00001', 'DIR-00101', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 12:26:35', '', 1, 0, '0000-00-00', ''),
(51, 'AGN-00001', 'DIR-00103', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 12:27:54', '', 1, 0, '0000-00-00', ''),
(52, 'AGN-00001', 'DIR-00105', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 12:28:40', '', 1, 0, '0000-00-00', ''),
(53, 'AGN-00001', 'DIR-00106', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 12:39:13', '', 1, 0, '0000-00-00', ''),
(54, 'AGN-00001', 'DIR-00109', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 12:45:35', '', 1, 0, '0000-00-00', ''),
(55, 'AGN-00001', 'DIR-00110', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 12:54:30', '', 1, 0, '0000-00-00', ''),
(56, 'AGN-00001', 'DIR-00115', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 13:00:58', '', 1, 0, '0000-00-00', ''),
(57, 'AGN-00001', 'DIR-00117', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 13:05:08', '', 1, 0, '0000-00-00', ''),
(58, 'AGN-00001', 'DIR-00135', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-19 13:19:06', '', 1, 0, '0000-00-00', ''),
(59, 'AGN-00001', 'DIR-00147', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 14:15:26', '', 1, 0, '0000-00-00', ''),
(60, 'AGN-00001', 'DIR-00148', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-19 14:19:31', '', 1, 0, '0000-00-00', ''),
(61, 'AGN-00001', 'DIR-00149', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-19 14:20:31', '', 1, 0, '0000-00-00', ''),
(62, 'AGN-00001', 'DIR-00151', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-19 14:26:22', '', 1, 0, '0000-00-00', ''),
(63, 'AGN-00001', 'DIR-00156', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-19 14:31:05', '', 1, 0, '0000-00-00', ''),
(64, 'AGN-00001', 'DIR-00159', 2, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-19 14:36:51', '', 1, 0, '0000-00-00', ''),
(65, 'AGN-00001', 'DIR-00161', 2, 1, 'DVF-00001', 33.00, 2.97, 2.97, '2024-11-19 15:29:45', '', 1, 0, '0000-00-00', ''),
(66, 'AGN-00001', 'DIR-00162', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 15:30:54', '', 1, 0, '0000-00-00', ''),
(67, 'AGN-00001', 'DIR-00164', 2, 1, 'DVF-00002', 17.00, 1.53, 1.53, '2024-11-19 15:31:50', '', 1, 0, '0000-00-00', ''),
(68, 'AGN-00001', 'DIR-00166', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-19 15:35:13', '', 1, 0, '0000-00-00', ''),
(69, 'AGN-00001', 'DIR-00167', 2, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-19 15:38:11', '', 1, 0, '0000-00-00', ''),
(70, 'AGN-00001', 'MEM-00004', 1, 1, 'DVF-00001', 20.00, 1.80, 1.80, '2024-11-19 18:13:37', '', 1, 0, '0000-00-00', ''),
(71, 'AGN-00001', 'MEM-00004', 1, 1, 'DVF-00002', 20.00, 1.80, 1.80, '2024-11-19 18:13:37', '', 1, 0, '0000-00-00', ''),
(72, 'AGN-00001', 'MEM-00004', 1, 1, 'DVF-00003', 20.00, 1.80, 1.80, '2024-11-19 18:13:37', '', 1, 0, '0000-00-00', ''),
(73, 'AGN-00001', 'MEM-00004', 1, 1, 'DVF-00004', 20.00, 1.80, 1.80, '2024-11-19 18:13:37', '', 1, 0, '0000-00-00', ''),
(74, 'AGN-00001', 'MEM-00004', 1, 1, 'DVF-00005', 20.00, 1.80, 1.80, '2024-11-19 18:13:37', '', 1, 0, '0000-00-00', ''),
(75, 'AGN-00003', 'END-00569', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-20 11:45:14', 'txn_1732083320278183', 1, 0, '0000-00-00', 'BUL-00049'),
(76, 'AGN-00001', 'MEM-00005', 1, 1, 'DVF-00001', 20.00, 1.80, 1.80, '2024-11-21 11:27:32', '', 1, 0, '0000-00-00', ''),
(77, 'AGN-00002', 'MEM-00006', 1, 1, 'DVF-00001', 20.00, 1.80, 1.80, '2024-11-21 11:31:22', '', 1, 0, '0000-00-00', ''),
(78, 'AGN-00005', 'END-00673', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-21 15:27:55', 'txn_1732183075491742', 1, 0, '0000-00-00', 'BUL-00006'),
(79, 'AGN-00005', 'END-00673', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-22 15:17:21', 'txn_1732268841340027', 1, 0, '0000-00-00', 'BUL-00006'),
(80, 'AGN-00005', 'END-00673', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-22 15:25:09', 'txn_1732269309812845', 1, 0, '0000-00-00', 'BUL-00006'),
(81, 'AGN-00005', 'END-00673', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-22 16:56:42', 'txn_1732274802227700', 1, 0, '0000-00-00', 'BUL-00006'),
(82, 'AGN-00005', 'END-00673', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-22 16:59:49', 'txn_1732274989565444', 1, 0, '0000-00-00', 'BUL-00006'),
(83, 'AGN-00003', 'END-00723', 3, 1, 'DVF-00001', 38.94, 2.97, 2.97, '2024-11-24 00:12:31', 'txn_1732387351345680', 1, 0, '0000-00-00', 'BUL-00015'),
(84, 'AGN-00003', 'END-00730', 3, 1, 'DVF-00008', 2.36, 0.18, 0.18, '2024-11-24 22:54:25', 'txn_1732469076301131', 1, 0, '0000-00-00', 'BUL-00016');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_recharge_failed_transaction_all`
--

CREATE TABLE `wallet_recharge_failed_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `line_no` int(50) NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `initial_wallet_balance` float(10,2) NOT NULL,
  `added_amount` float(10,2) NOT NULL,
  `final_blnce` int(50) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `recharge_from` int(11) NOT NULL COMMENT '1=online, 2=offer(lucky winner), 3=coupon_code',
  `coupon_amnt` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wallet_recharge_failed_transaction_all`
--

INSERT INTO `wallet_recharge_failed_transaction_all` (`id`, `agency_id`, `line_no`, `transaction_id`, `payment_gateway_id`, `initial_wallet_balance`, `added_amount`, `final_blnce`, `transaction_date`, `recharge_from`, `coupon_amnt`) VALUES
(1, 'AGN-00002', 3, '', '', 1976.40, 500.00, 2476, '2024-11-21 11:34:26', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wallet_recharge_transaction_all`
--

CREATE TABLE `wallet_recharge_transaction_all` (
  `id` int(11) NOT NULL,
  `agency_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `line_no` int(50) NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `initial_wallet_balance` float(10,2) NOT NULL,
  `added_amount` float(10,2) NOT NULL,
  `final_blnce` int(50) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `recharge_from` int(11) NOT NULL COMMENT '1=online, 2=offer(lucky winner), 3=coupon_code',
  `coupon_amnt` int(50) NOT NULL,
  `recharge_done_by` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if done by agency owner, then agency_id will go here , and if done by any admin then admin_id will go here'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wallet_recharge_transaction_all`
--

INSERT INTO `wallet_recharge_transaction_all` (`id`, `agency_id`, `line_no`, `transaction_id`, `payment_gateway_id`, `initial_wallet_balance`, `added_amount`, `final_blnce`, `transaction_date`, `recharge_from`, `coupon_amnt`, `recharge_done_by`) VALUES
(1, 'AGN-00001', 1, '', 'pay_PLATUBwoqbTNFv', 0.00, 500.00, 500, '2024-11-14 16:56:43', 1, 0, ''),
(2, 'AGN-00002', 1, '', 'pay_PMicOA36JUvgYY', 0.00, 500.00, 500, '2024-11-18 14:59:52', 1, 0, ''),
(3, 'AGN-00001', 2, '', 'pay_PMjtDpuNbvQ77q', -276.44, 500.00, 224, '2024-11-18 16:14:19', 1, 0, ''),
(4, 'AGN-00001', 3, '', 'pay_PMkDSAUkrdMKi5', 184.62, 100.00, 285, '2024-11-18 16:33:29', 1, 0, ''),
(5, 'AGN-00002', 2, '', 'pay_PNEgcbGG55Zzjy', 500.00, 1500.00, 2000, '2024-11-19 22:22:09', 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `weblink_archieve_all`
--

CREATE TABLE `weblink_archieve_all` (
  `id` int(11) NOT NULL,
  `weblink_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'generate for member_id, worker_id, and so on',
  `web_link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `generated_on` datetime NOT NULL,
  `inserted_on` datetime NOT NULL,
  `web_link_type` int(11) NOT NULL COMMENT '	1=details_only 2=with verification',
  `web_link_verifications` text COLLATE utf8_unicode_ci NOT NULL COMMENT '	CSV if verification is aadhar,pan then data is(1,2) here 1= Aadhar 2= Pan 3= Voter 4= DL 5= Indian Passport 6= international Passport 7= Crime check',
  `web_link_amount_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT '1(Aadhar) = base amount + tax@ transaction_id, 2 (PAN) = base amount + tax@ transaction_id,etc',
  `web_link_total_amount` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `web_link_actual_amount` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '1(aadhar) = total rs, etc',
  `completed_verifications` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'CSV if verification is done, aadhar,pan then data is(1,2) here 1= Aadhar 2= Pan 3= Voter 4= DL 5= Indian Passport 6= international Passport 7= Crime check eg: 1@date&time,5@date&time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_attendance_transaction_all`
--

CREATE TABLE `worker_attendance_transaction_all` (
  `id` int(10) NOT NULL,
  `agency_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `site_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `worker_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `admin_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sign_in_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sign_out_time` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_header_all`
--
ALTER TABLE `admin_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_amount_collection`
--
ALTER TABLE `agency_amount_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_cart_header_all`
--
ALTER TABLE `agency_cart_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_groups_header_all`
--
ALTER TABLE `agency_groups_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_header_all`
--
ALTER TABLE `agency_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_setting_all`
--
ALTER TABLE `agency_setting_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_storage_transaction_all`
--
ALTER TABLE `agency_storage_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency_visitor_location_header_all`
--
ALTER TABLE `agency_visitor_location_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_archive_all`
--
ALTER TABLE `alert_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_header_all`
--
ALTER TABLE `alert_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_transaction_all`
--
ALTER TABLE `alert_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_transaction_archive_all`
--
ALTER TABLE `alert_transaction_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_user_token_details_all`
--
ALTER TABLE `app_user_token_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `battery_status_header_all`
--
ALTER TABLE `battery_status_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_end_user_transaction_all`
--
ALTER TABLE `bulk_end_user_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_upload_file_information_all`
--
ALTER TABLE `bulk_upload_file_information_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_weblink_closed_all`
--
ALTER TABLE `bulk_weblink_closed_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_weblink_request_all`
--
ALTER TABLE `bulk_weblink_request_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cibil_active_transaction_all`
--
ALTER TABLE `cibil_active_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cibil_archive_transaction_all`
--
ALTER TABLE `cibil_archive_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `construction_site_header_all`
--
ALTER TABLE `construction_site_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `construction_site_worker_header_all`
--
ALTER TABLE `construction_site_worker_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custome_duty_archive_all`
--
ALTER TABLE `custome_duty_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_header_all`
--
ALTER TABLE `custom_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_aadhar_details_all`
--
ALTER TABLE `direct_aadhar_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_details_all`
--
ALTER TABLE `direct_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_dl_details_all`
--
ALTER TABLE `direct_dl_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_international_passport_details_all`
--
ALTER TABLE `direct_international_passport_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_pan_details_all`
--
ALTER TABLE `direct_pan_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_passport_details_all`
--
ALTER TABLE `direct_passport_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_verification_details_all`
--
ALTER TABLE `direct_verification_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct_voter_details_all`
--
ALTER TABLE `direct_voter_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disaster_header_all`
--
ALTER TABLE `disaster_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_direct_aadhar_details_all`
--
ALTER TABLE `edited_direct_aadhar_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_direct_dl_details_all`
--
ALTER TABLE `edited_direct_dl_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_direct_pan_details_all`
--
ALTER TABLE `edited_direct_pan_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_direct_passport_details_all`
--
ALTER TABLE `edited_direct_passport_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `edited_direct_voter_details_all`
--
ALTER TABLE `edited_direct_voter_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_header_all`
--
ALTER TABLE `employee_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `end_user_payment_transaction_all`
--
ALTER TABLE `end_user_payment_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `end_user_verification_transaction_all`
--
ALTER TABLE `end_user_verification_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factory_setting_header_all`
--
ALTER TABLE `factory_setting_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_contract_details_all`
--
ALTER TABLE `form_contract_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_process_header_all`
--
ALTER TABLE `form_process_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_and_support_category_header_all`
--
ALTER TABLE `help_and_support_category_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_and_support_generate_ticket`
--
ALTER TABLE `help_and_support_generate_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_and_support_issue_header_all`
--
ALTER TABLE `help_and_support_issue_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_and_support_videos_all`
--
ALTER TABLE `help_and_support_videos_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instant_messaging_detail_all`
--
ALTER TABLE `instant_messaging_detail_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internal_mgmt_table`
--
ALTER TABLE `internal_mgmt_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_recording_header_all`
--
ALTER TABLE `manual_recording_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_contract_combination_all`
--
ALTER TABLE `member_contract_combination_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_header_all`
--
ALTER TABLE `member_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_header_archive_all`
--
ALTER TABLE `member_header_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_old_weblink_detail_all`
--
ALTER TABLE `member_old_weblink_detail_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_role_header_all`
--
ALTER TABLE `member_role_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_settings_all`
--
ALTER TABLE `member_settings_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_header_all`
--
ALTER TABLE `notification_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_information_all`
--
ALTER TABLE `offer_information_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_address_header_all`
--
ALTER TABLE `order_address_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_archive_all`
--
ALTER TABLE `order_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_header_all`
--
ALTER TABLE `order_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_item_transaction_all`
--
ALTER TABLE `order_item_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_return_header_all`
--
ALTER TABLE `order_return_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_transaction_all`
--
ALTER TABLE `order_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_transaction_archive_all`
--
ALTER TABLE `order_transaction_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_enquiry_header_all`
--
ALTER TABLE `pending_enquiry_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policy_webpage_details_all`
--
ALTER TABLE `policy_webpage_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_header_all`
--
ALTER TABLE `profile_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pulse_info_all`
--
ALTER TABLE `pulse_info_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recording_archive_all`
--
ALTER TABLE `recording_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recording_header_all`
--
ALTER TABLE `recording_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recording_transaction_all`
--
ALTER TABLE `recording_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recording_transaction_archive_all`
--
ALTER TABLE `recording_transaction_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_confirmation_details_all`
--
ALTER TABLE `return_confirmation_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_initiated_details_all`
--
ALTER TABLE `return_initiated_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revoke_transaction_all`
--
ALTER TABLE `revoke_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sample_excel_definations_all`
--
ALTER TABLE `sample_excel_definations_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sos_archive_all`
--
ALTER TABLE `sos_archive_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sos_emergency_header_all`
--
ALTER TABLE `sos_emergency_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sos_emergency_transaction_all`
--
ALTER TABLE `sos_emergency_transaction_all`
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
-- Indexes for table `version_control_details_all`
--
ALTER TABLE `version_control_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vhss_help_header_all`
--
ALTER TABLE `vhss_help_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_aadhar_details_all`
--
ALTER TABLE `visitor_aadhar_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_dl_details_all`
--
ALTER TABLE `visitor_dl_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_emp_weblink_details_all`
--
ALTER TABLE `visitor_emp_weblink_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_header_all`
--
ALTER TABLE `visitor_header_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_international_passport_details_all`
--
ALTER TABLE `visitor_international_passport_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_location_setting_details_all`
--
ALTER TABLE `visitor_location_setting_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_pan_details_all`
--
ALTER TABLE `visitor_pan_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_passport_details_all`
--
ALTER TABLE `visitor_passport_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_payment_transaction_all`
--
ALTER TABLE `visitor_payment_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_temp_activity_detail_all`
--
ALTER TABLE `visitor_temp_activity_detail_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_voter_details_all`
--
ALTER TABLE `visitor_voter_details_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_payment_transaction_all`
--
ALTER TABLE `wallet_payment_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_recharge_failed_transaction_all`
--
ALTER TABLE `wallet_recharge_failed_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet_recharge_transaction_all`
--
ALTER TABLE `wallet_recharge_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weblink_archieve_all`
--
ALTER TABLE `weblink_archieve_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_attendance_transaction_all`
--
ALTER TABLE `worker_attendance_transaction_all`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_header_all`
--
ALTER TABLE `admin_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `agency_amount_collection`
--
ALTER TABLE `agency_amount_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agency_cart_header_all`
--
ALTER TABLE `agency_cart_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agency_groups_header_all`
--
ALTER TABLE `agency_groups_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agency_header_all`
--
ALTER TABLE `agency_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `agency_setting_all`
--
ALTER TABLE `agency_setting_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `agency_storage_transaction_all`
--
ALTER TABLE `agency_storage_transaction_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agency_visitor_location_header_all`
--
ALTER TABLE `agency_visitor_location_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `alert_archive_all`
--
ALTER TABLE `alert_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alert_header_all`
--
ALTER TABLE `alert_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alert_transaction_all`
--
ALTER TABLE `alert_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alert_transaction_archive_all`
--
ALTER TABLE `alert_transaction_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_user_token_details_all`
--
ALTER TABLE `app_user_token_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `battery_status_header_all`
--
ALTER TABLE `battery_status_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_end_user_transaction_all`
--
ALTER TABLE `bulk_end_user_transaction_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `bulk_upload_file_information_all`
--
ALTER TABLE `bulk_upload_file_information_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bulk_weblink_closed_all`
--
ALTER TABLE `bulk_weblink_closed_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_weblink_request_all`
--
ALTER TABLE `bulk_weblink_request_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cibil_active_transaction_all`
--
ALTER TABLE `cibil_active_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cibil_archive_transaction_all`
--
ALTER TABLE `cibil_archive_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `construction_site_header_all`
--
ALTER TABLE `construction_site_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `construction_site_worker_header_all`
--
ALTER TABLE `construction_site_worker_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custome_duty_archive_all`
--
ALTER TABLE `custome_duty_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_header_all`
--
ALTER TABLE `custom_header_all`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `direct_aadhar_details_all`
--
ALTER TABLE `direct_aadhar_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `direct_details_all`
--
ALTER TABLE `direct_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `direct_dl_details_all`
--
ALTER TABLE `direct_dl_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `direct_international_passport_details_all`
--
ALTER TABLE `direct_international_passport_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `direct_pan_details_all`
--
ALTER TABLE `direct_pan_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `direct_passport_details_all`
--
ALTER TABLE `direct_passport_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `direct_verification_details_all`
--
ALTER TABLE `direct_verification_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `direct_voter_details_all`
--
ALTER TABLE `direct_voter_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `disaster_header_all`
--
ALTER TABLE `disaster_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edited_direct_aadhar_details_all`
--
ALTER TABLE `edited_direct_aadhar_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `edited_direct_dl_details_all`
--
ALTER TABLE `edited_direct_dl_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edited_direct_pan_details_all`
--
ALTER TABLE `edited_direct_pan_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edited_direct_passport_details_all`
--
ALTER TABLE `edited_direct_passport_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edited_direct_voter_details_all`
--
ALTER TABLE `edited_direct_voter_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_header_all`
--
ALTER TABLE `employee_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `end_user_payment_transaction_all`
--
ALTER TABLE `end_user_payment_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `end_user_verification_transaction_all`
--
ALTER TABLE `end_user_verification_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `factory_setting_header_all`
--
ALTER TABLE `factory_setting_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `form_contract_details_all`
--
ALTER TABLE `form_contract_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `form_process_header_all`
--
ALTER TABLE `form_process_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `help_and_support_category_header_all`
--
ALTER TABLE `help_and_support_category_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `help_and_support_generate_ticket`
--
ALTER TABLE `help_and_support_generate_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `help_and_support_issue_header_all`
--
ALTER TABLE `help_and_support_issue_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `help_and_support_videos_all`
--
ALTER TABLE `help_and_support_videos_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `instant_messaging_detail_all`
--
ALTER TABLE `instant_messaging_detail_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internal_mgmt_table`
--
ALTER TABLE `internal_mgmt_table`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_recording_header_all`
--
ALTER TABLE `manual_recording_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_contract_combination_all`
--
ALTER TABLE `member_contract_combination_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `member_header_all`
--
ALTER TABLE `member_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `member_header_archive_all`
--
ALTER TABLE `member_header_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `member_old_weblink_detail_all`
--
ALTER TABLE `member_old_weblink_detail_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_role_header_all`
--
ALTER TABLE `member_role_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_settings_all`
--
ALTER TABLE `member_settings_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notification_header_all`
--
ALTER TABLE `notification_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_information_all`
--
ALTER TABLE `offer_information_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_address_header_all`
--
ALTER TABLE `order_address_header_all`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_archive_all`
--
ALTER TABLE `order_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_header_all`
--
ALTER TABLE `order_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item_transaction_all`
--
ALTER TABLE `order_item_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_return_header_all`
--
ALTER TABLE `order_return_header_all`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_transaction_all`
--
ALTER TABLE `order_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_transaction_archive_all`
--
ALTER TABLE `order_transaction_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_enquiry_header_all`
--
ALTER TABLE `pending_enquiry_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policy_webpage_details_all`
--
ALTER TABLE `policy_webpage_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_header_all`
--
ALTER TABLE `profile_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pulse_info_all`
--
ALTER TABLE `pulse_info_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recording_archive_all`
--
ALTER TABLE `recording_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recording_header_all`
--
ALTER TABLE `recording_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recording_transaction_all`
--
ALTER TABLE `recording_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recording_transaction_archive_all`
--
ALTER TABLE `recording_transaction_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_confirmation_details_all`
--
ALTER TABLE `return_confirmation_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_initiated_details_all`
--
ALTER TABLE `return_initiated_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revoke_transaction_all`
--
ALTER TABLE `revoke_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sample_excel_definations_all`
--
ALTER TABLE `sample_excel_definations_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sos_archive_all`
--
ALTER TABLE `sos_archive_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sos_emergency_header_all`
--
ALTER TABLE `sos_emergency_header_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sos_emergency_transaction_all`
--
ALTER TABLE `sos_emergency_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unique_id_header_all`
--
ALTER TABLE `unique_id_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `version_control_details_all`
--
ALTER TABLE `version_control_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vhss_help_header_all`
--
ALTER TABLE `vhss_help_header_all`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'this is auto incremented value and primary key for this table	';

--
-- AUTO_INCREMENT for table `visitor_aadhar_details_all`
--
ALTER TABLE `visitor_aadhar_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_dl_details_all`
--
ALTER TABLE `visitor_dl_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitor_emp_weblink_details_all`
--
ALTER TABLE `visitor_emp_weblink_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitor_header_all`
--
ALTER TABLE `visitor_header_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_international_passport_details_all`
--
ALTER TABLE `visitor_international_passport_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_location_setting_details_all`
--
ALTER TABLE `visitor_location_setting_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitor_pan_details_all`
--
ALTER TABLE `visitor_pan_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitor_passport_details_all`
--
ALTER TABLE `visitor_passport_details_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_payment_transaction_all`
--
ALTER TABLE `visitor_payment_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_temp_activity_detail_all`
--
ALTER TABLE `visitor_temp_activity_detail_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `visitor_voter_details_all`
--
ALTER TABLE `visitor_voter_details_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_payment_transaction_all`
--
ALTER TABLE `wallet_payment_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `wallet_recharge_failed_transaction_all`
--
ALTER TABLE `wallet_recharge_failed_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_recharge_transaction_all`
--
ALTER TABLE `wallet_recharge_transaction_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `weblink_archieve_all`
--
ALTER TABLE `weblink_archieve_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_attendance_transaction_all`
--
ALTER TABLE `worker_attendance_transaction_all`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
