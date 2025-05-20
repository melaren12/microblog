<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;
use App\managers\users\UsersManager;
use App\util\LogHelper;

#[RouteAttribute(uri: '/controllers/login-page')]

class LoginPageController
{
    public function index()
    {
        require_once __DIR__ . '/../smarty_config.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $usersManager = UsersManager::getInstance();

            $user = $usersManager->login($username, $password);

            if ($user !== null) {
                $_SESSION['user_id'] = $user->getId();
                LogHelper::getInstance()->createInfoLog('loginPage.php info: ' . $username . ' login');
                $redirectHelper = new ProfilePageController();
                $url = $redirectHelper->pathInfo();
                header('Location:' . $url);
                exit;
            } else {
                LogHelper::getInstance()->createErrorLog('loginPage.php error: ' . 'Incorrect username or password.');
                $output = 'Incorrect username or password.';
            }
        }

        $pageTitle = "Microblog";
        $extraCss = "authPage";
        $contentTemplate = "loginPage.tpl";

        $smarty->assign('pageTitle', $pageTitle);
        $smarty->assign('extraCss', $extraCss);
        $smarty->assign('contentTemplate', $contentTemplate);

        $smarty->display('layout.tpl');
    }

    public function pathInfo() {
        return "/controllers/login-page";
    }
}