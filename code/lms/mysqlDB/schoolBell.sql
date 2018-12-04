-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2018 at 07:08 AM
-- Server version: 5.5.44
-- PHP Version: 5.4.45-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `schoolBell`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE IF NOT EXISTS `action_log` (
  `colNum` bigint(16) NOT NULL AUTO_INCREMENT,
  `person` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `colNum` bigint(16) NOT NULL AUTO_INCREMENT,
  `fbdate` datetime NOT NULL,
  `fbresourceTitle` varchar(200) NOT NULL,
  `fbresourceID` varchar(20) NOT NULL,
  `fbstudentID` varchar(50) NOT NULL,
  `fbstudentName` varchar(300) NOT NULL,
  `fbstudentClass` varchar(15) NOT NULL,
  PRIMARY KEY (`colNum`),
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `LessonPlan`
--

CREATE TABLE IF NOT EXISTS `LessonPlan` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `class` varchar(3) NOT NULL,
  `DateOfEx` date NOT NULL,
  `Subject` varchar(300) NOT NULL,
  `Ref` text,
  `Time` time NOT NULL,
  `Duration` text NOT NULL,
  `Topic` varchar(300) NOT NULL,
  `Objective` text NOT NULL,
  `RPK` text NOT NULL,
  `BeLL_Resource_1` text,
  `BeLL_Resource_2` text,
  `BeLL_Resource_3` text,
  `BeLL_Resource_4` text,
  `BeLL_Resource_5` text,
  `Other_Resource_1` text,
  `Other_Resource_2` text,
  `Other_Resource_3` text,
  `Tech_Used_1` varchar(50) DEFAULT NULL,
  `Tech_Used_2` varchar(50) DEFAULT NULL,
  `Tech_Used_3` varchar(50) DEFAULT NULL,
  `Core_Points` text NOT NULL,
  `Introduction` text NOT NULL,
  `Pre_Writing` text NOT NULL,
  `Writing` text NOT NULL,
  `Post_Writing` text NOT NULL,
  `Conclusion` text NOT NULL,
  `Low_Order_Thinking_1` text,
  `Low_Order_Thinking_2` text,
  `Low_Order_Thinking_3` text,
  `Low_Order_Thinking_4` text,
  `Low_Order_Thinking_5` text,
  `High_Order_Thinking_1` text,
  `High_Order_Thinking_2` text,
  `High_Order_Thinking_3` text,
  `High_Order_Thinking_4` text,
  `High_Order_Thinking_5` text,
  `Teacher_Remark` text,
  `Head_Remark` text,
  `Coach_Remark` text,
  `DateUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `prepared_By` varchar(300) NOT NULL,
  PRIMARY KEY (`colNum`),
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `resrcID` varchar(10) NOT NULL,
  `subject` varchar(15) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(5) NOT NULL,
  `url` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `KG` varchar(3) NOT NULL DEFAULT 'NO',
  `P1` varchar(3) NOT NULL DEFAULT 'NO',
  `P2` varchar(3) NOT NULL DEFAULT 'NO',
  `P3` varchar(3) NOT NULL DEFAULT 'NO',
  `P4` varchar(3) NOT NULL DEFAULT 'NO',
  `P5` varchar(3) NOT NULL DEFAULT 'NO',
  `P6` varchar(3) NOT NULL DEFAULT 'NO',
  `Community` varchar(3) NOT NULL DEFAULT 'NO',
  `TLR` varchar(3) NOT NULL DEFAULT 'NO',
  PRIMARY KEY (`resrcID`),
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schoolDetails`
--

CREATE TABLE IF NOT EXISTS `schoolDetails` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `schoolName` varchar(300) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `schoolType` varchar(50) NOT NULL DEFAULT 'Public School',
  `dateOfEnrolment` date NOT NULL,
  PRIMARY KEY (`colNum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `schoolDetails`
--

INSERT INTO `schoolDetails` (`colNum`, `schoolName`, `location`, `schoolType`, `dateOfEnrolment`) VALUES
(3, 'Demo School', 'Accra', 'Public School', '2015-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `stuCode` varchar(5) NOT NULL,
  `stuName` varchar(200) NOT NULL,
  `stuClass` varchar(200) NOT NULL,
  `stuDOB` date NOT NULL,
  `stuGender` varchar(100) NOT NULL,
  `DateRegistered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `teacherClass`
--

CREATE TABLE IF NOT EXISTS `teacherClass` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `Name` varchar(300) NOT NULL,
  `Contact` varchar(300) NOT NULL,
  `loginId` varchar(30) NOT NULL,
  `pswd` text NOT NULL,
  `classAssign` varchar(3) DEFAULT NULL,
  `Role` varchar(50) NOT NULL,
  PRIMARY KEY (`loginId`),
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `teacherClass`
--

INSERT INTO `teacherClass` (`colNum`, `Name`, `Contact`, `loginId`, `pswd`, `classAssign`, `Role`) VALUES
(14, 'Ole Administrator', '0266663807', 'schoolbell', 'bfe20ca83cf931d29a95a28e308e80d7', 'Gen', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `usedResources`
--

CREATE TABLE IF NOT EXISTS `usedResources` (
  `colNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `resrcID` varchar(10) NOT NULL,
  `subject` varchar(15) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(5) NOT NULL,
  `usedby` varchar(50) NOT NULL,
  `dateUsed` date NOT NULL,
  `class` varchar(3) NOT NULL,
  `rating` int(5) NOT NULL,
  PRIMARY KEY (`colNum`),
  KEY `colNum` (`colNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `VBQuestion`
--

CREATE TABLE IF NOT EXISTS `VBQuestion` (
  `ColNum` bigint(20) NOT NULL AUTO_INCREMENT,
  `resrcID` varchar(10) NOT NULL,
  `question` text NOT NULL,
  `posAnsw1` varchar(50) NOT NULL,
  `posAnsw2` varchar(50) NOT NULL,
  `posAnsw3` varchar(50) NOT NULL,
  `posAnsw4` varchar(50) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`ColNum`),
  KEY `ColNum` (`ColNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `VBTask`
--

CREATE TABLE IF NOT EXISTS `VBTask` (
  `ColNum` bigint(8) NOT NULL AUTO_INCREMENT,
  `resrcID` int(11) NOT NULL,
  `questColNum` text NOT NULL,
  `usedby` varchar(50) NOT NULL,
  `dateUsed` date NOT NULL,
  `class` varchar(5) NOT NULL,
  PRIMARY KEY (`ColNum`),
  KEY `ColNum` (`ColNum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
