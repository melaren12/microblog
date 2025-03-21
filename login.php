<?php

require_once 'init.php';
global $pdo;

use App\User;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = User::login($pdo, $username, $password);

    if ($user !== null) {
        $_SESSION['user_id'] = $user->getId();
        header('Location: profile.php');
        exit;
    } else {
        $output = 'Incorrect username or password.';
    }
}

$page_title = "Microblog";
$extra_css = "auth";
$content_template = "src/templates/login.php";
include "src/templates/layout.php";
