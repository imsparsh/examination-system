-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2014 at 05:35 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `examination`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `resetToken` varchar(255) DEFAULT NULL,
  `resetComplete` varchar(3) DEFAULT 'No',
  `membersession` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`memberID`),
  UNIQUE KEY `uniq` (`username`),
  UNIQUE KEY `membersession` (`membersession`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;


-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `qid` int(2) NOT NULL,
  `question` varchar(2000) DEFAULT NULL,
  `ans1` varchar(1000) DEFAULT NULL,
  `ans2` varchar(1000) DEFAULT NULL,
  `ans3` varchar(1000) DEFAULT NULL,
  `ans4` varchar(1000) DEFAULT NULL,
  `cans` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`qid`, `question`, `ans1`, `ans2`, `ans3`, `ans4`, `cans`) VALUES
(1, 'What is the Full Form of PHP?', 'Post Hypertext Processor', 'Hypertext PreProcessor', 'Personal Home Page', 'Preformatted Hypertext Processor', '2'),
(2, 'To which platform does ASP.NET language belongs?', 'Microsoft Windows', 'Unix', 'Apple MacOS', 'Solaris', '1'),
(3, 'To which platform does PHP language belongs?', 'Microsoft Windows', 'Unix/Linux', 'Apple MacOS', 'Solaris', '2'),
(4, 'The PHP syntax is most similar to: ', 'VBScript', 'JavaScript', 'Perl and C', 'Python', '3'),
(5, 'What does HTML stand for?', 'Hyper Text Markup \nLanguage', 'Hyperlinks and Text Markup Language', 'Home Tool Markup \nLanguage', 'Highly Text Markup Language', '1'),
(6, 'Who is making the Web standards?', 'Microsoft', 'Google', 'The World Wide Web Consortium', 'Mozilla', '3'),
(7, 'What is the correct HTML for creating a hyperlink?', '&lt;a \nname=&quot;http://smarttutorials.net&quot;&gt;Smart \nTutorials&lt;/a&gt;', '&lt;a&gt;http://smarttutorials.net&lt;/a&gt;', '&lt;a \nurl=&quot;http://smarttutorials.net&quot;&gt;Smart \nTutorials&lt;/a&gt;', '&lt;a \nhref=&quot;http://smarttutorials.net&quot;&gt;Smart \nTutorials&lt;/a&gt;', '4'),
(8, 'What is the HTML element to bold a text?', '&lt;b&gt;', '&lt;bold&gt;', '&lt;wide&gt;', '&lt;big&gt;', '1'),
(9, 'What is the HTML tag for a link?', '&lt;link&gt;', '&lt;ref&gt;', '&lt;a&gt;', '&lt;hper&gt;', '3'),
(10, 'What does CSS stand for?', 'Creative Style\n Sheets', 'Colorful Style Sheets', 'Computer Style Sheets', 'Cascading \nStyle Sheets', '4');

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `memberID` int(11) NOT NULL,
  `status` varchar(4) DEFAULT NULL,
  `correct` int(3) DEFAULT NULL,
  `incorrect` int(3) DEFAULT NULL,
  `unmarked` int(3) DEFAULT NULL,
  `percentage` int(3) DEFAULT NULL,
  UNIQUE KEY `memberID` (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

