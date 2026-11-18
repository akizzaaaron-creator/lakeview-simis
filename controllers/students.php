<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/students.php - Student CRUD + profile.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/** Validate student input. Returns associative array of field => message. */
function validate_student(array $d, ?int $exceptId = null): array {
    $e = [];
    $studentNo = trim($d['student_no'] ?? '');
    $fullName  = trim($d['full_name'] ?? '');
    $admDate   = trim($d['admission_date'] ?? '');
    $dob       = trim($d['date_of_birth'] ?? '');
    $email     = trim($d['email'] ?? '');
    $gender    = $d['gender'] ?? '';
    $status    = $d['status'] ?? '';

    if ($studentNo === '') $e['student_no'] = 'Student number is required.';
    elseif (student_no_exists($studentNo, $exceptId)) $e['student_no'] = 'Student number already exists.';
    if ($fullName === '') $e['full_name'] = 'Full name is required.';
    if ($admDate === '') $e['admission_date'] = 'Admission date is required.';
    elseif (false === strtotime($admDate)) $e['admission_date'] = 'Invalid date.';
    if ($dob !== '' && false === strtotime($dob)) $e['date_of_birth'] = 'Invalid date.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $e['email'] = 'Invalid email address.';
    if ($gender !== '' && !in_array($gender, ['male', 'female', 'other'], true)) $e['gender'] = 'Invalid gender.';
    if (!in_array($status, STUDENT_STATUSES, true)) $e['status'] = 'Invalid status.';
    return $e;
}

switch ($action) {

    case 'index':
        require_permission('students', 'view');
        $search  = trim($_GET['search'] ?? '');
        $status  = trim($_GET['status'] ?? '');
        $students = student_all($search !== '' ? $search : null, $status !== '' ? $status : null);
        view('students/index', ['title' => 'Students', 'students' => $students, 'search' => $search, 'status' => $status, 'canManage' => can('students', 'create')]);
        break;

    case 'create':
        require_permission('students', 'create');
        view('students/form', ['title' => 'Add Student', 'student' => [], 'old' => [], 'errors' => [], 'editing' => false]);
        break;

    case 'store':
        require_permission('students', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=students&action=create'); }
        $d = $_POST;
        $errors = validate_student($d);
        if ($errors) {
            view('students/form', ['title' => 'Add Student', 'student' => [], 'old' => $d, 'errors' => $errors, 'editing' => false]);
            break;
        }
        $newId = student_create($d);
        flash_set('success', "Student registered successfully (ID $newId).");
        redirect(APP_URL . '/index.php?page=students&action=show&id=' . $newId);
        break;

    case 'edit':
        require_permission('students', 'edit');
        $student = student_find($id);
        if (!$student) { flash_set('error', 'Student not found.'); redirect(APP_URL . '/index.php?page=students'); }
        view('students/form', ['title' => 'Edit Student', 'student' => $student, 'old' => $student, 'errors' => [], 'editing' => true]);
        break;

    case 'update':
        require_permission('students', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=students'); }
        $student = student_find($id);
        if (!$student) { flash_set('error', 'Student not found.'); redirect(APP_URL . '/index.php?page=students'); }
        $errors = validate_student($_POST, $id);
        if ($errors) {
            view('students/form', ['title' => 'Edit Student', 'student' => $student, 'old' => $_POST, 'errors' => $errors, 'editing' => true]);
            break;
        }
        student_update($id, $_POST);
        flash_set('success', 'Student updated successfully.');
        redirect(APP_URL . '/index.php?page=students&action=show&id=' . $id);
        break;

    case 'delete':
        require_permission('students', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=students'); }
        $student = student_find($id);
        if (!$student) { flash_set('error', 'Student not found.'); redirect(APP_URL . '/index.php?page=students'); }
        student_delete($id);
        flash_set('success', 'Student record deleted.');
        redirect(APP_URL . '/index.php?page=students');
        break;

    case 'show':
        require_permission('students', 'view');
        $student = student_find($id);
        if (!$student) { flash_set('error', 'Student not found.'); redirect(APP_URL . '/index.php?page=students'); }
        $enrolments = enrolments_for_student($id);
        $charged    = fee_charge_total($id);
        $paid       = payment_total($id);
        $charges    = fee_charge_all($id);
        $payments   = payment_all($id);
        $att        = attendance_rate($id);
        $attRecords = attendance_all(null, null, $id);
        $results    = results_for_student($id);
        view('students/show', [
            'title' => 'Student Profile', 'student' => $student, 'enrolments' => $enrolments,
            'charged' => $charged, 'paid' => $paid, 'balance' => $charged - $paid,
            'charges' => $charges, 'payments' => $payments, 'att' => $att, 'attRecords' => $attRecords,
            'results' => $results, 'canManage' => can('students', 'create'),
        ]);
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
