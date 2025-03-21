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

use App\User;
use App\Photos;

$userManager = new User($pdo, $user_id);
$photosManager = new Photos($pdo, $user_id);

$stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = :id");
if (!$stmt->execute(['id' => $user_id])) {
    $errorInfo = $stmt->errorInfo();
    die("Error retrieving user data: " . $errorInfo[2]);
}
$user = $stmt->fetch();

$photos = $photosManager->getUserPhotos();
$output = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "public/uploads/avatars/";
            $userManager->updateAvatar($_FILES['avatar'], $target_dir, $user['avatar']);
            header("Location: ChangeProfile.php");
            exit;
        }

        if (!empty($_FILES['photo_path']['name'])) {
            $target_dir = "public/uploads/Photos/";
            $photosManager->uploadPhoto($_FILES['photo_path'], $target_dir);
            header("Location: ChangeProfile.php");
            exit;
        }
    } catch (RuntimeException $e) {
        $output = $e->getMessage();
    } catch (Exception $e) {
        $output = "An unexpected error occurred: " . $e->getMessage();
        error_log("ChangeProfile error: " . $e->getMessage());
    }
}

$page_title = "Microblog - Change Profile";
$extra_css = "changeProfile";
$extra_js = "changeProfile";
$content_template = "src/templates/changeProfile.php";
include "src/templates/layout.php";