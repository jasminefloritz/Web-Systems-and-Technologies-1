-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 09:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thesis_repository`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `activity_log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `logged_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`activity_log_id`, `user_id`, `action`, `logged_at`) VALUES
(1, 3, 'Submitted thesis', '2025-12-19 09:29:43'),
(2, 0, 'Submitted thesis', '2025-12-19 10:40:22'),
(3, 3, 'Submitted thesis: Test Sis', '2025-12-19 11:06:31'),
(4, 3, 'Submitted thesis: fggfh', '2025-12-19 11:07:15'),
(5, 3, 'Submitted thesis: fggfh', '2025-12-19 11:15:59'),
(6, 3, 'Submitted thesis: fggfh', '2025-12-19 11:18:49'),
(7, 3, 'Submitted thesis: fggfh', '2025-12-19 11:18:51'),
(8, 3, 'Submitted thesis: fggfh', '2025-12-19 11:20:06'),
(9, 3, 'Submitted thesis: fggfh', '2025-12-19 11:20:15'),
(10, 3, 'Submitted thesis: fggfh', '2025-12-19 11:22:23'),
(11, 3, 'Submitted thesis: fggfh', '2025-12-19 11:22:26'),
(12, 3, 'Submitted thesis: fggfh', '2025-12-19 11:22:34'),
(13, 3, 'Submitted thesis: fggfh', '2025-12-19 11:25:24'),
(14, 3, 'Submitted thesis: Halo', '2025-12-19 11:25:50'),
(15, 3, 'Submitted thesis: sdgf', '2025-12-19 12:01:18'),
(16, 3, 'Submitted thesis: esdfdsegf', '2025-12-19 12:02:51'),
(17, 3, 'Submitted thesis: esdfdsegf', '2025-12-19 12:04:05'),
(18, 3, 'Submitted thesis: sdgf', '2025-12-19 12:04:12'),
(19, 3, 'Submitted thesis: esdfdsegf', '2025-12-19 12:04:22'),
(20, 3, 'Submitted thesis: dxfhdfh', '2025-12-19 12:45:09'),
(21, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:37:12'),
(22, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:37:14'),
(23, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:37:16'),
(24, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:37:16'),
(25, 4, 'Reviewed thesis #20: rejected', '2025-12-19 15:37:17'),
(26, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:37:18'),
(27, 4, 'Reviewed thesis #20: rejected', '2025-12-19 15:37:19'),
(28, 4, 'Reviewed thesis #19: approved', '2025-12-19 15:37:20'),
(29, 4, 'Reviewed thesis #16: approved', '2025-12-19 15:37:22'),
(30, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:51:27'),
(31, 4, 'Reviewed thesis #19: approved', '2025-12-19 15:51:33'),
(32, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:59:29'),
(33, 4, 'Reviewed thesis #20: approved', '2025-12-19 15:59:31'),
(34, 4, 'Reviewed thesis #20: rejected', '2025-12-19 15:59:34'),
(35, 4, 'Reviewed thesis #5: approved', '2025-12-19 15:59:36'),
(36, 4, 'Reviewed thesis #20: approved', '2025-12-19 16:02:25'),
(37, 4, 'Reviewed thesis #20: approved', '2025-12-19 16:02:26'),
(38, 4, 'Reviewed thesis #18: approved', '2025-12-19 16:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `approval_id` int(11) NOT NULL,
  `thesis_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `decision` enum('approved','rejected') DEFAULT NULL,
  `decision_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`approval_id`, `thesis_id`, `reviewer_id`, `decision`, `decision_date`) VALUES
(1, 20, 2, 'rejected', '2025-12-19 14:57:16'),
(2, 20, 2, 'rejected', '2025-12-19 14:57:19'),
(3, 17, 2, 'rejected', '2025-12-19 14:57:27'),
(4, 20, 4, 'approved', '2025-12-19 15:37:12'),
(5, 20, 4, 'approved', '2025-12-19 15:37:14'),
(6, 20, 4, 'approved', '2025-12-19 15:37:16'),
(7, 20, 4, 'approved', '2025-12-19 15:37:16'),
(8, 20, 4, 'rejected', '2025-12-19 15:37:17'),
(9, 20, 4, 'approved', '2025-12-19 15:37:18'),
(10, 20, 4, 'rejected', '2025-12-19 15:37:19'),
(11, 19, 4, 'approved', '2025-12-19 15:37:20'),
(12, 16, 4, 'approved', '2025-12-19 15:37:22'),
(13, 20, 2, 'approved', '2025-12-19 15:50:31'),
(14, 19, 2, 'approved', '2025-12-19 15:50:32'),
(15, 18, 2, 'approved', '2025-12-19 15:50:34'),
(16, 20, 4, 'approved', '2025-12-19 15:51:27'),
(17, 19, 4, 'approved', '2025-12-19 15:51:33'),
(18, 20, 4, 'approved', '2025-12-19 15:59:29'),
(19, 20, 4, 'approved', '2025-12-19 15:59:31'),
(20, 20, 4, 'rejected', '2025-12-19 15:59:34'),
(21, 5, 4, 'approved', '2025-12-19 15:59:36'),
(22, 20, 4, 'approved', '2025-12-19 16:02:25'),
(23, 20, 4, 'approved', '2025-12-19 16:02:26'),
(24, 18, 4, 'approved', '2025-12-19 16:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `archives`
--

CREATE TABLE `archives` (
  `archive_id` int(11) NOT NULL,
  `thesis_id` int(11) DEFAULT NULL,
  `archived_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Department of Computer Science'),
