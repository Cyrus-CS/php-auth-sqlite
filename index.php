<?php
session_start();

define('DB_PATH', __DIR__ . '/database/auth.sqlite');
define('DB_DIR',  __DIR__ . '/database');

// ── Autoload helpers ──────────────────────────────────────────────────────────
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// ── Simple router ─────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register') {
        handleRegister();
    } elseif ($action === 'login') {
        handleLogin();
    }
}

if ($action === 'logout') {
    session_destroy();
    header('Location: index.php?action=login');
    exit;
}

// ── If already connected, redirect to dashboard ───────────────────────────────
if (isset($_SESSION['user_id']) && in_array($action, ['login', 'register'])) {
    header('Location: index.php?action=dashboard');
    exit;
}

// ── Render ────────────────────────────────────────────────────────────────────
include __DIR__ . '/views/layout.php';
