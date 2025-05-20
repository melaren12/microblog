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
    LogHelper::getInstance()->createErrorLog('api_posts.php error: Invalid or expired token');
    echo json_encode(['success' => false, 'errorMsg' => 'Invalid or expired token']);
    exit;
}

$postManager = PostManager::getInstance();
$posts = $postManager->getAllPosts();

$postsData = array_map(function ($post) {
    return [
        'id' => $post->getId(),
        'user_id' => $post->getUserId(),
        'user_name' => $post->getUserName(),
        'user_lastname' => $post->getUserLastname(),
        'content' => $post->getContent(),
        'created_at' => $post->getCreatedAt(),
    ];
}, $posts);

echo json_encode([
    'success' => true,
    'posts' => $postsData,
]);