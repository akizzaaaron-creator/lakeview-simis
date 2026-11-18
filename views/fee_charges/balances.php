<?php /** @var array $rows @var bool $canManage */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Fee Balances</h4>
  <a href="<?= APP_URL ?>/index.php?page=fee_charges" class="btn btn-outline-secondary btn-sm"><i class="bi-arrow-left me-1"></i>Back to Charges</a>
</div>

<div class="card"><div class="card-body p-0">
  <?php if (!$rows): ?>
    <div class="empty-state"><i class="bi-calculator d-block"></i><p class="mt-2">No students.</p></div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>Student</th><th class="text-end">Charged</th><th class="text-end">Paid</th><th class="text-end">Balance</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
        <?php $totCharged=$totPaid=0; foreach ($rows as $r): $bal=$r['charged']-$r['paid']; $totCharged+=$r['charged']; $totPaid+=$r['paid']; ?>
        <tr class="<?= $bal > 0 ? 'table-warning' : '' ?>">
          <td><a href="<?= APP_URL ?>/index.php?page=students&action=show&id=<?= (int)$r['student_id'] ?>"><?= e($r['student_no']) ?></a><br><small class="text-muted"><?= e($r['full_name']) ?></small></td>
          <td class="text-end"><?= money($r['charged']) ?></td>
          <td class="text-end text-success"><?= money($r['paid']) ?></td>
          <td class="text-end fw-bold <?= $bal > 0 ? 'text-danger' : 'text-success' ?>"><?= money($bal) ?></td>
          <td class="text-end">
            <?php if ($canManage && $bal > 0): ?>
            <a href="<?= APP_URL ?>/index.php?page=payments&action=create&student_id=<?= (int)$r['student_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi-cash me-1"></i>Record Payment</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot><tr class="fw-bold"><td>Total</td><td class="text-end"><?= money($totCharged) ?></td><td class="text-end text-success"><?= money($totPaid) ?></td><td class="text-end text-danger"><?= money($totCharged-$totPaid) ?></td><td></td></tr></tfoot>
    </table>
  </div>
  <?php endif; ?>
</div></div>
