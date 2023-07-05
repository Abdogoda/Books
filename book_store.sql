-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2023 at 06:36 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `author` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `image`, `price`, `category_id`, `description`, `author`, `date`) VALUES
(206, 'Open The Sky', 'fvYX6MiLR5O2ECw5aBsP.png', '153', 21, 'A classic novel that explores racial injustice and the loss of innocence in a small Southern town.', 20, '2023-06-24'),
(207, 'Pride and Prejudice', 'NX5ESf0w4gwDXrB5ZOtA.png', '201', 18, 'A classic romance novel set in Georgian-era England that explores themes of love, class, and societal expectations.', 22, '2023-06-24'),
(209, 'The Lord of the Rings', 's7vQPpJHjt0fQ8GapB2e.png', '300', 16, 'A high fantasy novel that follows the adventures of hobbit Frodo Baggins as he attempts to destroy the powerful One Ring.', 20, '2023-06-24'),
(210, 'The Art City', 'fqJMte08v4mzY3phGHUd.png', '546', 23, 'This book presents a unique approach to personal and professional development, encouraging readers to adopt a positive, possibility-oriented mindset. The authors draw on their experiences as a therapist and a conductor, respectively, to offer insights and', 21, '2023-06-24'),
(211, 'Me Before You', 'bNDAChn816VhthplbPQS.png', '250', 18, ' A heart-wrenching novel about a young woman named Louisa Clark who takes a job caring for a quadriplegic man named Will Traynor, and the unexpected romance that develops between them.', 22, '2023-06-24'),
(212, 'The Design of Everyday Things', 'sLL17H4Uk5kbGOwI5Jap.png', '255', 24, 'A classic book on design that explores the principles of usability and user-centered design, and provides a framework for creating products that are intuitive, efficient, and enjoyable to use.', 20, '2023-06-24'),
(213, 'Give Thanks In Everything', 'voMv0yaB5fv1I91aubU0.png', '350', 24, ' A guide to typography that explores the principles of type design, layout, and hierarchy, and provides practical tips and examples for creating effective and engaging typography.', 21, '2023-06-24'),
(214, 'Seductive Interaction Design', 'N08nXHYUU2pCAaBeNyGW.png', '125', 23, 'A guide to designing engaging and persuasive user interfaces that encourages users to take action and engage with products and services.\r\n', 22, '2023-06-24'),
(215, 'Every Thing You Never', 'YkZZF12z99N2Tbco3Lrs.png', '500', 24, 'A guide to designing interactive products and services that explores the principles of user-centered design, interaction design, and information architecture, and provides practical tips and examples for creating effective and engaging products.', 20, '2023-06-24'),
(216, 'Murdering Last Year', 's5NdN1DdSHO5jjdY0ct4.png', '320', 19, 'A murder book is a term used by law enforcement to describe a collection of documents and evidence related to an investigation into a homicide or murder case. The murder book typically contains reports from witnesses, investigators, and crime scene techni', 20, '2023-06-26');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `image`) VALUES
(1, 'Science', 'category_14.jpg'),
(2, 'History', 'category_11.jpg'),
(3, 'Sports', 'category_18.jpg'),
(4, 'Crime', 'category_20.jpg'),
(5, 'Travel', 'category_19.jpg'),
(8, 'Health', 'category_7.jpg'),
(9, 'Dictionary', 'category_8.jpg'),
(10, 'Philosophy', 'category_2.jpg'),
(11, 'Encyclopedia', 'category_10.jpg'),
(16, 'Fantasy ', 'category_4.jpg'),
(18, 'Romance', 'category_13.jpg'),
(19, 'Mystery', 'category_1.jpg'),
(20, 'Young', 'category_15.jpg'),
(21, 'Horror', 'category_21.jpg'),
(22, 'Historical', 'category_11.jpg'),
(23, 'Personal', 'category_16.jpg'),
(24, 'Art', 'category_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `ssn` varchar(14) NOT NULL,
  `birth_of_date` date NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `job_id`, `ssn`, `birth_of_date`, `gender`) VALUES
