<?php

require_once __DIR__ . "/../models/cart_model.php";
require_once __DIR__ . "/../models/order_model.php";
require_once __DIR__ . "/../views/layout.php";

require_customer();

$page  = $_GET["page"] ?? "checkout";
$uid   = current_user_id();
$error = "";

/* My Orders list (order confirmation / status page) */
if ($page === "my_orders") {
    $orders = t3_my_orders($conn, $uid);
    $itemsByOrder = [];
    foreach ($orders as $o) {
        $itemsByOrder[$o["id"]] = t3_order_items($conn, $o["id"], $uid);
    }
    require __DIR__ . "/../views/orders.php";
    return;
}

/* Downloadable bill for a placed order (shows the order number) */
if ($page === "bill") {
    $orderId = (int)($_GET["id"] ?? 0);
    $order   = t3_order_get($conn, $orderId, $uid);
    if (!$order) {
        set_flash("error", "Bill not found.");
        redirect("my_orders");
    }
    $billItems = t3_order_items($conn, $orderId, $uid);
    require __DIR__ . "/../views/bill.php";
    return;
}

/* Load cart for every checkout step */
$items = t3_cart_items($conn, $uid);
$total = 0;
foreach ($items as $it) {
    $total += $it["price"] * $it["quantity"];
}
if (!$items) {
    set_flash("error", "Your cart is empty.");
    redirect("cart");
}

/* Step 1: shipping address form -> store -> invoice */
if ($page === "checkout") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $address = trim($_POST["shipping_address"] ?? "");
        if ($address === "") {
            $error = "Shipping address is required.";
        } else {
            $_SESSION["checkout_address"] = $address;
            redirect("invoice");
        }
    }
    // Pre-fill from profile
    $stmt = mysqli_prepare($conn, "SELECT address FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $res     = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    $address = $_SESSION["checkout_address"] ?? ($profile["address"] ?? "");

    require __DIR__ . "/../views/checkout.php";
    return;
}

/* Step 2: invoice review */
if ($page === "invoice") {
    if (empty($_SESSION["checkout_address"])) {
        redirect("checkout");
    }
    $address = $_SESSION["checkout_address"];
    require __DIR__ . "/../views/invoice.php";
    return;
}

/* Step 3: payment method selection + place order */
if ($page === "payment") {
    if (empty($_SESSION["checkout_address"])) {
        redirect("checkout");
    }
    $address = $_SESSION["checkout_address"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $method  = $_POST["payment_method"] ?? "";
        $allowed = ["Credit Card", "bKash", "Nagad", "Bank Transfer", "Cash on Delivery"];

        // Server-side validation: payment method + stock availability
        if (!in_array($method, $allowed, true)) {
            $error = "Please select a valid payment method.";
        } else {
            foreach ($items as $it) {
                $med = t3_medicine_find($conn, $it["medicine_id"]);
                if (!$med || $it["quantity"] > (int)$med["availability"]) {
                    $error = "Not enough stock for " . $it["name"] . ".";
                    break;
                }
            }
        }

        if ($error === "") {
            $orderId = t3_place_order($conn, $uid, $items, $address, $method);
            if ($orderId) {
                t3_cart_clear($conn, $uid);
                unset($_SESSION["checkout_address"]);
                set_flash("success", "Order #$orderId placed. Pending admin approval.");
                redirect("bill", ["id" => $orderId]);
            } else {
                $error = "Could not place the order. Please try again.";
            }
        }
    }
    require __DIR__ . "/../views/payment.php";
    return;
}

redirect("cart");
