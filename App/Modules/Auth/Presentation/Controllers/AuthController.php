<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Controllers;

use App\Shared\Core\Controller;
use App\Shared\NotificationHelper;
use App\Modules\Auth\Application\Services\AuthService;

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

class AuthController extends Controller
{
    private AuthService $authService;
    private array $googleConfig;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->googleConfig = require BASE_PATH . '/App/config/google.php';
    }

    public function login(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->login($_POST);

            if ($result['success']) {
                $user = $result['user'] ?? [];
                $this->loginUser($user);
                $this->redirectToDashboard();
            }

            $_SESSION['errors'] = $result['errors'] ?? [];
            $_SESSION['old'] = $_POST;
            $this->redirectToLogin();
        }

        $this->view(
            'Auth/Presentation/Views/login',
            ['pageTitle' => 'Login']
        );
    }

    public function register(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->register($_POST);

            if ($result['success']) {
                NotificationHelper::studentRegistered(
                    (string)($_POST['username'] ?? 'A student'),
                    (int)($result['user_id'] ?? 0)
                );
                $_SESSION['pending_verification_email'] = $result['email'] ?? ($_POST['email'] ?? null);
                $_SESSION['pending_verification_user_id'] = (int)($result['user_id'] ?? 0);
                $_SESSION['success'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=verify-email');
                exit;
            }

            $_SESSION['errors'] = $result['errors'] ?? [];
            if (isset($result['message']) && empty($_SESSION['errors'])) {
                $_SESSION['error'] = $result['message'];
            }
            $_SESSION['old'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?page=register');
            exit;
        }

        $this->view(
            'Auth/Presentation/Views/register',
            ['pageTitle' => 'Register']
        );
    }

    public function verifyEmail(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? $_SESSION['pending_verification_email'] ?? '');
            $code = trim($_POST['code'] ?? '');
            $userId = (int)($_POST['user_id'] ?? $_SESSION['pending_verification_user_id'] ?? 0);

            $result = $this->authService->verifyEmail($email, $code, $userId);

            if ($result['success']) {
                unset($_SESSION['pending_verification_email'], $_SESSION['pending_verification_user_id']);
                $_SESSION['success'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=login');
                exit;
            }

            $_SESSION['errors'] = ['verification' => $result['message']];
            $_SESSION['old'] = ['email' => $email];
            header('Location: ' . BASE_URL . '/index.php?page=verify-email');
            exit;
        }

        $this->view(
            'Auth/Presentation/Views/verify-email',
            ['pageTitle' => 'Verify Email']
        );
    }

    public function resendVerification(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=verify-email');
            exit;
        }

        $email = trim($_POST['email'] ?? $_SESSION['pending_verification_email'] ?? '');
        $userId = (int)($_POST['user_id'] ?? $_SESSION['pending_verification_user_id'] ?? 0);

        $result = $this->authService->resendVerification($email, $userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['errors'] = ['resend' => $result['message']];
        }

        header('Location: ' . BASE_URL . '/index.php?page=verify-email');
        exit;
    }

    public function forgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->forgotPassword($_POST);

            if ($result['success']) {
                $_SESSION['pending_password_reset_email'] = $result['email'] ?? ($_POST['email'] ?? '');
                $_SESSION['pending_password_reset_user_id'] = (int)($result['user_id'] ?? 0);
                $_SESSION['success'] = 'Verification code sent to your email.';
                header('Location: ' . BASE_URL . '/index.php?page=verify-reset-code');
                exit;
            }

            $errors = $result['errors'] ?? [];
            $errorMsg = $result['message'] ?? (!empty($errors) ? reset($errors) : 'Unable to process request.');
            $_SESSION['forgot_errors'] = $errorMsg;
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }

    public function verifyResetCode(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? $_SESSION['pending_password_reset_email'] ?? '');
            $code = trim($_POST['code'] ?? '');

            $result = $this->authService->verifyPasswordResetCode($email, $code);

            if ($result['success']) {
                $_SESSION['pending_password_reset_email'] = $email;
                $_SESSION['pending_password_reset_user_id'] = (int)($result['user_id'] ?? $_SESSION['pending_password_reset_user_id'] ?? 0);
                header('Location: ' . BASE_URL . '/index.php?page=reset-password');
                exit;
            }

            $_SESSION['errors'] = ['verification' => $result['message']];
            $_SESSION['old'] = ['email' => $email];
            header('Location: ' . BASE_URL . '/index.php?page=verify-reset-code');
            exit;
        }

        $this->view(
            'Auth/Presentation/Views/verify-reset-code',
            ['pageTitle' => 'Verify Reset Code']
        );
    }

    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_SESSION['pending_password_reset_user_id'] ?? 0);
            $result = $this->authService->resetPassword($userId, $_POST);

            if ($result['success']) {
                unset($_SESSION['pending_password_reset_email'], $_SESSION['pending_password_reset_user_id']);
                $_SESSION['success'] = $result['message'];
                header('Location: ' . BASE_URL . '/index.php?page=login');
                exit;
            }

            $_SESSION['errors'] = $result['errors'] ?? ['password' => $result['message'] ?? 'Unable to reset password.'];
            $_SESSION['old'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?page=reset-password');
            exit;
        }

        $this->view(
            'Auth/Presentation/Views/reset-password',
            ['pageTitle' => 'Reset Password']
        );
    }

    public function googleLogin(): void
    {
        $client = new GoogleClient();

        $client->setClientId($this->googleConfig['client_id']);
        $client->setClientSecret($this->googleConfig['client_secret']);
        $client->setRedirectUri($this->googleConfig['redirect_uri']);

        $client->addScope('email');
        $client->addScope('profile');

        header('Location: ' . $client->createAuthUrl());
        exit;
    }

    public function googleCallback(): void
    {
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = 'Google login failed.';
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $client = new GoogleClient();

        $logger = new Logger('google');
        $logger->pushHandler(new ErrorLogHandler());

        $client->setLogger($logger);

        $client->setClientId($this->googleConfig['client_id']);
        $client->setClientSecret($this->googleConfig['client_secret']);
        $client->setRedirectUri($this->googleConfig['redirect_uri']);

        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Google login is temporarily unavailable. Please try again later.';
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        if (isset($token['error'])) {
            $_SESSION['error'] = 'Google login failed.';
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $client->setAccessToken($token);

        $oauth = new Oauth2($client);
        $googleUser = $oauth->userinfo->get();

        $result = $this->authService->loginWithGoogle(
            $googleUser->id,
            $googleUser->name,
            $googleUser->email
        );

        if (!$result['success']) {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $user = $result['user'] ?? [];
        $this->loginUser($user);
        $this->redirectToDashboard();
    }

    public function logout(): void
    {
        $this->logoutUser();
        $_SESSION['success'] = 'You have been logged out.';
        $this->redirectToLogin();
    }
}