<?php
/* ---------------------------------------------------------------------------
 * Task 2 controller: Category CRUD  (action routing like crud.php)
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/category_model.php";
require_once BASE_PATH . "/shared/layout.php";

require_admin();

$action  = $_REQUEST["action"] ?? "";
$message = "";
$error   = "";
$editing = null;

/* ---------- CREATE ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "create") {
    $name = trim($_POST["name"] ?? "");
    $type = ($_POST["category_type"] ?? "solid") === "liquid" ? "liquid" : "solid";

    if ($name === "") {
        $error = "Category name is required.";
    } elseif (t2_category_create($conn, $name, $type)) {
        $message = "Category added successfully.";
    } else {
        $error = "Could not add category.";
    }
}

/* ---------- UPDATE ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "update") {
    $id   = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["name"] ?? "");
    $type = ($_POST["category_type"] ?? "solid") === "liquid" ? "liquid" : "solid";

    if ($id <= 0 || $name === "") {
        $error = "Invalid input.";
    } elseif (t2_category_update($conn, $id, $name, $type)) {
        $message = "Category #$id updated.";
    } else {
        $error = "Could not update category.";
    }
}

/* ---------- DELETE (blocked if medicines exist) ---------- */
if ($action === "delete") {
    $id = (int)($_REQUEST["id"] ?? 0);
    if ($id > 0) {
        if (t2_category_has_medicines($conn, $id)) {
            $error = "Cannot delete: medicines exist in this category.";
        } elseif (t2_category_delete($conn, $id)) {
            $message = "Category #$id deleted.";
        } else {
            $error = "Could not delete category.";
        }
    }
}

/* ---------- Load record for EDIT ---------- */
if ($action === "edit") {
    $editing = t2_category_find($conn, (int)($_REQUEST["id"] ?? 0));
    if (!$editing) { $error = "Category not found."; }
}

$categories = t2_categories_all($conn);
require __DIR__ . "/../views/categories.php";
