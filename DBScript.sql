-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : hi5bu.myd.infomaniak.com
-- Généré le :  jeu. 26 mars 2020 à 15:34
-- Version du serveur :  5.7.27-log
-- Version de PHP :  7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `hi5bu_infinite_skills`
--

-- --------------------------------------------------------

--
-- Structure de la table `Comment`
--

CREATE TABLE `Comment` (
  `Id` int(11) NOT NULL,
  `VideoId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Follow`
--

CREATE TABLE `Follow` (
  `UserId` int(11) NOT NULL,
  `CreatorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `See`
--

CREATE TABLE `See` (
  `UserId` int(11) NOT NULL,
  `VideoId` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Subscription`
--

CREATE TABLE `Subscription` (
  `Id` int(1) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Price` decimal(3,2) NOT NULL DEFAULT '0.00',
  `Offer` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Subscription`
--

INSERT INTO `Subscription` (`Id`, `Name`, `Price`, `Offer`) VALUES
(1, 'qdqdsqds', '0.00', '2020-03-27');

-- --------------------------------------------------------

--
-- Structure de la table `Theme`
--

CREATE TABLE `Theme` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Theme`
--

INSERT INTO `Theme` (`Id`, `Name`, `Description`) VALUES
(1, 'Animals', 'Animals'),
(2, 'Food', 'Food'),
(3, 'Sport', 'Sport');

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `Id` int(11) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `Login` varchar(25) NOT NULL,
  `Password` text NOT NULL,
  `InscriptionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ExpirationDate` datetime NOT NULL,
  `SubscriptionId` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`Id`, `Name`, `Mail`, `Login`, `Password`, `InscriptionDate`, `ExpirationDate`, `SubscriptionId`) VALUES
(2, 'User 1', 'mdr', 'User1', 'User1', '2020-03-26 15:31:23', '2020-03-26 15:31:23', 1);

-- --------------------------------------------------------

--
-- Structure de la table `UserLike`
--

CREATE TABLE `UserLike` (
  `Id` int(11) NOT NULL,
  `VideoId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `UserOwn`
--

CREATE TABLE `UserOwn` (
  `Id` int(11) NOT NULL,
  `VideoId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `UserTheme`
--

CREATE TABLE `UserTheme` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `ThemeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Video`
--

CREATE TABLE `Video` (
  `Id` int(11) NOT NULL,
  `OwnerId` int(11) NOT NULL,
  `ThemeId` int(11) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Description` text NOT NULL,
  `Publication` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Price` int(11) NOT NULL,
  `Views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `Url` text NOT NULL,
  `Thumbnail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_Video` (`VideoId`),
  ADD KEY `FK_Owner` (`UserId`);

--
-- Index pour la table `Follow`
--
ALTER TABLE `Follow`
  ADD PRIMARY KEY (`UserId`,`CreatorId`),
  ADD KEY `FK_Follow_Creator` (`CreatorId`);

--
-- Index pour la table `See`
--
ALTER TABLE `See`
  ADD PRIMARY KEY (`UserId`,`VideoId`),
  ADD KEY `FK_See_Video` (`VideoId`);

--
-- Index pour la table `Subscription`
--
ALTER TABLE `Subscription`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `Theme`
--
ALTER TABLE `Theme`
  ADD PRIMARY KEY (`Id`);

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Login` (`Login`),
  ADD KEY `FK_User_Subscription` (`SubscriptionId`);

--
-- Index pour la table `UserLike`
--
ALTER TABLE `UserLike`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `FK_UserLike_User` (`UserId`),
  ADD KEY `FK_UserLike_Video` (`VideoId`);

--
-- Index pour la table `UserOwn`
--
ALTER TABLE `UserOwn`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_UserOwn_Video` (`VideoId`),
  ADD KEY `FK_UserOwn_User` (`UserId`);

--
-- Index pour la table `UserTheme`
--
ALTER TABLE `UserTheme`
  ADD KEY `FK_UserTheme_User` (`UserId`),
  ADD KEY `FK_UserTheme_Theme` (`ThemeId`);

--
-- Index pour la table `Video`
--
ALTER TABLE `Video`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UserId` (`OwnerId`),
  ADD KEY `FK_Video_Theme` (`ThemeId`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Subscription`
--
ALTER TABLE `Subscription`
  MODIFY `Id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Theme`
--
ALTER TABLE `Theme`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `UserLike`
--
ALTER TABLE `UserLike`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `UserOwn`
--
ALTER TABLE `UserOwn`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Video`
--
ALTER TABLE `Video`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `FK_Owner` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `FK_Video` FOREIGN KEY (`VideoId`) REFERENCES `Video` (`Id`);

--
-- Contraintes pour la table `Follow`
--
ALTER TABLE `Follow`
  ADD CONSTRAINT `FK_Follow_Creator` FOREIGN KEY (`CreatorId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `FK_Follow_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`);

--
-- Contraintes pour la table `See`
--
ALTER TABLE `See`
  ADD CONSTRAINT `FK_See_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `FK_See_Video` FOREIGN KEY (`VideoId`) REFERENCES `Video` (`Id`);

--
-- Contraintes pour la table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `FK_User_Subscription` FOREIGN KEY (`SubscriptionId`) REFERENCES `Subscription` (`Id`);

--
-- Contraintes pour la table `UserLike`
--
ALTER TABLE `UserLike`
  ADD CONSTRAINT `FK_UserLike_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `FK_UserLike_Video` FOREIGN KEY (`VideoId`) REFERENCES `Video` (`Id`);

--
-- Contraintes pour la table `UserOwn`
--
ALTER TABLE `UserOwn`
  ADD CONSTRAINT `FK_UserOwn_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  ADD CONSTRAINT `FK_UserOwn_Video` FOREIGN KEY (`VideoId`) REFERENCES `Video` (`Id`);

--
-- Contraintes pour la table `UserTheme`
--
ALTER TABLE `UserTheme`
  ADD CONSTRAINT `FK_UserTheme_Theme` FOREIGN KEY (`ThemeId`) REFERENCES `Theme` (`Id`),
  ADD CONSTRAINT `FK_UserTheme_User` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`);

--
-- Contraintes pour la table `Video`
--
ALTER TABLE `Video`
  ADD CONSTRAINT `FK_Theme` FOREIGN KEY (`ThemeId`) REFERENCES `Theme` (`Id`),
  ADD CONSTRAINT `Video_ibfk_1` FOREIGN KEY (`OwnerId`) REFERENCES `User` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
