<?php /** @var array $programmes @var bool $canManage */ ?>

<div class="page-header">
  <h4>Programmes</h4>
  <?php if ($canManage): ?>
  <a href="<?= APP_URL ?>/index.php?page=programmes&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Add Programme
  </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (!$programmes): ?>
      <div class="empty-state"><i class="bi-book"></i><p>No programmes yet.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Code</th><th>Name</th><th>Duration</th>
          <th class="text-center">Courses</th><th class="text-center">Enrolments</th>
          <th>Status</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($programmes as $p): ?>
          <tr>
            <td><code><?= e($p['programme_code']) ?></code></td>
            <td class="fw-semibold">
              <?= e($p['programme_name']) ?>
              <?php if ($p['description']): ?>
              <br><small class="text-muted"><?= e(mb_strimwidth($p['description'], 0, 60, '…')) ?></small>
              <?php endif; ?>
            </td>
            <td><?= e($p['duration'] ?: '—') ?></td>
            <td class="text-center"><?= (int)$p['course_count'] ?></td>
            <td class="text-center"><?= (int)$p['enrol_count'] ?></td>
            <td><?= status_badge($p['status']) ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=programmes&action=show&id=<?= (int)$p['programme_id'] ?>"
                   class="btn btn-sm btn-outline-primary"><i class="bi-eye"></i></a>
                <?php if ($canManage): ?>
                <a href="<?= APP_URL ?>/index.php?page=programmes&action=edit&id=<?= (int)$p['programme_id'] ?>"
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
