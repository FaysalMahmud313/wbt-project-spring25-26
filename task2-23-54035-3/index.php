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
$page   = $_GET["page"] ?? "admin";
$routes = [
    "login"               => "$T1/controllers/auth_controller.php",
    "logout"              => "$T1/controllers/auth_controller.php",

    "admin"               => __DIR__ . "/controllers/admin_controller.php",
    "admin_categories"    => __DIR__ . "/controllers/category_controller.php",
    "admin_medicines"     => __DIR__ . "/controllers/medicine_controller.php",
    "admin_customers"     => __DIR__ . "/controllers/customer_controller.php",
    "admin_users"         => __DIR__ . "/controllers/admin_user_controller.php",
    "admin_orders"        => __DIR__ . "/controllers/order_controller.php",
    "admin_history"       => __DIR__ . "/controllers/order_controller.php",
    "api_order_status"    => __DIR__ . "/ajax/order_status.php",
];

if (isset($routes[$page]) && file_exists($routes[$page])) {
    require $routes[$page];
} else {
    require __DIR__ . "/controllers/admin_controller.php";
}

mysqli_close($conn);
