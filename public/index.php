<?php
/* START UP SETTINGS FOR ERRORS AND SESSIONS START */
require_once "../vendor/autoload.php";


ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();


/*
 * METHODS FOR ACTIVITY USERS
 */
\App\Helpers::storeActivity();
\App\Helpers::storeReview();

include_once '../routes/web.php';

/*
 * INITIAL ROUTE
 */

\App\Logic\Route::run();



