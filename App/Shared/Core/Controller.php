<?php

declare(strict_types=1);

namespace App\Shared\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function getAuthenticatedUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;

        if (!is_array($user)) {
            return null;
        }

        if (!isset($user['id']) && isset($user['user_id'])) {
            $user['id'] = (int)$user['user_id'];
        }

        if (!isset($user['user_id']) && isset($user['id'])) {
            $user['user_id'] = (int)$user['id'];
        }

        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = (int)($user['id'] ?? $user['user_id'] ?? 0);

        return $user;
    }

    protected function isAuthenticated(): bool
    {
        $user = $this->getAuthenticatedUser();

        return !empty($user['id']);
    }

    protected function loginUser(array $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($user['id']) && isset($user['user_id'])) {
            $user['id'] = (int)$user['user_id'];
        }

        if (!isset($user['user_id']) && isset($user['id'])) {
            $user['user_id'] = (int)$user['id'];
        }

        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = (int)($user['id'] ?? $user['user_id'] ?? 0);
        session_regenerate_id(true);
    }

    protected function logoutUser(): void
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

    protected function redirectTo(string $page, array $query = []): void
    {
        $url = BASE_URL . '/index.php?page=' . urlencode($page);

        if ($query !== []) {
            $url .= '&' . http_build_query($query);
        }

        header('Location: ' . $url);
        exit;
    }

    protected function redirectToLogin(): void
    {
        $this->redirectTo('login');
    }

    protected function redirectToDashboard(): void
    {
        $this->redirectTo('dashboard');
    }

    protected function requireAuthenticatedUser(): array
    {
        $user = $this->getAuthenticatedUser();

        if (!$user || empty($user['id'])) {
            $this->redirectToLogin();
        }

        return $user;
    }
}