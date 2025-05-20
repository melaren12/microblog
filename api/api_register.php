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
$firstname = trim($data['firstname'] ?? '');
$lastname = trim($data['lastname'] ?? '');
$avatarName = 'default.png';

if (empty($username) || empty($password) || empty($firstname) || empty($lastname)) {
    echo json_encode(['success' => false, 'errorMsg' => 'All fields are required']);
    exit;
}

try {
    $userManager = UsersManager::getInstance();
    $user = $userManager->register($username, $password, $firstname, $lastname, $avatarName);
    LogHelper::getInstance()->createInfoLog('api_register.php info: ' . $username . ' Registration successful');
    echo json_encode(['success' => true, 'message' => 'Registration successful! Please log in.']);
} catch (InvalidArgumentException|RuntimeException $e) {
    echo json_encode(['success' => false, 'errorMsg' => $e->getMessage()]);
} catch (Exception $e) {
    LogHelper::getInstance()->createErrorLog('api_register.php error: ' . $username . ' Registration failed! ' . $e->getMessage());
    echo json_encode(['success' => false, 'errorMsg' => 'An unexpected error occurred']);
}