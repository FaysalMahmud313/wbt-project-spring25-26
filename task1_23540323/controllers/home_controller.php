<?php

require_once __DIR__ . "/../models/category_model.php";
require_once __DIR__ . "/../models/medicine_model.php";
require_once __DIR__ . "/../views/layout.php";

$page       = $_GET["page"] ?? "home";
$categories = categories_all($conn);

if ($page === "category") {
    $categoryId  = (int)($_GET["id"] ?? 0);
    $typeFilter  = $_GET["type"] ?? "";       
    $category    = category_find($conn, $categoryId);
    $medicines   = medicines_by_category($conn, $categoryId);


    if ($typeFilter === "liquid" || $typeFilter === "solid") {
        $medicines = array_values(array_filter($medicines,
            fn($m) => $m["category_type"] === $typeFilter));
    }
    require __DIR__ . "/../views/category.php";
} else {
    $medicines = medicines_by_category($conn, 0);
    require __DIR__ . "/../views/home.php";
}
