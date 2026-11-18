<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/assessments.php — Assessment CRUD.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_assessment(array $d): array {
    $e = [];
    if (empty($d['course_id']) || !course_find((int)$d['course_id'])) $e['course_id'] = 'Select a valid course.';
    if (trim($d['title'] ?? '') === '') $e['title'] = 'Title is required.';
    if (!in_array($d['assessment_type'] ?? '', ['ca','exam','assignment'], true)) $e['assessment_type'] = 'Invalid type.';
    $max = floatify($d['max_score'] ?? 0);
    if ($max <= 0) $e['max_score'] = 'Max score must be greater than zero.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('assessments', 'view');
        $courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $rows = assessment_all($courseId ?: null);
        view('assessments/index', ['title' => 'Assessments', 'assessments' => $rows, 'courses' => course_all(), 'courseId' => $courseId, 'canManage' => can('assessments', 'create')]);
        break;

    case 'create':
        require_permission('assessments', 'create');
        $old = ['course_id' => isset($_GET['course_id']) ? (int)$_GET['course_id'] : '', 'assessment_type' => 'ca', 'max_score' => 100, 'academic_year' => date('Y'), 'semester' => 1];
        view('assessments/form', ['title' => 'Add Assessment', 'old' => $old, 'errors' => [], 'editing' => false, 'courses' => course_all()]);
        break;

    case 'store':
        require_permission('assessments', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=assessments&action=create'); }
        $errors = validate_assessment($_POST);
        if ($errors) { view('assessments/form', ['title' => 'Add Assessment', 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'courses' => course_all()]); break; }
        $newId = assessment_create($_POST);
        flash_set('success', 'Assessment created.');
        redirect(APP_URL . '/index.php?page=results&action=assessment&id=' . $newId);
        break;

    case 'edit':
        require_permission('assessments', 'edit');
        $row = assessment_find($id);
        if (!$row) { flash_set('error', 'Assessment not found.'); redirect(APP_URL . '/index.php?page=assessments'); }
        view('assessments/form', ['title' => 'Edit Assessment', 'old' => $row, 'errors' => [], 'editing' => true, 'courses' => course_all()]);
        break;

    case 'update':
        require_permission('assessments', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=assessments'); }
        $row = assessment_find($id);
        if (!$row) { flash_set('error', 'Assessment not found.'); redirect(APP_URL . '/index.php?page=assessments'); }
        $errors = validate_assessment($_POST);
        if ($errors) { view('assessments/form', ['title' => 'Edit Assessment', 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'courses' => course_all()]); break; }
        assessment_update($id, $_POST);
        flash_set('success', 'Assessment updated.');
        redirect(APP_URL . '/index.php?page=assessments');
        break;

    case 'delete':
        require_permission('assessments', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=assessments'); }
        $row = assessment_find($id);
        if (!$row) { flash_set('error', 'Assessment not found.'); redirect(APP_URL . '/index.php?page=assessments'); }
        assessment_delete($id);
        flash_set('success', 'Assessment deleted.');
        redirect(APP_URL . '/index.php?page=assessments');
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
