SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `cache_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cache_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `score` tinyint(4) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id_user_id` (`cache_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cache_score_ibfk_1` FOREIGN KEY (`cache_id`) REFERENCES `caches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cache_score_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `caches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `difficulty` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  `world` varchar(255) NOT NULL,
  `owner` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `x_y_z_world` (`x`,`y`,`z`,`world`),
  KEY `owner` (`owner`),
  CONSTRAINT `caches_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cache_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `log_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id_user_id` (`cache_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`cache_id`) REFERENCES `caches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `rewards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cache_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `limited` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`cache_id`),
  CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`cache_id`) REFERENCES `caches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) NOT NULL,
  `cache_limit` int(10) unsigned NOT NULL,
  `role` enum('guest','member','moderator','admin','superadmin','owner') NOT NULL DEFAULT 'guest',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- CREATE DATABASE `database_name` COLLATE 'utf8_general_ci';
-- CREATE USER 'username'@'localhost' IDENTIFIED BY PASSWORD '*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19';
-- GRANT DELETE, INSERT, SELECT, UPDATE ON database_name.* TO 'username'@'localhost';