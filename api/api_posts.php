<?php

use App\managers\posts\PostManager;
use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header("Content-type: application/json");

if (!isset($_SESSION['user_id'])) {
    LogHelper::getInstance()->createErrorLog('User Id not found');
    echo json_encode(['success' => false, 'message' => 'User Id not found']);
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