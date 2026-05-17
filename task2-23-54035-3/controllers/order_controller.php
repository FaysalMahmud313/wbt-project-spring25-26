<?php
/* ---------------------------------------------------------------------------
 * Task 2 controller: View all purchase requests + purchase history
 * (Accept/Reject is handled by the AJAX endpoint ajax/order_status.php)
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/order_model.php";
require_once BASE_PATH . "/shared/layout.php";

require_admin();

$page = $_GET["page"] ?? "admin_orders";

if ($page === "admin_history") {
    $rows = t2_orders_history($conn);
    require __DIR__ . "/../views/history.php";
} else {
    $orders = t2_orders_all($conn);
    require __DIR__ . "/../views/orders.php";
}
