<?php

require_once 'init.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['content']) && !empty(trim($_POST['content']))) {
    $content = trim($_POST['content']);
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->execute([$user_id, $content]);
}


header("Location: profile.php");

exit;



