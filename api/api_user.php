<?php

use App\managers\users\UsersManager;
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

$userManager = UsersManager::getInstance();
$user = $userManager->getUserById($userId);

if (!$user || !$user->getId()) {
    LogHelper::getInstance()->createErrorLog('api_user.php error: User not found');
    echo json_encode(['success' => false, 'errorMsg' => 'User not found']);
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