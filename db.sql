-- PHP CRUD Generator Database Schema

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `php_crud_generator` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Use the database
USE `php_crud_generator`;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Projects table to store generated projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `base_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tables table to store table definitions for projects
CREATE TABLE IF NOT EXISTS `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `tables_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fields table to store field definitions for tables
CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `nullable` tinyint(1) NOT NULL DEFAULT '0',
  `constraints` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`),
  CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `users` (`username`, `email`, `password`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('user', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Insert sample project
INSERT INTO `projects` (`name`, `author`, `base_url`, `user_id`) VALUES
('Blog System', 'John Doe', 'http://localhost/blog', 1);

-- Insert sample tables for the blog project
INSERT INTO `tables` (`project_id`, `name`) VALUES
(1, 'posts'),
(1, 'categories'),
(1, 'comments');

-- Insert sample fields for the posts table
INSERT INTO `fields` (`table_id`, `name`, `type`, `length`, `default`, `nullable`, `constraints`) VALUES
(1, 'id', 'int', 11, NULL, 0, 'primary'),
(1, 'title', 'varchar', 255, NULL, 0, NULL),
(1, 'content', 'text', NULL, NULL, 0, NULL),
(1, 'category_id', 'int', 11, NULL, 1, 'foreign'),
(1, 'user_id', 'int', 11, NULL, 0, 'foreign'),
(1, 'created_at', 'datetime', NULL, 'CURRENT_TIMESTAMP', 0, NULL);

-- Insert sample fields for the categories table
INSERT INTO `fields` (`table_id`, `name`, `type`, `length`, `default`, `nullable`, `constraints`) VALUES
(2, 'id', 'int', 11, NULL, 0, 'primary'),
(2, 'name', 'varchar', 100, NULL, 0, NULL),
(2, 'description', 'text', NULL, NULL, 1, NULL);

-- Insert sample fields for the comments table
INSERT INTO `fields` (`table_id`, `name`, `type`, `length`, `default`, `nullable`, `constraints`) VALUES
(3, 'id', 'int', 11, NULL, 0, 'primary'),
(3, 'post_id', 'int', 11, NULL, 0, 'foreign'),
(3, 'user_id', 'int', 11, NULL, 1, 'foreign'),
(3, 'content', 'text', NULL, NULL, 0, NULL),
(3, 'created_at', 'datetime', NULL, 'CURRENT_TIMESTAMP', 0, NULL);