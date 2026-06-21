<?php
// Variables héritées de layout.php : $errors, $success, $old
?>

<h2 class="auth-card-title">Bon retour 👋</h2>
<p class="auth-card-sub">Connectez-vous pour accéder à votre espace.</p>

<?php if ($errors): ?>
    <div class="alert-custom alert-err" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <?php foreach ($errors as $e): ?>
            <?= htmlspecialchars($e) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert-custom alert-ok" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=login" novalidate>

    <!-- Identifiant (email ou pseudo) -->
    <div class="mb-4">
        <label class="form-label" for="identifier">E-mail ou nom d'utilisateur</label>
        <div class="input-group-custom">
            <i class="bi bi-person input-icon"></i>
            <input
                type="text"
                id="identifier"
                name="identifier"
                class="form-control-custom"
                placeholder="votre@email.com ou pseudo"
                value="<?= htmlspecialchars($old['identifier'] ?? '') ?>"
                autocomplete="username"
                required
            >
        </div>
    </div>

    <!-- Mot de passe -->
    <div class="mb-2">
        <label class="form-label" for="login_password">Mot de passe</label>
        <div class="input-group-custom">
            <i class="bi bi-lock input-icon"></i>
            <input
                type="password"
                id="login_password"
                name="password"
                class="form-control-custom"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            >
            <button
                type="button"
                class="toggle-pwd"
                onclick="togglePwd('login_password', this.querySelector('i'))"
                aria-label="Afficher / masquer le mot de passe"
            >
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <!-- Barre de force (lecture seule à la connexion, indicateur visuel) -->
    <div class="strength-bar-wrap mb-1">
        <div class="strength-bar-fill" id="login_strength_fill"></div>
    </div>
    <p class="strength-label mb-4" id="login_strength_label"></p>

    <button type="submit" class="btn-auth">
        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
    </button>
</form>

<div class="switch-link">
    Pas encore de compte ?
    <a href="index.php?action=register">Créer un compte</a>
</div>

<script>
    document.getElementById('login_password').addEventListener('input', function () {
        checkStrength(this, 'login_strength_fill', 'login_strength_label');
    });
</script>
