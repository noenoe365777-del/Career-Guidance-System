<?php

namespace App\Modules\Career\Presentation\Controllers;

use App\Shared\Core\View;

class CareerController
{
    public function index(): void
    {
        View::render('Career/Presentation/Views/careers', [
            'pageTitle' => 'Explore Careers - Career Guidance System'
        ]);
    }
}

