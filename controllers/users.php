<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/users.php — System user management. Administrator only.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_user(array $d, bool $creating = true, ?int $exceptId = null): array {
    $e = [];
    $username = trim($d['username'] ?? '');
    $fullName = trim($d['full_name'] ?? '');
    if ($username === '') $e['username'] = 'Username is required.';
    elseif (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) $e['username'] = 'Username: letters, numbers and underscores, 3-50 chars.';
    elseif (user_username_exists($username, $exceptId)) $e['username'] = 'Username already in use.';
    if ($fullName === '') $e['full_name'] = 'Full name is required.';
    if (!empty($d['email']) && !filter_var(trim($d['email']), FILTER_VALIDATE_EMAIL)) $e['email'] = 'Invalid email address.';
    if (!array_key_exists($d['role'] ?? '', ROLES)) $e['role'] = 'Select a valid role.';
    if (!in_array($d['status'] ?? '', ['active', 'inactive'], true)) $e['status'] = 'Invalid status.';
    if ($creating) {
        if (empty($d['password'])) $e['password'] = 'Password is required.';
        elseif (strlen($d['password']) < 8) $e['password'] = 'Password must be at least 8 characters.';
    } elseif (!empty($d['password']) && strlen($d['password']) < 8) {
        $e['password'] = 'New password must be at least 8 characters.';
    }
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('users', 'view');
        $users = user_all();
        view('users/index', ['title' => 'User Management', 'users' => $users, 'currentUserId' => (int)current_user()['user_id']]);
        break;

    case 'create':
        require_permission('users', 'create');
        view('users/form', ['title' => 'Add User', 'old' => ['status' => 'active'], 'errors' => [], 'editing' => false, 'currentUserId' => 0]);
        break;

    case 'store':
        require_permission('users', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=users&action=create'); }
        $errors = validate_user($_POST, true);
        if ($errors) { view('users/form', ['title' => 'Add User', 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'currentUserId' => 0]); break; }
        user_create($_POST);
        flash_set('success', 'User created successfully.');
        redirect(APP_URL . '/index.php?page=users');
        break;

    case 'edit':
        require_permission('users', 'edit');
        $row = user_find($id);
        if (!$row) { flash_set('error', 'User not found.'); redirect(APP_URL . '/index.php?page=users'); }
        view('users/form', ['title' => 'Edit User', 'old' => $row, 'errors' => [], 'editing' => true, 'currentUserId' => (int)current_user()['user_id']]);
        break;

    case 'update':
        require_permission('users', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=users'); }
        $row = user_find($id);
        if (!$row) { flash_set('error', 'User not found.'); redirect(APP_URL . '/index.php?page=users'); }
        $errors = validate_user($_POST, false, $id);
        if ($errors) { view('users/form', ['title' => 'Edit User', 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'currentUserId' => (int)current_user()['user_id']]); break; }
        $newPwd = !empty($_POST['password']) ? $_POST['password'] : null;
        user_update($id, $_POST, $newPwd);
        flash_set('success', 'User updated successfully.');
        redirect(APP_URL . '/index.php?page=users');
        break;

    case 'delete':
        require_permission('users', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=users'); }
        // Cannot delete yourself
        if ($id === (int)current_user()['user_id']) { flash_set('error', 'You cannot delete your own account.'); redirect(APP_URL . '/index.php?page=users'); }
        $row = user_find($id);
        if (!$row) { flash_set('error', 'User not found.'); redirect(APP_URL . '/index.php?page=users'); }
        user_delete($id);
        flash_set('success', 'User deleted.');
        redirect(APP_URL . '/index.php?page=users');
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
