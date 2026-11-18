<?php
/** models/reports.php — Cross-module aggregate queries for the Reports module. */

function report_enrolment_by_programme(): array {
    return programme_enrolment_counts();
}

/** Fee collection summary per programme. */
function report_fee_collection(): array {
    $sql = "SELECT p.programme_id, p.programme_code, p.programme_name,
                   COALESCE(SUM(DISTINCT fc.amount),0) AS charged,
                   (SELECT COALESCE(SUM(pay.amount),0)
                      FROM payment pay
                      JOIN student s2 ON s2.student_id = pay.student_id
                      JOIN enrolment e2 ON e2.student_id = s2.student_id AND e2.programme_id = p.programme_id
                   ) AS collected
            FROM programme p
            LEFT JOIN enrolment e ON e.programme_id = p.programme_id
            LEFT JOIN student s ON s.student_id = e.student_id
            LEFT JOIN fee_charge fc ON fc.student_id = s.student_id
            GROUP BY p.programme_id
            ORDER BY p.programme_code";
    return db()->query($sql)->fetchAll();
}

/** Per-student fee balance (charged - paid). */
function report_student_balances(): array {
    $sql = "SELECT s.student_no, s.full_name, s.status,
                   COALESCE((SELECT SUM(amount) FROM fee_charge c WHERE c.student_id = s.student_id),0) AS charged,
                   COALESCE((SELECT SUM(amount) FROM payment pay WHERE pay.student_id = s.student_id),0) AS paid
            FROM student s
            GROUP BY s.student_id
            ORDER BY s.student_no";
    return db()->query($sql)->fetchAll();
}

/** Attendance rate per student. */
function report_attendance_rates(): array {
    $sql = "SELECT s.student_no, s.full_name,
                   COUNT(a.attendance_id) AS records,
                   SUM(CASE WHEN a.status IN ('present','late') THEN 1 ELSE 0 END) AS attended
            FROM student s
            LEFT JOIN attendance a ON a.student_id = s.student_id
            GROUP BY s.student_id
            ORDER BY s.student_no";
    return db()->query($sql)->fetchAll();
}

/** Pass/fail counts per course. */
function report_pass_rates(): array {
    return result_pass_counts();
}
