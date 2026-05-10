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
-- Dépendances : à supprimer avant `estate` et `user_`
--

DROP TABLE IF EXISTS `lead_request`;
DROP TABLE IF EXISTS `client_dossier`;

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

-- --------------------------------------------------------

--
-- Demandes clients (infos / visites) sur un bien
--

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

-- --------------------------------------------------------

--
-- Dossiers client (suivi achat / vente)
--

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

--
-- Données de démonstration : agences, agents, annonces fictives et photos
--
-- Images (sous public/) :
--   public/pictures/appartement/ — fichiers appart1-*.jpg … appart5-*.jpg (voir INSERT photo)
--   public/pictures/house/       — Maison, Chalet, Villa : {estate_id}.jpg tant que pas d’autres fichiers
--

INSERT INTO `agence` (`agence_id`, `country`, `agence_address`, `agence_name`) VALUES
(1, 'France', '12 rue de Rivoli, 75004 Paris', 'Ymmo Paris Centre'),
(2, 'France', '45 cours Lafayette, 69003 Lyon', 'Ymmo Lyon Presqu\'île'),
(3, 'France', '8 place des Quinconces, 33000 Bordeaux', 'Ymmo Bordeaux');

INSERT INTO `user_` (`user_id`, `first_name`, `last_name`, `mail`, `phone_number`, `is_admin`, `is_agent`, `password`) VALUES
(1, 'Camille', 'Lefèvre', 'admin@ymm.demo', '+33 6 11 22 33 44', 1, 0, '$2y$12$zMgq3H/rPo4JbKGO2I/ffuvgispGOGgHAXCT7tJUqW6Yow5c2.sD2'),
(2, 'Marie', 'Dubois', 'marie.dubois@ymm.demo', '+33 6 21 32 43 54', 0, 1, '$2y$12$zMgq3H/rPo4JbKGO2I/ffuvgispGOGgHAXCT7tJUqW6Yow5c2.sD2'),
(3, 'Lucas', 'Martin', 'lucas.martin@ymm.demo', '+33 6 31 42 53 64', 0, 1, '$2y$12$zMgq3H/rPo4JbKGO2I/ffuvgispGOGgHAXCT7tJUqW6Yow5c2.sD2'),
(4, 'Sofia', 'Bernard', 'sofia.bernard@ymm.demo', '+33 6 41 52 63 74', 0, 1, '$2y$12$zMgq3H/rPo4JbKGO2I/ffuvgispGOGgHAXCT7tJUqW6Yow5c2.sD2'),
(5, 'Jean', 'Client', 'client@ymm.demo', '+33 6 55 66 77 88', 0, 0, '$2y$12$zMgq3H/rPo4JbKGO2I/ffuvgispGOGgHAXCT7tJUqW6Yow5c2.sD2');

