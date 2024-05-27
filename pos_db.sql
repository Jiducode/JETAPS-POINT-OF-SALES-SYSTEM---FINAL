-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2024 at 02:49 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catid`, `category`) VALUES
(17, 'Headset'),
(18, 'Mousepad'),
(19, 'USB Sound Adapter'),
(20, 'Switch Hub'),
(21, 'Keyboard Combo'),
(22, 'Keyboard'),
(23, 'Mouse'),
(24, 'Power Supply Unit'),
(25, 'Monitor'),
(26, 'UPS / AVR'),
(27, 'Speaker'),
(28, 'Mother Board'),
(29, 'Processor'),
(30, 'Memory'),
(31, 'Hard Drive'),
(32, 'Computer Casing'),
(33, 'Cord'),
(34, 'Web Cam'),
(35, 'hala'),
(36, 'hala');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `subtotal` double NOT NULL,
  `tax` double NOT NULL,
  `warranty` varchar(250) NOT NULL,
  `discount` double NOT NULL,
  `total` double NOT NULL,
  `paid` double NOT NULL,
  `change` double NOT NULL,
  `payment_type` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_details`
--

CREATE TABLE `tbl_invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_history`
--

CREATE TABLE `tbl_login_history` (
  `login_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `login_date` date DEFAULT NULL,
  `login_time` time DEFAULT NULL,
  `logout_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `pid` int(11) NOT NULL,
  `pname` varchar(200) NOT NULL,
  `pcategory` varchar(200) NOT NULL,
  `saleprice` float NOT NULL,
  `pstock` int(11) NOT NULL,
  `pdescription` varchar(250) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`pid`, `pname`, `pcategory`, `saleprice`, `pstock`, `pdescription`, `image`) VALUES
