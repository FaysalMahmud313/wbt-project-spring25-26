<?php /* Task 2 view: admin dashboard statistics */ render_header("Admin Dashboard"); ?>

<h1>Admin Dashboard</h1>

<div class="stat-grid">
    <div class="card stat"><span class="num"><?= h($stats['medicines']) ?></span><span>Medicines</span></div>
    <div class="card stat"><span class="num"><?= h($stats['categories']) ?></span><span>Categories</span></div>
    <div class="card stat"><span class="num"><?= h($stats['customers']) ?></span><span>Customers</span></div>
    <div class="card stat"><span class="num"><?= h($stats['pending']) ?></span><span>Pending Orders</span></div>
</div>

<div class="card">
    <h3>Quick Links</h3>
    <a class="btn btn-blue"  href="<?= url('admin_medicines') ?>">Manage Medicines</a>
    <a class="btn btn-blue"  href="<?= url('admin_categories') ?>">Manage Categories</a>
    <a class="btn btn-light" href="<?= url('admin_orders') ?>">Purchase Requests</a>
    <a class="btn btn-light" href="<?= url('admin_history') ?>">Purchase History</a>
</div>

<?php render_footer(); ?>
