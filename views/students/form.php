<?php /** @var array $student @var array $old @var array $errors @var bool $editing */ ?>

<div class="page-header">
  <h4><?= $editing ? 'Edit Student' : 'Register Student' ?></h4>
  <a href="<?= APP_URL ?>/index.php?page=students" class="btn btn-outline-secondary btn-sm">
    <i class="bi-arrow-left me-1"></i>Back
  </a>
</div>

<form method="post"
      action="<?= APP_URL ?>/index.php?page=students&action=<?= $editing ? 'update&id=' . (int)$student['student_id'] : 'store' ?>">
  <?= csrf_field() ?>

  <div class="card mb-3">
    <div class="card-header">Personal Information</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label required">Student Number</label>
          <input type="text" name="student_no"
                 class="form-control <?= isset($errors['student_no']) ? 'is-invalid' : '' ?>"
                 value="<?= val('student_no', $old) ?>" placeholder="LVS/2024/001">
          <?= err('student_no', $errors) ?>
        </div>
        <div class="col-md-5">
          <label class="form-label required">Full Name</label>
          <input type="text" name="full_name"
                 class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                 value="<?= val('full_name', $old) ?>">
          <?= err('full_name', $errors) ?>
        </div>
        <div class="col-md-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">—</option>
            <?php foreach (['male', 'female', 'other'] as $g): ?>
              <option value="<?= $g ?>" <?= ($old['gender'] ?? '') === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Date of Birth</label>
          <input type="date" name="date_of_birth"
                 class="form-control <?= isset($errors['date_of_birth']) ? 'is-invalid' : '' ?>"
                 value="<?= val('date_of_birth', $old) ?>">
          <?= err('date_of_birth', $errors) ?>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nationality</label>
          <input type="text" name="nationality" class="form-control"
                 value="<?= val('nationality', $old, 'Ugandan') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control"
                 value="<?= val('phone', $old) ?>" placeholder="+256 7xx xxx xxx">
        </div>
        <div class="col-md-3">
          <label class="form-label">Email</label>
          <input type="email" name="email"
                 class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                 value="<?= val('email', $old) ?>">
          <?= err('email', $errors) ?>
        </div>
        <div class="col-md-9">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control"
                 value="<?= val('address', $old) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label required">Admission Date</label>
          <input type="date" name="admission_date"
                 class="form-control <?= isset($errors['admission_date']) ? 'is-invalid' : '' ?>"
                 value="<?= val('admission_date', $old) ?>">
          <?= err('admission_date', $errors) ?>
        </div>
        <div class="col-md-3">
          <label class="form-label required">Status</label>
          <select name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
            <?php foreach (STUDENT_STATUSES as $st): ?>
              <option value="<?= $st ?>" <?= ($old['status'] ?? 'active') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
            <?php endforeach; ?>
          </select>
          <?= err('status', $errors) ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">Emergency Contact</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Contact Name</label>
          <input type="text" name="emergency_contact_name" class="form-control"
                 value="<?= val('emergency_contact_name', $old) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Phone</label>
          <input type="text" name="emergency_contact_phone" class="form-control"
                 value="<?= val('emergency_contact_phone', $old) ?>">
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end gap-2">
    <a href="<?= APP_URL ?>/index.php?page=students" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
      <i class="bi-check-lg me-1"></i><?= $editing ? 'Update Student' : 'Register Student' ?>
    </button>
  </div>
</form>