(2, 'Department of Information Technology'),
(3, 'Department of Engineering'),
(4, 'Department of Business Administration'),
(5, 'Department of Education'),
(6, 'Department of Arts and Sciences'),
(7, 'Department of Nursing'),
(8, 'Department of Accountancy');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `thesis_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`file_id`, `thesis_id`, `file_path`, `file_type`, `uploaded_at`) VALUES
(1, 1, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 09:29:43'),
(2, 2, '', 'pdf', '2025-12-19 10:40:22'),
(3, 8, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 11:20:06'),
(4, 13, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 11:25:24'),
(5, 14, 'jquery-3.7.1.js', 'js', '2025-12-19 11:25:50'),
(6, 15, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 12:01:18'),
(7, 16, 'Rossi Phone Wallpaper Motogp.jpg', 'jpg', '2025-12-19 12:02:51'),
(8, 17, 'Rossi Phone Wallpaper Motogp.jpg', 'jpg', '2025-12-19 12:04:05'),
(9, 18, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 12:04:12'),
(10, 19, 'Rossi Phone Wallpaper Motogp.jpg', 'jpg', '2025-12-19 12:04:22'),
(11, 20, 'NET Programmming DBASE.pptx.pdf', 'pdf', '2025-12-19 12:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(150) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_name`, `department_id`) VALUES
(1, 'BS in Computer Science', 1),
(2, 'BS in Information Technology', 2),
(3, 'BS in Software Engineering', 3),
(4, 'BS in Information Systems', 2),
(5, 'BS in Business Administration', 4),
(6, 'BS in Accountancy', 8),
(7, 'BS in Education', 5),
(8, 'BS in Nursing', 7),
(9, 'BS in Psychology', 6),
(10, 'BA in Communication', 6);

-- --------------------------------------------------------

--
-- Table structure for table `review_logs`
--

