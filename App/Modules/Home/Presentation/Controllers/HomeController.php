<?php

declare(strict_types=1);

namespace App\Modules\Home\Presentation\Controllers;

class HomeController
{
    public function index(): void
    {
        require BASE_PATH . '/App/Modules/Home/Presentation/Views/home.php';
    }
}