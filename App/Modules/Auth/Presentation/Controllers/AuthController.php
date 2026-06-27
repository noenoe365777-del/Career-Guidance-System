<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Controllers;

use App\Config\Database;
use App\Modules\Auth\Infrastructure\Repositories\AuthRepository;
use App\Modules\Auth\Application\Services\AuthService;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class AuthController
{
    private AuthService $authService;
    private array $googleConfig;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $repository = new AuthRepository($pdo);
        $this->authService = new AuthService($repository);

        $this->googleConfig = require BASE_PATH . '/App/config/google.php';
    }
public function login(): void
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = $this->authService->login($_POST);

    if ($result['success']) {

        $_SESSION['user'] = $result['user'];

        header('Location: index.php?page=home');
        exit;
    }

    $_SESSION['errors'] = $result['errors'] ?? [];
$_SESSION['old'] = $_POST;

    header('Location: index.php?page=login');
    exit;

}

    require BASE_PATH . '/App/Modules/Auth/Presentation/Views/login.php';
}

 public function register(): void
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = $this->authService->register($_POST);

    if ($result['success']) {

        $_SESSION['success'] = $result['message'];

        header('Location: index.php?page=login');
        exit;
    }

    $_SESSION['errors'] = $result['errors'] ?? [];
    $_SESSION['old'] = $_POST;

    header('Location: index.php?page=register');
    exit;

}
    require BASE_PATH . '/App/Modules/Auth/Presentation/Views/register.php';
}

    public function googleLogin(): void
    {
        $client = new GoogleClient();
        $client->setClientId($this->googleConfig['client_id']);
        $client->setClientSecret($this->googleConfig['client_secret']);
        $client->setRedirectUri($this->googleConfig['redirect_uri']);

        $client->addScope('email');
        $client->addScope('profile');

        $authUrl = $client->createAuthUrl();

        header('Location: ' . $authUrl);
        exit;
    }

    public function googleCallback(): void
    {
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = 'Google login failed: missing authorization code.';
            header('Location: index.php?page=login');
            exit;
        }

        $client = new GoogleClient();
        $client->setClientId($this->googleConfig['client_id']);
        $client->setClientSecret($this->googleConfig['client_secret']);
        $client->setRedirectUri($this->googleConfig['redirect_uri']);

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            $_SESSION['error'] = 'Google login failed.';
            header('Location: index.php?page=login');
            exit;
        }

        $client->setAccessToken($token);

        $oauth = new Oauth2($client);
        $googleUser = $oauth->userinfo->get();

        $googleId = $googleUser->id ?? '';
        $fullName = $googleUser->name ?? 'Google User';
        $email = $googleUser->email ?? '';

        if ($googleId === '' || $email === '') {
            $_SESSION['error'] = 'Google account data is incomplete.';
            header('Location: index.php?page=login');
            exit;
        }

        $result = $this->authService->loginWithGoogle($googleId, $fullName, $email);

        if ($result['success'] !== true) {
            $_SESSION['error'] = $result['message'] ?? 'Google login failed.';
            header('Location: index.php?page=login');
            exit;
        }

        $_SESSION['user'] = $result['user'];

        header('Location: index.php?page=home');
        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php?page=home');
        exit;
    }
}