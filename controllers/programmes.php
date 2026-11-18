<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/programmes.php - Programme CRUD.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_programme(array $d, ?int $exceptId = null): array {
    $e = [];
    $code = trim($d['programme_code'] ?? '');
    $name = trim($d['programme_name'] ?? '');
    if ($code === '') $e['programme_code'] = 'Programme code is required.';
    elseif (!preg_match('/^[A-Z0-9]{1,20}$/', $code)) $e['programme_code'] = 'Use uppercase letters/numbers, max 20.';
    elseif (programme_code_exists($code, $exceptId)) $e['programme_code'] = 'Programme code already exists.';
    if ($name === '') $e['programme_name'] = 'Programme name is required.';
    if (!in_array($d['status'] ?? '', ['active', 'inactive'], true)) $e['status'] = 'Invalid status.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('programmes', 'view');
        $rows = db()->query("SELECT p.*, (SELECT COUNT(*) FROM course c WHERE c.programme_id = p.programme_id) AS course_count, (SELECT COUNT(*) FROM enrolment e WHERE e.programme_id = p.programme_id AND e.status='active') AS enrol_count FROM programme p ORDER BY p.programme_code")->fetchAll();
        view('programmes/index', ['title' => 'Programmes', 'programmes' => $rows, 'canManage' => can('programmes', 'create')]);
        break;

    case 'create':
        require_permission('programmes', 'create');
        view('programmes/form', ['title' => 'Add Programme', 'programme' => [], 'old' => [], 'errors' => [], 'editing' => false]);
        break;

    case 'store':
        require_permission('programmes', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=programmes&action=create'); }
        $errors = validate_programme($_POST);
        if ($errors) { view('programmes/form', ['title' => 'Add Programme', 'programme' => [], 'old' => $_POST, 'errors' => $errors, 'editing' => false]); break; }
        $newId = programme_create($_POST);
        flash_set('success', 'Programme created successfully.');
        redirect(APP_URL . '/index.php?page=programmes&action=show&id=' . $newId);
        break;

    case 'edit':
        require_permission('programmes', 'edit');
        $row = programme_find($id);
        if (!$row) { flash_set('error', 'Programme not found.'); redirect(APP_URL . '/index.php?page=programmes'); }
        view('programmes/form', ['title' => 'Edit Programme', 'programme' => $row, 'old' => $row, 'errors' => [], 'editing' => true]);
        break;

    case 'update':
        require_permission('programmes', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=programmes'); }
        $row = programme_find($id);
        if (!$row) { flash_set('error', 'Programme not found.'); redirect(APP_URL . '/index.php?page=programmes'); }
        $errors = validate_programme($_POST, $id);
        if ($errors) { view('programmes/form', ['title' => 'Edit Programme', 'programme' => $row, 'old' => $_POST, 'errors' => $errors, 'editing' => true]); break; }
        programme_update($id, $_POST);
        flash_set('success', 'Programme updated successfully.');
        redirect(APP_URL . '/index.php?page=programmes&action=show&id=' . $id);
        break;

    case 'delete':
        require_permission('programmes', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=programmes'); }
        $row = programme_find($id);
        if (!$row) { flash_set('error', 'Programme not found.'); redirect(APP_URL . '/index.php?page=programmes'); }
        if (programme_course_count($id) > 0) { flash_set('error', 'Cannot delete: this programme still has courses. Remove or reassign them first.'); redirect(APP_URL . '/index.php?page=programmes&action=show&id=' . $id); }
        programme_delete($id);
        flash_set('success', 'Programme deleted.');
        redirect(APP_URL . '/index.php?page=programmes');
        break;

    case 'show':
        require_permission('programmes', 'view');
        $row = programme_find($id);
        if (!$row) { flash_set('error', 'Programme not found.'); redirect(APP_URL . '/index.php?page=programmes'); }
        $courses = courses_for_programme($id);
        $enrolments = enrolment_all(null, $id);
        view('programmes/show', ['title' => 'Programme', 'programme' => $row, 'courses' => $courses, 'enrolments' => $enrolments, 'canManage' => can('programmes', 'create'), 'canManageCourses' => can('courses', 'create')]);
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
