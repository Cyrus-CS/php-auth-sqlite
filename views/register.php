<?php
// Variables héritées de layout.php : $errors, $old
?>

<h2 class="auth-card-title">Créer un compte</h2>
<p class="auth-card-sub">Rejoignez-nous — c'est gratuit et rapide.</p>

<?php if ($errors): ?>
    <div class="alert-custom alert-err" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <?php foreach ($errors as $e): ?>
            <?= htmlspecialchars($e) ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=register" novalidate>

    <!-- Nom d'utilisateur -->
    <div class="mb-4">
        <label class="form-label" for="username">Nom d'utilisateur</label>
        <div class="input-group-custom">
            <i class="bi bi-person input-icon"></i>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control-custom"
                placeholder="mon_pseudo"
                value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                autocomplete="username"
                minlength="3"
                maxlength="30"
                required
            >
        </div>
    </div>

    <!-- E-mail -->
    <div class="mb-4">
        <label class="form-label" for="email">Adresse e-mail</label>
        <div class="input-group-custom">
            <i class="bi bi-envelope input-icon"></i>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control-custom"
                placeholder="votre@email.com"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                autocomplete="email"
                required
            >
        </div>
    </div>

    <!-- Mot de passe -->
    <div class="mb-1">
        <label class="form-label" for="reg_password">Mot de passe</label>
        <div class="input-group-custom">
            <i class="bi bi-lock input-icon"></i>
            <input
                type="password"
                id="reg_password"
                name="password"
                class="form-control-custom"
                placeholder="Min. 8 caractères"
                autocomplete="new-password"
                required
            >
            <button
                type="button"
                class="toggle-pwd"
                onclick="togglePwd('reg_password', this.querySelector('i'))"
                aria-label="Afficher / masquer"
            >
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <!-- Barre de force -->
    <div class="strength-bar-wrap mt-2">
        <div class="strength-bar-fill" id="reg_strength_fill"></div>
    </div>
    <p class="strength-label mb-4" id="reg_strength_label"></p>

    <!-- Confirmation -->
    <div class="mb-4">
        <label class="form-label" for="confirm">Confirmer le mot de passe</label>
        <div class="input-group-custom">
            <i class="bi bi-lock-fill input-icon"></i>
            <input
                type="password"
                id="confirm"
                name="confirm"
                class="form-control-custom"
                placeholder="Répétez le mot de passe"
                autocomplete="new-password"
                required
            >
            <button
                type="button"
                class="toggle-pwd"
                onclick="togglePwd('confirm', this.querySelector('i'))"
                aria-label="Afficher / masquer"
            >
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <!-- Indicateur de correspondance -->
        <p class="strength-label mt-2" id="match_label"></p>
    </div>

    <button type="submit" class="btn-auth" id="reg_submit">
        <i class="bi bi-person-plus me-2"></i>Créer mon compte
    </button>
</form>

<div class="switch-link">
    Déjà inscrit ?
    <a href="index.php?action=login">Se connecter</a>
</div>

<script>
    const pwdInput     = document.getElementById('reg_password');
    const confirmInput = document.getElementById('confirm');
    const matchLabel   = document.getElementById('match_label');

    pwdInput.addEventListener('input', function () {
        checkStrength(this, 'reg_strength_fill', 'reg_strength_label');
        checkMatch();
    });

    confirmInput.addEventListener('input', checkMatch);

    function checkMatch() {
        if (!confirmInput.value) {
            matchLabel.textContent = '';
            return;
        }
        if (pwdInput.value === confirmInput.value) {
            matchLabel.textContent = '✓ Les mots de passe correspondent';
            matchLabel.style.color = '#10B981';
        } else {
            matchLabel.textContent = '✗ Les mots de passe ne correspondent pas';
            matchLabel.style.color = '#EF4444';
        }
    }
</script>
