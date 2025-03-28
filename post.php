<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use App\managers\posts\PostManager;

require_once 'init.php';
global $pdo;

$content = trim($_POST['content']);
$postManager = PostManager::getInstance();

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'], $content)) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['content']) && !empty(trim($_POST['content']))) {
    $post = $postManager->create($user_id, $content);
}

header("Location: profile.php");

exit;



