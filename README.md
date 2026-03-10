# SAE S6 - Gestion de Bibliothèque

Projet de gestion de bibliothèque avec back-office (Symfony + EasyAdmin), API REST et front-office (Angular).

## Prérequis

- PHP 8.4+
- MariaDB / MySQL
- Node.js 20+
- Composer (ou `composer.phar` local)
- Symfony CLI (optionnel)

## Installation du back-end (Symfony)

```bash
cd gestionnaire

# Installer les dépendances
php ../composer.phar install

# Créer la base de données
php bin/console doctrine:database:create

# Générer et exécuter les migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Charger les fixtures (données de test)
php bin/console doctrine:fixtures:load

# Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

# Lancer le serveur
symfony server:start --port=8008
```

Ou avec le serveur PHP intégré :
```bash
php -S 127.0.0.1:8008 -t public
```

## Installation du front-end (Angular)

```bash
cd front_gestionnaire

# Installer les dépendances
npm install

# Lancer le serveur de dev
ng serve
```

Le front sera accessible sur `http://localhost:4200`.

## Accès

### Back-office (EasyAdmin)

URL : `https://127.0.0.1:8008/admin`

| Email | Mot de passe | Rôle |
|-------|-------------|------|
| admin@biblio.fr | admin | ROLE_ADMIN |
| biblio@biblio.fr | biblio | ROLE_BIBLIO |

### Front-office (Angular)

URL : `http://localhost:4200`

Comptes adhérents (mot de passe : `adherent` pour tous) :
- jean.dupont@mail.fr
- marie.martin@mail.fr
- pierre.durand@mail.fr
- sophie.bernard@mail.fr
- luc.petit@mail.fr
- emma.robert@mail.fr
- thomas.moreau@mail.fr
- julie.simon@mail.fr
- nicolas.laurent@mail.fr
- claire.michel@mail.fr (compte suspendu)

### API REST

| Méthode | URL | Description | Auth |
|---------|-----|-------------|------|
| GET | /api/livres | Liste des livres (pagination + filtres) | Non |
| GET | /api/livres/{id} | Détail d'un livre | Non |
| GET | /api/livres/langues | Langues disponibles | Non |
| GET | /api/categories | Liste des catégories | Non |
| GET | /api/categories/{id} | Détail d'une catégorie | Non |
| GET | /api/auteurs | Liste des auteurs | Non |
| GET | /api/auteurs/{id} | Détail d'un auteur | Non |
| POST | /api/login_check | Connexion JWT | Non |
| GET | /api/user/me | Profil connecté | Oui |
| PUT | /api/user/profil | Modifier profil | Oui |
| GET | /api/user/emprunts | Mes emprunts | Oui |
| GET | /api/user/reservations | Mes réservations | Oui |
| POST | /api/reservations | Créer réservation | Oui |
| DELETE | /api/reservations/{id} | Annuler réservation | Oui |

#### Paramètres de recherche pour /api/livres

- `titre` : recherche par titre (LIKE)
- `categorieId` : filtrer par catégorie
- `auteurId` : filtrer par auteur
- `langue` : filtrer par langue
- `page` : numéro de page (défaut: 1)
- `limit` : résultats par page (défaut: 12)

## Structure du projet

```
gestionnaire/          # Back-end Symfony
├── src/
│   ├── Entity/        # Entités Doctrine (Livre, Auteur, etc.)
│   ├── Repository/    # Repositories avec requêtes custom
│   ├── Controller/
│   │   ├── Admin/     # Controllers EasyAdmin (CRUD)
│   │   └── Api/       # Controllers API REST
│   ├── Security/      # Authenticator back-office
│   └── DataFixtures/  # Données de test
├── config/            # Configuration Symfony
└── templates/         # Templates Twig (login)

front_gestionnaire/    # Front-end Angular
├── src/app/
│   ├── models/        # Interfaces TypeScript
│   ├── services/      # Services (API, Auth)
│   ├── guards/        # Guard d'authentification
│   ├── interceptors/  # Intercepteur JWT
│   └── components/    # Composants (pages)
│       ├── home/
│       ├── catalogue/
│       ├── livre-detail/
│       ├── auteurs/
│       ├── auteur-detail/
│       ├── login/
│       └── mon-compte/
```

## Technologies

- **Back-end** : Symfony 7.4, PHP 8.4, Doctrine ORM, EasyAdmin 4
- **Front-end** : Angular 21, Bootstrap 5 (Bootswatch Cyborg)
- **Auth** : JWT (lexik/jwt-authentication-bundle)
- **BDD** : MariaDB / MySQL
