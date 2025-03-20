<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    die("Error: User is not authorized.");
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = :id");
if (!$stmt->execute(['id' => $user_id])) {
    $errorInfo = $stmt->errorInfo();
    die("Error retrieving user data: " . $errorInfo[2]);
}
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT photo_path, id FROM user_photos WHERE user_id = :user_id ORDER BY uploaded_at DESC");
if (!$stmt->execute(['user_id' => $user_id])) {
    $errorInfo = $stmt->errorInfo();
    die("Error receiving photo: " . $errorInfo[2]);
}
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_FILES['avatar']['name'])) {
        $target_dir = "uploads/";
        $avatar_name = time() . "_" . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $avatar_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if ($user['avatar'] != "default.png" && file_exists("uploads/" . $user['avatar'])) {
            unlink("uploads/" . $user['avatar']);
        }

        if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            die("Error loading file.");
        }

        $stmt = $pdo->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
        if (!$stmt->execute(['avatar' => $avatar_name, 'id' => $user_id])) {
            $errorInfo = $stmt->errorInfo();
            die("Error updating avatar: " . $errorInfo[2]);
        }

        header("Location: profile.php");
        exit;
    }

    if (!empty($_FILES['photo_path']['name'])) {
        $target_dir = "uploads/Photos/";

        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("Error: Could not create directory $target_dir. Check permissions.");
            }
        }

        if (!is_writable($target_dir)) {
            die("Error: Directory $target_dir is not writable. Check permissions.");
        }

        $photo_name = time() . "_" . basename($_FILES["photo_path"]["name"]);
        $target_file = $target_dir . $photo_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (!move_uploaded_file($_FILES["photo_path"]["tmp_name"], $target_file)) {
            die("Error loading file: " . error_get_last()['message']);
        }

        $stmt = $pdo->prepare("INSERT INTO user_photos (user_id, photo_path) VALUES (:user_id, :photo_path)");
        if (!$stmt->execute(['user_id' => $user_id, 'photo_path' => $target_file])) {
            $errorInfo = $stmt->errorInfo();
            die("Error adding photo to database: " . $errorInfo[2]);
        }

        header("Location: ChangeProfile.php");
        exit;
    }
}

$page_title = "Change Profile";
$extra_css = "changeProfile";
$content_template = "src/templates/changeProfile.php";
include "src/templates/layout.php";
