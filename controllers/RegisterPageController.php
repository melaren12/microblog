<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;
use App\managers\users\UsersManager;
use App\util\LogHelper;
use Exception;
use InvalidArgumentException;
use RuntimeException;

#[RouteAttribute(uri: '/controllers/register-page')]

class RegisterPageController
{
    public function index()
    {
        $output = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $avatarName = 'default.png';

            try {
                $userManager = UsersManager::getInstance();
                $user = $userManager->register($username, $password, $firstname, $lastname, $avatarName);

                $_SESSION['success_message'] = "Registration successful! Please log in.";

                LogHelper::getInstance()->createInfoLog('registerPage.php error: ' . $username . ' Registration successful! Please log in.');
                header("Location: login-page");
                exit;
            } catch (InvalidArgumentException|RuntimeException $e) {
                $output = $e->getMessage();
            } catch (Exception $e) {
                $output = "An unexpected error occurred: " . $e->getMessage();
                LogHelper::getInstance()->createErrorLog('registerPage.php error: ' . $username . ' Registration failed! ' . $e->getMessage());
            }
        }

        $pageTitle = "Microblog - Register";
        $extraCss = "authPage";
        $contentTemplate = __DIR__ . "/../src/templates/registerPage.php";
        include __DIR__ . "/../src/templates/layout.php";
    }

    public function pathInfo() {
        return "/controllers/register-page";
    }
}