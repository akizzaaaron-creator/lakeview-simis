<?php /** @var array $results @var array $courses @var array $students @var int $courseId @var int $studentId @var bool $canManage */ ?>

<div class="page-header">
  <h4>Results</h4>
  <a href="<?= APP_URL ?>/index.php?page=assessments" class="btn btn-outline-primary btn-sm">
    <i class="bi-clipboard-data me-1"></i>Assessments
  </a>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="results">
    <div class="col-md-4">
      <label class="form-label">Student</label>
      <select name="student_id" class="form-select form-select-sm">
        <option value="0">All students</option>
        <?php foreach ($students as $st): ?>
          <option value="<?= (int)$st['student_id'] ?>" <?= $studentId === (int)$st['student_id'] ? 'selected' : '' ?>>
            <?= e($st['student_no']) ?> - <?= e($st['full_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Course</label>
      <select name="course_id" class="form-select form-select-sm">
        <option value="0">All courses</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= (int)$c['course_id'] ?>" <?= $courseId === (int)$c['course_id'] ? 'selected' : '' ?>>
            <?= e($c['course_code']) ?> - <?= e($c['course_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4 d-flex gap-2 align-items-end">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=results" class="btn btn-outline-secondary btn-sm">Reset</a>
      <?php if ($studentId): ?>
      <a href="<?= APP_URL ?>/index.php?page=results&action=transcript&student_id=<?= $studentId ?>"
         class="btn btn-outline-primary btn-sm"><i class="bi-file-earmark-text me-1"></i>Transcript</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$results): ?>
      <div class="empty-state"><i class="bi-graph-up"></i><p>No results found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Student</th><th>Course</th><th>Assessment</th><th>Type</th>
          <th class="text-center">Score / Max</th><th class="text-center">Grade</th>
          <th>Remarks</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($results as $r): ?>
          <tr>
            <td>
              <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$r['student_id'] ?>"
                 class="fw-semibold"><?= e($r['student_no']) ?></a>
              <br><small class="text-muted"><?= e($r['full_name']) ?></small>
            </td>
            <td><code><?= e($r['course_code']) ?></code></td>
            <td><?= e($r['title']) ?></td>
            <td><span class="badge bg-secondary"><?= e(strtoupper($r['assessment_type'])) ?></span></td>
            <td class="text-center fw-semibold">
              <?= (float)$r['score'] ?> <span class="text-muted fw-normal">/ <?= (float)$r['max_score'] ?></span>
            </td>
            <td class="text-center">
              <span class="badge <?= in_array($r['grade'],['A','B']) ? 'bg-success' : ($r['grade'] === 'F' ? 'bg-danger' : 'bg-secondary') ?>">
                <?= e($r['grade']) ?>
              </span>
            </td>
            <td><small class="text-muted"><?= e($r['remarks'] ?: '-') ?></small></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=results&action=assessment&id=<?= (int)$r['assessment_id'] ?>"
                   class="btn btn-sm btn-outline-primary"><i class="bi-pencil-square"></i></a>
                <?php if ($canManage): ?>
                <form method="post"
                      action="<?= APP_URL ?>/index.php?page=results&action=delete&id=<?= (int)$r['result_id'] ?>"
                      data-confirm="Delete this result?" class="d-inline">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi-trash"></i></button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($results) ?> result(s)</div>
    <?php endif; ?>
  </div>
</div>
