<?php

require_once __DIR__ . "/../models/order_model.php";
require_once __DIR__ . "/../views/layout.php";

require_admin();

$stats = t2_dashboard_stats($conn);
require __DIR__ . "/../views/dashboard.php";
