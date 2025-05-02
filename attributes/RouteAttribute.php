<?php

namespace App\Attributes;

use Attribute;

#[Attribute]
class RouteAttribute
{
    public string $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }
}