<?php
/* ===========================================================================
 * Online Medicine Shop  --  Global Front Controller
 * Use after merging all 3 tasks: localhost/online_medicine_shop/
 * ========================================================================= */

define("BASE_PATH", __DIR__);
$_s = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
define("BASE_URL", (rtrim($_s, "/") ?: "") . "/");

define("MEDICINE_UPLOAD", BASE_PATH . "/task2_23540353/uploads/medicines");
define("PROFILE_UPLOAD",  BASE_PATH . "/task1_23540323/uploads/profiles");
define("MAX_UPLOAD_BYTES", 2 * 1024 * 1024);
$ALLOWED_IMAGE_MIME = ["image/jpeg" => "jpg", "image/png" => "png"];

$conn = mysqli_connect("localhost", "root", "", "online_medicine_shop");
if (!$conn) die("DB connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8mb4");

require __DIR__ . "/task1_23540323/helpers.php";
require __DIR__ . "/task1_23540323/auth.php";

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

$page = $_GET["page"] ?? "home";

$T1 = __DIR__ . "/task1_23540323";
$T2 = __DIR__ . "/task2_23540353";
$T3 = __DIR__ . "/task3_23544053";

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
    require "$T1/controllers/home_controller.php";
}

mysqli_close($conn);
