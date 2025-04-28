<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';

$pageTitle = "Microblog";
$extraCss = "archivePage";
$extraJs = "archivePage";
$contentTemplate = "../src/templates/archivePage.php";
$type = "module";
include "../src/templates/layout.php";