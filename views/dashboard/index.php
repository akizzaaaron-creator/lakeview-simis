<?php $u = current_user(); ?>

<div class="page-header">
  <div>
    <h4>Welcome, <?= e($u['full_name']) ?></h4>
    <span class="text-muted" style="font-size:.85rem"><?= e(ROLES[$u['role']] ?? $u['role']) ?> | <?= e(APP_NAME) ?></span>
  </div>
</div>

<!-- ── Stat cards (role-filtered) ── -->
<div class="row g-3 mb-4">
<?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_ADMISSIONS], true)): ?>
  <div class="col-6 col-md-3"><?= stat_card('people-fill',   'Total Students',  $stats['students']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('person-check',  'Active Students', $stats['active_students']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('book-fill',     'Programmes',      $stats['programmes']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('card-checklist','Enrolments',      $stats['enrolments']) ?></div>
<?php endif; ?>

<?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_FINANCE], true)): ?>
  <div class="col-6 col-md-4"><?= stat_card('receipt',           'Fees Charged',     money($stats['fee_charged']),    '#0369a1') ?></div>
  <div class="col-6 col-md-4"><?= stat_card('cash-coin',         'Collected',        money($stats['fee_collected']),  '#16a34a') ?></div>
  <div class="col-6 col-md-4"><?= stat_card('exclamation-circle','Outstanding',      money($stats['fee_outstanding']), '#dc2626') ?></div>
<?php endif; ?>

<?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_ACADEMIC], true)): ?>
  <div class="col-6 col-md-3"><?= stat_card('journal-text',  'Courses',          $stats['courses']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('clipboard-data','Assessments',      $stats['assessments']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('graph-up',      'Results Recorded', $stats['results_recorded']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('calendar-check','Attendance Rows',  (int)($att['records'] ?? 0)) ?></div>
<?php endif; ?>
</div>

<!-- ── Widgets ── -->
<div class="row g-3">

  <?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_ADMISSIONS], true)): ?>
  <div class="col-12 col-lg-6">
    <div class="card h-100">
      <div class="card-header"><i class="bi-bar-chart-line me-1"></i>Active enrolments by programme</div>
      <div class="card-body">
        <?php if (!$enrol): ?>
          <p class="text-muted mb-0">No programme data yet.</p>
        <?php else:
          $max = max(array_map(fn($r) => (int)$r['n'], $enrol)) ?: 1;
          foreach ($enrol as $r):
            $pct = max(2, round((int)$r['n'] / $max * 100));
        ?>
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1" style="font-size:.82rem">
              <span class="fw-semibold"><?= e($r['programme_code']) ?></span>
              <span><?= e($r['programme_name']) ?> &nbsp;<strong><?= (int)$r['n'] ?></strong></span>
            </div>
            <div class="progress" style="height:7px">
              <div class="progress-bar" style="width:<?= $pct ?>%; background:var(--primary)"></div>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_FINANCE], true)): ?>
  <div class="col-12 col-lg-6">
    <div class="card h-100">
      <div class="card-header"><i class="bi-receipt me-1"></i>Fee charges by status</div>
      <div class="card-body">
        <?php
        $feeMap = [];
        foreach ($feeStatus as $r) { $feeMap[$r['status']] = $r; }
        $clrs = ['paid' => '#16a34a', 'partial' => '#0369a1', 'pending' => '#d97706'];
        foreach ($clrs as $st => $clr):
          if (empty($feeMap[$st])) continue;
        ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <span><?= status_badge($st) ?></span>
            <span class="text-muted" style="font-size:.82rem">
              <strong><?= (int)$feeMap[$st]['n'] ?></strong> charges &nbsp;|&nbsp;
              <strong><?= money($feeMap[$st]['total']) ?></strong>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card h-100">
      <div class="card-header">
        <span><i class="bi-cash-coin me-1"></i>Recent payments</span>
        <?php if (can('payments')): ?><a href="<?= APP_URL ?>/index.php?page=payments" class="btn btn-sm btn-outline-primary">View all</a><?php endif; ?>
      </div>
      <div class="card-body p-0">
        <?php if (!$payments): ?>
          <div class="empty-state"><i class="bi-cash-coin"></i><p>No payments recorded.</p></div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead><tr><th>Receipt</th><th>Student</th><th class="text-end">Amount</th><th>Method</th><th>Date</th></tr></thead>
            <tbody>
              <?php foreach ($payments as $p): ?>
              <tr>
                <td><code><?= e($p['reference_no'] ?: '-') ?></code></td>
                <td><?= e($p['student_no']) ?><br><small class="text-muted"><?= e($p['full_name']) ?></small></td>
                <td class="text-end fw-semibold text-success"><?= money($p['amount']) ?></td>
                <td><?= status_badge(str_replace('_', '', $p['payment_method'])) ?></td>
                <td><?= e(fmt_date($p['payment_date'])) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if (in_array($role, [ROLE_ADMIN, ROLE_MANAGEMENT, ROLE_ADMISSIONS, ROLE_ACADEMIC], true)): ?>
  <div class="col-12 col-lg-5">
    <div class="card h-100">
      <div class="card-header"><i class="bi-pie-chart me-1"></i>Students by status</div>
      <div class="card-body">
        <?php
        $stColors = ['active' => '#16a34a', 'suspended' => '#d97706', 'graduated' => '#0369a1', 'withdrawn' => '#64748b'];
        foreach ($byStatus as $r):
          $clr = $stColors[$r['status']] ?? '#64748b';
        ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <span><?= status_badge($r['status']) ?></span>
            <strong><?= (int)$r['n'] ?></strong>
          </div>
        <?php endforeach; ?>
        <div class="mt-3">
          <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700">Attendance snapshot</div>
          <?php foreach (['present' => $att['present'] ?? 0, 'late' => $att['late'] ?? 0, 'absent' => $att['absent'] ?? 0, 'excused' => $att['excused'] ?? 0] as $st => $n): ?>
            <div class="d-flex justify-content-between py-1"><span><?= status_badge($st) ?></span><strong><?= (int)$n ?></strong></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
