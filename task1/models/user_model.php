<?php
/* ---------------------------------------------------------------------------
 * Task 1 model: users  (registration / login / profile)
 * Every query uses mysqli prepared statements.
 * ------------------------------------------------------------------------- */

function user_find_by_email($conn, $email) {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $user;
}

function user_find_by_id($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $user;
}

function user_create($conn, $name, $email, $hash, $role, $address, $phone) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO users (name, email, password_hash, role, address, phone)
         VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss",
        $name, $email, $hash, $role, $address, $phone);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function user_update_profile($conn, $id, $name, $email, $address, $phone, $picture) {
    if ($picture !== null) {
        $stmt = mysqli_prepare($conn,
            "UPDATE users SET name=?, email=?, address=?, phone=?, profile_picture=?
             WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi",
            $name, $email, $address, $phone, $picture, $id);
    } else {
        $stmt = mysqli_prepare($conn,
            "UPDATE users SET name=?, email=?, address=?, phone=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi",
            $name, $email, $address, $phone, $id);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function user_update_password($conn, $id, $newHash) {
    $stmt = mysqli_prepare($conn,
        "UPDATE users SET password_hash=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $newHash, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function user_set_remember_token($conn, $id, $token) {
    $stmt = mysqli_prepare($conn,
        "UPDATE users SET remember_token=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $token, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
