<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'init.php';
global $pdo;

use App\managers\photos\PhotosManager;
use App\managers\users\UsersManager;

$userManager = UsersManager::getInstance();
$photosManager = PhotosManager::getInstance();

if (!isset($_SESSION['user_id'])) {
    die("Error: User is not authorized.");
}

$user_id = $_SESSION['user_id'];

if (!$user = $userManager->getUserById($user_id)) {
    $errorInfo = $stmt->errorInfo();
    die("Error retrieving user data: " . $errorInfo[2]);
}

$photos = $photosManager->getUserPhotos($user_id);
$output = '';
$user = $userManager->getUserById($user_id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "public/uploads/avatars/";
            $userManager->updateAvatar($user, $_FILES['avatar'], $target_dir, $user->getAvatar());
            header("Location: ChangeProfile.php");
            exit;
        }

        if (!empty($_FILES['photo_path']['name'])) {
            $target_dir = "public/uploads/Photos/";
            $photosManager->uploadPhoto($user_id, $_FILES['photo_path'], $target_dir);
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