<?php
require_once '../init.php';

use App\managers\users\UsersManager;
use App\managers\posts\PostManager;
use App\managers\photos\PhotosManager;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$profileUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
if (!$profileUserId) {
    header("Location: index.php");
    exit;
}

$userManager = UsersManager::getInstance();
$photosManager = PhotosManager::getInstance();
$postManager = PostManager::getInstance();

$profileUser = $userManager->getUserById($profileUserId);
if (!$profileUser) {
    header("Location: index.php?error=user_not_found");
    exit;
}

$posts = $postManager->getPostsByUser($profileUserId);
$photos = $photosManager->getUserPhotos($profileUserId);

$pageTitle = "Microblog";
$extraCss = "guest";
$contentTemplate = "../src/templates/guestPage.php";
include "../src/templates/layout.php";