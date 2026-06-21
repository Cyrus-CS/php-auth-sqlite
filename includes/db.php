<?php
/**
 * Retourne une connexion PDO SQLite.
 * Crée le fichier + la table users si nécessaire.
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // Créer le dossier si absent
    if (!is_dir(DB_DIR)) {
        mkdir(DB_DIR, 0755, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Activer les foreign keys SQLite
    $pdo->exec('PRAGMA foreign_keys = ON;');

    // Création de la table users (si elle n'existe pas)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            username    TEXT    NOT NULL UNIQUE COLLATE NOCASE,
            email       TEXT    NOT NULL UNIQUE COLLATE NOCASE,
            password    TEXT    NOT NULL,
            created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
        );
    ");

    return $pdo;
}
