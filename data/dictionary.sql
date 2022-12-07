-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 07 déc. 2022 à 15:47
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dictionary`
--

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastName` varchar(30) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `century` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_nom` (`lastName`) USING BTREE,
  KEY `idx_siecle` (`century`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `author`
--

INSERT INTO `author` (`id`, `lastName`, `firstName`, `century`) VALUES
(1, 'Totovitch Totov', 'Toto', 19),
(2, 'Houlala', 'Anthony', 21),
(3, '', 'Socrate', -5),
(4, 'Socrate', 'Jean-Patrick', 21),
(5, 'Denard', 'Bob', 20),
(6, 'Camus', 'Serge-Henri', 20),
(7, 'Houlala', 'Jehan', 16),
(8, 'Random', '', 19),
(9, '', 'Gustave', 18),
(10, 'Raminagrobis', 'HerculÃ©on', 12),
(11, 'Delagarde', 'Louison', 21),
(12, 'Camus', 'Serge-Henri', 20);

-- --------------------------------------------------------

--
-- Structure de la table `quote`
--

DROP TABLE IF EXISTS `quote`;
CREATE TABLE IF NOT EXISTS `quote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `authorId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `authorIdIdx` (`authorId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `quote`
--

INSERT INTO `quote` (`id`, `text`, `authorId`) VALUES
(2, 'Bonjour tout le monde.', 2),
(3, 'Ne complique pas tout. Nom : Houlala !', 2),
(4, 'Heureusement qu\'y a pas la guerre !', 1),
(5, 'Wesh ou ?', 3),
(6, 'Force et rage valent plus que patience ni que longueur de temps.', 1),
(7, 'Rho l\'autre hÃ© !', 3),
(8, 'Mais enfin, pourquoi tout le monde paraÃ®t-il tant surpris que je ne sois pas vÃªtu d\'une toge ?', 4),
(9, 'Tant va l\'eau Ã  la cruche qu\'Ã  la fin elle se rÃ©pare.', 1),
(10, 'Ã‡a gaze ? 8)', 5),
(11, 'Sapristi ! Quelle splendide tarte aux quetsches.', 6),
(12, 'CompÃ¨re, voici qui est Ã  toi si tu veux tailler dans le fretin un bon coup.', 7),
(13, 'Au hasard : harengs pommes Ã  l\'huile !', 8),
(14, 'La peste soit des intolÃ©rants !', 9),
(15, 'Rien ne sert de partir Ã  point, il faut courir.', 1),
(16, 'Cet autocuiseur est de toute beautÃ©.', 6),
(17, 'Je fot saver molt bons chopins,\r\nSi fot saver bon lecheri\r\nDont je fot molt a cort cheri.', 10),
(18, 'Fait chaud, non ?', 5),
(19, 'C\'est moi ou il fait froid dans le coin ?', 4),
(20, 'Franchement Ã§a peut Ãªtre relou d\'avoir un homonyme cÃ©lÃ¨bre. Z\'avez rien de mieux Ã  faire que de noter tout ce que je dis ?', 4),
(21, 'En cest estat passa iusques Ã  vn an & dix moys : onquel temps par le conseil des medecins on commenÃ§a le porter : & fut faicte vne belle charrette Ã  bÅ“ufs par l\'inuention de Iehan Denyau.', 7),
(24, 'Vous sers-je en riz ?', 6),
(25, 'Youpi banane !', 1),
(26, 'C\'est bien de se lancer des fleurs.', 11),
(27, 'Tout Ã©couteur vit aux dÃ©pends de celui qui le flatte.', 1),
(28, 'Le couscous, c\'est trÃ¨s surfait.', 12),
(30, 'Ziva, tu t\'es cru chez mÃ©mÃ© ou ?!', 3),
(31, 'Ami, vous noterez que par le monde y a beaucoup plus de couillons que d\'hommes, et de ce vous souvienne.', 7),
(32, 'Le tajine Ã§a dÃ©chire.', 6);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `quote`
--
ALTER TABLE `quote`
  ADD CONSTRAINT `quote_ibfk_1` FOREIGN KEY (`authorId`) REFERENCES `author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
