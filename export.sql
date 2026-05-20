PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    description TEXT
);
INSERT INTO departements VALUES(1,'Informatique','Service IT');
INSERT INTO departements VALUES(2,'Ressources Humaines','Service RH');
CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL,
    jours_annuels INTEGER NOT NULL,
    deductible INTEGER DEFAULT 0
);
INSERT INTO types_conge VALUES(1,'Congé Annuel',30,1);
INSERT INTO types_conge VALUES(2,'Maladie',15,1);
INSERT INTO types_conge VALUES(3,'Congé Spécial',0,0);
INSERT INTO types_conge VALUES(4,'Sans Solde',0,0);
CREATE TABLE employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    prenom TEXT,
    email TEXT UNIQUE,
    password TEXT,
    role TEXT,
    departement_id INTEGER,
    date_embauche TEXT,
    actif INTEGER DEFAULT 1,
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);
INSERT INTO employes VALUES(1,'Admin','Super','admin@techmada.mg','$2y$10$pVXj0C334yAqZ7sXf3jVVOzzOU5EIMDlGZhoqpjchQZOAL1yReB9S','admin',1,'2020-01-01',1);
INSERT INTO employes VALUES(2,'pierre','jeann','rh@techmada.mg','$2y$10$h.3XBXvvBDwhMpizPjJoIemHPdUMMIY2lDUzu69.AL2rkupsiHD02','rh',2,'2021-01-01',1);
INSERT INTO employes VALUES(3,'lol','huhu','employe@techmada.mg','$2y$10$QYPlBCmYooF3mbuEKmAxwe0qyVOxVAskqBjzy8TDhLx1QaQODDm46','rh',1,'2022-01-01',1);
CREATE TABLE soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee TEXT NOT NULL,
    jours_attribues INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);
INSERT INTO soldes VALUES(1,3,1,'2026',30,12);
INSERT INTO soldes VALUES(2,3,2,'2026',15,2);
INSERT INTO soldes VALUES(3,3,1,'2026',30,12);
INSERT INTO soldes VALUES(4,3,2,'2026',15,2);
INSERT INTO soldes VALUES(5,3,1,'2026',30,12);
INSERT INTO soldes VALUES(6,3,2,'2026',15,2);
CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut TEXT NOT NULL,
    date_fin TEXT NOT NULL,
    nb_jours INTEGER NOT NULL,
    motif TEXT,
    statut TEXT,
    commentaire_rh TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    traite_par INTEGER,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employes(id)
);
INSERT INTO conges VALUES(1,3,1,'2026-05-18','2026-05-23',5,'Voyage en famille','en_attente',NULL,'2026-05-13 08:38:26',NULL);
INSERT INTO conges VALUES(2,3,2,'2026-04-28','2026-04-30',2,'Grippe','approuvee','Bon rétablissement','2026-04-27 08:38:26',2);
INSERT INTO conges VALUES(3,3,1,'2026-05-18','2026-05-23',5,'Voyage en famille','en_attente',NULL,'2026-05-13 09:27:24',NULL);
INSERT INTO conges VALUES(4,3,2,'2026-04-28','2026-04-30',2,'Grippe','approuvee','Bon rétablissement','2026-04-27 09:27:24',2);
INSERT INTO conges VALUES(5,3,1,'2026-05-18','2026-05-23',5,'Voyage en famille','en_attente',NULL,'2026-05-13 10:03:55',NULL);
INSERT INTO conges VALUES(6,3,2,'2026-04-28','2026-04-30',2,'Grippe','approuvee','Bon rétablissement','2026-04-27 10:03:55',2);
CREATE TABLE `migrations` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`version` VARCHAR NOT NULL,
	`class` VARCHAR NOT NULL,
	`group` VARCHAR NOT NULL,
	`namespace` VARCHAR NOT NULL,
	`time` INT NOT NULL,
	`batch` INT NOT NULL
);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('departements',2);
INSERT INTO sqlite_sequence VALUES('types_conge',4);
INSERT INTO sqlite_sequence VALUES('employes',3);
INSERT INTO sqlite_sequence VALUES('soldes',6);
INSERT INTO sqlite_sequence VALUES('conges',6);
COMMIT;
