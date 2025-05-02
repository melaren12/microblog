<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/profile-page')]
class ProfilePageController
{
    public function index()
    {
        $redirectHelper = new ChangeProfileController();
        $url = $redirectHelper->pathInfo();

        $pageTitle = "MicroBlog";
        $extraCss = "profilePage";
        $extraJs = "profilePage";
        $type = "module";
        $contentTemplate =  __DIR__ . "/../src/templates/profilePage.php";
        include __DIR__ . "/../src/templates/layout.php";
    }

    public function pathInfo() {
        return "/controllers/profile-page";
    }

}
