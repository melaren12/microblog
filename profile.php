<?php
global $pdo;
require_once 'init.php';
require_once 'classes/User.php';
require_once 'classes/Posts.php';

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

require 'templates/profile.php';