CREATE TABLE `review_logs` (
  `review_log_id` int(11) NOT NULL,
  `thesis_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theses`
--

CREATE TABLE `theses` (
  `thesis_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `abstract` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theses`
--

INSERT INTO `theses` (`thesis_id`, `title`, `abstract`, `keywords`, `author_id`, `adviser_id`, `department_id`, `program_id`, `year`, `status`, `submitted_at`) VALUES
(1, 'Test Sis', '', '', 3, NULL, 0, 0, 0, 'pending', '2025-12-19 09:29:43'),
(2, 'sadf', 'sdfdsfds', 'dsfsd', 0, NULL, 0, 0, 0, 'pending', '2025-12-19 10:40:22'),
(3, 'Test Sis', 'wetewt', 'wetwe.wetgsd,sdgsd', 3, 0, 0, 0, NULL, 'pending', '2025-12-19 11:06:31'),
(4, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 0, 4, 4, 0, 'pending', '2025-12-19 11:07:15'),
(5, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'approved', '2025-12-19 11:15:59'),
(6, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:18:49'),
(7, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:18:51'),
(8, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:20:06'),
(9, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:20:15'),
(10, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:22:23'),
(11, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:22:25'),
(12, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:22:34'),
(13, 'fggfh', 'dghdfsg', 'dsfgfd,dfghdf,dhfdfh', 3, 4, 4, 4, 0, 'pending', '2025-12-19 11:25:24'),
(14, 'Halo', 'asfgads', 'asf,af,', 3, 4, 1, 7, 1, 'pending', '2025-12-19 11:25:50'),
(15, 'sdgf', 'dfgdf', 'dfh', 3, 0, 1, 7, 0, 'pending', '2025-12-19 12:01:18'),
(16, 'esdfdsegf', 'dsfgdsf', 'asf,af,', 3, 4, 1, 2, 4, 'approved', '2025-12-19 12:02:51'),
(17, 'esdfdsegf', 'dsfgdsf', 'asf,af,', 3, 4, 1, 2, 4, 'pending', '2025-12-19 12:04:05'),
(18, 'sdgf', 'dfgdf', 'dfh', 3, 0, 1, 7, 0, 'approved', '2025-12-19 12:04:12'),
(19, 'esdfdsegf', 'dsfgdsf', 'asf,af,', 3, 4, 1, 2, 4, 'approved', '2025-12-19 12:04:22'),
(20, 'dxfhdfh', 'dhfdsfh', 'sdfhdsf', 3, 4, 3, 2, 2, 'approved', '2025-12-19 12:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','faculty','admin') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `role`, `profile_picture`, `signature`, `department_id`, `program_id`, `created_at`) VALUES
(2, 'Ad Min', 'admin@sample.com', '$2y$10$2hBRiNWVvrg6iBrJupsvV.J3tKsOEPRaqoEQogMwvhk.8V2xUhU3u', 'admin', '1766125162_Motogp_Season_Is_Upon_Us_And_Boris_Chomping_At_The_Bit.jpg', '1766125515_joachim-gaucks-signature-download-transparent-png--35.webp', NULL, NULL, '2025-12-19 09:06:25'),
(3, 'Jazzie', 'jazzie@sample.com', '$2y$10$JizZQ8.ZKG9941bBR7gSYugploI8I4BaW4kJAWFyrpKobJ6mu2Yey', 'student', 'Rossi Phone Wallpaper Motogp.jpg', 'joachim-gaucks-signature-download-transparent-png--35.webp', NULL, NULL, '2025-12-19 09:28:17'),
(4, 'Fa Cutie', 'faculty@sample.com', '$2y$10$OHYulhrkk9QiIM2qwTGiVenMYM1jQc4Kb.AyAZpfsfg5TVPhzj6QW', 'faculty', '1766130386_Mona-Lisa-oil-wood-panel-Leonardo-da.webp', '1766129170_joachim-gaucks-signature-download-transparent-png--35.webp', NULL, NULL, '2025-12-19 09:38:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`activity_log_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`approval_id`);

--
-- Indexes for table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`archive_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD PRIMARY KEY (`review_log_id`);

--
-- Indexes for table `theses`
--
ALTER TABLE `theses`
  ADD PRIMARY KEY (`thesis_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `activity_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `archives`
--
ALTER TABLE `archives`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `review_logs`
--
ALTER TABLE `review_logs`
  MODIFY `review_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theses`
--
ALTER TABLE `theses`
  MODIFY `thesis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
