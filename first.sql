-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 06 2018 г., 22:05
-- Версия сервера: 5.7.16
-- Версия PHP: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `first`
--

-- --------------------------------------------------------

--
-- Структура таблицы `private_mesages`
--

CREATE TABLE `private_mesages` (
  `id_message` int(11) NOT NULL,
  `id_sender` int(11) NOT NULL,
  `id_recip` int(11) NOT NULL,
  `send_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_body` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `private_mesages`
--

INSERT INTO `private_mesages` (`id_message`, `id_sender`, `id_recip`, `send_date`, `message_body`) VALUES
(1, 2, 1, '2018-02-04 21:14:25', 'Hi, 1\'st Admin'),
(2, 1, 2, '2018-02-04 21:25:06', 'Hello'),
(3, 8, 7, '2018-02-06 13:15:33', 'test message'),
(4, 46, 1, '2018-02-06 13:15:33', 'mr black'),
(5, 1, 1, '2018-02-06 16:22:48', 'cgvjbh'),
(6, 2, 46, '2018-02-06 16:24:01', 'ghj'),
(7, 8, 10, '2018-02-06 18:48:00', 'vbn'),
(8, 1, 48, '2018-02-06 18:54:48', 'test message 1'),
(9, 1, 48, '2018-02-06 18:55:20', 'test message 1');

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `value` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`id`, `id_type`, `value`) VALUES
(1, 7, 'admin'),
(2, 1, 'user'),
(3, 2, 'down');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type` smallint(4) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `time`, `id_type`, `banned`, `email`) VALUES
(1, 'qw', '9d6909cb0ddb28a6a370e2e897494284', '2017-10-12 14:12:49', 7, 1, 'bestbox95@gmail.com'),
(2, 'admin', 'c86c01f24074e0a4667e26afe2bb1025', '2017-10-12 15:43:56', 7, 1, 'admin@hot.loc'),
(7, 's', '35ca07a3f2a0565229a0cccb1233def7', '2017-10-15 13:19:27', 1, 1, 's'),
(8, 'w', 'd65b97e337d6a4af7266b6bf0c0a8135', '2017-10-15 13:19:42', 1, 1, 'w'),
(10, 'gg', '0e9c5652a14e134892f4926277239a5a', '2017-10-16 17:44:01', 1, 1, 'gg'),
(11, 'rev123', '66399ad62dc2416b0e09dac0d784ec88', '2017-10-19 14:44:27', 1, 1, 'wed@sds.fds'),
(46, 'xx', '21ef452c9219f60d1c310f9a2d32b6a0', '2017-11-25 18:38:56', 1, 1, 'bestbox95@gmail.com'),
(47, 'pp', 'bb60b1d41e5ec7f956a1c3d013bba468', '2017-12-08 17:29:04', 1, 1, 'bestbox95@gmail.com'),
(48, 'po', '42513e598d72b7391e36f8a647c2ccc1', '2017-12-08 17:33:38', 1, 1, 'bestbox95@gmail.com'),
(49, 'op', 'cb18e0211d95a91c8ab66b3c30139bcf', '2017-12-08 17:37:58', 1, 1, 'bestbox95@gmail.com'),
(50, 'oi', 'd06eac1d9258a281f893dd9cfc6019ad', '2017-12-08 17:42:59', 1, 1, 'bestbox95@gmail.com'),
(51, 'io', '3c6908555d8f1313e75ab842ab800fe2', '2017-12-08 17:44:09', 1, 1, 'bestbox95@gmail.com'),
(52, 'mo', '8c52b2631c484bcdc3ecef64c0133d39', '2017-12-08 17:59:44', 1, 1, 'bestbox95@gmail.com'),
(58, 'df', '098b6d3cc988a77b1c9619b9e393102c', '2017-12-14 15:38:49', 1, 1, 'ads'),
(59, 'as', '9d6909cb0ddb28a6a370e2e897494284', '2017-12-14 15:41:22', 1, 1, 'as'),
(60, 'cv', '393994ebd0b435c34f21b841151cd1f8', '2017-12-14 15:43:24', 1, 1, 'afd'),
(61, 'hj', '0bea95c8093118432e213d507bf291ef', '2017-12-14 15:44:38', 1, 1, 'hj'),
(62, 'uu', '2478606b697f48fb88afb5800b2323b9', '2017-12-14 15:46:16', 1, 1, 'uu');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `private_mesages`
--
ALTER TABLE `private_mesages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_sender` (`id_sender`),
  ADD KEY `id_recip` (`id_recip`);

--
-- Индексы таблицы `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `private_mesages`
--
ALTER TABLE `private_mesages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `private_mesages`
--
ALTER TABLE `private_mesages`
  ADD CONSTRAINT `private_mesages_ibfk_1` FOREIGN KEY (`id_sender`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `private_mesages_ibfk_2` FOREIGN KEY (`id_recip`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
