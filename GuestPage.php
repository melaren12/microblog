<?php

use App\managers\photos\PhotosManager;
use App\managers\posts\PostManager;
use App\managers\users\UsersManager;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';
global $pdo;

$profile_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$userManager = UsersManager::getInstance();
$postManager = PostManager::getInstance();
$photosManager = PhotosManager::getInstance();

$profile_user = $userManager->getUserById($profile_user_id);
$posts = $postManager->getPostsByUser($profile_user_id);
$photos = $photosManager->getUserPhotos($profile_user_id);

if (!$profile_user) {
    header("Location: index.php?error=user_not_found");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!$profile_user_id) {
    header("Location: index.php");
    exit;
}

$page_title = "Microblog";
$extra_css = "guest";
$content_template = "src/templates/guestPage.php";
include "src/templates/layout.php";