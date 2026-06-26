-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2026 at 09:05 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$Zz8lWFsoSjIJWpRFGVWBRORTrLfB2JMXIEsJGPSvVrXSZLMr0G4cW', '2025-09-09 04:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `food_item_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `food_item_id` (`food_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Pizza'),
(2, 'Burgers'),
(3, 'Drinks'),
(4, 'Desserts'),
(5, 'Salads');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

DROP TABLE IF EXISTS `food_items`;
CREATE TABLE IF NOT EXISTS `food_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(1, 'Margherita Pizza', 'Classic pizza with tomato, mozzarella, and basil', 899.00, 'images/margherita.jpg', 1),
(2, 'Farmhouse Pizza', 'Loaded with bell peppers, olives, onions, and mushrooms', 999.00, 'images/farmhouse.jpg', 1),
(3, 'Paneer Tikka Pizza', 'Tandoori paneer with onions and capsicum', 1099.00, 'images/paneer_tikka.jpg', 1),
(4, 'Veggie Burger', 'Delicious burger with grilled vegetables and sauce', 749.00, 'images/veggie_burger.jpg', 2),
(5, 'Aloo Tikki Burger', 'Spicy potato patty with chutney and veggies', 699.00, 'images/aloo_tikki_burger.jpg', 2),
(6, 'Cheese Corn Burger', 'Burger with cheese and sweet corn patty', 799.00, 'images/cheese_corn_burger.jpg', 2),
(7, 'Coca-Cola', 'A timeless taste that’s always refreshing.', 199.00, 'images/coke.jpg', 3),
(8, 'Orange Juice', 'Freshly squeezed goodness in every golden sip.', 249.00, 'images/orange_juice.jpg', 3),
(9, 'Lemonade', 'A refreshing blend of tart lemons and sweet simplicity.', 159.00, 'images/lemonade.jpg', 3),
(10, 'Chocolate Cake', 'A timeless treat layered with rich, velvety chocolate.', 499.00, 'images/chocolate_cake.jpg', 4),
(11, 'Ice Cream Sundae', 'Vanilla ice cream with chocolate syrup and nuts', 399.00, 'images/ice_cream_sundae.jpg', 4),
(12, 'Cheesecake', 'Creamy cheesecake with strawberry topping', 549.00, 'images/cheesecake.jpg', 4),
(13, 'Caesar Salad', 'Romaine lettuce with Caesar dressing and croutons', 699.00, 'images/caesar_salad.jpg', 5),
(14, 'Greek Salad', 'Tomatoes, cucumbers, olives, and feta cheese', 749.00, 'images/greek_salad.jpg', 5),
(15, 'Quinoa Salad', 'Healthy quinoa with fresh veggies and herbs', 799.00, 'images/quinoa_salad.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Processing','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_orders_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`) VALUES
(3, 2, 699.00, 'Processing', '2025-11-26 13:03:35'),
(2, 2, 499.00, '', '2025-09-10 05:07:18'),
(4, 2, 699.00, 'Delivered', '2025-11-26 13:09:27'),
(5, 2, 998.00, 'Pending', '2025-11-26 14:18:29'),
(6, 3, 999.00, 'Delivered', '2025-12-02 14:10:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `food_item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order_id` (`order_id`),
  KEY `fk_order_items_food_item_id` (`food_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_item_id`, `quantity`, `price`, `status`) VALUES
(1, 1, 5, 3, 699.00, NULL),
(2, 2, 5, 1, 699.00, NULL),
(3, 2, 13, 1, 699.00, NULL),
(4, 3, 13, 1, 699.00, NULL),
(5, 4, 13, 1, 699.00, NULL),
(6, 5, 5, 1, 699.00, NULL),
(7, 5, 14, 1, 749.00, NULL),
(8, 6, 5, 1, 699.00, NULL),
(9, 6, 13, 1, 699.00, NULL),
(10, 7, 5, 1, 699.00, NULL),
(11, 1, 5, 2, 699.00, NULL),
(12, 2, 10, 1, 499.00, NULL),
(13, 3, 5, 1, 699.00, NULL),
(14, 4, 5, 1, 699.00, NULL),
(15, 5, 10, 2, 499.00, NULL),
(16, 6, 2, 1, 999.00, NULL),
(17, 7, 2, 1, 999.00, NULL),
(18, 7, 10, 1, 499.00, NULL),
(19, 8, 5, 3, 699.00, NULL),
(20, 9, 5, 3, 699.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `created_at`, `role`) VALUES
(3, 'prit kasundra', 'prit25@gmail.com', '$2y$10$0zFuMvjXrRC1cK4i/XuHhuwioJNU8u9C/9R8p9hO7m6kAvdA.Na8m', 'jalalpore', '01234123412', '2025-09-19 04:22:19', 'user'),
(2, 'pankaj gohil', 'pankaj12@gmail.com', '$2y$10$PzVWj8Skhqmq343iq88UpuYiSYAOKEcHYBc7mkmY8mrMZKky0R33K', 'lk', '1234123411', '2025-09-10 05:06:38', 'user'),
(4, 'abhi', 'abhi@gmail.com', '$2y$10$znBgc42dpxfJtuN4o3FmIOBZD1kKB6A9aZCNBWilq3xzlc.QCU76.', 'jalalpore', '1122334455', '2025-12-03 03:40:04', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
