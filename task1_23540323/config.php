<?php

define("TASK_PATH", __DIR__); 
define("BASE_PATH", dirname(__DIR__)); 

if (!defined("BASE_URL")) {
    $s = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
    define("BASE_URL", (rtrim($s, "/") ?: "") . "/");
}


define("PROFILE_UPLOAD",  TASK_PATH . "/uploads/profiles");
define("MEDICINE_UPLOAD", BASE_PATH . "/task2_23540353/uploads/medicines");

define("MAX_UPLOAD_BYTES", 2 * 1024 * 1024); 
$ALLOWED_IMAGE_MIME = ["image/jpeg" => "jpg", "image/png" => "png"];

if (!is_dir(PROFILE_UPLOAD)) mkdir(PROFILE_UPLOAD, 0755, true);

$conn = mysqli_connect("localhost", "root", "", "online_medicine_shop");
if (!$conn) die("DB connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8mb4");
?>
