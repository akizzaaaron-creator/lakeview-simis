<?php
/** @var array $student @var array $enrolments @var float $charged @var float $paid @var float $balance
    @var array $charges @var array $payments @var array $att @var array $attRecords @var array $results @var bool $canManage */
$s = $student;
?>

<div class="page-header">
  <div>
    <h4><?= e($s['full_name']) ?></h4>
    <span class="text-muted"><code><?= e($s['student_no']) ?></code> &nbsp;|&nbsp; <?= status_badge($s['status']) ?></span>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= APP_URL ?>/index.php?page=students" class="btn btn-outline-secondary btn-sm">
      <i class="bi-arrow-left me-1"></i>Back
    </a>
    <?php if ($canManage): ?>
    <a href="<?= APP_URL ?>/index.php?page=students&action=edit&id=<?= (int)$s['student_id'] ?>"
       class="btn btn-primary btn-sm"><i class="bi-pencil me-1"></i>Edit</a>
    <?php endif; ?>
  </div>
</div>

<!-- Top info cards -->
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header">Personal Information</div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:.875rem">
          <dt class="col-5 text-muted fw-normal">Gender</dt>      <dd class="col-7"><?= e(ucfirst($s['gender'] ?? '') ?: '-') ?></dd>
          <dt class="col-5 text-muted fw-normal">Date of Birth</dt><dd class="col-7"><?= e(fmt_date($s['date_of_birth'])) ?></dd>
          <dt class="col-5 text-muted fw-normal">Nationality</dt>  <dd class="col-7"><?= e($s['nationality'] ?: '-') ?></dd>
          <dt class="col-5 text-muted fw-normal">Admitted</dt>     <dd class="col-7"><?= e(fmt_date($s['admission_date'])) ?></dd>
          <dt class="col-5 text-muted fw-normal">Phone</dt>        <dd class="col-7"><?= e($s['phone'] ?: '-') ?></dd>
          <dt class="col-5 text-muted fw-normal">Email</dt>        <dd class="col-7"><?= e($s['email'] ?: '-') ?></dd>
          <dt class="col-5 text-muted fw-normal">Address</dt>      <dd class="col-7"><?= e($s['address'] ?: '-') ?></dd>
          <dt class="col-5 text-muted fw-normal">Emergency</dt>    <dd class="col-7"><?= e($s['emergency_contact_name'] ?: '-') ?> <?= e($s['emergency_contact_phone'] ?: '') ?></dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header">Fee Summary</div>
      <div class="card-body">
        <div class="row text-center g-0 mb-3">
          <div class="col-4">
            <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em">Charged</div>
            <div class="fw-bold" style="font-size:1.1rem"><?= money($charged) ?></div>
          </div>
          <div class="col-4">
            <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em">Paid</div>
            <div class="fw-bold text-success" style="font-size:1.1rem"><?= money($paid) ?></div>
          </div>
          <div class="col-4">
            <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.06em">Balance</div>
            <div class="fw-bold <?= $balance > 0 ? 'text-danger' : 'text-success' ?>" style="font-size:1.1rem"><?= money($balance) ?></div>
          </div>
        </div>
        <hr class="my-3">
        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700">Attendance</div>
        <div class="d-flex align-items-center gap-2">
          <span style="font-size:.85rem"><?= $att['present'] ?> / <?= $att['total'] ?> sessions</span>
          <div class="progress flex-grow-1" style="height:8px">
            <div class="progress-bar" style="width:<?= (int)$att['rate'] ?>%; background:var(--primary)"></div>
          </div>
          <strong><?= $att['rate'] ?>%</strong>
        </div>
        <?php if (can('enrolments', 'create')): ?>
        <hr class="my-3">
        <a href="<?= APP_URL ?>/index.php?page=enrolments&action=create&student_id=<?= (int)$s['student_id'] ?>"
           class="btn btn-sm btn-outline-primary"><i class="bi-plus me-1"></i>Add Enrolment</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Detail tabs row -->
