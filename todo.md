# TechMada — Système RH Interne

Système de gestion des congés pour l'entreprise TechMada, développé en **CodeIgniter 4**.  
Un employé soumet une demande de congé, le responsable RH la valide ou la refuse, le solde se met à jour automatiquement. L'admin supervise l'ensemble via un tableau de bord.

---

## Prérequis

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- CLI disponible (`php spark`)

---

## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/<votre-org>/techmada-rh.git
cd techmada-rh

# 2. Installer les dépendances
composer install

# 3. Copier et configurer l'environnement
cp env .env
```

Ouvrir `.env` et renseigner :

```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = techmada_rh
database.default.username = root
database.default.password = votre_mot_de_passe
database.default.DBDriver = MySQLi
```

```bash
# 4. Exécuter les migrations (dans cet ordre)
php spark migrate

# 5. Peupler la base avec les données de test
php spark db:seed DatabaseSeeder
```

```bash
# 6. Lancer le serveur de développement
php spark serve
```

L'application est accessible sur : **http://localhost:8080**

---

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@techmada.mg | admin123 |
| Responsable RH | rh@techmada.mg | rh123 |
| Employé | employe1@techmada.mg | employe123 |
| Employé | employe2@techmada.mg | employe123 |

---

## Structure du projet

```
app/
├── Controllers/
│   ├── Auth/
│   ├── Employe/
│   ├── Rh/
│   └── Admin/
├── Filters/
│   └── AuthFilter.php
├── Models/
│   ├── EmployeModel.php
│   ├── CongeModel.php
│   ├── SoldeModel.php
│   ├── DepartementModel.php
│   └── TypeCongeModel.php
├── Views/
│   ├── layout/
│   │   └── app.php
│   ├── employe/
│   ├── rh/
│   └── admin/
└── Database/
    ├── Migrations/
    └── Seeds/
