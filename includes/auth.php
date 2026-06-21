<?php
/**
 * Gestion de l'inscription
 */
function handleRegister(): void
{
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';

    // ── Validations ───────────────────────────────────────────────────────────
    $errors = [];

    if (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = "Le nom d'utilisateur doit contenir entre 3 et 30 caractères.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Le nom d'utilisateur ne peut contenir que des lettres, chiffres et underscore.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if ($errors) {
        $_SESSION['errors']  = $errors;
        $_SESSION['old']     = compact('username', 'email');
        header('Location: index.php?action=register');
        exit;
    }

    // ── Insertion ─────────────────────────────────────────────────────────────
    try {
        $db   = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([':u' => $username, ':e' => $email, ':p' => $hash]);

        $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        header('Location: index.php?action=login');
        exit;

    } catch (PDOException $e) {
        // Détection de doublon sans révéler le détail exact
        if (str_contains($e->getMessage(), 'UNIQUE')) {
            $_SESSION['errors'] = ["Ces informations sont déjà utilisées par un autre compte."];
        } else {
            $_SESSION['errors'] = ["Une erreur est survenue. Veuillez réessayer."];
        }
        $_SESSION['old'] = compact('username', 'email');
        header('Location: index.php?action=register');
        exit;
    }
}

/**
 * Gestion de la connexion
 */
function handleLogin(): void
{
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password']        ?? '';

    $errors = [];

    if (empty($identifier) || empty($password)) {
        $errors[] = "Tous les champs sont requis.";
    }

    if ($errors) {
        $_SESSION['errors']  = $errors;
        $_SESSION['old']     = ['identifier' => $identifier];
        header('Location: index.php?action=login');
        exit;
    }

    try {
        $db   = getDB();
        $stmt = $db->prepare(
            'SELECT * FROM users WHERE email = :id OR username = :id2 LIMIT 1'
        );
        $stmt->execute([':id' => $identifier, ':id2' => $identifier]);
        $user = $stmt->fetch();

        // Message générique pour éviter l'énumération de comptes
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors']  = ["Identifiants incorrects."];
            $_SESSION['old']     = ['identifier' => $identifier];
            header('Location: index.php?action=login');
            exit;
        }

        // ── Succès : ouvrir la session ─────────────────────────────────────────
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];

        header('Location: index.php?action=dashboard');
        exit;

    } catch (PDOException) {
        $_SESSION['errors'] = ["Une erreur est survenue. Veuillez réessayer."];
        header('Location: index.php?action=login');
        exit;
    }
}
