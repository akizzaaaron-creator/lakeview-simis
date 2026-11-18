<?php /** @var array $data @var int $total */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
  <h4 class="mb-0">Enrolment Report</h4>
  <div class="d-flex gap-2"><button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=reports" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a></div>
</div>
<div class="card">
  <div class="card-header"><i class="bi-bar-chart-fill me-1"></i>Active Enrolments by Programme</div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0 align-middle">
      <thead><tr><th>Programme</th><th>Code</th><th class="text-center">Active Enrolments</th><th class="text-center">% of Total</th></tr></thead>
      <tbody>
        <?php foreach ($data as $r): $pct = $total > 0 ? round((int)$r['n'] / $total * 100, 1) : 0; ?>
        <tr>
          <td><?= e($r['programme_name']) ?></td>
          <td><code><?= e($r['programme_code']) ?></code></td>
          <td class="text-center"><strong><?= (int)$r['n'] ?></strong>
            <div class="progress mt-1" style="height:5px"><div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div></div>
          </td>
          <td class="text-center"><?= $pct ?>%</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot><tr class="fw-bold"><td colspan="2">Total</td><td class="text-center"><?= $total ?></td><td class="text-center">100%</td></tr></tfoot>
    </table>
  </div>
</div>