(1, 1, '45678912345678', '2001-09-25', 0),
(20, 3, '45645464454165', '1991-05-22', 1),
(21, 3, '45645465454164', '1985-03-15', 0),
(22, 3, '15845645649879', '1986-05-04', 1),
(23, 2, '15241654561214', '2000-06-09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ideas`
--

CREATE TABLE `ideas` (
  `id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ideas`
--

INSERT INTO `ideas` (`id`, `e_id`, `title`, `content`, `date`) VALUES
(1, 1, 'idea1', 'this is test idea with the title of idea1', '2023-06-26 00:00:00'),
(2, 1, 'idea1', 'this is test idea with the title of idea1', '2023-06-26 00:00:00'),
(3, 1, 'idea2', 'this is a test idea with the title of idea2\r\n', '2023-06-26 00:00:00'),
(4, 1, 'idea5', 'This Is A Test Idea With The Title Of Idea5\r\n', '2023-07-02 19:45:26');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job`) VALUES
(1, 'Manager'),
(2, 'IT'),
(3, 'Author'),
(4, 'Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `job_permisions`
--

CREATE TABLE `job_permisions` (
  `job_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `job_permisions`
--

INSERT INTO `job_permisions` (`job_id`, `permission_id`) VALUES
(1, 1),
(1, 5),
(3, 5),
(3, 2),
(1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `content`, `date`) VALUES
(1, 'User One', 'user1@gmail.com', 'Hello There,\r\nI just want to share with you how much the website is useful and easy to use,\r\nThank you so much for this great experince', '2023-07-02 19:28:01'),
(2, 'User Two', 'user2@gmail.com', 'Hello There, I Just Want To Share With You How Much The Website Is Useful And Easy To Use, Thank You So Much For This Great Experince', '2023-07-02 22:21:47'),
(3, 'User Three', 'user3@gmail.com', 'Hello There, I Just Want To Share With You How Much The Website Is Useful And Easy To Use, Thank You So Much For This Great Experince', '2023-07-02 22:21:55'),
(4, 'test user', 'test@test.test', 'test message\r\n', '2023-07-05 02:44:09'),
(5, 'test user', 'test@test.test', 'I recently discovered an amazing online bookshop and I couldn\'t be happier with my experience! The website is user-friendly, making it easy to browse through their extensive collection. The ordering process was seamless, and the books arrived well-packaged and in perfect condition. Their prices were competitive, and they offer frequent discounts and promotions. I highly recommend this online bookshop for any book lover!', '2023-07-05 02:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `sub_total` double NOT NULL DEFAULT 0,
  `taxes` double NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `u_id`, `sub_total`, `taxes`, `total`, `date`) VALUES
(1, 11, 50, 10, 60, '2023-01-25'),
(2, 9, 350, 10, 360, '2023-06-25'),
(3, 9, 100, 50, 150, '2023-07-01'),
(5, 11, 5490, 549, 5490, '2023-07-04'),
(6, 11, 3200, 320, 3200, '2023-07-04'),
(7, 11, 500, 50, 500, '2023-07-04'),
(8, 11, 125, 12.5, 125, '2023-07-04'),
(9, 11, 1713, 171.3, 1884.3, '2023-07-04'),
(10, 11, 459, 45.9, 504.9, '2023-07-04'),
(11, 11, 320, 32, 352, '2023-07-04'),
(12, 11, 320, 32, 352, '2023-07-05');

-- --------------------------------------------------------

--
-- Table structure for table `order_books`
--

CREATE TABLE `order_books` (
  `o_b_id` int(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `b_id` int(11) NOT NULL,
  `o_price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `o_total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_books`
--

INSERT INTO `order_books` (`o_b_id`, `o_id`, `b_id`, `o_price`, `quantity`, `o_total`) VALUES
(1, 1, 206, 153, 5, 765),
(2, 1, 207, 142, 3, 426),
(3, 2, 213, 350, 1, 350),
(4, 5, 216, 320, 4, 1280),
(5, 5, 211, 250, 1, 250),
(6, 5, 212, 255, 2, 510),
(7, 5, 209, 300, 1, 300),
(8, 5, 213, 350, 9, 3150),
(9, 6, 216, 320, 10, 3200),
(10, 7, 215, 500, 1, 500),
(11, 8, 214, 125, 1, 125),
(12, 9, 214, 125, 2, 250),
(13, 9, 211, 250, 2, 500),
(14, 9, 206, 153, 2, 306),
(15, 9, 207, 201, 2, 402),
(16, 9, 212, 255, 1, 255),
(17, 10, 206, 153, 3, 459),
(18, 11, 216, 320, 1, 320),
(19, 12, 216, 320, 1, 320);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `o_id` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `building` varchar(50) NOT NULL,
  `payment_method` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`o_id`, `city`, `street`, `building`, `payment_method`) VALUES
(1, 'Shildon', 'Dalton Crescent', '6', 0),
(2, 'Lymm', 'Higher Lane', '18C', 1),
(5, 'cairo', 'alqudos', '45', 0),
(6, 'NYC', 'Square', '45', 0),
(7, 'Austin, Texas', 'Elm Street', '5', 1),
(8, 'Portland, Oregon', 'Main Street', '98', 1),
(9, 'Denver, Colorado', 'Oak Avenue', '8', 1),
(10, 'Seattle, Washington', 'Pine Lane', '7', 1),
(11, 'Chicago, Illinois', 'Walnut Boulevard', '88', 0),
(12, 'New York City, New York', 'Willow Place', '5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_payment`
--

CREATE TABLE `order_payment` (
  `o_id` int(11) NOT NULL,
  `card_number` varchar(16) NOT NULL,
  `card_holder` varchar(50) NOT NULL,
  `card_cvv` varchar(4) NOT NULL,
  `expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_payment`
--

INSERT INTO `order_payment` (`o_id`, `card_number`, `card_holder`, `card_cvv`, `expiration_date`) VALUES
(2, '4567891234567895', 'Anna De Armas', '5459', '2023-06-29'),
(7, '', '', '', '0000-00-00'),
(8, '5465456454645646', 'Thia Queen', '8947', '2023-08-04'),
(9, '5456456456465456', 'Thia Queen', '4564', '2032-07-28'),
(10, '8797898978978978', 'Thia Queen', '4654', '2023-08-03');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `s_id` int(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `status` varchar(15) NOT NULL,
  `status_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`s_id`, `o_id`, `e_id`, `status`, `status_date`) VALUES
(1, 1, 1, 'Pending', '2023-06-25'),
(2, 2, 1, 'Completed', '2023-06-25'),
(9, 1, 1, 'Completed', '2023-07-02'),
(10, 10, 1, 'Completed', '2023-07-04'),
(11, 11, 1, 'Pending', '2023-07-04'),
(12, 5, 1, 'Pending', '2023-07-04'),
(13, 6, 1, 'Pending', '2023-07-04'),
(14, 7, 1, 'Completed', '2023-07-04'),
(15, 8, 1, 'Completed', '2023-07-04'),
(16, 9, 1, 'Completed', '2023-07-04'),
(17, 11, 1, ' Cancelled', '2023-07-04'),
(18, 12, 1, 'Pending', '2023-07-05');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`) VALUES
(1, 'Dashboard'),
(2, 'Orders'),
(3, 'Team'),
(4, 'Customers'),
(5, 'Books'),
(6, 'Messages'),
(7, 'Plans'),
(8, 'Settings'),
(9, 'Notifications'),
(10, 'Add Employee');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT 1,
  `image` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password`, `group_id`, `image`, `date`) VALUES
(1, 'Abdo Goda', 'abdogoda@gmail.com', '01142366716', 'Ground Floor And Basement, 22 - 44 London Lane, London,E8 3PRB', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0, 'G06wKeq6shNYiHSJci1S.png', '2023-06-22'),
(9, 'Anna De Armas', 'anna@gmail.com', '01512163415', '254 Moss Bay Road, Workington, CA14 3TL', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 'HwLaOt8UfI2VYO6qnkwF.jpg', '2023-06-22'),
(11, 'Thia Queen', 'thia@gmail.com', '01212465456', 'Thia address', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 'BdIzIzwCE2qMIW2n2KdS.jpg', '2023-06-23'),
(20, 'Chimamanda Ngozi Adichie', 'chimamanda@gmail.com', '01234567896', '56 Forde Park, Yeovil,BA21 3QR', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0, '89KRrg1f2G739O2OnW4a.jpg', '2023-06-25'),
(21, 'Haruki Murakami', 'haruki@gmail.com', '01019135056', '26 Canterbury Way, Wideopen,NE13 6JH', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0, 'BiWesCrQzF60YwHgj931.jpg', '2023-06-25'),
(22, 'J.K. Rowling', 'jk@gmail.com', '01010362040', 'Tangaer, Heol Hathren, Cwmann,SA48 8JR', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0, '5dNAYotupMASBEQJXt2f.jpg', '2023-06-25'),
(23, 'Mike Jim', 'mike@gmail.com', '01234667894', '1 Beaufort Gardens, South Petherton,TA13 5HS', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0, 'KTpWHQ9LwCCjMc562b1x.png', '2023-06-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `ideas`
--
ALTER TABLE `ideas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `e_id` (`e_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_permisions`
--
ALTER TABLE `job_permisions`
  ADD KEY `job_id` (`job_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `order_books`
--
ALTER TABLE `order_books`
  ADD PRIMARY KEY (`o_b_id`),
  ADD KEY `o_id` (`o_id`),
  ADD KEY `b_id` (`b_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD KEY `o_id` (`o_id`);

--
-- Indexes for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD KEY `o_id` (`o_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`s_id`),
  ADD KEY `order_status_ibfk_1` (`e_id`),
  ADD KEY `o_id` (`o_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ideas`
--
ALTER TABLE `ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_books`
--
ALTER TABLE `order_books`
  MODIFY `o_b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ideas`
--
ALTER TABLE `ideas`
  ADD CONSTRAINT `ideas_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_permisions`
--
ALTER TABLE `job_permisions`
  ADD CONSTRAINT `job_permisions_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_permisions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_books`
--
ALTER TABLE `order_books`
  ADD CONSTRAINT `order_books_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_payment`
--
ALTER TABLE `order_payment`
  ADD CONSTRAINT `order_payment_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_status_ibfk_2` FOREIGN KEY (`o_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
