<?php
/* ---------------------------------------------------------------------------
 * Task 1 model: categories (read only for browsing)
 * ------------------------------------------------------------------------- */

function categories_all($conn) {
    $res = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function category_find($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM categories WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}
