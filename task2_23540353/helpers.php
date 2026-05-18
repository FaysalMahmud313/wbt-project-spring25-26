<?php

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
function url($page, $params = []) {
    $query = array_merge(["page" => $page], $params);
    return BASE_URL . "index.php?" . http_build_query($query);
}
function asset($relativePath) {
    return BASE_URL . ltrim($relativePath, "/");
}
function redirect($page, $params = []) {
    header("Location: " . url($page, $params));
    exit;
}
function json_response($data, $httpCode = 200) {
    http_response_code($httpCode);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data);
    exit;
}
function set_flash($type, $text) { $_SESSION["flash"] = ["type" => $type, "text" => $text]; }
function get_flash() {
    if (!empty($_SESSION["flash"])) { $flash = $_SESSION["flash"]; unset($_SESSION["flash"]); return $flash; }
    return null;
}
function save_uploaded_image($fileField, $targetDir, &$error) {
    global $ALLOWED_IMAGE_MIME;
    if (empty($_FILES[$fileField]) || $_FILES[$fileField]["error"] === UPLOAD_ERR_NO_FILE) return "";
    $file = $_FILES[$fileField];
    if ($file["error"] !== UPLOAD_ERR_OK) { $error = "File upload failed."; return false; }
    if ($file["size"] > MAX_UPLOAD_BYTES)  { $error = "Image must be 2 MB or smaller."; return false; }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file["tmp_name"]);
    finfo_close($finfo);
    if (!isset($ALLOWED_IMAGE_MIME[$mime])) { $error = "Only JPEG and PNG images are allowed."; return false; }
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $ext      = $ALLOWED_IMAGE_MIME[$mime];
    $filename = uniqid("img_", true) . "." . $ext;
    if (!move_uploaded_file($file["tmp_name"], $targetDir . "/" . $filename)) {
        $error = "Could not save the uploaded image."; return false;
    }
    return $filename;
}
