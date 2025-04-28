<?php
ini_set('display_errors', value: 1);
ini_set('display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);

use App\managers\users\UsersManager;
use App\util\LogHelper;

require_once '../vendor/autoload.php';
require_once '../init.php';

header(header: 'Content-Type: application/json');

$userManager = UsersManager::getInstance();


$userId = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : null);


if (!$userId) {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        LogHelper::getInstance()->createErrorLog('User ID not provided and not found in session');
        echo json_encode(['success' => false, 'errorMsg' => 'User ID not provided and not found in session']);
        exit;
    }
}

$user = $userManager->getUserById($userId);

if (!$user || !$user->getId()) {
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
        'lastName' => $user->getLastName(),
    ]
]);