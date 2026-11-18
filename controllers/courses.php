<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/courses.php - Course CRUD. Each course belongs to one programme.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_course(array $d, ?int $exceptId = null): array {
    $e = [];
    $code = trim($d['course_code'] ?? '');
    $name = trim($d['course_name'] ?? '');
    if ($code === '') $e['course_code'] = 'Course code is required.';
    elseif (!preg_match('/^[A-Z0-9]{1,20}$/', $code)) $e['course_code'] = 'Use uppercase letters/numbers, max 20.';
    elseif (course_code_exists($code, $exceptId)) $e['course_code'] = 'Course code already exists.';
    if ($name === '') $e['course_name'] = 'Course name is required.';
    if (empty($d['programme_id']) || !programme_find((int)$d['programme_id'])) $e['programme_id'] = 'Select a valid programme.';
    if (!in_array($d['status'] ?? '', ['active', 'inactive'], true)) $e['status'] = 'Invalid status.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('courses', 'view');
        $progFilter = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;
        $rows = course_all($progFilter ?: null);
        view('courses/index', ['title' => 'Courses', 'courses' => $rows, 'programmes' => programme_all(), 'progFilter' => $progFilter, 'canManage' => can('courses', 'create')]);
        break;

    case 'create':
        require_permission('courses', 'create');
        $presetProg = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;
        view('courses/form', ['title' => 'Add Course', 'course' => [], 'old' => ['programme_id' => $presetProg, 'semester' => 1, 'credit_units' => 3, 'status' => 'active'], 'errors' => [], 'editing' => false, 'programmes' => programme_all()]);
        break;

    case 'store':
        require_permission('courses', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=courses&action=create'); }
        $errors = validate_course($_POST);
        if ($errors) { view('courses/form', ['title' => 'Add Course', 'course' => [], 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'programmes' => programme_all()]); break; }
        $newId = course_create($_POST);
        flash_set('success', 'Course created successfully.');
        redirect(APP_URL . '/index.php?page=courses&action=show&id=' . $newId);
        break;

    case 'edit':
        require_permission('courses', 'edit');
        $row = course_find($id);
        if (!$row) { flash_set('error', 'Course not found.'); redirect(APP_URL . '/index.php?page=courses'); }
        view('courses/form', ['title' => 'Edit Course', 'course' => $row, 'old' => $row, 'errors' => [], 'editing' => true, 'programmes' => programme_all()]);
        break;

    case 'update':
        require_permission('courses', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=courses'); }
        $row = course_find($id);
        if (!$row) { flash_set('error', 'Course not found.'); redirect(APP_URL . '/index.php?page=courses'); }
        $errors = validate_course($_POST, $id);
        if ($errors) { view('courses/form', ['title' => 'Edit Course', 'course' => $row, 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'programmes' => programme_all()]); break; }
        course_update($id, $_POST);
        flash_set('success', 'Course updated successfully.');
        redirect(APP_URL . '/index.php?page=courses&action=show&id=' . $id);
        break;

    case 'delete':
        require_permission('courses', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=courses'); }
        $row = course_find($id);
        if (!$row) { flash_set('error', 'Course not found.'); redirect(APP_URL . '/index.php?page=courses'); }
        course_delete($id);
        flash_set('success', 'Course deleted.');
        redirect(APP_URL . '/index.php?page=courses');
        break;

    case 'show':
        require_permission('courses', 'view');
        $row = course_find($id);
        if (!$row) { flash_set('error', 'Course not found.'); redirect(APP_URL . '/index.php?page=courses'); }
        $assessments = assessments_for_course($id);
        view('courses/show', ['title' => 'Course', 'course' => $row, 'assessments' => $assessments, 'canManage' => can('courses', 'create'), 'canManageAssessments' => can('assessments', 'create')]);
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
