-- phpMyAdmin SQL Dump
-- version 4.7.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 07 2018 г., 21:21
-- Версия сервера: 10.1.29-MariaDB
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Tr_demo_site`
--

-- --------------------------------------------------------

--
-- Структура таблицы `menuPageSite`
--

CREATE TABLE `menuPageSite` (
  `id` int(11) NOT NULL,
  `img_file` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_page` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='menu site';

--
-- Дамп данных таблицы `menuPageSite`
--

INSERT INTO `menuPageSite` (`id`, `img_file`, `description`, `link_page`) VALUES
(1, '7.jpg', 'example', '/');

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` tinyint(4) NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='роли пользователей';

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(3, 'test'),
(1, 'администратор'),
(2, 'пользователь');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `sid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_last` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='сесии пользователей';

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `id_user`, `sid`, `time_start`, `time_last`) VALUES
(16, 1, 'y1it1BCsfqeVd7Re', '2018-02-07 20:53:35', '2018-02-07 21:04:55'),
(17, 2, 'vnrSXsDKnVdi4cxO', '2018-02-07 21:05:09', '2018-02-07 21:08:37'),
(18, 2, 'wyvGpuEnrCnYgQ8S', '2018-02-07 21:08:48', '2018-02-07 21:10:17'),
(19, 2, 'dbiEkQMpBtCzqlKE', '2018-02-07 21:10:28', '2018-02-07 21:10:28'),
(20, 2, 'q2OxjkSNiC9uKhuJ', '2018-02-07 21:10:29', '2018-02-07 21:10:37'),
(21, 2, 'oz8JHvnIY6tfuPQ2', '2018-02-07 21:10:53', '2018-02-07 21:11:35'),
(22, 1, '9arAUIgroRxBYhpB', '2018-02-07 21:11:44', '2018-02-07 21:11:48'),
(23, 2, 'ISihmqgKXHSv0Ame', '2018-02-07 21:12:00', '2018-02-07 21:12:00'),
(24, 2, 'RVKSRZ3uMn3AFhYI', '2018-02-07 21:12:00', '2018-02-07 21:15:24'),
(25, 1, 'Vz4zbCgVDqaiLgvD', '2018-02-07 21:15:32', '2018-02-07 21:15:42');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '2',
  `foto_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='пользователи';

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `email`, `surname`, `first_name`, `middle_name`, `role`, `foto_user`) VALUES
(1, 'admin', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'admin@admin.ru', 'Админов', 'Админ', 'Админович', 1, 'user.png'),
(2, 'user1', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'user@user.ru', 'Юзверь', 'Юзер', 'Юзерович', 3, 'user.png'),
(7, 'testPro', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'testPro@testPro.ru', 'testPro', 'testPro', 'testPro', 3, 'user.png'),
(8, 'test2', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'test@test.ru', 'Тестов', 'Тест', 'Тестович', 2, 'user.png'),
(9, 'admin2', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'admin2@admin2.ru', 'Админов2', 'Админ2', 'Админович2', 1, 'user.png'),
(11, 'UserTest', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'user@user.ru', 'Петров', 'Петр', 'Петрович', 2, 'user.png'),
(12, 'TEST123', '$2y$10$imSEtgKbg.xpkev98JhG.ei6U1jZjSmvbqcy4Qu31.GLdhhlqeZXO', 'testPro@testPro.ru', 'Тестовкий', 'Тест', 'Тестович', 2, 'user.png'),
(14, 'ewrqwerewqrewq', '$2y$10$XatY0/v1IqMgshWfAIru9.x5gdZj9pu.9/cK4nESNQCUR4RWSo0g6', 'werwer@tgertewqrt', 'ewtewtewt', 'ewqtetew', 'twetewt', 2, 'user.png'),
(15, 'vcdvavv', '$2y$10$8LCqM5wIr026ESrR0rkbNuxOWFItUDTt6ooMpMX8vK7lYPSkPs0wC', 'vdsvsd@gverabrae', '', 'fbdfbdfb', '', 2, 'user.png');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `menuPageSite`
--
ALTER TABLE `menuPageSite`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_role_uindex` (`role`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_users_id_fk` (`id_user`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_role_id_fk` (`role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `menuPageSite`
--
ALTER TABLE `menuPageSite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_users_id_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_fk` FOREIGN KEY (`role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
