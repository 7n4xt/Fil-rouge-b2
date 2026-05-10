-- À exécuter sur une base déjà créée (sans tout réimporter real_estate.sql).
-- Crée les tables pour demandes clients et dossiers.

CREATE TABLE IF NOT EXISTS `lead_request` (
  `lead_request_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `estate_id` int NOT NULL,
  `request_kind` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'information',
  `message` text COLLATE utf8mb4_unicode_ci,
  `preferred_visit_at` datetime DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nouveau',
  `agent_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lead_request_id`),
  KEY `user_id` (`user_id`),
  KEY `estate_id` (`estate_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `client_dossier` (
  `dossier_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `agent_id` int NOT NULL,
  `estate_id` int DEFAULT NULL,
  `flow_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'achat',
  `step` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contact',
  `title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes_public` text COLLATE utf8mb4_unicode_ci,
  `notes_internal` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`dossier_id`),
  KEY `user_id` (`user_id`),
  KEY `agent_id` (`agent_id`),
  KEY `estate_id` (`estate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
