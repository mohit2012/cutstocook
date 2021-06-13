-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2021 at 12:41 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodeli_license_testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `remember_token` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `remember_token`, `image`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$Ejc2oqFDYYGMlFoBxWWBUeMWioIZXtMWmQotc/VlYD5i8/nr7pFu2', NULL, 'admin.png', 1, '2019-11-04 00:00:00', '2021-04-22 13:12:30', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notification`
--

CREATE TABLE `admin_notification` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_setting`
--

CREATE TABLE `company_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `logo` varchar(50) NOT NULL,
  `favicon` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_setting`
--

INSERT INTO `company_setting` (`id`, `name`, `address`, `location`, `phone`, `email`, `website`, `description`, `logo`, `favicon`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Foodeli', '22,GreenLand, Icepark', NULL, '8769543456', 'contact@saasmonks.in', 'saasmonks.in', 'The entire food industry is booming with Application launches and campaigns to generate a user base.\r\nThe restaurant business is in revolutionizing pace. Food chain business is competing in the market with technology but the sure-shot solution is Applicat', '6081562c85d51.png', '6081562c85d51.png', '2019-11-15 00:00:00', '2020-10-01 05:31:31', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `discount` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `max_use` int(11) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  `use_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `use_for` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `symbol` varchar(100) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `country`, `currency`, `code`, `symbol`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek'),
(2, 'America', 'Dollars', 'USD', '$'),
(3, 'Afghanistan', 'Afghanis', 'AFN', '؋'),
(4, 'Argentina', 'Pesos', 'ARS', '$'),
(5, 'Aruba', 'Guilders', 'AWG', 'Afl'),
(6, 'Australia', 'Dollars', 'AUD', '$'),
(7, 'Azerbaijan', 'New Manats', 'AZN', '₼'),
(8, 'Bahamas', 'Dollars', 'BSD', '$'),
(9, 'Barbados', 'Dollars', 'BBD', '$'),
(10, 'Belarus', 'Rubles', 'BYR', 'p.'),
(11, 'Belgium', 'Euro', 'EUR', '€'),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$'),
(13, 'Bermuda', 'Dollars', 'BMD', '$'),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b'),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM'),
(16, 'Botswana', 'Pula', 'BWP', 'P'),
(17, 'Bulgaria', 'Leva', 'BGN', 'Лв.'),
(18, 'Brazil', 'Reais', 'BRL', 'R$'),
(19, 'Britain (United Kingdom)', 'Pounds', 'GBP', '£\r\n'),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$'),
(21, 'Cambodia', 'Riels', 'KHR', '៛'),
(22, 'Canada', 'Dollars', 'CAD', '$'),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$'),
(24, 'Chile', 'Pesos', 'CLP', '$'),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥'),
(26, 'Colombia', 'Pesos', 'COP', '$'),
(27, 'Costa Rica', 'Colón', 'CRC', '₡'),
(28, 'Croatia', 'Kuna', 'HRK', 'kn'),
(29, 'Cuba', 'Pesos', 'CUP', '₱'),
(30, 'Cyprus', 'Euro', 'EUR', '€'),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč'),
(32, 'Denmark', 'Kroner', 'DKK', 'kr'),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$'),
(34, 'East Caribbean', 'Dollars', 'XCD', '$'),
(35, 'Egypt', 'Pounds', 'EGP', '£'),
(36, 'El Salvador', 'Colones', 'SVC', '$'),
(37, 'England (United Kingdom)', 'Pounds', 'GBP', '£'),
(38, 'Euro', 'Euro', 'EUR', '€'),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£'),
(40, 'Fiji', 'Dollars', 'FJD', '$'),
(41, 'France', 'Euro', 'EUR', '€'),
(42, 'Ghana', 'Cedis', 'GHC', 'GH₵'),
(43, 'Gibraltar', 'Pounds', 'GIP', '£'),
(44, 'Greece', 'Euro', 'EUR', '€'),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q'),
(46, 'Guernsey', 'Pounds', 'GGP', '£'),
(47, 'Guyana', 'Dollars', 'GYD', '$'),
(48, 'Holland (Netherlands)', 'Euro', 'EUR', '€'),
(49, 'Honduras', 'Lempiras', 'HNL', 'L'),
(50, 'Hong Kong', 'Dollars', 'HKD', '$'),
(51, 'Hungary', 'Forint', 'HUF', 'Ft'),
(52, 'Iceland', 'Kronur', 'ISK', 'kr'),
(53, 'India', 'Rupees', 'INR', '₹'),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp'),
(55, 'Iran', 'Rials', 'IRR', '﷼'),
(56, 'Ireland', 'Euro', 'EUR', '€'),
(57, 'Isle of Man', 'Pounds', 'IMP', '£'),
(58, 'Israel', 'New Shekels', 'ILS', '₪'),
(59, 'Italy', 'Euro', 'EUR', '€'),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$'),
(61, 'Japan', 'Yen', 'JPY', '¥'),
(62, 'Jersey', 'Pounds', 'JEP', '£'),
(63, 'Kazakhstan', 'Tenge', 'KZT', '₸'),
(64, 'Korea (North)', 'Won', 'KPW', '₩'),
(65, 'Korea (South)', 'Won', 'KRW', '₩'),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'Лв'),
(67, 'Laos', 'Kips', 'LAK', '	₭'),
(68, 'Latvia', 'Lati', 'LVL', 'Ls'),
(69, 'Lebanon', 'Pounds', 'LBP', '£'),
(70, 'Liberia', 'Dollars', 'LRD', '$'),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF'),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt'),
(73, 'Luxembourg', 'Euro', 'EUR', '€'),
(74, 'Macedonia', 'Denars', 'MKD', 'Ден\r\n'),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM'),
(76, 'Malta', 'Euro', 'EUR', '€'),
(77, 'Mauritius', 'Rupees', 'MUR', '₹'),
(78, 'Mexico', 'Pesos', 'MXN', '$'),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮'),
(80, 'Mozambique', 'Meticais', 'MZN', 'MT'),
(81, 'Namibia', 'Dollars', 'NAD', '$'),
(82, 'Nepal', 'Rupees', 'NPR', '₹'),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ'),
(84, 'Netherlands', 'Euro', 'EUR', '€'),
(85, 'New Zealand', 'Dollars', 'NZD', '$'),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$'),
(87, 'Nigeria', 'Nairas', 'NGN', '₦'),
(88, 'North Korea', 'Won', 'KPW', '₩'),
(89, 'Norway', 'Krone', 'NOK', 'kr'),
(90, 'Oman', 'Rials', 'OMR', '﷼'),
(91, 'Pakistan', 'Rupees', 'PKR', '₹'),
(92, 'Panama', 'Balboa', 'PAB', 'B/.'),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs'),
(94, 'Peru', 'Nuevos Soles', 'PEN', 'S/.'),
(95, 'Philippines', 'Pesos', 'PHP', 'Php'),
(96, 'Poland', 'Zlotych', 'PLN', 'zł'),
(97, 'Qatar', 'Rials', 'QAR', '﷼'),
(98, 'Romania', 'New Lei', 'RON', 'lei'),
(99, 'Russia', 'Rubles', 'RUB', '₽'),
(100, 'Saint Helena', 'Pounds', 'SHP', '£'),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼'),
(102, 'Serbia', 'Dinars', 'RSD', 'ع.د'),
(103, 'Seychelles', 'Rupees', 'SCR', '₹'),
(104, 'Singapore', 'Dollars', 'SGD', '$'),
(105, 'Slovenia', 'Euro', 'EUR', '€'),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$'),
(107, 'Somalia', 'Shillings', 'SOS', 'S'),
(108, 'South Africa', 'Rand', 'ZAR', 'R'),
(109, 'South Korea', 'Won', 'KRW', '₩'),
(110, 'Spain', 'Euro', 'EUR', '€'),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₹'),
(112, 'Sweden', 'Kronor', 'SEK', 'kr'),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF'),
(114, 'Suriname', 'Dollars', 'SRD', '$'),
(115, 'Syria', 'Pounds', 'SYP', '£'),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$'),
(117, 'Thailand', 'Baht', 'THB', '฿'),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$'),
(119, 'Turkey', 'Lira', 'TRY', 'TL'),
(120, 'Turkey', 'Liras', 'TRL', '₺'),
(121, 'Tuvalu', 'Dollars', 'TVD', '$'),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴'),
(123, 'United Kingdom', 'Pounds', 'GBP', '£'),
(124, 'United States of America', 'Dollars', 'USD', '$'),
(125, 'Uruguay', 'Pesos', 'UYU', '$U'),
(127, 'Vatican City', 'Euro', 'EUR', '€'),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs'),
(129, 'Vietnam', 'Dong', 'VND', '₫\r\n'),
(130, 'Yemen', 'Rials', 'YER', '﷼'),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `general_setting`
--

