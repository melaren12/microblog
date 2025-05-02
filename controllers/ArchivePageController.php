<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/archive-page')]

class ArchivePageController
{
    public function index()
    {
        $pageTitle = "Microblog";
        $extraCss = "archivePage";
        $extraJs = "archivePage";
        $contentTemplate = __DIR__ . "/../src/templates/archivePage.php";
        $type = "module";
        include __DIR__ . "/../src/templates/layout.php";
    }

    public function pathInfo() {
        return "/controllers/archive-page";
    }
}