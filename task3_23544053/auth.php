<?php

if (session_status() === PHP_SESSION_NONE) session_start();

function attempt_remember_login($conn) {
    if (!empty($_SESSION["user_id"]) || empty($_COOKIE["remember_token"])) return;
    $token = $_COOKIE["remember_token"];
    $stmt  = mysqli_prepare($conn, "SELECT id, name, role FROM users WHERE remember_token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($user) { $_SESSION["user_id"] = (int)$user["id"]; $_SESSION["name"] = $user["name"]; $_SESSION["role"] = $user["role"]; }
}
function is_logged_in()    { return !empty($_SESSION["user_id"]); }
function current_user_id() { return $_SESSION["user_id"] ?? 0; }
function current_role()    { return $_SESSION["role"] ?? ""; }
function require_login()   { if (!is_logged_in()) { set_flash("error", "Please log in to continue."); redirect("login"); } }
function require_admin()   { require_login(); if (current_role() !== "admin")    { set_flash("error", "Admins only."); redirect("home"); } }
function require_customer(){ require_login(); if (current_role() !== "customer") { set_flash("error", "Customer account required."); redirect("home"); } }
