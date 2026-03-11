# SAE S6 - Gestion de Bibliothèque

Projet de gestion de bibliothèque avec back-office (Symfony + EasyAdmin), API REST et front-office (Angular).

## Installation

### 1. Base de données

Charger la base avec le fichier `bd-dump.sql` :


### 2. Back-end (Symfony) — port 8000

```bash
cd gestionnaire
php ../composer.phar install
php bin/console lexik:jwt:generate-keypair
symfony server:start
```

Back-office : `https://127.0.0.1:8000/admin`

### 3. Front-end (Angular) — port 4200

```bash
cd front_gestionnaire
npm install
npx ng serve --proxy-config proxy.conf.json
```

Front-office : `http://localhost:4200`

## Comptes

Mot de passe pour tous les comptes : **`adherent`**

### Back-office

| Email | Rôle |
|-------|------|
| admin@biblio.fr | ROLE_ADMIN |
| biblio@biblio.fr | ROLE_BIBLIO |

### Front-office (adhérents)

| Email | Nom |
|-------|-----|
| pierre.durand@email.fr | Pierre Durand |
| marie.bernard@email.fr | Marie Bernard |
| luc.petit@email.fr | Luc Petit |
| emma.moreau@email.fr | Emma Moreau |
| julien.garcia@email.fr | Julien Garcia |
| claire.roux@email.fr | Claire Roux |
| thomas.leroy@email.fr | Thomas Leroy |
| sarah.simon@email.fr | Sarah Simon |
| nicolas.laurent@email.fr | Nicolas Laurent |
| camille.michel@email.fr | Camille Michel (suspendu) |
