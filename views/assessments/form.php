<?php /** @var array $old @var array $errors @var bool $editing @var array $courses */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0"><?= $editing ? 'Edit Assessment' : 'Add Assessment' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=assessments" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<form method="post" action="<?= APP_URL ?>/index.php?page=assessments&action=<?= $editing ? 'update&id=' . (int)$old['assessment_id'] : 'store' ?>" class="card">
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label required">Course</label>
        <select name="course_id" class="form-select <?= isset($errors['course_id']) ? 'is-invalid' : '' ?>">
          <option value="">— select course —</option>
          <?php foreach ($courses as $c): ?>
            <option value="<?= (int)$c['course_id'] ?>" <?= (int)($old['course_id'] ?? 0) === (int)$c['course_id'] ? 'selected' : '' ?>><?= e($c['course_code']) ?> — <?= e($c['course_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('course_id', $errors) ?>
      </div>
      <div class="col-md-6">
        <label class="form-label required">Title</label>
        <input type="text" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" value="<?= val('title', $old) ?>" placeholder="e.g. Continuous Assessment">
        <?= err('title', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Type</label>
        <select name="assessment_type" class="form-select">
          <?php foreach (['ca' => 'CA (Coursework)', 'exam' => 'Examination', 'assignment' => 'Assignment'] as $v => $lbl): ?>
            <option value="<?= $v ?>" <?= ($old['assessment_type'] ?? 'ca') === $v ? 'selected' : '' ?>><?= $lbl ?></option>
          <?php endforeach; ?>
        </select>
        <?= err('assessment_type', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label required">Max Score</label>
        <input type="number" step="any" name="max_score" class="form-control <?= isset($errors['max_score']) ? 'is-invalid' : '' ?>" value="<?= val('max_score', $old, '100') ?>">
        <?= err('max_score', $errors) ?>
      </div>
      <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="<?= val('date', $old) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Academic Year</label>
        <input type="text" name="academic_year" class="form-control" value="<?= val('academic_year', $old) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Semester</label>
        <input type="number" name="semester" class="form-control" value="<?= val('semester', $old) ?>" min="1" max="12">
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=assessments" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi-check-lg me-1"></i><?= $editing ? 'Update' : 'Add Assessment' ?></button>
  </div>
</form>
