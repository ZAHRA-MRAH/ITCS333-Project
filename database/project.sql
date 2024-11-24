-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 04:59 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `AvailabilityID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BookingID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `BookingData` date NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Status` enum('Pending','confirmed','Cancelled') NOT NULL,
  `BookingTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `RoomID` int(11) NOT NULL,
  `RoomNumber` varchar(10) NOT NULL,
  `RoomType` varchar(50) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Equipment` text NOT NULL,
  `imgURL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `RoomNumber`, `RoomType`, `Capacity`, `Equipment`, `imgURL`) VALUES
(1, 'S40-049', 'Classroom', 35, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(2, 'S40-051', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(3, 'S40-056', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(4, 'S40-057', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(5, 'S40-058', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(6, 'S40-060', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(7, 'S40-1043', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200\r\n'),
(8, 'S40-1045', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(9, 'S40-1047', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(10, 'S40-1048', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(11, 'S40-1050', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(12, 'S40-1052', 'Computer lab', 40, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(13, 'S40-2043', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(14, 'S40-2045', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(15, 'S40-2046', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(16, 'S40-2048', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(17, 'S40-2049', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(18, 'S40-2050', 'Classroom', 40, 'Multimedia Projector\r\nWhiteboard', 'https://placehold.co/200x200'),
(19, 'S40-2051', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200'),
(20, 'S40-2052', 'Computer lab', 25, 'Multimedia Projector\r\nWhiteboard\r\nComputers', 'https://placehold.co/200x200');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('Student','Faculty','','') NOT NULL,
  `ProfilePic` varchar(255) NOT NULL DEFAULT '../pictures/default-picture.jpg',
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PhoneNo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `FirstName`, `LastName`, `Email`, `Password`, `Role`, `ProfilePic`, `CreatedAt`, `UpdatedAt`, `PhoneNo`) VALUES
(3, 'zahra', 'husain', '202203876@stu.uob.edu.bh', '$2y$10$jqlbWeWeSaCzpppnOAbAIeut4usH7lS8vjPJ9Y.NzgJ0rNQkrvdO6', 'Student', '../Pictures/default-picture.jpg', '2024-11-23 21:44:17', '2024-11-23 21:44:17', '38211535'),
(5, 'Batool', 'Alsayed', '202109262@stu.uob.edu.bh', '$2y$10$uLEXC1Z1jJpHZOOTwvcgyuJ485em8BBnDcQ92knAx7wEW04eYQEwG', 'Student', '../Pictures/default-picture.jpg', '2024-11-24 07:27:10', '2024-11-24 07:27:10', '36366460');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`AvailabilityID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`RoomID`),
  ADD UNIQUE KEY `RoomNumber` (`RoomNumber`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `AvailabilityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
