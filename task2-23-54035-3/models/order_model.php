<?php
/* ---------------------------------------------------------------------------
 * Task 2 model: orders (view all requests, accept/reject, history) + stats
 * ------------------------------------------------------------------------- */

function t2_orders_all($conn) {
    $res = mysqli_query($conn,
        "SELECT o.*, u.name AS customer_name, u.email AS customer_email
           FROM orders o
           JOIN users u ON u.id = o.user_id
          ORDER BY o.id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/* Accepted orders only, with their medicine line items (purchase history) */
function t2_orders_history($conn) {
    $res = mysqli_query($conn,
        "SELECT o.id AS order_id, o.total_amount, o.order_date,
                u.name AS customer_name, u.email AS customer_email,
                m.name AS medicine_name, oi.quantity, oi.unit_price
           FROM orders o
           JOIN users u       ON u.id = o.user_id
           JOIN order_items oi ON oi.order_id = o.id
           JOIN medicines m   ON m.id = oi.medicine_id
          WHERE o.status = 'accepted'
          ORDER BY o.id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function t2_order_update_status($conn, $id, $status) {
    $stmt = mysqli_prepare($conn,
        "UPDATE orders SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

/* Dashboard counts */
function t2_dashboard_stats($conn) {
    $stats = [];
    $stats["medicines"]  = (int)mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) c FROM medicines"))["c"];
    $stats["categories"] = (int)mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) c FROM categories"))["c"];
    $stats["customers"]  = (int)mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) c FROM users WHERE role='customer'"))["c"];
    $stats["pending"]    = (int)mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) c FROM orders WHERE status='pending'"))["c"];
    return $stats;
}
