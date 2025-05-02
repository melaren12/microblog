<?php

declare(strict_types=1);

namespace App\Attributes;

use ReflectionClass;
use ReflectionException;

class Route
{
    private static $routes = [];

    /**
     * @throws ReflectionException
     */
    public static function registerController(string $controllerClass): void
    {
        $reflection = new ReflectionClass($controllerClass);
        $attributes = $reflection->getAttributes(RouteAttribute::class);
        if (!empty($attributes)) {
            $routeAttribute = $attributes[0]->newInstance();
            self::$routes[$routeAttribute->uri] = $controllerClass;
        }
    }

    public static function dispatch(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (isset(self::$routes[$requestUri])) {
            $controllerClass = self::$routes[$requestUri];
            $instance = new $controllerClass();
            $instance->index($_REQUEST);
            exit;
        }
        http_response_code(404);
        echo "The Page Not Found";
    }

    /**
     * @throws ReflectionException
     */
    public static function run(): void
    {
        $controllers = [
            'App\Controllers\ProfilePageController',
            'App\Controllers\ChangeProfileController',
            'App\Controllers\GuestPageController',
            'App\Controllers\LoginPageController',
            'App\Controllers\ArchivePageController',
            'App\Controllers\RegisterPageController'
        ];
        foreach ($controllers as $controller) {
            self::registerController($controller);
        }
        self::dispatch();
    }
}
