# php-auth

> Système d'authentification PHP natif avec SQLite : inscription, connexion, gestion de session et indicateur de force du mot de passe.

---

## Aperçu

**php-auth-sqlite** est un boilerplate d'authentification léger, sans framework, conçu pour être intégré rapidement dans n'importe quel projet PHP. Il couvre l'inscription, la connexion sécurisée et la gestion de session, avec un design sombre moderne et une expérience utilisateur soignée.

<img width="765" height="647" alt="Image" src="https://github.com/user-attachments/assets/2b59669c-2c30-4de1-b4a8-eb5d581e7ffc" />
<img width="765" height="647" alt="Image" src="https://github.com/user-attachments/assets/72311084-386f-4ad6-b56f-653ed4c788a9" />
<img width="791" height="648" alt="Image" src="https://github.com/user-attachments/assets/a1ac0b07-6279-4caf-89de-42bdf810fc34" />
<img width="791" height="648" alt="Image" src="https://github.com/user-attachments/assets/ccc59973-f4fe-4ecc-b7b3-07d0a7fc7e90" />

### Fonctionnalités

- **Inscription** avec validation côté serveur (unicité, format e-mail, longueur)
- **Connexion** par e-mail *ou* nom d'utilisateur
- **Gestion de session** sécurisée avec `session_regenerate_id()`
- **Hachage bcrypt** via `password_hash()` / `password_verify()`
- **Indicateur de force du mot de passe** (Faible → Moyen → Bon → Excellent)
- **Toggle visibilité** du mot de passe
- **Confirmateur de correspondance** en temps réel sur l'inscription
- **Messages d'erreur génériques** (anti-énumération de comptes)
- **Création automatique** de la base SQLite et de la table `users` au premier démarrage
- Design responsive avec Bootstrap 5 + thème sombre personnalisé

---

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | PHP 8.1+ natif (PDO) |
| Base de données | SQLite 3 (fichier local) |
| Frontend | Bootstrap 5.3, Bootstrap Icons |
| Typographie | Space Grotesk, Inter (Google Fonts) |
| Serveur local | PHP built-in server / XAMPP / Laragon |

---

## Structure du projet

```
php-auth-sqlite/
├── index.php              # Routeur principal
├── includes/
│   ├── db.php             # Connexion PDO + création automatique de la DB
│   └── auth.php           # Handlers register / login
├── views/
│   ├── layout.php         # Shell HTML — CSS design system + JS
│   ├── login.php          # Formulaire de connexion
│   ├── register.php       # Formulaire d'inscription
│   └── dashboard.php      # Page protégée post-connexion
├── database/              # Créé automatiquement (ignoré par Git)
│   └── auth.sqlite        # Fichier SQLite (généré au premier lancement)
└── .gitignore
```

---

## Installation & lancement

### Prérequis

- PHP 8.1 ou supérieur
- Extension `pdo_sqlite` activée (incluse par défaut dans XAMPP / Laragon)

### Démarrage rapide

```bash
# Cloner le dépôt
git clone https://github.com/Cyrus-CS/php-auth-sqlite.git
cd php-auth-sqlite

# Lancer le serveur PHP intégré
php -S localhost:8000
```

Ouvrir ensuite `http://localhost:8000` dans le navigateur.

> La base de données `database/auth.sqlite` est **créée automatiquement** à la première visite. Aucune configuration supplémentaire n'est nécessaire.

### Avec XAMPP / Laragon

Placer le dossier dans `htdocs/` (XAMPP) ou `www/` (Laragon) et accéder via `http://localhost/php-auth-sqlite`.

---

## Schéma de la base de données

```sql
CREATE TABLE users (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    username    TEXT    NOT NULL UNIQUE COLLATE NOCASE,
    email       TEXT    NOT NULL UNIQUE COLLATE NOCASE,
    password    TEXT    NOT NULL,                        -- bcrypt hash
    created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
);
```

---

## Sécurité

- Mots de passe hachés avec **bcrypt** (cost factor par défaut de PHP)
- Régénération de l'ID de session après connexion (`session_regenerate_id(true)`)
- Échappement systématique des sorties avec `htmlspecialchars()`
- Messages d'erreur volontairement génériques pour éviter l'énumération de comptes
- Requêtes préparées PDO sur toutes les interactions avec la base

> Ce projet est un **boilerplate de démarrage**. Pour un environnement de production, il est recommandé d'ajouter : HTTPS, protection CSRF, rate limiting sur les formulaires et un système de réinitialisation de mot de passe.

---

## Captures d'écran

| Connexion | Inscription | Dashboard |
|---|---|---|
| *(à venir)* | *(à venir)* | *(à venir)* |

---

## Roadmap

- [ ] Protection CSRF (token dans les formulaires)
- [ ] Rate limiting (blocage après N tentatives échouées)
- [ ] Réinitialisation de mot de passe par e-mail
- [ ] Remember me (cookie persistant)
- [ ] Migration vers MySQL / PostgreSQL (via PDO, changement de DSN uniquement)

---

## Licence

Distribué sous licence **MIT**. Voir le fichier `LICENSE` pour plus de détails.

---

## Auteur

**Cyrus** — [@Cyrus-CS](https://github.com/Cyrus-CS)
