<?php /** @var array $users @var int $currentUserId */ ?>

<div class="page-header">
  <h4>User Management</h4>
  <a href="<?= APP_URL ?>/index.php?page=users&action=create" class="btn btn-primary btn-sm">
    <i class="bi-plus-lg me-1"></i>Add User
  </a>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Username</th><th>Full Name</th><th>Email</th>
          <th>Role</th><th>Status</th><th>Created</th><th class="text-end">Actions</th>
        </tr></thead>
        <tbody>
          <?php foreach ($users as $u): $isMe = (int)$u['user_id'] === $currentUserId; ?>
          <tr class="<?= $isMe ? 'table-primary' : '' ?>">
            <td>
              <code><?= e($u['username']) ?></code>
              <?php if ($isMe): ?><span class="badge bg-accent ms-1">You</span><?php endif; ?>
            </td>
            <td class="fw-semibold"><?= e($u['full_name']) ?></td>
            <td><?= e($u['email'] ?: '—') ?></td>
            <td><span class="badge bg-primary"><?= e(ROLES[$u['role']] ?? $u['role']) ?></span></td>
            <td><?= status_badge($u['status']) ?></td>
            <td><?= e(fmt_date($u['created_at'])) ?></td>
            <td class="text-end">
              <div class="d-flex gap-1 justify-content-end">
                <a href="<?= APP_URL ?>/index.php?page=users&action=edit&id=<?= (int)$u['user_id'] ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="bi-pencil"></i></a>
                <?php if (!$isMe): ?>
                <form method="post"
                      action="<?= APP_URL ?>/index.php?page=users&action=delete&id=<?= (int)$u['user_id'] ?>"
                      data-confirm="Delete user '<?= e($u['username']) ?>'? This cannot be undone."
                      class="d-inline">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi-trash"></i></button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 text-muted" style="font-size:.8rem"><?= count($users) ?> user(s)</div>
  </div>
</div>
