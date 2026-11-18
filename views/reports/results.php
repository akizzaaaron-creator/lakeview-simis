<?php /** @var array $data */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
  <h4 class="mb-0">Results Report</h4>
  <div class="d-flex gap-2"><button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=reports" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a></div>
</div>
<div class="card">
  <div class="card-header"><i class="bi-graph-up-arrow me-1"></i>Pass/Fail Rates by Course</div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0 align-middle">
      <thead><tr><th>Code</th><th>Course</th><th class="text-center">Results</th><th class="text-center">Passed</th><th class="text-center">Failed</th><th class="text-center">Pass Rate</th></tr></thead>
      <tbody>
        <?php foreach ($data as $r):
          $rate = (int)$r['results'] > 0 ? round((int)$r['passed'] / (int)$r['results'] * 100, 1) : 0;
          $clr = $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
        ?>
        <tr>
          <td><code><?= e($r['course_code']) ?></code></td>
          <td><?= e($r['course_name']) ?></td>
          <td class="text-center"><?= (int)$r['results'] ?></td>
          <td class="text-center text-success fw-semibold"><?= (int)$r['passed'] ?></td>
          <td class="text-center text-danger fw-semibold"><?= (int)$r['failed'] ?></td>
          <td class="text-center">
            <?php if ((int)$r['results'] > 0): ?>
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1" style="height:8px"><div class="progress-bar bg-<?= $clr ?>" style="width:<?= $rate ?>%"></div></div>
              <strong class="text-<?= $clr ?>" style="min-width:38px"><?= $rate ?>%</strong>
            </div>
            <?php else: ?><span class="text-muted">—</span><?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
