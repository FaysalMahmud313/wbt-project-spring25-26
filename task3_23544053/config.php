<?php

define("TASK_PATH", __DIR__);
define("BASE_PATH", dirname(__DIR__));

if (!defined("BASE_URL")) {
    $s = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
    define("BASE_URL", (rtrim($s, "/") ?: "") . "/");
}

// Task 3 reads images; writes are handled by task 1 (profiles) and task 2 (medicines)
define("MEDICINE_UPLOAD", BASE_PATH . "/task2_23540353/uploads/medicines");
define("PROFILE_UPLOAD",  BASE_PATH . "/task1_23540323/uploads/profiles");

define("MAX_UPLOAD_BYTES", 2 * 1024 * 1024);
$ALLOWED_IMAGE_MIME = ["image/jpeg" => "jpg", "image/png" => "png"];

$conn = mysqli_connect("localhost", "root", "", "online_medicine_shop");
if (!$conn) die("DB connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8mb4");
