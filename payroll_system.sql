-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 08:56 AM
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
-- Database: `payroll_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `Employee Code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Contact` int(15) NOT NULL,
  `DOB` date DEFAULT NULL,
  `Joining` date DEFAULT NULL,
  `Blood` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Employee Type` enum('Permanent','Part-time','Intern') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`Employee Code`, `Name`, `Email`, `Contact`, `DOB`, `Joining`, `Blood`, `Employee Type`) VALUES
('ZY102', 'Ayaan Jauhar', 'Ayyu43@gmail.com', 967426907, '2002-12-07', '2024-07-17', 'B+', 'Part-time'),
('ZY104', 'Dua Sharma', 'Duaa33@gmail.com', 775362466, '2000-10-05', '2022-11-04', 'AB+', 'Permanent');

-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE `leave` (
  `Employee Code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Dates` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Message` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Leave Type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Status` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave`
--

INSERT INTO `leave` (`Employee Code`, `Subject`, `Dates`, `Message`, `Leave Type`, `Status`) VALUES
('ZY102', 'A Day Leave', '5.10.24', NULL, 'Sick leave', 'Pending'),
('ZY104', 'A week leave', '6.9.23 to 11.9.23', 'Request to grant me this leave', 'Vacation Leave', 'Rejected'),
('ZY102', '3 days Leave', '12.5.23 to 14.5.23', 'Please grant me this leave.', 'Sick', 'Pending'),
('ZY102', '3 days Leave', '12.5.23 to 14.5.23', 'Please grant me this leave.', 'Sick', 'Pending'),
('ZY104', '4 days Leave', '12.10.23 to 14.10.23', 'I am going with my family to Goa', 'Vacation', 'Pending'),
('ZY104', 'A day leave', '28.10.20', 'Due to Medical Appointment', 'Sick', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `Salary Month` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Employee Code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Earnings` decimal(10,2) UNSIGNED NOT NULL,
  `Deductions` decimal(10,2) UNSIGNED NOT NULL,
  `Net Salary` decimal(10,2) UNSIGNED NOT NULL,
  `Actions` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`Salary Month`, `Employee Code`, `Earnings`, `Deductions`, `Net Salary`, `Actions`) VALUES
('August,2024', 'ZY102', 58912.56, 4000.12, 54912.44, 'Paid'),
('August,2024', 'ZY104', 48776.87, 4000.12, 44776.75, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Name` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `Employee Code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Role` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Phone` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Name`, `ID`, `Email`, `Address`, `Employee Code`, `Password`, `Role`, `Department`, `Phone`) VALUES
('Amir Sabir', 1, 'amir21@gmail.com', 'CIT Road,kolakta', 'ZY100', 'zain21', 'admin', 'Finance', 933124577),
('Ayaan Jauhar', 2, 'Ayyu43@gmail.com', 'Vaidik garden,Lucknow', 'ZY102', 'Ayan12', 'employee', 'IT', 967426907),
('Zainab Ahmed', 3, 'zainabahmed2460@gmail.com', 'Dent mission road,Kolkata', 'ZY103', 'zain', 'admin', 'HR', 858482242),
('Dua Sharma', 4, 'Duaa33@gmail.com', 'Bandra,Mumbai', 'ZY104', 'Duaz3', 'employee', 'Marketing', 775362466),
('', 5, '', '', 'ZY110', 'Arun@23', 'employee', NULL, 0),
('', 6, '', '', 'ZY200', 'disha11', 'admin', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD UNIQUE KEY `Employee Code` (`Employee Code`);

--
-- Indexes for table `leave`
--
ALTER TABLE `leave`
  ADD KEY `Employee Code` (`Employee Code`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD KEY `Employee Code` (`Employee Code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Employee Code` (`Employee Code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `salary`
--
ALTER TABLE `salary`
  ADD CONSTRAINT `fk_employee_code` FOREIGN KEY (`Employee Code`) REFERENCES `users` (`Employee Code`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