```

---

## Schéma de base de données

5 tables :

| Table | Description |
|-------|-------------|
| `employes` | Utilisateurs (rôle : employe / rh / admin) |
| `departements` | Départements de l'entreprise |
| `types_conge` | Types de congé (congé annuel, maladie, etc.) |
| `soldes` | Jours attribués et jours pris par employé/type/année |
| `conges` | Demandes de congé avec statut |

---

## Logique métier — Calcul du solde

Le solde restant est **toujours calculé**, jamais stocké :

```
nb_jours_restant = jours_attribues - jours_pris
```

- Le solde est déduit **uniquement à l'approbation**, pas à la soumission.
- Si une demande approuvée est annulée, le solde est recrédité.
- Validation : `jours_pris + nb_jours_demandes <= jours_attribues`

---

## Fonctionnalités par rôle

### Employé
- Connexion / déconnexion
- Soumettre une demande de congé (type, dates, motif)
- Consulter ses demandes et leurs statuts
- Voir son solde restant par type
- Annuler une demande encore en attente
- Modifier son profil (nom, mot de passe)

### Responsable RH
- Voir toutes les demandes en attente de son équipe
- Approuver ou refuser (commentaire optionnel) avec MAJ solde automatique
- Filtrer par département ou statut
- Voir le solde de chaque employé

### Administrateur
- CRUD employés (créer, éditer, désactiver)
- CRUD départements et types de congé
- Tableau de bord : absences du mois en cours
- Initialiser / ajuster le solde annuel d'un employé
- Historique complet de toutes les demandes

---

## Directives techniques

- Session CI4 native, `password_hash()` obligatoire
- Filtre `AuthFilter` sur toutes les routes protégées
- Pattern PRG (Post/Redirect/Get) sur toutes les écritures
- Flashdata CI4 pour les messages succès/erreur
- CSRF activé sur tous les formulaires POST
- Query Builder uniquement — pas de SQL brut
- Aucun JavaScript métier — tout côté serveur

---

## Découpage du temps (4h)

| Phase | Durée | Contenu |
|-------|-------|---------|
| Setup & BDD | 20 min | Migrations + seeder + routes squelette |
| Authentification | 40 min | Login, session, filtre 3 rôles |
| Espace employé | 60 min | Soumettre, lister, solde, annuler |
| Espace RH | 50 min | Approuver/refuser + MAJ solde |
| Back-office admin | 30 min | CRUD employés + dashboard |
| Finition | 20 min | Flashdata, CSRF, README |

---

## Livrables attendus

- [x] Code source complet (structure CI4 standard)
- [x] Migrations + Seeder fonctionnels (`php spark migrate && php spark db:seed`)
- [x] Les 4 fonctionnalités obligatoires employé opérationnelles
- [x] Les 3 fonctionnalités obligatoires RH opérationnelles (dont MAJ solde)
- [x] Les 3 fonctionnalités obligatoires admin opérationnelles
- [x] README avec instructions + comptes de test

---

*Projet réalisé dans le cadre d'un TP binôme — durée cible 4h.*
### REPARTION DES TACHES
## Phase 1 — Setup & BDD (20 min)
- Initialiser le projet CI4, configurer .env et la base de données
5 min
Pers. A
obligatoire

- Créer les migrations dans l'ordre : departements → types_conge → employes → soldes → conges
10 min
Pers. A
obligatoire

- Écrire le Seeder : 1 admin, 2 employés, 3 types de congé, soldes initialisés
5 min
Pers. A
obligatoire

## Phase 2 — Authentification & rôles (40 min)
- Créer AuthFilter (filtre session CI4) et groupes de routes /employe, /rh, /admin
15 min
Pers. A
obligatoire

- Implémenter login/logout avec password_hash() + vérification du rôle dans le controller
15 min
Pers. A
obligatoire

- Créer le layout partagé layout/app.php + sidebar selon rôle
10 min
Pers. A

## Phase 3 — Espace Employé (60 min)
- Vue : soumettre une demande de congé (type, date_debut, date_fin, motif) avec calcul jours ouvrables
20 min
Pers. B
obligatoire

- Vue : lister ses demandes avec statut (en_attente / approuvée / refusée / annulée)
15 min
Pers. B
obligatoire

- Vue : afficher le solde restant par type de congé
10 min
Pers. B
obligatoire

- Fonctionnalité : annuler une demande encore en_attente
10 min
Pers. B
obligatoire

- Modifier son profil (nom, mot de passe)
5 min
Pers. B

## Phase 4 — Espace RH (50 min)
- Vue : liste de toutes les demandes en attente de son équipe
15 min
Pers. B
obligatoire

- Approuver ou refuser une demande (commentaire RH optionnel) + MAJ solde automatique
20 min
Pers. B
obligatoire

- Filtrer les demandes par département ou statut
10 min
Pers. B

- Voir le solde restant de chaque employé
5 min
Pers. B

## Phase 5 — Back-office Admin (30 min)
- CRUD employés : créer, éditer, désactiver (actif 0/1)
15 min
Pers. A
obligatoire

- CRUD départements et types de congé
10 min
Pers. A
obligatoire

- Tableau de bord : absences du mois en cours + initialiser/ajuster solde annuel
5 min
Pers. A
obligatoire

## Phase 6 — Finition (20 min)
- Activer CSRF sur tous les formulaires POST
5 min
Pers. A

- Flashdata CI4 pour tous les messages succès/erreur
5 min
Pers. B

- Vérifications métier : jours_pris + nb_jours ≤ jours_attribués, date_debut < date_fin, pas de chevauchement
10 min
Pers. B
obligatoire

- Écrire le README : instructions, compte admin, compte employé de test
5 min
Pers. A
obligatoire





+ [X] creer models
    + [X] CongeModel
    + [X] DepartementModel
    + [X] EmployeeModel
    + [X] SoldeModel
    + [X] TypeCongeModel
+ [X] creer Filters:
    + [X] AuthFilter   
    + [X] RoleFilter
        +  [X] Admin
        +  [X] Rh
        +  [X] Employe
+ [X] creer controllers:
    + [X] AuthController
    + [X] EmployeController
+ [ ] localhost:3000
.env 



+ [ ] deux liens 
    + [ ] 
    + [ ] page 1   
        + [ ] import calendar 
        + [ ] option 
            + [ ] days 
            + [ ] week 
            + [ ] month 
    + [ ] page 2 
        + [ ] import chart ou pie 
            + [ ] generaliser javascipt 
    + [ ] 
+ [ ] créer calendar javascript 
    + [ ] table full clendar
        + [ ]
    + [ ] chart.js /cdn 
        + [ ] pie ????