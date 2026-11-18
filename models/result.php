<?php
/** models/result.php — Result data access (student score on an assessment). */

function result_all(?int $studentId = null, ?int $assessmentId = null): array {
    $sql = "SELECT r.*, s.student_no, s.full_name, a.title, a.assessment_type, a.max_score,
                   c.course_id, c.course_code, c.course_name
            FROM result r
            JOIN student s ON s.student_id = r.student_id
            JOIN assessment a ON a.assessment_id = r.assessment_id
            JOIN course c ON c.course_id = a.course_id";
    $w = []; $p = [];
    if ($studentId) { $w[] = "r.student_id = ?"; $p[] = $studentId; }
    if ($assessmentId) { $w[] = "r.assessment_id = ?"; $p[] = $assessmentId; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY s.student_no, a.date";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function result_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM result WHERE result_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function result_find_unique(int $studentId, int $assessmentId): ?array {
    $stmt = db()->prepare("SELECT * FROM result WHERE student_id = ? AND assessment_id = ?");
    $stmt->execute([$studentId, $assessmentId]);
    return $stmt->fetch() ?: null;
}

/** Insert or update a student's score for an assessment, auto-grading it. */
function result_upsert(int $studentId, int $assessmentId, float $score, ?string $remarks): void {
    $assessment = assessment_find($assessmentId);
    $max = $assessment ? (float)$assessment['max_score'] : 100;
    $grade = grade_for_score($score, $max);
    $g = $grade['grade'];
    if (result_find_unique($studentId, $assessmentId)) {
        $stmt = db()->prepare("UPDATE result SET score = ?, grade = ?, remarks = ? WHERE student_id = ? AND assessment_id = ?");
        $stmt->execute([$score, $g, $remarks, $studentId, $assessmentId]);
    } else {
        $stmt = db()->prepare("INSERT INTO result (student_id, assessment_id, score, grade, remarks) VALUES (?,?,?,?,?)");
        $stmt->execute([$studentId, $assessmentId, $score, $g, $remarks]);
    }
}

function result_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM result WHERE result_id = ?");
    $stmt->execute([$id]);
}

function results_for_student(int $studentId): array {
    $stmt = db()->prepare(
        "SELECT r.*, a.title, a.assessment_type, a.max_score, c.course_code, c.course_name
         FROM result r
         JOIN assessment a ON a.assessment_id = r.assessment_id
         JOIN course c ON c.course_id = a.course_id
         WHERE r.student_id = ?
         ORDER BY c.course_code, a.date");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll();
}

/** Pass/fail counts per course (for reports). */
function result_pass_counts(): array {
    $sql = "SELECT c.course_code, c.course_name,
                   COUNT(r.result_id) AS results,
                   SUM(CASE WHEN r.grade IN ('A','B','C','D') THEN 1 ELSE 0 END) AS passed,
                   SUM(CASE WHEN r.grade = 'F' THEN 1 ELSE 0 END) AS failed
            FROM course c
            LEFT JOIN assessment a ON a.course_id = c.course_id
            LEFT JOIN result r ON r.assessment_id = a.assessment_id
            GROUP BY c.course_id
            ORDER BY c.course_code";
    return db()->query($sql)->fetchAll();
}
