<?php /** @var array $course @var array $assessments @var bool $canManage @var bool $canManageAssessments */ $c = $course; ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h4 class="mb-0"><code><?= e($c['course_code']) ?></code> <?= e($c['course_name']) ?></h4>
    <span class="text-muted">Programme: <?= e($c['programme_name']) ?> | Semester <?= (int)$c['semester'] ?> | <?= (int)$c['credit_units'] ?> credits | <?= status_badge($c['status']) ?></span></div>
  <div class="d-flex gap-2">
    <a href="<?= APP_URL ?>/index.php?page=courses" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
    <?php if ($canManage): ?><a href="<?= APP_URL ?>/index.php?page=courses&action=edit&id=<?= (int)$c['course_id'] ?>" class="btn btn-outline-primary btn-sm"><i class="bi-pencil me-1"></i>Edit</a><?php endif; ?>
  </div>
</div>
<?php if (!empty($c['description'])): ?><p class="text-muted"><?= e($c['description']) ?></p><?php endif; ?>

<div class="card"><div class="card-header d-flex justify-content-between"><span><i class="bi-clipboard-data me-1"></i>Assessments</span>
  <?php if ($canManageAssessments): ?><a class="btn btn-sm btn-outline-primary" href="<?= APP_URL ?>/index.php?page=assessments&action=create&course_id=<?= (int)$c['course_id'] ?>"><i class="bi-plus me-1"></i>Add Assessment</a><?php endif; ?>
</div>
<div class="card-body p-0">
  <?php if (!$assessments): ?>
    <p class="text-muted m-3 mb-0">No assessments defined for this course yet.</p>
  <?php else: ?>
  <table class="table table-sm mb-0 align-middle">
    <thead><tr><th>Title</th><th>Type</th><th class="text-center">Max</th><th>Date</th><th class="text-end"></th></tr></thead>
    <tbody>
      <?php foreach ($assessments as $a): ?>
      <tr><td><?= e($a['title']) ?></td>
        <td><span class="badge bg-light text-dark"><?= e($a['assessment_type']) ?></span></td>
        <td class="text-center"><?= (float)$a['max_score'] ?></td>
        <td><?= e(fmt_date($a['date'])) ?></td>
        <td class="text-end"><a href="<?= APP_URL ?>/index.php?page=results&action=assessment&id=<?= (int)$a['assessment_id'] ?>" class="btn btn-sm btn-outline-primary" title="Enter scores"><i class="bi-pencil-square"></i> Scores</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div></div>
