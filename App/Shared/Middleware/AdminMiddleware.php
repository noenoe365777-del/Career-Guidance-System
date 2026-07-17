<?php

declare(strict_types=1);

namespace App\Shared\Middleware;

use App\Shared\Auth\Auth;
use App\Shared\Core\Middleware;
use App\Shared\Core\View;

class AdminMiddleware implements Middleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            $_SESSION['error'] = 'Please log in as an admin to access this page.';
            header('Location: ' . BASE_URL . '/index.php?page=admin-login');
            exit;
        }

        if (!Auth::isAdmin()) {
            http_response_code(403);
            View::render('Admin/Presentation/Views/403', [
                'layout' => 'none',
                'pageTitle' => 'Access Denied',
                'backUrl' => BASE_URL . '/index.php?page=admin-dashboard',
            ]);
            exit;
        }
    }
}
