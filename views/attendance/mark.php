<?php /** @var array $courses @var int $courseId @var string $date @var array $students @var array $statusMap */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Mark Attendance</h4>
  <a href="<?= APP_URL ?>/index.php?page=attendance" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a>
</div>

<div class="card mb-3"><div class="card-body">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="attendance">
    <input type="hidden" name="action" value="mark">
    <div class="col-md-5">
      <label class="form-label small text-muted">Course</label>
      <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="0">— select course —</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= (int)$c['course_id'] ?>" <?= $courseId === (int)$c['course_id'] ? 'selected' : '' ?>><?= e($c['course_code']) ?> — <?= e($c['course_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label small text-muted">Date</label>
      <input type="date" name="date" class="form-control form-control-sm" value="<?= e($date) ?>" onchange="this.form.submit()">
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm">Load</button></div>
  </form>
</div></div>

<?php if ($courseId): ?>
<?php $course = course_find($courseId); ?>
<div class="card"><div class="card-header d-flex justify-content-between">
  <span><i class="bi-people me-1"></i>Enrolled Students — <code><?= e($course['course_code'] ?? '') ?></code> &middot; <?= e($course['course_name'] ?? '') ?> &middot; <?= e(fmt_date($date)) ?></span>
  <span class="badge bg-secondary"><?= count($students) ?> students</span>
</div>
<div class="card-body p-0">
  <?php if (!$students): ?>
    <div class="empty-state"><i class="bi-people d-block"></i><p class="mt-2">No active students enrolled in this course's programme.</p></div>
  <?php else: ?>
  <form method="post" action="<?= APP_URL ?>/index.php?page=attendance&action=save">
    <?= csrf_field() ?>
    <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">
    <input type="hidden" name="date" value="<?= e($date) ?>">
    <table class="table table-sm mb-0 align-middle">
      <thead><tr><th>Student No</th><th>Name</th><th style="width:180px">Status</th><th>Remarks</th></tr></thead>
      <tbody>
        <?php foreach ($students as $st): $cur = $statusMap[(int)$st['student_id']]['status'] ?? 'present'; ?>
        <tr>
          <td><code><?= e($st['student_no']) ?></code></td>
          <td><?= e($st['full_name']) ?></td>
          <td>
            <select name="status[<?= (int)$st['student_id'] ?>]" class="form-select form-select-sm">
              <?php foreach (['present','absent','late','excused'] as $s): ?>
                <option value="<?= $s ?>" <?= $cur === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><input type="text" name="remarks[<?= (int)$st['student_id'] ?>]" class="form-control form-control-sm" value="<?= e($statusMap[(int)$st['student_id']]['remarks'] ?? '') ?>" placeholder="optional"></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="p-3 d-flex justify-content-end gap-2">
      <a href="<?= APP_URL ?>/index.php?page=attendance" class="btn btn-outline-secondary btn-sm">Cancel</a>
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-check-lg me-1"></i>Save Attendance</button>
    </div>
  </form>
  <?php endif; ?>
</div></div>
<?php else: ?>
<div class="alert alert-info"><i class="bi-info-circle me-1"></i>Select a course and date above to load the enrolled students for marking.</div>
<?php endif; ?>
