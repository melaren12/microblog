<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/profile-page')]
class ProfilePageController
{
    public function index()
    {
        require_once __DIR__ . '/../smarty_config.php';

        $redirectHelper = new ChangeProfileController();
        $url = $redirectHelper->pathInfo();

        $pageTitle = "Microblog";
        $extraCss = "profilePage";
        $extraJs = "profilePage";
        $type = "module";
        $contentTemplate = "profilePage.tpl";

        $smarty->assign('pageTitle', $pageTitle);
        $smarty->assign('extraCss', $extraCss);
        $smarty->assign('extraJs', $extraJs);
        $smarty->assign('type', $type);
        $smarty->assign('contentTemplate', $contentTemplate);

        $smarty->display('layout.tpl');
    }

    public function pathInfo() {
        return "/controllers/profile-page";
    }

}