INSERT INTO `estate` (`estate_country`, `estate_address`, `estate_type`, `price`, `surface`, `rooms_count`, `bedrooms_count`, `description`, `estate_status`, `energy_class`, `agent_id`, `agence_id`) VALUES
('France', '18 rue Montorgueil, 75002 Paris', 'Appartement', 685000.00, 72, 3, 2, 'Bel appartement traversant au 4e étage avec balcon filant. Cuisine ouverte équipée, parquet massif, double vitrage récent. Quartier piéton vibrant, commerces et métro à deux pas. Idéal premier achat ou pied-à-terre.', 'available', 'C', 2, 1),
('France', '7 impasse des Lilas, 92100 Boulogne-Billancourt', 'Maison', 1240000.00, 145, 6, 4, 'Maison de ville avec jardin clos de murs, garage et sous-sol aménageable. Séjour lumineux sud-ouest, quatre chambres à l\'étage, salle de bains et salle d\'eau. Quartier calme à proximité du tram.', 'available', 'B', 2, 1),
('France', '112 cours Gambetta, 69007 Lyon', 'Appartement', 395000.00, 58, 2, 1, 'T2 rénové avec coin bureau et grande cave. Vue dégagée sur les toits, ascenseur, digicode et interphone. Charges maîtrisées, résidence bien entretenue.', 'available', 'D', 3, 2),
('France', '3 rue Sainte-Catherine, 33000 Bordeaux', 'Studio', 178000.00, 28, 1, 0, 'Studio optimisé avec mezzanine couchage et rangements sur mesure. Parfait pour étudiant ou investissement locatif meublé. Proche tram et quais.', 'available', 'E', 4, 3),
('France', '56 avenue Victor Hugo, 75116 Paris', 'Duplex', 2150000.00, 118, 5, 3, 'Duplex haussmannien : entrée, cuisine dinatoire et salon au rez-de-chaussée ; chambres et suites à l\'étage. Moulures, cheminée décorative, grandes fenêtres. Cave et parking en sus.', 'available', 'C', 2, 1),
('France', '22 chemin des Vignes, 69290 Craponne', 'Maison', 549000.00, 132, 5, 3, 'Pavillon des années 90 sur terrain arboré de 450 m². Terrasse couverte, piscine hors-sol possible selon PLU. Garage double, dépendance pour bricolage.', 'available', 'D', 3, 2),
('France', '9 place Bellecour, 69002 Lyon', 'Loft', 890000.00, 105, 4, 2, 'Loft industriel réhabilité : dalles béton ciré, poutres apparentes, cuisine îlot central. Hauteur sous plafond 3,20 m. Fenêtres atelier plein sud.', 'available', 'B', 3, 2),
('France', '41 rue Notre-Dame, 33000 Bordeaux', 'Appartement', 512000.00, 89, 4, 2, 'Familial T4 avec balcon cuisine et séjour ouvrant sur cours verdoyante. Place de parking sécurisée en sous-sol. École et parc à 5 minutes à pied.', 'available', 'C', 4, 3),
('France', '200 rue de la Pompe, 75116 Paris', 'Appartement', 1380000.00, 96, 4, 3, 'Quartier très recherché, appartement en bon état avec potentiel décoration. Dressing, deux salles d\'eau, gardienne. Proche Bois de Boulogne.', 'available', 'C', 2, 1),
('France', '15 route des Crêts, 74300 Cluses', 'Chalet', 425000.00, 110, 5, 3, 'Chalet rénové avec vue montagne. Poêle à granulés, isolation récente. Idéal résidence secondaire ou télétravail en montagne. Station à 20 min.', 'available', 'B', 4, 2),
('France', '88 boulevard Haussmann, 75008 Paris', 'Appartement', 2450000.00, 142, 6, 4, 'Grand bourgeois familial, volumes rares. Triple réception, bureau, cave à vin. Immeuble pierre de taille avec ascenseur. Prestige et luminosité.', 'available', 'D', 2, 1),
('France', '5 quai Saint-Antoine, 69005 Lyon', 'Appartement', 334000.00, 63, 3, 2, 'Vue Rhône depuis le séjour. Cuisine séparée rénovée, chambres sur cour calme. Ideal jeune famille ou couple.', 'available', 'D', 3, 2),
('France', '17 cours de l\'Intendance, 33000 Bordeaux', 'Appartement', 467000.00, 77, 3, 2, 'Charme ancien : parquet chevron, cheminée marbre. Rénovation électricité et chauffage collectaire récente. Hyper centre piéton.', 'available', 'E', 4, 3),
('France', '3 villa Mozart, 92110 Clichy', 'Maison', 798000.00, 118, 5, 3, 'Maison mitoyenne une façade avec jardin plein sud. Extension véranda, cuisine équipée neuve. Écoles réputées dans le rayon.', 'available', 'C', 2, 1),
('France', '60 rue Garibaldi, 69006 Lyon', 'Duplex', 715000.00, 102, 4, 3, 'Duplex dernier étage : terrasse privatieve 25 m² sans vis-à-vis. Climatisation réversible, placards intégrés. Cave et box fermé.', 'available', 'B', 3, 2),
('France', '28 rue Fondaudege, 33000 Bordeaux', 'Maison', 689000.00, 125, 6, 4, 'Maison de ville rénovée avec patio intérieur vitré. Garage moto, cave voûtée. Quartier Chartrons, tous commerces.', 'available', 'C', 4, 3),
('France', '14 rue des Martyrs, 75009 Paris', 'Studio', 295000.00, 31, 1, 0, 'Studio coin rue calme, cuisine équipée, salle d\'eau avec fenêtre. Charges inclus chauffage collectif. Excellent rendement locatif.', 'available', 'F', 2, 1),
('France', '101 avenue Jean Jaurès, 69007 Lyon', 'Appartement', 289000.00, 54, 2, 1, 'T2 lumineux avec loggia fermée type véranda. Résidence années 70 réhabilitée (ITE). Stationnement libre dans la cour.', 'available', 'C', 3, 2),
('France', '6 impasse du Moulin, 33170 Gradignan', 'Maison', 459000.00, 108, 5, 3, 'Plain-pied sur sous-sol total aménagé. Terrain clos 600 m², portail motorisé. Fibre optique, panneaux photovoltaïques en location vente à reprendre.', 'available', 'A', 4, 3),
('France', '29 rue de Belleville, 75020 Paris', 'Appartement', 418000.00, 68, 3, 2, 'Excellente exposition, rénovation complète 2023. Matériaux nobles, rangements optimisés. Métro ligne 11 à 200 m.', 'available', 'B', 2, 1),
('France', '44 rue de la République, 69002 Lyon', 'Penthouse', 1680000.00, 135, 5, 3, 'Dernier étage avec rooftop privatif et jacuzzi. Domotique, cuisine bulthaup, dressing luxe. Service conciergerie dans la résidence.', 'available', 'A', 3, 2),
('France', '11 allée de Tourny, 33000 Bordeaux', 'Appartement', 925000.00, 112, 5, 4, 'Famille nombreuse : cinq pièces dont bureau, deux salles de bains. Parquet chêne, moulures restaurées. Vue dégagée musée.', 'available', 'D', 4, 3),
('France', '8 rue du Faubourg Saint-Honoré, 75008 Paris', 'Appartement', 3200000.00, 155, 6, 4, 'Prestige absolu : réception en enfilade, hauteur sous plafond exceptionnelle. Sécurité renforcée, climatisation gainable invisible.', 'available', 'C', 2, 1),
('France', '33 montée du Chemin Neuf, 69005 Lyon', 'Maison', 582000.00, 118, 5, 3, 'Maison villageoise avec terrasses en restanques. Garage sous la maison, cave à vin naturelle. Quartier Fourvière, panorama Lyon.', 'available', 'E', 3, 2),
('France', '19 cours Pasteur, 33000 Bordeaux', 'Loft', 598000.00, 92, 3, 2, 'Ancien entrepôt réhabilité en loft artistique. Grande hauteur, verrière manufacture, espace open minimal cloisonné.', 'available', 'D', 4, 3),
('France', '72 rue de Rome, 75008 Paris', 'Appartement', 975000.00, 85, 4, 2, 'Cadre administratif idéal : bureau séparé, double salon. Immeuble sécurisé, proximité Saint-Lazare.', 'available', 'E', 2, 1),
('France', '5 place Carnot, 69002 Lyon', 'Studio', 212000.00, 26, 1, 0, 'Investissement : studio loué meublé jusqu\'à échéance libérable. Charges faibles, fenêtre double orientation.', 'available', 'D', 3, 2),
('France', '26 rue du Temple, 33000 Bordeaux', 'Duplex', 734000.00, 98, 4, 3, 'Duplex dans immeuble pierre : RDC séjour-cuisine, étage nuit. Patio privatif 15 m² avec stores.', 'available', 'C', 4, 3),
('France', '103 avenue Kléber, 75116 Paris', 'Appartement', 1520000.00, 104, 4, 3, 'Vue Tour Eiffel partielle depuis deux pièces. Rénovation haut de gamme, dressing walk-in, deux caves.', 'available', 'B', 2, 1),
('France', '14 chemin des Hauts de Bron, 69500 Bron', 'Maison', 389000.00, 98, 5, 3, 'Pavillon secteur pavillonnaire, garage accolé, terrain 350 m² clos. Travaux de rafraîchissement à prévoir sur sanitaires.', 'available', 'F', 3, 2),
('France', '8 rue Camille Sauvageau, 33100 Bordeaux', 'Appartement', 356000.00, 67, 3, 2, 'Bacalan / Bassins à flot : T3 avec terrasse 12 m² sans vis-à-vis. Navette fluviale à 300 m, tramway direct centre.', 'available', 'B', 4, 3),
('France', '55 rue de Passy, 75016 Paris', 'Appartement', 1180000.00, 91, 4, 2, 'Familial Passy : entrée, séjour double, cuisine dinatoire. Gardienne, digicode, ascenseur moderne. Écoles internationales.', 'available', 'C', 2, 1),
('France', '21 rue Auguste Comte, 69002 Lyon', 'Appartement', 445000.00, 74, 3, 2, 'Jean Macé : proximité parc Blandan. Appartement calme sur cour, placards partout, chauffage individuel gaz.', 'available', 'D', 3, 2),
('France', '40 cours Alsace-Lorraine, 33000 Bordeaux', 'Villa', 1850000.00, 210, 8, 5, 'Villa exceptionnelle avec piscine chauffée et pool-house. Dépendance maison d\'amis, portail vidéo. Quartier résidentiel arboré.', 'available', 'B', 4, 3),
('France', '16 rue des Archives, 75004 Paris', 'Appartement', 798000.00, 76, 3, 2, 'Marais historique : poutres apparentes, parquet Versailles partiel. Cave voûtée, faibles charges copropriété six lots.', 'available', 'E', 2, 1),
('France', '9 rue de la République, 69260 Charbonnières-les-Bains', 'Maison', 515000.00, 140, 6, 4, 'Grande maison familiale avec sous-sol semi enterré (buanderie + cave). Jardin plat, cabanon outillage. Bus vers Lyon.', 'available', 'C', 3, 2);

