-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2024 at 05:45 AM
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
-- Database: `project_ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_comments`
--

INSERT INTO `project_comments` (`id`, `project_id`, `user_id`, `comment`, `timestamp`) VALUES
(14, 22, 8, 'Please work in time guys!!', '2024-07-25 18:21:41'),
(15, 22, 20, 'Okay sir', '2024-07-25 18:22:59'),
(16, 22, 8, 'ok', '2024-08-02 19:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

CREATE TABLE `project_files` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(11) NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Process','Completed') NOT NULL DEFAULT 'Pending',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `project_name`, `status`, `start_date`, `end_date`, `description`, `user_id`) VALUES
(22, 'School management system', 'Pending', '2024-07-26', '2024-08-14', 'Do both frontend and backend', 8);

-- --------------------------------------------------------

--
-- Table structure for table `project_team_members`
--

CREATE TABLE `project_team_members` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_team_members`
--

INSERT INTO `project_team_members` (`id`, `project_id`, `user_id`) VALUES
(50, 22, 18),
(51, 22, 20);

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(11) NOT NULL,
  `task_name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(390) NOT NULL,
  `number` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `user_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `user_role`) VALUES
(8, 'admin', 'admin@admin.com', '987456123', '$2y$10$8fcMltYzcze789Kw.EDAVubC18bnY6bEiB2qEc4pZ1LtxRKcV3Mau', 1),
(18, 'Ashish Basnet', 'abasnet018@gmail.com', '9823819698', '$2y$10$P5vw8ltBJfWb5qW4R72nKeaCyGPwpWtJwY0dvRyaJpKIuuJmCJtDm', 2),
(19, 'Dinesh Rana Magar', 'dnes@gmail.com', '9874561235', '$2y$10$WHEDHFWi3mrwwxjMT/m6xO1qR.R14k1MzComa0xjdlk8wBHYp6Uwq', 2),
(20, 'Dipu Adhikari', 'dipu@gmail.com', '9856471230', '$2y$10$MnZEvCQxjN7xF8Me6LUV1OnYgDQFl5mBunxa2aVqgJRedkd5oRmY2', 2),
(21, 'Dipak Dhakal', 'dipak@gmail.com', '9856231471', '$2y$10$9tpDUYgr/ZdkfwkeEpELKeVfrxnsDJyUAqfiG60M3kd3E3sS3qiWK', 2),
(22, 'Rakesh Ayer', 'rakesh@gmail.com', '4116846514', '$2y$10$6wbhfIyood6M4GKIYpoNx.dYzvu85nT.jzda0tkesNR6X2tYv4/Ky', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_project_comments_project_id` (`project_id`);

--
-- Indexes for table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_files_uploaded_by` (`uploaded_by`),
  ADD KEY `fk_project_files_project_id` (`project_id`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_list_user` (`user_id`);

--
-- Indexes for table `project_team_members`
--
ALTER TABLE `project_team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
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
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `project_team_members`
--
ALTER TABLE `project_team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `fk_project_comments_project_id` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`),
  ADD CONSTRAINT `project_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_files`
--
ALTER TABLE `project_files`
  ADD CONSTRAINT `fk_project_files_project_id` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_files_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_list`
--
ALTER TABLE `project_list`
  ADD CONSTRAINT `fk_project_list_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `project_team_members`
--
ALTER TABLE `project_team_members`
  ADD CONSTRAINT `fk_project_team_members_project` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_project_team_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
