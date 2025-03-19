<?php

require_once 'init.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    die("Ошибка: пользователь не авторизован.");
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES['avatar']['name'])) {
    $target_dir = "uploads/";
    $avatar_name = time() . "_" . basename($_FILES["avatar"]["name"]);
    $target_file = $target_dir . $avatar_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Ошибка: Только JPG, JPEG, PNG & GIF файлы разрешены.");
    }

    if ($user['avatar'] != "default.png" && file_exists("uploads/" . $user['avatar'])) {
        unlink("uploads/" . $user['avatar']);
    }

    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        die("Ошибка загрузки файла.");
    }

    $stmt = $pdo->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
    $stmt->execute(['avatar' => $avatar_name, 'id' => $user_id]);

    header("Location: profile.php");
    exit;
}

require 'templates/changeProfile.php';
