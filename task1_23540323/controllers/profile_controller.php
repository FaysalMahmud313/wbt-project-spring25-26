<?php
/* ---------------------------------------------------------------------------
 * Task 1 controller: Profile management + change password + picture upload
 * Session-gated (any logged-in user).
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/user_model.php";
require_once __DIR__ . "/../views/layout.php";

require_login();

$message = "";
$error   = "";
$uid     = current_user_id();

/* ---- Update profile details (+ optional new picture) ---- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_profile") {
    $name    = trim($_POST["name"]    ?? "");
    $email   = trim($_POST["email"]   ?? "");
    $address = trim($_POST["address"] ?? "");
    $phone   = trim($_POST["phone"]   ?? "");

    if ($name === "" || $email === "" || $address === "" || $phone === "") {
        $error = "All profile fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Make sure the email is not used by another account
        $other = user_find_by_email($conn, $email);
        if ($other && (int)$other["id"] !== $uid) {
            $error = "That email is already used by another account.";
        } else {
            $picture = null;
            $upErr   = "";
            $saved   = save_uploaded_image("profile_picture", PROFILE_UPLOAD, $upErr);
            if ($saved === false) {
                $error = $upErr;
            } else {
                if ($saved !== "") {
                    $picture = "task1_23540323/uploads/profiles/" . $saved;
                }
                if (user_update_profile($conn, $uid, $name, $email, $address, $phone, $picture)) {
                    $_SESSION["name"] = $name;
                    $message = "Profile updated successfully.";
                } else {
                    $error = "Could not update profile.";
                }
            }
        }
    }
}

/* ---- Change password (requires current password) ---- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "change_password") {
    $current = $_POST["current_password"] ?? "";
    $new     = $_POST["new_password"]     ?? "";

    $user = user_find_by_id($conn, $uid);
    if (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } elseif (!password_verify($current, $user["password_hash"])) {
        $error = "Current password is incorrect.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        if (user_update_password($conn, $uid, $hash)) {
            $message = "Password changed successfully.";
        } else {
            $error = "Could not change password.";
        }
    }
}

$user = user_find_by_id($conn, $uid);
require __DIR__ . "/../views/profile.php";
