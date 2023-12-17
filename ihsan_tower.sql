-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2023 at 02:58 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ihsan_tower`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `mobile` int(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `role` int(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) DEFAULT NULL,
  `password` varchar(500) NOT NULL,
  `department` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `first_name`, `last_name`, `mobile`, `email`, `role`, `created_at`, `updated_at`, `password`, `department`) VALUES
(1, 'Abdul', 'Qhuddus', 561285501, 'm.qhuddus@alihsan.ae', 1, '', '', '$2a$12$.LRYwa8sNCN10pcczORE9eh3ZS1NFckG09UY5mIGmpy3cUZDsoJOO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `apartments`
--

CREATE TABLE `apartments` (
  `id` int(11) NOT NULL,
  `floor` varchar(200) NOT NULL DEFAULT '15',
  `door` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `parking` varchar(200) NOT NULL DEFAULT '0',
  `updated_at` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Occupied\r\n0=Empty',
  `contract_from` varchar(200) NOT NULL,
  `contract_to` varchar(200) NOT NULL,
  `rent` int(11) DEFAULT NULL,
  `bedroom` int(11) NOT NULL,
  `last_payment` varchar(250) DEFAULT NULL,
  `next_payment` varchar(250) DEFAULT NULL,
  `last_pay_date` varchar(250) DEFAULT NULL,
  `next_pay_date` varchar(250) DEFAULT NULL,
  `updated_by` varchar(250) DEFAULT NULL,
  `contract_number` varchar(250) DEFAULT NULL,
  `eid` int(15) DEFAULT NULL,
  `eid_expiry` varchar(250) DEFAULT NULL,
  `default_rent` int(11) DEFAULT NULL,
  `default_first_rent` int(11) DEFAULT 25,
  `nationality` int(11) DEFAULT NULL,
  `default_security` int(11) DEFAULT NULL,
  `default_insurance` int(11) DEFAULT NULL,
  `default_service` int(11) DEFAULT NULL,
  `default_parking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apartments`
--

INSERT INTO `apartments` (`id`, `floor`, `door`, `name`, `mobile`, `email`, `parking`, `updated_at`, `status`, `contract_from`, `contract_to`, `rent`, `bedroom`, `last_payment`, `next_payment`, `last_pay_date`, `next_pay_date`, `updated_by`, `contract_number`, `eid`, `eid_expiry`, `default_rent`, `default_first_rent`, `nationality`, `default_security`, `default_insurance`, `default_service`, `default_parking`) VALUES
(101, '1', '101', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22000, 25, NULL, 5000, 1000, 1500, 1500),
(102, '1', '102', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(103, '1', '103', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(104, '1', '104', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(105, '1', '105', '', '', '', '0', NULL, 0, '', '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22000, 25, NULL, 5000, 1000, 1500, 1500),
(106, '1', '106', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(107, '1', '107', '', '', '', '0', NULL, 0, '', '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22000, 25, NULL, 5000, 1000, 1500, 1500),
(108, '1', '108', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(109, '1', '109', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(201, '2', '201', '', '', '', '0', NULL, 0, '', '', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18000, 25, NULL, 5000, 1000, 1500, 1500),
(202, '2', '202', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(203, '2', '203', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(204, '2', '204', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(205, '2', '205', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(206, '2', '206', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(207, '2', '207', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(208, '2', '208', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(209, '2', '209', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(301, '3', '301', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(302, '3', '302', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(303, '3', '303', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(304, '3', '304', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(305, '3', '305', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(306, '3', '306', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(307, '3', '307', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(308, '3', '308', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(309, '3', '309', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(401, '4', '401', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(402, '4', '402', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(403, '4', '403', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(404, '4', '404', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(405, '4', '405', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(406, '4', '406', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(407, '4', '407', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(408, '4', '408', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(409, '4', '409', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(501, '5', '501', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(502, '5', '502', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(503, '5', '503', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(504, '5', '504', '', '', '', '0', NULL, 0, '', '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22000, 25, NULL, 5000, 1000, 1500, 1500),
(505, '5', '505', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(506, '5', '506', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(507, '5', '507', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(508, '5', '508', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(509, '5', '509', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(601, '6', '601', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(602, '6', '602', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(603, '6', '603', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(604, '6', '604', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(605, '6', '605', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(606, '6', '606', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(607, '6', '607', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(608, '6', '608', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(609, '6', '609', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(701, '7', '701', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(702, '7', '702', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(703, '7', '703', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(704, '7', '704', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(705, '7', '705', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(706, '7', '706', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(707, '7', '707', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(708, '7', '708', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(709, '7', '709', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(801, '8', '801', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(802, '8', '802', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(803, '8', '803', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(804, '8', '804', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(805, '8', '805', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(806, '8', '806', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(807, '8', '807', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(808, '8', '808', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(809, '8', '809', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(901, '9', '901', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(902, '9', '902', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(903, '9', '903', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(904, '9', '904', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(905, '9', '905', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(906, '9', '906', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(907, '9', '907', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(908, '9', '908', '', '', '', '0', NULL, 0, '', '', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22000, 25, NULL, 5000, 1000, 1500, 1500),
(909, '9', '909', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1001, '10', '1001', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1002, '10', '1002', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1003, '10', '1003', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1004, '10', '1004', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1005, '10', '1005', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1006, '10', '1006', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1007, '10', '1007', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1008, '10', '1008', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1009, '10', '1009', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1101, '11', '1101', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1102, '11', '1102', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1103, '11', '1103', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1104, '11', '1104', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1105, '11', '1105', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1106, '11', '1106', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1107, '11', '1107', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1108, '11', '1108', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1109, '11', '1109', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1201, '12', '1201', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1202, '12', '1202', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1203, '12', '1203', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1204, '12', '1204', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1205, '12', '1205', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1206, '12', '1206', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1207, '12', '1207', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1208, '12', '1208', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1209, '12', '1209', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1301, '13', '1301', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1302, '13', '1302', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1303, '13', '1303', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1304, '13', '1304', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1305, '13', '1305', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1306, '13', '1306', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1307, '13', '1307', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1308, '13', '1308', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1309, '13', '1309', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1401, '14', '1401', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1402, '14', '1402', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1403, '14', '1403', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1404, '14', '1404', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1405, '14', '1405', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1406, '14', '1406', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1407, '14', '1407', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1408, '14', '1408', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1409, '14', '1409', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1501, '15', '1501', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1502, '15', '1502', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1503, '15', '1503', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1504, '15', '1504', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1505, '15', '1505', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1506, '15', '1506', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1507, '15', '1507', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1508, '15', '1508', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500),
(1509, '15', '1509', '', '', '', '0', NULL, 0, '', '', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26000, 25, NULL, 5000, 1000, 1500, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `cancel_contracts`
--

CREATE TABLE `cancel_contracts` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `date` varchar(200) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `updated_by` varchar(200) DEFAULT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `pay_mode` varchar(250) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `download_count` int(100) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `contract_number` varchar(250) DEFAULT NULL,
  `refund` int(11) DEFAULT 0,
  `service_charge` int(11) DEFAULT 0,
  `security` int(11) DEFAULT 0,
  `cancellation_type` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `mobile` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `contract_from` varchar(200) DEFAULT NULL,
  `contract_to` varchar(200) DEFAULT NULL,
  `bedroom` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` varchar(200) DEFAULT NULL,
  `updated_by` varchar(200) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `apt_id` int(11) NOT NULL,
  `download_count` int(11) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `pay_mode` varchar(250) DEFAULT NULL,
  `eid` varchar(15) DEFAULT NULL,
  `eid_expiry` varchar(250) DEFAULT NULL,
  `security` varchar(250) DEFAULT NULL,
  `insurance` varchar(250) DEFAULT NULL,
  `service_charge` varchar(250) DEFAULT NULL,
  `cheque_2_number` varchar(250) DEFAULT NULL,
  `cheque_3_number` varchar(250) DEFAULT NULL,
  `cheque_4_number` varchar(250) DEFAULT NULL,
  `cheque_2_date` varchar(250) DEFAULT NULL,
  `cheque_3_date` varchar(250) DEFAULT NULL,
  `cheque_4_date` varchar(250) DEFAULT NULL,
  `cheque_2_status` varchar(250) DEFAULT 'Unpaid',
  `cheque_3_status` varchar(250) DEFAULT 'Unpaid',
  `cheque_4_status` varchar(250) DEFAULT 'Unpaid',
  `nationality` int(11) DEFAULT NULL,
  `cheque_2_name` varchar(250) DEFAULT NULL,
  `cheque_3_name` varchar(250) DEFAULT NULL,
  `cheque_4_name` varchar(250) DEFAULT NULL,
  `cheque_2_size` varchar(250) DEFAULT NULL,
  `cheque_3_size` varchar(250) DEFAULT NULL,
  `cheque_4_size` varchar(250) DEFAULT NULL,
  `eid_name` varchar(250) DEFAULT NULL,
  `eid_size` varchar(250) DEFAULT NULL,
  `cheque_2_amount` int(11) DEFAULT NULL,
  `cheque_3_amount` int(11) DEFAULT NULL,
  `cheque_4_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `status` int(10) NOT NULL DEFAULT 0 COMMENT '0=Open\r\n1=Closed',
  `cost` int(200) NOT NULL,
  `bill` varchar(200) NOT NULL,
  `remarks` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `mobile` int(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL,
  `otp` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `mobile`, `updated_at`, `otp`) VALUES
(18, 561285501, '2023-07-03 20:13:13pm', 9521);

-- --------------------------------------------------------

--
-- Table structure for table `parking`
--

CREATE TABLE `parking` (
  `id` int(11) NOT NULL,
  `number` varchar(200) NOT NULL,
  `floor` int(11) NOT NULL DEFAULT 3,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking`
