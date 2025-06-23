-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2024 at 07:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bok`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(10) NOT NULL,
  `cat_id` varchar(100) NOT NULL,
  `subcat_id` varchar(100) NOT NULL,
  `book` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `totquantity` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `mrp` varchar(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `cat_id`, `subcat_id`, `book`, `image`, `price`, `totquantity`, `author`, `description`, `mrp`, `time`) VALUES
(16, '16', '15', 'Arihant -  Fast Track Objective Arithmetic ', 'Fast Track OA Book.jpg', '595', '10', 'Rajesh Varma', '', '600', '2023-06-01 07:23:56'),
(17, '16', '16', 'LUCENT GK', 'Lucent GK.jpg', '270', '10', 'Lucent Publication', 'The book is useful for every reader in general and for the aspirants of various competitive examinations, viz. UPSC Civil Services, CDS, NDA, etc. exams, State Public Service Commission’s exams., SSC, Railway Recruitment Boards (RRBs), SCRA, Bank P.O./T.O., LIC/GIC/OIC (AAO), RBI grade ‘A’ and ‘B’ Officers, CAT, MAT, CLAT, MBA, BBA, MCA, BCA, entrance examinations etc. in particular.', '300', '2023-06-02 06:10:10'),
(18, '16', '17', 'Objective General English - Arihant', 'SP Bakshi.jpg', '375', '10', 'SP Bakshi', 'Objective General English Book PDF by SP Bakshi, Arihant Publication for competitive Exams available for free download. Book is useful for SSC CGL, CPO, CHSL, MTA, MBA, UPSC, Bank and other Competitive Exams. This book is one of the best for the study of Government Jobs examinations.', '400', '2023-06-02 06:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `career`
--

CREATE TABLE `career` (
  `caid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `pout` varchar(100) NOT NULL,
  `pace` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career`
--

INSERT INTO `career` (`caid`, `name`, `dob`, `email`, `phone`, `pout`, `pace`, `address`, `msg`, `cdate`) VALUES
(1, 'aaa', '2023-03-15', 'aaa@gmail.com', '9988776655', 'Yes', 'Yes', 'dfdgfgfg', 'sggfrgfgfgf', '2023-03-15 05:43:15'),
(2, 'Test', '2023-05-05', 'test@gmail.com', '9988776655', 'Yes', 'Yes', 'Karaikudi', 'This is a Test Message', '2023-05-05 11:54:12'),
(3, 'demon', '2023-05-30', 'demo@gmail.com', '9988877665', 'Yes', 'Yes', 'kkdi', 'This is test', '2023-05-30 07:43:40'),
(4, 'Raja', '2023-06-01', 'raja@gmail.com', '9988776655', 'Yes', 'No', 'KKDI', 'This is test message', '2023-06-01 06:05:40'),
(5, 'ravi', '2023-06-21', 'ravi@gmail.com', '9988777661', 'Yes', 'Yes', 'KKDI', 'Test Message', '2023-06-26 13:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productQty` int(11) DEFAULT NULL,
  `postingDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`) VALUES
(16, 'SSC'),
(18, 'TNPSC'),
(19, 'Banking'),
(20, 'UPSC');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(10) NOT NULL,
  `ctitle` varchar(100) NOT NULL,
  `ccate` varchar(100) NOT NULL,
  `ins` varchar(100) NOT NULL,
  `cdura` varchar(100) NOT NULL,
  `csprice` varchar(100) NOT NULL,
  `mrp` varchar(100) NOT NULL,
  `cdesc` mediumtext NOT NULL,
  `cfea` mediumtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `uptime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `ctitle`, `ccate`, `ins`, `cdura`, `csprice`, `mrp`, `cdesc`, `cfea`, `image`, `uptime`) VALUES
(2, 'Economy - Basic 2 Advance ', 'institute', 'EA Dream Supporters', '3-months', '5000', '7000', 'Economy Course for UPSC, TNPSC, Exams', '◉ Basic to Advance Level\r\n◉ Covers Important Current Affair', 'Course (1).jpg', '2023-03-17 10:19:15'),
(14, 'Accounting Terms [ EPFO ]', 'educators', 'EA Dream Supporters', '1 Month', '1999', '3000', 'Basic Accounting & Accounting Terms for EPFO EO | AO Exam', 'Covers Complete Accounting Concepts', 'Course (2).jpg', '2023-06-05 07:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `course_subscription`
--

CREATE TABLE `course_subscription` (
  `id` int(10) NOT NULL,
  `uid` int(100) NOT NULL,
  `cid` int(100) NOT NULL,
  `intime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_id` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT '0',
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_subscription`
--

