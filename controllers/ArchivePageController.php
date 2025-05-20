<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/archive-page')]

class ArchivePageController
{
    public function index()
    {
        require_once __DIR__ . '/../smarty_config.php';

        $pageTitle = "Microblog";
        $extraCss = "archivePage";
        $contentTemplate = "archivePage.tpl";
        $extraJs = "archivePage";
        $type = "module";

        $smarty->assign('pageTitle', $pageTitle);
        $smarty->assign('extraCss', $extraCss);
        $smarty->assign('contentTemplate', $contentTemplate);
        $smarty->assign('type', $type);
        $smarty->assign('extraJs', $extraJs);
        $smarty->display('layout.tpl');

    }

    public function pathInfo() {
        return "/controllers/archive-page";
    }
}