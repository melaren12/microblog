<?php

use App\managers\posts\PostManager;
use App\util\LogHelper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../init.php';
require_once 'api_helper.php';

ApiHelper::setCorsHeaders();
header('Content-Type: application/json');

$token = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$userId = ApiHelper::verifyToken($token);

if (!$userId) {
    echo json_encode(['success' => false, 'errorMsg' => 'Invalid or expired token']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$content = trim($data['content'] ?? '');

if (empty($content)) {
    echo json_encode(['success' => false, 'errorMsg' => 'Content is required']);
    exit;
}

try {
    $postManager = PostManager::getInstance();
    $post = $postManager->create($userId, $content); // Assuming createPost method exists
    LogHelper::getInstance()->createInfoLog('api_post_create.php info: Post created by user ' . $userId);
    echo json_encode(['success' => true, 'message' => 'Post created successfully']);
} catch (Exception $e) {
    LogHelper::getInstance()->createErrorLog('api_post_create.php error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'errorMsg' => 'Failed to create post']);
}