<?php
/** models/attendance.php - Attendance data access. */

function attendance_all(?int $courseId = null, ?string $date = null, ?int $studentId = null): array {
    $sql = "SELECT a.*, s.student_no, s.full_name, c.course_code, c.course_name, u.full_name AS recorder
            FROM attendance a
            JOIN student s ON s.student_id = a.student_id
            JOIN course c ON c.course_id = a.course_id
            LEFT JOIN user u ON u.user_id = a.recorded_by";
    $w = []; $p = [];
    if ($courseId) { $w[] = "a.course_id = ?"; $p[] = $courseId; }
    if ($date) { $w[] = "a.date = ?"; $p[] = $date; }
    if ($studentId) { $w[] = "a.student_id = ?"; $p[] = $studentId; }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY a.date DESC, s.student_no";
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function attendance_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM attendance WHERE attendance_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function attendance_exists(int $studentId, int $courseId, string $date): bool {
    $stmt = db()->prepare("SELECT 1 FROM attendance WHERE student_id = ? AND course_id = ? AND date = ?");
    $stmt->execute([$studentId, $courseId, $date]);
    return (bool) $stmt->fetchColumn();
}

/** Insert or update a single attendance record (one student, one course, one date). */
function attendance_upsert(int $studentId, int $courseId, string $date, string $status, ?string $remarks, int $recordedBy): void {
    if (attendance_exists($studentId, $courseId, $date)) {
        $stmt = db()->prepare("UPDATE attendance SET status = ?, remarks = ?, recorded_by = ? WHERE student_id = ? AND course_id = ? AND date = ?");
        $stmt->execute([$status, $remarks, $recordedBy, $studentId, $courseId, $date]);
    } else {
        $stmt = db()->prepare("INSERT INTO attendance (student_id, course_id, date, status, remarks, recorded_by) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$studentId, $courseId, $date, $status, $remarks, $recordedBy]);
    }
}

function attendance_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM attendance WHERE attendance_id = ?");
    $stmt->execute([$id]);
}

/** Attendance summary for a student (optionally one course). */
function attendance_rate(int $studentId, ?int $courseId = null): array {
    $sql = "SELECT status FROM attendance WHERE student_id = ?";
    $p = [$studentId];
    if ($courseId) { $sql .= " AND course_id = ?"; $p[] = $courseId; }
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    $rows = $stmt->fetchAll();
    $total = count($rows);
    $present = 0;
    foreach ($rows as $r) {
        if ($r['status'] === 'present' || $r['status'] === 'late') $present++;
    }
    return ['present' => $present, 'total' => $total, 'rate' => $total ? round($present / $total * 100, 1) : 0.0];
}

/** Students enrolled (active) in the programme that a course belongs to - used for marking attendance. */
function attendance_students_for_course(int $courseId): array {
    $sql = "SELECT s.* FROM student s
            JOIN enrolment e ON e.student_id = s.student_id
            JOIN course c ON c.programme_id = e.programme_id
            WHERE c.course_id = ? AND e.status = 'active' AND s.status = 'active'
            ORDER BY s.student_no";
    $stmt = db()->prepare($sql);
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}

/** Existing attendance status for a set of students on a given course/date (keyed by student_id). */
function attendance_status_map(int $courseId, string $date): array {
    $stmt = db()->prepare("SELECT student_id, status, remarks FROM attendance WHERE course_id = ? AND date = ?");
    $stmt->execute([$courseId, $date]);
    $map = [];
    foreach ($stmt->fetchAll() as $r) { $map[(int)$r['student_id']] = $r; }
    return $map;
}
