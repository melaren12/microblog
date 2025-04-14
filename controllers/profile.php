<?php
global $pdo;
require_once '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';

use App\managers\users\UsersManager;
use App\managers\posts\PostManager;
use App\util\LogHelper;

$userManager = UsersManager::getInstance();

if (!$_SESSION['user_id']) {
    LogHelper::getInstance()->createErrorLog('User ID not found!');
    header('location: login.php');
}

$user = $userManager->getUserById($_SESSION['user_id']);

$postManager = PostManager::getInstance();
$posts = $postManager->getAllPosts();

if (!$user || !$user->getId()) {
    LogHelper::getInstance()->createErrorLog('User not found!');
    header(header: "Location: login.php?error=user_not_found");
    exit;
}

$pageTitle = "Microblog";
$extraCss = "profile";
$contentTemplate = "../src/templates/profile.php";
include "../src/templates/layout.php";