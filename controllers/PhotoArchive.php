<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';
global $pdo;



$pageTitle = "Microblog - Change Profile";
$extraCss = "photoArchive";
$contentTemplate = "../src/templates/photoArchive.php";
include "../src/templates/layout.php";