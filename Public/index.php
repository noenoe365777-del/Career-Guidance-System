<?php

declare(strict_types=1);

session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);




define('BASE_PATH', dirname(__DIR__));

$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
define('BASE_URL', $scriptDir === '' || $scriptDir === '.' ? '' : $scriptDir);

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/App/config/Database.php';
require_once BASE_PATH . '/App/Routing/Router.php';

use App\Routing\Router;

$router = require BASE_PATH . '/App/Routing/web.php';
$router->dispatch();