<div class="row g-3">

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header">Enrolments</div>
      <div class="card-body p-0">
        <?php if (!$enrolments): ?>
          <div class="empty-state"><i class="bi-book"></i><p>Not enrolled in any programme.</p></div>
        <?php else: ?>
        <table class="table mb-0">
          <thead><tr><th>Programme</th><th>Year</th><th>Sem</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($enrolments as $e): ?>
            <tr>
              <td><span class="badge bg-primary"><?= e($e['programme_code']) ?></span> <?= e($e['programme_name']) ?></td>
              <td><?= e($e['academic_year']) ?></td>
              <td><?= (int)$e['semester'] ?></td>
              <td><?= status_badge($e['status']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header">
        <span>Fee Charges &amp; Payments</span>
        <div class="d-flex gap-1">
          <?php if (can('fee_charges', 'create')): ?>
            <a href="<?= APP_URL ?>/index.php?page=fee_charges&action=create&student_id=<?= (int)$s['student_id'] ?>"
               class="btn btn-sm btn-outline-primary">+ Charge</a>
          <?php endif; ?>
          <?php if (can('payments', 'create')): ?>
            <a href="<?= APP_URL ?>/index.php?page=payments&action=create&student_id=<?= (int)$s['student_id'] ?>"
               class="btn btn-sm btn-outline-primary">+ Payment</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body p-0">
        <?php if (!$charges && !$payments): ?>
          <div class="empty-state"><i class="bi-receipt"></i><p>No fee records.</p></div>
        <?php else: ?>
        <table class="table mb-0">
          <thead><tr><th>Description</th><th class="text-end">Amount</th><th>Status / Ref</th></tr></thead>
          <tbody>
            <?php foreach ($charges as $c): ?>
            <tr>
              <td><?= e($c['description'] ?: ucfirst($c['charge_type'])) ?><br>
                  <small class="text-muted">Due <?= e(fmt_date($c['due_date'])) ?></small></td>
              <td class="text-end"><?= money($c['amount']) ?></td>
              <td><?= status_badge($c['status']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($payments as $pm): ?>
            <tr class="table-success">
              <td><i class="bi-check-circle text-success me-1"></i>Payment</td>
              <td class="text-end text-success fw-semibold">−<?= money($pm['amount']) ?></td>
              <td><code><?= e($pm['reference_no'] ?: '-') ?></code><br>
                  <small class="text-muted"><?= e(fmt_date($pm['payment_date'])) ?></small></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header">Academic Results</div>
      <div class="card-body p-0">
        <?php if (!$results): ?>
          <div class="empty-state"><i class="bi-graph-up"></i><p>No results recorded.</p></div>
        <?php else: ?>
        <table class="table mb-0">
          <thead><tr><th>Course</th><th>Assessment</th><th class="text-end">Score</th><th class="text-center">Grade</th></tr></thead>
          <tbody>
            <?php foreach ($results as $r): ?>
            <tr>
              <td><code><?= e($r['course_code']) ?></code></td>
              <td><?= e($r['title']) ?><br>
                  <small class="text-muted"><?= e($r['assessment_type']) ?> / <?= (float)$r['max_score'] ?></small></td>
              <td class="text-end"><?= (float)$r['score'] ?></td>
              <td class="text-center">
                <span class="badge <?= in_array($r['grade'], ['A','B']) ? 'bg-success' : ($r['grade'] === 'F' ? 'bg-danger' : 'bg-secondary') ?>">
                  <?= e($r['grade']) ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header">Recent Attendance</div>
      <div class="card-body p-0">
        <?php if (!$attRecords): ?>
          <div class="empty-state"><i class="bi-calendar-check"></i><p>No attendance records.</p></div>
        <?php else: ?>
        <table class="table mb-0">
          <thead><tr><th>Date</th><th>Course</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach (array_slice($attRecords, 0, 10) as $a): ?>
            <tr>
              <td><?= e(fmt_date($a['date'])) ?></td>
              <td><code><?= e($a['course_code']) ?></code></td>
              <td><?= status_badge($a['status']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
