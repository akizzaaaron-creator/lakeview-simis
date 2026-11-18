<?php
/** models/dashboard.php — Aggregate statistics for dashboards. */

function dashboard_stats(): array {
    $stats = [];
    $stats['students']         = (int) db()->query("SELECT COUNT(*) FROM student")->fetchColumn();
    $stats['active_students']  = (int) db()->query("SELECT COUNT(*) FROM student WHERE status='active'")->fetchColumn();
    $stats['programmes']       = (int) db()->query("SELECT COUNT(*) FROM programme WHERE status='active'")->fetchColumn();
    $stats['courses']          = (int) db()->query("SELECT COUNT(*) FROM course WHERE status='active'")->fetchColumn();
    $stats['enrolments']       = (int) db()->query("SELECT COUNT(*) FROM enrolment WHERE status='active'")->fetchColumn();
    $stats['fee_charged']      = (float) db()->query("SELECT COALESCE(SUM(amount),0) FROM fee_charge")->fetchColumn();
    $stats['fee_collected']    = (float) db()->query("SELECT COALESCE(SUM(amount),0) FROM payment")->fetchColumn();
    $stats['fee_outstanding']  = $stats['fee_charged'] - $stats['fee_collected'];
    $stats['assessments']      = (int) db()->query("SELECT COUNT(*) FROM assessment")->fetchColumn();
    $stats['results_recorded'] = (int) db()->query("SELECT COUNT(*) FROM result")->fetchColumn();
    return $stats;
}

function programme_enrolment_counts(): array {
    $sql = "SELECT p.programme_id, p.programme_code, p.programme_name,
                   COUNT(e.enrolment_id) AS n
            FROM programme p
            LEFT JOIN enrolment e ON e.programme_id = p.programme_id AND e.status = 'active'
            GROUP BY p.programme_id
            ORDER BY p.programme_code";
    return db()->query($sql)->fetchAll();
}

function recent_payments(int $limit = 5): array {
    $sql = "SELECT p.payment_id, p.amount, p.payment_date, p.reference_no, p.payment_method,
                   s.student_no, s.full_name
            FROM payment p JOIN student s ON s.student_id = p.student_id
            ORDER BY p.payment_date DESC, p.payment_id DESC LIMIT " . (int)$limit;
    return db()->query($sql)->fetchAll();
}

function fee_status_counts(): array {
    return db()->query("SELECT status, COUNT(*) AS n, COALESCE(SUM(amount),0) AS total FROM fee_charge GROUP BY status")->fetchAll();
}

function students_by_status(): array {
    return db()->query("SELECT status, COUNT(*) AS n FROM student GROUP BY status")->fetchAll();
}

function attendance_overview(): array {
    $sql = "SELECT COUNT(*) AS records,
                   SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) AS present,
                   SUM(CASE WHEN status='late' THEN 1 ELSE 0 END) AS late,
                   SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) AS absent,
                   SUM(CASE WHEN status='excused' THEN 1 ELSE 0 END) AS excused
            FROM attendance";
    return db()->query($sql)->fetch() ?: [];
}
