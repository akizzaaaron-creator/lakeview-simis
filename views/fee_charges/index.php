<?php /** @var array $charges @var array $students @var int $studentId @var string $status @var float $totalCharged @var bool $canManage */ ?>

<div class="page-header">
  <h4>Fee Charges</h4>
  <div class="d-flex gap-2">
    <a href="<?= APP_URL ?>/index.php?page=fee_charges&action=balances" class="btn btn-outline-primary btn-sm">
      <i class="bi-calculator me-1"></i>Balances
    </a>
    <?php if ($canManage): ?>
    <a href="<?= APP_URL ?>/index.php?page=fee_charges&action=create" class="btn btn-primary btn-sm">
      <i class="bi-plus-lg me-1"></i>Add Charge
    </a>
    <?php endif; ?>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-sm-6 col-md-4"><?= stat_card('receipt',           'Total Charged',  money($totalCharged), '#0369a1') ?></div>
  <div class="col-sm-6 col-md-4"><?= stat_card('list-ul',           'Total Records',  count($charges)) ?></div>
  <div class="col-sm-6 col-md-4"><?= stat_card('arrow-right-circle','View Balances →', '') ?></div>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="fee_charges">
    <div class="col-md-5">
      <label class="form-label">Student</label>
      <select name="student_id" class="form-select form-select-sm">
        <option value="0">All students</option>
        <?php foreach ($students as $st): ?>
          <option value="<?= (int)$st['student_id'] ?>" <?= $studentId === (int)$st['student_id'] ? 'selected' : '' ?>>
            <?= e($st['student_no']) ?> - <?= e($st['full_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">All</option>
        <?php foreach (['pending','partial','paid'] as $st): ?>
          <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=fee_charges" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$charges): ?>
      <div class="empty-state"><i class="bi-receipt"></i><p>No fee charges found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Student</th><th>Description</th><th>Type</th>
          <th>Year / Sem</th><th class="text-end">Amount</th><th>Due</th><th>Status</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($charges as $c): ?>
          <tr>
            <td>
              <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$c['student_id'] ?>"
                 class="fw-semibold"><?= e($c['student_no']) ?></a>
              <br><small class="text-muted"><?= e($c['full_name']) ?></small>
            </td>
            <td><?= e($c['description'] ?: '-') ?></td>
            <td><span class="badge bg-secondary"><?= e($c['charge_type']) ?></span></td>
            <td><?= e($c['academic_year'] ?: '-') ?> / <?= $c['semester'] !== null ? (int)$c['semester'] : '-' ?></td>
            <td class="text-end fw-semibold"><?= money($c['amount']) ?></td>
            <td><?= e(fmt_date($c['due_date'])) ?></td>
            <td><?= status_badge($c['status']) ?></td>
            <td class="text-end">
              <?php if ($canManage): ?>
              <a href="<?= APP_URL ?>/index.php?page=fee_charges&action=edit&id=<?= (int)$c['fee_charge_id'] ?>"
                 class="btn btn-sm btn-outline-secondary"><i class="bi-pencil"></i></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
