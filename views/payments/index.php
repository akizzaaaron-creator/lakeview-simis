<?php /** @var array $payments @var array $students @var int $studentId @var string $method @var float $totalPaid @var bool $canManage */ ?>

<div class="page-header">
  <h4>Payments</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=payments&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Record Payment
  </a>
  <?php endif; ?>
</div>

<div class="row g-3 mb-3">
  <div class="col-sm-6"><?= stat_card('cash-coin', 'Total Collected (filtered)', money($totalPaid), '#16a34a') ?></div>
  <div class="col-sm-6"><?= stat_card('receipt',   'Payment Records',           count($payments)) ?></div>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="payments">
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
      <label class="form-label">Method</label>
      <select name="method" class="form-select form-select-sm">
        <option value="">All methods</option>
        <?php foreach (PAYMENT_METHODS as $m): ?>
          <option value="<?= $m ?>" <?= $method === $m ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$m)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=payments" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$payments): ?>
      <div class="empty-state"><i class="bi-cash-coin"></i><p>No payments found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Receipt No.</th><th>Student</th><th class="text-end">Amount</th>
          <th>Method</th><th>Date</th><th>Allocated To</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($payments as $p): ?>
          <tr>
            <td><code><?= e($p['reference_no'] ?: '-') ?></code></td>
            <td>
              <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$p['student_id'] ?>"
                 class="fw-semibold"><?= e($p['student_no']) ?></a>
              <br><small class="text-muted"><?= e($p['full_name']) ?></small>
            </td>
            <td class="text-end fw-semibold text-success"><?= money($p['amount']) ?></td>
            <td><span class="badge bg-secondary"><?= e(ucfirst(str_replace('_',' ',$p['payment_method']))) ?></span></td>
            <td><?= e(fmt_date($p['payment_date'])) ?></td>
            <td><small class="text-muted"><?= e($p['charge_desc'] ?: 'General') ?></small></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=payments&action=receipt&id=<?= (int)$p['payment_id'] ?>"
                   class="btn btn-sm btn-outline-primary" title="Receipt"><i class="bi-receipt"></i></a>
                <?php if ($canManage): ?>
                <a href="<?= APP_URL ?>/index.php?page=payments&action=edit&id=<?= (int)$p['payment_id'] ?>"
                   class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi-pencil"></i></a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
