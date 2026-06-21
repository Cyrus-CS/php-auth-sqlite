<?php
$action  = $_GET['action'] ?? 'login';
$errors  = $_SESSION['errors']  ?? [];
$success = $_SESSION['success'] ?? '';
$old     = $_SESSION['old']     ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);

$titles = [
    'login'     => 'Connexion',
    'register'  => 'Créer un compte',
    'dashboard' => 'Tableau de bord',
];
$pageTitle = $titles[$action] ?? 'Auth';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — AuthSystem</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════
           TOKENS
        ═══════════════════════════════════════════════════════ */
        :root {
            --bg-deep:    #0D0D1A;
            --bg-card:    #13132A;
            --bg-input:   #1A1A35;
            --border:     #2A2A55;
            --border-focus: #7C3AED;
            --accent:     #7C3AED;
            --accent-soft:#A78BFA;
            --success:    #10B981;
            --danger:     #EF4444;
            --warning:    #F59E0B;
            --text-main:  #E2E8F0;
            --text-muted: #94A3B8;
            --dot-color:  rgba(124, 58, 237, 0.15);
            --radius:     14px;
            --font-display: 'Space Grotesk', sans-serif;
            --font-body:    'Inter', sans-serif;
        }

        /* ═══════════════════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════════════════════
           GRILLE DE POINTS (signature visuelle)
        ═══════════════════════════════════════════════════════ */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(var(--dot-color) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
            animation: driftDots 20s linear infinite;
        }

        @keyframes driftDots {
            0%   { background-position: 0 0; }
            100% { background-position: 28px 28px; }
        }

        /* Halo violet en arrière-plan */
        body::after {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124,58,237,.18) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        /* ═══════════════════════════════════════════════════════
           WRAPPER
        ═══════════════════════════════════════════════════════ */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 1.25rem;
        }

        /* ═══════════════════════════════════════════════════════
           LOGO
        ═══════════════════════════════════════════════════════ */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--accent), var(--accent-soft));
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: .75rem;
            box-shadow: 0 0 24px rgba(124,58,237,.4);
        }
        .brand-name {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -.01em;
        }
        .brand-tagline {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .15rem;
        }

        /* ═══════════════════════════════════════════════════════
           CARD
        ═══════════════════════════════════════════════════════ */
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.25rem 2rem;
            box-shadow:
                0 0 0 1px rgba(124,58,237,.06),
                0 24px 48px rgba(0,0,0,.45);
        }

        .auth-card-title {
            font-family: var(--font-display);
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }
        .auth-card-sub {
            font-size: .83rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        /* ═══════════════════════════════════════════════════════
           FORM ELEMENTS
        ═══════════════════════════════════════════════════════ */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: .45rem;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
            transition: color .2s;
        }

        .form-control-custom {
            width: 100%;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-family: var(--font-body);
            font-size: .9rem;
            padding: .72rem 2.6rem .72rem 2.6rem;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-control-custom::placeholder { color: var(--text-muted); opacity: .6; }
        .form-control-custom:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(124,58,237,.18);
        }
        .form-control-custom:focus + .input-icon,
        .input-group-custom:focus-within .input-icon { color: var(--accent-soft); }

        .toggle-pwd {
            position: absolute;
            right: 13px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            z-index: 2;
            transition: color .2s;
        }
        .toggle-pwd:hover { color: var(--accent-soft); }

        /* ── Password strength bar ────────────────────────────── */
        .strength-bar-wrap {
            height: 4px;
            background: var(--border);
            border-radius: 99px;
            margin-top: .55rem;
            overflow: hidden;
        }
        .strength-bar-fill {
            height: 100%;
            width: 0;
            border-radius: 99px;
            transition: width .35s ease, background .35s ease;
        }
        .strength-label {
            font-size: .73rem;
            font-weight: 600;
            margin-top: .3rem;
            min-height: 1rem;
            text-align: right;
            transition: color .35s;
        }

        /* ── Submit button ────────────────────────────────────── */
        .btn-auth {
            width: 100%;
            padding: .78rem;
            border-radius: 10px;
            font-family: var(--font-display);
            font-size: .95rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent) 0%, #6D28D9 100%);
            color: #fff;
            letter-spacing: .01em;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 18px rgba(124,58,237,.35);
            margin-top: .5rem;
        }
        .btn-auth:hover {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(124,58,237,.5);
        }
        .btn-auth:active { transform: translateY(0); }

        /* ── Switch link ──────────────────────────────────────── */
        .switch-link {
            text-align: center;
            font-size: .83rem;
            color: var(--text-muted);
            margin-top: 1.5rem;
        }
        .switch-link a {
            color: var(--accent-soft);
            text-decoration: none;
            font-weight: 600;
        }
        .switch-link a:hover { text-decoration: underline; }

        /* ── Alerts (Bootstrap override) ──────────────────────── */
        .alert-custom {
            border-radius: 10px;
            font-size: .85rem;
            padding: .85rem 1rem;
            margin-bottom: 1.25rem;
            border: 1.5px solid;
        }
        .alert-err {
            background: rgba(239,68,68,.1);
            border-color: rgba(239,68,68,.35);
            color: #FCA5A5;
        }
        .alert-ok {
            background: rgba(16,185,129,.1);
            border-color: rgba(16,185,129,.35);
            color: #6EE7B7;
        }

        /* ── Divider ──────────────────────────────────────────── */
        .form-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        /* ═══════════════════════════════════════════════════════
           DASHBOARD
        ═══════════════════════════════════════════════════════ */
        .dashboard-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 24px 48px rgba(0,0,0,.45);
            max-width: 500px;
        }
        .dash-avatar {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--accent), var(--accent-soft));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.25rem;
            box-shadow: 0 0 28px rgba(124,58,237,.4);
        }
        .dash-username {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: .3rem;
        }
        .dash-email {
            font-size: .85rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }
        .dash-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.3);
            color: #6EE7B7;
            border-radius: 99px;
            padding: .3rem .9rem;
            font-size: .78rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }
        .btn-logout {
            background: none;
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            border-radius: 10px;
            padding: .6rem 1.5rem;
            font-family: var(--font-body);
            font-size: .87rem;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }
        .btn-logout:hover {
            border-color: var(--danger);
            color: #FCA5A5;
        }

        /* ═══════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════ */
        @media (max-width: 480px) {
            .auth-card { padding: 1.75rem 1.25rem; }
        }
    </style>
