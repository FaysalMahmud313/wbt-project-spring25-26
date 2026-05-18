<?php

function t2_medicines_all($conn) {
    $res = mysqli_query($conn,
        "SELECT m.*, c.name AS category_name
           FROM medicines m
           JOIN categories c ON c.id = m.category_id
          ORDER BY m.id DESC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function t2_medicine_find($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

function t2_medicine_create($conn, $name, $catId, $vendor, $price, $stock, $desc, $image) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO medicines
            (name, category_id, vendor_name, price, availability, description, image_path)
         VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sisdiss",
        $name, $catId, $vendor, $price, $stock, $desc, $image);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t2_medicine_update($conn, $id, $name, $catId, $vendor, $price, $stock, $desc, $image) {
    if ($image !== null) {
        $stmt = mysqli_prepare($conn,
            "UPDATE medicines SET name=?, category_id=?, vendor_name=?, price=?,
                 availability=?, description=?, image_path=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sisdissi",
            $name, $catId, $vendor, $price, $stock, $desc, $image, $id);
    } else {
        $stmt = mysqli_prepare($conn,
            "UPDATE medicines SET name=?, category_id=?, vendor_name=?, price=?,
                 availability=?, description=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sisdisi",
            $name, $catId, $vendor, $price, $stock, $desc, $id);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}


function t2_medicine_in_pending_order($conn, $id) {
    $stmt = mysqli_prepare($conn,
        "SELECT COUNT(*) AS c
           FROM order_items oi
           JOIN orders o ON o.id = oi.order_id
          WHERE oi.medicine_id = ? AND o.status = 'pending'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return (int)$row["c"] > 0;
}

function t2_medicine_delete($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function t2_categories_for_dropdown($conn) {
    $res = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}
