<?php

declare(strict_types=1);

namespace App\Shared\Providers;

use App\Config\Database;
use App\Modules\Auth\Application\Services\AuthService;
use App\Modules\Auth\Infrastructure\Repositories\AuthRepository;

class AuthServiceProvider
{
    public static function make(): AuthService
    {
        $pdo = Database::getConnection();

        $repository = new AuthRepository($pdo);

        return new AuthService($repository);
    }
}