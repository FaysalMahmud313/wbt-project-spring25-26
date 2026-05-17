<?php

function medicines_by_category($conn, $categoryId = 0) {
    if ($categoryId > 0) {
        $stmt = mysqli_prepare($conn,
            "SELECT m.*, c.name AS category_name, c.category_type
               FROM medicines m
               JOIN categories c ON c.id = m.category_id
              WHERE m.category_id = ?
              ORDER BY m.name ASC");
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
    } else {
        $stmt = mysqli_prepare($conn,
            "SELECT m.*, c.name AS category_name, c.category_type
               FROM medicines m
               JOIN categories c ON c.id = m.category_id
              ORDER BY m.name ASC");
    }
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function medicines_search($conn, $q, $vendor, $genreId) {
    $sql = "SELECT m.*, c.name AS category_name, c.category_type
              FROM medicines m
              JOIN categories c ON c.id = m.category_id
             WHERE m.name LIKE ? AND m.vendor_name LIKE ?";
    $like  = "%" . $q . "%";
    $vlike = "%" . $vendor . "%";

    if ($genreId > 0) {
        $sql .= " AND m.category_id = ? ORDER BY m.name ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $like, $vlike, $genreId);
    } else {
        $sql .= " ORDER BY m.name ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $like, $vlike);
    }
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
