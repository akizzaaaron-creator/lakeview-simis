<?php
/**
 * includes/functions.php
 * Shared helper functions used throughout SIMIS.
 */

/** Escape output for safe HTML rendering. */
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Redirect and stop execution. */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

// ---- Flash messages ------------------------------------------------------
function flash_set(string $type, string $message): void {
    $_SESSION['_flash'][$type][] = $message;
}
function flash_get(): array {
    $f = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $f;
}
function display_flash(): void {
    $map = ['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'];
    foreach (flash_get() as $type => $messages) {
        $cls = $map[$type] ?? 'info';
        foreach ($messages as $m) {
            echo '<div class="alert alert-' . $cls . ' alert-dismissible fade show" role="alert">'
               . e($m)
               . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }
}

// ---- CSRF protection -----------------------------------------------------
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}
function csrf_verify(): bool {
    $token = $_POST['_csrf'] ?? '';
    return !empty($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
}

// ---- View rendering -------------------------------------------------------
/**
 * Render a view inside the shared layout.
 * @param string $__view  view path relative to views/ (e.g. 'students/index')
 * @param array  $__data  variables extracted into the view scope
 */
function view(string $__view, array $__data = []): void {
    extract($__data, EXTR_SKIP);
    if (!isset($title)) $title = '';
    require APP_ROOT . '/views/layouts/header.php';
    require APP_ROOT . '/views/' . $__view . '.php';
    require APP_ROOT . '/views/layouts/footer.php';
}

// ---- Form helpers --------------------------------------------------------
function val(string $field, array $old, string $default = ''): string {
    return e($old[$field] ?? $default);
}
function err(string $field, array $errors): string {
    return isset($errors[$field])
        ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>'
        : '';
}

// ---- Navigation helper ---------------------------------------------------
function is_active(string $p): string {
    global $page;
    return (($page ?? '') === $p) ? 'active' : '';
}

// ---- Formatting ----------------------------------------------------------
function money($amount): string {
    return number_format((float)$amount) . ' UGX';
}
function fmt_date(?string $date): string {
    if (!$date || $date === '0000-00-00') return '';
    $ts = strtotime($date);
    return $ts ? date('d M Y', $ts) : '';
}
function status_badge(string $status): string {
    $map = [
        'active'     => 'success', 'inactive'   => 'secondary', 'suspended' => 'warning',
        'graduated'  => 'info',     'withdrawn'  => 'dark',
        'present'    => 'success', 'absent'     => 'danger', 'late' => 'warning', 'excused' => 'info',
        'paid'       => 'success', 'pending'    => 'warning', 'partial' => 'info', 'overdue' => 'danger',
        'pass'       => 'success', 'fail'       => 'danger',
    ];
    $cls = $map[$status] ?? 'secondary';
    return '<span class="badge bg-' . $cls . '">' . e(ucfirst(str_replace('_', ' ', $status))) . '</span>';
}

// ---- Misc ----------------------------------------------------------------
function intify($v, int $default = 0): int {
    $v = filter_var($v, FILTER_VALIDATE_INT);
    return $v === false ? $default : $v;
}
function floatify($v, float $default = 0): float {
    $v = filter_var($v, FILTER_VALIDATE_FLOAT);
    return $v === false ? $default : $v;
}

/** Render a dashboard stat card. */
function stat_card(string $icon, string $label, $value, string $color = ''): string {
    $style = $color ? ' style="border-left-color:' . e($color) . '"' : '';
    return '<div class="card stat-card h-100"' . $style . '>'
        . '<div class="card-body">'
        . '<div class="stat-info">'
        . '<div class="stat-label">' . e($label) . '</div>'
        . '<div class="stat-value">' . e((string)$value) . '</div>'
        . '</div>'
        . '<i class="bi bi-' . e($icon) . ' stat-icon"></i>'
        . '</div></div>';
}
