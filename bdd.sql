DROP TABLE IF EXISTS Paiement;
DROP TABLE IF EXISTS MessageRequete;
DROP TABLE IF EXISTS Requete;
DROP TABLE IF EXISTS Pause;
DROP TABLE IF EXISTS LuMessageInfo;
DROP TABLE IF EXISTS MessageInfo;
DROP TABLE IF EXISTS HorairePoubelle;
DROP TABLE IF EXISTS Horaire;
DROP TABLE IF EXISTS Evenement;
DROP TABLE IF EXISTS Connexion;
DROP TABLE IF EXISTS BlockUser;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Coupure;
DROP TABLE IF EXISTS Societe;

CREATE TABLE Societe (
    id INT NOT NULL PRIMARY KEY auto_increment,
    nomSociete VARCHAR(20) NOT NULL
);

CREATE TABLE Coupure (
    idSociete INT NOT NULL,
    borneDebut TIME NOT NULL,
    borneFin TIME NOT NULL,
    temps TIME NOT NULL,
    PRIMARY KEY (idSociete, temps),
    FOREIGN KEY (idSociete) REFERENCES Societe(id)
);

CREATE TABLE User (
    id INT NOT NULL PRIMARY KEY,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    telephone VARCHAR(20) DEFAULT NULL,
    genre VARCHAR(5) NOT NULL,
    code VARCHAR(6) NOT NULL,
    inscription DATE NOT NULL,
    idSociete INT DEFAULT NULL,
    verifie TINYINT(4) DEFAULT 0,
    cleSecurite TEXT DEFAULT NULL,
    ancienPlanning TINYINT(4) DEFAULT 0,
    preferenceEmail TINYINT(4) DEFAULT 0,
    bloquer TINYINT(4) DEFAULT 0,
    desactiver TINYINT(4) DEFAULT 0,
    FOREIGN  KEY (idSociete) REFERENCES Societe(id)
);

CREATE TABLE BlockUser (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ipAdresse VARCHAR(20) NOT NULL,
    datage DATE DEFAULT CURRENT_DATE,
    nbTentative INT DEFAULT 1,
    dureeBloquage INT DEFAULT 30,
    estBloque TINYINT(4) DEFAULT 1
);

CREATE TABLE Connexion (
  idUser INT NOT NULL,
  dateConnexion DATETIME NOT NULL DEFAULT current_timestamp(),
  appareil VARCHAR(50) NOT NULL,
  PRIMARY KEY (idUser, dateConnexion),
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE Evenement (
  id INT NOT NULL PRIMARY KEY auto_increment,
  idUser INT DEFAULT NULL,
  dateEvenement DATETIME DEFAULT current_timestamp(),
  type VARCHAR(50) NOT NULL,
  description TEXT NOT NULL,
  important TINYINT(4) DEFAULT 0,
  connaissance DATETIME DEFAULT NULL,
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE Horaire (
  idHoraire INT NOT NULL PRIMARY KEY auto_increment,
  idUser INT NOT NULL,
  datage DATE NOT NULL,
  hDebut TIME NOT NULL,
  hFin TIME DEFAULT NULL,
  coupure TIME DEFAULT '00:00:00',
  decouchage TINYINT(4) DEFAULT 0,
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE HorairePoubelle (
  idHoraire INT PRIMARY KEY,
  datePoubelle DATETIME NOT NULL DEFAULT current_timestamp(),
  dateSuppression DATE NOT NULL,
  FOREIGN KEY (idHoraire) REFERENCES Horaire(idHoraire)
);

CREATE TABLE MessageInfo (
    id INT NOT NULL PRIMARY KEY auto_increment,
    message TEXT NOT NULL,
    description TEXT NOT NULL,
    dateMessage DATE NOT NULL,
    dateCloture DATE DEFAULT NULL
);

CREATE TABLE LuMessageInfo (
  idMessageInfo INT NOT NULL,
  idUser INT NOT NULL,
  dateLecture DATETIME DEFAULT NULL,
  PRIMARY KEY (idMessageInfo, idUser),
  FOREIGN KEY (idMessageInfo) REFERENCES MessageInfo(id),
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE Pause (
  id INT NOT NULL PRIMARY KEY auto_increment,
  idUser INT NOT NULL,
  hDebut TIME NOT NULL,
  hFin TIME DEFAULT NULL,
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE Requete (
  id INT NOT NULL PRIMARY KEY auto_increment,
  idUser INT NOT NULL,
  type VARCHAR(50) NOT NULL,
  dateOuverture DATE NOT NULL,
  dateCloture DATE DEFAULT NULL,
  messageAdmin TEXT DEFAULT NULL,
  FOREIGN KEY (idUser) REFERENCES User(id)
);

CREATE TABLE MessageRequete (
    id INT NOT NULL PRIMARY KEY auto_increment,
    idUser INT,
    idRequete INT NOT NULL,
    dateEnvoie DATETIME NOT NULL DEFAULT current_timestamp(),
    message TEXT NOT NULL,
    FOREIGN KEY (idUser) REFERENCES User(id),
    FOREIGN KEY (idRequete) REFERENCES Requete(id)
);

CREATE TABLE Paiement (
    id INT NOT NULL PRIMARY KEY auto_increment,
    idUser INT NOT NULL,
    montant INT DEFAULT 0,
    datePaiement DATETIME NOT NULL DEFAULT current_timestamp(),
    dateDebutAbonnement DATE NOT NULL,
    dureeMois INT NOT NULL DEFAULT 12,
    etat VARCHAR(20) NOT NULL CHECK (etat IN ('EXECUTE_AUTO', 'EXECUTE_MANUEL', 'EN ATTENTE', 'EXONERATION')),
    FOREIGN KEY (idUser) REFERENCES User(id)
);