<?php
/**
 * config/database.php
 * Returns a single shared PDO connection via db().
 * All models use prepared statements through this connection.
 */
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            $msg = (APP_ENV === 'development')
                ? 'Database connection failed. Ensure MySQL is running and the schema is imported. '
                  . $e->getMessage()
                : 'The system is temporarily unavailable. Please try again later.';
            die(htmlspecialchars($msg));
        }
    }
    return $pdo;
}
