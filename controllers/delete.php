<?php
use App\managers\photos\PhotosManager;
use App\managers\posts\PostManager;

require '../init.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$user_id = $_SESSION["user_id"] ?? null;
$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$type = $_POST['type'] ?? null;

$photoManager = PhotosManager::getInstance();
$postManager = PostManager::getInstance();

if (!isset($user_id)) {
    echo json_encode(['success' => false, 'error' => 'User not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!$id || !$type) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID or type']);
    exit;
}

if ($type === 'photo') {
    try {
        $photoManager->deletePhoto($user_id, $id);
        echo json_encode(['success' => true]);
        exit;
    } catch (RuntimeException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
} elseif ($type === 'post') {
    $post = $postManager->getPostsByUser($user_id);
    if (!$post) {
        echo json_encode(['success' => false, 'error' => 'Post not found or not authorized']);
        exit;
    }

    try {
        $postManager->deletePost($user_id, $id);
        echo json_encode(['success' => true]);
        exit;
    } catch (RuntimeException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid type specified']);
    exit;
}