<?php /* Task 2 view: all purchase requests + AJAX accept/reject */ render_header("Purchase Requests"); ?>

<h1>Purchase Requests</h1>

<div class="searchbar card">
    <input type="text" class="table-search" data-table="ordTable"
           data-count="ordCount" data-noun="order"
           placeholder="Search by customer, status, payment...">
    <span class="muted result-count" id="ordCount"></span>
</div>

<table class="table" id="ordTable">
    <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Shipping Address</th>
        <th>Payment</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php if (!$orders): ?>
        <tr data-skip-filter><td colspan="8" class="muted">No orders yet.</td></tr>
    <?php endif; ?>
    <?php foreach ($orders as $o): ?>
        <tr id="order-row-<?= (int)$o['id'] ?>">
            <td>#<?= h($o['id']) ?></td>
            <td><?= h($o['customer_name']) ?><br><span class="muted"><?= h($o['customer_email']) ?></span></td>
            <td>&#2547;<?= h(number_format($o['total_amount'], 2)) ?></td>
            <td><?= h($o['shipping_address']) ?></td>
            <td><?= h($o['payment_method']) ?></td>
            <td><?= h($o['order_date']) ?></td>
            <td><span class="status status-<?= h($o['status']) ?>"><?= h($o['status']) ?></span></td>
            <td>
                <?php if ($o['status'] === 'pending'): ?>
                    <button class="btn btn-green btn-sm"
                            onclick="updateOrderStatus(<?= (int)$o['id'] ?>, 'accepted')">Accept</button>
                    <button class="btn btn-danger btn-sm"
                            onclick="updateOrderStatus(<?= (int)$o['id'] ?>, 'rejected')">Reject</button>
                <?php else: ?>
                    &mdash;
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script src="<?= asset('task2_23540353/assets/js/task2.js') ?>"></script>
<?php render_footer(); ?>
