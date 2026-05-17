<?php
/* ---------------------------------------------------------------------------
 * Task 2 controller: Admin dashboard (stats). Admin gate applied.
 * ------------------------------------------------------------------------- */

require_once __DIR__ . "/../models/order_model.php";
require_once BASE_PATH . "/shared/layout.php";

require_admin();

$stats = t2_dashboard_stats($conn);
require __DIR__ . "/../views/dashboard.php";
