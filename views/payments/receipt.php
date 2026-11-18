<?php /** @var array $p */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
  <h4 class="mb-0">Payment Receipt</h4>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=payments" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
  </div>
</div>

<div class="card receipt">
  <div class="card-body">
    <div class="text-center mb-4">
      <i class="bi-mortarboard-fill text-primary" style="font-size:2rem"></i>
      <h5 class="mb-0"><?= e(APP_NAME) ?></h5>
      <small class="text-muted">Lakeview Skills Institute, Uganda</small>
    </div>
    <h6 class="text-center text-muted text-uppercase mb-3">Payment Receipt</h6>

    <table class="table table-borderless">
      <tbody>
        <tr><td class="text-muted" style="width:40%">Receipt No.</td><td><strong><?= e($p['reference_no'] ?: 'RCT-' . str_pad((string)$p['payment_id'], 4, '0', STR_PAD_LEFT)) ?></strong></td></tr>
        <tr><td class="text-muted">Date</td><td><?= e(fmt_date($p['payment_date'])) ?></td></tr>
        <tr><td class="text-muted">Student</td><td><?= e($p['student_no']) ?> — <?= e($p['full_name']) ?></td></tr>
        <tr><td class="text-muted">Contact</td><td><?= e($p['phone'] ?: '-') ?></td></tr>
        <tr><td class="text-muted">Allocated to</td><td><?= e($p['charge_desc'] ?: 'General payment') ?> <?= $p['charge_type'] ? '(' . e($p['charge_type']) . ')' : '' ?></td></tr>
        <tr><td class="text-muted">Method</td><td><?= e(ucfirst(str_replace('_',' ',$p['payment_method']))) ?></td></tr>
      </tbody>
    </table>

    <hr>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="mb-0">Amount Received</h5>
      <h3 class="text-success mb-0"><?= money($p['amount']) ?></h3>
    </div>

    <div class="text-center text-muted small mt-5 pt-4">
      Received with thanks. This is a computer-generated receipt from the Lakeview SIMIS prototype.<br>
      <span class="text-muted">Recorded on <?= e(date('d M Y H:i')) ?></span>
    </div>
  </div>
</div>
