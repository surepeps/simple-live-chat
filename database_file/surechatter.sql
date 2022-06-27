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
(19, 'site_url', 'http://localhost/surechatter'),
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


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
