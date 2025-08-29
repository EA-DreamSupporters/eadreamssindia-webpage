-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 08:39 AM
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
(49, '', 'Economy', 'Econmic', '', '', '{\"type\":\"text_mcq\",\"A\":\"\",\"B\":\"\",\"C\":\"\",\"D\":\"\",\"E\":\"\",\"i18n\":{\"en\":{\"question_text\":\"1. Which of the following best describes the nature of the Indian economy?\\r\\n(i) Mixed economy\\r\\n(ii) Socialist economy\\r\\n(iii) Capitalist economy\\r\\n(iv) Agrarian economy\",\"options\":{\"A\":\"(i) only\",\"B\":\"(i) and (iv) only\",\"C\":\"(ii) and (iii) only\",\"D\":\" (ii), (iii), and (iv) only\",\"E\":\"\"}},\"ta\":{\"question_text\":\"1. \\u0b87\\u0ba8\\u0bcd\\u0ba4\\u0bbf\\u0baf \\u0baa\\u0bca\\u0bb0\\u0bc1\\u0bb3\\u0bbe\\u0ba4\\u0bbe\\u0bb0\\u0ba4\\u0bcd\\u0ba4\\u0bbf\\u0ba9\\u0bcd \\u0b87\\u0baf\\u0bb2\\u0bcd\\u0baa\\u0bc1 \\u0b95\\u0bc1\\u0bb1\\u0bbf\\u0ba4\\u0bcd\\u0ba4 \\u0baa\\u0bbf\\u0ba9\\u0bcd\\u0bb5\\u0bb0\\u0bc1\\u0bb5\\u0ba9\\u0bb5\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bb3\\u0bcd \\u0b8e\\u0ba4\\u0bc1 \\u0b9a\\u0bb0\\u0bbf\\u0baf\\u0bbe\\u0ba9\\u0ba4\\u0bc1?\\r\\n(i) \\u0b95\\u0bb2\\u0baa\\u0bcd\\u0baa\\u0bc1 \\u0baa\\u0bca\\u0bb0\\u0bc1\\u0bb3\\u0bbe\\u0ba4\\u0bbe\\u0bb0\\u0bae\\u0bcd\\r\\n(ii) \\u0b9a\\u0bae\\u0bc2\\u0b95\\u0ba8\\u0bb2\\u0bb5\\u0bbe\\u0ba4 \\u0baa\\u0bca\\u0bb0\\u0bc1\\u0bb3\\u0bbe\\u0ba4\\u0bbe\\u0bb0\\u0bae\\u0bcd\\r\\n(iii) \\u0bae\\u0bc2\\u0bb2\\u0ba4\\u0ba9\\u0bb5\\u0bbe\\u0ba4 \\u0baa\\u0bca\\u0bb0\\u0bc1\\u0bb3\\u0bbe\\u0ba4\\u0bbe\\u0bb0\\u0bae\\u0bcd\\r\\n(iv) \\u0bb5\\u0bbf\\u0bb5\\u0b9a\\u0bbe\\u0baf \\u0b86\\u0ba4\\u0bbe\\u0bb0 \\u0baa\\u0bca\\u0bb0\\u0bc1\\u0bb3\\u0bbe\\u0ba4\\u0bbe\\u0bb0\\u0bae\\u0bcd\\r\\n\",\"options\":{\"A\":\"(i) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"B\":\"(i) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iv) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"C\":\"(ii) \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iii) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"D\":\"(ii), (iii), \\u0bae\\u0bb1\\u0bcd\\u0bb1\\u0bc1\\u0bae\\u0bcd (iv) \\u0bae\\u0b9f\\u0bcd\\u0b9f\\u0bc1\\u0bae\\u0bcd\",\"E\":\"\"}},\"hi\":{\"question_text\":\"\",\"options\":{\"A\":\"\",\"B\":\"\",\"C\":\"\",\"D\":\"\",\"E\":\"\"}}}}', 'B', 'India is a mixed economy with both public and private sectors. It also has a strong agrarian base.', 'easy', '2025', 'TNPSC', 1, NULL, '2025-08-22 04:11:30');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `test_packs`
--
ALTER TABLE `test_packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
