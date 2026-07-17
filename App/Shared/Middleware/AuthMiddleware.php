<?php

declare(strict_types=1);

namespace App\Shared\Middleware;

use App\Shared\Auth\Auth;
use App\Shared\Core\Middleware;

class AuthMiddleware implements Middleware
{
    public function handle(): void
    {
        if (Auth::check()) {
            return;
        }

        $_SESSION['error'] = 'Please log in to access this page.';
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}
