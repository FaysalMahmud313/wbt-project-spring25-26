<?php /* Task 3 view: invoice review before payment */ render_header("Invoice"); ?>

<h1>Invoice</h1>

<div class="card">
    <p><b>Shipping to:</b> <?= h($address) ?></p>
    <table class="table">
        <thead><tr><th>Medicine</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr>
                <td><?= h($it['name']) ?></td>
                <td>&#2547;<?= h(number_format($it['price'], 2)) ?></td>
                <td><?= (int)$it['quantity'] ?></td>
                <td>&#2547;<?= h(number_format($it['price'] * $it['quantity'], 2)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="3" class="right"><b>Total Amount</b></td>
                <td><b>&#2547;<?= h(number_format($total, 2)) ?></b></td></tr>
        </tfoot>
    </table>

    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('cart') ?>">Cancel</a>
        <a class="btn btn-green" href="<?= url('payment') ?>">Confirm Purchase</a>
    </div>
</div>

<?php render_footer(); ?>
