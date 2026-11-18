<?php /** @var array $assessment @var array $students @var array $existing @var bool $canManage */ $a = $assessment; ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h4 class="mb-0">Enter Scores</h4>
    <span class="text-muted"><code><?= e($a['course_code']) ?></code> <?= e($a['course_name']) ?> &middot; <?= e($a['title']) ?> <span class="badge bg-light text-dark"><?= e(strtoupper($a['assessment_type'])) ?></span> &middot; Max: <?= (float)$a['max_score'] ?></span>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= APP_URL ?>/index.php?page=assessments" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Assessments</a>
    <a href="<?= APP_URL ?>/index.php?page=results" class="btn btn-outline-secondary btn-sm">All Results</a>
  </div>
</div>

<?php if (!$students): ?>
  <div class="alert alert-warning"><i class="bi-exclamation-triangle me-1"></i>No active students enrolled in this course's programme.</div>
<?php else: ?>
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <span><i class="bi-pencil-square me-1"></i>Score Sheet &mdash; <?= e(fmt_date($a['date'])) ?></span>
    <span class="text-muted small">Score range: 0 &ndash; <?= (float)$a['max_score'] ?></span>
  </div>
  <div class="card-body p-0">
    <form method="post" action="<?= APP_URL ?>/index.php?page=results&action=save">
      <?= csrf_field() ?>
      <input type="hidden" name="assessment_id" value="<?= (int)$a['assessment_id'] ?>">
      <table class="table table-sm mb-0 align-middle">
        <thead><tr>
          <th>Student No</th><th>Name</th>
          <th class="text-center" style="width:130px">Score <small class="text-muted">/ <?= (float)$a['max_score'] ?></small></th>
          <th class="text-center" style="width:60px">Grade</th>
          <th style="width:160px">Remarks</th>
        </tr></thead>
        <tbody>
          <?php foreach ($students as $st):
            $ex = $existing[(int)$st['student_id']] ?? null;
            $curScore = $ex ? (float)$ex['score'] : '';
            $curGrade = $ex ? $ex['grade'] : '';
            $curRem   = $ex ? $ex['remarks'] : '';
          ?>
          <tr>
            <td><code><?= e($st['student_no']) ?></code></td>
            <td><?= e($st['full_name']) ?></td>
            <td><input type="number" step="any" min="0" max="<?= (float)$a['max_score'] ?>"
                       name="score[<?= (int)$st['student_id'] ?>]"
                       class="form-control form-control-sm text-center"
                       value="<?= $curScore !== '' ? (float)$curScore : '' ?>"
                       placeholder="—"
                       <?= !$canManage ? 'readonly' : '' ?>></td>
            <td class="text-center">
              <?php if ($curGrade): ?>
                <span class="badge <?= in_array($curGrade,['A','B'])?'bg-success':($curGrade==='F'?'bg-danger':'bg-secondary') ?>"><?= e($curGrade) ?></span>
              <?php else: ?><span class="text-muted">—</span><?php endif; ?>
            </td>
            <td><input type="text" name="remarks[<?= (int)$st['student_id'] ?>]"
                       class="form-control form-control-sm"
                       value="<?= e($curRem ?? '') ?>" placeholder="optional"
                       <?= !$canManage ? 'readonly' : '' ?>></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if ($canManage): ?>
      <div class="p-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">Leave score blank to skip a student. Grade is auto-calculated on save.</small>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi-check-lg me-1"></i>Save Scores</button>
      </div>
      <?php endif; ?>
    </form>
  </div>
</div>
<?php endif; ?>
