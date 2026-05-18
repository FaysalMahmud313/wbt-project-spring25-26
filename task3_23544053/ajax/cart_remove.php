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

if ($cartId <= 0) {
    json_response(["success" => false, "message" => "Invalid input"], 400);
}

if (t3_cart_remove($conn, $uid, $cartId)) {
    $items = t3_cart_items($conn, $uid);
    $total = 0;
    foreach ($items as $it) {
        $total += $it["price"] * $it["quantity"];
    }
    json_response([
        "success"    => true,
        "total"      => number_format($total, 2),
        "cart_count" => t3_cart_count($conn, $uid),
        "empty"      => count($items) === 0,
    ]);
}
json_response(["success" => false, "message" => "Remove failed"], 500);
