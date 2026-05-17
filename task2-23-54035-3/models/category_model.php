<?php
/* ---------------------------------------------------------------------------
 * Task 2 model: categories (full CRUD)
 * ------------------------------------------------------------------------- */

function t2_categories_all($conn) {
    $res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function t2_category_find($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

function t2_category_create($conn, $name, $type) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO categories (name, category_type) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $name, $type);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t2_category_update($conn, $id, $name, $type) {
    $stmt = mysqli_prepare($conn,
        "UPDATE categories SET name = ?, category_type = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $type, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

/* Block deletion if medicines exist in this category */
function t2_category_has_medicines($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "SELECT COUNT(*) AS c FROM medicines WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return (int)$row["c"] > 0;
}

function t2_category_delete($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
