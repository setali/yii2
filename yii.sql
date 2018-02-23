-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2018 at 01:49 PM
-- Server version: 5.7.18-1
-- PHP Version: 7.0.20-2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii`
--

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_persian_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1518969560),
('m130524_201442_init', 1518969579);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'salim', 'XGqPKcC4Tc3pGN_8C5odDrqTtHQOVIu_', '$2y$13$5WYVJAMoMD/kHDqrdVBcZuHi1clr1OtK4AriSoa74JcTvQRI.DTw.', NULL, 'set.ali.mousavi@gmail.com', 10, 1518969648, 1518969648),
(2, 'seyed', '9MAIjBLhcZCUIK_69CNLds45HU-FxcZt', '$2y$13$Gp2K/su/qLNdCY7asTg40uW9zF.TS6qMBNrz99iyOUKcXBpwOkVA2', NULL, 'seyed@gmail.com', 10, 1519070138, 1519070138);

-- --------------------------------------------------------

--
-- Table structure for table `youtube_video`
--

CREATE TABLE `youtube_video` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `channel_id` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `channel_title` varchar(511) COLLATE utf8_persian_ci NOT NULL,
  `video_id` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `title` varchar(511) COLLATE utf8_persian_ci NOT NULL,
  `description` text COLLATE utf8_persian_ci NOT NULL,
  `published_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `youtube_video`
--

INSERT INTO `youtube_video` (`id`, `owner_id`, `channel_id`, `channel_title`, `video_id`, `title`, `description`, `published_at`) VALUES
(19, 1, 'UCUQ_-0cMMnvq3QnWGjyJtRw', 'SAli Mousavi', 'h3oJQBj8JYM', 'محقق غازچران', 'آدم واقعا متاسف میشه', 2018),
(20, 1, 'UCUQ_-0cMMnvq3QnWGjyJtRw', 'SAli Mousavi', '471QdIQtJck', 'این کوچولو چه چیزا بلده', 'ماشالله بهش', 2018);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `youtube_video`
--
ALTER TABLE `youtube_video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `video_id` (`video_id`),
  ADD KEY `owner` (`owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `youtube_video`
--
ALTER TABLE `youtube_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `youtube_video`
--
ALTER TABLE `youtube_video`
  ADD CONSTRAINT `owner` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
