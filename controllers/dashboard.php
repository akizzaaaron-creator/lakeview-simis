<?php
if (!defined('APP_RUNNING')) { http_response_code(403); exit; }
/**
 * controllers/dashboard.php — role-aware overview dashboard.
 */
require_permission('dashboard');

$stats    = dashboard_stats();
$role     = current_user()['role'];
$enrol    = programme_enrolment_counts();
$payments = recent_payments(5);
$feeSt    = fee_status_counts();
$byStatus = students_by_status();
$att      = attendance_overview();

view('dashboard/index', [
    'title'      => 'Dashboard',
    'stats'      => $stats,
    'role'       => $role,
    'enrol'      => $enrol,
    'payments'   => $payments,
    'feeStatus'  => $feeSt,
    'byStatus'   => $byStatus,
    'att'        => $att,
]);
