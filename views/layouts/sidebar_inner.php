<?php $u = current_user(); ?>

<!-- Brand -->
<div class="sidebar-brand">
  <i class="bi-mortarboard-fill sidebar-brand-icon"></i>
  <div>
    <div class="sidebar-brand-name">Lakeview SIMIS</div>
    <div class="sidebar-brand-sub">Skills Institute</div>
  </div>
</div>

<!-- Navigation -->
<nav class="sidebar-nav">

  <a class="sidebar-link <?= is_active('dashboard') ?>" href="<?= APP_URL ?>/index.php?page=dashboard">
    <i class="bi-speedometer2"></i><span>Dashboard</span>
  </a>

  <?php if (can('students') || can('programmes') || can('courses') || can('enrolments')): ?>
  <div class="sidebar-heading">Records</div>
  <?php if (can('students')): ?>
  <a class="sidebar-link <?= is_active('students') ?>" href="<?= APP_URL ?>/index.php?page=students">
    <i class="bi-people"></i><span>Students</span>
  </a>
  <?php endif; ?>
  <?php if (can('programmes')): ?>
  <a class="sidebar-link <?= is_active('programmes') ?>" href="<?= APP_URL ?>/index.php?page=programmes">
    <i class="bi-book"></i><span>Programmes</span>
  </a>
  <?php endif; ?>
  <?php if (can('courses')): ?>
  <a class="sidebar-link <?= is_active('courses') ?>" href="<?= APP_URL ?>/index.php?page=courses">
    <i class="bi-journal-text"></i><span>Courses</span>
  </a>
  <?php endif; ?>
  <?php if (can('enrolments')): ?>
  <a class="sidebar-link <?= is_active('enrolments') ?>" href="<?= APP_URL ?>/index.php?page=enrolments">
    <i class="bi-card-checklist"></i><span>Enrolments</span>
  </a>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (can('fee_charges') || can('payments')): ?>
  <div class="sidebar-heading">Finance</div>
  <?php if (can('fee_charges')): ?>
  <a class="sidebar-link <?= is_active('fee_charges') ?>" href="<?= APP_URL ?>/index.php?page=fee_charges">
    <i class="bi-receipt"></i><span>Fee Charges</span>
  </a>
  <?php endif; ?>
  <?php if (can('payments')): ?>
  <a class="sidebar-link <?= is_active('payments') ?>" href="<?= APP_URL ?>/index.php?page=payments">
    <i class="bi-cash-coin"></i><span>Payments</span>
  </a>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (can('attendance') || can('assessments') || can('results')): ?>
  <div class="sidebar-heading">Academic</div>
  <?php if (can('attendance')): ?>
  <a class="sidebar-link <?= is_active('attendance') ?>" href="<?= APP_URL ?>/index.php?page=attendance">
    <i class="bi-calendar-check"></i><span>Attendance</span>
  </a>
  <?php endif; ?>
  <?php if (can('assessments')): ?>
  <a class="sidebar-link <?= is_active('assessments') ?>" href="<?= APP_URL ?>/index.php?page=assessments">
    <i class="bi-clipboard-data"></i><span>Assessments</span>
  </a>
  <?php endif; ?>
  <?php if (can('results')): ?>
  <a class="sidebar-link <?= is_active('results') ?>" href="<?= APP_URL ?>/index.php?page=results">
    <i class="bi-graph-up"></i><span>Results</span>
  </a>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (can('users')): ?>
  <div class="sidebar-heading">Administration</div>
  <a class="sidebar-link <?= is_active('users') ?>" href="<?= APP_URL ?>/index.php?page=users">
    <i class="bi-shield-lock"></i><span>Users</span>
  </a>
  <?php endif; ?>

  <?php if (can('reports')): ?>
  <div class="sidebar-heading">Insights</div>
  <a class="sidebar-link <?= is_active('reports') ?>" href="<?= APP_URL ?>/index.php?page=reports">
    <i class="bi-bar-chart-line"></i><span>Reports</span>
  </a>
  <?php endif; ?>

</nav>

<!-- Sidebar footer -->
<div class="sidebar-footer">
  <div class="sidebar-footer-role"><?= e(ROLES[$u['role']] ?? $u['role']) ?></div>
  <div class="sidebar-footer-name"><?= e($u['full_name']) ?></div>
</div>
