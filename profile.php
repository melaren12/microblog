<?php
global $pdo;
require_once 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';

use App\managers\users\UsersManager;
use App\managers\posts\PostManager;

$userManager = UsersManager::getInstance();
$user = $userManager->getUserById($_SESSION['user_id']);

$postManager = PostManager::getInstance();
$posts = $postManager->getAllPosts();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!$user || !$user->getId()) {
    header(header: "Location: login.php?error=user_not_found");
    exit;
}

$page_title = "Microblog";
$extra_css = "profile";
$content_template = "src/templates/profile.php";
include "src/templates/layout.php";