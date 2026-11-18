<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/payments.php — Fee payment CRUD + receipt.
 */
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function validate_payment(array $d): array {
    $e = [];
    if (empty($d['student_id']) || !student_find((int)$d['student_id'])) $e['student_id'] = 'Select a valid student.';
    $amt = floatify($d['amount'] ?? 0);
    if ($amt <= 0) $e['amount'] = 'Amount must be greater than zero.';
    if (trim($d['payment_date'] ?? '') === '') $e['payment_date'] = 'Payment date is required.';
    elseif (false === strtotime($d['payment_date'])) $e['payment_date'] = 'Invalid date.';
    if (!in_array($d['payment_method'] ?? '', PAYMENT_METHODS, true)) $e['payment_method'] = 'Invalid payment method.';
    if (!empty($d['fee_charge_id']) && !fee_charge_find((int)$d['fee_charge_id'])) $e['fee_charge_id'] = 'Invalid fee charge.';
    return $e;
}

switch ($action) {
    case 'index':
        require_permission('payments', 'view');
        $studentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
        $method = trim($_GET['method'] ?? '');
        $rows = payment_all($studentId ?: null, $method !== '' ? $method : null);
        $totalPaid = array_sum(array_map(fn($r) => (float)$r['amount'], $rows));
        view('payments/index', ['title' => 'Payments', 'payments' => $rows, 'students' => student_all(), 'studentId' => $studentId, 'method' => $method, 'totalPaid' => $totalPaid, 'canManage' => can('payments', 'create')]);
        break;

    case 'create':
        require_permission('payments', 'create');
        $sid = isset($_GET['student_id']) ? (int)$_GET['student_id'] : (int)($_GET['student_id'] ?? 0);
        $old = ['student_id' => $sid, 'payment_date' => date('Y-m-d'), 'payment_method' => 'cash', 'fee_charge_id' => '', 'amount' => ''];
        view('payments/form', ['title' => 'Record Payment', 'old' => $old, 'errors' => [], 'editing' => false, 'students' => student_all(), 'charges' => $sid ? fee_charge_all($sid) : []]);
        break;

    case 'store':
        require_permission('payments', 'create');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=payments&action=create'); }
        $errors = validate_payment($_POST);
        $sid = (int)($_POST['student_id'] ?? 0);
        if ($errors) { view('payments/form', ['title' => 'Record Payment', 'old' => $_POST, 'errors' => $errors, 'editing' => false, 'students' => student_all(), 'charges' => $sid ? fee_charge_all($sid) : []]); break; }
        $pid = payment_create($_POST);
        flash_set('success', 'Payment recorded successfully.');
        redirect(APP_URL . '/index.php?page=payments&action=receipt&id=' . $pid);
        break;

    case 'edit':
        require_permission('payments', 'edit');
        $row = payment_find($id);
        if (!$row) { flash_set('error', 'Payment not found.'); redirect(APP_URL . '/index.php?page=payments'); }
        view('payments/form', ['title' => 'Edit Payment', 'old' => $row, 'errors' => [], 'editing' => true, 'students' => student_all(), 'charges' => fee_charge_all((int)$row['student_id'])]);
        break;

    case 'update':
        require_permission('payments', 'edit');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=payments'); }
        $row = payment_find($id);
        if (!$row) { flash_set('error', 'Payment not found.'); redirect(APP_URL . '/index.php?page=payments'); }
        $errors = validate_payment($_POST);
        if ($errors) { view('payments/form', ['title' => 'Edit Payment', 'old' => $_POST, 'errors' => $errors, 'editing' => true, 'students' => student_all(), 'charges' => fee_charge_all((int)$_POST['student_id'])]); break; }
        payment_update($id, $_POST);
        flash_set('success', 'Payment updated.');
        redirect(APP_URL . '/index.php?page=payments');
        break;

    case 'delete':
        require_permission('payments', 'delete');
        if (!csrf_verify()) { flash_set('error', 'Invalid form submission.'); redirect(APP_URL . '/index.php?page=payments'); }
        $row = payment_find($id);
        if (!$row) { flash_set('error', 'Payment not found.'); redirect(APP_URL . '/index.php?page=payments'); }
        payment_delete($id);
        flash_set('success', 'Payment deleted.');
        redirect(APP_URL . '/index.php?page=payments');
        break;

    case 'receipt':
        require_permission('payments', 'view');
        $row = db()->prepare("SELECT p.*, s.student_no, s.full_name, s.phone, fc.description AS charge_desc, fc.charge_type
                              FROM payment p JOIN student s ON s.student_id = p.student_id
                              LEFT JOIN fee_charge fc ON fc.fee_charge_id = p.fee_charge_id
                              WHERE p.payment_id = ?");
        $row->execute([$id]);
        $p = $row->fetch();
        if (!$p) { flash_set('error', 'Payment not found.'); redirect(APP_URL . '/index.php?page=payments'); }
        view('payments/receipt', ['title' => 'Payment Receipt', 'p' => $p]);
        break;

    default:
        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
}
