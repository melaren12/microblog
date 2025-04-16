<?php

use App\managers\photos\PhotosManager;
use App\managers\users\UsersManager;
use App\util\LogHelper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';
global $pdo;
$userId = $_SESSION['user_id'];
$photosManager = PhotosManager::getInstance();
$userManager = UsersManager::getInstance();

if (!isset($_SESSION['user_id'])) {
    LogHelper::getInstance()->createErrorLog('ChangeProfile error:' .'Cant find User ID.');
    die("Error: User is not authorized.");
}

if (!$user = $userManager->getUserById($userId)) {
    LogHelper::getInstance()->createErrorLog('ChangeProfile error:' . 'Cant find user by Id ' . $userId);
    die("User is not found");
}

$photos = $photosManager->getUserArchivedPhotos($userId);

$pageTitle = "Microblog";
$extraCss = "archive";
$extraJs = "archive";
$contentTemplate = "../src/templates/archive.php";
$type = "module";
include "../src/templates/layout.php";