INSERT INTO `photo` (`url_path`, `estate_id`) VALUES
('pictures/appartement/appart1-front.jpg', 1),
('pictures/appartement/appart1-salon.jpg', 1),
('pictures/appartement/appart1-kitchen.jpg', 1),
('pictures/appartement/appart1-bedroom1.jpg', 1),
('pictures/appartement/appart1-bedroom2.jpg', 1),
('pictures/appartement/appart1-swimingpool.jpg', 1),
('pictures/house/2.jpg', 2),
('pictures/appartement/appart2.jpg', 3),
('pictures/appartement/appart2-salon.jpg', 3),
('pictures/appartement/appart2-kitchen.jpg', 3),
('pictures/appartement/appart2-bedroom.jpg', 3),
('pictures/appartement/appart2-bedroom2.jpg', 3),
('pictures/appartement/appart2-bathroom.jpg', 3),
('pictures/appartement/appart3-linvingroom.jpg', 4),
('pictures/appartement/appart3-linvingroom2.jpg', 4),
('pictures/appartement/appart3-kitchen.jpg', 4),
('pictures/appartement/appart3-bedroom1.jpg', 4),
('pictures/appartement/appart3-bedroom2.jpg', 4),
('pictures/appartement/appart3-bathroom.jpg', 4),
('pictures/appartement/appart4-exe.jpg', 5),
('pictures/appartement/appart4-livingroom.jpg', 5),
('pictures/appartement/appart4-livingroom-image2.jpg', 5),
('pictures/appartement/appart4-kitchen.jpg', 5),
('pictures/appartement/appart4-kitchen2.jpg', 5),
('pictures/appartement/appart4-bedroom1.jpg', 5),
('pictures/appartement/appart4-bedroom2.jpg', 5),
('pictures/appartement/appart4-bathroom1.jpg', 5),
('pictures/appartement/appart4-bathroom-image2.jpg', 5),
('pictures/house/6.jpg', 6),
('pictures/appartement/appart5-ex.jpg', 7),
('pictures/appartement/appart5-livingroom.jpg', 7),
('pictures/appartement/appart5-livingroom2.jpg', 7),
('pictures/appartement/appart5-kitchen.jpg', 7),
('pictures/appartement/appart5-bedroom1.jpg', 7),
('pictures/appartement/appart5-bedroom2.jpg', 7),
('pictures/appartement/appart5-bathroom1.jpg', 7),
('pictures/appartement/appart5-bathroom-image2.jpg', 7),
('pictures/appartement/appart5-garage.jpg', 7),
('pictures/appartement/appart5-swimingpool.jpg', 7),
('pictures/appartement/appart1-front.jpg', 8),
('pictures/appartement/appart1-salon.jpg', 8),
('pictures/appartement/appart1-kitchen.jpg', 8),
('pictures/appartement/appart1-bedroom1.jpg', 8),
('pictures/appartement/appart1-bedroom2.jpg', 8),
('pictures/appartement/appart1-swimingpool.jpg', 8),
('pictures/appartement/appart2.jpg', 9),
('pictures/appartement/appart2-salon.jpg', 9),
('pictures/appartement/appart2-kitchen.jpg', 9),
('pictures/appartement/appart2-bedroom.jpg', 9),
('pictures/appartement/appart2-bedroom2.jpg', 9),
('pictures/appartement/appart2-bathroom.jpg', 9),
('pictures/house/10.jpg', 10),
('pictures/appartement/appart3-linvingroom.jpg', 11),
('pictures/appartement/appart3-linvingroom2.jpg', 11),
('pictures/appartement/appart3-kitchen.jpg', 11),
('pictures/appartement/appart3-bedroom1.jpg', 11),
('pictures/appartement/appart3-bedroom2.jpg', 11),
('pictures/appartement/appart3-bathroom.jpg', 11),
('pictures/appartement/appart4-exe.jpg', 12),
('pictures/appartement/appart4-livingroom.jpg', 12),
('pictures/appartement/appart4-livingroom-image2.jpg', 12),
('pictures/appartement/appart4-kitchen.jpg', 12),
('pictures/appartement/appart4-kitchen2.jpg', 12),
('pictures/appartement/appart4-bedroom1.jpg', 12),
('pictures/appartement/appart4-bedroom2.jpg', 12),
('pictures/appartement/appart4-bathroom1.jpg', 12),
('pictures/appartement/appart4-bathroom-image2.jpg', 12),
('pictures/appartement/appart5-ex.jpg', 13),
('pictures/appartement/appart5-livingroom.jpg', 13),
('pictures/appartement/appart5-livingroom2.jpg', 13),
('pictures/appartement/appart5-kitchen.jpg', 13),
('pictures/appartement/appart5-bedroom1.jpg', 13),
('pictures/appartement/appart5-bedroom2.jpg', 13),
('pictures/appartement/appart5-bathroom1.jpg', 13),
('pictures/appartement/appart5-bathroom-image2.jpg', 13),
('pictures/appartement/appart5-garage.jpg', 13),
('pictures/appartement/appart5-swimingpool.jpg', 13),
('pictures/house/14.jpg', 14),
('pictures/appartement/appart1-front.jpg', 15),
('pictures/appartement/appart1-salon.jpg', 15),
('pictures/appartement/appart1-kitchen.jpg', 15),
('pictures/appartement/appart1-bedroom1.jpg', 15),
('pictures/appartement/appart1-bedroom2.jpg', 15),
('pictures/appartement/appart1-swimingpool.jpg', 15),
('pictures/house/16.jpg', 16),
('pictures/appartement/appart2.jpg', 17),
('pictures/appartement/appart2-salon.jpg', 17),
('pictures/appartement/appart2-kitchen.jpg', 17),
('pictures/appartement/appart2-bedroom.jpg', 17),
('pictures/appartement/appart2-bedroom2.jpg', 17),
('pictures/appartement/appart2-bathroom.jpg', 17),
('pictures/appartement/appart3-linvingroom.jpg', 18),
('pictures/appartement/appart3-linvingroom2.jpg', 18),
('pictures/appartement/appart3-kitchen.jpg', 18),
('pictures/appartement/appart3-bedroom1.jpg', 18),
('pictures/appartement/appart3-bedroom2.jpg', 18),
('pictures/appartement/appart3-bathroom.jpg', 18),
('pictures/house/19.jpg', 19),
('pictures/appartement/appart4-exe.jpg', 20),
('pictures/appartement/appart4-livingroom.jpg', 20),
('pictures/appartement/appart4-livingroom-image2.jpg', 20),
('pictures/appartement/appart4-kitchen.jpg', 20),
('pictures/appartement/appart4-kitchen2.jpg', 20),
('pictures/appartement/appart4-bedroom1.jpg', 20),
('pictures/appartement/appart4-bedroom2.jpg', 20),
('pictures/appartement/appart4-bathroom1.jpg', 20),
('pictures/appartement/appart4-bathroom-image2.jpg', 20),
('pictures/appartement/appart5-ex.jpg', 21),
('pictures/appartement/appart5-livingroom.jpg', 21),
('pictures/appartement/appart5-livingroom2.jpg', 21),
('pictures/appartement/appart5-kitchen.jpg', 21),
('pictures/appartement/appart5-bedroom1.jpg', 21),
('pictures/appartement/appart5-bedroom2.jpg', 21),
('pictures/appartement/appart5-bathroom1.jpg', 21),
('pictures/appartement/appart5-bathroom-image2.jpg', 21),
('pictures/appartement/appart5-garage.jpg', 21),
('pictures/appartement/appart5-swimingpool.jpg', 21),
('pictures/appartement/appart1-front.jpg', 22),
('pictures/appartement/appart1-salon.jpg', 22),
('pictures/appartement/appart1-kitchen.jpg', 22),
('pictures/appartement/appart1-bedroom1.jpg', 22),
('pictures/appartement/appart1-bedroom2.jpg', 22),
('pictures/appartement/appart1-swimingpool.jpg', 22),
('pictures/appartement/appart2.jpg', 23),
('pictures/appartement/appart2-salon.jpg', 23),
('pictures/appartement/appart2-kitchen.jpg', 23),
('pictures/appartement/appart2-bedroom.jpg', 23),
('pictures/appartement/appart2-bedroom2.jpg', 23),
('pictures/appartement/appart2-bathroom.jpg', 23),
('pictures/house/24.jpg', 24),
('pictures/appartement/appart3-linvingroom.jpg', 25),
('pictures/appartement/appart3-linvingroom2.jpg', 25),
('pictures/appartement/appart3-kitchen.jpg', 25),
('pictures/appartement/appart3-bedroom1.jpg', 25),
('pictures/appartement/appart3-bedroom2.jpg', 25),
('pictures/appartement/appart3-bathroom.jpg', 25),
('pictures/appartement/appart4-exe.jpg', 26),
('pictures/appartement/appart4-livingroom.jpg', 26),
('pictures/appartement/appart4-livingroom-image2.jpg', 26),
('pictures/appartement/appart4-kitchen.jpg', 26),
('pictures/appartement/appart4-kitchen2.jpg', 26),
('pictures/appartement/appart4-bedroom1.jpg', 26),
('pictures/appartement/appart4-bedroom2.jpg', 26),
('pictures/appartement/appart4-bathroom1.jpg', 26),
('pictures/appartement/appart4-bathroom-image2.jpg', 26),
('pictures/appartement/appart5-ex.jpg', 27),
('pictures/appartement/appart5-livingroom.jpg', 27),
('pictures/appartement/appart5-livingroom2.jpg', 27),
('pictures/appartement/appart5-kitchen.jpg', 27),
('pictures/appartement/appart5-bedroom1.jpg', 27),
('pictures/appartement/appart5-bedroom2.jpg', 27),
('pictures/appartement/appart5-bathroom1.jpg', 27),
('pictures/appartement/appart5-bathroom-image2.jpg', 27),
('pictures/appartement/appart5-garage.jpg', 27),
('pictures/appartement/appart5-swimingpool.jpg', 27),
('pictures/appartement/appart1-front.jpg', 28),
('pictures/appartement/appart1-salon.jpg', 28),
('pictures/appartement/appart1-kitchen.jpg', 28),
('pictures/appartement/appart1-bedroom1.jpg', 28),
('pictures/appartement/appart1-bedroom2.jpg', 28),
('pictures/appartement/appart1-swimingpool.jpg', 28),
('pictures/appartement/appart2.jpg', 29),
('pictures/appartement/appart2-salon.jpg', 29),
('pictures/appartement/appart2-kitchen.jpg', 29),
('pictures/appartement/appart2-bedroom.jpg', 29),
('pictures/appartement/appart2-bedroom2.jpg', 29),
('pictures/appartement/appart2-bathroom.jpg', 29),
('pictures/house/30.jpg', 30),
('pictures/appartement/appart3-linvingroom.jpg', 31),
('pictures/appartement/appart3-linvingroom2.jpg', 31),
('pictures/appartement/appart3-kitchen.jpg', 31),
('pictures/appartement/appart3-bedroom1.jpg', 31),
('pictures/appartement/appart3-bedroom2.jpg', 31),
('pictures/appartement/appart3-bathroom.jpg', 31),
('pictures/appartement/appart4-exe.jpg', 32),
('pictures/appartement/appart4-livingroom.jpg', 32),
('pictures/appartement/appart4-livingroom-image2.jpg', 32),
('pictures/appartement/appart4-kitchen.jpg', 32),
('pictures/appartement/appart4-kitchen2.jpg', 32),
('pictures/appartement/appart4-bedroom1.jpg', 32),
('pictures/appartement/appart4-bedroom2.jpg', 32),
('pictures/appartement/appart4-bathroom1.jpg', 32),
('pictures/appartement/appart4-bathroom-image2.jpg', 32),
('pictures/appartement/appart5-ex.jpg', 33),
('pictures/appartement/appart5-livingroom.jpg', 33),
('pictures/appartement/appart5-livingroom2.jpg', 33),
('pictures/appartement/appart5-kitchen.jpg', 33),
('pictures/appartement/appart5-bedroom1.jpg', 33),
('pictures/appartement/appart5-bedroom2.jpg', 33),
('pictures/appartement/appart5-bathroom1.jpg', 33),
('pictures/appartement/appart5-bathroom-image2.jpg', 33),
('pictures/appartement/appart5-garage.jpg', 33),
('pictures/appartement/appart5-swimingpool.jpg', 33),
('pictures/house/34.jpg', 34),
('pictures/appartement/appart1-front.jpg', 35),
('pictures/appartement/appart1-salon.jpg', 35),
('pictures/appartement/appart1-kitchen.jpg', 35),
('pictures/appartement/appart1-bedroom1.jpg', 35),
('pictures/appartement/appart1-bedroom2.jpg', 35),
('pictures/appartement/appart1-swimingpool.jpg', 35),
('pictures/house/36.jpg', 36);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
INSERT INTO user_ (first_name, last_name, mail, phone_number, is_admin, is_agent, password) VALUES
('Malek', 'Esughi', 'malek@example.com', NULL, 1, 0, '$2y$12$OEOosIFwaSMJ8I.X5FFd6eOEkF3qPax7QU1jcAVuvtwx8cyBQruiC'),
('Victor', 'Uzodimma', 'victor@example.com', NULL, 0, 1, '$2y$12$OEOosIFwaSMJ8I.X5FFd6eOEkF3qPax7QU1jcAVuvtwx8cyBQruiC');