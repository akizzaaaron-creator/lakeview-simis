<?php
/** models/student.php — Student data access. */

function student_all(?string $search = null, ?string $status = null, int $limit = 0): array {
    $sql = "SELECT s.*, pr.programme_code, pr.programme_name
            FROM student s
            LEFT JOIN (SELECT student_id, programme_id FROM enrolment WHERE enrolment_id IN (SELECT MAX(enrolment_id) FROM enrolment GROUP BY student_id)) le ON le.student_id = s.student_id
            LEFT JOIN programme pr ON pr.programme_id = le.programme_id";
    $w = []; $p = [];
    if ($search !== null && $search !== '') {
        $w[] = "(s.student_no LIKE ? OR s.full_name LIKE ? OR s.phone LIKE ? OR s.email LIKE ?)";
        $like = '%' . $search . '%';
        array_push($p, $like, $like, $like, $like);
    }
    if ($status !== null && $status !== '') {
        $w[] = "s.status = ?";
        $p[] = $status;
    }
    if ($w) $sql .= " WHERE " . implode(' AND ', $w);
    $sql .= " ORDER BY s.student_no";
    if ($limit > 0) $sql .= " LIMIT " . (int)$limit;
    $stmt = db()->prepare($sql);
    $stmt->execute($p);
    return $stmt->fetchAll();
}

function student_find(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM student WHERE student_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function student_create(array $d): int {
    $stmt = db()->prepare(
        "INSERT INTO student (student_no, full_name, gender, date_of_birth, nationality, phone, email, address, admission_date, status, emergency_contact_name, emergency_contact_phone)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $d['student_no'], $d['full_name'], $d['gender'] ?: null, $d['date_of_birth'] ?: null,
        $d['nationality'] ?: null, $d['phone'] ?: null, $d['email'] ?: null, $d['address'] ?: null,
        $d['admission_date'], $d['status'], $d['emergency_contact_name'] ?: null, $d['emergency_contact_phone'] ?: null,
    ]);
    return (int) db()->lastInsertId();
}

function student_update(int $id, array $d): void {
    $stmt = db()->prepare(
        "UPDATE student SET student_no=?, full_name=?, gender=?, date_of_birth=?, nationality=?, phone=?, email=?, address=?, admission_date=?, status=?, emergency_contact_name=?, emergency_contact_phone=? WHERE student_id=?");
    $stmt->execute([
        $d['student_no'], $d['full_name'], $d['gender'] ?: null, $d['date_of_birth'] ?: null,
        $d['nationality'] ?: null, $d['phone'] ?: null, $d['email'] ?: null, $d['address'] ?: null,
        $d['admission_date'], $d['status'], $d['emergency_contact_name'] ?: null, $d['emergency_contact_phone'] ?: null, $id,
    ]);
}

function student_delete(int $id): void {
    $stmt = db()->prepare("DELETE FROM student WHERE student_id = ?");
    $stmt->execute([$id]);
}

function student_count(?string $status = null): int {
    if ($status) {
        $stmt = db()->prepare("SELECT COUNT(*) FROM student WHERE status = ?");
        $stmt->execute([$status]);
    } else {
        $stmt = db()->query("SELECT COUNT(*) FROM student");
    }
    return (int) $stmt->fetchColumn();
}

function student_no_exists(string $no, ?int $exceptId = null): bool {
    if ($exceptId) {
        $stmt = db()->prepare("SELECT 1 FROM student WHERE student_no = ? AND student_id <> ?");
        $stmt->execute([$no, $exceptId]);
    } else {
        $stmt = db()->prepare("SELECT 1 FROM student WHERE student_no = ?");
        $stmt->execute([$no]);
    }
    return (bool) $stmt->fetchColumn();
}
