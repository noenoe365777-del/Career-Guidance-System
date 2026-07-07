<?php

declare(strict_types=1);

namespace App\Shared\Core;

use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Shared\Providers\AuthServiceProvider;
use App\Modules\Dashboard\Presentation\Controllers\DashboardController;
use App\Modules\Profile\Presentation\Controllers\ProfileController;
use App\Shared\Providers\ProfileServiceProvider;

class Container
{
    public function make(string $class)
    {
        if ($class === AuthController::class) {
            return new AuthController(
                AuthServiceProvider::make()
            );
        }

        if ($class === ProfileController::class) {
            return new ProfileController(
                ProfileServiceProvider::make()
            );
        }

        if ($class === DashboardController::class) {
    return new DashboardController();
}

        return new $class();
    }
}