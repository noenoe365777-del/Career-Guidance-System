<?php

declare(strict_types=1);

namespace App\Routing;

use App\Modules\Student\Support\StudentFeaturePermissionHelper;
use App\Shared\Core\Container;

class Router
{
    private array $routes;

    private Container $container;

    private array $protectedRoutes = [
        'dashboard',
        'profile',
        'edit-profile',
        'update-profile',
        'change-password',
        'update-password',
        'student-assessments',
        'personality',
        'interest',
        'aptitude',
        'values',
        'assessment-progress',
        'assessment-result',
        'assessment-detailed-answers',
        'recommendation',
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

        if (in_array($page, $this->protectedRoutes, true) && empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        if (StudentFeaturePermissionHelper::isStudentFeatureRestricted($page)) {
            StudentFeaturePermissionHelper::ensureStudentPageAccess($page);
        }

        [$controllerClass, $method] = $this->routes[$page];

        $controller = $this->container->make($controllerClass);

        $controller->$method();
    }
}