-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 21 juil. 2024 à 19:07
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(5) NOT NULL,
  `email` varchar(200) NOT NULL,
  `adminname` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `adminname`, `password`, `created_at`) VALUES
(1, 'naruto@esp.sn', 'naruto', '$2y$10$JBykIYmCMEpY3zCPi9WlIOX4ZODxEUmWVJgBBExx6TaGCoWw4iqeG', '2024-07-13 20:49:27'),
(2, 'itachi@esp.sn', 'itachi', '$2y$10$k/4sYh6Ok.0E1WdjiRYb9O9aDm1Gi8dThsTEzEOWJfeaZKLfdtjpm', '2024-07-14 13:43:34'),
(3, 'sasuke@esp.sn', 'sasuke', '$2y$10$8c1o.06/cgZA7mynmDF6wuy93XaW7ZRm/NcxkTL5a9jQE.3tNbE4e', '2024-07-14 18:04:04');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(5) NOT NULL,
  `name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Computer_Engineering', '2024-07-13 08:36:29'),
(2, 'Chemical_and_Biology', '2024-07-13 08:36:29'),
(3, 'Civil_Engineering', '2024-07-13 08:36:29'),
(4, 'Electrical_Engineering', '2024-07-13 08:36:29'),
(5, 'Mechanical_Engineering', '2024-07-13 08:36:29'),
(6, 'Management', '2024-07-13 09:10:36');

-- --------------------------------------------------------

--
-- Structure de la table `replies`
--

CREATE TABLE `replies` (
  `id` int(5) NOT NULL,
  `reply` text,
  `user_id` int(5) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `topic_id` int(5) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `replies`
--

INSERT INTO `replies` (`id`, `reply`, `user_id`, `user_image`, `topic_id`, `user_name`, `create_at`) VALUES
(16, 'Chic ! Une question qui mélange des choux et des carottes, un calcul de trajectoire et une fusée.\r\n\r\nUn ordinateur, même primitif, n’est pas indispensable pour calculer une trajectoire Terre-Lune puisque lors des premiers vols spatiaux du programme Mercure, notamment celui d’Alan Shepard, c’est une femme, Katherine Johnson, qui a calculé à la main la trajectoire des fusées. John Glenn, qui ne faisait pas confiance aux nouveaux ordinateurs d’IBM, lui a même demandé de vérifier son vol.\r\n\r\nIls (je suppose les américains) ont atteint la Lune parce qu’ils se sont donné les moyens financiers pour y arriver, suite à une promesse ambitieuse de John Kennedy . Nul doute qu’une fois les problèmes techniques de la fusée Artemis résolus, « ils » pourront de nouveau atteindre la Lune.\r\n\r\nC’est juste une question de tremps.', 13, '3135789.png', 21, 'Mariama', '2024-07-16 22:21:20'),
(17, 'Les grains de sable, surtout marins. Magnifiques ', 12, 'gravatar.jpeg', 22, 'paul', '2024-07-16 22:28:00'),
(27, 'Sur un Ordinateur primitif comme sur Apollo du fait de ses composants énormes il est presque rendu insensible aux percussions par des rayonnement cosmiques (qui ne sont pas filtrés par l\'atmosphère terrestre), un transistor au germanium d\'antan supportait presque un trou de perceuse ou un coup de marteau sans cesser de fonctionner .\r\n', 16, 'gravatar.jpeg', 21, 'kernel', '2024-07-17 02:04:03'),
(28, 'Les différences entre un ingénieur en génie civil et un docteur en génie civil résident essentiellement dans leur niveau de formation, leurs spécialités, leurs perspectives professionnelles et leurs domaines d\'activité. Les ingénieurs en génie civil sont généralement titulaires d\'un diplôme de premier cycle ou de cycles supérieurs en génie civil, tandis que les docteurs en génie civil ont complété des études de troisième cycle jusqu\'au niveau du doctorat. Les ingénieurs sont plus axés sur la pratique, impliqués dans la conception, la construction et la gestion de projets d\'infrastructure, tandis que les docteurs se concentrent souvent sur la recherche, le développement technologique et l\'enseignement universitaire. Les ingénieurs sont souvent spécialisés dans des domaines comme la construction, la gestion de projet ou la conception, tandis que les docteurs se spécialisent davantage dans des sous-domaines spécifiques du génie civil, tels que les matériaux, les structures ou l\'environnement.', 14, '3135789.png', 23, 'Ngoné', '2024-07-17 02:17:46');

-- --------------------------------------------------------

--
-- Structure de la table `topics`
--

CREATE TABLE `topics` (
  `id` int(5) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `topics`
--

INSERT INTO `topics` (`id`, `title`, `category`, `body`, `user_name`, `user_image`, `created_at`) VALUES
(21, 'atteindre la lune avec des ordinateurs primitifs et maintenant', 'Computer_Engineering', 'Comment ont-ils pu atteindre la lune avec des ordinateurs primitifs et maintenant, avec tant de progrès, vous ne pouvez plus ?', 'paul', 'gravatar.jpeg', '2024-07-16 22:04:30'),
(22, 'les choses intéressantes à regarder au microscope', 'Chemical_and_Biology', 'Quelles sont les choses intéressantes à regarder au microscope ?', 'Mariama', '3135789.png', '2024-07-16 22:25:34'),
(23, ' différences entre un ingénieur en génie civil et un docteur en génie civil', 'Civil_Engineering', 'Quelles sont les différences entre un ingénieur en génie civil et un docteur en génie civil ?', 'kernel', 'gravatar.jpeg', '2024-07-17 02:05:20');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `about` text NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `about`, `avatar`, `created_at`) VALUES
(12, 'paul', 'paul@esp.sn', 'paul', '$2y$10$.fmNVhopsVyJTbMFZAxMuOFD5PXyMCaym2//vGARipw5syCxPVk8C', 'Mussum Ipsum, cacilds vidis litro abertis. Quem num gosta di mé, boa gentis num é.Copo furadis é disculpa de bebadis, arcu quam euismod magna.Admodum accumsan disputationi eu sit. Vide electram sadipscing et per.Diuretics paradis num copo é motivis de denguis.\r\n', 'gravatar.jpeg', '2024-07-16 22:01:49'),
(13, 'mariama', 'mariama@esp.sn', 'Mariama', '$2y$10$8HgkHEeQdg1SsIwrAuOpYe16xAmL2SOJrjbZmbSdgSmIjw6YaZxxu', 'Mussum Ipsum, cacilds vidis litro abertis. Quem num gosta di mé, boa gentis num é.Copo furadis é disculpa de bebadis, arcu quam euismod magna.Admodum accumsan disputationi eu sit. Vide electram sadipscing et per.Diuretics paradis num copo é motivis de denguis.\r\n', '3135789.png', '2024-07-16 22:18:22'),
(14, 'ngone', 'ngone@esp.sn', 'Ngoné', '$2y$10$oBEMzmcGRFUkDn3.nK./C.wUsNvi0lbfXgD8wZlAYv1V3d2TGLiIe', 'Mussum Ipsum, cacilds vidis litro abertis. Quem num gosta di mé, boa gentis num é.Copo furadis é disculpa de bebadis, arcu quam euismod magna.Admodum accumsan disputationi eu sit. Vide electram sadipscing et per.Diuretics paradis num copo é motivis de denguis.\r\n', '3135789.png', '2024-07-16 22:29:21'),
(15, 'aissatou', 'aissatou@esp.sn', 'Aissatou', '$2y$10$IIXyFKTyGwBVXvutOHdvde38IIhj1P/2EzRUo.erZHs4EQ1P.EMJG', 'Mussum Ipsum, cacilds vidis litro abertis. Quem num gosta di mé, boa gentis num é.Copo furadis é disculpa de bebadis, arcu quam euismod magna.Admodum accumsan disputationi eu sit. Vide electram sadipscing et per.Diuretics paradis num copo é motivis de denguis.\r\n', '3135789.png', '2024-07-17 00:45:22'),
(16, 'kernel', 'kernel@esp.sn', 'kernel', '$2y$10$pS6q3TckcLJS9.UeQcQ3xunZRZUdx2YxmMcHpBodswl6fxHEKEdmS', 'Mussum Ipsum, cacilds vidis litro abertis. Quem num gosta di mé, boa gentis num é.Copo furadis é disculpa de bebadis, arcu quam euismod magna.Admodum accumsan disputationi eu sit. Vide electram sadipscing et per.Diuretics paradis num copo é motivis de denguis.\r\n', 'gravatar.jpeg', '2024-07-17 01:59:28'),
(17, 'ndour', 'ndour@esp.sn', 'ndour', '$2y$10$ThGAbLI7RrcNBJ0a02tufuUVfV7KbxxnHZStGYdukvwI8Z6fwytNG', 'Mussum Ipsum, cacilds vidis litro abertis. Suco de cevadiss deixa as pessoas mais interessantis.Quem manda na minha terra sou euzis!Sapien in monti palavris qui num significa nadis i pareci latim.Quem num gosta di mé, boa gentis num é.\r\n', 'gravatar.jpeg', '2024-07-21 19:05:21');

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `vote_type` enum('up','down') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `topic_id`, `vote_type`, `created_at`) VALUES
(8, 12, 21, 'up', '2024-07-16 22:11:40'),
(9, 13, 21, 'up', '2024-07-16 22:22:00'),
(10, 13, 22, 'up', '2024-07-16 22:26:00'),
(11, 14, 22, 'up', '2024-07-16 22:30:52'),
(12, 14, 21, 'up', '2024-07-16 22:31:01'),
(13, 16, 22, 'up', '2024-07-17 02:05:46'),
(14, 16, 21, 'down', '2024-07-17 02:05:54'),
(15, 16, 23, 'up', '2024-07-17 02:06:02'),
(16, 15, 22, 'up', '2024-07-17 02:18:46');

-- --------------------------------------------------------

--
-- Structure de la table `votesReplies`
--

CREATE TABLE `votesReplies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `vote_type` enum('up','down') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `votesReplies`
--

INSERT INTO `votesReplies` (`id`, `user_id`, `reply_id`, `vote_type`, `created_at`) VALUES
(6, 13, 16, 'up', '2024-07-16 22:22:15'),
(7, 12, 16, 'down', '2024-07-16 22:26:26'),
(8, 12, 17, 'up', '2024-07-16 22:28:08'),
(9, 14, 17, 'down', '2024-07-16 22:30:50'),
(10, 14, 16, 'up', '2024-07-16 22:34:35'),
(21, 15, 16, 'up', '2024-07-17 00:58:02'),
(30, 16, 17, 'up', '2024-07-17 02:05:39'),
(31, 14, 28, 'up', '2024-07-17 02:17:53');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKlej8qoe1wpmuh15xsdsyhl70m` (`topic_id`);

--
-- Index pour la table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `votesReplies`
--
ALTER TABLE `votesReplies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`reply_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `votesReplies`
--
ALTER TABLE `votesReplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `FKlej8qoe1wpmuh15xsdsyhl70m` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
