<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/guest-page')]

class GuestPageController
{
    public function index()
    {
        require_once __DIR__ . '/../smarty_config.php';

        $pageTitle = "Microblog";
        $extraCss = "guestPage";
        $contentTemplate = "guestPage.tpl";
        $extraJs = "guestPage";
        $type = "module";

        $smarty->assign('pageTitle', $pageTitle);
        $smarty->assign('extraCss', $extraCss);
        $smarty->assign('contentTemplate', $contentTemplate);
        $smarty->assign('extraJs', $extraJs);
        $smarty->assign('type', $type);

        $smarty->display('layout.tpl');

    }

    public function pathInfo() {
        return "/controllers/guest-page";
    }
}