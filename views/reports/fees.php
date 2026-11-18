<?php /** @var array $balances @var float $totCharged @var float $totPaid */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
  <h4 class="mb-0">Fee Collection Report</h4>
  <div class="d-flex gap-2"><button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi-printer me-1"></i>Print</button>
    <a href="<?= APP_URL ?>/index.php?page=reports" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back</a></div>
</div>
<div class="row g-3 mb-3 no-print">
  <div class="col-4"><?= stat_card('receipt',           'Total Charged',  money($totCharged)) ?></div>
  <div class="col-4"><?= stat_card('cash-coin',         'Total Collected',money($totPaid)) ?></div>
  <div class="col-4"><?= stat_card('exclamation-circle','Outstanding',    money($totCharged - $totPaid)) ?></div>
</div>
<div class="card">
  <div class="card-header"><i class="bi-cash-stack me-1"></i>Student Fee Balances</div>
  <div class="card-body p-0">
    <table class="table table-bordered mb-0 align-middle">
      <thead><tr><th>Student No</th><th>Name</th><th>Status</th><th class="text-end">Charged</th><th class="text-end">Paid</th><th class="text-end">Balance</th></tr></thead>
      <tbody>
        <?php foreach ($balances as $r): $bal = $r['charged'] - $r['paid']; ?>
        <tr class="<?= $bal > 0 ? 'table-warning' : '' ?>">
          <td><code><?= e($r['student_no']) ?></code></td>
          <td><?= e($r['full_name']) ?></td>
          <td><?= status_badge($r['status']) ?></td>
          <td class="text-end"><?= money($r['charged']) ?></td>
          <td class="text-end text-success"><?= money($r['paid']) ?></td>
          <td class="text-end fw-bold <?= $bal > 0 ? 'text-danger' : 'text-success' ?>"><?= money($bal) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot><tr class="fw-bold"><td colspan="3">Total</td><td class="text-end"><?= money($totCharged) ?></td><td class="text-end text-success"><?= money($totPaid) ?></td><td class="text-end text-danger"><?= money($totCharged - $totPaid) ?></td></tr></tfoot>
    </table>
  </div>
</div>
