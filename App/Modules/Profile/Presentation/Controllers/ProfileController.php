<?php

declare(strict_types=1);

namespace App\Modules\Profile\Presentation\Controllers;

use App\Config\Database;
use App\Modules\Profile\Application\Services\ProfileService;
use App\Modules\Profile\Infrastructure\Repositories\ProfileRepository;

class ProfileController
{
    private ProfileService $profileService;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $repository = new ProfileRepository($pdo);

        $this->profileService = new ProfileService($repository);
    }

    /**
     * Display profile page
     */
    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = (int) $_SESSION['user']['user_id'];

        $profile = $this->profileService->getProfile($userId);

        require BASE_PATH .
            '/App/Modules/Profile/Presentation/Views/profile.php';
    }
}