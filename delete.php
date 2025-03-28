<?php

use App\managers\photos\PhotosManager;
require 'init.php';

header('Content-Type: application/json');

$user_id = $_SESSION["user_id"];
$photo_id = isset($_POST['id']) ? (int)$_POST['id'] : null;

$photoManager = PhotosManager::getInstance();
$photo = $photoManager->getPhotosById($photo_id, $user_id);

if (!isset($user_id)) {
    echo json_encode(['success' => false, 'error' => 'User not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!$photo_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid photo ID']);
    exit;
}

if (!$photo) {
    echo json_encode(['success' => false, 'error' => 'Photo not found or not authorized']);
    exit;
}

if (file_exists($photo['photo_path'])) {
    if (!unlink($photo['photo_path'])) {
        echo json_encode(['success' => false, 'error' => 'Could not delete file']);
        exit;
    }
}

try {
    $photoManager->deletePhoto($user_id, $photo_id);
    echo json_encode(['success' => true]);
    exit;
} catch (RuntimeException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
