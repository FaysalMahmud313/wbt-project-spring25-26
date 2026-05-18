<?php

$_r = str_replace("\\", "/", dirname(dirname($_SERVER["SCRIPT_NAME"])));
define("BASE_URL", (rtrim($_r, "/") ?: "") . "/");

require __DIR__ . "/config.php";    
require __DIR__ . "/helpers.php";
require __DIR__ . "/auth.php";

function seed_default_users($conn) {
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"));
    if ((int)$row["c"] > 0) return;
    $accounts = [
        ["Admin",         "admin@shop.com",    "admin123",    "admin",    "HQ Office, Dhaka",       "01700000000"],
        ["John Customer", "customer@shop.com", "customer123", "customer", "House 1, Road 2, Dhaka", "01800000000"],
    ];
    foreach ($accounts as $u) {
        $hash = password_hash($u[2], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (name,email,password_hash,role,address,phone) VALUES (?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $u[0], $u[1], $hash, $u[3], $u[4], $u[5]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
seed_default_users($conn);
attempt_remember_login($conn);

$T1     = __DIR__ . "/../task1_23540323";
$page   = $_GET["page"] ?? "cart";
$routes = [
    "login"               => "$T1/controllers/auth_controller.php",
    "logout"              => "$T1/controllers/auth_controller.php",

    "cart"                => __DIR__ . "/controllers/cart_controller.php",
    "checkout"            => __DIR__ . "/controllers/checkout_controller.php",
    "invoice"             => __DIR__ . "/controllers/checkout_controller.php",
    "payment"             => __DIR__ . "/controllers/checkout_controller.php",
    "place_order"         => __DIR__ . "/controllers/checkout_controller.php",
    "my_orders"           => __DIR__ . "/controllers/checkout_controller.php",
    "bill"                => __DIR__ . "/controllers/checkout_controller.php",
    "api_cart_add"        => __DIR__ . "/ajax/cart_add.php",
    "api_cart_update"     => __DIR__ . "/ajax/cart_update.php",
    "api_cart_remove"     => __DIR__ . "/ajax/cart_remove.php",
];

if (isset($routes[$page]) && file_exists($routes[$page])) {
    require $routes[$page];
} else {
    require __DIR__ . "/controllers/cart_controller.php";
}

mysqli_close($conn);
