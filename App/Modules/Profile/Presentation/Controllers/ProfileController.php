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

    public function updateProfileImage(): void
{
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit;
    }

    $userId = (int) $_SESSION['user']['user_id'];

    if (
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
    ) {

        $file = $_FILES['profile_image'];

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {

            $newName = "user_" . $userId . "_" . time() . "." . $extension;

            $destination = BASE_PATH .
                "/Public/assets/images/" . $newName;

            move_uploaded_file($file['tmp_name'], $destination);

$result = $this->profileService->updateProfileImage(
    $userId,
    $newName
);
       $_SESSION['user']['profile_image'] = $newName;
        }
    }

    header("Location: index.php?page=profile");
    exit;
}

}