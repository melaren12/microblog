<?php
global $pdo;
require_once 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'init.php';

use App\Post;
use App\User;



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = new User($pdo, $_SESSION['user_id']);
if (!$user->getId()) {
    header("Location: login.php?error=user_not_found");
    exit;
}

$postManager = new Post($pdo);
$posts = $postManager->getAllPosts();

$page_title = "Microblog";
$extra_css = "profile";
$content_template = "src/templates/profile.php";
include "src/templates/layout.php";

