<?php
function t3_place_order($conn, $userId, $cartItems, $address, $paymentMethod) {
    $total = 0;
    foreach ($cartItems as $it) {
        $total += $it["price"] * $it["quantity"];
    }

    mysqli_begin_transaction($conn);
    try {
        // orders
        $stmt = mysqli_prepare($conn,
            "INSERT INTO orders (user_id, total_amount, shipping_address, status, payment_method)
             VALUES (?, ?, ?, 'pending', ?)");
        mysqli_stmt_bind_param($stmt, "idss",
            $userId, $total, $address, $paymentMethod);
        mysqli_stmt_execute($stmt);
        $orderId = mysqli_stmt_insert_id($stmt);
        mysqli_stmt_close($stmt);

        // order_items + reduce stock
        foreach ($cartItems as $it) {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO order_items (order_id, medicine_id, quantity, unit_price)
                 VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiid",
                $orderId, $it["medicine_id"], $it["quantity"], $it["price"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn,
                "UPDATE medicines SET availability = availability - ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $it["quantity"], $it["medicine_id"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // payments
        $txn  = "TXN" . strtoupper(uniqid());
        $stmt = mysqli_prepare($conn,
            "INSERT INTO payments (order_id, amount, payment_method, transaction_id)
             VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "idss",
            $orderId, $total, $paymentMethod, $txn);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        return $orderId;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return false;
    }
}

function t3_my_orders($conn, $userId) {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

/* One order (with customer details) restricted to its owner */
function t3_order_get($conn, $orderId, $userId) {
    $stmt = mysqli_prepare($conn,
        "SELECT o.*, u.name AS customer_name, u.email AS customer_email,
                u.phone AS customer_phone
           FROM orders o
           JOIN users u ON u.id = o.user_id
          WHERE o.id = ? AND o.user_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $orderId, $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

function t3_order_items($conn, $orderId, $userId) {
    $stmt = mysqli_prepare($conn,
        "SELECT oi.quantity, oi.unit_price, m.name
           FROM order_items oi
           JOIN medicines m ON m.id = oi.medicine_id
           JOIN orders o    ON o.id = oi.order_id
          WHERE oi.order_id = ? AND o.user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $orderId, $userId);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
