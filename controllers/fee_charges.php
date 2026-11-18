<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/fee_charges.php — Fee charge CRUD + balances overview.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_fee_charge(array $d): array {
    $e = [];
    if (empty($d['student_id']) || !student_find((int)$d['student_id'])) $e['student_id'] = 'Select a valid student.';
    $amt = floatify($d['amount'] ?? 0);
    if ($amt <= 0) $e['amount'] = 'Amount must be greater than zero.';
    if (!in_array($d['charge_type'] ?? '', CHARGE_TYPES, true)) $e['charge_type'] = 'Invalid charge type.';
    if (!in_array($d['status'] ?? '', ['pending', 'partial', 'paid'], true)) $e['status'] = 'Invalid status.';
    $due = trim($d['due_date'] ?? '');
    if ($due !== '' && false === strtotime($due)) $e['due_date'] = 'Invalid date.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('fee_charges', 'view');
        $studentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
        $status = trim($_GET['status'] ?? '');
        $rows = fee_charge_all($studentId ?: null, $status !== '' ? $status : null);
        $totalCharged = array_sum(array_map(fn($r) => (float)$r['amount'], $rows));
        view('fee_charges/index', ['title' => 'Fee Charges', 'charges' => $rows, 'students' => student_all(), 'studentId' => $studentId, 'status' => $status, 'totalCharged' => $totalCharged, 'canManage' => can('fee_charges', 'create')]);
        break;

    case 'balances':
        require_permission('fee_charges', 'view');
        $rows = db()->query("SELECT s.student_id, s.student_no, s.full_name,
                COALESCE((SELECT SUM(amount) FROM fee_charge c WHERE c.student_id = s.student_id),0) AS charged,
                COALESCE((SELECT SUM(amount) FROM payment p WHERE p.student_id = s.student_id),0) AS paid
              FROM student s GROUP BY s.student_id ORDER BY s.student_no")->fetchAll();
        view('fee_charges/balances', ['title' => 'Fee Balances', 'rows' => $rows, 'canManage' => can('payments', 'create')]);
        break;

    case 'create':
        require_permission('fee_charges', 'create');
        $old = ['student_id' => isset($_GET['student_id']) ? (int)$_GET['student_id'] : '', 'charge_type' => 'tuition', 'status' => 'pending', 'academic_year' => date('Y'), 'semester' => 1];
        view('fee_charges/form', ['title' => 'Add Fee Charge', 'old' => $old, 'errors' => [], 'editing' => false, 'students' => student_all()]);
        break;

    case 'store':
        require_permission('fee_charges', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=fee_charges&action=create'); }
        $errors = validate_fee_charge($_POST);
        if ($errors) { view('fee_charges/form', ['title' => 'Add Fee Charge', 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'students' => student_all()]); break; }
        fee_charge_create($_POST);
        flash_set('success', 'Fee charge recorded.');
        redirect(APP_URL . '/index.php?page=fee_charges');
        break;

    case 'edit':
        require_permission('fee_charges', 'edit');
        $row = fee_charge_find($id);
        if (!$row) { flash_set('error', 'Fee charge not found.'); redirect(APP_URL . '/index.php?page=fee_charges'); }
        view('fee_charges/form', ['title' => 'Edit Fee Charge', 'old' => $row, 'errors' => [], 'editing' => true, 'students' => student_all()]);
        break;

    case 'update':
        require_permission('fee_charges', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=fee_charges'); }
        $row = fee_charge_find($id);
        if (!$row) { flash_set('error', 'Fee charge not found.'); redirect(APP_URL . '/index.php?page=fee_charges'); }
        $errors = validate_fee_charge($_POST);
        if ($errors) { view('fee_charges/form', ['title' => 'Edit Fee Charge', 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'students' => student_all()]); break; }
        fee_charge_update($id, $_POST);
        flash_set('success', 'Fee charge updated.');
        redirect(APP_URL . '/index.php?page=fee_charges');
        break;

    case 'delete':
        require_permission('fee_charges', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=fee_charges'); }
        $row = fee_charge_find($id);
        if (!$row) { flash_set('error', 'Fee charge not found.'); redirect(APP_URL . '/index.php?page=fee_charges'); }
        // Linked payments are kept (their fee_charge_id is set NULL via FK) so balances stay correct.
        fee_charge_delete($id);
        flash_set('success', 'Fee charge deleted.');
        redirect(APP_URL . '/index.php?page=fee_charges');
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