</head>
<body>

<?php if ($action === 'dashboard'): ?>
    <?php include __DIR__ . '/dashboard.php'; ?>
<?php else: ?>
    <div class="auth-wrapper">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-shield-lock-fill text-white"></i>
            </div>
            <div class="brand-name">AuthSystem</div>
            <div class="brand-tagline">Accès sécurisé à votre espace</div>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <?php if ($action === 'login'): ?>
                <?php include __DIR__ . '/login.php'; ?>
            <?php else: ?>
                <?php include __DIR__ . '/register.php'; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /* ── Toggle visibilité mot de passe ── */
    function togglePwd(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    /* ── Mesure de la force du mot de passe ── */
    function checkStrength(input, fillId, labelId) {
        const val  = input.value;
        const fill = document.getElementById(fillId);
        const lbl  = document.getElementById(labelId);
        let score  = 0;

        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '',          text: '' },
            { pct: '25%',  color: '#EF4444',   text: 'Faible' },
            { pct: '50%',  color: '#F59E0B',   text: 'Moyen' },
            { pct: '75%',  color: '#A78BFA',   text: 'Bon' },
            { pct: '100%', color: '#10B981',   text: 'Excellent' },
        ];

        fill.style.width      = levels[score].pct;
        fill.style.background = levels[score].color;
        lbl.textContent       = levels[score].text;
        lbl.style.color       = levels[score].color;
    }
</script>
</body>
</html>
