-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Час створення: Гру 10 2016 р., 15:34
-- Версія сервера: 10.1.19-MariaDB
-- Версія PHP: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `my_manager`
--

-- --------------------------------------------------------

--
-- Структура таблиці `address`
--

CREATE TABLE `address` (
  `address_id` int(10) NOT NULL,
  `contact_id` int(10) NOT NULL,
  `type` enum('1','2') CHARACTER SET utf8 NOT NULL,
  `address` varchar(48) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `address`
--

INSERT INTO `address` (`address_id`, `contact_id`, `type`, `address`) VALUES
(45, 1, '1', 'Danyla'),
(46, 1, '2', 'lafata'),
(99, 62, '1', 'Danyla Galyckogo 43'),
(100, 62, '2', 'asdfasdf asdfasdf'),
(149, 97, '1', NULL),
(150, 97, '2', NULL),
(177, 111, '1', 'asdf 21'),
(178, 111, '2', 'asfda 23'),
(219, 133, '1', 'Danyla Galyckogo 43'),
(220, 133, '2', 'asdfasdf asdfasdf'),
(243, 147, '1', NULL),
(244, 147, '2', NULL),
(247, 149, '1', NULL),
(248, 149, '2', NULL),
(249, 150, '1', 'asdf 3'),
(250, 150, '2', 'asfda 3'),
(267, 165, '1', 'asdf 2'),
(268, 165, '2', 'asdf 2'),
(315, 191, '1', 'Danyla 5'),
(316, 191, '2', 'hands 3'),
(341, 204, '1', 'Danyla 5'),
(342, 204, '2', 'hands 3'),
(349, 208, '1', 'Danyla Galyckogo 43'),
(350, 208, '2', 'handsasdf 23'),
(351, 209, '1', 'Danyla 43'),
(352, 209, '2', 'handsasdf 23'),
(407, 237, '1', NULL),
(408, 237, '2', NULL),
(421, 244, '1', NULL),
(422, 244, '2', NULL),
(425, 246, '1', NULL),
(426, 246, '2', NULL),
(427, 247, '1', NULL),
(428, 247, '2', NULL),
(429, 248, '1', NULL),
(430, 248, '2', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `address_type`
--

CREATE TABLE `address_type` (
  `address_type_id` enum('1','2') CHARACTER SET utf8 NOT NULL,
  `address_type` char(8) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `address_type`
--

INSERT INTO `address_type` (`address_type_id`, `address_type`) VALUES
('1', 'address1'),
('2', 'address2');

-- --------------------------------------------------------

--
-- Структура таблиці `city`
--

CREATE TABLE `city` (
  `city_id` int(10) NOT NULL,
  `zip` int(10) DEFAULT NULL,
  `city` varchar(15) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `city`
--

INSERT INTO `city` (`city_id`, `zip`, `city`) VALUES
(1, NULL, NULL),
(2, 123, 'Luka'),
(3, 1233, 'Myshd'),
(4, 2343, 'Mysh'),
(5, 12345, 'Chigaco'),
(6, 23451, 'Ankara'),
(7, 47732, 'Ternopil'),
(8, 64564, 'Warshawa'),
(9, 123123, 'asdf'),
(10, 234513, 'Ankara'),
(11, 645646, 'Warshawa'),
(12, 65776783, 'Luka'),
(13, 1111, 'Lublin'),
(14, 323, 'Warshawa'),
(15, 234234234, 'Reus'),
(16, 23423546, 'Reus'),
(18, 123456, 'Salou'),
(19, 111333, 'Tarragona'),
(20, 55555, 'London'),
(21, 555556, 'NewYork'),
(22, 5555565, 'Lviv'),
(23, 7788, 'Warshawa');

-- --------------------------------------------------------

--
-- Структура таблиці `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `last` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(64) CHARACTER SET utf8 NOT NULL,
  `birthday` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `contact`
--

INSERT INTO `contact` (`contact_id`, `user_id`, `first`, `last`, `email`, `birthday`) VALUES
(1, 12, 'Misha', 'Xymchujk', '', '1985-08-05'),
(62, 11, 'Koshakus', 'Asdf', 'koshacool@xaker.ru', '1986-10-16'),
(97, 11, '', '', 'taras@gmail.com', '0000-00-00'),
(111, 11, 'Nastya', 'Rosiak', 'rosiak@gmail.com', '1952-12-31'),
(133, 11, 'Roman', 'Kushytskyy', 'koshacool@xaker.com', '1963-10-17'),
(147, 11, '', '', 'test11212@iu.ua', '0000-00-00'),
(149, 11, '', '', 'test123@iu.ua', '0000-00-00'),
(150, 11, 'Misha', 'Rosiak', 'asdf1@asdf.ua', '1949-10-01'),
(165, 11, 'Roman', 'Fedyshyn', 'fed@is.ua', '1965-12-17'),
(191, 11, 'Olya', 'Fedysshyn', 'olya_fedyshyn@gmail.com', '1988-08-05'),
(204, 11, 'Sdfg', 'Asdf', 'test5@i.ua', '1995-11-18'),
(208, 11, 'New', 'New', 'kossdfhacool@xaker.ru', '1979-11-18'),
(209, 11, 'Bodya', 'Kindzerskyi', 'bodya@xaker.ru', '1976-12-18'),
(237, 11, NULL, NULL, 'gjhg@i.ua', NULL),
(244, 11, NULL, NULL, 'test3@i.ua', NULL),
(246, 11, '', '', 'test1@is.ua', '0000-00-00'),
(247, 11, '', '', 'testsd@i.ua', '0000-00-00'),
(248, 11, '', '', 'test1@i.ua', '0000-00-00');

-- --------------------------------------------------------

--
-- Структура таблиці `country`
--

CREATE TABLE `country` (
  `country_id` int(10) NOT NULL,
  `country` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `country`
--

INSERT INTO `country` (`country_id`, `country`) VALUES
(25, NULL),
(26, 'China'),
(28, 'England'),
(29, 'Franch'),
(9, 'Germany'),
(8, 'Italy'),
(27, 'Japan'),
(30, 'Mexica'),
(16, 'Poland'),
(6, 'Spain'),
(2, 'Ukraine'),
(7, 'USA');

-- --------------------------------------------------------

--
-- Структура таблиці `location`
--

CREATE TABLE `location` (
  `location_id` int(10) NOT NULL,
  `contact_id` int(10) NOT NULL,
  `city_id` int(10) NOT NULL,
  `state_id` int(10) NOT NULL,
  `country_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `location`
--

INSERT INTO `location` (`location_id`, `contact_id`, `city_id`, `state_id`, `country_id`) VALUES
(21, 1, 1, 9, 8),
(50, 62, 8, 10, 16),
(77, 97, 1, 19, 25),
(91, 111, 10, 14, 7),
(112, 133, 11, 10, 9),
(124, 147, 1, 19, 25),
(126, 149, 1, 19, 25),
(127, 150, 6, 7, 7),
(136, 165, 6, 1, 2),
(160, 191, 15, 1, 2),
(173, 204, 6, 12, 30),
(177, 208, 6, 12, 2),
(178, 209, 8, 1, 2),
(206, 237, 1, 19, 25),
(213, 244, 1, 19, 25),
(215, 246, 1, 19, 25),
(216, 247, 1, 19, 25),
(217, 248, 1, 19, 25);

-- --------------------------------------------------------

--
-- Структура таблиці `phone`
--

CREATE TABLE `phone` (
  `phone_id` int(10) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `type_id` enum('1','2','3') CHARACTER SET utf8 NOT NULL,
  `best_phone` enum('0','1') CHARACTER SET utf8 NOT NULL,
  `phone` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `phone`
--

INSERT INTO `phone` (`phone_id`, `contact_id`, `type_id`, `best_phone`, `phone`) VALUES
(49, 1, '1', '0', 12345),
(50, 1, '2', '0', 222),
(51, 1, '3', '1', 222),
(130, 62, '1', '1', 222),
(131, 62, '2', '0', 123),
(132, 62, '3', '0', 123),
(172, 97, '1', '0', NULL),
(173, 97, '2', '0', NULL),
(174, 97, '3', '1', NULL),
(214, 111, '1', '1', 123),
(215, 111, '2', '0', 123),
(216, 111, '3', '0', 123),
(277, 133, '1', '1', 968255635),
(278, 133, '2', '0', 123),
(279, 133, '3', '0', 123),
(313, 147, '1', '0', NULL),
(314, 147, '2', '0', NULL),
(315, 147, '3', '1', NULL),
(319, 149, '1', '0', NULL),
(320, 149, '2', '0', NULL),
(321, 149, '3', '1', NULL),
(322, 150, '1', '0', 123),
(323, 150, '2', '0', 123),
(324, 150, '3', '1', 123),
(349, 165, '1', '0', 234),
(350, 165, '2', '0', 234),
(351, 165, '3', '1', 234),
(421, 191, '1', '0', 12345),
(422, 191, '2', '0', 3245),
(423, 191, '3', '1', 111),
(460, 204, '1', '0', 12345),
(461, 204, '2', '1', 3245),
(462, 204, '3', '0', 123234234),
(472, 208, '1', '1', 123),
(473, 208, '2', '0', 123),
(474, 208, '3', '0', 123),
(475, 209, '1', '0', 123),
(476, 209, '2', '0', 123),
(477, 209, '3', '1', 123),
(559, 237, '1', '0', NULL),
(560, 237, '2', '0', NULL),
(561, 237, '3', '1', NULL),
(580, 244, '1', '0', NULL),
(581, 244, '2', '0', NULL),
(582, 244, '3', '1', NULL),
(586, 246, '1', '0', NULL),
(587, 246, '2', '0', NULL),
(588, 246, '3', '1', NULL),
(589, 247, '1', '0', NULL),
(590, 247, '2', '0', NULL),
(591, 247, '3', '1', NULL),
(592, 248, '1', '0', NULL),
(593, 248, '2', '0', NULL),
(594, 248, '3', '1', NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `phone_type`
--

CREATE TABLE `phone_type` (
  `type_id` enum('1','2','3') CHARACTER SET utf8 NOT NULL,
  `type` char(4) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `phone_type`
--

INSERT INTO `phone_type` (`type_id`, `type`) VALUES
('1', 'home'),
('2', 'work'),
('3', 'cell');

-- --------------------------------------------------------

--
-- Структура таблиці `state`
--

CREATE TABLE `state` (
  `state_id` int(10) NOT NULL,
  `state` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `state`
--

INSERT INTO `state` (`state_id`, `state`) VALUES
(1, 'Ternopil'),
(7, 'Ter'),
(8, 'Luka'),
(9, 'sdf'),
(10, 'Barcelona'),
(11, 'Luka1'),
(12, 'Asadfa'),
(13, 'Asadfas'),
(14, 'Polskyi'),
(19, NULL),
(20, 'Asadfas');

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `Login` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`user_id`, `Login`, `Password`) VALUES
(11, 'kosha', '$2y$10$SZ4SYUEgEXaDnZ8hypdMKe9h7n1rcRKkrom1xCSuBcUAx6k1ycXhS'),
(12, 's', '$2y$10$o3ENT9xrYgP6z8NaGljcEOFHCF0VctCnUoWwRKsNMj/4djV8CXbuW'),
(21, 'Andriy', '$2y$10$gXeznAutU/glupn3hXxgJOkWfQoaUjE2oFbwP0xH8KeRHqfgJV3au'),
(23, 'oleg1', '$2y$10$IsXECeZnxrY3c/YDnb5jIOBiOcVmQsz2pzkzPq85c539JwFp50W1a'),
(25, 'olichka', '$2y$10$3n6lDiYQa5Zgh97hkHHt8Odei.yFnqJrnSWjr5vENFB3cfp6EEfXO'),
(40, 'test', '$2y$10$SZ4SYUEgEXaDnZ8hypdMKeOwBgH4bXaiw/l5JnDFs40uj71VnU/xi');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `type` (`type`);

--
-- Індекси таблиці `address_type`
--
ALTER TABLE `address_type`
  ADD PRIMARY KEY (`address_type_id`);

--
-- Індекси таблиці `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `zip` (`zip`);

--
-- Індекси таблиці `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country` (`country`);

--
-- Індекси таблиці `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `zip` (`city_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `location_ibfk_1` (`contact_id`);

--
-- Індекси таблиці `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`phone_id`),
  ADD KEY `phones_ibfk_1` (`contact_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Індекси таблиці `phone_type`
--
ALTER TABLE `phone_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Індекси таблиці `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;
--
-- AUTO_INCREMENT для таблиці `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблиці `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;
--
-- AUTO_INCREMENT для таблиці `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT для таблиці `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;
--
-- AUTO_INCREMENT для таблиці `phone`
--
ALTER TABLE `phone`
  MODIFY `phone_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=595;
--
-- AUTO_INCREMENT для таблиці `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`type`) REFERENCES `address_type` (`address_type_id`);

--
-- Обмеження зовнішнього ключа таблиці `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Обмеження зовнішнього ключа таблиці `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `location_ibfk_3` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`),
  ADD CONSTRAINT `location_ibfk_4` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`),
  ADD CONSTRAINT `location_ibfk_5` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`);

--
-- Обмеження зовнішнього ключа таблиці `phone`
--
ALTER TABLE `phone`
  ADD CONSTRAINT `phone_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `phone_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `phone_type` (`type_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
