<?php render_header("My Cart"); ?>

<h1>My Cart</h1>

<?php if (!$items): ?>
    <div class="card"><p class="muted">Your cart is empty.</p>
        <a class="btn btn-blue" href="<?= url('home') ?>">Browse medicines</a></div>
<?php else: ?>
<div class="searchbar card">
    <input type="text" class="table-search" data-table="cartTable"
           data-count="cartCount" placeholder="Search items in your cart...">
    <span class="muted result-count" id="cartCount"></span>
</div>
<table class="table" id="cartTable">
    <thead><tr><th>Medicine</th><th>Vendor</th><th>Price</th>
        <th>Quantity</th><th>Subtotal</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($items as $it): ?>
        <tr id="cart-row-<?= (int)$it['cart_id'] ?>"
            data-price="<?= h($it['price']) ?>" data-stock="<?= h($it['availability']) ?>">
            <td><?= h($it['name']) ?></td>
            <td><?= h($it['vendor_name']) ?></td>
            <td>&#2547;<?= h(number_format($it['price'], 2)) ?></td>
            <td class="qty-cell">
                <button class="btn btn-light btn-sm"
                        onclick="changeQty(<?= (int)$it['cart_id'] ?>, -1)">&minus;</button>
                <span class="qty"><?= (int)$it['quantity'] ?></span>
                <button class="btn btn-light btn-sm"
                        onclick="changeQty(<?= (int)$it['cart_id'] ?>, 1)">+</button>
            </td>
            <td class="subtotal">&#2547;<?= h(number_format($it['price'] * $it['quantity'], 2)) ?></td>
            <td>
                <button class="btn btn-danger btn-sm"
                        onclick="removeItem(<?= (int)$it['cart_id'] ?>)">Remove</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr><td colspan="4" class="right"><b>Total</b></td>
            <td colspan="2"><b id="cartTotal">&#2547;<?= h(number_format($total, 2)) ?></b></td></tr>
    </tfoot>
</table>

<div class="form-actions">
    <a class="btn btn-light" href="<?= url('home') ?>">Continue Shopping</a>
    <a class="btn btn-green" href="<?= url('checkout') ?>">Proceed to Checkout</a>
</div>
<?php endif; ?>

<script src="<?= asset('task3_23544053/assets/js/task3.js') ?>"></script>
<?php render_footer(); ?>
