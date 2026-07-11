<?php

declare(strict_types=1);

namespace App\Shared\Middleware;

use App\Shared\Core\Middleware;

class StudentAuthMiddleware implements Middleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please log in to access this page.';
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }
}
