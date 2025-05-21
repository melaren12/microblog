<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\managers\users\UsersManager;
// use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header('Content-Type: application/json');

$userManager = UsersManager::getInstance();

$userId = null;

// Приоритет: GET, затем POST, затем SESSION
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $userId = $_GET['user_id'];
} elseif (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $userId = $_POST['user_id'];
} elseif (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}


if (!$userId) {
    echo json_encode(['success' => false, 'errorMsg' => 'User ID not provided or found.']);
    exit;
}

$user = $userManager->getUserById($userId);

if (!$user || !$user->getId()) {
    echo json_encode(['success' => false, 'errorMsg' => 'User not found or invalid user data.']);
    exit;
}

echo json_encode([
    'success' => true,
    'user' => [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'avatar' => $user->getAvatar(),
        'lastName' => $user->getLastName(),
    ]
]);