<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';
global $pdo;

use App\managers\users\UsersManager;
use App\util\LogHelper;

$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $avatar_name = 'default.png';

    try {
        $userManager = UsersManager::getInstance();
        $user = $userManager->register($username, $password, $firstname, $lastname, $avatar_name);

        $_SESSION['success_message'] = "Registration successful! Please log in.";

        LogHelper::getInstance()->createInfoLog('register.php error: ' . $username . ' Registration successful! Please log in.');
        header("Location: login.php");
        exit;
    } catch (InvalidArgumentException|RuntimeException $e) {
        $output = $e->getMessage();
    } catch (Exception $e) {
        $output = "An unexpected error occurred: " . $e->getMessage();
        LogHelper::getInstance()->createErrorLog('register.php error: ' . $username . ' Registration failed! ' . $e->getMessage());
    }
}

$page_title = "Microblog - Register";
$extra_css = "auth";
$content_template = "src/templates/register.php";
include "src/templates/layout.php";