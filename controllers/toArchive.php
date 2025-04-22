<?php

use App\managers\photos\PhotosManager;

require '../init.php';

header('Content-Type: application/json');

$userId = $_SESSION["user_id"] ?? null;
$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$type = $_POST['type'] ?? null;

$photoManager = PhotosManager::getInstance();

if (!isset($userId)) {
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
        $photoManager->moveToArchive($userId, $id);
        echo json_encode(['success' => true]);
        exit;
    } catch (RuntimeException $e) {
        var_dump($e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid type specified']);
    exit;
}