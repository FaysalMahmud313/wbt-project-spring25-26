<?php /* Task 3 view: payment method selection */ render_header("Payment"); ?>

<h1>Select Payment Method</h1>

<?php if ($error !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<form class="card form" method="post" action="<?= url('payment') ?>"
      onsubmit="return validatePayment(this)">
    <p><b>Amount to pay:</b> &#2547;<?= h(number_format($total, 2)) ?></p>

    <?php foreach (["Credit Card","bKash","Nagad","Bank Transfer","Cash on Delivery"] as $pm): ?>
        <label class="radio-row">
            <input type="radio" name="payment_method" value="<?= h($pm) ?>"> <?= h($pm) ?>
        </label>
    <?php endforeach; ?>

    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('invoice') ?>">Back</a>
        <button class="btn btn-green" type="submit">Place Order</button>
    </div>
</form>

<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>
