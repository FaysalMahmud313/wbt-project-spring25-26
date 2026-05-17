<?php /* Task 2 view: manage admins (admin creates admin) */ render_header("Manage Admins"); ?>

<h1>Manage Admins</h1>

<?php if ($message !== ""): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error   !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<form class="card form" method="post" action="<?= url('admin_users') ?>"
      onsubmit="return validateAdmin(this)">
    <input type="hidden" name="action" value="create_admin">
    <h3>Add New Admin</h3>
    <div class="form-row">
        <label>Full Name</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-row">
        <label>Password (min 8 characters)</label>
        <input type="password" name="password" required minlength="8">
    </div>
    <div class="form-row">
        <label>Address</label>
        <input type="text" name="address" required>
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input type="text" name="phone" required>
    </div>
    <div class="form-actions">
        <button class="btn btn-blue">Create Admin</button>
    </div>
</form>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="adminTable"
           data-count="adminCount" data-noun="admin"
           placeholder="Search admins by name or email...">
    <span class="muted result-count" id="adminCount"></span>
</div>

<table class="table" id="adminTable">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created</th></tr></thead>
    <tbody>
    <?php if (!$admins): ?>
        <tr data-skip-filter><td colspan="5" class="muted">No admins found.</td></tr>
    <?php endif; ?>
    <?php foreach ($admins as $a): ?>
        <tr>
            <td><?= h($a['id']) ?></td>
            <td><?= h($a['name']) ?></td>
            <td><?= h($a['email']) ?></td>
            <td><?= h($a['phone']) ?></td>
            <td><?= h($a['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>
