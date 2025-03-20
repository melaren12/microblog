<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$profile_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
if (!$profile_user_id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT name, lastname, avatar FROM users WHERE id = :id");
$stmt->execute(['id' => $profile_user_id]);
$profile_user = $stmt->fetch();

if (!$profile_user) {
    header("Location: index.php?error=user_not_found");
    exit;
}

$stmt = $pdo->prepare("
    SELECT * FROM posts 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC
");
$stmt->execute(['user_id' => $profile_user_id]);
$posts = $stmt->fetchAll();

$page_title = "Guest";
$extra_css = "guest";
$content_template = "src/templates/guestPage.php";
include "src/templates/layout.php";
