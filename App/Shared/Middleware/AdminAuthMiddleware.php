<?php

declare(strict_types=1);

namespace App\Shared\Middleware;

use App\Shared\Core\Middleware;

class AdminAuthMiddleware implements Middleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $admin = $_SESSION['admin'] ?? null;

        if (!is_array($admin) || empty($admin['id'])) {
            header('Location: ' . BASE_URL . '/index.php?page=admin-login');
            exit;
        }
    }
}
