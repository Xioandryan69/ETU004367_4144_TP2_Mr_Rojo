PRAGMA foreign_keys = OFF;
BEGIN TRANSACTION;

-- 🔥 (Optionnel) vider les tables
DELETE FROM conges;
DELETE FROM soldes;
DELETE FROM employes;
DELETE FROM types_conge;
DELETE FROM departements;

-- reset des AUTO_INCREMENT
DELETE FROM sqlite_sequence;

-- ✔ réinitialiser explicitement (option recommandé si tu veux contrôler)
INSERT INTO sqlite_sequence (name, seq) VALUES ('departements', 0);
INSERT INTO sqlite_sequence (name, seq) VALUES ('types_conge', 0);
INSERT INTO sqlite_sequence (name, seq) VALUES ('employes', 0);
INSERT INTO sqlite_sequence (name, seq) VALUES ('soldes', 0);
INSERT INTO sqlite_sequence (name, seq) VALUES ('conges', 0);

COMMIT;
PRAGMA foreign_keys = ON;