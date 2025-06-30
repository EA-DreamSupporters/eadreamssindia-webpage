-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 11:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ea_tms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics_data`
--

CREATE TABLE `analytics_data` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `repetition_count` int(11) DEFAULT 1,
  `exam_years` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exam_years`)),
  `prediction_score` decimal(5,2) DEFAULT NULL,
  `last_analyzed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `branding_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`branding_config`)),
  `subscription_plan` enum('basic','premium','enterprise') DEFAULT 'basic',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`, `logo`, `branding_config`, `subscription_plan`, `created_at`) VALUES
(1, 'Demo Institute', NULL, NULL, NULL, '2025-06-30 06:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `question_banks`
--

CREATE TABLE `question_banks` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `topic` varchar(100) DEFAULT NULL,
  `subtopic` varchar(100) DEFAULT NULL,
  `question_text` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(10) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `exam_year` year(4) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 1,
  `institute_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_banks`
--

INSERT INTO `question_banks` (`id`, `title`, `subject`, `topic`, `subtopic`, `question_text`, `options`, `correct_answer`, `explanation`, `difficulty`, `exam_year`, `source`, `is_public`, `institute_id`, `created_at`) VALUES
(1, '', 'Polity', 'Judiciary', 'Contempt of Court', 'Consider the following statements:\r\n\r\nPursuant to the report of H.N. Sanyal Committee, the Contempt of Courts Act, 1971 was passed.\r\nThe Constitution of India empowers the Supreme Court and the High Courts to punish for contempt of themselves.\r\nThe Constitution of India defines Civil Contempt and Criminal Contempt.\r\nIn India, the Parliament is vested with the powers to make laws on Contempt of Court.\r\nWhich of the statements given above is/are correct?', '{\"A\":\"1 and 2 only\",\"B\":\"1, 2 and 4 only\",\"C\":\"3 and 4 only\",\"D\":\"3 only\"}', 'B', '', 'medium', '2022', 'UPSC', 1, NULL, '2025-06-30 05:21:14'),
(2, '', 'Polity', 'Judiciary', 'Contempt of Court', 'With reference to the writs issued by the Courts in India, consider the following statements:\r\n\r\n1. Mandamus will not lie against a private organization unless it is entrusted with a public duty.\r\n2. Mandamus will not lie against a Company even though it may be a Government Company.\r\n3. Any public minded person can be a petitioner to move the Court to obtain the writ of Quo Warranto.\r\n\r\nWhich of the statements given above are correct?', '{\"A\":\"1 and 2 only\",\"B\":\"2 and 3 only\",\"C\":\"3 and 4 only\",\"D\":\"1, 2 and 3\"}', 'A', '', 'medium', '2022', 'UPSC', 1, NULL, '2025-06-30 05:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `test_packs`
--

CREATE TABLE `test_packs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mrp` decimal(10,2) DEFAULT NULL,
  `test_type` enum('mock','real','instant') NOT NULL,
  `timer_type` enum('per_question','full_test') DEFAULT 'full_test',
  `duration_minutes` int(11) DEFAULT 60,
  `institute_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_visible_to_students` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_packs`
--

INSERT INTO `test_packs` (`id`, `title`, `description`, `cover_image`, `price`, `mrp`, `test_type`, `timer_type`, `duration_minutes`, `institute_id`, `is_active`, `created_at`, `is_visible_to_students`) VALUES
(11, 'UPSC DAMAKA', 'Heloow FAM', 'assets/images/1750949589_Slide 16_9 - 4.jpg', 1202.00, 3210.00, 'mock', 'full_test', 60, NULL, 1, '2025-06-26 16:53:09', 1),
(29, 'TNPSC', '', 'assets/images/1751264581_f58a7789f8.jpg', 1999.00, 3000.00, 'mock', 'full_test', 60, 1, 1, '2025-06-30 08:23:01', 1),
(31, 'TNSPC', '', 'assets/images/1751264664_Group 2216.png', 1999.00, 6330.00, 'mock', 'full_test', 60, NULL, 1, '2025-06-30 08:24:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `test_sessions`
--

CREATE TABLE `test_sessions` (
  `id` int(11) NOT NULL,
  `test_pack_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `proctoring_enabled` tinyint(1) DEFAULT 0,
  `recording_url` varchar(255) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','vendor','student') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `institute_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `name`, `email`, `created_at`, `institute_id`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Admin User', 'admin@example.com', '2025-06-23 08:49:47', NULL),
(15, 'superadmin', '4e4c56e4a15f89f05c2f4c72613da2a18c9665d4f0d6acce16415eb06f9be776', 'super_admin', 'Platform Super Admin', 'superadmin@example.com', '2025-06-26 15:22:23', NULL),
(16, 'vendor01', '00fc1e6c602824793c9840e781e5e20747507e26ddf0d60fab996567a0327cdf', 'vendor', 'Vendor Name', 'vendor01@yourplatform.com', '2025-06-26 15:28:56', 1),
(17, 'student01', '703b0a3d6ad75b649a28adde7d83c6251da457549263bc7ff45ec709b0a8448b', 'student', 'Student Name', 'student01@yourplatform.com', '2025-06-26 15:28:56', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics_data`
--
ALTER TABLE `analytics_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_banks`
--
ALTER TABLE `question_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `test_packs`
--
ALTER TABLE `test_packs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `test_sessions`
--
ALTER TABLE `test_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `test_pack_id` (`test_pack_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics_data`
--
ALTER TABLE `analytics_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `question_banks`
--
ALTER TABLE `question_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `test_packs`
--
ALTER TABLE `test_packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `test_sessions`
--
ALTER TABLE `test_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analytics_data`
--
ALTER TABLE `analytics_data`
  ADD CONSTRAINT `analytics_data_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question_banks` (`id`);

--
-- Constraints for table `question_banks`
--
ALTER TABLE `question_banks`
  ADD CONSTRAINT `question_banks_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institutions` (`id`);

--
-- Constraints for table `test_packs`
--
ALTER TABLE `test_packs`
  ADD CONSTRAINT `test_packs_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institutions` (`id`);

--
-- Constraints for table `test_sessions`
--
ALTER TABLE `test_sessions`
  ADD CONSTRAINT `test_sessions_ibfk_1` FOREIGN KEY (`test_pack_id`) REFERENCES `test_packs` (`id`),
  ADD CONSTRAINT `test_sessions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
