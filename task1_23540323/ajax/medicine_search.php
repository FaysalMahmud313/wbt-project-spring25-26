<?php

require_once __DIR__ . "/../models/medicine_model.php";

$q       = trim($_GET["q"]      ?? "");
$vendor  = trim($_GET["vendor"] ?? "");
$genreId = (int)($_GET["genre"] ?? 0);

$rows = medicines_search($conn, $q, $vendor, $genreId);

// Clean output for the client
$out = [];
foreach ($rows as $m) {
    $out[] = [
        "id"            => (int)$m["id"],
        "name"          => $m["name"],
        "vendor_name"   => $m["vendor_name"],
        "category_name" => $m["category_name"],
        "category_type" => $m["category_type"],
        "price"         => number_format($m["price"], 2),
        "availability"  => (int)$m["availability"],
        "image_path"    => $m["image_path"],
    ];
}

json_response(["success" => true, "count" => count($out), "medicines" => $out]);
