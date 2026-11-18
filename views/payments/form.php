<?php /** @var array $old @var array $errors @var bool $editing @var array $students @var array $charges */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Payment' : 'Record Payment' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=payments" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=payments&action=<?= $editing ? 'update&id=' . (int)$old['payment_id'] : 'store' ?>" class="card" id="paymentForm">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label required">Student</label>
        <select name="student_id" id="studentSelect" class="form-select <?= isset($errors['student_id']) ? 'is-invalid' : '' ?>">
          <option value="">- select student -</option>
          <?php foreach ($students as $st): ?>
            <option value="<?= (int)$st['student_id'] ?>" <?= (int)($old['student_id'] ?? 0) === (int)$st['student_id'] ? 'selected' : '' ?>><?= e($st['student_no']) ?> - <?= e($st['full_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('student_id', $errors) ?>
        <small class="text-muted">Changing the student reloads its fee charges.</small>
      </div>
      <div class="col-md-6">
        <label class="form-label">Allocate to Fee Charge (optional)</label>
        <select name="fee_charge_id" class="form-select">
          <option value="">- general payment / not allocated -</option>
          <?php foreach ($charges as $c): ?>
            <option value="<?= (int)$c['fee_charge_id'] ?>" <?= (int)($old['fee_charge_id'] ?? 0) === (int)$c['fee_charge_id'] ? 'selected' : '' ?>><?= e($c['description'] ?: $c['charge_type']) ?> (<?= money($c['amount']) ?>, <?= e($c['status']) ?>)</option>
          <?php endforeach; ?>
        </select>
        <?php if (!$charges): ?><small class="text-muted">No existing charges for this student.</small><?php endif; ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Amount (UGX)</label>
        <input type="number" step="any" name="amount" class="form-control <?= isset($errors['amount']) ? 'is-invalid' : '' ?>" value="<?= val('amount', $old) ?>" placeholder="500000">
        <?= err('amount', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Payment Date</label>
        <input type="date" name="payment_date" class="form-control <?= isset($errors['payment_date']) ? 'is-invalid' : '' ?>" value="<?= val('payment_date', $old) ?>">
        <?= err('payment_date', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Method</label>
        <select name="payment_method" class="form-select">
          <?php foreach (PAYMENT_METHODS as $m): ?>
            <option value="<?= $m ?>" <?= ($old['payment_method'] ?? 'cash') === $m ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ', $m)) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('payment_method', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label">Reference / Receipt No.</label>
        <input type="text" name="reference_no" class="form-control" value="<?= val('reference_no', $old) ?>" placeholder="RCT-2024-0001">
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=payments" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update Payment' : 'Record Payment' ?></button>
  </div>
</form>

<script>
// Reload the create page with the selected student so its fee charges load.
document.getElementById('studentSelect').addEventListener('change', function () {
  var sid = this.value;
  window.location.href = '<?= APP_URL ?>/index.php?page=payments&action=create' + (sid ? '&student_id=' + sid : '');
});
</script>
