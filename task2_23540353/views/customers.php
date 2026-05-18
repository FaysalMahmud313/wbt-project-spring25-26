<?php  render_header("Customers"); ?>

<h1>Customers</h1>

<?php if ($message !== ""): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error   !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="custTable"
           data-count="custCount" data-noun="customer"
           placeholder="Search customers by name, email, phone...">
    <span class="muted result-count" id="custCount"></span>
</div>

<table class="table" id="custTable">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
        <th>Address</th><th>Joined</th><th>Action</th></tr></thead>
    <tbody>
    <?php if (!$customers): ?>
        <tr data-skip-filter><td colspan="7" class="muted">No customers yet.</td></tr>
    <?php endif; ?>
    <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= h($c['id']) ?></td>
            <td><?= h($c['name']) ?></td>
            <td><?= h($c['email']) ?></td>
            <td><?= h($c['phone']) ?></td>
            <td><?= h($c['address']) ?></td>
            <td><?= h($c['created_at']) ?></td>
            <td>
                <a class="btn btn-danger btn-sm"
                   href="<?= url('admin_customers', ['action'=>'delete','id'=>$c['id']]) ?>"
                   onclick="return confirm('Delete this customer and all their data?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>
