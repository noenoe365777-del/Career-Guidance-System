<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Manual requires
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/App/config/Database.php';



/* Home */
require_once BASE_PATH . '/App/Modules/Home/Presentation/Controllers/HomeController.php';

/* Auth */
require_once BASE_PATH . '/App/Modules/Auth/Domain/Repositories/AuthRepositoryInterface.php';
require_once BASE_PATH . '/App/Modules/Auth/Infrastructure/Repositories/AuthRepository.php';
require_once BASE_PATH . '/App/Modules/Auth/Application/Services/AuthService.php';
require_once BASE_PATH . '/App/Modules/Auth/Presentation/Controllers/AuthController.php';


require_once BASE_PATH . '/App/Modules/Profile/Presentation/Controllers/ProfileController.php';
require_once BASE_PATH . '/App/Modules/Profile/Domain/Repositories/ProfileRepositoryInterface.php';
require_once BASE_PATH . '/App/Modules/Profile/Infrastructure/Repositories/ProfileRepository.php';
require_once BASE_PATH . '/App/Modules/Profile/Application/Services/ProfileService.php';
require_once BASE_PATH . '/App/Modules/Profile/Presentation/Controllers/ProfileController.php';

use App\Modules\Home\Presentation\Controllers\HomeController;
use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Modules\Profile\Presentation\Controllers\ProfileController;

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

        case 'google-login':
    $controller = new AuthController();
    $controller->googleLogin();
    break;

case 'google-callback':
    $controller = new AuthController();
    $controller->googleCallback();
    break;



    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
case 'profile':

    $controller = new ProfileController();

    $controller->index();

    break;
    }