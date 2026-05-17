<?php /* Task 2 view: all customers' purchase history (accepted orders) */ render_header("Purchase History"); ?>

<h1>All Customers' Purchase History</h1>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="histTable"
           data-count="histCount" data-noun="record"
           placeholder="Search history by customer, medicine...">
    <span class="muted result-count" id="histCount"></span>
</div>

<table class="table" id="histTable">
    <thead><tr><th>Order</th><th>Customer</th><th>Medicine</th>
        <th>Qty</th><th>Unit Price</th><th>Order Total</th><th>Date</th></tr></thead>
    <tbody>
    <?php if (!$rows): ?>
        <tr data-skip-filter><td colspan="7" class="muted">No accepted orders yet.</td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td>#<?= h($r['order_id']) ?></td>
            <td><?= h($r['customer_name']) ?><br><span class="muted"><?= h($r['customer_email']) ?></span></td>
            <td><?= h($r['medicine_name']) ?></td>
            <td><?= h($r['quantity']) ?></td>
            <td>&#2547;<?= h(number_format($r['unit_price'], 2)) ?></td>
            <td>&#2547;<?= h(number_format($r['total_amount'], 2)) ?></td>
            <td><?= h($r['order_date']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>
