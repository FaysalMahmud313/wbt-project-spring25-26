<?php

require_once __DIR__ . "/../models/cart_model.php";

if (current_role() !== "customer") {
    json_response(["success" => false, "message" => "Unauthorized"], 403);
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_response(["success" => false, "message" => "POST required"], 405);
}

$uid    = current_user_id();
$cartId = (int)($_POST["cart_id"] ?? 0);
$qty    = (int)($_POST["quantity"] ?? 0);

if ($cartId <= 0 || $qty <= 0) {
    json_response(["success" => false, "message" => "Quantity must be a positive number"], 400);
}


$items = t3_cart_items($conn, $uid);
$row   = null;
foreach ($items as $it) {
    if ((int)$it["cart_id"] === $cartId) { $row = $it; break; }
}
if (!$row) {
    json_response(["success" => false, "message" => "Cart item not found"], 404);
}
if ($qty > (int)$row["availability"]) {
    json_response(["success" => false, "message" => "Only " . $row["availability"] . " in stock"], 400);
}

if (t3_cart_set_qty($conn, $uid, $cartId, $qty)) {
   
    $items = t3_cart_items($conn, $uid);
    $total = 0;
    $sub   = 0;
    foreach ($items as $it) {
        $line = $it["price"] * $it["quantity"];
        $total += $line;
        if ((int)$it["cart_id"] === $cartId) { $sub = $line; }
    }
    json_response([
        "success"    => true,
        "subtotal"   => number_format($sub, 2),
        "total"      => number_format($total, 2),
        "cart_count" => t3_cart_count($conn, $uid),
    ]);
}
json_response(["success" => false, "message" => "Update failed"], 500);
