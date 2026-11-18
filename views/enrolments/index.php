<?php /** @var array $enrolments @var array $programmes @var string $status @var int $progId @var bool $canManage */ ?>

<div class="page-header">
  <h4>Enrolments</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=enrolments&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Add Enrolment
  </a>
  <?php endif; ?>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="enrolments">
    <div class="col-md-5">
      <label class="form-label">Programme</label>
      <select name="programme_id" class="form-select form-select-sm">
        <option value="0">All programmes</option>
        <?php foreach ($programmes as $p): ?>
          <option value="<?= (int)$p['programme_id'] ?>" <?= $progId === (int)$p['programme_id'] ? 'selected' : '' ?>>
            <?= e($p['programme_code']) ?> - <?= e($p['programme_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">All statuses</option>
        <?php foreach (['active','suspended','graduated','withdrawn'] as $st): ?>
          <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=enrolments" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$enrolments): ?>
      <div class="empty-state"><i class="bi-card-checklist"></i><p>No enrolments found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Student</th><th>Programme</th><th>Year</th>
          <th class="text-center">Sem</th><th>Enrolled</th><th>Status</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($enrolments as $e): ?>
          <tr>
            <td>
              <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$e['student_id'] ?>"
                 class="fw-semibold"><?= e($e['student_no']) ?></a>
              <br><small class="text-muted"><?= e($e['full_name']) ?></small>
            </td>
            <td><span class="badge bg-primary"><?= e($e['programme_code']) ?></span></td>
            <td><?= e($e['academic_year']) ?></td>
            <td class="text-center"><?= (int)$e['semester'] ?></td>
            <td><?= e(fmt_date($e['enrolment_date'])) ?></td>
            <td><?= status_badge($e['status']) ?></td>
            <td class="text-end">
              <?php if ($canManage): ?>
              <a href="<?= APP_URL ?>/index.php?page=enrolments&action=edit&id=<?= (int)$e['enrolment_id'] ?>"
                 class="btn btn-sm btn-outline-secondary"><i class="bi-pencil"></i></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($enrolments) ?> enrolment(s)</div>
    <?php endif; ?>
  </div>
</div>
