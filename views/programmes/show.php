<?php /** @var array $programme @var array $courses @var array $enrolments @var bool $canManage @var bool $canManageCourses */ $p = $programme; ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div><h4 class="mb-0"><code><?= e($p['programme_code']) ?></code> <?= e($p['programme_name']) ?></h4>
    <span class="text-muted"><?= e($p['duration'] ?: '') ?> | <?= status_badge($p['status']) ?></span></div>
  <div class="d-flex gap-2">
    <a href="<?= APP_URL ?>/index.php?page=programmes" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
    <?php if ($canManage): ?><a href="<?= APP_URL ?>/index.php?page=programmes&action=edit&id=<?= (int)$p['programme_id'] ?>" class="btn btn-outline-primary btn-sm"><i class="bi-pencil me-1"></i>Edit</a><?php endif; ?>
  </div>
</div>

<?php if ($p['description']): ?><p class="text-muted"><?= e($p['description']) ?></p><?php endif; ?>

<div class="row g-3 mt-1">
  <div class="col-lg-7">
    <div class="card h-100"><div class="card-header d-flex justify-content-between"><span><i class="bi-journal-text me-1"></i>Courses</span>
      <?php if ($canManageCourses): ?><a class="btn btn-sm btn-outline-primary" href="<?= APP_URL ?>/index.php?page=courses&action=create&programme_id=<?= (int)$p['programme_id'] ?>"><i class="bi-plus me-1"></i>Add Course</a><?php endif; ?>
    </div>
      <div class="card-body p-0">
        <?php if (!$courses): ?>
          <p class="text-muted m-3 mb-0">No courses attached to this programme.</p>
        <?php else: ?>
        <table class="table table-sm mb-0 align-middle">
          <thead><tr><th>Code</th><th>Course</th><th class="text-center">Sem</th><th class="text-center">Credits</th><th>Status</th><th class="text-end"></th></tr></thead>
          <tbody>
            <?php foreach ($courses as $c): ?>
            <tr><td><code><?= e($c['course_code']) ?></code></td>
              <td><?= e($c['course_name']) ?></td>
              <td class="text-center"><?= (int)$c['semester'] ?></td>
              <td class="text-center"><?= (int)$c['credit_units'] ?></td>
              <td><?= status_badge($c['status']) ?></td>
              <td class="text-end"><a href="<?= APP_URL ?>/index.php?page=courses&action=show&id=<?= (int)$c['course_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi-eye"></i></a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card h-100"><div class="card-header d-flex justify-content-between"><span><i class="bi-card-checklist me-1"></i>Enrolled Students</span>
      <?php if ($canManage): ?><a class="btn btn-sm btn-outline-primary" href="<?= APP_URL ?>/index.php?page=enrolments&action=create&programme_id=<?= (int)$p['programme_id'] ?>"><i class="bi-plus me-1"></i>Enrol</a><?php endif; ?>
    </div>
      <div class="card-body p-0">
        <?php if (!$enrolments): ?>
          <p class="text-muted m-3 mb-0">No active enrolments.</p>
        <?php else: ?>
        <table class="table table-sm mb-0 align-middle">
          <thead><tr><th>Student</th><th>Year</th><th>Sem</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($enrolments as $e): ?>
            <tr><td><a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$e['student_id'] ?>"><?= e($e['student_no']) ?></a><br><small class="text-muted"><?= e($e['full_name']) ?></small></td>
              <td><?= e($e['academic_year']) ?></td><td><?= (int)$e['semester'] ?></td><td><?= status_badge($e['status']) ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
