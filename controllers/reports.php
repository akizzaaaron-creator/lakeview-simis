<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/reports.php — Cross-module summary reports.
 */
$action = $_GET['action'] ?? 'index';
require_permission('reports', 'view');

switch ($action) {
    case 'enrolment':
        $data = report_enrolment_by_programme();
        $total = array_sum(array_map(fn($r) => (int)$r['n'], $data));
        view('reports/enrolment', ['title' => 'Enrolment Report', 'data' => $data, 'total' => $total]);
        break;

    case 'fees':
        $balances = report_student_balances();
        $totCharged = array_sum(array_map(fn($r) => (float)$r['charged'], $balances));
        $totPaid    = array_sum(array_map(fn($r) => (float)$r['paid'], $balances));
        view('reports/fees', ['title' => 'Fee Collection Report', 'balances' => $balances, 'totCharged' => $totCharged, 'totPaid' => $totPaid]);
        break;

    case 'attendance':
        $data = report_attendance_rates();
        view('reports/attendance', ['title' => 'Attendance Report', 'data' => $data]);
        break;

    case 'results':
        $data = report_pass_rates();
        view('reports/results', ['title' => 'Results Report', 'data' => $data]);
        break;

    default:
        $stats = dashboard_stats();
        view('reports/index', ['title' => 'Reports', 'stats' => $stats]);
        break;
}
