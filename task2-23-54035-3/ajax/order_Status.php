<?php
/* ---------------------------------------------------------------------------
 * Task 2 AJAX endpoint: POST /api/orders/update-status
 * Route: index.php?page=api_order_status   (admin only)
 * Body: order_id, status (accepted|rejected)  -> JSON response
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/order_model.php";

if (current_role() !== "admin") {
    json_response(["success" => false, "message" => "Unauthorized"], 403);
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_response(["success" => false, "message" => "POST required"], 405);
}

$orderId = (int)($_POST["order_id"] ?? 0);
$status  = $_POST["status"] ?? "";

if ($orderId <= 0 || !in_array($status, ["accepted", "rejected"], true)) {
    json_response(["success" => false, "message" => "Invalid input"], 400);
}

if (t2_order_update_status($conn, $orderId, $status)) {
    json_response(["success" => true, "order_id" => $orderId, "status" => $status]);
}
json_response(["success" => false, "message" => "Update failed"], 500);
