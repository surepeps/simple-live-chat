-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2022 at 01:46 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `surechatter`
--

-- --------------------------------------------------------

--
-- Table structure for table `sc_messages`
--

CREATE TABLE `sc_messages` (
  `msg_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sc_messages`
--

INSERT INTO `sc_messages` (`msg_id`, `from_id`, `to_id`, `message`, `status`, `date_created`) VALUES
(21, 19, 20, 'hello', 1, '2022-06-27 22:45:23'),
(22, 20, 19, 'how you doing???', 1, '2022-06-27 22:45:40'),
(23, 19, 20, 'hey', 1, '2022-06-27 22:47:45'),
(24, 20, 19, 'i am here', 1, '2022-06-27 22:47:58'),
(25, 19, 20, 'why is the design like that', 1, '2022-06-27 22:48:42'),
(26, 20, 19, 'i dont know', 1, '2022-06-27 22:49:18'),
(27, 20, 19, 'design@done', 1, '2022-06-27 23:01:33'),
(28, 20, 21, 'hi', 1, '2022-06-27 23:02:31'),
(29, 21, 20, 'how you doing', 1, '2022-06-27 23:02:47'),
(30, 20, 21, 'i am doing good and you', 1, '2022-06-27 23:03:04'),
(31, 19, 20, 'send to test time', 1, '2022-06-27 23:23:57'),
(32, 19, 21, 'hey', 1, '2022-06-27 23:25:57'),
(33, 21, 19, 'how you doing', 1, '2022-06-27 23:26:34'),
(34, 19, 20, 'hey', 1, '2022-06-27 23:36:11');

-- --------------------------------------------------------

--
-- Table structure for table `sc_typing`
--

CREATE TABLE `sc_typing` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sc_users`
--

CREATE TABLE `sc_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `avartar` text NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sc_users`
--

INSERT INTO `sc_users` (`user_id`, `username`, `email`, `first_name`, `last_name`, `password`, `avartar`, `active`, `date_created`, `email_code`) VALUES
(19, 'user_97366', 'gatukurh1@gmail.com', 'Hassan', 'Tijani', '$2y$10$Or4LlDzA5Af/VZtE7tKmg.x8nGYUmFb4TTKglkbETwyWASE9iFSAa', 'upload/photos/2022/06/1IFpBBL5UoTXgINSx28r_25_8abf21144dfa45be3b38a098ab9d6337_image.jpg', 1, '2022-06-25 19:55:24', '867bc95878ddc014e2eafab0081ad4b3'),
(20, 'user_4684', 'gatukurh0@gmail.com', 'Surecoder', 'Gat', '$2y$10$tnTicBur6uJhaAGDLX7t9.7S11nYshE299tnE9mCS7qA.zEedqyaa', 'upload/photos/2022/06/EY5pErecBLLDswGfbBTQ_25_024b391d72aad16b487d6b994db640d9_image.jpg', 1, '2022-06-27 22:39:10', 'fc393ee7c96873c3badc2f6f50e02a6e'),
(21, 'user_317212', 'fatimah@gmail.com', 'Fatimah', 'john', '$2y$10$EPXWyY4fO5RLDA8aoEr7/.XZx/rtK2PhBoCKG.3iCCjvGXxXNW2BW', 'upload/photos/2022/06/vjMKR34VTlB1vZfcLu9E_27_78bef7eb22a4a5f99fd6639a455a880b_image.jpg', 1, '2022-06-27 12:24:15', '8afd7d5fca542dd74918368245a52719');

-- --------------------------------------------------------

--
-- Table structure for table `sh_appssessions`
--

CREATE TABLE `sh_appssessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `platform` varchar(32) NOT NULL,
  `platform_details` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sh_appssessions`
--

INSERT INTO `sh_appssessions` (`id`, `user_id`, `session_id`, `platform`, `platform_details`, `time`) VALUES
(28, 20, 'caab9c99360a082cf4579cddcafc1e9564511e18ebf0c99b10d1ddf77e7ac66f09345d6a5048962427d52bcb3580724eb4cbe9f2718a9365', 'web', '{\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/102.0.5005.87 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"604.1\",\"platform\":\"mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\",\"ip_address\":\"::1\"}', 1656370460),
(30, 19, 'd3ca74d1c08accd08f63bae06d7a8942777ff92f280891f1339fdcab632222dd00deb099461103602ea19e760aeeeeeb813a2406d0d31a25', 'web', '{\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"102.0.0.0\",\"platform\":\"windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\",\"ip_address\":\"::1\"}', 1656370862),
(31, 21, '61ee2a81b1e04f81019d5caaacc314a91a0b61beb5d39411285296bb41f4a71f2bafa80b1820463105e97c207235d63ceb1db43c60db7bbb', 'web', '{\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"102.0.0.0\",\"platform\":\"windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\",\"ip_address\":\"::1\"}', 1656370922);

-- --------------------------------------------------------

--
-- Table structure for table `sh_config`
--

CREATE TABLE `sh_config` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sh_config`
--

INSERT INTO `sh_config` (`id`, `name`, `value`) VALUES
(1, 'english', '1'),
(2, 'arabic', '1'),
(3, 'dutch', '1'),
(4, 'french', '1'),
(5, 'german', '1'),
(6, 'italian', '1'),
(7, 'portuguese', '1'),
(8, 'russian', '1'),
(9, 'spanish', '1'),
(10, 'turkish', '1'),
(11, 'siteName', ''),
(12, 'siteTitle', 'SureChatter'),
(13, 'siteKeywords', ''),
(14, 'siteDesc', ''),
(15, 'siteEmail', ''),
(16, 'defualtLang', 'english'),
(17, 'emailValidation', '1'),
(19, 'site_url', 'https://12ab-197-211-61-36.in.ngrok.io/surechatter'),
(20, 'prevent_system', '0'),
(21, 'bad_login_limit', '23'),
(22, 'lock_time', '23'),
(23, 'hash_id', 'a20f01a742a1c4e4a9a79543bcd5388bd2244696'),
(24, 'user_registration', '1'),
(25, 'user_limit', '120'),
(26, 'login_auth', '1'),
(30, 'two_factor_type', 'email'),
(32, 'maintenance_mode', '1'),
(33, 'useSeoFrindly', '1'),
(34, 'developers_page', '1'),
(35, 'profile_privacy', '1'),
(36, 'smtp_or_mail', 'mail'),
(37, 'smtp_host', ''),
(38, 'smtp_username', ''),
(39, 'smtp_password', ''),
(40, 'smtp_port', ''),
(41, 'login_type_system', '1'),
(42, 'register_type_system', '1'),
(43, 'password_complexity_system', '0'),
(44, 'login_auth', '1'),
(45, 'smooth_loading', '0'),
(46, 'user_must_login_activities', '1'),
(47, 'user_rating_system', '1'),
(48, 'fileSharing', '1'),
(49, 'allowedExtenstion', 'jpg,png,jpeg,gif,mkv,docx,zip,rar,pdf,doc,docx,xls,xlsx,pptx,csv,mp3,mp4,flv,wav,txt,mov,avi,webm,wav,mpeg,ppt'),
(50, 'maxUpload', '96000000'),
(51, 'mime_types', 'text/plain,video/mp4,video/mov,video/mpeg,video/flv,video/avi,video/webm,audio/wav,audio/mpeg,video/quicktime,audio/mp3,image/png,image/jpeg,image/gif,application/pdf,application/msword,application/zip,application/x-rar-compressed,text/pdf,application/x-pointplus,text/css,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,\r\n'),
(52, 'site_logo', 'upload/photos/2022/05/HNknTS8JjqaFx9g61VYz_17_64ddf54019c7ed3264b8662267557fa3_image.png'),
(53, 'category_title', 'Popular Categories 2'),
(54, 'category_description', 'Cum doctus civibus efficiantur in imperdiet deterruisset'),
(55, 'expert_description', 'Cum doctus civibus efficiantur in imperdiet deterruisset.'),
(56, 'expert_title', 'Popular Professionals '),
(57, 'home_banner_title', 'Find a Professional'),
(58, 'home_banner_description', 'Book a Consultation by Appointment, Chat or Video call'),
(59, 'homepage_search_input', '1'),
(60, 'site_footer', '© 2022 Sourcher'),
(61, 'banner_image', 'upload/photos/2022/05/LmcSKCRoMEgA8dMiqmpR_17_1109785fe6a6d7279e73389c6782c9b7_image.jpg'),
(62, 'automatic_approve_reviews', '0'),
(63, 'primary_color', '#000000'),
(64, 'secondary_color', '#ff3a65'),
(65, 'text_color', '#222222'),
(66, 'button_primary_color', '#1f2f6a'),
(67, 'button_secondary_color', '#ff3a65'),
(68, 'work_link', 'https://www.youtube.com/embed/b4CYurJKmdE'),
(69, 'work_title', 'How does it works?'),
(70, 'work_description', 'Cum doctus civibus efficiantur in imperdiet deterruisset.'),
(71, 'work_step_1_title', 'Search for a Expert'),
(72, 'work_step_1_desc', 'Search over 12.000 verifyed professionals that match your criteria.'),
(73, 'work_step_2_title', 'View Expert Profile'),
(74, 'work_step_2_desc', 'View professional introduction and read reviews from other customers.'),
(75, 'work_step_3_title', 'Enjoy the Consultation'),
(76, 'work_step_3_desc', 'Connect with your professional booking an appointment, via chat or video call!'),
(77, 'partner1_image', 'upload/photos/2022/05/qw7Y49C6plyJW3bWmEbe_27_3b524af567119d9e663bf9f43afb83f2_image.jpg'),
(78, 'partner2_image', 'upload/photos/2022/05/kACZx9SeGH8QjHPpcMtV_27_9cc0bd02ccc38d9ebd182441b46f78fe_image.jpg'),
(79, 'contact_banner', 'upload/photos/2022/05/JDIpTj2Q4etu5SPNta2s_27_f3c77f279bff2a738c279b4be05e9f54_image.png'),
(80, 'page_banner', 'upload/photos/2022/05/T3gKX5eQ14BWkNanysBi_27_ad35f81056f5aea9426882498cfd9853_image.png');

-- --------------------------------------------------------

--
-- Table structure for table `texster`
--

CREATE TABLE `texster` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avartar` text NOT NULL,
  `email_code` text NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `texster`
--

INSERT INTO `texster` (`user_id`, `username`, `first_name`, `last_name`, `email`, `password`, `avartar`, `email_code`, `active`, `date_created`) VALUES
(1, 'jhjkl', '', '', '', '', '', '', 1, '2022-06-25 14:47:58'),
(2, 'jhjkl', 'iuyghj', 'uytgfghj', '', '', '', '', 1, '2022-06-25 14:47:58'),
(3, 'jhjkl', 'iuyghj', 'uytgfghj', 'hhagd@hhd.dcf', 'ghj', '', '', 1, '2022-06-25 14:47:58'),
(4, 'jhjkl', 'iuyghj', 'uytgfghj', 'hhagd@hhd.dcf', 'ghj', '', 'hgfgvhjk', 1, '2022-06-25 14:47:58'),
(5, 'jhjkl', 'iuyghj', 'uytgfghj', 'hhagd@hhd.dcf', 'ghj', '', 'hgfgvhjk', 1, '2022-06-25 14:47:58'),
(6, 'jhjkl', 'iuyghj', 'uytgfghj', 'hhagd@hhd.dcf', 'ghj', '', 'hgfgvhjk', 1, '2022-06-25 14:47:59'),
(7, 'jhjkl', 'iuyghj', 'uytgfghj', 'hhagd@hhd.dcf', 'ghj', '', 'hgfgvhjk', 1, '2022-06-25 14:48:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sc_messages`
--
ALTER TABLE `sc_messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `sc_typing`
--
ALTER TABLE `sc_typing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sc_users`
--
ALTER TABLE `sc_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `sh_appssessions`
--
ALTER TABLE `sh_appssessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sh_config`
--
ALTER TABLE `sh_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `texster`
--
ALTER TABLE `texster`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sc_messages`
--
ALTER TABLE `sc_messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `sc_typing`
--
ALTER TABLE `sc_typing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `sc_users`
--
ALTER TABLE `sc_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sh_appssessions`
--
ALTER TABLE `sh_appssessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sh_config`
--
ALTER TABLE `sh_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `texster`
--
ALTER TABLE `texster`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
