<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\ContactMessageModel;
use App\Shared\Core\Controller;

class AdminContactController extends Controller
{
    private ContactMessageModel $contactMessageModel;

    public function __construct(?ContactMessageModel $contactMessageModel = null)
    {
        $this->contactMessageModel = $contactMessageModel ?? new ContactMessageModel();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();

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
                $this->contactMessageModel->saveMessage([
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

        $this->view(
            'Admin/Presentation/Views/contact/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Contact Us',
                'activeMenu' => 'contact',
                'errors' => $errors,
                'success' => $success,
                'old' => $old,
            ]
        );
    }
}
