-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 19 jan. 2020 à 12:32
-- Version du serveur :  5.6.38-1~dotdeb+7.1
-- Version de PHP :  7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `osurv`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE `accounts` (
  `userId` int(20) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `verificationId` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `verfIdEmail` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verfPassword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canBeDeleted` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `accounts_client`
--

CREATE TABLE `accounts_client` (
  `userId` int(11) NOT NULL,
  `secretToken` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `playerlist`
--

CREATE TABLE `playerlist` (
  `userId` int(10) NOT NULL COMMENT 'osu player id',
  `userName` varchar(256) NOT NULL COMMENT 'osu username'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `replaylist`
--

CREATE TABLE `replaylist` (
  `replayId` varchar(13) NOT NULL,
  `beatmapId` int(11) NOT NULL,
  `beatmapSetId` int(11) NOT NULL,
  `userId` int(10) NOT NULL,
  `OFN` text NOT NULL,
  `BFN` text NOT NULL,
  `md5` text NOT NULL,
  `youtubeId` varchar(1000) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `permanent` tinyint(1) NOT NULL DEFAULT '0',
  `playMod` int(11) DEFAULT '4' COMMENT 'Osu, Mania, CTB, Taiko',
  `binaryMods` int(11) DEFAULT NULL,
  `compressed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `replaystats`
--

CREATE TABLE `replaystats` (
  `replayId` varchar(100) NOT NULL,
  `gamemode` int(11) NOT NULL,
  `modsBinary` int(11) NOT NULL,
  `stars` int(11) NOT NULL,
  `pp` float NOT NULL,
  `acc` float NOT NULL,
  `ar` float NOT NULL,
  `BPM` float NOT NULL,
  `x300` int(11) NOT NULL,
  `x100` int(11) NOT NULL,
  `x50` int(11) NOT NULL,
  `gekis` int(11) NOT NULL,
  `katus` int(11) NOT NULL,
  `miss` int(11) NOT NULL,
  `t_score` int(11) NOT NULL,
  `max_combo` int(11) NOT NULL,
  `perfect` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `requestlist`
--

CREATE TABLE `requestlist` (
  `replayId` varchar(13) NOT NULL COMMENT 'id du request',
  `beatmapId` int(6) NOT NULL,
  `beatmapSetId` int(10) NOT NULL,
  `OFN` longtext NOT NULL COMMENT 'osu file name',
  `BFN` longtext NOT NULL COMMENT 'Beatmap File Name',
  `md5` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date de depot du replay',
  `duration` int(11) NOT NULL COMMENT 'Durée du replay',
  `priority` smallint(1) NOT NULL DEFAULT '0' COMMENT 'priorite',
  `persistance` int(11) NOT NULL DEFAULT '0',
  `currentStatut` int(11) DEFAULT '0' COMMENT 'Processing state',
  `playerId` int(10) NOT NULL,
  `playMod` int(11) DEFAULT NULL COMMENT 'Osu, Mania, CTB, Taiko',
  `binaryMods` int(28) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `verfIds`
--

CREATE TABLE `verfIds` (
  `userId` int(11) NOT NULL,
  `accountVerfId` varchar(100) DEFAULT NULL,
  `emailVerfId` varchar(100) DEFAULT NULL,
  `deleteVerfId` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Index pour la table `accounts_client`
--
ALTER TABLE `accounts_client`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Index pour la table `playerlist`
--
ALTER TABLE `playerlist`
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Index pour la table `replaylist`
--
ALTER TABLE `replaylist`
  ADD UNIQUE KEY `replayId` (`replayId`);

--
-- Index pour la table `replaystats`
--
ALTER TABLE `replaystats`
  ADD PRIMARY KEY (`replayId`);

--
-- Index pour la table `requestlist`
--
ALTER TABLE `requestlist`
  ADD UNIQUE KEY `replayId` (`replayId`);
ALTER TABLE `requestlist` ADD FULLTEXT KEY `OFN` (`OFN`);

--
-- Index pour la table `verfIds`
--
ALTER TABLE `verfIds`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userId` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
