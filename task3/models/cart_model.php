<?php

function t3_cart_items($conn, $userId) {
    $stmt = mysqli_prepare($conn,
        "SELECT ct.id AS cart_id, ct.quantity,
                m.id AS medicine_id, m.name, m.vendor_name, m.price, m.availability
           FROM cart ct
           JOIN medicines m ON m.id = ct.medicine_id
          WHERE ct.user_id = ?
          ORDER BY ct.id DESC");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function t3_cart_count($conn, $userId) {
    $stmt = mysqli_prepare($conn,
        "SELECT COALESCE(SUM(quantity),0) AS c FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return (int)$row["c"];
}

function t3_cart_add($conn, $userId, $medicineId, $qty) {
    $stmt = mysqli_prepare($conn,
        "SELECT id, quantity FROM cart WHERE user_id = ? AND medicine_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $medicineId);
    mysqli_stmt_execute($stmt);
    $res      = mysqli_stmt_get_result($stmt);
    $existing = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($existing) {
        $newQty = (int)$existing["quantity"] + $qty;
        $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $newQty, $existing["id"]);
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO cart (user_id, medicine_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $userId, $medicineId, $qty);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t3_cart_set_qty($conn, $userId, $cartId, $qty) {
    $stmt = mysqli_prepare($conn,
        "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "iii", $qty, $cartId, $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t3_cart_remove($conn, $userId, $cartId) {
    $stmt = mysqli_prepare($conn,
        "DELETE FROM cart WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $cartId, $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t3_cart_clear($conn, $userId) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function t3_medicine_find($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, price, availability FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}
