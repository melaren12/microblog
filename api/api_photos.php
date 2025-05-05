<?php

use App\managers\photos\PhotosManager;
use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header("Content-type: application/json");

$postData = json_decode(file_get_contents('php://input'), true);
$userId = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : null);

$archived = isset($_POST['archived']) ? $_POST['archived'] : (isset($_GET['archived']) ? $_GET['archived'] : 'false');
$archived = filter_var($archived, FILTER_VALIDATE_BOOLEAN);

if (!$userId) {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        LogHelper::getInstance()->createErrorLog('User ID not provided and not found in session');
        echo json_encode(['success' => false, 'errorMsg' => 'User ID not provided and not found in session']);
        exit;
    }
}

try {
    $photoManager = PhotosManager::getInstance();
    if ($archived) {
        $photos = $photoManager->getUserArchivedPhotos($userId);
    } else {
        $photos = $photoManager->getUserPhotos($userId);
    }

    $photosData = array_map(function ($photo) {
        return [
            'id' => $photo['id'],
            'user_id' => $photo['user_id'],
            'photo_path' => $photo['photo_path'],
            'caption' => $photo['caption'] ?? '',
            'uploaded_at' => $photo['uploaded_at'] ?? null
        ];
    }, $photos);

    echo json_encode(['success' => true, 'photos' => $photosData]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error uploading photo: ' . $e->getMessage()]);
}