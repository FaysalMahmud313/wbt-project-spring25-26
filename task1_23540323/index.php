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

$page   = $_GET["page"] ?? "home";
$routes = [
    "home"                => __DIR__ . "/controllers/home_controller.php",
    "category"            => __DIR__ . "/controllers/home_controller.php",
    "register"            => __DIR__ . "/controllers/auth_controller.php",
    "login"               => __DIR__ . "/controllers/auth_controller.php",
    "logout"              => __DIR__ . "/controllers/auth_controller.php",
    "profile"             => __DIR__ . "/controllers/profile_controller.php",
    "api_medicine_search" => __DIR__ . "/ajax/medicine_search.php",
];

if (isset($routes[$page]) && file_exists($routes[$page])) {
    require $routes[$page];
} else {
    require __DIR__ . "/controllers/home_controller.php";
}

mysqli_close($conn);
