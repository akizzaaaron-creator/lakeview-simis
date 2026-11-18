<?php
/** models/enrolment.php — Enrolment data access (student <-> programme). */

function enrolment_all(?string $status = null, ?int $programmeId = null): array {
    $sql = "SELECT e.*, s.student_no, s.full_name, p.programme_code, p.programme_name
            FROM enrolment e
            JOIN student s ON s.student_id = e.student_id
            JOIN programme p ON p.programme_id = e.programme_id";
    $w = []; $p = [];
    if ($status) { $w[] = "e.status = ?"; $p[] = $status; }
    if ($programmeId) { $w[] = "e.programme_id = ?"; $p[] = $programmeId; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY e.academic_year DESC, s.student_no";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function enrolments_for_student(int $studentId): array {
    $stmt = db()->prepare(
        "SELECT e.*, p.programme_code, p.programme_name
         FROM enrolment e JOIN programme p ON p.programme_id = e.programme_id
         WHERE e.student_id = ? ORDER BY e.academic_year DESC");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll();
}

function enrolment_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM enrolment WHERE enrolment_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function enrolment_create(array $d): int {
    $stmt = db()->prepare("INSERT INTO enrolment (student_id, programme_id, academic_year, semester, enrolment_date, status) VALUES (?,?,?,?,?,?)");
    $stmt->execute([(int)$d['student_id'], (int)$d['programme_id'], $d['academic_year'], (int)$d['semester'], $d['enrolment_date'], $d['status']]);
    return (int) db()->lastInsertId();
}

function enrolment_update(int $id, array $d): void {
    $stmt = db()->prepare("UPDATE enrolment SET student_id=?, programme_id=?, academic_year=?, semester=?, enrolment_date=?, status=? WHERE enrolment_id=?");
    $stmt->execute([(int)$d['student_id'], (int)$d['programme_id'], $d['academic_year'], (int)$d['semester'], $d['enrolment_date'], $d['status'], $id]);
}

function enrolment_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM enrolment WHERE enrolment_id = ?");
    $stmt->execute([$id]);
}
