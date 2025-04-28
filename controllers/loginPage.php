<?php

require_once '../init.php';
global $pdo;

use App\managers\users\UsersManager;
use App\util\LogHelper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $usersManager = new UsersManager();
    $user = $usersManager->login($username, $password);

    if ($user !== null) {
        $_SESSION['user_id'] = $user->getId();
        LogHelper::getInstance()->createInfoLog('loginPage.php info: ' . $username . ' login');
        header('Location: profilePage.php');
        exit;
    } else {
        LogHelper::getInstance()->createErrorLog('loginPage.php error: ' . 'Incorrect username or password.');
        $output = 'Incorrect username or password.';
    }
}

$pageTitle = "Microblog";
$extraCss = "authPage";
$contentTemplate = "../src/templates/loginPage.php";
include "../src/templates/layout.php";