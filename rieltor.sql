CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `rooms` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `district` varchar(100) NOT NULL,
  `house_type` varchar(100) NOT NULL,
  `renovation` varchar(100) NOT NULL,
  `floor` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `properties` (`id`, `title`, `price`, `rooms`, `area`, `district`, `house_type`, `renovation`, `floor`, `image_url`, `created_at`, `description`) VALUES
(1, 'Уютная 2-к квартира в центре', 8500000, 2, 60, 'Центральный', 'Кирпичный', 'Евроремонт', 5, 'house1.jpg', '2026-04-18 21:51:48', 'Прекрасная квартира с панорамными окнами. Рядом вся инфраструктура: школы, садики, магазины. Тихий двор и вежливые соседи.'),
(2, 'Просторная студия у парка', 4200000, 1, 32, 'Приморский', 'Монолит', 'Косметический', 12, 'house2.jpg', '2026-04-18 21:51:48', 'Светлая студия, идеально подойдет для молодого специалиста или сдачи в аренду. Сделан свежий ремонт.'),
(3, '3-к квартира для большой семьи', 12500000, 3, 95, 'Выборгский', 'Панельный', 'Дизайнерский', 3, 'house3.jpg', '2026-04-18 21:51:48', 'Огромная квартира для большой семьи. Три изолированные комнаты, большая кухня-гостиная и балкон с видом на город.');

CREATE TABLE IF NOT EXISTS `property_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'В обработке',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `property_requests` (`id`, `property_id`, `user_name`, `phone`, `message`, `created_at`, `status`) VALUES
(1, 1, 'asd', '+79538458598', 'Хочу посмотреть объект: Уютная 2-к квартира в центре', '2026-04-19 20:41:37', 'Одобрено');

CREATE TABLE IF NOT EXISTS `seller_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `property_address` text NOT NULL,
  `expected_price` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'В обработке',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `seller_requests` (`id`, `full_name`, `phone`, `property_address`, `expected_price`, `created_at`, `status`) VALUES
(1, 'asd', '8754214521', 'dsf', '213524', '2026-04-19 20:44:40', 'Отменено'),
(2, 'hadesray', '4646546', 'jhgh\r\n\r\n', '45123', '2026-04-19 21:05:48', 'Одобрено'),
(3, 'admin', 'фыв', 'фывфыв', 'фывфыв', '2026-04-20 11:13:38', 'Одобрено'),
(4, 'admin', 'ssadf', 'asfadfs', 'asfadf', '2026-04-20 11:24:33', 'Одобрено'),
(5, 'admin', '+79538458598', 'jpiyh', ';ijlhj', '2026-04-20 11:27:41', 'Одобрено'),
(6, 'admin', '+79538458598', 'kljhg', 'gvjbmn', '2026-04-20 11:28:42', 'В обработке');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `email`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'hadesray@yandex.ru', 'hadesray', '1234', '2026-04-18 21:35:06', 'user'),
(2, 'admin@mail.ru', 'admin', 'Qaz12345', '2026-04-18 22:21:54', 'admin');

ALTER TABLE `property_requests`
  ADD CONSTRAINT `property_requests_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;