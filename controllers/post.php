<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use App\managers\posts\PostManager;
use App\util\LogHelper;

require_once '../init.php';
global $pdo;

$content = trim($_POST['content']);
$postManager = PostManager::getInstance();

$userId = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'], $content)) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['content']) && !empty(trim($_POST['content']))) {
    try {
        $post = $postManager->create($userId, $content);
        LogHelper::getInstance()->createInfoLog('Create Post Info: ' . 'Post created successfully!');
    }catch (Throwable $e) {
        LogHelper::getInstance()->createErrorLog('Create Post Info: ' . 'Error:' . $e->getMessage());
    }
}

header("Location: profile.php");

exit;