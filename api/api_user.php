<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\managers\users\UsersManager;
use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header('Content-Type: application/json');

$userManager = UsersManager::getInstance();

if (!isset($_SESSION['user_id'])) {
    LogHelper::getInstance()->createErrorLog('User ID not found in session');
    echo json_encode(['success' => false, 'errorMsg' => 'User ID not found in session']);
    exit;
}

$user = $userManager->getUserById($_SESSION['user_id']);

if (!$user || !$user->getId()){
    LogHelper::getInstance()->createErrorLog('User not found');
    echo json_encode(['success' => false, 'errorMsg' => 'User not found']);
    exit;
}

echo json_encode([
    'success' => true,
    'user' => [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'avatar' => $user->getAvatar(),
    ]]);