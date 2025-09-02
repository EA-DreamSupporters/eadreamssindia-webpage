-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 06:30 AM
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
  `image` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 1,
  `institute_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_banks`
--

INSERT INTO `question_banks` (`id`, `title`, `subject`, `topic`, `subtopic`, `question_text`, `options`, `correct_answer`, `explanation`, `difficulty`, `exam_year`, `source`, `image`, `is_public`, `institute_id`, `created_at`) VALUES
(49, 'Economy - 1. Which of the following statements about the Fun', 'Economy', 'Economic', '', '1. Which of the following statements about the Fundamental Rights of India is/are correct?\r\n\r\n(i) They are justiciable.\r\n(ii) They can be suspended during a National Emergency.\r\n(iii) They are provided under Part III of the Constitution.\r\n(iv) They include the Right to Property.', '{\"A\":\"(i), (ii), and (iii) only\",\"B\":\"(i) and (iii) only\",\"C\":\"(ii) and (iv) only\",\"D\":\"All of the above\",\"i18n\":{\"en\":{\"question_text\":\"1. Which of the following statements about the Fundamental Rights of India is\\/are correct?\\r\\n\\r\\n(i) They are justiciable.\\r\\n(ii) They can be suspended during a National Emergency.\\r\\n(iii) They are provided under Part III of the Constitution.\\r\\n(iv) They include the Right to Property.\",\"option_A\":\"(i), (ii), and (iii) only\",\"option_B\":\"(i) and (iii) only\",\"option_C\":\"(ii) and (iv) only\",\"option_D\":\"All of the above\"}}}', 'A', 'India is a mixed economy with both public and private sectors. It also has a strong agrarian base.', 'easy', '2025', 'TNPSC', NULL, 1, 1, '2025-08-22 04:11:30'),
(51, 'Aptitude - Following image:', 'Aptitude', 'Aptitude', 'Dice', 'Following image:', '{\"type\":\"image_mcq\",\"image\":\"images\\/qimg_68b1c17362b77.png\",\"i18n\":{\"en\":{\"question_text\":\"Following image:\",\"option_A\":\"1\",\"option_B\":\"2\",\"option_C\":\"3\",\"option_D\":\"4\"}},\"A\":\"1\",\"B\":\"2\",\"C\":\"3\",\"D\":\"4\"}', 'A', '', 'medium', '2025', '', 'images/qimg_68b1c17362b77.png', 1, 1, '2025-08-29 11:14:28'),
(150, 'Polity - Which of the following powers', 'Polity', 'Court', 'Parliament', 'Which of the following powers is/are vested in the President of India?\n(i) Granting pardons\n(ii) Dissolving the Lok Sabha\n(iii) Appointing the Prime Minister\n(iv) Making laws independently', '{\"A\":{\"text\":\"(i), (ii), and (iii) only\",\"image\":\"\"},\"B\":{\"text\":\"(i) and (iv) only\",\"image\":\"\"},\"C\":{\"text\":\"All of the above\",\"image\":\"\"},\"D\":{\"text\":\"(ii) and (iii) only\",\"image\":\"\"},\"E\":{\"text\":\"Answer not known\",\"image\":\"\"},\"i18n\":{\"ta\":{\"question_text\":\"\\u0b87\\u0ba8\\u0bcd\\u0ba4\\u0bbf\\u0baf\\u0bbe\\u0bb5\\u0bbf\\u0bb2\\u0bcd \\u0b95\\u0bc1\\u0b9f\\u0bbf\\u0baf\\u0bb0\\u0b9a\\u0bc1\\u0ba4\\u0bcd \\u0ba4\\u0bb2\\u0bc8\\u0bb5\\u0bb0\\u0bcd \\u0b95\\u0bc0\\u0bb4\\u0bcd\\u0b95\\u0bbe\\u0ba3\\u0bc1\\u0bae\\u0bcd \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0bbe\\u0bb0\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bc8 \\u0b89\\u0b9f\\u0bc8\\u0baf\\u0bb5\\u0bb0\\u0bbe?\\n(i) \\u0bae\\u0ba9\\u0bcd\\u0ba9\\u0bbf\\u0baa\\u0bcd\\u0baa\\u0bc1\\u0b95\\u0bcd \\u0b95\\u0bca\\u0b9f\\u0bc1\\u0b95\\u0bcd\\u0b95\\u0bc1\\u0bae\\u0bcd \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0bbe\\u0bb0\\u0bae\\u0bcd\\n(ii) \\u0bb2\\u0bcb\\u0b95\\u0bcd\\u0b9a\\u0baa\\u0bbe\\u0bb5\\u0bc8 \\u0b95\\u0bb0\\u0bc8\\u0b9a\\u0bb2\\u0bcd \\u0b9a\\u0bc6\\u0baf\\u0bcd\\u0baf\\u0bc1\\u0bae\\u0bcd \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0bbe\\u0bb0\\u0bae\\u0bcd\\n(iii) \\u0baa\\u0bbf\\u0bb0\\u0ba4\\u0bae\\u0bb0\\u0bc8 \\u0ba8\\u0bbf\\u0baf\\u0bae\\u0bbf\\u0b95\\u0bcd\\u0b95\\u0bc1\\u0bae\\u0bcd \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0bbe\\u0bb0\\u0bae\\u0bcd\\n(iv) \\u0ba4\\u0ba9\\u0bbf\\u0baf\\u0bc7 \\u0b9a\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bc8 \\u0b89\\u0bb0\\u0bc1\\u0bb5\\u0bbe\\u0b95\\u0bcd\\u0b95\\u0bc1\\u0bae\\u0bcd \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0bbe\\u0bb0\\u0bae\\u0bcd\",\"A\":\"(i), (ii), \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iii) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"B\":\"(i) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iv) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"C\":\"\\u0bae\\u0bc7\\u0bb2\\u0bc1\\u0bb3\\u0bcd\\u0bb3 \\u0b85\\u0ba9\\u0bc8\\u0ba4\\u0bcd\\u0ba4\\u0bc1\\u0bae\\u0bcd\",\"D\":\"(ii) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iii) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"E\":\"\\u0bb5\\u0bbf\\u0b9f\\u0bc8 \\u0ba4\\u0bc6\\u0bb0\\u0bbf\\u0baf\\u0bb5\\u0bbf\\u0bb2\\u0bcd\\u0bb2\\u0bc8\"},\"en\":{\"A\":\"(i), (ii), and (iii) only\",\"B\":\"(i) and (iv) only\",\"C\":\"All of the above\",\"D\":\"(ii) and (iii) only\",\"E\":\"Answer not known\"}}}', 'A', 'President has powers to grant pardons, dissolve Lok Sabha, and appoint the PM. The President cannot make laws independently; laws require Parliament.', 'easy', '2025', 'TNPSC', '', 1, 1, '2025-08-31 15:59:27'),
(151, 'Polity - Identify the correct statement(s) regarding', 'Polity', 'Court', 'Parliament', 'Identify the correct statement(s) regarding the Union Parliament of India:\n(i) Rajya Sabha is a permanent body.\n(ii) Lok Sabha has a maximum strength of 545 members.\n(iii) Only the Lok Sabha can initiate money bills.\n(iv) The Parliament can amend the Constitution without any restriction.', '{\"A\":{\"text\":\"(i), (ii), and (iii) only\",\"image\":\"\"},\"B\":{\"text\":\"(i) and (iv) only\",\"image\":\"\"},\"C\":{\"text\":\"(ii) and (iii) only\",\"image\":\"\"},\"D\":{\"text\":\"All of the above\",\"image\":\"\"},\"E\":{\"text\":\"Answer not known\",\"image\":\"\"},\"i18n\":{\"ta\":{\"question_text\":\"\\u0b87\\u0ba8\\u0bcd\\u0ba4\\u0bbf\\u0baf\\u0bbe \\u0b92\\u0ba9\\u0bcd\\u0bb1\\u0bbf\\u0baf \\u0b9a\\u0baa\\u0bc8 \\u0b95\\u0bc1\\u0bb1\\u0bbf\\u0ba4\\u0bcd\\u0ba4 \\u0b9a\\u0bb0\\u0bbf\\u0baf\\u0bbe\\u0ba9 \\u0b95\\u0bc2\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0b95\\u0bb3\\u0bc8 \\u0b95\\u0ba3\\u0bcd\\u0b9f\\u0bb1\\u0bbf\\u0b95:\\n(i) \\u0bb0\\u0bbe\\u0b9c\\u0bcd\\u0baf \\u0b9a\\u0baa\\u0bc8 \\u0ba8\\u0bbf\\u0bb2\\u0bc8\\u0baf\\u0bbe\\u0ba9 \\u0b85\\u0bae\\u0bc8\\u0baa\\u0bcd\\u0baa\\u0bc1.\\n(ii) \\u0bb2\\u0bcb\\u0b95\\u0bcd\\u0b9a\\u0baa\\u0bbe \\u0b85\\u0ba4\\u0bbf\\u0b95\\u0baa\\u0b9f\\u0bcd\\u0b9a\\u0bae\\u0bbe\\u0b95 545 \\u0b89\\u0bb1\\u0bc1\\u0baa\\u0bcd\\u0baa\\u0bbf\\u0ba9\\u0bb0\\u0bcd\\u0b95\\u0bb3\\u0bcd \\u0b95\\u0bca\\u0ba3\\u0bcd\\u0b9f\\u0bbf\\u0bb0\\u0bc1\\u0b95\\u0bcd\\u0b95\\u0bb2\\u0bbe\\u0bae\\u0bcd.\\n(iii) \\u0baa\\u0ba3\\u0b9a\\u0bcd \\u0b9a\\u0b9f\\u0bcd\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb3\\u0bc8 \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd \\u0bb2\\u0bcb\\u0b95\\u0bcd\\u0b9a\\u0baa\\u0bbe \\u0ba4\\u0bca\\u0b9f\\u0b99\\u0bcd\\u0b95\\u0bb2\\u0bbe\\u0bae\\u0bcd.\\n(iv) \\u0b85\\u0bb0\\u0b9a\\u0bbf\\u0baf\\u0bb2\\u0bae\\u0bc8\\u0baa\\u0bcd\\u0baa\\u0bc8 \\u0b8e\\u0ba8\\u0bcd\\u0ba4 \\u0b95\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0baa\\u0bcd\\u0baa\\u0bbe\\u0b9f\\u0bc1\\u0bae\\u0bcd \\u0b87\\u0bb2\\u0bcd\\u0bb2\\u0bbe\\u0bae\\u0bb2\\u0bcd \\u0bae\\u0bbe\\u0bb1\\u0bcd\\u0bb1\\u0bb2\\u0bbe\\u0bae\\u0bcd.\",\"A\":\"(i), (ii), \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iii) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"B\":\"(i) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iv) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"C\":\"(ii) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iii) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"D\":\"\\u0bae\\u0bc7\\u0bb2\\u0bc1\\u0bb3\\u0bcd\\u0bb3 \\u0b85\\u0ba9\\u0bc8\\u0ba4\\u0bcd\\u0ba4\\u0bc1\\u0bae\\u0bcd\",\"E\":\"\\u0bb5\\u0bbf\\u0b9f\\u0bc8 \\u0ba4\\u0bc6\\u0bb0\\u0bbf\\u0baf\\u0bb5\\u0bbf\\u0bb2\\u0bcd\\u0bb2\\u0bc8\"},\"en\":{\"A\":\"(i), (ii), and (iii) only\",\"B\":\"(i) and (iv) only\",\"C\":\"(ii) and (iii) only\",\"D\":\"All of the above\",\"E\":\"Answer not known\"}}}', 'A', 'Rajya Sabha is permanent; Lok Sabha can have up to 545 members; only Lok Sabha can introduce money bills. Constitutional amendments require special procedures; not unrestricted.', 'easy', '2026', 'TNPSC', '', 1, 1, '2025-08-31 15:59:27');

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

-- --------------------------------------------------------

--
-- Table structure for table `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, 'superadmin', '4e4c56e4a15f89f05c2f4c72613da2a18c9665d4f0d6acce16415eb06f9be776', 'super_admin', 'Platform Super Admin', 'superadmin@example.com', '2025-06-26 15:22:23', 0),
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
-- Indexes for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_test_question` (`test_id`,`question_id`),
  ADD KEY `question_id` (`question_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `test_packs`
--
ALTER TABLE `test_packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- Constraints for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test_packs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE;

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
