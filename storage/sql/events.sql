-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 27 2021 г., 17:21
-- Версия сервера: 5.7.21-20-beget-5.7.21-20-1-log
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `o937876j_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--
-- Создание: Окт 25 2021 г., 17:51
-- Последнее обновление: Окт 27 2021 г., 13:57
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `type`, `created_at`, `updated_at`, `user_id`) VALUES
(4, 'Показания счетчиков', '2021-11-24', 'monthly', '2021-09-13 01:50:08', '2021-10-24 04:58:41', 4),
(5, 'Квартплата', '2021-11-15', 'monthly', '2021-09-13 01:50:57', '2021-10-17 04:23:40', 4),
(9, 'Заплатить налоги  Проверить', '2021-10-28', 'annual', '2021-09-14 02:37:11', '2021-10-27 10:57:42', 4),
(12, 'Оплатить кредит ВТБ', '2021-11-08', 'monthly', '2021-09-14 12:59:54', '2021-10-09 15:08:33', 4),
(13, 'Оплатить ДОМ.РУ интернет', '2021-10-28', 'monthly', '2021-09-14 13:00:59', '2021-10-09 15:08:09', 4),
(14, 'Cтрахование жизни (ИПОТЕКА)', '2022-01-15', 'annual', '2021-09-14 13:02:04', '2021-09-14 13:10:36', 4),
(15, 'День рождения Пляц', '2022-02-01', 'annual', '2021-09-14 13:02:41', '2021-09-14 13:02:41', 4),
(16, 'День рождения Шаронов Владимир', '2022-03-21', 'annual', '2021-09-14 13:03:26', '2021-09-14 13:03:26', 4),
(17, 'День рождения Мамы', '2022-04-29', 'annual', '2021-09-14 13:04:33', '2021-09-14 13:04:33', 4),
(18, 'День рождения Артём Гелунг', '2022-05-14', 'annual', '2021-09-14 13:05:43', '2021-09-14 13:05:43', 4),
(19, 'ОСАГО Королла 649', '2022-06-06', 'annual', '2021-09-14 13:06:33', '2021-09-14 13:06:33', 4),
(20, 'Страхование квартиры (ИПОТЕКА)', '2022-08-22', 'annual', '2021-09-14 13:07:29', '2021-09-14 13:07:29', 4),
(21, 'День рождения Оксана Гелунг', '2022-06-30', 'annual', '2021-09-14 13:15:19', '2021-09-14 13:15:19', 4),
(49, 'Сделать фотографию на загранпаспорт', '2021-10-29', 'unique', '2021-10-21 15:32:50', '2021-10-27 10:56:01', 4),
(52, 'my event', '2021-10-26', 'unique', '2021-10-26 06:04:47', '2021-10-26 06:04:47', 5);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
