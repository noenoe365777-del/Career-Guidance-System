<?php

declare(strict_types=1);

namespace App\Routing;

use App\Modules\Student\Support\StudentFeaturePermissionHelper;
use App\Shared\Auth\Auth;
use App\Shared\Core\Container;
use App\Shared\Core\View;

class Router
{
    private array $routes;

    private Container $container;

    private array $routeGuards = [
        'admin-dashboard'                  => 'admin',
        'admin-users'                      => 'admin',
        'admin-users-create'               => 'admin',
        'admin-users-store'                => 'admin',
        'admin-users-edit'                 => 'admin',
        'admin-users-update'               => 'admin',
        'admin-users-view'                 => 'admin',
        'admin-users-delete'               => 'admin',
        'admin-users-toggle-status'        => 'admin',
        'admin-settings-student-permissions' => 'admin',
        'admin-settings-student-permissions-manage' => 'admin',
        'admin-settings-student-permissions-save' => 'admin',
        'student-feature-permissions'      => 'admin',
        'student-feature-permissions-save' => 'admin',
        'admin-assessments'                => 'admin',
        'admin-assessments-view'           => 'admin',
        'admin-assessments-edit'           => 'admin',
        'admin-assessments-update'         => 'admin',
        'admin-assessments-toggle-status'  => 'admin',
        'admin-assessments-duplicate'      => 'admin',
        'admin-careers'                    => 'admin',
        'admin-careers-view'               => 'admin',
        'admin-careers-create'             => 'admin',
        'admin-careers-store'              => 'admin',
        'admin-careers-edit'               => 'admin',
        'admin-careers-update'             => 'admin',
        'admin-careers-delete'             => 'admin',
        'admin-questions'                  => 'admin',
        'admin-questions-view'             => 'admin',
        'admin-questions-create'           => 'admin',
        'admin-questions-store'            => 'admin',
        'admin-questions-edit'             => 'admin',
        'admin-questions-update'           => 'admin',
        'admin-questions-delete'           => 'admin',
        'admin-questions-duplicate'        => 'admin',
        'admin-questions-bulk-delete'      => 'admin',
        'admin-reports'                    => 'admin',
        'admin-notifications'              => 'admin',
        'admin-notifications-api-unread-count' => 'admin',
        'admin-notifications-api-mark-read' => 'admin',
        'admin-notifications-api-mark-all-read' => 'admin',
        'admin-notifications-api-delete'   => 'admin',
        'admin-role-permissions'           => 'admin',
        'admin-role-permissions-save'      => 'admin',
        'dashboard'                        => 'student',
        'profile'                          => 'student',
        'edit-profile'                     => 'student',
        'update-profile'                   => 'student',
        'update-profile-image'             => 'student',
        'change-password'                  => 'student',
        'update-password'                  => 'student',
        'student-change-password'          => 'student',
        'notifications'                    => 'student',
        'student-assessments'              => 'student',
        'personality'                      => 'student',
        'interest'                         => 'student',
        'aptitude'                         => 'student',
        'values'                           => 'student',
        'assessment-progress'              => 'student',
        'assessment-result'                => 'student',
        'assessment-detailed-answers'      => 'student',
        'assessment-api-start'             => 'student',
        'assessment-api-question'          => 'student',
        'assessment-api-save-answer'       => 'student',
        'assessment-api-finish'            => 'student',
        'student-assessments-v2'           => 'student',
        'v2-assessment-api-start'          => 'student',
        'v2-assessment-api-question'       => 'student',
        'v2-assessment-api-save'           => 'student',
        'v2-assessment-api-finish'         => 'student',
        'recommendation'                   => 'student',
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

        $this->runGuard($page);

        if (StudentFeaturePermissionHelper::isStudentFeatureRestricted($page)) {
            StudentFeaturePermissionHelper::ensureStudentPageAccess($page);
        }

        [$controllerClass, $method] = $this->routes[$page];

        $controller = $this->container->make($controllerClass);

        $controller->$method();
    }

    private function runGuard(string $page): void
    {
        $requiredRole = $this->routeGuards[$page] ?? null;

        if ($requiredRole === null) {
            return;
        }

        if (Auth::check()) {
            if ($requiredRole === 'admin' && Auth::isAdmin()) {
                return;
            }
            if ($requiredRole === 'student' && Auth::isStudent()) {
                return;
            }

            http_response_code(403);
            View::render('Admin/Presentation/Views/403', [
                'layout' => 'none',
                'pageTitle' => 'Access Denied',
                'backUrl' => Auth::isAdmin()
                    ? (BASE_URL . '/index.php?page=admin-dashboard')
                    : (BASE_URL . '/index.php?page=dashboard'),
            ]);
            exit;
        }

        if ($requiredRole === 'admin') {
            $_SESSION['error'] = 'Please log in as an admin to access this page.';
            header('Location: ' . BASE_URL . '/index.php?page=admin-login');
            exit;
        }

        $_SESSION['error'] = 'Please log in to access this page.';
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}
