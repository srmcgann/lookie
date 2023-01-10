-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 10, 2023 at 10:22 AM
-- Server version: 8.0.31-0ubuntu0.22.04.1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lookie`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `date` datetime NOT NULL,
  `shortName` varchar(32) NOT NULL,
  `IP` varchar(32) NOT NULL,
  `views` int NOT NULL,
  `type` varchar(32) NOT NULL,
  `name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `public` tinyint(1) NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `votes` int NOT NULL DEFAULT '0',
  `size` int NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `base` varchar(32) NOT NULL DEFAULT '',
  `origin` varchar(1024) NOT NULL DEFAULT '',
  `artist` varchar(512) NOT NULL DEFAULT '',
  `description` varchar(1024) NOT NULL DEFAULT '',
  `lastviewed` datetime DEFAULT NULL,
  `autodelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `IP` varchar(16) NOT NULL,
  `last_login` datetime NOT NULL,
  `pass` varchar(32) NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `asset_id` int NOT NULL,
  `IP` bigint NOT NULL,
  `vote` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
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
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

