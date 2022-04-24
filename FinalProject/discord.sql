-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 21, 2022 at 04:50 PM
-- Server version: 8.0.28-0ubuntu0.21.10.3
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `discord`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_key`
--

CREATE TABLE `api_key` (
  `api_key` varchar(64) NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `creation_date` datetime NOT NULL,
  `requests` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatting`
--

CREATE TABLE `chatting` (
  `chat_id` int UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `is_group` tinyint(1) NOT NULL,
  `name` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

--
-- Dumping data for table `chatting`
--

INSERT INTO `chatting` (`chat_id`, `start_date`, `is_group`, `name`) VALUES
(170, '2022-04-19 15:17:09', 0, NULL),
(171, '2022-04-19 15:38:16', 0, NULL),
(172, '2022-04-19 15:38:30', 0, NULL),
(173, '2022-04-19 15:39:29', 0, NULL),
(174, '2022-04-19 15:39:30', 0, NULL),
(175, '2022-04-21 09:45:05', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chatting_client`
--

CREATE TABLE `chatting_client` (
  `chat_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

--
-- Dumping data for table `chatting_client`
--

INSERT INTO `chatting_client` (`chat_id`, `user_id`) VALUES
(170, 1),
(170, 2),
(171, 1),
(171, 54),
(172, 2),
(172, 54),
(173, 52),
(173, 54),
(174, 1),
(174, 55),
(175, 1),
(175, 44);

-- --------------------------------------------------------

--
-- Table structure for table `guild`
--

CREATE TABLE `guild` (
  `guild_id` int UNSIGNED NOT NULL,
  `ownership` int NOT NULL,
  `name` varchar(16) NOT NULL,
  `nationality` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int UNSIGNED NOT NULL,
  `context` text NOT NULL,
  `date` datetime NOT NULL,
  `chat_id` int UNSIGNED NOT NULL,
  `owner` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `context`, `date`, `chat_id`, `owner`) VALUES
(215, 'a', '2022-04-19 15:34:08', 170, 1),
(216, 'C\'hai la mamma che te lo giuro fa un fottio da mangiare, ma sei magro', '2022-04-19 15:39:10', 171, 54),
(217, 'anche te', '2022-04-19 15:39:23', 171, 1),
(218, 'Bella Guzuni', '2022-04-19 15:39:39', 173, 54),
(219, 'porco dio', '2022-04-19 15:40:14', 172, 54),
(220, 'muygddfg', '2022-04-19 15:41:07', 174, 55),
(221, 'loyfvuik', '2022-04-19 15:41:15', 174, 55),
(222, 'l', '2022-04-21 09:43:14', 170, 1),
(223, 'k', '2022-04-21 09:43:14', 170, 1),
(224, 'l', '2022-04-21 09:43:23', 170, 1),
(225, 'a', '2022-04-21 09:43:59', 170, 1),
(226, 's', '2022-04-21 09:43:59', 170, 1),
(227, 'd', '2022-04-21 09:44:00', 170, 1),
(228, 'f', '2022-04-21 09:44:00', 170, 1),
(229, 'sas', '2022-04-21 09:44:44', 170, 1),
(230, 'lol', '2022-04-21 09:44:46', 170, 1),
(231, 'aaa', '2022-04-21 09:45:09', 175, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `user_id` int UNSIGNED NOT NULL,
  `guild_id` int UNSIGNED NOT NULL,
  `permission_level` int NOT NULL,
  `name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` text NOT NULL,
  `username` varchar(16) NOT NULL,
  `image_format` text CHARACTER SET utf8 COLLATE utf8_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `username`, `image_format`) VALUES
(1, 'davide.tonno.dt@gmail.com', '$2y$10$jnuGpIml4iu3cqcbLNWeA.XyVGwWVb7MqLu7tXK613pPZjGeQXuse', 'tonno7103', 'jpg'),
(2, 'tonno.davide.td@gmail.com', '$2y$10$S5cxcIe90eYfee0yqtRs5.ZsjjkeB6yT.bURhM9DGlYFm6NaDzTq6', 'tonno2', NULL),
(39, 'davide.asd.asd@asdf.asd', '$2y$10$7oQTam7pcoZ9.Y4.tRHQzOtdJ1wyqTKHHNS1RlsUyrHiXmG6uRc26', 'usersus', NULL),
(40, 'davide.tonno.dt@toto.to', '$2y$10$XxRJNhL.TFyuNkXycECPSux3L6unKeFLT2B3iBPgLFcChaAx0sGAy', 'Qksks', NULL),
(41, 'ciao', '$2y$10$hd3oROnaVg6TtvigzAgRpeQUtKkXowsaFavoKhPOfbiVEVQ5Ecws2', 'sono', NULL),
(42, 'lamo@iclaa.caa', '$2y$10$.ZpMR.0P4i0CNzlf5juiKug.LLNDZz23bRFLAnf8S2D.3QWjXmm/m', 'dada', NULL),
(43, 'mail', '$2y$10$FAW3x2s4iXeF92n3bSwf5OWU91nTXlvLZCVtGxbCOJgV13Ixx/XxW', 'mail', NULL),
(44, 'sdfdkm@asd.asd', '$2y$10$5neegT8xV9YzrWZM4YAM1eRz2wto2fLbTlH/VHe4jqTgmTIaZSMUG', 'jimmy', NULL),
(45, 'davide.tonno.dqwsheyu@asd.asd', '$2y$10$WR14va5FhCfEhv3KRT.A0.0dDPKIS7IjJpha3aQp4ex5esKMfsdcq', 'tonno', NULL),
(46, 'koalaowo@gmail.owo', '$2y$10$BN7I6h6U6sgY4LiCCk8XFe4fLS.g4fcujWjPg5p63hzixkF7PBS0C', 'owo', NULL),
(48, 'asiodhu@asdphjkb.asd', '$2y$10$fw7OmqxpldIJgMakwoCpuuohjc1.b0urdXkgBuWsvANVbOrPr68W.', 'asdhjiof', NULL),
(49, 'asdxfjkhbksjbfsxijkf@asd.asdas', '$2y$10$fXx05AXlmWrNrq5gNosXXO5K6uDjmR86O0sm5wysOsXSp6N1q0hYC', 'sessoGigante', 'gif'),
(50, 'Piedini@gmail.com', '$2y$10$GJln5Mo0Sga6geHOULO2ruzizPTo8na1.tzP.eDIDgwEiifoL8sXS', 'Piedini', NULL),
(51, 'felipe.vasquez.lazzara@gmail.com', '$2y$10$ffLJIfb9r9t.jht9FsX2Ee2Kmt0tR37nW8vE0jbf2FyqPyFy2ReIi', 'awdawd', NULL),
(52, 'scemo@chi.legge', '$2y$10$TRJgcdDIm4k.xx8iW2mR8.bekQE.U8PG6AxlsRH5iYi6/RCC3dY5q', 'caccagay', NULL),
(53, 'ada@ada.ada', '$2y$10$SMUqGsziXTw5dJ0olgTDLuF0q1noNJ9hC8OCC23OnuMlPJptfBajG', 'ada', NULL),
(54, 'admin@gmail.com', '$2y$10$nnAwgqBH/msQpZA.tA3fiOahY0CkKdw3CSCHq8pk.qqGuNR8I1sgW', 'admin', 'jpg'),
(55, 'cecilia@hfghfd.com', '$2y$10$zoikqlD0ACTjzdh8g6/l.O7mBiRaTAxxYpVg9ieKVIPinX33/LOKq', 'loloòdfd', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voice_channel`
--

CREATE TABLE `voice_channel` (
  `voice_channel_id` int UNSIGNED NOT NULL,
  `name` varchar(8) NOT NULL,
  `user_limit` int NOT NULL,
  `guild_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_key`
--
ALTER TABLE `api_key`
  ADD PRIMARY KEY (`api_key`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chatting`
--
ALTER TABLE `chatting`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `chatting_client`
--
ALTER TABLE `chatting_client`
  ADD PRIMARY KEY (`user_id`,`chat_id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `guild`
--
ALTER TABLE `guild`
  ADD PRIMARY KEY (`guild_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`user_id`,`guild_id`),
  ADD KEY `role_guild_id_foreign` (`guild_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mail` (`email`);

--
-- Indexes for table `voice_channel`
--
ALTER TABLE `voice_channel`
  ADD PRIMARY KEY (`voice_channel_id`),
  ADD KEY `voice_channel_guild_id_foreign` (`guild_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatting`
--
ALTER TABLE `chatting`
  MODIFY `chat_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `guild`
--
ALTER TABLE `guild`
  MODIFY `guild_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `voice_channel`
--
ALTER TABLE `voice_channel`
  MODIFY `voice_channel_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_key`
--
ALTER TABLE `api_key`
  ADD CONSTRAINT `api_key_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `chatting_client`
--
ALTER TABLE `chatting_client`
  ADD CONSTRAINT `chatting_client_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `chatting_client_ibfk_3` FOREIGN KEY (`chat_id`) REFERENCES `chatting` (`chat_id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chatting` (`chat_id`);

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_guild_id_foreign` FOREIGN KEY (`guild_id`) REFERENCES `guild` (`guild_id`),
  ADD CONSTRAINT `role_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `voice_channel`
--
ALTER TABLE `voice_channel`
  ADD CONSTRAINT `voice_channel_guild_id_foreign` FOREIGN KEY (`guild_id`) REFERENCES `guild` (`guild_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
