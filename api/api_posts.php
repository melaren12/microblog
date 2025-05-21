<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\managers\posts\PostManager;
use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header("Content-type: application/json");

//if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
//    LogHelper::getInstance()->createErrorLog('Access denied to posts: User ID not found in session.');
//    echo json_encode(['success' => false, 'message' => 'Authentication required. User ID not found in session.']);
//    exit;
//}

$currentUserId = 5;
LogHelper::getInstance()->createInfoLog("DEBUG: api_posts.php - User ID from session: " . $currentUserId);


$postManager = PostManager::getInstance();

$posts = [];
try {
    $posts = $postManager->getAllPosts();
    LogHelper::getInstance()->createInfoLog('DEBUG: api_posts.php - Successfully fetched ' . count($posts) . ' posts.');

} catch (Exception $e) {
    LogHelper::getInstance()->createErrorLog('ERROR: api_posts.php - Failed to get posts: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to retrieve posts due to a server error.']);
    exit;
}


$postsData = [];
if (!empty($posts)) {
    $postsData = array_map(function ($post) {
        return [
            'id' => $post->getId(),
            'user_id' => $post->getUserId(),
            'user_name' => $post->getUserName(),
            'user_lastname' => $post->getUserLastname(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            // 'avatar' => $post->getUserAvatar(),
        ];
    }, $posts);
} else {
    LogHelper::getInstance()->createErrorLog('DEBUG: api_posts.php - No posts found.');
}


echo json_encode([
    'success' => true,
    'posts' => $postsData,
]);