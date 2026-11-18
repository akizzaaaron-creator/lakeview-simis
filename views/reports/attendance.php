<?php /** @var array $data */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
  <h4 class="mb-0">Attendance Report</h4>
  <div class="d-flex gap-2"><button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=reports" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a></div>
</div>
<div class="card">
  <div class="card-header"><i class="bi-calendar-check me-1"></i>Attendance Rates by Student</div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0 align-middle">
      <thead><tr><th>Student No</th><th>Name</th><th class="text-center">Sessions</th><th class="text-center">Attended</th><th class="text-center">Rate</th></tr></thead>
      <tbody>
        <?php foreach ($data as $r):
          $rate = $r['records'] > 0 ? round($r['attended'] / $r['records'] * 100, 1) : 0;
          $clr = $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
        ?>
        <tr>
          <td><code><?= e($r['student_no']) ?></code></td>
          <td><?= e($r['full_name']) ?></td>
          <td class="text-center"><?= (int)$r['records'] ?></td>
          <td class="text-center"><?= (int)$r['attended'] ?></td>
          <td class="text-center">
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1" style="height:8px"><div class="progress-bar bg-<?= $clr ?>" style="width:<?= $rate ?>%"></div></div>
              <strong class="text-<?= $clr ?>" style="min-width:38px"><?= $rate ?>%</strong>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
