<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

class AdminAuthMiddleware
{
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $admin = $_SESSION['admin'] ?? null;

        return is_array($admin) && !empty($admin['id']);
    }

    public static function requireAdmin(): array
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/index.php?page=admin-login');
            exit;
        }

        return $_SESSION['admin'];
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
