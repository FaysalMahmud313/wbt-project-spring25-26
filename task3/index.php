<?php
$_root = str_replace("\\", "/", dirname(dirname($_SERVER["SCRIPT_NAME"])));
$_root = rtrim($_root, "/");
define("BASE_URL", $_root === "" ? "/" : $_root . "/");

require __DIR__ . "/../config/config.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../shared/helpers.php";
require __DIR__ . "/../shared/auth.php";

function seed_default_users($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users");
    $row    = mysqli_fetch_assoc($result);
    if ((int)$row["c"] > 0) return;
    $accounts = [
        ["Admin",         "admin@shop.com",    "admin123",    "admin",    "HQ Office, Dhaka",      "01700000000"],
        ["John Customer", "customer@shop.com", "customer123", "customer", "House 1, Road 2, Dhaka","01800000000"],
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

$page = $_GET["page"] ?? "cart";

$T1 = __DIR__ . "/../task1_23540323";
$T2 = __DIR__ . "/../task2_23540353";
$T3 = __DIR__;

$routes = [
    "home"                => "$T1/controllers/home_controller.php",
    "category"            => "$T1/controllers/home_controller.php",
    "register"            => "$T1/controllers/auth_controller.php",
    "login"               => "$T1/controllers/auth_controller.php",
    "logout"              => "$T1/controllers/auth_controller.php",
    "profile"             => "$T1/controllers/profile_controller.php",
    "api_medicine_search" => "$T1/ajax/medicine_search.php",

    "admin"               => "$T2/controllers/admin_controller.php",
    "admin_categories"    => "$T2/controllers/category_controller.php",
    "admin_medicines"     => "$T2/controllers/medicine_controller.php",
    "admin_customers"     => "$T2/controllers/customer_controller.php",
    "admin_users"         => "$T2/controllers/admin_user_controller.php",
    "admin_orders"        => "$T2/controllers/order_controller.php",
    "admin_history"       => "$T2/controllers/order_controller.php",
    "api_order_status"    => "$T2/ajax/order_status.php",

    "cart"                => "$T3/controllers/cart_controller.php",
    "checkout"            => "$T3/controllers/checkout_controller.php",
    "invoice"             => "$T3/controllers/checkout_controller.php",
    "payment"             => "$T3/controllers/checkout_controller.php",
    "place_order"         => "$T3/controllers/checkout_controller.php",
    "my_orders"           => "$T3/controllers/checkout_controller.php",
    "bill"                => "$T3/controllers/checkout_controller.php",
    "api_cart_add"        => "$T3/ajax/cart_add.php",
    "api_cart_update"     => "$T3/ajax/cart_update.php",
    "api_cart_remove"     => "$T3/ajax/cart_remove.php",
];

if (isset($routes[$page]) && file_exists($routes[$page])) {
    require $routes[$page];
} else {
    require "$T3/controllers/cart_controller.php";
}

mysqli_close($conn);
