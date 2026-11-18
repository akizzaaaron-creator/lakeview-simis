<?php
/** models/user.php - System user data access. Passwords always hashed. */

function user_all(): array {
    return db()->query("SELECT user_id, username, full_name, email, role, status, created_at FROM user ORDER BY full_name")->fetchAll();
}

function user_find(int $id): ?array {
    $stmt = db()->prepare("SELECT user_id, username, full_name, email, role, status, created_at FROM user WHERE user_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function user_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO user (username, password_hash, full_name, email, role, status) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$d['username'], password_hash($d['password'], PASSWORD_DEFAULT), $d['full_name'], $d['email'] ?: null, $d['role'], $d['status']]);
    return (int) db()->lastInsertId();
}

/** Update profile fields; set a new password only when $newPassword is provided. */
function user_update(int $id, array $d, ?string $newPassword = null): void {
    if ($newPassword) {
        $stmt = db()->prepare("UPDATE user SET username=?, full_name=?, email=?, role=?, status=?, password_hash=? WHERE user_id=?");
        $stmt->execute([$d['username'], $d['full_name'], $d['email'] ?: null, $d['role'], $d['status'], password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    } else {
        $stmt = db()->prepare("UPDATE user SET username=?, full_name=?, email=?, role=?, status=? WHERE user_id=?");
        $stmt->execute([$d['username'], $d['full_name'], $d['email'] ?: null, $d['role'], $d['status'], $id]);
    }
}

function user_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->execute([$id]);
}

function user_username_exists(string $username, ?int $exceptId = null): bool {
    if ($exceptId) {
        $stmt = db()->prepare("SELECT 1 FROM user WHERE username = ? AND user_id <> ?");
        $stmt->execute([$username, $exceptId]);
    } else {
        $stmt = db()->prepare("SELECT 1 FROM user WHERE username = ?");
        $stmt->execute([$username]);
    }
    return (bool) $stmt->fetchColumn();
}

function user_count(): int {
    return (int) db()->query("SELECT COUNT(*) FROM user")->fetchColumn();
}
