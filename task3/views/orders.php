<?php /* Task 3 view: my orders / confirmation list */ render_header("My Orders"); ?>

<h1>My Orders</h1>

<?php if (!$orders): ?>
    <div class="card"><p class="muted">You have no orders yet.</p></div>
<?php else: ?>
<div class="searchbar card">
    <input type="text" class="order-search" data-wrap="ordersWrap"
           data-count="myOrdCount" placeholder="Search your orders by medicine, status...">
    <span class="muted result-count" id="myOrdCount"></span>
</div>
<?php endif; ?>

<div id="ordersWrap">
<?php foreach ($orders as $o): ?>
    <div class="card order-card">
        <div class="order-head">
            <span><b>Order #<?= h($o['id']) ?></b> &middot; <?= h($o['order_date']) ?></span>
            <span class="status status-<?= h($o['status']) ?>">
                <?= $o['status'] === 'pending' ? 'pending admin approval' : h($o['status']) ?>
            </span>
        </div>
        <table class="table">
            <thead><tr><th>Medicine</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
            <tbody>
            <?php foreach ($itemsByOrder[$o['id']] as $it): ?>
                <tr>
                    <td><?= h($it['name']) ?></td>
                    <td><?= (int)$it['quantity'] ?></td>
                    <td>&#2547;<?= h(number_format($it['unit_price'], 2)) ?></td>
                    <td>&#2547;<?= h(number_format($it['unit_price'] * $it['quantity'], 2)) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="right"><b>Total: &#2547;<?= h(number_format($o['total_amount'], 2)) ?></b>
           &middot; Payment: <?= h($o['payment_method']) ?>
           &middot; <a class="btn btn-light btn-sm" href="<?= url('bill', ['id' => $o['id']]) ?>">View / Download Bill</a></p>
    </div>
<?php endforeach; ?>
</div>

<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>
