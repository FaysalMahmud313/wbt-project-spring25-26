<?php

function cart_count($conn) {
    if (!is_logged_in()) return 0;
    $uid  = current_user_id();
    $stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(quantity),0) AS c FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (int)$row["c"];
}

function current_avatar($conn) {
    if (!is_logged_in()) return "";
    $uid  = current_user_id();
    $stmt = mysqli_prepare($conn, "SELECT profile_picture FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row && !empty($row["profile_picture"]) ? $row["profile_picture"] : "";
}

function render_header($title = "Online Medicine Shop") {
    global $conn;
    $role    = current_role();
    $flash   = get_flash();
    $avatar  = current_avatar($conn);
    $current = $_GET["page"] ?? "home";
    $nav     = fn($p) => $current === $p ? " active" : "";
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?> &mdash; Online Medicine Shop</title>
    <link rel="stylesheet" href="<?= asset('task1_23540323/assets/css/style.css') ?>">
    <script>
        window.IS_CUSTOMER = <?= current_role() === 'customer' ? 'true' : 'false' ?>;
        window.BASE_URL    = "<?= BASE_URL ?>";
    </script>
</head>
<body>
<nav class="navbar">
    <a class="brand" href="<?= url('home') ?>">
        <span class="brand-logo">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M14 3h-4v7H3v4h7v7h4v-7h7v-4h-7z"></path></svg>
        </span>
        MediShop
    </a>
    <button class="nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')">&#9776;</button>
    <div class="nav-links">
        <a class="nav-item<?= $nav('home') ?>" href="<?= url('home') ?>">Home</a>

        <?php if ($role === "admin"): ?>
            <a class="nav-item<?= $nav('admin') ?>"            href="<?= url('admin') ?>">Dashboard</a>
            <a class="nav-item<?= $nav('admin_categories') ?>" href="<?= url('admin_categories') ?>">Categories</a>
            <a class="nav-item<?= $nav('admin_medicines') ?>"  href="<?= url('admin_medicines') ?>">Medicines</a>
            <a class="nav-item<?= $nav('admin_customers') ?>"  href="<?= url('admin_customers') ?>">Customers</a>
            <a class="nav-item<?= $nav('admin_users') ?>"      href="<?= url('admin_users') ?>">Admins</a>
            <a class="nav-item<?= $nav('admin_orders') ?>"     href="<?= url('admin_orders') ?>">Orders</a>
            <a class="nav-item<?= $nav('admin_history') ?>"    href="<?= url('admin_history') ?>">History</a>
        <?php endif; ?>

        <?php if ($role !== "admin"): ?>
            <a class="cart-link" href="<?= url('cart') ?>" title="Cart">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="20" r="1.5"></circle><circle cx="18" cy="20" r="1.5"></circle>
                    <path d="M2 3h3l2.4 12.2a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.6L23 7H6"></path>
                </svg>
                <span id="cart-badge" class="badge"><?= is_logged_in() ? cart_count($conn) : 0 ?></span>
            </a>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <div class="profile-wrap">
                <button type="button" class="profile-btn" onclick="toggleProfileMenu(event)">
                    <?php if ($avatar !== ""): ?>
                        <img class="avatar-sm" src="<?= asset($avatar) ?>" alt="profile">
                    <?php else: ?>
                        <span class="avatar-sm avatar-default">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 4-6 8-6s8 2 8 6v1H4z"></path></svg>
                        </span>
                    <?php endif; ?>
                    <span class="profile-name"><?= h($_SESSION['name']) ?></span>
                    <span class="caret">&#9662;</span>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <div class="profile-menu-head">
                        <b><?= h($_SESSION['name']) ?></b>
                        <span class="muted"><?= h(ucfirst($role)) ?></span>
                    </div>
                    <a href="<?= url('profile') ?>">Edit Profile</a>
                    <?php if ($role === "admin"): ?>
                        <a href="<?= url('admin') ?>">Admin Dashboard</a>
                        <a href="<?= url('admin_orders') ?>">Purchase Requests</a>
                    <?php else: ?>
                        <a href="<?= url('my_orders') ?>">My Orders</a>
                        <a href="<?= url('cart') ?>">My Cart</a>
                    <?php endif; ?>
                    <a href="<?= url('home') ?>">Browse Medicines</a>
                    <a class="danger" href="<?= url('logout') ?>">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= url('login') ?>">Login</a>
            <a class="nav-cta" href="<?= url('register') ?>">Register</a>
        <?php endif; ?>
    </div>
</nav>

<script>
function toggleProfileMenu(e) {
    e.stopPropagation();
    var m = document.getElementById("profileMenu");
    if (m) m.classList.toggle("open");
}
document.addEventListener("click", function(e) {
    var m = document.getElementById("profileMenu");
    if (m && !e.target.closest(".profile-wrap")) m.classList.remove("open");
});
</script>

<main class="container">
    <?php if ($flash): ?>
        <div class="alert <?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
            <?= h($flash['text']) ?>
        </div>
    <?php endif; ?>
<?php
}

function render_footer() { ?>
</main>
<footer class="site-footer">
    <p>Online Medicine Shop &mdash; Web Technologies Group 05 Project</p>
</footer>
</body>
</html>
<?php }
