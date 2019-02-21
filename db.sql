-- phpMyAdmin SQL Dump
-- version 4.0.10deb1ubuntu0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2019 at 08:46 AM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 7.2.12-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lookie`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `shortName` varchar(32) NOT NULL,
  `IP` varchar(32) NOT NULL,
  `views` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `name` varchar(512) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `base` varchar(32) NOT NULL DEFAULT '',
  `origin` varchar(1024) NOT NULL DEFAULT '',
  `artist` varchar(512) NOT NULL DEFAULT '',
  `description` varchar(1024) NOT NULL DEFAULT '',
  `lastviewed` datetime DEFAULT NULL,
  `autodelete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2146909654 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `IP` varchar(16) NOT NULL,
  `last_login` datetime NOT NULL,
  `pass` varchar(32) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `asset_id` int(11) NOT NULL,
  `IP` bigint(20) NOT NULL,
  `vote` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
