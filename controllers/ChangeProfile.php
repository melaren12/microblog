<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';
global $pdo;

use App\managers\photos\PhotosManager;
use App\managers\users\UsersManager;
use App\util\LogHelper;

$userManager = UsersManager::getInstance();
$photosManager = PhotosManager::getInstance();

$userId = $_SESSION['user_id'];

$photos = $photosManager->getUserPhotos($userId);

$user = $userManager->getUserById($userId);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        if (!empty($_FILES['avatar']['name'])) {
            $targetDir = "../public/uploads/avatars/";
            $userManager->updateAvatar($user, $_FILES['avatar'], $targetDir, $user->getAvatar());
            header("Location: ChangeProfile.php");
            exit;
        }

        if (!empty($_FILES['photo_path']['name'])) {
            $targetDir = "../public/uploads/Photos/";
            $photosManager->uploadPhoto($_FILES['photo_path'], $targetDir, $userId);
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

$pageTitle = "Microblog - Change Profile";
$extraCss = "changeProfile";
$extraJs = "changeProfile";
$type = 'module';
$contentTemplate = "../src/templates/changeProfile.php";
include "../src/templates/layout.php";