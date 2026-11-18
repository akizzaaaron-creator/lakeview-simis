<?php
/**
 * includes/auth.php
 * Authentication helpers: current user, login, logout, session guard.
 */

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
function is_logged_in(): bool {
    return isset($_SESSION['user']);
}
function require_login(): void {
    if (!is_logged_in()) {
        flash_set('error', 'Please log in to continue.');
        redirect(APP_URL . '/index.php?page=login');
    }
}

/**
 * Attempt to authenticate a user against the user table.
 * Passwords are stored hashed with password_hash() and verified with
 * password_verify(). Only active accounts may log in.
 */
function login(string $username, string $password): bool {
    $stmt = db()->prepare('SELECT * FROM user WHERE username = ? AND status = ? LIMIT 1');
    $stmt->execute([$username, 'active']);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'user_id'   => $user['user_id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
        ];
        session_regenerate_id(true); // prevent session fixation
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    // Start a fresh session so the flash message survives the redirect.
    session_start();
    session_regenerate_id(true);
}
