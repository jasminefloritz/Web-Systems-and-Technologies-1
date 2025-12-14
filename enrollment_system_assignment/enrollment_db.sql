-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 03:06 PM
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
-- Database: `enrollment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` enum('enrolled','completed') DEFAULT 'enrolled',
  `grade` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `subject_id`, `status`, `grade`) VALUES
(1, 13, 0, 'enrolled', NULL),
(2, 13, 1, 'completed', '1.00'),
(3, 13, 2, 'completed', '1.00'),
(4, 16, 2, 'enrolled', NULL),
(5, 16, 1, 'enrolled', NULL),
(6, 20, 12, 'completed', '2.00'),
(7, 20, 14, 'enrolled', NULL),
(8, 20, 13, 'enrolled', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `enrollment_id` int(11) NOT NULL,
  `grade` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`enrollment_id`, `grade`) VALUES
(0, '1.00'),
(2, '1.00'),
(3, '1.00'),
(6, '2.00');

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `subject_id` int(11) NOT NULL,
  `prerequisite_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prerequisites`
--

INSERT INTO `prerequisites` (`subject_id`, `prerequisite_id`) VALUES
(13, 12);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `name`) VALUES
(9, '2222', 'Web Systems and Technologies 2'),
(12, '1111', 'MAD 1'),
(13, '4444', 'MAD 2'),
(14, '3333', 'Networking');

-- --------------------------------------------------------

--
-- Table structure for table `subject_faculty`
--

CREATE TABLE `subject_faculty` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_faculty`
--

INSERT INTO `subject_faculty` (`id`, `faculty_id`, `subject_id`) VALUES
(1, 6, 0),
(2, 6, 1),
(3, 6, 2),
(4, 18, 8),
(5, 18, 10),
(6, 18, 12),
(7, 18, 13),
(8, 18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('student','faculty','admin') NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `signature` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `profile_pic`, `signature`) VALUES
(9, 'admin', 'System Admin', 'admin@sample.com', '$2y$10$Pinjx6sTKtcS3Ujs7oAvWuwAGrYLlH6wYrUiJEr1Tbj18ok51iH/K', '1765711249_default-profile-picture1.jpg', '1765711249_signature_sample1.jpg'),
(18, 'faculty', 'Floritz Dumpit', 'floritz@sample.com', '$2y$10$DOIca1p82JlfaLyC/Yaoyuv8ZImDjqT4osOd5J/zIXPYY4J.rurkK', 'istockphoto-1131282201-612x612.jpg', 'signature_sample.png'),
(19, 'student', 'Franxene Dumpit', 'franxene@sample.com', '$2y$10$rfLJTWi5oZDj.FW2Y4o7YOui0btFkx9NoRVIWkNMifQBwGovz8nYa', 'woman-with-long-brown-hair-pink-shirt_90220-2940.avif', 'signature_sample1.jpg'),
(20, 'student', 'Floritz Jasmine Dumpit', 'jasmine@gmail.com', '$2y$10$Zq5UX9m/dPxrXjUPCZnhHurmwvnDG/.XvYZ5LFnynGCZKuTQobHNC', 'istockphoto-1131282201-612x612.jpg', 'signature_sample.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_faculty`
--
ALTER TABLE `subject_faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `subject_faculty`
--
ALTER TABLE `subject_faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
