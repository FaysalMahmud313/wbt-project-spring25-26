<?php

require_once __DIR__ . "/../models/cart_model.php";

if (current_role() !== "customer") {
    json_response(["success" => false, "message" => "Please log in as a customer."], 403);
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_response(["success" => false, "message" => "POST required"], 405);
}

$uid        = current_user_id();
$medicineId = (int)($_POST["medicine_id"] ?? 0);
$qty        = (int)($_POST["quantity"] ?? 1);

if ($medicineId <= 0 || $qty <= 0) {
    json_response(["success" => false, "message" => "Invalid input"], 400);
}

$med = t3_medicine_find($conn, $medicineId);
if (!$med) {
    json_response(["success" => false, "message" => "Medicine not found"], 404);
}
if ($qty > (int)$med["availability"]) {
    json_response(["success" => false, "message" => "Not enough stock"], 400);
}

if (t3_cart_add($conn, $uid, $medicineId, $qty)) {
    json_response([
        "success"    => true,
        "message"    => "Added to cart",
        "cart_count" => t3_cart_count($conn, $uid),
    ]);
}
json_response(["success" => false, "message" => "Could not add to cart"], 500);
