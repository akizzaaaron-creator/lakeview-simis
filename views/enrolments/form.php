<?php /** @var array $old @var array $errors @var bool $editing @var array $students @var array $programmes */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Enrolment' : 'Add Enrolment' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=enrolments" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=enrolments&action=<?= $editing ? 'update&id=' . (int)$old['enrolment_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label required">Student</label>
        <select name="student_id" class="form-select <?= isset($errors['student_id']) ? 'is-invalid' : '' ?>">
          <option value="">— select student —</option>
          <?php foreach ($students as $st): ?>
            <option value="<?= (int)$st['student_id'] ?>" <?= (int)($old['student_id'] ?? 0) === (int)$st['student_id'] ? 'selected' : '' ?>><?= e($st['student_no']) ?> — <?= e($st['full_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('student_id', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Programme</label>
        <select name="programme_id" class="form-select <?= isset($errors['programme_id']) ? 'is-invalid' : '' ?>">
          <option value="">— select programme —</option>
          <?php foreach ($programmes as $p): ?>
            <option value="<?= (int)$p['programme_id'] ?>" <?= (int)($old['programme_id'] ?? 0) === (int)$p['programme_id'] ? 'selected' : '' ?>><?= e($p['programme_code']) ?> — <?= e($p['programme_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('programme_id', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Academic Year</label>
        <input type="text" name="academic_year" class="form-control <?= isset($errors['academic_year']) ? 'is-invalid' : '' ?>" value="<?= val('academic_year', $old) ?>" placeholder="2024">
        <?= err('academic_year', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Semester</label>
        <input type="number" name="semester" class="form-control" value="<?= val('semester', $old, '1') ?>" min="1" max="12">
        <?= err('semester', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Enrolment Date</label>
        <input type="date" name="enrolment_date" class="form-control <?= isset($errors['enrolment_date']) ? 'is-invalid' : '' ?>" value="<?= val('enrolment_date', $old) ?>">
        <?= err('enrolment_date', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['active', 'suspended', 'graduated', 'withdrawn'] as $st): ?>
            <option value="<?= $st ?>" <?= ($old['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=enrolments" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update Enrolment' : 'Add Enrolment' ?></button>
  </div>
</form>
