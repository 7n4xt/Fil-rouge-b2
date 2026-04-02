-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 02 avr. 2026 à 12:14
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `real_estate`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `agence_id` int NOT NULL AUTO_INCREMENT,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agence_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agence_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`agence_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `estate`
--

DROP TABLE IF EXISTS `estate`;
CREATE TABLE IF NOT EXISTS `estate` (
  `estate_id` int NOT NULL AUTO_INCREMENT,
  `estate_country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `surface` int DEFAULT NULL,
  `rooms_count` int DEFAULT NULL,
  `bedrooms_count` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `estate_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `energy_class` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_id` int NOT NULL,
  `agence_id` int NOT NULL,
  PRIMARY KEY (`estate_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agence_id` (`agence_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `estatefile`
--

DROP TABLE IF EXISTS `estatefile`;
CREATE TABLE IF NOT EXISTS `estatefile` (
  `file_id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate_id` int NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `estate_id` (`estate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorite`
--

DROP TABLE IF EXISTS `favorite`;
CREATE TABLE IF NOT EXISTS `favorite` (
  `user_id` int NOT NULL,
  `estate_id` int NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`estate_id`),
  KEY `estate_id` (`estate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `photo_id` int NOT NULL AUTO_INCREMENT,
  `url_path` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estate_id` int NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `estate_id` (`estate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `transaction_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `price_final` decimal(12,2) DEFAULT NULL,
  `buyer_id` int NOT NULL,
  `estate_id` int NOT NULL,
  `agence_id` int NOT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `estate_id` (`estate_id`),
  KEY `agence_id` (`agence_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_`
--

DROP TABLE IF EXISTS `user_`;
CREATE TABLE IF NOT EXISTS `user_` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `is_agent` tinyint(1) DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
