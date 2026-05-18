<?php

function t2_customers_all($conn) {
    $res = mysqli_query($conn,
        "SELECT id, name, email, phone, address, created_at
           FROM users WHERE role = 'customer' ORDER BY id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}



function t2_admins_all($conn) {
    $res = mysqli_query($conn,
        "SELECT id, name, email, phone, created_at
           FROM users WHERE role = 'admin' ORDER BY id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function t2_email_exists($conn, $email) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return (bool)$row;
}

function t2_admin_create($conn, $name, $email, $hash, $address, $phone) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO users (name, email, password_hash, role, address, phone)
         VALUES (?, ?, ?, 'admin', ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss",
        $name, $email, $hash, $address, $phone);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}


function t2_customer_delete($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "DELETE FROM users WHERE id = ? AND role = 'customer'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
