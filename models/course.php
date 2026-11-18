<?php
/** models/course.php - Course data access. Each course belongs to one programme. */

function course_all(?int $programmeId = null): array {
    if ($programmeId) {
        $stmt = db()->prepare(
            "SELECT c.*, p.programme_code, p.programme_name
             FROM course c JOIN programme p ON p.programme_id = c.programme_id
             WHERE c.programme_id = ?
             ORDER BY c.semester, c.course_code");
        $stmt->execute([$programmeId]);
    } else {
        $stmt = db()->query(
            "SELECT c.*, p.programme_code, p.programme_name
             FROM course c JOIN programme p ON p.programme_id = c.programme_id
             ORDER BY p.programme_code, c.semester, c.course_code");
    }
    return $stmt->fetchAll();
}

function course_find(int $id): ?array {
    $stmt = db()->prepare("SELECT c.*, p.programme_name FROM course c JOIN programme p ON p.programme_id = c.programme_id WHERE c.course_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function course_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO course (course_code, course_name, description, credit_units, programme_id, semester, status) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([
        $d['course_code'], $d['course_name'], $d['description'] ?: null,
        (int)$d['credit_units'], (int)$d['programme_id'], (int)$d['semester'], $d['status'],
    ]);
    return (int) db()->lastInsertId();
}

function course_update(int $id, array $d): void {
    $stmt = db()->prepare("UPDATE course SET course_code=?, course_name=?, description=?, credit_units=?, programme_id=?, semester=?, status=? WHERE course_id=?");
    $stmt->execute([
        $d['course_code'], $d['course_name'], $d['description'] ?: null,
        (int)$d['credit_units'], (int)$d['programme_id'], (int)$d['semester'], $d['status'], $id,
    ]);
}

function course_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM course WHERE course_id = ?");
    $stmt->execute([$id]);
}

function course_code_exists(string $code, ?int $exceptId = null): bool {
    if ($exceptId) {
        $stmt = db()->prepare("SELECT 1 FROM course WHERE course_code = ? AND course_id <> ?");
        $stmt->execute([$code, $exceptId]);
    } else {
        $stmt = db()->prepare("SELECT 1 FROM course WHERE course_code = ?");
        $stmt->execute([$code]);
    }
    return (bool) $stmt->fetchColumn();
}

function courses_for_programme(int $programmeId): array {
    $stmt = db()->prepare("SELECT * FROM course WHERE programme_id = ? ORDER BY semester, course_code");
    $stmt->execute([$programmeId]);
    return $stmt->fetchAll();
}
