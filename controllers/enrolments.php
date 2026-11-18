<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/enrolments.php - Enrolment CRUD (student <-> programme).
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_enrolment(array $d): array {
    $e = [];
    if (empty($d['student_id']) || !student_find((int)$d['student_id'])) $e['student_id'] = 'Select a valid student.';
    if (empty($d['programme_id']) || !programme_find((int)$d['programme_id'])) $e['programme_id'] = 'Select a valid programme.';
    $year = trim($d['academic_year'] ?? '');
    if ($year === '') $e['academic_year'] = 'Academic year is required.';
    $sem = (int)($d['semester'] ?? 0);
    if ($sem < 1 || $sem > 12) $e['semester'] = 'Semester must be 1-12.';
    if (trim($d['enrolment_date'] ?? '') === '') $e['enrolment_date'] = 'Enrolment date is required.';
    elseif (false === strtotime($d['enrolment_date'])) $e['enrolment_date'] = 'Invalid date.';
    if (!in_array($d['status'] ?? '', ['active', 'suspended', 'graduated', 'withdrawn'], true)) $e['status'] = 'Invalid status.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('enrolments', 'view');
        $status = trim($_GET['status'] ?? '');
        $progId = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;
        $rows = enrolment_all($status !== '' ? $status : null, $progId ?: null);
        view('enrolments/index', ['title' => 'Enrolments', 'enrolments' => $rows, 'programmes' => programme_all(), 'status' => $status, 'progId' => $progId, 'canManage' => can('enrolments', 'create')]);
        break;

    case 'create':
        require_permission('enrolments', 'create');
        $old = [
            'student_id' => isset($_GET['student_id']) ? (int)$_GET['student_id'] : '',
            'programme_id' => isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : '',
            'academic_year' => date('Y'), 'semester' => 1, 'enrolment_date' => date('Y-m-d'), 'status' => 'active',
        ];
        view('enrolments/form', ['title' => 'Add Enrolment', 'old' => $old, 'errors' => [], 'editing' => false, 'students' => student_all(), 'programmes' => programme_all()]);
        break;

    case 'store':
        require_permission('enrolments', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=enrolments&action=create'); }
        $errors = validate_enrolment($_POST);
        if ($errors) { view('enrolments/form', ['title' => 'Add Enrolment', 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'students' => student_all(), 'programmes' => programme_all()]); break; }
        enrolment_create($_POST);
        flash_set('success', 'Enrolment recorded successfully.');
        redirect(APP_URL . '/index.php?page=enrolments');
        break;

    case 'edit':
        require_permission('enrolments', 'edit');
        $row = enrolment_find($id);
        if (!$row) { flash_set('error', 'Enrolment not found.'); redirect(APP_URL . '/index.php?page=enrolments'); }
        view('enrolments/form', ['title' => 'Edit Enrolment', 'old' => $row, 'errors' => [], 'editing' => true, 'students' => student_all(), 'programmes' => programme_all()]);
        break;

    case 'update':
        require_permission('enrolments', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=enrolments'); }
        $row = enrolment_find($id);
        if (!$row) { flash_set('error', 'Enrolment not found.'); redirect(APP_URL . '/index.php?page=enrolments'); }
        $errors = validate_enrolment($_POST);
        if ($errors) { view('enrolments/form', ['title' => 'Edit Enrolment', 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'students' => student_all(), 'programmes' => programme_all()]); break; }
        enrolment_update($id, $_POST);
        flash_set('success', 'Enrolment updated successfully.');
        redirect(APP_URL . '/index.php?page=enrolments');
        break;

    case 'delete':
        require_permission('enrolments', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=enrolments'); }
        $row = enrolment_find($id);
        if (!$row) { flash_set('error', 'Enrolment not found.'); redirect(APP_URL . '/index.php?page=enrolments'); }
        enrolment_delete($id);
        flash_set('success', 'Enrolment deleted.');
        redirect(APP_URL . '/index.php?page=enrolments');
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
