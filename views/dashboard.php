<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$initial = strtoupper(mb_substr($_SESSION['username'], 0, 1));
?>

<div class="d-flex align-items-center justify-content-center min-vh-100 position-relative" style="z-index:1">
    <div class="dashboard-card">
        <!-- Avatar initiale -->
        <div class="dash-avatar"><?= htmlspecialchars($initial) ?></div>

        <!-- Nom & email -->
        <div class="dash-username"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <div class="dash-email"><?= htmlspecialchars($_SESSION['email']) ?></div>

        <!-- Badge connecté -->
        <div>
            <span class="dash-badge">
                <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                Connecté
            </span>
        </div>

        <!-- Stats rapides -->
        <div class="d-flex justify-content-center gap-4 mb-4" style="font-size:.82rem; color:var(--text-muted)">
            <div>
                <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-main)">
                    #<?= (int)$_SESSION['user_id'] ?>
                </div>
                ID compte
            </div>
            <div style="width:1px;background:var(--border)"></div>
            <div>
                <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-main)">
                    MySQL
                </div>
                Base de données
            </div>
        </div>

        <!-- Déconnexion -->
        <a href="index.php?action=logout" class="btn-logout">
            <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter
        </a>
    </div>
</div>