DROP TABLE IF EXISTS appartenir;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS emprunt;
DROP TABLE IF EXISTS ecrire;
DROP TABLE IF EXISTS adherent;
DROP TABLE IF EXISTS livre;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS auteur;



CREATE TABLE auteur (
  idAut           INT AUTO_INCREMENT PRIMARY KEY,
  nom             VARCHAR(120) NOT NULL,
  prenom          VARCHAR(120) NOT NULL,
  dateNaissance   DATE,
  dateDeces       DATE,
  nationalite     VARCHAR(120),
  photo           VARCHAR(120),
  description     VARCHAR(500) NULL,
  INDEX idx_auteur_nom (nom)
) ENGINE=InnoDB;


CREATE TABLE categorie (
  idCat           INT AUTO_INCREMENT PRIMARY KEY,
  nom             VARCHAR(120) NOT NULL,
  description     VARCHAR(500) NULL
) ENGINE=InnoDB;


CREATE TABLE livre (
  idLivre         INT AUTO_INCREMENT PRIMARY KEY,
  titre           VARCHAR(120) NOT NULL,
  dateSortie      DATE,
  langue           VARCHAR(120) NOT NULL,
  photoCouverture VARCHAR(120),
  INDEX idx_livre_dateSortie (dateSortie),
  INDEX idx_livre_langue (langue)
) ENGINE=InnoDB;


CREATE TABLE ecrire (
  idLivre         INT,
  idCat           INT,
  CONSTRAINT fk_ecrire_livre
    FOREIGN KEY (idLivre) REFERENCES livre(idLivre)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_ecrire_categorie
    FOREIGN KEY (idCat) REFERENCES categorie(idCat)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (idLivre, idCat)
) ENGINE=InnoDB;


CREATE TABLE adherent (
  idAdh           INT AUTO_INCREMENT PRIMARY KEY,
  dateAdhesion    DATE NOT NULL,
  nom             VARCHAR(120) NOT NULL,
  prenom          VARCHAR(120) NOT NULL,
  dateNaiss       DATE NOT NULL,
  email           VARCHAR(120) NOT NULL,
  adressePostale  VARCHAR(120) NOT NULL,
  numTel          CHAR(10) NOT NULL,
  photo           VARCHAR(120),
  /*Ajouter des INDEX*/
) ENGINE=InnoDB;


CREATE TABLE emprunt (
  idEmp           INT AUTO_INCREMENT PRIMARY KEY,
  dateEmprunt     DATE NOT NULL,
  dateRetour      DATE,
  idLivre         INT NOT NULL,
  idAdh           INT NOT NULL,
  CONSTRAINT fk_emprunt_livre
    FOREIGN KEY (idLivre) REFERENCES livre(idLivre)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_emprunt_adherent
    FOREIGN KEY (idAdh) REFERENCES categorie(idAdh)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE reservations (
  idResa           INT AUTO_INCREMENT PRIMARY KEY,
  dateResa         DATE NOT NULL,
  idLivre          INT NOT NULL,
  idAdh            INT NOT NULL,
  CONSTRAINT fk_reservations_livre
    FOREIGN KEY (idLivre) REFERENCES livre(idLivre)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_reservations_adherent
    FOREIGN KEY (idAdh) REFERENCES categorie(idAdh)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE appartenir (
  idLivre          INT,
  idCat            INT,
  CONSTRAINT fk_appartenir_livre
    FOREIGN KEY (idLivre) REFERENCES livre(idLivre)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_appartenir_categorie
    FOREIGN KEY (idCat) REFERENCES categorie(idCat)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (idLivre, idCat)
) ENGINE=InnoDB;

