<?php

use App\Attributes\Route;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'init.php';
Route::run();

if (!isset($_SESSION['user_id'])) {
    header('Location: controllers/login-page');
    exit;
}

header("Location: controllers/profile-page");