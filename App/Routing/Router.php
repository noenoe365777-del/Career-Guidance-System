<?php

declare(strict_types=1);

namespace App\Routing;

use App\Modules\Student\Support\StudentFeaturePermissionHelper;
use App\Shared\Auth\Auth;
use App\Shared\Core\Container;
use App\Shared\Core\Middleware;
use App\Shared\Core\View;
use App\Shared\Middleware\AdminMiddleware;
use App\Shared\Middleware\AuthMiddleware;
use App\Shared\Middleware\PermissionMiddleware;
use App\Shared\Middleware\StudentMiddleware;

class Router
{
    private array $patterns = [];

    private array $routeNames = [];

    private ?array $lastRoute = null;

    private string $groupPrefix = '';

    private array $groupMiddleware = [];

    private array $executedAliases = [];

    private array $middlewareMap = [
        'auth' => AuthMiddleware::class,
        'admin' => AdminMiddleware::class,
        'student' => StudentMiddleware::class,
    ];

    private array $middlewarePrerequisites = [
        'student' => ['auth'],
    ];

    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function get(string $path, array $handler, array $middleware = []): static
    {
        $this->match(['GET'], $path, $handler, $middleware);
        return $this;
    }

    public function post(string $path, array $handler, array $middleware = []): static
    {
        $this->match(['POST'], $path, $handler, $middleware);
        return $this;
    }

    public function match(array $methods, string $path, array $handler, array $middleware = []): static
    {
        $path = $this->groupPrefix . '/' . trim($path, '/');
        $path = '/' . ltrim(preg_replace('#/+#', '/', $path), '/');

        $pageName = str_replace('/', '-', trim($path, '/'));
        $flatPath = '/' . $pageName;

        $allMiddleware = array_unique(array_merge($this->groupMiddleware, $middleware));

        $route = [
            'handler' => $handler,
            'middleware' => $allMiddleware,
        ];

        foreach ($methods as $method) {
            $this->patterns[strtoupper($method)][$path] = $route;

            if ($flatPath !== $path) {
                $this->patterns[strtoupper($method)][$flatPath] = $route;
            }
        }

        $this->lastRoute = ['path' => $path, 'methods' => $methods];

        return $this;
    }

    public function name(string $name): static
    {
        if ($this->lastRoute !== null) {
            $this->routeNames[$name] = $this->lastRoute;
        }
        return $this;
    }

    public function url(string $name, array $params = []): string
    {
        $route = $this->routeNames[$name] ?? null;
        if ($route === null) {
            return '#';
        }
        $url = BASE_URL . $route['path'];
        if ($params !== []) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    public function group(string $prefix, callable $callback, array $middleware = []): void
    {
        $previousPrefix = $this->groupPrefix;

        if ($prefix !== '') {
            $this->groupPrefix = rtrim($this->groupPrefix, '/') . '/' . trim($prefix, '/');
        }

        $previousMw = $this->groupMiddleware;
        if (!empty($middleware)) {
            $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);
        }

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMw;
    }

    public function middleware(array $middleware, callable $callback): void
    {
        $this->group('', $callback, $middleware);
    }

    public function dispatch(): void
    {
        $this->executedAliases = [];

        $page = $_GET['page'] ?? 'home';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = '/' . $page;

        $route = $this->patterns[$method][$path] ?? $this->patterns['GET'][$path] ?? null;

        if ($route === null) {
            http_response_code(404);
            echo "404 - Page not found";
            return;
        }

        $handler = $route['handler'];
        $middleware = $route['middleware'] ?? [];

        $middleware = $this->expandMiddlewareStack($middleware);

        foreach ($middleware as $alias) {
            if (in_array($alias, $this->executedAliases, true)) {
                continue;
            }
            $this->executedAliases[] = $alias;
            $this->executeMiddleware($alias);
        }

        if (StudentFeaturePermissionHelper::isStudentFeatureRestricted($page)) {
            StudentFeaturePermissionHelper::ensureStudentPageAccess($page);
        }

        [$controllerClass, $methodName] = $handler;

        $controller = $this->container->make($controllerClass);

        $controller->$methodName();
    }

    private function expandMiddlewareStack(array $middleware): array
    {
        $expanded = [];
        foreach ($middleware as $alias) {
            $prereqs = $this->middlewarePrerequisites[$alias] ?? [];
            foreach ($prereqs as $prereq) {
                if (!in_array($prereq, $expanded, true)) {
                    $expanded[] = $prereq;
                }
            }
            if (!in_array($alias, $expanded, true)) {
                $expanded[] = $alias;
            }
        }
        return $expanded;
    }

    private function executeMiddleware(string $alias): void
    {
        if (str_starts_with($alias, 'can:')) {
            $this->executePermissionMiddleware($alias);
            return;
        }

        $class = $this->middlewareMap[$alias] ?? null;

        if ($class === null) {
            return;
        }

        /** @var Middleware $instance */
        $instance = new $class();
        $instance->handle();
    }

    private function executePermissionMiddleware(string $alias): void
    {
        $permission = substr($alias, 4);

        if (!Auth::check() || !Auth::isAdmin()) {
            $_SESSION['error'] = 'Please log in as an admin to access this page.';
            header('Location: ' . BASE_URL . '/index.php?page=admin-login');
            exit;
        }

        $instance = new PermissionMiddleware($permission);
        $instance->handle();
    }

    public function getRouteNames(): array
    {
        return $this->routeNames;
    }
}
