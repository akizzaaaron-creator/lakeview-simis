<?php /** @var array $assessments @var array $courses @var int $courseId @var bool $canManage */ ?>

<div class="page-header">
  <h4>Assessments</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=assessments&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Add Assessment
  </a>
  <?php endif; ?>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="assessments">
    <div class="col-md-7">
      <label class="form-label">Filter by Course</label>
      <select name="course_id" class="form-select form-select-sm">
        <option value="0">All courses</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= (int)$c['course_id'] ?>" <?= $courseId === (int)$c['course_id'] ? 'selected' : '' ?>>
            <?= e($c['course_code']) ?> — <?= e($c['course_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=assessments" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$assessments): ?>
      <div class="empty-state"><i class="bi-clipboard-data"></i><p>No assessments found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Title</th><th>Course</th><th>Type</th>
          <th class="text-center">Max Score</th><th>Date</th><th>Year / Sem</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($assessments as $a): ?>
          <tr>
            <td class="fw-semibold"><?= e($a['title']) ?></td>
            <td><code><?= e($a['course_code']) ?></code> <small class="text-muted"><?= e($a['course_name']) ?></small></td>
            <td><span class="badge bg-secondary"><?= e(strtoupper($a['assessment_type'])) ?></span></td>
            <td class="text-center"><?= (float)$a['max_score'] ?></td>
            <td><?= e(fmt_date($a['date'])) ?></td>
            <td><?= e($a['academic_year'] ?: '—') ?> / <?= $a['semester'] !== null ? (int)$a['semester'] : '—' ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=results&action=assessment&id=<?= (int)$a['assessment_id'] ?>"
                   class="btn btn-sm btn-outline-primary"><i class="bi-pencil-square me-1"></i>Scores</a>
                <?php if ($canManage): ?>
                <a href="<?= APP_URL ?>/index.php?page=assessments&action=edit&id=<?= (int)$a['assessment_id'] ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="bi-pencil"></i></a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
