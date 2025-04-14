<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';
global $pdo;

use App\managers\photos\PhotosManager;
use App\managers\posts\PostManager;
use App\managers\users\UsersManager;
use App\util\LogHelper;

$userManager = UsersManager::getInstance();
$photosManager = PhotosManager::getInstance();
$postsManager = PostManager::getInstance();

if (!isset($_SESSION['user_id'])) {
    LogHelper::getInstance()->createErrorLog('ChangeProfile error:' .'Cant find User ID.');
    die("Error: User is not authorized.");
}

$user_id = $_SESSION['user_id'];

if (!$user = $userManager->getUserById($user_id)) {
    LogHelper::getInstance()->createErrorLog('ChangeProfile error:' . 'Cant find user by Id ' . $user_id);
    die("User is not found");
}

$photos = $photosManager->getUserPhotos($user_id);
$output = '';
$user = $userManager->getUserById($user_id);
$posts = $postsManager->getPostsByUser($user_id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "../public/uploads/avatars/";
            $userManager->updateAvatar($user, $_FILES['avatar'], $target_dir, $user->getAvatar());
            header("Location: ChangeProfile.php");
            exit;
        }

        if (!empty($_FILES['photo_path']['name'])) {
            $target_dir = "../public/uploads/Photos/";
            $photosManager->uploadPhoto($_FILES['photo_path'], $target_dir, $user_id);
            header("Location: ChangeProfile.php");
            exit;
        }
    } catch (RuntimeException $e) {
        $output = $e->getMessage();
    } catch (Exception $e) {
        LogHelper::getInstance()->createErrorLog('ChangeProfile error:' . 'Cant create user: ' . $e->getMessage());
        $output = "An unexpected error occurred: ";
    }
}

$page_title = "Microblog - Change Profile";
$extra_css = "changeProfile";
$extra_js = "changeProfile";
$content_template = "../src/templates/changeProfile.php";
include "../src/templates/layout.php";