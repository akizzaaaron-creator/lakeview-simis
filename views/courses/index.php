<?php /** @var array $courses @var array $programmes @var int $progFilter @var bool $canManage */ ?>

<div class="page-header">
  <h4>Courses</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=courses&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Add Course
  </a>
  <?php endif; ?>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="courses">
    <div class="col-md-7">
      <label class="form-label">Filter by Programme</label>
      <select name="programme_id" class="form-select form-select-sm">
        <option value="0">All programmes</option>
        <?php foreach ($programmes as $p): ?>
          <option value="<?= (int)$p['programme_id'] ?>" <?= $progFilter === (int)$p['programme_id'] ? 'selected' : '' ?>>
            <?= e($p['programme_code']) ?> — <?= e($p['programme_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=courses" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$courses): ?>
      <div class="empty-state"><i class="bi-journal-text"></i><p>No courses found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Code</th><th>Course</th><th>Programme</th>
          <th class="text-center">Sem</th><th class="text-center">Credits</th>
          <th>Status</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($courses as $c): ?>
          <tr>
            <td><code><?= e($c['course_code']) ?></code></td>
            <td class="fw-semibold"><?= e($c['course_name']) ?></td>
            <td><span class="badge bg-primary"><?= e($c['programme_code']) ?></span></td>
            <td class="text-center"><?= (int)$c['semester'] ?></td>
            <td class="text-center"><?= (int)$c['credit_units'] ?></td>
            <td><?= status_badge($c['status']) ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=courses&action=show&id=<?= (int)$c['course_id'] ?>"
                   class="btn btn-sm btn-outline-primary"><i class="bi-eye"></i></a>
                <?php if ($canManage): ?>
                <a href="<?= APP_URL ?>/index.php?page=courses&action=edit&id=<?= (int)$c['course_id'] ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="bi-pencil"></i></a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($courses) ?> course(s)</div>
    <?php endif; ?>
  </div>
</div>
