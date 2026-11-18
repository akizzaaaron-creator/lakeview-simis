<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/attendance.php - Attendance records and bulk marking.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

switch ($action) {
    case 'index':
        require_permission('attendance', 'view');
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $date     = trim($_GET['date'] ?? '');
        $studentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
        $rows = attendance_all($courseId ?: null, $date !== '' ? $date : null, $studentId ?: null);
        // Attendance overview counts
        $ov = attendance_overview();
        view('attendance/index', ['title' => 'Attendance', 'records' => $rows, 'courses' => course_all(), 'courseId' => $courseId, 'date' => $date, 'studentId' => $studentId, 'ov' => $ov, 'canManage' => can('attendance', 'create')]);
        break;

    case 'mark':
        require_permission('attendance', 'create');
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $date = trim($_GET['date'] ?? '') ?: date('Y-m-d');
        $students = [];
        $statusMap = [];
        if ($courseId) {
            $students = attendance_students_for_course($courseId);
            $statusMap = attendance_status_map($courseId, $date);
        }
        view('attendance/mark', ['title' => 'Mark Attendance', 'courses' => course_all(), 'courseId' => $courseId, 'date' => $date, 'students' => $students, 'statusMap' => $statusMap]);
        break;

    case 'save':
        require_permission('attendance', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=attendance'); }
        $courseId = (int)($_POST['course_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $statuses = $_POST['status'] ?? [];
        $remarks = $_POST['remarks'] ?? [];
        if (!$courseId || !course_find($courseId)) { flash_set('error', 'Select a valid course.'); redirect(APP_URL . '/index.php?page=attendance&action=mark'); }
        if ($date === '' || false === strtotime($date)) { flash_set('error', 'Select a valid date.'); redirect(APP_URL . '/index.php?page=attendance&action=mark&course_id=' . $courseId); }
        $recordedBy = (int) current_user()['user_id'];
        $count = 0;
        foreach ($statuses as $sid => $status) {
            if (!in_array($status, ['present', 'absent', 'late', 'excused'], true)) continue;
            $r = trim($remarks[$sid] ?? '');
            attendance_upsert((int)$sid, $courseId, $date, $status, $r !== '' ? $r : null, $recordedBy);
            $count++;
        }
        flash_set('success', "Attendance saved for $count student(s) on " . fmt_date($date) . '.');
        redirect(APP_URL . '/index.php?page=attendance&action=mark&course_id=' . $courseId . '&date=' . urlencode($date));
        break;

    case 'delete':
        require_permission('attendance', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=attendance'); }
        $row = attendance_find($id);
        if (!$row) { flash_set('error', 'Attendance record not found.'); redirect(APP_URL . '/index.php?page=attendance'); }
        attendance_delete($id);
        flash_set('success', 'Attendance record deleted.');
        redirect(APP_URL . '/index.php?page=attendance');
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
