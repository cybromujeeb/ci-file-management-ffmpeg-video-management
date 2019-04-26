-- phpMyAdmin SQL Dump
-- version 4.4.15.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2019 at 01:49 PM
-- Server version: 10.0.38-MariaDB-0ubuntu0.16.04.1
-- PHP Version: 7.2.16-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ffmpeg`
--

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `video_id` int(11) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `video_random_key` varchar(255) DEFAULT NULL,
  `converted` int(11) NOT NULL DEFAULT '0',
  `video_upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`video_id`, `video_path`, `video_random_key`, `converted`, `video_upload_date`) VALUES
(1, 'uploads/042019/VID-20190413-WA0000.mp4', '5cc2ba16e2f79', 0, '2019-04-26 13:28:14'),
(2, 'uploads/cutvideos/042019/15562655120_VID-20190413-WA000026-04-2019-13-28-32.mp4', '5cc2ba315082f', 1, '2019-04-26 13:28:41'),
(3, 'uploads/042019/VID-20190423-WA0023.mp4', '5cc2ba9f7452a', 0, '2019-04-26 13:30:31'),
(4, 'uploads/cutvideos/042019/15562657220_VID-20190413-WA000026-04-2019-13-32-02.mp4', '5cc2bafbaff24', 1, '2019-04-26 13:32:03'),
(5, 'uploads/cutvideos/042019/15562657480_VID-20190413-WA000026-04-2019-13-32-28.mp4', '5cc2bb1607ee5', 1, '2019-04-26 13:32:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
