<?php

declare(strict_types=1);

namespace App\Shared\Middleware;

use App\Shared\Auth\Auth;
use App\Shared\Core\Middleware;
use App\Shared\Core\View;

class StudentMiddleware implements Middleware
{
    public function handle(): void
    {
        if (!Auth::isStudent()) {
            if (!Auth::check()) {
                $_SESSION['error'] = 'Please log in to access this page.';
                header('Location: ' . BASE_URL . '/index.php?page=login');
                exit;
            }

            http_response_code(403);
            View::render('Admin/Presentation/Views/403', [
                'layout' => 'none',
                'pageTitle' => 'Access Denied',
                'backUrl' => BASE_URL . '/index.php?page=dashboard',
            ]);
            exit;
        }
    }
}
