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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `properties` (`id`, `title`, `price`, `rooms`, `area`, `district`, `house_type`, `renovation`, `floor`, `image_url`, `created_at`) VALUES
(1, 'Уютная 2-к квартира в центре', 8500000, 2, 60, 'Центральный', 'Кирпичный', 'Евроремонт', 5, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80', '2026-04-19 11:51:48'),
(2, 'Просторная студия у парка', 4200000, 1, 32, 'Приморский', 'Монолит', 'Косметический', 12, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80', '2026-04-19 11:51:48'),
(3, '3-к квартира для большой семьи', 12500000, 3, 95, 'Выборгский', 'Панельный', 'Дизайнерский', 3, 'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=600&q=80', '2026-04-19 11:51:48');

CREATE TABLE IF NOT EXISTS `seller_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `property_address` text NOT NULL,
  `expected_price` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'hadesray@yandex.ru', 'hadesray', '1234', '2026-04-19 11:35:06', 'user'),
(2, 'admin@mail.ru', 'admin', 'Qaz12345', '2026-04-19 12:21:54', 'admin');
COMMIT;