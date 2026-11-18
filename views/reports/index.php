<?php /** @var array $stats */ ?>

<div class="page-header">
  <h4>Reports</h4>
  <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
    <i class="bi-printer me-1"></i>Print
  </button>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><?= stat_card('people-fill',    'Total Students',   $stats['students']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('person-check',   'Active Students',  $stats['active_students']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('card-checklist', 'Enrolments',       $stats['enrolments']) ?></div>
  <div class="col-6 col-md-3"><?= stat_card('book-fill',      'Programmes',       $stats['programmes']) ?></div>
  <div class="col-6 col-md-4"><?= stat_card('receipt',        'Fees Charged',     money($stats['fee_charged']),    '#0369a1') ?></div>
  <div class="col-6 col-md-4"><?= stat_card('cash-coin',      'Fees Collected',   money($stats['fee_collected']),  '#16a34a') ?></div>
  <div class="col-6 col-md-4"><?= stat_card('exclamation-circle','Outstanding',   money($stats['fee_outstanding']), '#dc2626') ?></div>
</div>

<h5 class="mb-3 fw-bold">Detailed Reports</h5>
<div class="row g-3">
  <?php
  $rpts = [
    ['enrolment', 'bi-people-fill',      '#0f7a8a', 'Enrolment Report',   'Students enrolled per programme'],
    ['fees',      'bi-cash-stack',        '#16a34a', 'Fee Collection',     'Charges, payments and balances'],
    ['attendance','bi-calendar-check-fill','#0369a1','Attendance Report',  'Attendance rates per student'],
    ['results',   'bi-graph-up-arrow',   '#7c3aed', 'Results Report',     'Pass and fail rates per course'],
  ];
  foreach ($rpts as [$slug, $icon, $color, $label, $desc]): ?>
  <div class="col-sm-6 col-lg-3">
    <a href="<?= APP_URL ?>/index.php?page=reports&action=<?= $slug ?>" class="text-decoration-none">
      <div class="card h-100" style="transition:.18s ease;border-top:3px solid <?= $color ?>">
        <div class="card-body text-center py-4">
          <i class="<?= $icon ?>" style="font-size:2rem;color:<?= $color ?>"></i>
          <h6 class="mt-3 mb-1 fw-bold"><?= $label ?></h6>
          <small class="text-muted"><?= $desc ?></small>
        </div>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>
