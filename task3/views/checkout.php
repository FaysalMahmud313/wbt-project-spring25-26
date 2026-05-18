<?php /* Task 3 view: shipping address form */ render_header("Checkout"); ?>

<h1>Checkout &mdash; Shipping Address</h1>

<?php if ($error !== ""): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>

<form class="card form" method="post" action="<?= url('checkout') ?>"
      onsubmit="return validateCheckout(this)">
    <div class="form-row">
        <label>Shipping Address</label>
        <textarea name="shipping_address" rows="3" required><?= h($address) ?></textarea>
    </div>
    <div class="form-actions">
        <a class="btn btn-light" href="<?= url('cart') ?>">Back to Cart</a>
        <button class="btn btn-blue" type="submit">Continue to Invoice</button>
    </div>
</form>

<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>
