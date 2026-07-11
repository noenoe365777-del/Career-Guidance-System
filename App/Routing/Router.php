<?php

declare(strict_types=1);

namespace App\Routing;

use App\Modules\Student\Support\StudentFeaturePermissionHelper;
use App\Shared\Core\Container;
use App\Shared\Core\Middleware;
use App\Shared\Middleware\StudentAuthMiddleware;

class Router
{
    private array $routes;

    private Container $container;

    private array $routeMiddleware = [
        'dashboard' => [StudentAuthMiddleware::class],
        'profile' => [StudentAuthMiddleware::class],
        'edit-profile' => [StudentAuthMiddleware::class],
        'update-profile' => [StudentAuthMiddleware::class],
        'update-profile-image' => [StudentAuthMiddleware::class],
        'change-password' => [StudentAuthMiddleware::class],
        'update-password' => [StudentAuthMiddleware::class],
        'student-change-password' => [StudentAuthMiddleware::class],
        'student-assessments' => [StudentAuthMiddleware::class],
        'personality' => [StudentAuthMiddleware::class],
        'interest' => [StudentAuthMiddleware::class],
        'aptitude' => [StudentAuthMiddleware::class],
        'values' => [StudentAuthMiddleware::class],
        'assessment-progress' => [StudentAuthMiddleware::class],
        'assessment-result' => [StudentAuthMiddleware::class],
        'assessment-detailed-answers' => [StudentAuthMiddleware::class],
        'recommendation' => [StudentAuthMiddleware::class],
    ];

    public function __construct()
    {
        $this->routes = require BASE_PATH . '/App/Routing/web.php';

        $this->container = new Container();
    }

    public function dispatch(string $page): void
    {
        if (!isset($this->routes[$page])) {
            http_response_code(404);
            echo "404 - Page not found";
            return;
        }

        $this->runMiddleware($page);

        if (StudentFeaturePermissionHelper::isStudentFeatureRestricted($page)) {
            StudentFeaturePermissionHelper::ensureStudentPageAccess($page);
        }

        [$controllerClass, $method] = $this->routes[$page];

        $controller = $this->container->make($controllerClass);

        $controller->$method();
    }

    private function runMiddleware(string $page): void
    {
        $middlewareList = $this->routeMiddleware[$page] ?? [];

        foreach ($middlewareList as $middlewareClass) {
            $instance = new $middlewareClass();

            if (!$instance instanceof Middleware) {
                throw new \RuntimeException(
                    sprintf('Middleware "%s" must implement App\Shared\Core\Middleware', $middlewareClass)
                );
            }

            $instance->handle();
        }
    }
}
