-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : hi5bu.myd.infomaniak.com
-- Généré le :  ven. 27 mars 2020 à 09:35
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

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Login` (`Login`),
  ADD KEY `FK_User_Subscription` (`SubscriptionId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `FK_User_Subscription` FOREIGN KEY (`SubscriptionId`) REFERENCES `Subscription` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
