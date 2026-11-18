<?php /** @var array $course @var array $old @var array $errors @var bool $editing @var array $programmes */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Course' : 'Add Course' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=courses" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=courses&action=<?= $editing ? 'update&id=' . (int)$course['course_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label required">Course Code</label>
        <input type="text" name="course_code" class="form-control <?= isset($errors['course_code']) ? 'is-invalid' : '' ?>" value="<?= val('course_code', $old) ?>" placeholder="ICT101" maxlength="20" style="text-transform:uppercase">
        <?= err('course_code', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Course Name</label>
        <input type="text" name="course_name" class="form-control <?= isset($errors['course_name']) ? 'is-invalid' : '' ?>" value="<?= val('course_name', $old) ?>">
        <?= err('course_name', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Programme</label>
        <select name="programme_id" class="form-select <?= isset($errors['programme_id']) ? 'is-invalid' : '' ?>">
          <option value="">- select -</option>
          <?php foreach ($programmes as $p): ?>
            <option value="<?= (int)$p['programme_id'] ?>" <?= (int)($old['programme_id'] ?? 0) === (int)$p['programme_id'] ? 'selected' : '' ?>><?= e($p['programme_code']) ?> - <?= e($p['programme_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('programme_id', $errors) ?>
      </div>
      <div class="col-md-9">
        <label class="form-label">Description</label>
        <textarea name="description" rows="2" class="form-control"><?= val('description', $old) ?></textarea>
      </div>
      <div class="col-md-3">
        <label class="form-label">Credit Units</label>
        <input type="number" name="credit_units" class="form-control" value="<?= val('credit_units', $old, '3') ?>" min="0" max="20">
      </div>
      <div class="col-md-3">
        <label class="form-label">Semester</label>
        <input type="number" name="semester" class="form-control" value="<?= val('semester', $old, '1') ?>" min="1" max="12">
      </div>
      <div class="col-md-3">
        <label class="form-label required">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['active', 'inactive'] as $st): ?>
            <option value="<?= $st ?>" <?= ($old['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=courses" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update Course' : 'Add Course' ?></button>
  </div>
</form>
