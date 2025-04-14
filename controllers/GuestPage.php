<?php
require_once '../init.php';

use App\managers\users\UsersManager;
use App\managers\posts\PostManager;
use App\managers\photos\PhotosManager;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$profile_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
if (!$profile_user_id) {
    header("Location: index.php");
    exit;
}

$userManager = UsersManager::getInstance();
$photosManager = PhotosManager::getInstance();
$postManager = PostManager::getInstance();

$profile_user = $userManager->getUserById($profile_user_id);
if (!$profile_user) {
    header("Location: index.php?error=user_not_found");
    exit;
}

$posts = $postManager->getPostsByUser($profile_user_id);
$photos = $photosManager->getUserPhotos($profile_user_id);

$page_title = "Microblog";
$extra_css = "guest";
$content_template = "../src/templates/guestPage.php";
include "../src/templates/layout.php";