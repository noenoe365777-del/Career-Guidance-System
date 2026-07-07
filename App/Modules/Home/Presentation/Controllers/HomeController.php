<?php

namespace App\Modules\Home\Presentation\Controllers;

use App\Shared\Core\Controller;
use App\Shared\Core\View;

class HomeController extends Controller
{
    public function index(): void
    {
        View::render('Home/Presentation/Views/home', [
            'pageTitle' => 'Home - Career Guidance System'
        ]);
    }

    public function about(): void
    {
        View::render('Home/Presentation/Views/about', [
            'pageTitle' => 'About Us - Career Guidance System'
        ]);
    }

    public function contact(): void
    {
        View::render('Home/Presentation/Views/contact', [
            'pageTitle' => 'Contact Us - Career Guidance System'
        ]);
    }
}