-- Database: `TestProject`

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `TestProject` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `TestProject`;

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL ,
  `email` varchar(100) NOT NULL ,
  `password` varchar(255) NOT NULL ,
  `created_at` datetime NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

