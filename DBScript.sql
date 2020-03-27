-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : hi5bu.myd.infomaniak.com
-- Généré le :  ven. 27 mars 2020 à 09:40
-- Version du serveur :  5.7.27-log
-- Version de PHP :  7.2.29

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
  `SubscriptionId` int(1) NOT NULL DEFAULT '0',
  `Avatar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`Id`, `Name`, `Mail`, `Login`, `Password`, `InscriptionDate`, `ExpirationDate`, `SubscriptionId`, `Avatar`) VALUES
(2, 'Foxy', 'mdr', 'Foxy', 'bidon', '2020-03-26 15:31:23', '2020-03-26 15:31:23', 1, 'https://media.discordapp.net/attachments/641401938235097110/691200266363469854/0322.jpg'),
(3, 'Steakatcheur', 'mdr', 'Steakatcheur', 'bidon', '2020-03-26 15:37:55', '2020-03-26 15:37:55', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHkC1tgaHwI7VcKVaPR2sZGyljUhxTrvUxZMpy9CRQAnb-Lebi&s'),
(4, 'Tybo la Chaype', 'mdr', 'TyboLaChaype', 'bidon', '2020-03-26 15:37:55', '2020-03-26 15:37:55', 1, 'https://www.arcueil.fr/wp-content/uploads/2018/04/sports-arcueil.jpg');

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
  `Price` int(11) NOT NULL DEFAULT '0',
  `Views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `Url` text NOT NULL,
  `Thumbnail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Video`
--

INSERT INTO `Video` (`Id`, `OwnerId`, `ThemeId`, `Name`, `Description`, `Publication`, `Price`, `Views`, `Url`, `Thumbnail`) VALUES
(1, 2, 1, 'Animal #1', 'Fluffy', '2020-03-26 15:35:54', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/691200266363469854/0322.jpg'),
(2, 2, 1, 'Animal #2', 'Fluffy too', '2020-03-26 15:35:54', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/686125751678140446/0308.jpg'),
(3, 2, 1, 'Animal #3', 'Fluffy again', '2020-03-26 15:35:54', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/684290184799453187/0303.jpg?width=541&height=677'),
(4, 2, 1, 'Animal #4', 'Fluffy once more', '2020-03-26 15:42:07', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/692273421433962566/0325.jpg?width=677&height=677'),
(5, 2, 1, 'Animal #5', 'Fluffy for ever', '2020-03-26 15:42:07', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/691911901268934686/0324.jpg?width=483&height=677'),
(6, 2, 1, 'Animal #6', 'Fluffy as always', '2020-03-26 15:42:07', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/691911774588502077/0323.jpg?width=508&height=677'),
(7, 2, 1, 'Animal #7', 'Fluffy yay', '2020-03-26 15:42:07', 0, 0, '', 'https://media.discordapp.net/attachments/641401938235097110/690850832870146068/0321.jpg?width=761&height=677'),
(8, 3, 2, 'Food #1', 'Yum', '2020-03-26 15:42:07', 0, 0, '', 'https://d1doqjmisr497k.cloudfront.net/-/media/ducrosfr-2016/recipes/2000/steak_au_vin_rouge_et_aux_echalotes_2000.jpg?vd=20180616T221321Z&ir=1&width=885&height=498&crop=auto&quality=75&speed=0&hash=53CBB1CD5F1F493DBFE5923FA8F6D7A79AA4CD32'),
(9, 3, 2, 'Food #2', 'Yum', '2020-03-26 15:46:59', 0, 0, '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKR_pTc4kCDEF92VGbhk3KDcPzgo8tXhQngMibEye88Ox8FYrR&s'),
(10, 3, 2, 'Food #3', 'Yum', '2020-03-26 15:46:59', 0, 0, '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHkC1tgaHwI7VcKVaPR2sZGyljUhxTrvUxZMpy9CRQAnb-Lebi&s'),
(11, 4, 3, 'Sport #1', 'Heavy', '2020-03-26 15:46:59', 0, 0, '', 'https://www.arcueil.fr/wp-content/uploads/2018/04/sports-arcueil.jpg'),
(12, 4, 3, 'Sport #2', 'Speedy', '2020-03-26 15:46:59', 0, 0, '', 'https://image.shutterstock.com/image-photo/huge-multi-sports-collage-soccer-260nw-650017768.jpg');

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