INSERT INTO `course_subscription` (`id`, `uid`, `cid`, `intime`, `payment_id`, `status`, `remark`) VALUES
(24, 21, 14, '2024-03-29 06:05:28', 'pay_Ns48TNa0sINi5f', '0', ''),
(25, 21, 2, '2024-03-29 06:06:43', 'pay_Ns49nqptgXzaJP', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `eid` int(10) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `reply` varchar(1000) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`eid`, `uid`, `date`, `subject`, `message`, `reply`, `status`) VALUES
(1, '2', '22-05-2022', 'book', 'if book is available', '', '0'),
(2, '1', '13-07-2022', 'job', 'how will you provide job vacancy', 'yes', '1'),
(3, '1', '22-05-2022', 'class', 'total hours of class', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `guide`
--

CREATE TABLE `guide` (
  `guid` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `pout` varchar(15) NOT NULL,
  `poutyear` varchar(100) NOT NULL,
  `pace` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `reply` varchar(255) NOT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `gutime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guide`
--

INSERT INTO `guide` (`guid`, `user`, `name`, `dob`, `email`, `phone`, `pout`, `poutyear`, `pace`, `address`, `msg`, `reply`, `status`, `gutime`) VALUES
(1, 1, 'aaa', '2023-03-15', 'aaa@gmail.com', '9988776655', 'Yes', '2011', 'Yes', 'aaaaa', 'dsfdsfsda', 'accepted', 1, '2023-03-15 05:38:12'),
(2, 1, 'demo', '2023-03-15', 'demo@gmail.com', '9988776655', 'Yes', '2021', 'Yes', 'sssss', 'fgdgfdgfd', 'Rejected', -1, '2023-03-15 07:57:39'),
(3, 9, 'Test', '2023-05-05', 'test@gmail.com', '9988776655', 'Yes', '2022', 'Yes', 'Karaikudi', 'This is a test message', 'This is a Test Message', 1, '2023-05-05 11:51:24'),
(4, 11, 'demon', '2023-05-02', 'demo@gmail.com', '9988877665', 'Yes', '2022', 'Yes', 'kkdi', 'This is for testing', 'This is test reply', 1, '2023-05-30 06:32:42'),
(5, 11, 'demon', '2023-05-09', 'demo@gmail.com', '9988877665', 'No', '2022', 'No', 'kkdi', 'This is for test', 'Test Reply', 1, '2023-05-30 06:37:13'),
(6, 12, 'Raja', '2023-05-29', 'raja@gmail.com', '9888776655', 'Yes', '2010', 'No', 'KKDI', 'This is a test message', 'This is test reply\r\n', 1, '2023-06-01 06:03:47'),
(7, 13, 'EA Rider', '1999-10-23', 'dummy@gmail.com', '0000000000', 'Yes', '2021', 'Yes', 'edfsx', 'want', 'YES', 1, '2023-06-24 07:30:00'),
(8, 13, 'EA Rider', '1999-10-23', 'dummy@gmail.com', '0000000000', 'Yes', '2021', 'Yes', 'edfsx', 'want', '', 0, '2023-06-24 07:42:35'),
(9, 20, 'ravi', '2023-06-21', 'ravi@gmail.com', '9988777661', 'Yes', '2022', 'Yes', 'KKDI', 'TEst Message', 'test message', 1, '2023-06-26 13:25:44');

-- --------------------------------------------------------

--
-- Table structure for table `magazines`
--

CREATE TABLE `magazines` (
  `mid` int(10) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `mplan` varchar(1000) NOT NULL,
  `mprice` varchar(1000) NOT NULL,
  `mdescription` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `adate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `magazines`
--

INSERT INTO `magazines` (`mid`, `mname`, `mplan`, `mprice`, `mdescription`, `image`, `adate`) VALUES
(21, 'YOJANA', '1,12', '30,250', 'Yojana Magazine is an educational magazine, part of an official venture by the Government of India. ', '367234d46f80bb86cb984f598b3ed38e1685943939.jpg', '2023-06-05 05:10:32'),
(22, 'KURUKSHETRA', '1,12', '30,230', 'Kurukshetra Magazine emphasizes more on social issues, including issues relating to rural governance', 'Magazine Book 2.jpg', '2023-06-05 06:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `online_order`
--

CREATE TABLE `online_order` (
  `oid` int(11) NOT NULL,
  `uid` int(111) NOT NULL,
  `addressId` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `mode` varchar(100) NOT NULL,
  `transacton` varchar(100) NOT NULL,
  `orddate` datetime NOT NULL DEFAULT current_timestamp(),
  `orderno` int(100) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `block` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `ordersts` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_order`
--

INSERT INTO `online_order` (`oid`, `uid`, `addressId`, `name`, `phone`, `amount`, `mode`, `transacton`, `orddate`, `orderno`, `notes`, `block`, `city`, `state`, `zip`, `ordersts`) VALUES
(17, 21, 'Karaikudi', 'Dhivakaran', '6382810681', 270, 'Online Payment', 'pay_Ns40LA1a2bEEF7', '2024-03-29 11:27:46', 845749415, 'Test', 'Sekkalai', 'Karaikudi', 'Tamilnadu', '630001', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `orderNumber` bigint(12) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `addressId` varchar(100) DEFAULT NULL,
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `txnType` varchar(200) DEFAULT NULL,
  `payment_id` varchar(100) NOT NULL,
  `txnNumber` varchar(50) DEFAULT NULL,
  `notes` varchar(255) NOT NULL,
  `block` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` int(10) NOT NULL,
  `orderStatus` varchar(120) NOT NULL,
  `orderDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `orderNumber`, `userId`, `addressId`, `totalAmount`, `txnType`, `payment_id`, `txnNumber`, `notes`, `block`, `city`, `state`, `zip`, `orderStatus`, `orderDate`) VALUES
(32, 208659591, 21, 'Karaikudi', 375.00, 'COD', '', '6382810681', 'Hi', 'Sekkalai', 'Karaikudi', 'Tamilnadu', 630001, '', '2024-03-29');

-- --------------------------------------------------------

--
-- Table structure for table `ordersdetails`
--

CREATE TABLE `ordersdetails` (
  `id` int(11) NOT NULL,
  `orderNumber` bigint(12) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `orderDate` date NOT NULL DEFAULT current_timestamp(),
  `orderStatus` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ordersdetails`
--

INSERT INTO `ordersdetails` (`id`, `orderNumber`, `userId`, `productId`, `quantity`, `orderDate`, `orderStatus`) VALUES
(52, 845749415, 21, 17, 1, '2024-03-29', NULL),
(53, 208659591, 21, 18, 1, '2024-03-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ordertrackhistory`
--

CREATE TABLE `ordertrackhistory` (
  `id` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remark` mediumtext DEFAULT NULL,
  `actionBy` int(3) DEFAULT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `canceledBy` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ordertrackhistory`
--

INSERT INTO `ordertrackhistory` (`id`, `orderId`, `status`, `remark`, `actionBy`, `postingDate`, `canceledBy`) VALUES
(1, 1, 'Packed', 'dfgh', 0, '2022-08-27 08:57:52', NULL),
(2, 1, 'In Transit', 'fgh', 0, '2022-08-27 08:58:01', NULL),
(3, 1, 'Delivered', 'ghjgh', 0, '2022-08-27 09:00:18', NULL),
(4, 3, 'Packed', 'fghfh', 0, '2022-10-08 15:54:21', NULL),
(5, 2, 'Cancelled', 'dsfd', 0, '2023-03-30 06:20:12', ' Admin'),
(6, 6, 'In Transit', 'cdsc', 0, '2023-03-30 06:20:29', NULL),
(7, 9, 'In Transit', 'on the way', 0, '2023-03-30 07:18:43', NULL),
(8, 9, 'Delivered', 'Delivered\r\n', 0, '2023-03-30 07:19:33', NULL),
(9, 2, 'Packed', 'vbhgfhb', 0, '2023-04-03 13:32:29', NULL),
(10, 2, 'In Transit', 'fdgfdg', 0, '2023-04-03 13:50:38', NULL),
(11, 2, 'Delivered', 'fdf', 0, '2023-04-03 13:50:51', NULL),
(12, 2, 'Delivered', 'delivered', 0, '2023-04-18 10:04:00', NULL),
(13, 12, 'Cancelled', 'due to some issues we not need this product now', NULL, '2023-05-05 11:12:09', 'User'),
(14, 3, 'Cancelled', 'Test', NULL, '2023-05-05 11:14:15', 'User'),
(15, 3, 'Cancelled', 'due to some issues cancel it', NULL, '2023-05-05 11:20:00', 'User'),
(16, 5, 'Packed', 'Payment Recieved', 0, '2023-05-29 11:56:03', NULL),
(17, 5, 'In Transit', 'Shipped out', 0, '2023-05-29 11:56:40', NULL),
(18, 5, 'Delivered', 'Reached Your Location', 0, '2023-05-29 11:57:12', NULL),
(19, 13, 'Packed', 'Accepted', 0, '2023-05-29 12:32:15', NULL),
(20, 14, 'Packed', 'Your Order has been Accepted ', 0, '2023-05-30 05:18:53', NULL),
(21, 14, 'In Transit', 'Your Order shipped Out', 0, '2023-05-30 05:19:29', NULL),
(22, 14, 'Delivered', 'Your Order get Delivered', 0, '2023-05-30 05:19:54', NULL),
(23, 6, 'Packed', 'Accepted', 0, '2023-05-30 05:27:11', NULL),
(24, 6, 'In Transit', 'Shipped Out and payment Recieved', 0, '2023-05-30 05:27:52', NULL),
(25, 6, 'Delivered', 'Delivered To Your Location', 0, '2023-05-30 05:29:33', NULL),
(26, 7, 'Packed', 'Accepted', 0, '2023-05-31 13:46:00', NULL),
(27, 15, 'Packed', 'Accepted', 0, '2023-05-31 13:49:10', NULL),
(28, 15, 'In Transit', 'In transist', 0, '2023-05-31 13:49:49', NULL),
(29, 15, 'Delivered', 'delivered', 0, '2023-05-31 13:50:06', NULL),
(30, 16, 'Packed', 'ACCEPTED', 0, '2023-06-26 13:11:46', NULL),
(31, 16, 'In Transit', 'INTRANSIST', 0, '2023-06-26 13:12:17', NULL),
(32, 16, 'Delivered', 'DELIVERED', 0, '2023-06-26 13:12:33', NULL),
(33, 8, 'Cancelled', 'personal reason', NULL, '2023-06-26 13:13:12', 'User'),
(34, 30, 'Packed', 'Shiped', 0, '2024-03-27 07:47:51', NULL),
(35, 29, 'Packed', 'gh', 0, '2024-03-27 07:50:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `precontent`
--

CREATE TABLE `precontent` (
  `p_id` int(11) NOT NULL,
  `subjid` varchar(100) NOT NULL,
  `topid` varchar(100) NOT NULL,
  `title1` varchar(1000) NOT NULL,
  `link1` varchar(1000) NOT NULL,
  `cname1` varchar(1000) NOT NULL,
  `title2` varchar(1000) NOT NULL,
  `link2` varchar(1000) NOT NULL,
  `cname2` varchar(1000) NOT NULL,
  `title3` varchar(1000) NOT NULL,
  `link3` varchar(1000) NOT NULL,
  `cname3` varchar(1000) NOT NULL,
  `title4` varchar(1000) NOT NULL,
  `link4` varchar(1000) NOT NULL,
  `cname4` varchar(1000) NOT NULL,
  `title5` varchar(1000) NOT NULL,
  `link5` varchar(1000) NOT NULL,
  `cname5` varchar(1000) NOT NULL,
  `count` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `precontent`
--

INSERT INTO `precontent` (`p_id`, `subjid`, `topid`, `title1`, `link1`, `cname1`, `title2`, `link2`, `cname2`, `title3`, `link3`, `cname3`, `title4`, `link4`, `cname4`, `title5`, `link5`, `cname5`, `count`) VALUES
(1, '1', '1', 'algebra mathematics', 'https://www.youtube-nocookie.com/embed/OmJ-4B-mS-Y?controls=0', 'Ak', 'algebra mathematics', 'https://www.youtube.com/watch?v=rALehUl-4kM', 'as', 'algebra mathematics', 'https://www.youtube.com/watch?v=rALehUl-4kM', 'Ak', 'algebra mathematics', 'https://www.youtube.com/watch?v=rALehUl-4kM', 'Ak', 'algebra mathematics', 'https://www.youtube.com/watch?v=rALehUl-4kM', 'Ak', ''),
(3, '3', '3', 'Rights for Each Person ', 'https://www.youtube-nocookie.com/embed/OmJ-4B-mS-Y?controls=0', 'Upsc Coaching', 'Rights to Vote', 'https:/www.youtube.com', 'Upsc Coaching', 'Border And Safety', 'https:/www.youtube.com', 'Upsc Coaching', 'Crime Investigation', 'https:/www.youtube.com', 'Upsc Coaching', 'Industries and Country development', 'https:/www.youtube.com', 'Upsc Coaching', ''),
(4, '', '0', 'introduction of mathematics', 'https://www.youtube.com/embed/BGTx91t8q50', 'some channel', 'introduction of mathematics', 'https://www.youtube.com/embed/BGTx91t8q50', 'some channel', 'introduction of mathematics', 'https://www.youtube.com/embed/BGTx91t8q50', 'some channel', 'introduction of mathematics', 'https://www.youtube.com/embed/BGTx91t8q50', 'some channel', 'introduction of mathematics', 'https://www.youtube.com/embed/BGTx91t8q50', 'some channel', ''),
(5, '2', '2', 'HTML Tags and Elements', 'https://www.youtube.com/embed/9kfScGV6W1Y', 'Tech', 'CSS Styles and Forms', 'https://www.youtube.com/embed/9RRQtNnq3s0', 'Tech', '', '', '', '', '', '', '', '', '', ''),
(7, '4', '5', 'Introduction to Atomic Structure', 'https://www.youtube.com/embed/4QblYo-XeoY', 'Atoms and molecules', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, '6', '6', 'Introduction of Css', 'https://www.youtube.com/embed/OEV8gMkCHXQ', 'Css Guru', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, '7', '7', 'Reasoning trick - Analogy | CWJ', 'https://www.youtube.com/embed/w8p1kEnxlTM', 'Crack With Jack', 'Analogy Reasoning For SSC in Tamil', 'https://www.youtube.com/embed/LXWpJGNSBxc', 'Govt Exam Aspirants', 'Word Analogy | Reasoning In Tamil', 'https://www.youtube.com/embed/xIaF9SiqiBc', 'Adda247', 'Letter, Number & Word ANALOGY Reasoning Tricks & Shortcuts', 'https://www.youtube.com/embed/Lj41YmUfaJQ', 'Veranda Race', 'NUMBER ANALOGY - Reasoning Shortcut TNPSC', 'https://www.youtube.com/embed/6zkzPEdCNUE?controls=0', 'Prepare to Crack Tamil', '');

-- --------------------------------------------------------

--
-- Table structure for table `rentalbooks`
--

CREATE TABLE `rentalbooks` (
  `rid` int(11) NOT NULL,
  `rcat_id` varchar(100) NOT NULL,
  `rsubcat_id` varchar(100) NOT NULL,
  `rbook` varchar(100) NOT NULL,
  `rimage` varchar(100) NOT NULL,
  `rprice` varchar(100) NOT NULL,
  `radvance` varchar(100) NOT NULL,
  `rquantity` varchar(100) NOT NULL,
  `rauthor` varchar(100) NOT NULL,
  `rdescription` text NOT NULL,
  `mrp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentalbooks`
--

INSERT INTO `rentalbooks` (`rid`, `rcat_id`, `rsubcat_id`, `rbook`, `rimage`, `rprice`, `radvance`, `rquantity`, `rauthor`, `rdescription`, `mrp`) VALUES
(1, '2', '2', 'rahul', '05.jpg', '50', '300', '1', 'sam', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur ad	', '70'),
(2, '2', '2', 'sachin', '03.jpg', '20', '200', '5', 'sam', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit	 Lorem ipsum dolor sit amet, consectetur ad', '30'),
(3, '2', '2', 'warner', '12.jpg', '100', '500', '5', 'anbu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit	 Lorem ipsum dolor sit amet, consectetur ad', '150'),
(4, '2', '2', 'billing', '11.jpg', '70', '1000', '3', 'anbu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit	 Lorem ipsum dolor sit amet, consectetur ad', '80'),
(5, '8', '6', 'Indian Political ', '01.jpg', '560', '', '1', 'A.P. Eshwar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit	', '650'),
(6, '8', '10', 'Silapathigaram', '04.jpg', '', '', '6', 'Elango Adigal', 'Silapathigaram is an ancient literature which is really meant the injustice of a king.', ''),
(7, '5', '5', 'geography of india', '11.jpg', '', '', '5', 'Balasubramaniyam', 'This book can explain the geological condition of india', ''),
(8, '8', '2', 'The world of Economics', '11.jpg', '', '', '5', 'Adam Smith', 'This book that refers the economic state of the world', ''),
(13, '16', '15', 'Arihant -  Fast Track Objective Arithmetic', 'Fast Track OA Book (R).jpg', '', '', '5', 'Rajesh Varma', 'Arihant -  Fast Track Objective Arithmetic', ''),
(14, '16', '16', 'Lucent GK', 'Lucent GK (R).jpg', '', '', '5', 'Lucent Publication', 'Lucent GK', ''),
(15, '16', '17', 'Objective General English - Arihant', 'SP BAKSHI (R).jpg', '', '', '5', 'SP Bakshi', 'Objective General English - Arihant', '');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rentalid` int(11) NOT NULL,
  `rendalbook` varchar(100) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `rdate` varchar(100) NOT NULL,
  `enddate` varchar(100) NOT NULL,
  `tdays` varchar(100) NOT NULL,
  `tamount` varchar(100) NOT NULL,
  `paid` varchar(100) NOT NULL,
  `due` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rentalid`, `rendalbook`, `userid`, `rdate`, `enddate`, `tdays`, `tamount`, `paid`, `due`, `status`, `remark`) VALUES
(1, '3', '1', '2023-04-04', '2023-04-06', '2', '200', '1', '199', '', ''),
(7, '4', '4', '2022-08-27', '2022-08-26', '60', '4200', '2000', '0', 'Paid', 'Amount received'),
(8, '3', '1', '2022-08-28', '2022-08-30', '10', '1000', '500', '500', 'Accepted', 'ghfj'),
(9, '2', '1', '2022-08-27', '2022-09-05', '20', '400', '400', '0', '', ''),
(10, '1', '3', '2022-08-27', '2022-10-16', '50', '2500', '1000', '1500', '', ''),
(12, '2', '4', '2022-10-09', '2022-11-28', '50', '1000', '450', '550', '', ''),
(13, '3', '4', '2022-10-09', '2022-10-28', '19', '1900', '500', '1400', '', ''),
(14, '3', '1', '2023-04-04', '2023-04-09', '5', '500', '1000', '', 'Paid', 'Paid'),
(15, '2', '9', '2023-05-05', '2023-05-10', '5', '500', '1000', '', 'Rejected', 'Out of stock'),
(16, '2', '8', '2023-05-29', '2023-06-03', '5', '500', '1000', '', 'Paid', 'Paid '),
(17, '8', '11', '2023-05-30', '2023-06-04', '5', '500', '1000', '', 'Paid', 'Payment Recieved'),
(18, '6', '8', '2023-05-31', '2023-06-05', '5', '500', '1000', '', 'Paid', 'Paid'),
(19, '14', '20', '2023-06-26', '2023-06-27', '1', '100', '1000', '', 'Paid', 'Payment Paid');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `sid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sid`, `name`, `email`, `phone`, `address`, `photo`, `username`, `password`) VALUES
(1, 'anbarasan', 'arunkumarsaravanan1234@gmail.com', '9988776655', 'devakttai', 'photo.jpg', 'anbu', '81dc9bdb52d04dc20036dbd8313ed055'),
(2, 'ram', 'ram@gmail.com', '8877559988', 'karaikudi', 'girl-flat-icon-11549760838cubn4qroau.png', 'ram', '827ccb0eea8a706c4c34a16891f84e7b'),
(3, 'arun', 'arun@gmail.com', '7774374759', 'chennai', 'girl-flat-icon-11549760838cubn4qroau.png', 'arun', '827ccb0eea8a706c4c34a16891f84e7b'),
(4, 'admin', 'anbar5@gmail.com', '6784374759', 'Chennai - 600088.', 'girl-flat-icon-11549760838cubn4qroau.png', 'admin', '827ccb0eea8a706c4c34a16891f84e7b'),
(5, 'aaa', 'aaa@gmail.com', '123456', 'aaaa', 'balloons120.jpg', 'aaa', '1552c03e78d38d5005d4ce7b8018addf'),
(6, 'ak', 'ak@gmail.com', '9988776655', '1234', 'us.jpg', 'ak', '81dc9bdb52d04dc20036dbd8313ed055'),
(8, 'Ranjithkumar', 'ranjith@gmail.com', '8889988776', 'Anna nagar, Karaikudi. 630001', 'doctor_strange_by_scarlettcindy-daon0ly.jpg', 'ranjith', '674f3c2c1a8a6f90461e8a66fb5550ba'),
(9, 'Kumar', 'kumar@gmail.com', '9988776655', 'Karaikudi, Tamilnadu, 630001', '', 'Kumar', '9e1e06ec8e02f0a0074f2fcc6b26303b'),
(11, 'demon', 'demo@gmail.com', '9988776655', 'kkdi', 'thanossmile.jpg', 'demo', '81dc9bdb52d04dc20036dbd8313ed055'),
(12, 'Raja', 'raja@gmail.com', '9988776655', 'Kkdi', 'thanossmile.jpg', 'Raja', '81dc9bdb52d04dc20036dbd8313ed055'),
(13, 'EARider', 'dummy@gmail.com', '0000000000', 'bknh', '', 'EARider', '1e059220999e8f8690367a2fd2bb1a02'),
(20, 'ravi', 'ravi@gmail.com', '9776654321', 'KKDI', 'thanossmile.jpg', 'ravi', '81dc9bdb52d04dc20036dbd8313ed055'),
(21, 'Dhivakaran', 'dhiva106@gmail.com', '6382810681', 'Karaikudi', '', 'Dhivakaran', '462eff4e745611f16ad81a81b2438640');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_id` int(11) NOT NULL,
  `categoryid` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcategory_id`, `categoryid`, `subcategory`) VALUES
(15, '16', 'Aptitude'),
(16, '16', 'General Knowledge'),
(17, '16', 'English'),
(19, '18', 'GENERAL KNOWLEDGE'),
(20, '19', 'GENERAL KNOWLEDGE'),
(21, '20', 'GENERAL KNOWLEDGE');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectid` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectid`, `subject`) VALUES
(7, 'REASONING'),
(9, 'UPSC');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `subid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sub_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subid`, `email`, `sub_date`) VALUES
(16, 'na.amizhtharasan23@gmail.com', '2023-06-05 15:47:41'),
(18, 'ak@gmail.com', '2023-06-26 13:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `sub_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `sdate` varchar(100) NOT NULL,
  `edate` varchar(100) NOT NULL,
  `magazine` varchar(100) NOT NULL,
  `splan` varchar(100) NOT NULL,
  `sprice` varchar(100) NOT NULL,
  `payment_id` varchar(100) NOT NULL,
  `substatus` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`sub_id`, `user_id`, `sdate`, `edate`, `magazine`, `splan`, `sprice`, `payment_id`, `substatus`, `remark`) VALUES
(26, '21', '2024-03-29', '2024-04-29', '22', '1', '30', 'pay_Ns46ctW8gAqaSx', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblbanner`
--

CREATE TABLE `tblbanner` (
  `bannerID` int(10) NOT NULL,
  `position` varchar(100) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `bannertime` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbanner`
--

INSERT INTO `tblbanner` (`bannerID`, `position`, `banner`, `bannertime`) VALUES
(14, 'mag', '9969e27d326f787da28f3a3ff5e16ac51679055685.jpg', '2023-03-17'),
(15, 'book', '0833a234c565fc717a48364d81c1e3d31679047477.jpg', '2023-03-17'),
(16, 'mag', 'e8c30e3d8a5b3d381b1b4252979886371679055570.jpg', '2023-03-17'),
(17, 'course', '58e44ba00b68307e29f02ea4b796735f1679057191.jpg', '2023-03-17'),
(18, 'course', 'bbdb9a5b9960e518ae6dabad091e333c1679060340.jpg', '2023-03-17'),
(19, 'side1', '5a135b1ac557bbd388a36d9a68a027cb1683282354.jpg', '2023-05-05'),
(20, 'book', '84e258d7f69f1eb8d5f545cba48ffd401685433673.png', '2023-05-29'),
(21, 'side1', 'd7088d5b6cb35803d304c3a4e86016a71685441899.jpg', '2023-05-30'),
(22, 'side1', '00493b3f5af40f5e614214702e49cd8f1685598443.jpg', '2023-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `sprice` varchar(100) NOT NULL,
  `mrp` varchar(100) NOT NULL,
  `descri` mediumtext NOT NULL,
  `fea` mediumtext NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `title`, `sprice`, `mrp`, `descri`, `fea`, `image`) VALUES
(12, 'TNPSC General Studies PYQ - TEST SERIES', '2500', '3000', 'Test Series for TNPSC Group 1 - 4 TET | TRB | TNUSRB | RRB', '◉ 12 Major Test / 36 Mini Test\r\n\r\n◉ 1 Year Package', 'Test Pack 1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `test_subscription`
--

CREATE TABLE `test_subscription` (
  `id` int(11) NOT NULL,
  `uid` int(100) NOT NULL,
  `mid` int(100) NOT NULL,
  `intime` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_id` varchar(100) NOT NULL,
  `status` int(10) NOT NULL DEFAULT 0,
  `remarks` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_subscription`
--

INSERT INTO `test_subscription` (`id`, `uid`, `mid`, `intime`, `payment_id`, `status`, `remarks`) VALUES
(17, 21, 12, '2024-03-29 06:07:45', 'pay_Ns4AstwniL0M4I', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `topicid` int(11) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `subject_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`topicid`, `topic`, `subject_id`) VALUES
(1, 'algebras', '1'),
(2, 'form', '2'),
(3, 'Indian Policy', '3'),
(5, 'Atomic structure', '4'),
(6, 'Css and Styles', '6'),
(7, 'ANALOGY', '7'),
(9, 'General English', '9');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `phone_no` int(13) NOT NULL,
  `address` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `regdate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vid`, `name`, `Email`, `phone_no`, `address`, `message`, `regdate`) VALUES
(5, 'Partner', 'demo@gmail.com', 2147483647, 'KKDI', 'This is test message', '2023-06-01 05:46:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`caid`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uiid` (`userID`),
  ADD KEY `piddd` (`productId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_subscription`
--
ALTER TABLE `course_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`guid`);

--
-- Indexes for table `magazines`
--
ALTER TABLE `magazines`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `online_order`
--
ALTER TABLE `online_order`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uidddd` (`userId`),
  ADD KEY `addressid` (`addressId`),
  ADD KEY `orderNumber` (`orderNumber`);

--
-- Indexes for table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderidd` (`orderNumber`),
  ADD KEY `useridd` (`userId`),
  ADD KEY `productiddd` (`productId`);

--
-- Indexes for table `ordertrackhistory`
--
ALTER TABLE `ordertrackhistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderidddddd` (`orderId`);

--
-- Indexes for table `precontent`
--
ALTER TABLE `precontent`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `rentalbooks`
--
ALTER TABLE `rentalbooks`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rentalid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectid`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`subid`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `tblbanner`
--
ALTER TABLE `tblbanner`
  ADD PRIMARY KEY (`bannerID`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_subscription`
--
ALTER TABLE `test_subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topicid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `career`
--
ALTER TABLE `career`
  MODIFY `caid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `course_subscription`
--
ALTER TABLE `course_subscription`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `eid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guide`
--
ALTER TABLE `guide`
  MODIFY `guid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `magazines`
--
ALTER TABLE `magazines`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `online_order`
--
ALTER TABLE `online_order`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `ordertrackhistory`
--
ALTER TABLE `ordertrackhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `precontent`
--
ALTER TABLE `precontent`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rentalbooks`
--
ALTER TABLE `rentalbooks`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rentalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `subid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblbanner`
--
ALTER TABLE `tblbanner`
  MODIFY `bannerID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `test_subscription`
--
ALTER TABLE `test_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `topicid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
