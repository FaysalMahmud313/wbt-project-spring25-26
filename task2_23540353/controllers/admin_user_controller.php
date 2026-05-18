<?php

require_once __DIR__ . "/../models/user_model.php";
require_once __DIR__ . "/../views/layout.php";

require_admin();

$message = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create_admin") {
    $name    = trim($_POST["name"]    ?? "");
    $email   = trim($_POST["email"]   ?? "");
    $pass    = $_POST["password"]     ?? "";
    $address = trim($_POST["address"] ?? "");
    $phone   = trim($_POST["phone"]   ?? "");

    
    if ($name === "" || $email === "" || $pass === "" || $address === "" || $phone === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($pass) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (t2_email_exists($conn, $email)) {
        $error = "That email is already registered.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        if (t2_admin_create($conn, $name, $email, $hash, $address, $phone)) {
            $message = "New admin account created.";
        } else {
            $error = "Could not create admin.";
        }
    }
}

$admins = t2_admins_all($conn);
require __DIR__ . "/../views/admins.php";
