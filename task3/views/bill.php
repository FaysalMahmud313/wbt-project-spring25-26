<?php /* Task 3 view: downloadable / printable bill with order number */ render_header("Bill #" . $order['id']); ?>

<div class="no-print form-actions">
    <a class="btn btn-light" href="<?= url('my_orders') ?>">&larr; My Orders</a>
    <button class="btn btn-blue" onclick="window.print()">Download / Print Bill</button>
</div>

<div class="card bill" id="bill">
    <div class="bill-head">
        <div>
            <h2>&#9877; MediShop</h2>
            <p class="muted">Online Medicine Shop</p>
        </div>
        <div class="right">
            <h3>INVOICE</h3>
            <p><b>Order No: #<?= h($order['id']) ?></b></p>
            <p class="muted"><?= h($order['order_date']) ?></p>
        </div>
    </div>

    <div class="bill-meta">
        <div>
            <p class="muted">Billed To</p>
            <p><b><?= h($order['customer_name']) ?></b></p>
            <p><?= h($order['customer_email']) ?></p>
            <p><?= h($order['customer_phone']) ?></p>
        </div>
        <div>
            <p class="muted">Shipping Address</p>
            <p><?= h($order['shipping_address']) ?></p>
            <p class="muted">Payment</p>
            <p><?= h($order['payment_method']) ?></p>
            <p class="muted">Status</p>
            <p><span class="status status-<?= h($order['status']) ?>">
                <?= $order['status'] === 'pending' ? 'pending admin approval' : h($order['status']) ?>
            </span></p>
        </div>
    </div>

    <table class="table">
        <thead><tr><th>#</th><th>Medicine</th><th>Unit Price</th>
            <th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php $i = 1; foreach ($billItems as $it): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= h($it['name']) ?></td>
                <td>&#2547;<?= h(number_format($it['unit_price'], 2)) ?></td>
                <td><?= (int)$it['quantity'] ?></td>
                <td>&#2547;<?= h(number_format($it['unit_price'] * $it['quantity'], 2)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="4" class="right"><b>Total Amount</b></td>
                <td><b>&#2547;<?= h(number_format($order['total_amount'], 2)) ?></b></td></tr>
        </tfoot>
    </table>

    <p class="muted bill-foot">Thank you for shopping with MediShop. This is a
       computer-generated bill for order #<?= h($order['id']) ?>.</p>
</div>

<?php render_footer(); ?>
