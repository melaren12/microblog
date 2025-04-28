<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';
global $pdo;

use App\managers\users\UsersManager;
use App\util\LogHelper;

$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $avatarName = 'default.png';

    try {
        $userManager = UsersManager::getInstance();
        $user = $userManager->register($username, $password, $firstname, $lastname, $avatarName);

        $_SESSION['success_message'] = "Registration successful! Please log in.";

        LogHelper::getInstance()->createInfoLog('registerPage.php error: ' . $username . ' Registration successful! Please log in.');
        header("Location: loginPage.php");
        exit;
    } catch (InvalidArgumentException|RuntimeException $e) {
        $output = $e->getMessage();
    } catch (Exception $e) {
        $output = "An unexpected error occurred: " . $e->getMessage();
        LogHelper::getInstance()->createErrorLog('registerPage.php error: ' . $username . ' Registration failed! ' . $e->getMessage());
    }
}

$pageTitle = "Microblog - Register";
$extraCss = "authPage";
$contentTemplate = "../src/templates/registerPage.php";
include "../src/templates/layout.php";