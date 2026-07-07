<?php

declare(strict_types=1);

namespace App\Shared\Providers;

use App\Config\Database;
use App\Modules\Profile\Application\Services\ProfileService;
use App\Modules\Profile\Infrastructure\Repositories\ProfileRepository;

class ProfileServiceProvider
{
    public static function make(): ProfileService
    {
        $pdo = Database::getConnection();

        $repository = new ProfileRepository($pdo);

        return new ProfileService($repository);
    }
}