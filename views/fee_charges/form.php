<?php /** @var array $old @var array $errors @var bool $editing @var array $students */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Fee Charge' : 'Add Fee Charge' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=fee_charges" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=fee_charges&action=<?= $editing ? 'update&id=' . (int)$old['fee_charge_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label required">Student</label>
        <select name="student_id" class="form-select <?= isset($errors['student_id']) ? 'is-invalid' : '' ?>">
          <option value="">- select student -</option>
          <?php foreach ($students as $st): ?>
            <option value="<?= (int)$st['student_id'] ?>" <?= (int)($old['student_id'] ?? 0) === (int)$st['student_id'] ? 'selected' : '' ?>><?= e($st['student_no']) ?> - <?= e($st['full_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('student_id', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Amount (UGX)</label>
        <input type="number" step="any" name="amount" class="form-control <?= isset($errors['amount']) ? 'is-invalid' : '' ?>" value="<?= val('amount', $old) ?>" placeholder="800000">
        <?= err('amount', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Charge Type</label>
        <select name="charge_type" class="form-select">
          <?php foreach (CHARGE_TYPES as $t): ?>
            <option value="<?= $t ?>" <?= ($old['charge_type'] ?? 'tuition') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('charge_type', $errors) ?>
      </div>
      <div class="col-md-8">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control" value="<?= val('description', $old) ?>" placeholder="Tuition - ICT Semester 1 2024">
      </div>
      <div class="col-md-2">
        <label class="form-label">Academic Year</label>
        <input type="text" name="academic_year" class="form-control" value="<?= val('academic_year', $old) ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Semester</label>
        <input type="number" name="semester" class="form-control" value="<?= val('semester', $old) ?>" min="1" max="12">
      </div>
      <div class="col-md-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control <?= isset($errors['due_date']) ? 'is-invalid' : '' ?>" value="<?= val('due_date', $old) ?>">
        <?= err('due_date', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['pending', 'partial', 'paid'] as $st): ?>
            <option value="<?= $st ?>" <?= ($old['status'] ?? 'pending') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('status', $errors) ?>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=fee_charges" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update Charge' : 'Add Charge' ?></button>
  </div>
</form>
