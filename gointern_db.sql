-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2019 at 12:02 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gointern_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `app_id` int(11) NOT NULL,
  `std_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `int_id` int(11) DEFAULT NULL,
  `selected` tinyint(1) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`app_id`, `std_id`, `emp_id`, `int_id`, `selected`, `date`) VALUES
(1, 1, 2, 13, 1, '2019-08-01 17:29:20'),
(2, 1, 1, 4, NULL, '2019-08-01 18:17:47'),
(3, 1, 1, 2, 0, '2019-08-01 18:25:46'),
(4, 2, 1, 3, NULL, '2019-08-01 22:02:34'),
(5, 2, 2, 13, 1, '2019-08-01 22:09:01'),
(6, 1, 1, 1, NULL, '2019-08-01 22:22:00'),
(7, 2, 1, 2, 1, '2019-08-01 22:42:24'),
(8, 1, 2, 14, NULL, '2019-08-01 23:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`emp_id`, `name`, `email`, `location`, `pwd`, `date`) VALUES
(1, 'Novo Tech', 'novotech@gmail.com', 'Kolkata', '26f2174bc12ca9e20f43f078fafe6221', '2019-07-31 23:46:27'),
(2, 'iCoders', 'icoders@gmail.com', 'Durgapur', '72e70ce4265038091d2361ef7a0815b2', '2019-08-01 16:46:23');

-- --------------------------------------------------------

--
-- Table structure for table `internship_posts`
--

CREATE TABLE `internship_posts` (
  `int_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `location` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `skills_req` text NOT NULL,
  `responsibilities` text NOT NULL,
  `stipend` varchar(20) NOT NULL DEFAULT '0',
  `duration` varchar(20) NOT NULL,
  `last_date` date NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `internship_posts`
--

INSERT INTO `internship_posts` (`int_id`, `title`, `location`, `start_date`, `skills_req`, `responsibilities`, `stipend`, `duration`, `last_date`, `emp_id`, `date`) VALUES
(1, 'Content Writing', 'Kolkata', '2019-08-20', 'Social Media Marketing and Creative Writing', '1. Copywriting, Managing Hindi/English content/tweets \r\n2. Monitor and post on blogs, forums, and social networks\r\n3. Optimize website and social media\r\n4. Assist with online outreach and promotion using Facebook, Instagram, LinkedIn, Twitter, and more\r\n5. Calling for leads (if required)', '2000 /Month', '2 Month(s)', '2019-08-10', 1, '2019-08-01 14:36:10'),
(2, 'Web Development', 'Delhi', '2019-08-16', 'WordPress', 'Selected interns will be working as a WordPress developer to update an existing website.', '5000 /Month', '3 Month(s)', '2019-08-17', 1, '2019-08-01 14:39:01'),
(3, 'UNIX Scripting', 'Work from Home', '2019-08-15', 'Unix, Shell Commands', 'Selected intern\'s day-to-day responsibilities include:\r\n\r\n1. Developing Scripts for different versions of Unix (RedHat, Fedora, CentOS, Debian)', '300 /Week', '2 Week(s)', '2019-08-08', 1, '2019-08-01 15:25:07'),
(4, 'Mobile App Development', 'Durgapur', '2019-08-30', 'Java, JavaScript, Node.js, Android', 'Selected intern\'s day-to-day responsibilities include:\r\n\r\n1. Designing, developing, and maintaining the Android application\r\n2. Working from design to backend Java development\r\n3. Creating and maintaining a robust framework to support the apps\r\n4. Building good UI along with great features so as to enable the best user experience\r\n5. Creating compelling device specific user interfaces and experiences', '0 //Week', '1 Week(s)', '2019-08-14', 1, '2019-08-01 15:29:58'),
(13, 'Web Developer', 'Durgapur', '2019-08-24', 'HTML,CSS,JavaScript,jQuery', 'Selected intern\'s day-to-day responsibilities include: \r\n\r\n1. Build html pages from wireframes and libraries provided by us\r\n2. Research and assist in developing internal libraries of various UI widgets\r\n3. Integrate UI with back end server using javascript (optional)', '0 //Week', '2 Month(s)', '2019-08-17', 2, '2019-08-01 16:49:03'),
(14, 'Content Writing', 'Durgapur', '2019-08-24', 'English Proficiency (Written)', 'Selected intern\'s day-to-day responsibilities include: \r\n\r\n1. Do research regarding interesting concepts and facts related to science through various online resources\r\n2. Write comprehensive and easy-to-understand science articles which an average person can easily understand', '1000 //Month', '6 Week(s)', '2019-08-15', 2, '2019-08-01 23:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `std_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `skills` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`std_id`, `name`, `email`, `pwd`, `skills`, `date`) VALUES
(1, 'Shivam Kumar', 'shivam@gmail.com', 'fba660a50bb1744203b4dbb0a91ed469', 'HTML,CSS,JS,PHP,Node.js', '2019-07-31 23:51:57'),
(2, 'Anshuman Shikhar', 'anshuman@gmail.com', 'aabde52194f82228521f6f6ccb48fe67', 'C, C++, Python, Java', '2019-08-01 22:02:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `applications_ibfk_1` (`std_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `int_id` (`int_id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `internship_posts`
--
ALTER TABLE `internship_posts`
  ADD PRIMARY KEY (`int_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`std_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `internship_posts`
--
ALTER TABLE `internship_posts`
  MODIFY `int_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `std_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`std_id`) REFERENCES `students` (`std_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`emp_id`) REFERENCES `employers` (`emp_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`int_id`) REFERENCES `internship_posts` (`int_id`) ON DELETE SET NULL;

--
-- Constraints for table `internship_posts`
--
ALTER TABLE `internship_posts`
  ADD CONSTRAINT `internship_posts_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employers` (`emp_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
