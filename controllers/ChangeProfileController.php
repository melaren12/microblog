<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;
use App\managers\photos\PhotosManager;
use App\managers\users\UsersManager;
use App\util\LogHelper;

#[RouteAttribute(uri: '/controllers/change-profile')]
class ChangeProfileController
{
        public function index()
    {
        $userManager = UsersManager::getInstance();
        $photosManager = PhotosManager::getInstance();

        $userId = $_SESSION['user_id'];

        $user = $userManager->getUserById($userId);

        $output = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                if (!empty($_FILES['avatar']['name'])) {
                    $targetDir = __DIR__ . "/../public/uploads/avatars/";
                    $userManager->updateAvatar($user, $_FILES['avatar'], $targetDir, $user->getAvatar());
                    header("Location: /controllers/change-profile");
                    exit;
                }

                if (!empty($_FILES['photo_path']['name'])) {
                    $targetDir = __DIR__ . "/../public/uploads/Photos/";
                    $photosManager->uploadPhoto($_FILES['photo_path'], $targetDir, $userId);
                    header("Location: /controllers/change-profile");
                    exit;
                }
            } catch (\RuntimeException $e) {
                $output = $e->getMessage();
            } catch (\Exception $e) {
                LogHelper::getInstance()->createErrorLog('ChangeProfile error: Cant create user: ' . $e->getMessage());
                $output = "An unexpected error occurred: ";
            }
        }

        $pageTitle = "Microblog - Change Profile";
        $extraCss = "changeProfile";
        $extraJs = "changeProfile";
        $type = 'module';
        $contentTemplate = __DIR__ . "/../src/templates/changeProfile.php";

        include __DIR__ . "/../src/templates/layout.php";
    }

    public function pathInfo() {
        return "/controllers/change-profile";
    }
}