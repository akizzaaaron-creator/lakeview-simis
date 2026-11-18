<?php $u = current_user(); ?>
<aside class="offcanvas offcanvas-start sidebar" id="sidebar" tabindex="-1" data-bs-scroll="true" data-bs-backdrop="true" aria-label="Main navigation">
  <div class="offcanvas-header sidebar-brand">
    <span class="fw-bold"><i class="bi-mortarboard-fill me-2"></i>Lakeview SIMIS</span>
    <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0 d-flex flex-column">
    <nav class="nav flex-column sidebar-nav flex-grow-1">
      <a class="nav-link sidebar-link <?= is_active('dashboard') ?>" href="<?= APP_URL ?>/index.php?page=dashboard"><i class="bi-speedometer2"></i> Dashboard</a>

      <?php if (can('students') || can('programmes') || can('courses') || can('enrolments')): ?>
        <div class="sidebar-section">Records</div>
        <?php if (can('students')): ?><a class="nav-link sidebar-link <?= is_active('students') ?>" href="<?= APP_URL ?>/index.php?page=students"><i class="bi-people"></i> Students</a><?php endif; ?>
        <?php if (can('programmes')): ?><a class="nav-link sidebar-link <?= is_active('programmes') ?>" href="<?= APP_URL ?>/index.php?page=programmes"><i class="bi-book"></i> Programmes</a><?php endif; ?>
        <?php if (can('courses')): ?><a class="nav-link sidebar-link <?= is_active('courses') ?>" href="<?= APP_URL ?>/index.php?page=courses"><i class="bi-journal-text"></i> Courses</a><?php endif; ?>
        <?php if (can('enrolments')): ?><a class="nav-link sidebar-link <?= is_active('enrolments') ?>" href="<?= APP_URL ?>/index.php?page=enrolments"><i class="bi-card-checklist"></i> Enrolments</a><?php endif; ?>
      <?php endif; ?>

      <?php if (can('fee_charges') || can('payments')): ?>
        <div class="sidebar-section">Finance</div>
        <?php if (can('fee_charges')): ?><a class="nav-link sidebar-link <?= is_active('fee_charges') ?>" href="<?= APP_URL ?>/index.php?page=fee_charges"><i class="bi-receipt"></i> Fee Charges</a><?php endif; ?>
        <?php if (can('payments')): ?><a class="nav-link sidebar-link <?= is_active('payments') ?>" href="<?= APP_URL ?>/index.php?page=payments"><i class="bi-cash-coin"></i> Payments</a><?php endif; ?>
      <?php endif; ?>

      <?php if (can('attendance') || can('assessments') || can('results')): ?>
        <div class="sidebar-section">Academic</div>
        <?php if (can('attendance')): ?><a class="nav-link sidebar-link <?= is_active('attendance') ?>" href="<?= APP_URL ?>/index.php?page=attendance"><i class="bi-calendar-check"></i> Attendance</a><?php endif; ?>
        <?php if (can('assessments')): ?><a class="nav-link sidebar-link <?= is_active('assessments') ?>" href="<?= APP_URL ?>/index.php?page=assessments"><i class="bi-clipboard-data"></i> Assessments</a><?php endif; ?>
        <?php if (can('results')): ?><a class="nav-link sidebar-link <?= is_active('results') ?>" href="<?= APP_URL ?>/index.php?page=results"><i class="bi-graph-up"></i> Results</a><?php endif; ?>
      <?php endif; ?>

      <?php if (can('users')): ?>
        <div class="sidebar-section">Administration</div>
        <a class="nav-link sidebar-link <?= is_active('users') ?>" href="<?= APP_URL ?>/index.php?page=users"><i class="bi-shield-lock"></i> Users</a>
      <?php endif; ?>

      <?php if (can('reports')): ?>
        <div class="sidebar-section">Insights</div>
        <a class="nav-link sidebar-link <?= is_active('reports') ?>" href="<?= APP_URL ?>/index.php?page=reports"><i class="bi-bar-chart-line"></i> Reports</a>
      <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
      <div class="small" style="color:#7fa6ad">Signed in as</div>
      <div class="small text-white"><?= e($u['full_name']) ?></div>
      <span class="badge bg-accent mt-1"><?= e(ROLES[$u['role']] ?? $u['role']) ?></span>
    </div>
  </div>
</aside>
