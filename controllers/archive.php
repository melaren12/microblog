<?php

use App\managers\photos\PhotosManager;
use App\managers\users\UsersManager;
use App\util\LogHelper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';

$pageTitle = "Microblog";
$extraCss = "archive";
$extraJs = "archive";
$contentTemplate = "../src/templates/archive.php";
$type = "module";
include "../src/templates/layout.php";