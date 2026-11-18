<?php /** @var array $student @var array $results @var array $enrolments */ $s = $student; ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
  <h4 class="mb-0">Academic Transcript</h4>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=results" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="text-center mb-4">
      <i class="bi-mortarboard-fill text-primary" style="font-size:2rem"></i>
      <h5 class="mb-0"><?= e(APP_NAME) ?></h5>
      <small class="text-muted">Academic Transcript &mdash; Official Copy</small>
    </div>
    <div class="row mb-4">
      <div class="col-md-6"><table class="table table-borderless table-sm mb-0">
        <tr><td class="text-muted" style="width:40%">Student Name</td><td><strong><?= e($s['full_name']) ?></strong></td></tr>
        <tr><td class="text-muted">Student No</td><td><?= e($s['student_no']) ?></td></tr>
        <tr><td class="text-muted">Gender</td><td><?= e(ucfirst($s['gender'] ?? '-')) ?></td></tr>
        <tr><td class="text-muted">Admission Date</td><td><?= e(fmt_date($s['admission_date'])) ?></td></tr>
        <tr><td class="text-muted">Status</td><td><?= status_badge($s['status']) ?></td></tr>
      </table></div>
      <div class="col-md-6"><table class="table table-borderless table-sm mb-0">
        <?php foreach ($enrolments as $e): ?>
        <tr><td class="text-muted" style="width:40%">Programme</td><td><?= e($e['programme_code']) ?> — <?= e($e['programme_name']) ?></td></tr>
        <tr><td class="text-muted">Academic Year</td><td><?= e($e['academic_year']) ?> / Sem <?= (int)$e['semester'] ?></td></tr>
        <?php break; endforeach; ?>
      </table></div>
    </div>

    <?php if (!$results): ?>
      <p class="text-muted text-center">No academic results on record.</p>
    <?php else:
      // Group by course
      $byCourse = [];
      foreach ($results as $r) { $byCourse[$r['course_code']][] = $r; }
    ?>
    <table class="table mb-4">
      <thead><tr><th>Course</th><th>Assessment</th><th class="text-center">Type</th><th class="text-end">Score</th><th class="text-end">Max</th><th class="text-end">%</th><th class="text-center">Grade</th><th>Remarks</th></tr></thead>
      <tbody>
      <?php foreach ($byCourse as $code => $rows):
        $firstRow = $rows[0];
      ?>
        <tr class="table-light"><td colspan="8"><strong><?= e($code) ?> — <?= e($firstRow['course_name']) ?></strong></td></tr>
        <?php foreach ($rows as $r):
          $pct = $r['max_score'] > 0 ? round($r['score'] / $r['max_score'] * 100, 1) : 0;
        ?>
        <tr>
          <td></td>
          <td><?= e($r['title']) ?></td>
          <td class="text-center"><span class="badge bg-light text-dark"><?= e(strtoupper($r['assessment_type'])) ?></span></td>
          <td class="text-end"><?= (float)$r['score'] ?></td>
          <td class="text-end text-muted"><?= (float)$r['max_score'] ?></td>
          <td class="text-end"><?= $pct ?>%</td>
          <td class="text-center"><span class="badge <?= in_array($r['grade'],['A','B'])?'bg-success':($r['grade']==='F'?'bg-danger':'bg-secondary') ?>"><?= e($r['grade']) ?></span></td>
          <td><small><?= e($r['remarks'] ?: '-') ?></small></td>
        </tr>
        <?php endforeach; ?>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-muted text-uppercase small">Grade Legend <span class="text-warning">(Demo bands only)</span></h6>
        <table class="table table-sm table-bordered mb-0">
          <thead><tr><th>Grade</th><th>%</th><th>Remark</th></tr></thead>
          <tbody>
            <?php foreach (grade_config()['bands'] as $b): ?>
            <tr><td><strong><?= e($b['grade']) ?></strong></td><td>&ge; <?= $b['min'] ?>%</td><td><?= e($b['remark']) ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <div class="text-center text-muted small mt-5 pt-4 border-top">
      This transcript is generated from the Lakeview SIMIS prototype system. For official use it must carry the institution's stamp and authorised signature.<br>
      Printed: <?= e(date('d M Y H:i')) ?>
    </div>
  </div>
</div>
