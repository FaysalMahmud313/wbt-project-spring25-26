<?php
/* ---------------------------------------------------------------------------
 * Task 2 controller: Medicine CRUD with secure image upload
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/medicine_model.php";
require_once BASE_PATH . "/shared/layout.php";

require_admin();

$action  = $_REQUEST["action"] ?? "";
$message = "";
$error   = "";
$editing = null;

/* Shared validation for create/update form fields */
function validate_medicine(&$error) {
    $name   = trim($_POST["name"]        ?? "");
    $catId  = (int)($_POST["category_id"] ?? 0);
    $vendor = trim($_POST["vendor_name"] ?? "");
    $price  = (float)($_POST["price"]    ?? 0);
    $stock  = (int)($_POST["availability"] ?? 0);
    $desc   = trim($_POST["description"] ?? "");

    if ($name === "" || $vendor === "" || $catId <= 0) {
        $error = "Name, vendor and category are required.";
        return false;
    }
    if ($price <= 0) {
        $error = "Price must be greater than 0.";
        return false;
    }
    if ($stock < 0) {
        $error = "Stock cannot be negative.";
        return false;
    }
    return [$name, $catId, $vendor, $price, $stock, $desc];
}

/* ---------- CREATE ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "create") {
    $data = validate_medicine($error);
    if ($data) {
        $upErr = "";
        $saved = save_uploaded_image("image", MEDICINE_UPLOAD, $upErr);
        if ($saved === false) {
            $error = $upErr;
        } else {
            $imgPath = $saved !== "" ? "public/uploads/medicines/" . $saved : null;
            if (t2_medicine_create($conn, $data[0], $data[1], $data[2],
                                   $data[3], $data[4], $data[5], $imgPath)) {
                $message = "Medicine added successfully.";
            } else {
                $error = "Could not add medicine.";
            }
        }
    }
}

/* ---------- UPDATE ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "update") {
    $id   = (int)($_POST["id"] ?? 0);
    $data = validate_medicine($error);
    if ($id > 0 && $data) {
        $upErr = "";
        $saved = save_uploaded_image("image", MEDICINE_UPLOAD, $upErr);
        if ($saved === false) {
            $error = $upErr;
        } else {
            $imgPath = $saved !== "" ? "public/uploads/medicines/" . $saved : null;
            if (t2_medicine_update($conn, $id, $data[0], $data[1], $data[2],
                                   $data[3], $data[4], $data[5], $imgPath)) {
                $message = "Medicine #$id updated.";
            } else {
                $error = "Could not update medicine.";
            }
        }
    }
}

/* ---------- DELETE (blocked if in a pending order; removes image) ---------- */
if ($action === "delete") {
    $id = (int)($_REQUEST["id"] ?? 0);
    if ($id > 0) {
        if (t2_medicine_in_pending_order($conn, $id)) {
            $error = "Cannot delete: medicine is in a pending order.";
        } else {
            $med = t2_medicine_find($conn, $id);
            if ($med && t2_medicine_delete($conn, $id)) {
                // Remove the uploaded image file from disk
                if (!empty($med["image_path"])) {
                    $file = BASE_PATH . "/" . $med["image_path"];
                    if (is_file($file)) { unlink($file); }
                }
                $message = "Medicine #$id deleted.";
            } else {
                $error = "Could not delete medicine.";
            }
        }
    }
}

/* ---------- Load record for EDIT ---------- */
if ($action === "edit") {
    $editing = t2_medicine_find($conn, (int)($_REQUEST["id"] ?? 0));
    if (!$editing) { $error = "Medicine not found."; }
}

$medicines  = t2_medicines_all($conn);
$categories = t2_categories_for_dropdown($conn);
require __DIR__ . "/../views/medicines.php";
