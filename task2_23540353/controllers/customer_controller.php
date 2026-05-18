<?php

require_once __DIR__ . "/../models/user_model.php";
require_once __DIR__ . "/../views/layout.php";

require_admin();

$message = "";
$error   = "";

if (($_REQUEST["action"] ?? "") === "delete") {
    $id = (int)($_REQUEST["id"] ?? 0);
    if ($id > 0 && t2_customer_delete($conn, $id)) {
        $message = "Customer #$id deleted (cart & orders removed).";
    } else {
        $error = "Could not delete customer.";
    }
}

$customers = t2_customers_all($conn);
require __DIR__ . "/../views/customers.php";