--

INSERT INTO `parking` (`id`, `number`, `floor`, `status`) VALUES
(101, '101', 1, 1),
(102, '102', 1, 1),
(103, '103', 1, 1),
(104, '104', 1, 1),
(105, '105', 1, 1),
(106, '106', 1, 1),
(107, '107', 1, 1),
(108, '108', 1, 1),
(109, '109', 1, 1),
(110, '110', 1, 1),
(111, '111', 1, 1),
(112, '112', 1, 1),
(113, '113', 1, 1),
(114, '114', 1, 1),
(115, '115', 1, 1),
(116, '116', 1, 1),
(117, '117', 1, 1),
(118, '118', 1, 1),
(119, '119', 1, 1),
(120, '120', 1, 1),
(121, '121', 1, 1),
(122, '122', 1, 1),
(123, '123', 1, 1),
(201, '201', 2, 1),
(202, '202', 2, 1),
(203, '203', 2, 1),
(204, '204', 2, 1),
(205, '205', 2, 1),
(206, '206', 2, 1),
(207, '207', 2, 1),
(208, '208', 2, 1),
(209, '209', 2, 1),
(210, '210', 2, 1),
(211, '211', 2, 1),
(212, '212', 2, 1),
(213, '213', 2, 1),
(214, '214', 2, 1),
(215, '215', 2, 1),
(216, '216', 2, 1),
(217, '217', 2, 1),
(218, '218', 2, 1),
(219, '219', 2, 1),
(220, '220', 2, 1),
(221, '221', 2, 1),
(222, '222', 2, 1),
(223, '223', 2, 1),
(301, '301', 3, 1),
(302, '302', 3, 1),
(303, '303', 3, 1),
(304, '304', 3, 1),
(305, '305', 3, 1),
(306, '306', 3, 1),
(307, '307', 3, 1),
(308, '308', 3, 1),
(309, '309', 3, 1),
(310, '310', 3, 1),
(311, '311', 3, 1),
(312, '312', 3, 1),
(313, '313', 3, 1),
(314, '314', 3, 1),
(315, '315', 3, 1),
(316, '316', 3, 1),
(317, '317', 3, 1),
(318, '318', 3, 1),
(319, '319', 3, 1),
(320, '320', 3, 1),
(321, '321', 3, 1),
(322, '322', 3, 1),
(323, '323', 3, 1),
(401, '401', 4, 1),
(402, '402', 4, 1),
(403, '403', 4, 1),
(404, '404', 4, 1),
(405, '405', 4, 1),
(406, '406', 4, 1),
(407, '407', 4, 1),
(408, '408', 4, 1),
(409, '409', 4, 1),
(410, '410', 4, 1),
(411, '411', 4, 1),
(412, '412', 4, 1),
(413, '413', 4, 1),
(414, '414', 4, 1),
(415, '415', 4, 1),
(416, '416', 4, 1),
(417, '417', 4, 1),
(418, '418', 4, 1),
(419, '419', 4, 1),
(420, '420', 4, 1),
(421, '421', 4, 1),
(422, '422', 4, 1),
(423, '423', 4, 1),
(501, '501', 5, 1),
(502, '502', 5, 1),
(503, '503', 5, 1),
(504, '504', 5, 1),
(505, '505', 5, 1),
(506, '506', 5, 1),
(507, '507', 5, 1),
(508, '508', 5, 1),
(509, '509', 5, 1),
(510, '510', 5, 1),
(511, '511', 5, 1),
(512, '512', 5, 1),
(513, '513', 5, 1),
(514, '514', 5, 1),
(515, '515', 5, 1),
(516, '516', 5, 1),
(517, '517', 5, 1),
(518, '518', 5, 1),
(519, '519', 5, 1),
(520, '520', 5, 1),
(521, '521', 5, 1),
(522, '522', 5, 1),
(523, '523', 5, 1),
(601, '601', 6, 1),
(602, '602', 6, 1),
(603, '603', 6, 1),
(604, '604', 6, 1),
(605, '605', 6, 1),
(606, '606', 6, 1),
(607, '607', 6, 1),
(608, '608', 6, 1),
(609, '609', 6, 1),
(610, '610', 6, 1),
(611, '611', 6, 1),
(612, '612', 6, 1),
(613, '613', 6, 1),
(614, '614', 6, 1),
(615, '615', 6, 1),
(616, '616', 6, 1),
(617, '617', 6, 1),
(618, '618', 6, 1),
(619, '619', 6, 1),
(620, '620', 6, 1),
(621, '621', 6, 1),
(622, '622', 6, 1),
(623, '623', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rents`
--

CREATE TABLE `rents` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` varchar(200) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `updated_by` varchar(200) DEFAULT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `pay_mode` varchar(250) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `download_count` int(100) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `contract_number` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `apt_id` int(11) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` varchar(200) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `updated_by` varchar(200) DEFAULT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `pay_mode` varchar(250) DEFAULT NULL,
  `file_name` varchar(250) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `download_count` int(100) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `invoice_id` varchar(250) DEFAULT NULL,
  `payment` int(11) DEFAULT 0,
  `refund` int(11) DEFAULT 0,
  `security` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancel_contracts`
--
ALTER TABLE `cancel_contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking`
--
ALTER TABLE `parking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rents`
--
ALTER TABLE `rents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `apartments`
--
ALTER TABLE `apartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1510;

--
-- AUTO_INCREMENT for table `cancel_contracts`
--
ALTER TABLE `cancel_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `parking`
--
ALTER TABLE `parking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=624;

--
-- AUTO_INCREMENT for table `rents`
--
ALTER TABLE `rents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
