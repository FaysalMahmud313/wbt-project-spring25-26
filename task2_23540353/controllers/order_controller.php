<?php

require_once __DIR__ . "/../models/order_model.php";
require_once __DIR__ . "/../views/layout.php";

require_admin();

$page = $_GET["page"] ?? "admin_orders";

if ($page === "admin_history") {
    $rows = t2_orders_history($conn);
    require __DIR__ . "/../views/history.php";
} else {
    $orders = t2_orders_all($conn);
    require __DIR__ . "/../views/orders.php";
}
