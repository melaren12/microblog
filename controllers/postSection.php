<?php
require_once '../vendor/autoload.php';
require_once '../init.php';

use App\managers\posts\PostManager;
use App\util\LogHelper;

header('Content-Type: application/json');

$userId = $_POST['user_id'] ?? $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode([
        'success' => false,
        'error' => 'User is not authorized'
    ]);
    exit;
}

try {
    $content = trim($_POST['content']);
    $postManager = PostManager::getInstance();

    $postManager->create($userId, $content);

    LogHelper::getInstance()->createInfoLog('Create Post Info: Post created successfully');

    echo json_encode([
        'success' => true
    ]);
} catch (Throwable $e) {
    LogHelper::getInstance()->createErrorLog('Create Post Info: Error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => 'Error creating post: ' . $e->getMessage()
    ]);
}
