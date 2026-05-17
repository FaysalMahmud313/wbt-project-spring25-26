<?php
require_once __DIR__ . "/../models/cart_model.php";
require_once BASE_PATH . "/shared/layout.php";

require_customer();

$items = t3_cart_items($conn, current_user_id());
$total = 0;
foreach ($items as $it) {
    $total += $it["price"] * $it["quantity"];
}

require __DIR__ . "/../views/cart.php";
