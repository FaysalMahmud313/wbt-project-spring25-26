<?php

require_once __DIR__ . "/../models/user_model.php";
require_once __DIR__ . "/../views/layout.php";

$page    = $_GET["page"];
$message = "";
$error   = "";

if ($page === "logout") {
    if (is_logged_in()) {
        user_set_remember_token($conn, current_user_id(), null);
    }
    $_SESSION = [];
    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
 
    session_start();
    set_flash("success", "You have been logged out.");
    redirect("login");
}

if ($page === "register" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"]     ?? "");
    $email   = trim($_POST["email"]    ?? "");
    $pass    = $_POST["password"]      ?? "";
    $address = trim($_POST["address"]  ?? "");
    $phone   = trim($_POST["phone"]    ?? "");

    $role    = "customer";

 
    if ($name === "" || $email === "" || $pass === "" || $address === "" || $phone === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($pass) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (user_find_by_email($conn, $email)) {
        $error = "That email is already registered.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        if (user_create($conn, $name, $email, $hash, $role, $address, $phone)) {
            set_flash("success", "Registration successful. Please log in.");
            redirect("login");
        } else {
            $error = "Could not create the account. Try again.";
        }
    }
}

if ($page === "login" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]    ?? "");
    $pass  = $_POST["password"]      ?? "";

    if ($email === "" || $pass === "") {
        $error = "Email and password are required.";
    } else {
        $user = user_find_by_email($conn, $email);
        if (!$user || !password_verify($pass, $user["password_hash"])) {
            $error = "Invalid email or password.";
        } else {
            $_SESSION["user_id"] = (int)$user["id"];
            $_SESSION["name"]    = $user["name"];
            $_SESSION["role"]    = $user["role"];

            if (!empty($_POST["remember"])) {
                $token = bin2hex(random_bytes(32));
                user_set_remember_token($conn, $user["id"], $token);
                setcookie("remember_token", $token, time() + 60 * 60 * 24 * 30, "/");
            }
            set_flash("success", "Welcome back, " . $user["name"] . "!");
            redirect($user["role"] === "admin" ? "admin" : "home");
        }
    }
}

if ($page === "register") {
    require __DIR__ . "/../views/register.php";
} else {
    require __DIR__ . "/../views/login.php";
}
