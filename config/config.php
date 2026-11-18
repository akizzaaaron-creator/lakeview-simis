<?php
/**
 * config/config.php
 * Lakeview Skills Institute SIMIS — application configuration.
 *
 * NOTE: These are the default XAMPP MySQL credentials (root / no password)
 * so the prototype runs out of the box. CHANGE them for any real deployment.
 */

// Project root (this file lives in config/, so one level up is the project root).
define('APP_ROOT', dirname(__DIR__));

// ---- Database (default XAMPP MySQL) --------------------------------------
define('DB_HOST', '127.0.0.1');
// MySQL port. Default is 3306 (standard XAMPP). Override with the SIMIS_DB_PORT
// environment variable if your MySQL runs on a different port.
define('DB_PORT', (int)(getenv('SIMIS_DB_PORT') ?: 3306));
define('DB_NAME', 'simis_lakeview');
define('DB_USER', 'root');
define('DB_PASS', '');                 // default XAMPP MySQL password is empty
define('DB_CHARSET', 'utf8mb4');

// ---- Application ---------------------------------------------------------
define('APP_NAME', 'Lakeview Skills Institute SIMIS');
define('APP_SHORT', 'Lakeview SIMIS');
define('APP_ENV', 'development');      // development | production

// Compute the base URL from the script location so links work whether the
// app is served from the domain root or from a subfolder (e.g. /simis).
$script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$base   = str_replace('\\', '/', dirname($script));
$base   = ($base === '/' || $base === '.' || $base === '') ? '' : rtrim($base, '/');
define('APP_URL', $base);

// ---- Error reporting -----------------------------------------------------
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ---- Session security ----------------------------------------------------
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

date_default_timezone_set('Africa/Kampala');

// ---- Roles ---------------------------------------------------------------
define('ROLE_ADMIN',       'administrator');
define('ROLE_ADMISSIONS',  'admissions_officer');
define('ROLE_FINANCE',     'finance_officer');
define('ROLE_ACADEMIC',    'academic_officer');
define('ROLE_MANAGEMENT',  'management');

define('ROLES', [
    ROLE_ADMIN       => 'Administrator',
    ROLE_ADMISSIONS  => 'Admissions Officer',
    ROLE_FINANCE     => 'Finance Officer',
    ROLE_ACADEMIC    => 'Academic Officer',
    ROLE_MANAGEMENT  => 'Management',
]);

// Status options reused across forms
define('STUDENT_STATUSES', ['active', 'suspended', 'graduated', 'withdrawn']);
define('PAYMENT_METHODS',  ['cash', 'bank_transfer', 'mobile_money', 'cheque']);
define('CHARGE_TYPES',     ['tuition', 'registration', 'examination', 'library', 'other']);
