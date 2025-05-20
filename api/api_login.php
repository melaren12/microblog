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

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'errorMsg' => 'Username and password are required']);
    exit;
}

$userManager = UsersManager::getInstance();
$user = $userManager->login($username, $password);

if ($user !== null) {
    $token = ApiHelper::generateToken($user->getId());
    LogHelper::getInstance()->createInfoLog('api_login.php info: ' . $username . ' login');
    echo json_encode([
        'success' => true,
        'token' => $token,
        'user' => [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastName' => $user->getLastName(),
            'avatar' => $user->getAvatar(),
        ]
    ]);
} else {
    LogHelper::getInstance()->createErrorLog('api_login.php error: Incorrect username or password');
    echo json_encode(['success' => false, 'errorMsg' => 'Incorrect username or password']);
}