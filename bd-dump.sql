-- ============================================================
-- dump.sql - Structure + Données de la bibliothèque (saebiblio)
-- Base : saebiblio | Charset : utf8mb4
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- SUPPRESSION DES TABLES EXISTANTES
-- ============================================================
DROP TABLE IF EXISTS `livre_auteur`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `emprunt`;
DROP TABLE IF EXISTS `livre`;
DROP TABLE IF EXISTS `auteur`;
DROP TABLE IF EXISTS `categorie`;
DROP TABLE IF EXISTS `adherent`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `doctrine_migration_versions`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- STRUCTURE DES TABLES
-- ============================================================

CREATE TABLE `utilisateur` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `roles` JSON NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    UNIQUE INDEX `UNIQ_IDENTIFIER_EMAIL` (`email`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `adherent` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `date_naissance` DATE DEFAULT NULL,
    `num_tel` VARCHAR(20) DEFAULT NULL,
    `adresse_postale` VARCHAR(255) DEFAULT NULL,
    `date_adhesion` DATETIME NOT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `actif` TINYINT(1) NOT NULL,
    UNIQUE INDEX `UNIQ_ADHERENT_EMAIL` (`email`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `auteur` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `date_naissance` DATE DEFAULT NULL,
    `date_deces` DATE DEFAULT NULL,
    `nationalite` VARCHAR(100) DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `description` LONGTEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `categorie` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `description` LONGTEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `livre` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `titre` VARCHAR(255) NOT NULL,
    `isbn` VARCHAR(13) NOT NULL,
    `resume` LONGTEXT DEFAULT NULL,
    `langue` VARCHAR(50) NOT NULL,
    `date_sortie` DATE NOT NULL,
    `photo_couverture` VARCHAR(255) DEFAULT NULL,
    `disponible` TINYINT(1) NOT NULL,
    `categorie_id` INT NOT NULL,
    UNIQUE INDEX `UNIQ_AC634F99CC1CF4E6` (`isbn`),
    INDEX `IDX_AC634F99BCF5E72D` (`categorie_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `livre_auteur` (
    `livre_id` INT NOT NULL,
    `auteur_id` INT NOT NULL,
    INDEX `IDX_A11876B537D925CB` (`livre_id`),
    INDEX `IDX_A11876B560BB6FE6` (`auteur_id`),
    PRIMARY KEY (`livre_id`, `auteur_id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `emprunt` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `date_emprunt` DATETIME NOT NULL,
    `date_retour_prevue` DATE NOT NULL,
    `date_retour_effective` DATETIME DEFAULT NULL,
    `adherent_id` INT NOT NULL,
    `livre_id` INT NOT NULL,
    INDEX `IDX_364071D725F06C53` (`adherent_id`),
    INDEX `IDX_364071D737D925CB` (`livre_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `reservation` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `date_reservation` DATETIME NOT NULL,
    `adherent_id` INT NOT NULL,
    `livre_id` INT NOT NULL,
    INDEX `IDX_42C8495525F06C53` (`adherent_id`),
    INDEX `IDX_42C8495537D925CB` (`livre_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4;

CREATE TABLE `doctrine_migration_versions` (
    `version` VARCHAR(191) NOT NULL,
    `executed_at` DATETIME DEFAULT NULL,
    `execution_time` INT DEFAULT NULL,
    PRIMARY KEY (`version`)
) DEFAULT CHARACTER SET utf8mb4;

-- ============================================================
-- CONTRAINTES DE CLES ETRANGERES
-- ============================================================
ALTER TABLE `livre` ADD CONSTRAINT `FK_AC634F99BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);
ALTER TABLE `livre_auteur` ADD CONSTRAINT `FK_A11876B537D925CB` FOREIGN KEY (`livre_id`) REFERENCES `livre` (`id`) ON DELETE CASCADE;
ALTER TABLE `livre_auteur` ADD CONSTRAINT `FK_A11876B560BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `auteur` (`id`) ON DELETE CASCADE;
ALTER TABLE `emprunt` ADD CONSTRAINT `FK_364071D725F06C53` FOREIGN KEY (`adherent_id`) REFERENCES `adherent` (`id`);
ALTER TABLE `emprunt` ADD CONSTRAINT `FK_364071D737D925CB` FOREIGN KEY (`livre_id`) REFERENCES `livre` (`id`);
ALTER TABLE `reservation` ADD CONSTRAINT `FK_42C8495525F06C53` FOREIGN KEY (`adherent_id`) REFERENCES `adherent` (`id`);
ALTER TABLE `reservation` ADD CONSTRAINT `FK_42C8495537D925CB` FOREIGN KEY (`livre_id`) REFERENCES `livre` (`id`);

-- ============================================================
-- MIGRATION DOCTRINE
-- ============================================================
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260309102411', NOW(), 100);

-- ============================================================
-- UTILISATEURS (back-office)
-- Mot de passe : admin / biblio (bcrypt)
-- ============================================================
INSERT INTO `utilisateur` (`id`, `email`, `roles`, `password`, `nom`, `prenom`) VALUES
(1, 'admin@biblio.fr', '["ROLE_ADMIN"]', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Martin', 'Sophie'),
(2, 'biblio@biblio.fr', '["ROLE_BIBLIO"]', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Dupont', 'Jean');

-- ============================================================
-- ADHERENTS
-- Mot de passe : adherent (bcrypt)
-- ============================================================
INSERT INTO `adherent` (`id`, `email`, `password`, `nom`, `prenom`, `date_naissance`, `num_tel`, `adresse_postale`, `date_adhesion`, `photo`, `actif`) VALUES
(1, 'pierre.durand@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Durand', 'Pierre', '1990-05-15', '0601020304', '12 rue des Lilas, 31000 Toulouse', '2026-02-01 10:00:00', NULL, 1),
(2, 'marie.bernard@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Bernard', 'Marie', '1985-08-22', '0605060708', '5 avenue Jean Jaurès, 31500 Toulouse', '2026-01-15 10:00:00', NULL, 1),
(3, 'luc.petit@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Petit', 'Luc', '1995-02-10', '0611223344', '8 place du Capitole, 31000 Toulouse', '2025-12-20 10:00:00', NULL, 1),
(4, 'emma.moreau@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Moreau', 'Emma', '1992-11-03', '0655667788', '22 rue Alsace-Lorraine, 31000 Toulouse', '2025-11-10 10:00:00', NULL, 1),
(5, 'julien.garcia@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Garcia', 'Julien', '1988-07-28', '0699887766', '3 allée des Demoiselles, 31400 Toulouse', '2025-10-01 10:00:00', NULL, 1),
(6, 'claire.roux@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Roux', 'Claire', '1993-04-17', '0633445566', '15 rue de Metz, 31000 Toulouse', '2025-09-05 10:00:00', NULL, 1),
(7, 'thomas.leroy@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Leroy', 'Thomas', '1991-01-30', '0677889900', '7 boulevard de Strasbourg, 31000 Toulouse', '2025-08-15 10:00:00', NULL, 1),
(8, 'sarah.simon@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Simon', 'Sarah', '1997-09-12', '0612345678', '45 route de Blagnac, 31700 Blagnac', '2025-07-20 10:00:00', NULL, 1),
(9, 'nicolas.laurent@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Laurent', 'Nicolas', '1986-12-05', '0698765432', '11 chemin de Lapujade, 31200 Toulouse', '2025-06-10 10:00:00', NULL, 1),
(10, 'camille.michel@email.fr', '$2y$13$8CI4.qTcHmabAOGAh26X..2TO/V6faUSqoBRNQrmpmCAIkogv2Raq', 'Michel', 'Camille', '1994-06-21', '0654321098', '28 rue du Taur, 31000 Toulouse', '2025-05-01 10:00:00', NULL, 0);

-- ============================================================
-- CATEGORIES
-- ============================================================
INSERT INTO `categorie` (`id`, `nom`, `description`) VALUES
(1, 'Roman', 'Oeuvres de fiction narrative en prose'),
(2, 'Science-Fiction', 'Romans explorant des mondes futuristes et technologies imaginaires'),
(3, 'Policier', 'Romans d''enquêtes criminelles et de suspense'),
(4, 'Histoire', 'Romans historiques et récits du passé'),
(5, 'Fantasy', 'Mondes imaginaires, magie et créatures fantastiques'),
(6, 'Philosophie', 'Oeuvres de réflexion philosophique'),
(7, 'Théâtre', 'Pièces de théâtre et oeuvres dramatiques'),
(8, 'Poésie', 'Recueils de poèmes et vers');

-- ============================================================
-- AUTEURS
-- ============================================================
INSERT INTO `auteur` (`id`, `nom`, `prenom`, `date_naissance`, `date_deces`, `nationalite`, `description`, `photo`) VALUES
(1,  'Hugo',          'Victor',       '1802-02-26', '1885-05-22', 'Française',  'Ecrivain, poète et dramaturge français du XIXe siècle. Auteur des Misérables et de Notre-Dame de Paris.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Victor_Hugo_by_%C3%89tienne_Carjat_1876_-_full.jpg/200px-Victor_Hugo_by_%C3%89tienne_Carjat_1876_-_full.jpg'),
(2,  'Zola',          'Emile',        '1840-04-02', '1902-09-29', 'Française',  'Ecrivain et journaliste français, chef de file du naturalisme. Auteur du cycle des Rougon-Macquart.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/%C3%89mile_Zola_2.jpg/200px-%C3%89mile_Zola_2.jpg'),
(3,  'Asimov',        'Isaac',        '1920-01-02', '1992-04-06', 'Américaine', 'Ecrivain américain d''origine russe, auteur majeur de science-fiction. Créateur des Lois de la Robotique.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Isaac.Asimov01.jpg/200px-Isaac.Asimov01.jpg'),
(4,  'Christie',      'Agatha',       '1890-09-15', '1976-01-12', 'Britannique','Romancière britannique, reine du roman policier. Créatrice d''Hercule Poirot et Miss Marple.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Agatha_Christie.png/200px-Agatha_Christie.png'),
(5,  'Tolkien',       'J.R.R.',       '1892-01-03', '1973-09-02', 'Britannique','Ecrivain et philologue britannique, auteur du Seigneur des Anneaux et du Hobbit.', 'https://upload.wikimedia.org/wikipedia/commons/3/35/JRR_Tolkien.jpg'),
(6,  'Camus',         'Albert',       '1913-11-07', '1960-01-04', 'Française',  'Ecrivain, philosophe et journaliste français. Prix Nobel de littérature 1957.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Albert_Camus%2C_gagnant_de_prix_Nobel%2C_portrait_en_buste.jpg/200px-Albert_Camus%2C_gagnant_de_prix_Nobel%2C_portrait_en_buste.jpg'),
(7,  'Dumas',         'Alexandre',    '1802-07-24', '1870-12-05', 'Française',  'Ecrivain français, auteur de romans historiques populaires comme Les Trois Mousquetaires.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Alexander_Dumas_p%C3%A8re_par_Nadar_-_Google_Art_Project.jpg/200px-Alexander_Dumas_p%C3%A8re_par_Nadar_-_Google_Art_Project.jpg'),
(8,  'Orwell',        'George',       '1903-06-25', '1950-01-21', 'Britannique','Ecrivain et journaliste britannique. Auteur de 1984 et La Ferme des animaux.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/George_Orwell_press_photo.jpg/200px-George_Orwell_press_photo.jpg'),
(9,  'Saint-Exupéry', 'Antoine de',   '1900-06-29', '1944-07-31', 'Française',  'Aviateur et écrivain français, auteur du Petit Prince.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/58/Antoine_de_Saint-Exup%C3%A9ry.jpg/200px-Antoine_de_Saint-Exup%C3%A9ry.jpg'),
(10, 'Verne',         'Jules',        '1828-02-08', '1905-03-24', 'Française',  'Ecrivain français, pionnier de la science-fiction. Auteur de Vingt mille lieues sous les mers.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/75/F%C3%A9lix_Nadar_1820-1910_portraits_Jules_Verne.jpg/200px-F%C3%A9lix_Nadar_1820-1910_portraits_Jules_Verne.jpg'),
(11, 'Molière',       '',             '1622-01-15', '1673-02-17', 'Française',  'Dramaturge et comédien français du XVIIe siècle, auteur du Misanthrope et de Tartuffe.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Moliere_-_Nicolas_Mignard.jpg/200px-Moliere_-_Nicolas_Mignard.jpg'),
(12, 'Baudelaire',    'Charles',      '1821-04-09', '1867-08-31', 'Française',  'Poète français du XIXe siècle, auteur des Fleurs du mal.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/%C3%89tienne_Carjat%2C_Portrait_of_Charles_Baudelaire%2C_circa_1862.jpg/200px-%C3%89tienne_Carjat%2C_Portrait_of_Charles_Baudelaire%2C_circa_1862.jpg'),
(13, 'Lovecraft',     'H.P.',         '1890-08-20', '1937-03-15', 'Américaine', 'Ecrivain américain, maître de l''horreur cosmique et du fantastique.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/10/H._P._Lovecraft%2C_June_1934.jpg/200px-H._P._Lovecraft%2C_June_1934.jpg');

-- ============================================================
-- LIVRES (avec couvertures Open Library)
-- ============================================================
INSERT INTO `livre` (`id`, `titre`, `isbn`, `resume`, `langue`, `date_sortie`, `photo_couverture`, `disponible`, `categorie_id`) VALUES

-- Victor Hugo
(1,  'Les Misérables',                '9782070409228', 'L''histoire de Jean Valjean, ancien forçat, dans la France du XIXe siècle. Un chef-d''oeuvre de la littérature française qui explore la misère, l''injustice et la rédemption.', 'Français', '1862-04-03', 'https://covers.openlibrary.org/b/isbn/9782070409228-L.jpg', 0, 1),
(2,  'Notre-Dame de Paris',           '9782070411429', 'L''histoire de Quasimodo et Esmeralda dans le Paris médiéval. Un roman historique qui met en scène la cathédrale comme personnage central.', 'Français', '1831-03-16', 'https://covers.openlibrary.org/b/isbn/9782070411429-L.jpg', 1, 1),

-- Emile Zola
(3,  'Germinal',                      '9782070360024', 'La vie des mineurs du nord de la France au XIXe siècle. Un roman puissant sur la lutte des classes et les conditions de travail.', 'Français', '1885-03-01', 'https://covers.openlibrary.org/b/isbn/9782070360024-L.jpg', 0, 1),
(4,  'L''Assommoir',                  '9782070409914', 'L''histoire de Gervaise Macquart dans le Paris ouvrier. Septième volume du cycle des Rougon-Macquart.', 'Français', '1877-01-01', 'https://covers.openlibrary.org/b/isbn/9782070409914-L.jpg', 1, 1),
(5,  'Nana',                          '9782070414000', 'L''ascension et la chute d''une courtisane parisienne. Neuvième volume du cycle des Rougon-Macquart.', 'Français', '1880-02-01', 'https://covers.openlibrary.org/b/isbn/9782070414000-L.jpg', 1, 1),

-- Isaac Asimov
(6,  'Fondation',                     '9782070360536', 'Premier tome du Cycle de Fondation. Hari Seldon prédit l''effondrement de l''Empire galactique et crée la Fondation pour préserver le savoir.', 'Français', '1951-05-01', 'https://covers.openlibrary.org/b/isbn/9782070360536-L.jpg', 0, 2),
(7,  'I, Robot',                      '9780553294385', 'Recueil de nouvelles sur les robots et les trois lois de la robotique.', 'Anglais', '1950-12-02', 'https://covers.openlibrary.org/b/isbn/9780553294385-L.jpg', 1, 2),
(8,  'Foundation and Empire',         '9780553293371', 'Second tome du Cycle de Fondation. La Fondation fait face à la menace du Mulet.', 'Anglais', '1952-06-01', 'https://covers.openlibrary.org/b/isbn/9780553293371-L.jpg', 1, 2),

-- Agatha Christie
(9,  'Dix petits nègres',             '9782253010050', 'Dix personnes sont invitées sur une île isolée par un mystérieux inconnu. Un à un, ils disparaissent.', 'Français', '1939-11-06', 'https://covers.openlibrary.org/b/isbn/9782253010050-L.jpg', 0, 3),
(10, 'Le Crime de l''Orient-Express', '9782253004721', 'Un meurtre à bord du célèbre train. Hercule Poirot mène l''enquête parmi les passagers.', 'Français', '1934-01-01', 'https://covers.openlibrary.org/b/isbn/9782253004721-L.jpg', 1, 3),
(11, 'Murder on the Nile',            '9780062073556', 'A Hercule Poirot mystery set during a luxurious cruise on the Nile.', 'Anglais', '1937-11-01', 'https://covers.openlibrary.org/b/isbn/9780062073556-L.jpg', 1, 3),

-- J.R.R. Tolkien
(12, 'Le Seigneur des Anneaux',       '9782267021998', 'La quête de Frodon pour détruire l''Anneau Unique en Terre du Milieu.', 'Français', '1954-07-29', 'https://covers.openlibrary.org/b/isbn/9782267021998-L.jpg', 0, 5),
(13, 'Le Hobbit',                     '9782267028034', 'L''aventure de Bilbo Baggins avec les nains et le dragon Smaug.', 'Français', '1937-09-21', 'https://covers.openlibrary.org/b/isbn/9782267028034-L.jpg', 0, 5),
(14, 'Le Silmarillion',               '9782267013078', 'Recueil de mythes et légendes de la Terre du Milieu, depuis la création du monde.', 'Français', '1977-09-15', 'https://covers.openlibrary.org/b/isbn/9782267013078-L.jpg', 1, 5),

-- Albert Camus
(15, 'L''Etranger',                   '9782070360048', 'Meursault, un homme indifférent au monde qui l''entoure, commet un meurtre absurde sur une plage d''Alger.', 'Français', '1942-06-15', 'https://covers.openlibrary.org/b/isbn/9782070360048-L.jpg', 1, 1),
(16, 'La Peste',                      '9782070360420', 'Une épidémie de peste frappe la ville d''Oran en Algérie. Le docteur Rieux lutte contre le fléau.', 'Français', '1947-06-10', 'https://covers.openlibrary.org/b/isbn/9782070360420-L.jpg', 1, 1),
(17, 'La Chute',                      '9782070360659', 'Un monologue de Jean-Baptiste Clamence, ancien avocat, dans un bar d''Amsterdam.', 'Français', '1956-05-01', 'https://covers.openlibrary.org/b/isbn/9782070360659-L.jpg', 1, 6),

-- Alexandre Dumas
(18, 'Les Trois Mousquetaires',       '9782070409322', 'Les aventures de d''Artagnan et de ses compagnons Athos, Porthos et Aramis.', 'Français', '1844-03-14', 'https://covers.openlibrary.org/b/isbn/9782070409322-L.jpg', 0, 4),
(19, 'Le Comte de Monte-Cristo',      '9782070405312', 'L''histoire d''Edmond Dantès, injustement emprisonné au château d''If, et sa vengeance.', 'Français', '1844-08-28', 'https://covers.openlibrary.org/b/isbn/9782070405312-L.jpg', 1, 4),
(20, 'Vingt Ans Après',               '9782070411818', 'Suite des Trois Mousquetaires. Vingt ans plus tard, les mousquetaires se retrouvent.', 'Français', '1845-01-21', 'https://covers.openlibrary.org/b/isbn/9782070411818-L.jpg', 1, 4),

-- George Orwell
(21, '1984',                          '9780451524935', 'Dans un futur dystopique, Winston Smith travaille au Ministère de la Vérité et commence à douter du Parti.', 'Anglais', '1949-06-08', 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg', 1, 2),
(22, 'La Ferme des animaux',          '9782070360246', 'Les animaux d''une ferme se révoltent contre leur propriétaire humain. Une fable satirique sur le totalitarisme.', 'Français', '1945-08-17', 'https://covers.openlibrary.org/b/isbn/9782070360246-L.jpg', 1, 1),

-- Antoine de Saint-Exupéry
(23, 'Le Petit Prince',               '9782070612758', 'Un aviateur en panne dans le désert rencontre un petit prince venu d''une autre planète. Un conte poétique et philosophique.', 'Français', '1943-04-06', 'https://covers.openlibrary.org/b/isbn/9782070612758-L.jpg', 1, 1),
(24, 'Vol de nuit',                   '9782070360109', 'Le récit d''un vol postal nocturne au-dessus de l''Amérique du Sud. Une méditation sur le courage et le devoir.', 'Français', '1931-12-01', 'https://covers.openlibrary.org/b/isbn/9782070360109-L.jpg', 1, 1),

-- Jules Verne
(25, 'Vingt mille lieues sous les mers', '9782070424832', 'Le professeur Aronnax embarque à bord du Nautilus, le sous-marin du capitaine Nemo.', 'Français', '1870-06-20', 'https://covers.openlibrary.org/b/isbn/9782070424832-L.jpg', 1, 2),
(26, 'Le Tour du monde en 80 jours',    '9782070413195', 'Phileas Fogg parie qu''il peut faire le tour du monde en 80 jours. Une course contre la montre.', 'Français', '1873-01-30', 'https://covers.openlibrary.org/b/isbn/9782070413195-L.jpg', 1, 4),
(27, 'Voyage au centre de la Terre',    '9782070414284', 'Le professeur Lidenbrock et son neveu Axel descendent dans les entrailles de la Terre par un volcan islandais.', 'Français', '1864-11-25', 'https://covers.openlibrary.org/b/isbn/9782070414284-L.jpg', 1, 2),

-- Molière
(28, 'Le Misanthrope',                '9782070449873', 'Alceste, homme intègre, refuse toute forme d''hypocrisie sociale. Une comédie sur les moeurs de la cour.', 'Français', '1666-06-04', 'https://covers.openlibrary.org/b/isbn/9782070449873-L.jpg', 1, 7),
(29, 'Tartuffe',                      '9782070450015', 'Un faux dévot s''installe chez Orgon et manipule toute la famille grâce à son apparente piété.', 'Français', '1669-02-05', 'https://covers.openlibrary.org/b/isbn/9782070450015-L.jpg', 1, 7),

-- Baudelaire
(30, 'Les Fleurs du mal',             '9782070411788', 'Recueil de poèmes explorant la beauté dans le mal, le spleen et l''idéal.', 'Français', '1857-06-25', 'https://covers.openlibrary.org/b/isbn/9782070411788-L.jpg', 1, 8),

-- H.P. Lovecraft
(31, 'L''Appel de Cthulhu',           '9791028109246', 'Un culte voue une adoration terrifiante à une entité cosmique endormie dans les profondeurs de l''océan.', 'Français', '1928-02-01', NULL, 1, 2),
(32, 'Les Montagnes hallucinées',     '9782070419562', 'Une expédition scientifique en Antarctique découvre les ruines d''une civilisation extraterrestre.', 'Français', '1936-02-01', 'https://covers.openlibrary.org/b/isbn/9782070419562-L.jpg', 1, 2);

-- ============================================================
-- LIVRE_AUTEUR (relations many-to-many)
-- ============================================================
INSERT INTO `livre_auteur` (`livre_id`, `auteur_id`) VALUES
(1, 1), (2, 1),
(3, 2), (4, 2), (5, 2),
(6, 3), (7, 3), (8, 3),
(9, 4), (10, 4), (11, 4),
(12, 5), (13, 5), (14, 5),
(15, 6), (16, 6), (17, 6),
(18, 7), (19, 7), (20, 7),
(21, 8), (22, 8),
(23, 9), (24, 9),
(25, 10), (26, 10), (27, 10),
(28, 11), (29, 11),
(30, 12),
(31, 13), (32, 13);

-- ============================================================
-- EMPRUNTS
-- ============================================================
-- En cours
INSERT INTO `emprunt` (`id`, `adherent_id`, `livre_id`, `date_emprunt`, `date_retour_prevue`, `date_retour_effective`) VALUES
(1,  1, 1,  '2026-03-06 10:00:00', '2026-03-21', NULL),
(2,  1, 6,  '2026-03-08 10:00:00', '2026-03-23', NULL),
(3,  2, 9,  '2026-03-01 10:00:00', '2026-03-16', NULL),
(4,  3, 12, '2026-02-27 10:00:00', '2026-03-14', NULL),
-- En retard
(5,  4, 13, '2026-02-19 10:00:00', '2026-03-06', NULL),
(6,  5, 3,  '2026-02-21 10:00:00', '2026-03-08', NULL),
(7,  4, 18, '2026-02-25 10:00:00', '2026-03-12', NULL),
-- Rendus
(8,  1, 2,  '2026-02-09 10:00:00', '2026-02-24', '2026-02-23 10:00:00'),
(9,  2, 4,  '2026-01-31 10:00:00', '2026-02-14', '2026-02-13 10:00:00'),
(10, 3, 7,  '2026-02-04 10:00:00', '2026-02-19', '2026-02-17 10:00:00'),
(11, 6, 10, '2026-02-14 10:00:00', '2026-03-01', '2026-02-28 10:00:00'),
(12, 4, 13, '2026-01-25 10:00:00', '2026-02-09', '2026-02-08 10:00:00'),
(13, 7, 11, '2026-01-20 10:00:00', '2026-02-04', '2026-02-03 10:00:00'),
(14, 8, 14, '2026-02-11 10:00:00', '2026-02-26', '2026-02-25 10:00:00'),
(15, 5, 8,  '2026-01-15 10:00:00', '2026-01-30', '2026-01-28 10:00:00');

-- ============================================================
-- RESERVATIONS
-- ============================================================
INSERT INTO `reservation` (`id`, `adherent_id`, `livre_id`, `date_reservation`) VALUES
(1, 6, 1,  '2026-03-09 14:00:00'),
(2, 7, 9,  '2026-03-10 09:00:00');
