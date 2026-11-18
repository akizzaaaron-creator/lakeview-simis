<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/auth.php - login and logout.
 */
if ($page === 'logout') {
    logout();
    flash_set('success', 'You have been logged out.');
    redirect(APP_URL . '/index.php?page=login');
}

// page === 'login'
$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        flash_set('error', 'Invalid form submission. Please try again.');
        redirect(APP_URL . '/index.php?page=login');
    }
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $old = ['username' => $username];

    if ($username === '' || $password === '') {
        $errors['login'] = 'Please enter both username and password.';
    } elseif (!login($username, $password)) {
        $errors['login'] = 'Invalid username or password.';
    } else {
        flash_set('success', 'Welcome back, ' . current_user()['full_name'] . '.');
        redirect(APP_URL . '/index.php?page=dashboard');
    }
}

require APP_ROOT . '/views/auth/login.php';
