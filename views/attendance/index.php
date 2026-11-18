<?php /** @var array $records @var array $courses @var int $courseId @var string $date @var int $studentId @var array $ov @var bool $canManage */ ?>

<div class="page-header">
  <h4>Attendance</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=attendance&action=mark" class="btn btn-primary btn-sm">
    <i class="bi-check2-square me-1"></i>Mark Attendance
  </a>
  <?php endif; ?>
</div>

<div class="row g-3 mb-3">
  <?php
  $attStats = [
    'present' => ['icon' => 'check-circle-fill', 'color' => '#16a34a'],
    'late'    => ['icon' => 'clock-fill',         'color' => '#d97706'],
    'absent'  => ['icon' => 'x-circle-fill',      'color' => '#dc2626'],
    'excused' => ['icon' => 'info-circle-fill',   'color' => '#0369a1'],
  ];
  foreach ($attStats as $st => $cfg):
  ?>
  <div class="col-6 col-md-3">
    <?= stat_card($cfg['icon'], ucfirst($st), (int)($ov[$st] ?? 0), $cfg['color']) ?>
  </div>
  <?php endforeach; ?>
</div>

<div class="filter-bar">
  <form method="get" action="<?= APP_URL ?>/index.php" class="row g-2 align-items-end">
    <input type="hidden" name="page" value="attendance">
    <div class="col-md-5">
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
    <div class="col-md-3">
      <label class="form-label">Date</label>
      <input type="date" name="date" class="form-control form-control-sm" value="<?= e($date) ?>">
    </div>
    <div class="col-md-4 d-flex gap-2 align-items-end">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi-funnel me-1"></i>Filter</button>
      <a href="<?= APP_URL ?>/index.php?page=attendance" class="btn btn-outline-secondary btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$records): ?>
      <div class="empty-state"><i class="bi-calendar-check"></i><p>No attendance records found.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Date</th><th>Student</th><th>Course</th>
          <th>Status</th><th>Remarks</th><th>Recorded By</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($records as $a): ?>
          <tr>
            <td><?= e(fmt_date($a['date'])) ?></td>
            <td>
              <a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$a['student_id'] ?>"
                 class="fw-semibold"><?= e($a['student_no']) ?></a>
              <br><small class="text-muted"><?= e($a['full_name']) ?></small>
            </td>
            <td><code><?= e($a['course_code']) ?></code></td>
            <td><?= status_badge($a['status']) ?></td>
            <td><?= e($a['remarks'] ?: '-') ?></td>
            <td><small class="text-muted"><?= e($a['recorder'] ?: '-') ?></small></td>
            <td class="text-end">
              <?php if ($canManage): ?>
              <form method="post"
                    action="<?= APP_URL ?>/index.php?page=attendance&action=delete&id=<?= (int)$a['attendance_id'] ?>"
                    data-confirm="Delete this attendance record?" class="d-inline">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-outline-danger"><i class="bi-trash"></i></button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($records) ?> record(s)</div>
    <?php endif; ?>
  </div>
</div>
