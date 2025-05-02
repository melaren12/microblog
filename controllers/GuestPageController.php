<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Attributes\RouteAttribute;

#[RouteAttribute(uri: '/controllers/guest-page')]

class GuestPageController
{
    public function index()
    {
        $pageTitle = "Microblog";
        $extraCss = "guestPage";
        $extraJs = "guestPage";
        $type = "module";
        $contentTemplate = __DIR__ . "/../src/templates/guestPage.php";
        include __DIR__ . "/../src/templates/layout.php";
    }

    public function pathInfo() {
        return "/controllers/guest-page";
    }
}