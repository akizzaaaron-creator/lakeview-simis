<?php
/** models/programme.php — Programme data access. */

function programme_all(): array {
    return db()->query("SELECT * FROM programme ORDER BY programme_code")->fetchAll();
}

function programme_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM programme WHERE programme_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function programme_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO programme (programme_code, programme_name, description, duration, status) VALUES (?,?,?,?,?)");
    $stmt->execute([$d['programme_code'], $d['programme_name'], $d['description'] ?: null, $d['duration'] ?: null, $d['status']]);
    return (int) db()->lastInsertId();
}

function programme_update(int $id, array $d): void {
    $stmt = db()->prepare("UPDATE programme SET programme_code=?, programme_name=?, description=?, duration=?, status=? WHERE programme_id=?");
    $stmt->execute([$d['programme_code'], $d['programme_name'], $d['description'] ?: null, $d['duration'] ?: null, $d['status'], $id]);
}

function programme_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM programme WHERE programme_id = ?");
    $stmt->execute([$id]);
}

function programme_code_exists(string $code, ?int $exceptId = null): bool {
    if ($exceptId) {
        $stmt = db()->prepare("SELECT 1 FROM programme WHERE programme_code = ? AND programme_id <> ?");
        $stmt->execute([$code, $exceptId]);
    } else {
        $stmt = db()->prepare("SELECT 1 FROM programme WHERE programme_code = ?");
        $stmt->execute([$code]);
    }
    return (bool) $stmt->fetchColumn();
}

/** Number of courses attached to a programme (used before delete). */
function programme_course_count(int $id): int {
    $stmt = db()->prepare("SELECT COUNT(*) FROM course WHERE programme_id = ?");
    $stmt->execute([$id]);
    return (int) $stmt->fetchColumn();
}
