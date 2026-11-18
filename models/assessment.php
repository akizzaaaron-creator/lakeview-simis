<?php
/** models/assessment.php - Assessment data access (assessments belong to a course). */

function assessment_all(?int $courseId = null): array {
    $sql = "SELECT a.*, c.course_code, c.course_name
            FROM assessment a JOIN course c ON c.course_id = a.course_id";
    $w = []; $p = [];
    if ($courseId) { $w[] = "a.course_id = ?"; $p[] = $courseId; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY a.date DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function assessment_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM assessment WHERE assessment_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function assessment_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO assessment (course_id, title, assessment_type, max_score, date, academic_year, semester) VALUES (?,?,?,?,?,?,?)");
    $sem = (isset($d['semester']) && $d['semester'] !== '') ? (int)$d['semester'] : null;
    $stmt->execute([
        (int)$d['course_id'], $d['title'], $d['assessment_type'], (float)$d['max_score'],
        $d['date'] ?: null, $d['academic_year'] ?: null, $sem,
    ]);
    return (int) db()->lastInsertId();
}

function assessment_update(int $id, array $d): void {
    $stmt = db()->prepare("UPDATE assessment SET course_id=?, title=?, assessment_type=?, max_score=?, date=?, academic_year=?, semester=? WHERE assessment_id=?");
    $sem = (isset($d['semester']) && $d['semester'] !== '') ? (int)$d['semester'] : null;
    $stmt->execute([
        (int)$d['course_id'], $d['title'], $d['assessment_type'], (float)$d['max_score'],
        $d['date'] ?: null, $d['academic_year'] ?: null, $sem, $id,
    ]);
}

function assessment_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM assessment WHERE assessment_id = ?");
    $stmt->execute([$id]);
}

function assessments_for_course(int $courseId): array {
    $stmt = db()->prepare("SELECT * FROM assessment WHERE course_id = ? ORDER BY date");
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}
