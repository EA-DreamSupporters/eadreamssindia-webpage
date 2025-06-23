-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2024 at 07:16 AM
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
-- Database: `bok`
--

-- --------------------------------------------------------

--
-- Table structure for table `guide`
--

CREATE TABLE `guide` (
  `guid` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `plan` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `pout` varchar(15) NOT NULL,
  `poutyear` varchar(100) NOT NULL,
  `pace` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `reply` varchar(255) NOT NULL,
  `yoe` varchar(100) NOT NULL,
  `wakeup_time` varchar(100) NOT NULL,
  `sleep_time` varchar(100) NOT NULL,
  `pre_time` varchar(100) NOT NULL,
  `walk_time` varchar(100) NOT NULL,
  `father` varchar(100) NOT NULL,
  `mother` varchar(100) NOT NULL,
  `about` varchar(100) NOT NULL,
  `problem_faced` varchar(100) NOT NULL,
  `favorite` varchar(100) NOT NULL,
  `difficult` varchar(100) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `problems` varchar(100) NOT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `gutime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guide`
--

INSERT INTO `guide` (`guid`, `user`, `name`, `dob`, `email`, `phone`, `plan`, `amount`, `payment_id`, `pout`, `poutyear`, `pace`, `address`, `msg`, `reply`, `yoe`, `wakeup_time`, `sleep_time`, `pre_time`, `walk_time`, `father`, `mother`, `about`, `problem_faced`, `favorite`, `difficult`, `institute`, `problems`, `status`, `gutime`) VALUES
(1, 1, 'aaa', '2023-03-15', 'aaa@gmail.com', '9988776655', '', '', '', 'Yes', '2011', 'Yes', 'aaaaa', 'dsfdsfsda', 'accepted', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-03-15 05:38:12'),
(2, 1, 'demo', '2023-03-15', 'demo@gmail.com', '9988776655', '', '', '', 'Yes', '2021', 'Yes', 'sssss', 'fgdgfdgfd', 'Rejected', '', '', '', '', '', '', '', '', '', '', '', '', '', -1, '2023-03-15 07:57:39'),
(3, 9, 'Test', '2023-05-05', 'test@gmail.com', '9988776655', '', '', '', 'Yes', '2022', 'Yes', 'Karaikudi', 'This is a test message', 'This is a Test Message', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-05-05 11:51:24'),
(4, 11, 'demon', '2023-05-02', 'demo@gmail.com', '9988877665', '', '', '', 'Yes', '2022', 'Yes', 'kkdi', 'This is for testing', 'This is test reply', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-05-30 06:32:42'),
(5, 11, 'demon', '2023-05-09', 'demo@gmail.com', '9988877665', '', '', '', 'No', '2022', 'No', 'kkdi', 'This is for test', 'Test Reply', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-05-30 06:37:13'),
(6, 12, 'Raja', '2023-05-29', 'raja@gmail.com', '9888776655', '', '', '', 'Yes', '2010', 'No', 'KKDI', 'This is a test message', 'This is test reply\r\n', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-06-01 06:03:47'),
(7, 13, 'EA Rider', '1999-10-23', 'dummy@gmail.com', '0000000000', '', '', '', 'Yes', '2021', 'Yes', 'edfsx', 'want', 'YES', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-06-24 07:30:00'),
(8, 13, 'EA Rider', '1999-10-23', 'dummy@gmail.com', '0000000000', '', '', '', 'Yes', '2021', 'Yes', 'edfsx', 'want', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2023-06-24 07:42:35'),
(9, 20, 'ravi', '2023-06-21', 'ravi@gmail.com', '9988777661', '', '', '', 'Yes', '2022', 'Yes', 'KKDI', 'TEst Message', 'test message', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '2023-06-26 13:25:44'),
(14, 21, 'Dhivakaran', '2024-05-24', 'dhivakaran@gmail.com', '7676767676', 'Plus', '2500', 'pay_OGdF2IoyqeIxBr', 'No', '2027', 'No', 'Karaikudi', 'Message From Dhivakaran', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2024-05-30 08:02:11'),
(15, 21, 'Dhivakaran', '2024-05-30', 'dhivakaran@gmail.com', '6767676776', 'Plus', '2500', 'pay_OGgLReo8gbI7Vx', 'Yes', '2025', 'No', 'Karaikudi', 'Message From Dhivakaran', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2024-05-30 11:04:15'),
(16, 21, 'gfh', '2024-05-23', 'gf@dfd.fghf', '4543', 'Plus', '2500', 'pay_OGxJBK9LDdl6jM', 'No', 'dgfd', 'No', 'dfg', 'dfg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2024-05-31 03:39:54'),
(17, 21, 'Ananth', '2024-05-17', 'an@gmail.com', '8778787878', 'Fresh', '1000', 'pay_OGyKFw04pGq3gF', 'No', '2223', 'No', 'Ddgdfg', 'fgjhgfjhg', '', '10', '05:40', '20:00', '10:40', '08:40', 'Farmer', 'House Wife', 'Student', 'Problemssss', 'Social', 'English', 'Pramid Academy', 'Inconsistent,Irregular Priparation,No Proper Test', 0, '2024-05-31 04:39:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`guid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guide`
--
ALTER TABLE `guide`
  MODIFY `guid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
