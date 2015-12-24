-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2015 at 03:20 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rentrmnl_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_rate` decimal(10,2) NOT NULL,
  `item_pic` blob NOT NULL,
  `item_stats` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_cash_bond` int(11) NOT NULL,
  `item_rental_mode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `item_penalty` int(11) NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `items_shop_id_foreign` (`shop_id`),
  KEY `items_subscriber_id_foreign` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items_categories`
--

CREATE TABLE IF NOT EXISTS `items_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `items_categories_item_id_foreign` (`item_id`),
  KEY `items_categories_category_id_foreign` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_details`
--

CREATE TABLE IF NOT EXISTS `item_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_details_item_id_foreign` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_pictures`
--

CREATE TABLE IF NOT EXISTS `item_pictures` (
  `picture_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`picture_id`),
  KEY `item_pictures_item_id_foreign` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lessees`
--

CREATE TABLE IF NOT EXISTS `lessees` (
  `lessee_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lessee_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_phoneno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`lessee_id`),
  UNIQUE KEY `lessees_lessee_email_unique` (`lessee_email`),
  UNIQUE KEY `lessees_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `my_interests`
--

CREATE TABLE IF NOT EXISTS `my_interests` (
  `interest_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `interest_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`interest_id`),
  KEY `my_interests_lessee_id_foreign` (`lessee_id`),
  KEY `my_interests_item_id_foreign` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `my_shops`
--

CREATE TABLE IF NOT EXISTS `my_shops` (
  `myshop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `myshop_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_id` int(10) unsigned NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`myshop_id`),
  KEY `my_shops_lessee_id_foreign` (`lessee_id`),
  KEY `my_shops_shop_id_foreign` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_payments`
--

CREATE TABLE IF NOT EXISTS `rental_payments` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_amt` decimal(12,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `reserve_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `rental_payments_reserve_id_foreign` (`reserve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_penalties`
--

CREATE TABLE IF NOT EXISTS `rental_penalties` (
  `penalty_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `penalty_amt` decimal(12,2) NOT NULL,
  `penalty_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reserve_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`penalty_id`),
  KEY `rental_penalties_reserve_id_foreign` (`reserve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_reservations`
--

CREATE TABLE IF NOT EXISTS `rental_reservations` (
  `reserve_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reserve_date` datetime NOT NULL,
  `date_rented` datetime NOT NULL,
  `date_returned` datetime NOT NULL,
  `total_amt` decimal(12,2) NOT NULL,
  `down_payment` decimal(12,2) NOT NULL,
  `total_balance` decimal(12,2) NOT NULL,
  `penalty` int(11) NOT NULL,
  `status` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `lessee_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`reserve_id`),
  KEY `rental_reservations_lessee_id_foreign` (`lessee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_shops`
--

CREATE TABLE IF NOT EXISTS `rental_shops` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shop_branch` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(10,2) NOT NULL,
  `longitude` decimal(10,2) NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`shop_id`),
  KEY `rental_shops_subscriber_id_foreign` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_details`
--

CREATE TABLE IF NOT EXISTS `reservation_details` (
  `reserve_detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rental_amt` decimal(12,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `reserve_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`reserve_detail_id`),
  KEY `reservation_details_item_id_foreign` (`item_id`),
  KEY `reservation_details_reserve_id_foreign` (`reserve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `subscriber_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_midint` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriber_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_telno` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriber_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_paypal_account` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscriber_status` char(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `subscriber_type` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`subscriber_id`),
  UNIQUE KEY `subscribers_subscriber_email_unique` (`subscriber_email`),
  UNIQUE KEY `subscribers_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `subscription_amt` double(12,2) NOT NULL,
  `status` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `qty` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  `plan_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `subscriptions_subscriber_id_foreign` (`subscriber_id`),
  KEY `subscriptions_plan_id_foreign` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `plan_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plan_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plan_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plan_rate` double(12,2) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `super_admins`
--

CREATE TABLE IF NOT EXISTS `super_admins` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_midint` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `admin_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_status` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`subscriber_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `rental_shops` (`shop_id`) ON DELETE CASCADE;

--
-- Constraints for table `items_categories`
--
ALTER TABLE `items_categories`
  ADD CONSTRAINT `items_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_categories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_details`
--
ALTER TABLE `item_details`
  ADD CONSTRAINT `item_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_pictures`
--
ALTER TABLE `item_pictures`
  ADD CONSTRAINT `item_pictures_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `my_interests`
--
ALTER TABLE `my_interests`
  ADD CONSTRAINT `my_interests_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `my_interests_lessee_id_foreign` FOREIGN KEY (`lessee_id`) REFERENCES `lessees` (`lessee_id`) ON DELETE CASCADE;

--
-- Constraints for table `my_shops`
--
ALTER TABLE `my_shops`
  ADD CONSTRAINT `my_shops_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `rental_shops` (`shop_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `my_shops_lessee_id_foreign` FOREIGN KEY (`lessee_id`) REFERENCES `lessees` (`lessee_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD CONSTRAINT `rental_payments_reserve_id_foreign` FOREIGN KEY (`reserve_id`) REFERENCES `rental_reservations` (`reserve_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_penalties`
--
ALTER TABLE `rental_penalties`
  ADD CONSTRAINT `rental_penalties_reserve_id_foreign` FOREIGN KEY (`reserve_id`) REFERENCES `rental_reservations` (`reserve_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_reservations`
--
ALTER TABLE `rental_reservations`
  ADD CONSTRAINT `rental_reservations_lessee_id_foreign` FOREIGN KEY (`lessee_id`) REFERENCES `lessees` (`lessee_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_shops`
--
ALTER TABLE `rental_shops`
  ADD CONSTRAINT `rental_shops_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`subscriber_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_details`
--
ALTER TABLE `reservation_details`
  ADD CONSTRAINT `reservation_details_reserve_id_foreign` FOREIGN KEY (`reserve_id`) REFERENCES `rental_reservations` (`reserve_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`subscriber_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
