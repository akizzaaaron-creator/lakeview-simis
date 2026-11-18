<?php
/**
 * index.php — Front controller / router for SIMIS.
 *
 * All requests pass through this file. The requested 'page' maps to a
 * controller in controllers/<page>.php. The action is read from $_GET['action'].
 * Public pages (listed in $public) skip the login requirement.
 * Everything else requires an authenticated session.
 */
define('APP_RUNNING', true);

// 1. Load config first (sets session ini options, constants, timezone).
require __DIR__ . '/config/config.php';

// 2. Start session AFTER session ini options have been applied.
session_start();

// 3. Load remaining dependencies.
require __DIR__ . '/config/database.php';
require __DIR__ . '/config/grades.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/access.php';

// 4. Auto-load all model files.
foreach (glob(__DIR__ . '/models/*.php') as $__model) {
    require $__model;
}

// 5. Determine and sanitise the requested page.
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_]/', '', $_GET['page']) : '';
if ($page === '') {
    $page = is_logged_in() ? 'dashboard' : 'login';
}

// 6. Enforce authentication on all non-public pages.
$public = ['login', 'logout'];
if (!in_array($page, $public, true)) {
    require_login();
}

// 7. Map page names to controller files.
$routeMap = ['login' => 'auth', 'logout' => 'auth'];
$controllerPage = $routeMap[$page] ?? $page;

// 8. Dispatch to the matching controller.
$controller = __DIR__ . "/controllers/{$controllerPage}.php";
if (!is_file($controller)) {
    http_response_code(404);
    if (is_logged_in()) {
        view('errors/404', ['title' => 'Page not found']);
    } else {
        // Not logged in and no controller — just redirect to login.
        redirect(APP_URL . '/index.php?page=login');
    }
    exit;
}
require $controller;
