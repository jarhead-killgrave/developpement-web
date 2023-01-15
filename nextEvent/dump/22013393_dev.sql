-- phpMyAdmin SQL Dump
-- version 5.0.4deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql.info.unicaen.fr:3306
-- Généré le : lun. 28 nov. 2022 à 21:48
-- Version du serveur :  10.5.11-MariaDB-1
-- Version de PHP : 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `22013393_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `place` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `dateCreation` datetime NOT NULL DEFAULT current_timestamp(),
  `dateUpdate` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `date`, `place`, `image`, `dateCreation`, `dateUpdate`, `user_id`) VALUES
('event_6384be79f036a4.53196852', 'La nuit du code', 'La nuit du code est une initiative de l\'association Code.org, qui a pour but de faire découvrir aux jeunes le code informatique. Elle se déroule chaque année le dernier vendredi de novembre, et est organisée dans plus de 100 pays.', '2024-11-24 19:00:00', 'Bordeaux', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be79f41222.05062032', 'La marche des femmes', 'La marche des femmes est une manifestation qui a lieu chaque année le 8 mars, pour dénoncer les inégalités entre les hommes et les femmes.', '2023-03-08 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a0498a2.97609906', 'La fête de la musique', 'La fête de la musique est une manifestation qui a lieu chaque année le 21 juin, pour célébrer la musique.', '2023-06-21 19:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a0d6b55.50693498', 'La journée mondiale du logiciel libre', 'La journée mondiale du logiciel libre est une manifestation qui a lieu chaque année le 3 septembre, pour promouvoir le logiciel libre.', '2024-09-03 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a162b43.48294318', 'La journée mondiale de la paix', 'La journée mondiale de la paix est une manifestation qui a lieu chaque année le 21 septembre, pour promouvoir la paix.', '2024-09-21 19:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a1b0453.12799169', 'La journée mondiale de l\'information', 'La journée mondiale de l\'information est une manifestation qui a lieu chaque année le 28 septembre, pour promouvoir l\'accès à l\'information.', '2024-09-28 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a1f28f2.99718209', 'La journée mondiale de l\'audiovisuel', 'La journée mondiale de l\'audiovisuel est une manifestation qui a lieu chaque année le 2 novembre, pour promouvoir l\'audiovisuel.', '2023-11-02 19:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a23cf96.58307262', 'La journée mondiale de l\'Internet', 'La journée mondiale de l\'Internet est une manifestation qui a lieu chaque année le 17 novembre, pour promouvoir l\'Internet.', '2024-11-17 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a27f783.88598655', 'La journée mondiale de la télévision', 'La journée mondiale de la télévision est une manifestation qui a lieu chaque année le 21 novembre, pour promouvoir la télévision.', '2023-11-21 19:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a2ca4f0.13285143', 'La journée mondiale de la radio', 'La journée mondiale de la radio est une manifestation qui a lieu chaque année le 13 février, pour promouvoir la radio.', '2025-02-13 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a30c472.93657840', 'La journée mondiale de la téléphonie mobile', 'La journée mondiale de la téléphonie mobile est une manifestation qui a lieu chaque année le 17 février, pour promouvoir la téléphonie mobile.', '2025-02-17 19:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a361035.33934519', 'La marche pour le climat', 'La marche pour le climat est une manifestation qui a lieu chaque année le 24 mars, pour promouvoir la lutte contre le réchauffement climatique.', '2025-03-24 14:00:00', 'Paris', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a3aaf27.40330672', 'Hackathon de la ville de Caen', 'Hackathon de la ville de Caen', '2026-04-06 19:00:00', 'Caen', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a438592.38511188', 'Gala de l\'innovation', 'Gala au cours duquel les projets de l\'année sont présentés', '2027-04-12 19:00:00', 'Caen', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', ''),
('event_6384be7a47e379.19884469', 'Tous au Hackathon', 'Journée de découverte du Hackathon', '2028-04-13 19:00:00', 'Caen', '', '2022-11-28 14:58:17', '2022-11-28 14:58:17', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `status`) VALUES
('user_638381abc6c960.31309221', 'admin', 'admin', '$2y$10$29Zmzz1PZ.5cqYxEU6kHyucklS4ibwa/NhwhSMPdQkVDw7AOHuOfi', 'admin'),
('user_6383c8ed8bced0.50681521', 'Manne Emile', 'jarhead', '$2y$10$1Oo1eK7f6q2lH7cl2umFY..jZWpsuFJTYswzfClktkZGYi1R5vRBK', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
