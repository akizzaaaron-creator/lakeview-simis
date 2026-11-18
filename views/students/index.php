<?php /** @var array $students @var string $search @var string $status @var bool $canManage */ ?>

<div class="page-header">
  <h4>Students</h4>
  <?php if ($canManage): ?>
    <a href="<?= APP_URL ?>/index.php?page=students&action=create" class="btn btn-primary btn-sm">
      <i class="bi-plus-lg me-1"></i>Register Student
    </a>
  <?php endif; ?>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="students">
    <div class="col-md-5">
      <label class="form-label">Search</label>
      <input type="text" name="search" class="form-control form-control-sm"
             value="<?= e($search) ?>" placeholder="Student no, name, phone or email">
    </div>
    <div class="col-md-4">
      <label class="form-label">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">All statuses</option>
        <?php foreach (STUDENT_STATUSES as $st): ?>
          <option value="<?= e($st) ?>" <?= $status === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi-search me-1"></i>Search</button>
      <a href="<?= APP_URL ?>/index.php?page=students" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$students): ?>
      <div class="empty-state"><i class="bi-people"></i><p>No students found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Student No</th><th>Name</th><th>Programme</th><th>Gender</th><th>Phone</th><th>Status</th><th>Admitted</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($students as $s): ?>
          <tr>
            <td><code><?= e($s['student_no']) ?></code></td>
            <td class="fw-semibold"><?= e($s['full_name']) ?></td>
            <td>
              <?php if ($s['programme_code']): ?>
                <span class="badge bg-primary"><?= e($s['programme_code']) ?></span>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td><?= e(ucfirst($s['gender'] ?? '') ?: '-') ?></td>
            <td><?= e($s['phone'] ?: '-') ?></td>
            <td><?= status_badge($s['status']) ?></td>
            <td><?= e(fmt_date($s['admission_date'])) ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$s['student_id'] ?>"
                   class="btn btn-sm btn-outline-primary" title="View profile"><i class="bi-eye"></i></a>
                <?php if ($canManage): ?>
                <a href="<?= APP_URL ?>/index.php?page=students&action=edit&id=<?= (int)$s['student_id'] ?>"
                   class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi-pencil"></i></a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($students) ?> student(s) found</div>
    <?php endif; ?>
  </div>
</div>
