<?php

namespace App\Modules\Home\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\ContactMessageModel;
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

    public function aboutUs(): void
    {
        View::render('Home/Presentation/Views/about-us', [
            'pageTitle' => 'About Us - Career Guidance System'
        ]);
    }

    public function contact(): void
    {
        View::render('Home/Presentation/Views/contact', [
            'pageTitle' => 'Contact Us - Career Guidance System',
        ]);
    }

    public function contactAddress(): void
    {
        View::render('Home/Presentation/Views/address', [
            'pageTitle' => 'Our Address - Career Guidance System',
        ]);
    }

    public function contactPhone(): void
    {
        View::render('Home/Presentation/Views/phone', [
            'pageTitle' => 'Phone Support - Career Guidance System',
        ]);
    }

    public function contactEmail(): void
    {
        $errors = [];
        $success = null;
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim((string)($_POST['full_name'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $subject = trim((string)($_POST['subject'] ?? ''));
            $message = trim((string)($_POST['message'] ?? ''));

            if ($fullName === '') {
                $errors['full_name'] = 'Full name is required.';
            }

            if ($email === '') {
                $errors['email'] = 'Email address is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email address.';
            }

            if ($subject === '') {
                $errors['subject'] = 'Please select a subject.';
            }

            if ($message === '') {
                $errors['message'] = 'Message is required.';
            }

            if ($errors === []) {
                $model = new ContactMessageModel();
                $model->saveMessage([
                    'full_name' => $fullName,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                ]);

                $success = 'Your message has been sent successfully. We will get back to you as soon as possible.';
                $old = [];
            } else {
                $old = [
                    'full_name' => $fullName,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                ];
            }
        }

        View::render('Home/Presentation/Views/email', [
            'pageTitle' => 'Send a Message - Career Guidance System',
            'errors' => $errors,
            'success' => $success,
            'old' => $old,
        ]);
    }
}