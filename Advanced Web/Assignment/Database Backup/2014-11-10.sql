-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2014 at 01:43 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Assignment3`
--

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
`levelID` int(11) NOT NULL,
  `levelName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `neededOnShift` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`levelID`, `levelName`, `neededOnShift`) VALUES
(1, 'admin', 0),
(2, 'Nurse', 3),
(3, 'Senior', 2);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE IF NOT EXISTS `shifts` (
`shiftID` bigint(20) NOT NULL,
  `userID` int(11) NOT NULL,
  `shiftDate` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=149 ;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shiftID`, `userID`, `shiftDate`) VALUES
(1, 2, '2014-09-27'),
(2, 1, '2014-09-27'),
(3, 3, '2014-09-27'),
(4, 4, '2014-09-27'),
(5, 7, '2014-09-27'),
(6, 6, '2014-09-27'),
(7, 5, '2014-09-27'),
(8, 7, '2014-09-28'),
(9, 6, '2014-09-28'),
(10, 2, '2014-09-28'),
(11, 1, '2014-09-28'),
(12, 2, '2014-10-27'),
(13, 1, '2014-10-27'),
(14, 3, '2014-10-27'),
(15, 4, '2014-10-27'),
(16, 7, '2014-10-27'),
(17, 6, '2014-10-27'),
(18, 5, '2014-10-27'),
(19, 7, '2014-10-28'),
(20, 6, '2014-10-28'),
(21, 2, '2014-10-28'),
(22, 1, '2014-10-28'),
(23, 2, '2014-11-27'),
(24, 1, '2014-11-27'),
(25, 3, '2014-11-27'),
(26, 4, '2014-11-27'),
(27, 7, '2014-11-27'),
(28, 6, '2014-11-27'),
(29, 5, '2014-11-27'),
(30, 7, '2014-11-28'),
(31, 6, '2014-11-28'),
(32, 2, '2014-11-28'),
(33, 1, '2014-11-28'),
(34, 9, '2014-10-28'),
(36, 9, '2014-12-10'),
(39, 9, '2014-11-04'),
(40, 9, '2015-02-09'),
(41, 9, '2014-11-03'),
(42, 9, '2014-11-10'),
(43, 9, '2014-11-24'),
(44, 9, '2014-11-05'),
(45, 9, '2014-11-11'),
(46, 9, '2014-11-12'),
(47, 9, '2014-11-13'),
(48, 9, '2014-11-14'),
(49, 9, '2014-11-06'),
(50, 9, '2014-11-08'),
(51, 9, '2014-11-02'),
(52, 9, '2014-11-17'),
(53, 9, '2014-11-26'),
(54, 9, '2014-11-28'),
(56, 9, '2014-11-29'),
(57, 9, '2014-11-30'),
(58, 9, '2014-10-29'),
(59, 9, '2014-10-30'),
(60, 9, '2014-11-22'),
(61, 9, '2014-11-21'),
(65, 9, '2014-11-19'),
(66, 9, '2014-11-20'),
(73, 9, '2014-10-31'),
(74, 9, '2014-12-01'),
(75, 9, '2015-01-05'),
(76, 9, '2015-02-03'),
(77, 9, '2015-02-03'),
(78, 9, '2015-02-03'),
(79, 9, '2015-02-03'),
(80, 9, '2015-02-03'),
(81, 9, '2015-02-03'),
(82, 9, '2015-02-03'),
(83, 9, '2014-12-09'),
(84, 9, '2014-12-02'),
(85, 9, '2014-12-02'),
(86, 9, '2015-01-08'),
(87, 9, '2015-01-07'),
(88, 9, '2014-12-02'),
(89, 9, '2014-12-02'),
(90, 9, '2014-12-02'),
(91, 9, '2014-12-02'),
(92, 9, '2014-12-03'),
(93, 9, '2014-12-02'),
(94, 9, '2014-12-02'),
(95, 9, '2014-12-04'),
(96, 9, '2014-12-02'),
(97, 9, '2014-12-03'),
(98, 9, '2014-12-03'),
(99, 9, '2014-12-02'),
(100, 9, '2014-12-02'),
(101, 9, '2014-12-02'),
(102, 9, '2014-12-02'),
(103, 9, '2014-12-16'),
(104, 9, '2014-12-30'),
(105, 9, '2015-01-06'),
(106, 9, '2015-01-06'),
(107, 9, '2015-01-06'),
(109, 9, '2015-01-06'),
(111, 9, '2014-12-25'),
(112, 9, '2014-12-22'),
(114, 9, '2015-01-07'),
(115, 9, '2014-12-18'),
(116, 9, '2014-12-17'),
(117, 9, '2014-12-31'),
(118, 9, '2015-01-07'),
(119, 9, '2014-12-03'),
(121, 9, '2015-01-15'),
(122, 9, '2015-01-15'),
(123, 9, '2015-01-15'),
(124, 9, '2014-12-09'),
(125, 9, '2014-12-11'),
(126, 9, '2014-12-12'),
(127, 9, '2014-12-10'),
(128, 9, '2014-12-26'),
(129, 9, '2015-01-16'),
(132, 9, '2014-12-13'),
(133, 9, '2014-12-19'),
(134, 9, '2015-01-24'),
(135, 9, '2015-01-24'),
(136, 9, '2015-01-24'),
(137, 9, '2014-12-20'),
(138, 9, '2014-12-07'),
(139, 9, '2014-12-23'),
(140, 9, '2014-12-28'),
(141, 9, '2015-01-02'),
(142, 9, '2014-12-02'),
(143, 9, '2015-01-01'),
(144, 9, '2014-12-29'),
(145, 9, '2015-01-13'),
(146, 9, '2015-01-14'),
(147, 9, '2015-02-10'),
(148, 9, '2015-02-04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`userID` int(11) NOT NULL,
  `surname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `forename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `levelID` int(11) NOT NULL,
  `staffID` int(11) NOT NULL,
  `emailAddress` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneNumber` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `surname`, `forename`, `password`, `levelID`, `staffID`, `emailAddress`, `phoneNumber`, `address1`, `address2`, `city`, `postcode`) VALUES
(1, 'Apple', 'Amy', 'Ppl!eta123', 2, 4567, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Berry', 'Bert', 'aSerty456a', 2, 5467, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Carrot', 'Carl', 'asDghj1', 2, 1432, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Donkey', 'Dave', 'sgGgdghj1', 2, 6743, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Emu', 'Ernie', 'tyuIo124', 2, 2456, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Fur', 'Frank', '45frAnk67', 3, 8543, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Goat', 'Graham', 'deDede1', 3, 7832, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'timetabler', 'admin', 'organ1sed', 1, 6189, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'McQueen', 'Dave', 'dave', 2, 1234, 'dave.j.mcqueen@gmail.com', '07712454780', '3 Barnsfold Avenue', 'Fallowfield', 'Manchester', 'M14 6FJ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
 ADD PRIMARY KEY (`levelID`), ADD UNIQUE KEY `levelID_2` (`levelID`), ADD KEY `levelID` (`levelID`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
 ADD PRIMARY KEY (`shiftID`), ADD KEY `shiftID` (`shiftID`), ADD KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`userID`), ADD KEY `userID` (`userID`), ADD KEY `levelID` (`levelID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
MODIFY `levelID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
MODIFY `shiftID` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
ADD CONSTRAINT `shifts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`levelID`) REFERENCES `levels` (`levelID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
