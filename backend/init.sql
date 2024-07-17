CREATE DATABASE IF NOT EXISTS midnight;

USE midnight;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `long_url` text NOT NULL,
  `short_code` varchar(6) NOT NULL,
  `user` int(11) NOT NULL,
  `transition_count` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`),
  FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Аккаунт админа
INSERT INTO `users` (`id`, `first_name`, `last_name`, `login`, `password`) VALUES
(1, 'Admin', 'Admin', 'admin', 'efe6398127928f1b2e9ef3207fb82663')