CREATE TABLE `general_setting` (
  `id` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `request_duration` int(11) DEFAULT NULL,
  `default_driver_radius` int(11) DEFAULT NULL,
  `sell_product` int(11) NOT NULL DEFAULT 0,
  `map_key` varchar(255) DEFAULT NULL,
  `push_notification` int(11) NOT NULL,
  `onesignal_app_id` varchar(255) DEFAULT NULL,
  `onesignal_project_number` varchar(255) DEFAULT NULL,
  `onesignal_api_key` varchar(255) DEFAULT NULL,
  `onesignal_auth_key` varchar(255) DEFAULT NULL,
  `web_notification` int(11) NOT NULL DEFAULT 0,
  `web_onesignal_app_id` varchar(255) DEFAULT NULL,
  `web_onesignal_api_key` varchar(255) DEFAULT NULL,
  `web_onesignal_auth_key` varchar(255) DEFAULT NULL,
  `sms_twilio` int(11) NOT NULL,
  `twilio_account_id` varchar(255) DEFAULT NULL,
  `twilio_auth_token` varchar(255) DEFAULT NULL,
  `twilio_phone_number` varchar(50) DEFAULT NULL,
  `mail_notification` int(11) NOT NULL,
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` varchar(255) DEFAULT NULL,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `delivery_charge_amount` int(11) DEFAULT 0,
  `delivery_charge_per` int(11) DEFAULT 0,
  `commission_amount` int(11) NOT NULL DEFAULT 0,
  `commission_per` int(11) NOT NULL DEFAULT 0,
  `user_verify` int(11) NOT NULL,
  `phone_verify` int(11) NOT NULL,
  `email_verify` int(11) NOT NULL,
  `primary_color` varchar(255) DEFAULT NULL,
  `license_key` varchar(255) DEFAULT NULL,
  `license_name` varchar(255) DEFAULT NULL,
  `license_status` int(11) DEFAULT NULL,
  `terms_condition` text DEFAULT NULL,
  `privacy_policy` text DEFAULT NULL,
  `about_us` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_setting`
--

INSERT INTO `general_setting` (`id`, `currency`, `request_duration`, `default_driver_radius`, `sell_product`, `map_key`, `push_notification`, `onesignal_app_id`, `onesignal_project_number`, `onesignal_api_key`, `onesignal_auth_key`, `web_notification`, `web_onesignal_app_id`, `web_onesignal_api_key`, `web_onesignal_auth_key`, `sms_twilio`, `twilio_account_id`, `twilio_auth_token`, `twilio_phone_number`, `mail_notification`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `sender_email`, `delivery_charge_amount`, `delivery_charge_per`, `commission_amount`, `commission_per`, `user_verify`, `phone_verify`, `email_verify`, `primary_color`, `license_key`, `license_name`, `license_status`, `terms_condition`, `privacy_policy`, `about_us`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'USD', 60000, 30, 0, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 50, 5, 20, 0, 0, 0, 0, '#94b92d', '3978-E723-31F0-A941', 'ODS', 1, 'This web page represents our Terms of Use and Sale (\"Agreement\") regarding our website, located at TermsFeed.com, and the tools we provide you (the \"Website\" or the \"Service\"). It was last posted on 13 September 2012. The terms, \"we\" and \"our\" as used in this Agreement refer to TermsFeed.', 'Our Privacy Policy was posted on 13 September 2012 and last updated on 22 Mar 2020. It governs the privacy terms of our website, located at TermsFeed.com, and the tools we provide you (the \"Website\" or the \"Service\"). Any capitalized terms not defined in our Privacy Policy, have the meaning as specified in our Terms', 'We work with lawyers attorneys paralegals solicitors and people from the legal industry to bring high-quality and on-demand legal agreements, so you can focus on your business.TermsFeed is a company spread out across the world. Lawyers attorneys and the people we work with are from all over the world.', '2019-11-15 00:00:00', '2021-04-22 13:12:30', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `grocery_category`
--

CREATE TABLE `grocery_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_item`
--

CREATE TABLE `grocery_item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `fake_price` int(11) NOT NULL,
  `sell_price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `stoke` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_order`
--

CREATE TABLE `grocery_order` (
  `id` int(11) NOT NULL,
  `order_no` varchar(50) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `deliveryBoy_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `items` varchar(255) DEFAULT NULL,
  `payment` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `delivery_charge` int(11) NOT NULL,
  `delivery_type` varchar(50) DEFAULT NULL,
  `coupon_price` int(11) DEFAULT 0,
  `discount` int(11) DEFAULT 0,
  `order_status` varchar(50) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `payment_token` varchar(50) DEFAULT NULL,
  `order_otp` varchar(50) DEFAULT NULL,
  `reject_by` varchar(255) DEFAULT NULL,
  `review_status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_order_child`
--

CREATE TABLE `grocery_order_child` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_review`
--

CREATE TABLE `grocery_review` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `deliveryBoy_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `rate` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_shop`
--

CREATE TABLE `grocery_shop` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location` int(11) NOT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `radius` int(11) NOT NULL,
  `open_time` varchar(255) NOT NULL,
  `close_time` varchar(255) NOT NULL,
  `delivery_charge` int(11) NOT NULL,
  `delivery_type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grocery_sub_category`
--

CREATE TABLE `grocery_sub_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `isNew` int(11) NOT NULL,
  `isPopular` int(11) NOT NULL,
  `isVeg` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `file`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(3, 'english', 'english.json', '1580901280.png', 1, '2020-02-05 11:14:40', '2020-02-05 11:14:40'),
(4, 'arebic', 'arebic.json', '1580901435.png', 1, '2020-02-05 11:17:15', '2020-02-05 11:24:22');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) DEFAULT NULL,
  `popular` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(5, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(6, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(7, '2016_06_01_000004_create_oauth_clients_table', 2),
(8, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `notification_type` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification_template`
--

CREATE TABLE `notification_template` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `mail_content` text NOT NULL,
  `message_content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_template`
--

INSERT INTO `notification_template` (`id`, `title`, `subject`, `mail_content`, `message_content`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'User Verification', 'User Verification', 'Dear {{name}},<br>&nbsp; &nbsp;<br>&nbsp; &nbsp; Your registration is completed successfully.<br><br>&nbsp; &nbsp; Your Verification code is <b>{{otp}}</b>.<br><br>From {{shop_name}}', 'Dear {{name}}, Your Verification code is {{otp}}. From {{shop_name}}', '1574854450.png', '2019-11-27 11:34:10', '2019-11-27 13:13:54', '0000-00-00 00:00:00'),
(2, 'Forget Password', 'Forget Password', 'Dear {{name}},<br>&nbsp; &nbsp; &nbsp;&nbsp;<br>&nbsp; &nbsp; Your new passowrd is <b>{{password}}</b>.<br><br>From {{shop_name}}<br><br>', 'Dear {{name}},  Your new passowrd is {{password}}. From {{shop_name}}', '1574860457.jpg', '2019-11-27 11:42:00', '2019-11-27 13:14:17', '0000-00-00 00:00:00'),
(3, 'Create Order', 'Create Order', 'Dear {{name}},<br><br>&nbsp; &nbsp;Your Order is successfully created in {{shop}}.<br>&nbsp; &nbsp;<br>&nbsp; &nbsp;Thank you for using our application.<br><br>From {{shop_name}}', 'Dear {{name}}, Your Order is successfully created in {{shop}}. From {{shop_name}}', '1581055777.png', '2019-11-27 13:17:14', '2020-02-07 06:09:37', '0000-00-00 00:00:00'),
(4, 'Cancel Order', 'Cancel Order', 'Dear {{name}},<br><br>&nbsp; &nbsp;Your Order {{order_no}} on {{shop}} is Rejected by Restaurant.<br><br>From {{shop_name}}', 'Dear {{name}}, Your Order {{order_no}} on {{shop}} is Rejected by Restaurant. From {{shop_name}}', '1574861383.png', '2019-11-27 13:27:27', '2020-02-07 10:41:59', '0000-00-00 00:00:00'),
(6, 'Order Arrive', 'Order Arrive', 'Dear {{name}},<br>&nbsp; &nbsp; &nbsp;&nbsp;<br>&nbsp; &nbsp;You have new order {{order_no}} in {{shop}} from {{customer_name}}.<br><br>From {{shop_name}}', 'Dear {{name}}, You have new order {{order_no}} in {{shop}} from {{customer_name}}. From {{shop_name}}', '1574940643.png', '2019-11-28 11:30:43', '2019-12-24 07:42:41', '0000-00-00 00:00:00'),
(7, 'Order Status', 'Order Status', 'Dear {{name}},<br><br>&nbsp; &nbsp;Your Order {{order_no}} on {{shop}} is successfully {{status}}.<br><br>From {{shop_name}}', 'Dear {{name}}, Your Order {{order_no}} on {{shop}} is successfully {{status}}. From {{shop_name}}', '1577256227.jpeg', '2019-12-24 07:38:55', '2019-12-25 06:43:47', '0000-00-00 00:00:00'),
(8, 'Payment Status', 'Payment Status', 'Dear {{name}},<br><br>&nbsp; &nbsp;Your Payment for order {{order_no}} is successfully {{payment_status}}.<br><br>From {{shop_name}}', 'Dear {{name}}, Your Payment for order {{order_no}} is successfully {{payment_status}}. From {{shop_name}}', '1577267855.png', '2019-12-25 09:57:35', '2019-12-25 10:20:56', '0000-00-00 00:00:00'),
(9, 'Order Request', 'Order Request', 'Dear {{name}},<br><br>&nbsp; &nbsp; &nbsp; You have new request for order {{order_no}}&nbsp;at {{user_address}} by {{shop}}.<br><br>from {{shop_name}}<br><br>', 'Dear {{name}}, You have new request for order {{order_no}} at {{user_address}} by {{shop}}. from {{shop_name}}', '1579160492.png', '2020-01-16 07:41:32', '2020-01-16 07:45:37', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('03f3dbb05c1b1db61af78d209d69cbd3594299ba49c75a945e30e88e1113998647bf1180973b8fe6', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 21:52:17', '2021-03-18 21:52:17', '2022-03-19 08:52:17'),
('0a12efefeee715493bd8edcbc09d6fb5f5c43be850e44837c1c6df613d78d90cbfa0a8d63a609e30', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 01:56:46', '2021-03-17 01:56:46', '2022-03-17 12:56:46'),
('12b66d12c27249cf23366d98dc559d09f550e49ea04c13c4983415ff4f962c4f3ed3ca3c17c54066', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:57:21', '2021-03-17 19:57:21', '2022-03-18 06:57:21'),
('1ce8f575cae61cabaf21fdd9a72968ebb28e68b2edb18721a03076482a06ce0ea66667b5f3ee9fd6', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 01:45:36', '2021-03-18 01:45:36', '2022-03-18 12:45:36'),
('253de3b3023fd5ebb9782186774ee75172ba3223c12e7a3229dd002f32d10e4fada423a09fa7975a', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 19:53:48', '2021-03-18 19:53:48', '2022-03-19 06:53:48'),
('2bad3421587772e1b4c82eacbd09f82d0fbd12e014edfbdf81b697fe1986f33e5645f95b5e8ecaca', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:50:12', '2021-03-17 19:50:12', '2022-03-18 06:50:12'),
('30c0aa3cf582709c25e9ecc8dbbb177265f85449cbc19c46bac8cd199bf577b0fb9b2819905bc26a', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 00:54:10', '2021-03-17 00:54:10', '2022-03-17 11:54:10'),
('39796c0508fe27ef6f7e71f7dc94c1a926ef8c36700a4f797cc76ab8b27450422845307a82379b94', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:53:49', '2021-03-17 19:53:49', '2022-03-18 06:53:49'),
('40eff6df4be6d399aa5b30e223d295709c5cc0342e15aaaf5b701f89c8b85424d3524b577d0201d8', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 02:30:36', '2021-03-18 02:30:36', '2022-03-18 13:30:36'),
('47c6b7f2d6aecaf810fbed40f97a1270c85639b95b11b5bc279113665294fd829a9948e87572c339', 45, 1, 'Foodlans', '[]', 0, '2021-03-22 19:30:49', '2021-03-22 19:30:49', '2022-03-23 06:30:49'),
('4946a532252a121235c6dab9dd6ca2853b872aedc82089ce6a288ea43e1876ae3e53d7efb55c55b0', 45, 1, 'Foodlans', '[]', 0, '2021-03-22 19:30:17', '2021-03-22 19:30:17', '2022-03-23 06:30:17'),
('52bb2394b7c0f5fd9fdb580f9c8add56d3f2d267944861e4877b6060996fec8479ad8b0b959c58f0', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 23:46:08', '2021-03-17 23:46:08', '2022-03-18 10:46:08'),
('649b4b1e738b95cecaf64e471717eb950aaf474d7beb518db6814810e172cfe36cba5a8427a3b45e', 40, 1, 'Foodlans', '[]', 0, '2021-03-22 02:10:16', '2021-03-22 02:10:16', '2022-03-22 13:10:16'),
('6b26367cc5d8c1452c2974cb120d835d69f9b301052d0b8ee107a037870c3a9b4616ef132fd4f70d', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 20:01:59', '2021-03-18 20:01:59', '2022-03-19 07:01:59'),
('706d7c7a46e599c4d056b7dfe602428f575a7a483ff11abe83505ad48ac375a2073bc0a7a44aba68', 45, 1, 'Foodlans', '[]', 0, '2021-03-16 20:31:49', '2021-03-16 20:31:49', '2022-03-17 07:31:49'),
('733801d28737f4ccb01ce8bd2f45f68d132f1e99f97231eefc9739298e4a45b641cf648d681e83f8', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 19:42:49', '2021-03-18 19:42:49', '2022-03-19 06:42:49'),
('741ee9ee1fbd79d7f16285f7d09a0b0aefe86556c8e65c2092f63b3d5847cc87fdbb049526599e02', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:37:02', '2021-03-17 19:37:02', '2022-03-18 06:37:02'),
('751229e9e52880d5c6b3b6a371d2d8a6240edd00bbd441218fae033b860f2fb26bfd3c37621d916c', 40, 1, 'Foodlans', '[]', 0, '2021-03-22 18:16:04', '2021-03-22 18:16:04', '2022-03-23 05:16:04'),
('79da44b2e901a48acb7cc0d6ee4424fc320d58f69526b0e77e6f45d25e9a348e35acb2e30e560963', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:50:03', '2021-03-17 19:50:03', '2022-03-18 06:50:03'),
('7d29dbb39a4257cf57ec282bf84e10ba59a1bd93f9647c7e7cf95f1982c46e09ec58eca112df4ee8', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 20:02:59', '2021-03-18 20:02:59', '2022-03-19 07:02:59'),
('81746bc808ff3a0a60cc32638eccbe836a2f7b7338c7bd80c5e374ee818ab1520db761631c744971', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 20:09:41', '2021-03-17 20:09:41', '2022-03-18 07:09:41'),
('8f4fe99e7de959faee64b7a8859e3fce87fbf41cff28faed882c702528d0f5215c7ddc8ec0057cca', 40, 1, 'Foodlans', '[]', 0, '2021-03-21 19:10:41', '2021-03-21 19:10:41', '2022-03-22 06:10:41'),
('8f8e771be6f26a00579baa97836a54f69b998a2323cb5bbc73946a254c74eb6feda1f7180c6e09a3', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 20:05:54', '2021-03-17 20:05:54', '2022-03-18 07:05:54'),
('90749fa8889284429be9cd2121aad4e31f9687b8368a440029b86b89b7f6084a62ade947abc0df7c', 40, 1, 'Foodlans', '[]', 0, '2021-03-22 18:18:32', '2021-03-22 18:18:32', '2022-03-23 05:18:32'),
('a8c4d34b588c04cb9614bb28a8847c3662674a0843dfc45a45dbd3f5a8477df77951381538028fcc', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 19:54:06', '2021-03-17 19:54:06', '2022-03-18 06:54:06'),
('b979e90f2b8e648d394ae614a1d1b4b7daef0a97c6c5b94c7080e8e189b042681b316651821d6db0', 45, 1, 'Foodlans', '[]', 0, '2021-03-16 20:44:24', '2021-03-16 20:44:24', '2022-03-17 07:44:24'),
('c5aa9cf93396b3f850412b11f95dd8f56e6d35dd7f20b518d408b0135f5bc1d31afb5a9de42687ec', 40, 1, 'Foodlans', '[]', 0, '2021-03-22 02:10:39', '2021-03-22 02:10:39', '2022-03-22 13:10:39'),
('cdb5fe60b58e12284806d7b1f104d45edac24c1fa2096ca70bcb7b26518efe1c5782fcad97c3a459', 45, 1, 'Foodlans', '[]', 0, '2021-03-16 20:36:54', '2021-03-16 20:36:54', '2022-03-17 07:36:54'),
('d246f28056e0671589f0543ccf530d5fccac890ad9ef0f2827bd723d0e98fdfd34effbf815adf930', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 02:29:56', '2021-03-18 02:29:56', '2022-03-18 13:29:56'),
('d6a68789f1e2872c741abf8590895c87c798255b47ef87a08abaaf31adc7e175ec5e32a51ae42475', 46, 1, 'Foodlans', '[]', 0, '2021-03-22 16:58:23', '2021-03-22 16:58:23', '2022-03-23 03:58:23'),
('e3a5fbf010174b619c4e05c750b57546a471088a401894ecbfea54c187d78e83e352cc64af2eaba4', 40, 1, 'Foodlans', '[]', 0, '2021-03-22 02:08:28', '2021-03-22 02:08:28', '2022-03-22 13:08:28'),
('ed97b50d1849b58881ae21f475f81353eaec59502855cdc23bb8ac448609ba1ba8d500020e785d43', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 20:14:03', '2021-03-18 20:14:03', '2022-03-19 07:14:03'),
('f26c521dd4e71230ef77fe0dfe72e7d91646396b8ff41dbc0534eea34a2ef6377b950f5f1e7e1f73', 40, 1, 'Foodlans', '[]', 0, '2021-03-17 20:25:11', '2021-03-17 20:25:11', '2022-03-18 07:25:11'),
('f39ed3909ae5b5cdbfe6d4a17c3bb9b124f2903568cda42b711f481e46c2d35413a5d4f29d085126', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 02:31:02', '2021-03-18 02:31:02', '2022-03-18 13:31:02'),
('f3fd4a59c293f107f96cd2e4b1ae011223654fafdac28633773a50e5ecd111ee260e4681bd6d5bdd', 40, 1, 'Foodlans', '[]', 0, '2021-03-18 19:45:02', '2021-03-18 19:45:02', '2022-03-19 06:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'B89nQIIEsLiCebp7k1CuW0WpRQzAMDX64kA60gSW', 'http://localhost', 1, 0, 0, '2019-11-17 17:32:47', '2019-11-17 17:32:47'),
(2, NULL, 'Laravel Password Grant Client', '0xQbOjfOLo0R6YA8v86jnWdm2OVQArLHpWs5JlTr', 'http://localhost', 0, 1, 0, '2019-11-17 17:32:47', '2019-11-17 17:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-11-17 17:32:47', '2019-11-17 17:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `shop_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `deliveryBoy_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `items` varchar(255) DEFAULT NULL,
  `package_id` varchar(50) DEFAULT NULL,
  `payment` int(11) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `shop_charge` int(11) NOT NULL,
  `delivery_charge` int(11) NOT NULL,
  `coupon_price` int(11) DEFAULT 0,
  `discount` int(11) DEFAULT 0,
  `order_status` varchar(50) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `delivery_type` varchar(50) DEFAULT NULL,
  `driver_otp` varchar(50) DEFAULT NULL,
  `review_status` int(11) NOT NULL DEFAULT 0,
  `shopReview_status` int(11) NOT NULL DEFAULT 0,
  `driverReview_status` int(11) NOT NULL DEFAULT 0,
  `cancel_reason` varchar(255) DEFAULT NULL,
  `reject_by` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_child`
--

CREATE TABLE `order_child` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owner_setting`
--

CREATE TABLE `owner_setting` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `web_notification` int(11) DEFAULT 0,
  `play_sound` int(11) NOT NULL DEFAULT 0,
  `sound` varchar(255) DEFAULT NULL,
  `coupon` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `default_food_order_status` varchar(50) DEFAULT 'Pending',
  `default_grocery_order_status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `items` varchar(255) NOT NULL,
  `total_price` int(11) NOT NULL,
  `package_price` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_setting`
--

CREATE TABLE `payment_setting` (
  `id` int(11) NOT NULL,
  `cod` int(11) NOT NULL,
  `stripe` int(11) NOT NULL,
  `paypal` int(11) NOT NULL,
  `razor` int(11) NOT NULL,
  `paytabs` int(11) NOT NULL DEFAULT 0,
  `stripePublicKey` varchar(255) DEFAULT NULL,
  `stripeSecretKey` varchar(255) DEFAULT NULL,
  `paypalSendbox` varchar(255) DEFAULT NULL,
  `paypalProduction` varchar(255) DEFAULT NULL,
  `razorPublishKey` varchar(255) DEFAULT NULL,
  `razorSecretKey` varchar(255) DEFAULT NULL,
  `paytab_email` varchar(255) DEFAULT NULL,
  `paytab_secret_key` varchar(255) DEFAULT NULL,
  `flutterwave_public_key` text DEFAULT NULL,
  `paystack_public_key` text DEFAULT NULL,
  `paystack` tinyint(1) DEFAULT NULL,
  `flutterwave` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_setting`
--

INSERT INTO `payment_setting` (`id`, `cod`, `stripe`, `paypal`, `razor`, `paytabs`, `stripePublicKey`, `stripeSecretKey`, `paypalSendbox`, `paypalProduction`, `razorPublishKey`, `razorSecretKey`, `paytab_email`, `paytab_secret_key`, `flutterwave_public_key`, `paystack_public_key`, `paystack`, `flutterwave`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2019-11-15 00:00:00', '2021-02-17 12:17:20', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'order.show', 0, '2020-01-03 12:39:45', '2020-01-03 12:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `point_log`
--

CREATE TABLE `point_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `point` int(11) DEFAULT NULL,
  `redeem_point` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `point_setting`
--

CREATE TABLE `point_setting` (
  `id` int(11) NOT NULL,
  `enable_point` int(11) DEFAULT NULL,
  `value_per_point` int(11) DEFAULT NULL,
  `max_order_for_point` int(11) DEFAULT NULL,
  `min_cart_value_for_point` int(11) DEFAULT NULL,
  `max_redeem_amount` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `point_setting`
--

INSERT INTO `point_setting` (`id`, `enable_point`, `value_per_point`, `max_order_for_point`, `min_cart_value_for_point`, `max_redeem_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 5, 500, 400, '2020-06-15 00:00:00', '2020-06-16 10:39:09'),
(1, 1, 5, 5, 500, 400, '2020-06-15 00:00:00', '2020-06-16 10:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `deliveryBoy_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `rate` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'User', 0, '2020-01-03 12:21:22', '2020-01-03 12:21:22'),
(2, 'Shop Owner', 0, '2020-01-03 12:22:12', '2020-01-03 12:22:12'),
(3, 'Delivery Boy', 0, '2020-01-03 12:22:26', '2020-01-03 12:22:26'),
(4, 'Support Staff', 0, '2020-01-03 12:22:54', '2020-01-03 12:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `pincode` varchar(50) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) DEFAULT NULL,
  `licence_code` varchar(255) NOT NULL,
  `rastaurant_charge` int(11) NOT NULL,
  `avarage_plate_price` int(11) NOT NULL,
  `delivery_charge` int(11) NOT NULL,
  `cancle_charge` int(11) NOT NULL,
  `delivery_time` int(11) NOT NULL,
  `open_time` varchar(50) DEFAULT NULL,
  `close_time` varchar(50) DEFAULT NULL,
  `featured` int(11) NOT NULL,
  `exclusive` int(11) DEFAULT NULL,
  `veg` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateOfBirth` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'user.png',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favourite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `friend_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_user` int(11) NOT NULL DEFAULT 0,
  `free_order` int(11) NOT NULL DEFAULT 0,
  `verify` int(11) NOT NULL DEFAULT 0,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LOCAL',
  `provider_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(11) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_radius` int(11) DEFAULT NULL,
  `driver_available` int(11) DEFAULT NULL,
  `enable_notification` int(11) NOT NULL DEFAULT 0,
  `enable_location` int(11) NOT NULL DEFAULT 0,
  `enable_call` int(11) NOT NULL DEFAULT 0,
  `fcm_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` varchar(255) NOT NULL,
  `soc_name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `lang` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_gallery`
--

CREATE TABLE `user_gallery` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_point`
--

CREATE TABLE `user_point` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_point` int(11) NOT NULL,
  `use_point` int(11) NOT NULL,
  `total_spent` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notification`
--
ALTER TABLE `admin_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_setting`
--
ALTER TABLE `company_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_setting`
--
ALTER TABLE `general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grocery_category`
--
ALTER TABLE `grocery_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grocery_item`
--
ALTER TABLE `grocery_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`category_id`,`subcategory_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `grocery_order`
--
ALTER TABLE `grocery_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`,`customer_id`,`deliveryBoy_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `deliveryBoy_id` (`deliveryBoy_id`);

--
-- Indexes for table `grocery_order_child`
--
ALTER TABLE `grocery_order_child`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grocery_review`
--
ALTER TABLE `grocery_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grocery_shop`
--
ALTER TABLE `grocery_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `grocery_sub_category`
--
ALTER TABLE `grocery_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`,`shop_id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_template`
--
ALTER TABLE `notification_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`,`location_id`,`shop_id`,`customer_id`,`deliveryBoy_id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `deliveryBoy_id` (`deliveryBoy_id`);

--
-- Indexes for table `order_child`
--
ALTER TABLE `order_child`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owner_setting`
--
ALTER TABLE `owner_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_setting`
--
ALTER TABLE `payment_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_log`
--
ALTER TABLE `point_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`,`order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_gallery`
--
ALTER TABLE `user_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_point`
--
ALTER TABLE `user_point`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notification`
--
ALTER TABLE `admin_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `company_setting`
--
ALTER TABLE `company_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `general_setting`
--
ALTER TABLE `general_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `grocery_category`
--
ALTER TABLE `grocery_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grocery_item`
--
ALTER TABLE `grocery_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grocery_order`
--
ALTER TABLE `grocery_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grocery_order_child`
--
ALTER TABLE `grocery_order_child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `grocery_review`
--
ALTER TABLE `grocery_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grocery_shop`
--
ALTER TABLE `grocery_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grocery_sub_category`
--
ALTER TABLE `grocery_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notification_template`
--
ALTER TABLE `notification_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `order_child`
--
ALTER TABLE `order_child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `owner_setting`
--
ALTER TABLE `owner_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_setting`
--
ALTER TABLE `payment_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `point_log`
--
ALTER TABLE `point_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_gallery`
--
ALTER TABLE `user_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_point`
--
ALTER TABLE `user_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grocery_item`
--
ALTER TABLE `grocery_item`
  ADD CONSTRAINT `grocery_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `grocery_category` (`id`),
  ADD CONSTRAINT `grocery_item_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `grocery_sub_category` (`id`);

--
-- Constraints for table `grocery_order`
--
ALTER TABLE `grocery_order`
  ADD CONSTRAINT `grocery_order_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `grocery_shop` (`id`),
  ADD CONSTRAINT `grocery_order_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `grocery_order_ibfk_3` FOREIGN KEY (`deliveryBoy_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `grocery_shop`
--
ALTER TABLE `grocery_shop`
  ADD CONSTRAINT `grocery_shop_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `grocery_shop_ibfk_2` FOREIGN KEY (`location`) REFERENCES `location` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`deliveryBoy_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `shop`
--
ALTER TABLE `shop`
  ADD CONSTRAINT `shop_ibfk_1` FOREIGN KEY (`location`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `shop_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