(8, 'INPLAY RAINBOW 12V FAN   RGB', 'COOLING FAN', 150, 100, '', 'image/INPLAY RAINBOW 12V FAN   RGB (2).png'),
(9, 'INPLAY 3IN1 REMOTE FAN', 'COOLING FAN', 850, 38, '', 'image/INPLAY 3IN1 REMOTE FAN.png'),
(11, '4X4 12V FAN', 'COOLING FAN', 90, 92, '', 'image/4X4 12V FAN (2).png'),
(12, 'INPLAY RGB CPU COOLER', 'COOLING FAN', 250, 6, '', 'image/INPLAY RGB CPU COOLER.png'),
(13, '4X4 220V FAN', 'COOLING FAN', 150, 100, '                                                                                                                                        ', 'image/4X4 12V FAN (2).png'),
(16, 'INPLAY HEADSET H2', 'Headset', 170, 50, '', 'image/INPLAY HEADSET H2.png'),
(17, 'INPLAY HEADSET W/LIGHT', 'Headset', 195, 12, '', 'image/INPLAY HEADSET WITH LIGHT.png'),
(18, 'ALLAN GAMING WITH LIGHT', 'Headset', 320, 45, '', 'image/ALLAN GAMING WITH LIGHT.png'),
(19, 'DAREU GAMING HEADSET', 'Headset', 1600, 3, '', 'image/DAREU GAMING HEADSET.png'),
(20, 'INPLAY NOISE CANCELLING', 'Headset', 750, 5, '', 'image/INPLAY NOISE CANCELLING.png'),
(21, 'MOUSEPAD', 'Mousepad', 25, 200, '', 'image/GAMING MOUSEPAD.png'),
(22, 'USB LAN ORDINARY', 'USB Sound Adapter', 250, 6, '', 'image/USB LAN ORDINARY.png'),
(23, 'USB SOUND ADAPTER', 'USB Sound Adapter', 250, 500, '', 'image/USB SOUND ADAPTER.png'),
(24, 'COMFAST USB WI-FI ADAPTER', 'USB Sound Adapter', 350, 25, '', 'image/COMFAST USB WI-FI ADAPTER.png'),
(25, 'USB HUB', 'USB Sound Adapter', 150, 23, '', 'image/USB HUB.png'),
(26, '5 PORTS GIGABIT ', 'Switch Hub', 650, 34, '', 'image/5 PORTS GIGABIT.png'),
(27, '8 PORTS  ORDINARY', 'Switch Hub', 550, 67, '', 'image/8 PORTS  ORDINARY.png'),
(28, '16 PORTS GIGABIT ', 'Switch Hub', 3600, 0, '', 'image/16 PORTS GIGABIT.png'),
(29, '16 PORTS SOHO SWITCH ', 'Switch Hub', 1300, 5, '', 'image/16 PORTS SOHO SWITCH.png'),
(30, 'MICROPACK COMBO USB TYPE', 'Keyboard Combo', 370, 65, '', 'image/MICROPACK COMBO USB TYPE.png'),
(31, 'A4TECH COMBO USB TYPE', 'Keyboard Combo', 480, 53, '', 'image/A4TECH COMBO USB TYPE.png'),
(32, 'Q9 COMBO ', 'Keyboard Combo', 270, 12, '', 'image/Q9 COMBO.png'),
(33, 'INPLAY STX360  BLACK COMBO ', 'Keyboard Combo', 350, 32, '', 'image/INPLAY STX360  BLACK COMBO.png'),
(34, 'INPLAY STX200', 'Keyboard Combo', 300, 4, '', 'image/INPLAY STX200.png'),
(35, 'INPLAY STX540 4IN1', 'Keyboard Combo', 1400, 6, '', 'image/INPLAY STX540 4IN1.png'),
(36, 'MICROPACK KEYBOARD', 'Keyboard', 270, 500, '', 'image/MICROPACK KEYBOARD.png'),
(37, 'JEDEL KEYBOARD', 'Keyboard', 200, 45, '', 'image/JEDEL KEYBOARD.png'),
(38, '  A4 TECH  KEYBOARD', 'Keyboard', 320, 444, '', 'image/A4 TECH  KEYBOARD.png'),
(39, '  DELL LAPTOP  KEYBOARD	', 'Keyboard', 250, 212, '', 'image/DELL LAPTOP  KEYBOARD.png'),
(40, 'A4TECH MOUSE', 'Mouse', 190, 503, '', 'image/A4TECH MOUSE.png'),
(41, 'GOLD FOUR TECH MOUSE', 'Mouse', 100, 67, '', 'image/GOLD FOUR TECH MOUSE.png'),
(42, 'ALLAN M200 WARWICK MOUSE', 'Mouse', 100, 3, '', 'image/ALLAN M200 WARWICK MOUSE.png'),
(43, 'DAREU LM103 MOUSE', 'Mouse', 190, 44, '', 'image/DAREU LM103 MOUSE.png'),
(44, 'ASUS UT280 MOUSE', 'Mouse', 100, 30, '', 'image/ASUS UT280 MOUSE.png'),
(45, 'INPLAY M360 MOUSE', 'Mouse', 100, 34, '', 'image/INPLAY M360 MOUSE.png'),
(46, 'CENTRALIZED PSU 12V, 5AMP', 'Power Supply Unit', 500, 37, '', 'image/CENTRALIZED PSU 12V, 5AMP.png'),
(47, 'INPLAY SMALL POWER SUPPLY', 'Power Supply Unit', 480, 29, '', 'image/INPLAY SMALL POWER SUPPLY.png'),
(48, 'CVS 700W', 'Power Supply Unit', 580, 9, '', 'image/CVS 700W.png'),
(49, 'TRU RATED ES GAMING  650 W ', 'Power Supply Unit', 1950, 600, '', 'image/TRU RATED ES GAMING  650 W.png'),
(50, 'N-VISION N18.5HD', 'Monitor', 2700, 56, '', 'image/N-VISION N18.5HD.png'),
(51, 'N-VISION IP24V1', 'Monitor', 5300, 65, '', 'image/N-VISION IP24V1.png'),
(52, 'GAMDIAS 24 “INC MONITOR', 'Monitor', 5500, 38, '', 'image/GAMDIAS 24 “INC MONITOR.png'),
(53, 'HK PLUS MONITOR', 'Monitor', 2700, 27, '', 'image/HK PLUS MONITOR.png'),
(54, 'AOC MONITOR  24’INC', 'Monitor', 7500, 5, '', 'image/AOC MONITOR  24’INC.png'),
(55, 'AOC MONITOR  19’INC', 'Monitor', 3000, 20, '', 'image/AOC MONITOR  19’INC.png'),
(56, 'ILOGIC BLAZER 720', 'UPS / AVR', 1950, 120, '', 'image/AOC MONITOR  19’INC.png'),
(57, 'SECURE AVR', 'UPS / AVR', 250, 14, '', 'image/SECURE AVR.png'),
(58, 'INPLAY MS002 SPEAKER', 'Speaker', 280, 16, '', 'image/INPLAY MS002 SPEAKER.png'),
(59, 'INPLAY MS001 SPEAKER', 'Speaker', 150, 26, '', 'image/INPLAY MS001 SPEAKER.png'),
(60, 'BOSTON EK-01 SPEAKER', 'Speaker', 150, 13, '', 'image/BOSTON EK-01 SPEAKER.png'),
(61, 'ASUS PRIME A320M-K MOTHER BOARD', 'Mother Board', 2900, 1, '', 'image/ASUS PRIME A320M-K MOTHER BOARD.png'),
(62, 'MAXSUN DDR3 MOTHERBOARD', 'Mother Board', 2300, 10, '', 'image/MAXSUN DDR3 MOTHERBOARD.png'),
(63, 'MAXSUN DDR4 MOTHERBOARD', 'Mother Board', 2800, 9, '', 'image/MAXSUN DDR4 MOTHERBOARD.png'),
(64, 'FM2 BIOSTAR MOBO', 'Mother Board', 2500, 7, '', 'image/FM2 BIOSTAR MOBO.png'),
(65, 'A8 9600 DDR4', 'Processor', 2800, 76, '', 'image/A8 9600 DDR4.png'),
(66, 'AMD A6 7480 DDR3', 'Processor', 2300, 43, '', 'image/AMD A6 7480 DDR3.png'),
(67, 'AMD ATHLON 20GE', 'Processor', 3700, 22, '', 'image/AMD ATHLON 20GE.png'),
(68, '4GB RAM DDR3', 'Memory', 1200, 3, '', 'image/AMD ATHLON 20GE.png'),
(69, '4GB RAM DDR4', 'Memory', 1350, 4, '', 'image/4GB RAM DDR4.png'),
(70, '8GB RAM DDR3', 'Memory', 1800, 10, '', 'image/8GB RAM DDR3.png'),
(71, '8GB RAM DDR4', 'Memory', 2200, 21, '', 'image/8GB RAM DDR4.png'),
(72, 'HARD DICS (HDD) 1 TERRA SEAGATE', 'Hard Drive', 2300, 7, '', 'image/HARD DICS (HDD) 1 TERRA SEAGATE.png'),
(73, 'HARD DICS (HDD) 500GB SEAGATE', 'Hard Drive', 1200, 18, '', 'image/HARD DICS (HDD) 500GB SEAGATE.png'),
(74, 'SSD(S100) 128GB', 'Hard Drive', 1300, 17, '', 'image/SSD(S100) 128GB.png'),
(75, 'SSD(S100) 240GB', 'Hard Drive', 1980, 15, '', 'image/SSD(S100) 240GB.png'),
(76, 'SSD(S100) 512GB', 'Hard Drive', 2700, 200, '', 'image/SSD(S100) 512GB.png'),
(77, 'COMPLETE CASE WITH PSU CVS', 'Computer Casing', 900, 16, '', 'image/COMPLETE CASE WITH PSU CVS.png'),
(78, 'STUCTURE WITH PSU', 'Computer Casing', 650, 65, '', 'image/STUCTURE WITH PSU.png'),
(79, 'INPLAY CASING WIND 01', 'Computer Casing', 900, 5, '', 'image/INPLAY CASING WIND 01.png'),
(80, 'ES-07M GAMING CASE', 'Computer Casing', 1200, 22, '', 'image/ES-07M GAMING CASE.png'),
(81, 'ES-06W GAMING CASE', 'Computer Casing', 1500, 16, '', 'image/ES-06W GAMING CASE.png'),
(82, 'HDMI', 'Cord', 150, 22, '', 'image/HDMI.png'),
(84, 'POWER CORD', 'Cord', 40, 2, '', 'image/POWER CORD.png'),
(85, 'USB EXTENSION 1.5M', 'Cord', 65, 34, '', 'image/USB EXTENSION 1.5M.png'),
(86, 'SATA CABLE', 'Cord', 25, 43, '', 'image/SATA CABLE.png'),
(87, 'ES-10B GAMING CASE', 'Computer Casing', 1500, 23, '', 'image/ES-10B GAMING CASE.png'),
(88, 'ES-17B GAMING CASE', 'Computer Casing', 1500, 44, '', 'image/ES-17B GAMING CASE.png'),
(89, 'MICROPACK WEB CAM 10809', 'Web Cam', 750, 33, '', 'image/MICROPACK WEB CAM.png'),
(90, 'THIEYE WEB CAM 1080P', 'Web Cam', 1300, 22, '', 'image/THIEYE WEB CAM.png'),
(91, 'EGG CAM', 'Web Cam', 200, 123, '', 'image/EGG CAM.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `useremail` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `password`, `role`) VALUES
(21, 'Jireh', 'jireh@gmail.com', '12345', 'Admin'),
(24, 'cashier1', 'cashier@gmail.com', '12345', 'Cashier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login_history`
--
ALTER TABLE `tbl_login_history`
  ADD PRIMARY KEY (`login_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_login_history`
--
ALTER TABLE `tbl_login_history`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_login_history`
--
ALTER TABLE `tbl_login_history`
  ADD CONSTRAINT `tbl_login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
