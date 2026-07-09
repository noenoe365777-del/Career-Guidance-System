<?php

declare(strict_types=1);

namespace App\Modules\Profile\Presentation\Controllers;


use App\Modules\Profile\Application\Services\ProfileService;
use App\Shared\Core\View;

class ProfileController
{
    private ProfileService $profileService;

   public function __construct(ProfileService $profileService)
{
    $this->profileService = $profileService;
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

        $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
        $profile = $this->profileService->getProfile($userId) ?? [];

        View::render(
            'Profile/Presentation/Views/profile',
            [
                'pageTitle' => 'My Profile',
                'profile' => $profile,
                'layout' => 'dashboard',
            ]
        );
    }

    public function edit(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $userId = (int) ($_SESSION['user']['user_id'] ?? 0);
    $profile = $this->profileService->getProfile($userId) ?? [];

    View::render(
        'Profile/Presentation/Views/edit-profile',
        [
            'pageTitle' => 'Edit Profile',
            'profile' => $profile,
            'layout' => 'dashboard',
        ]
    );
}


public function update(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=edit-profile');
        exit;
    }

    $userId = (int) $_SESSION['user']['user_id'];

    $result = $this->profileService->updateProfile(
        $userId,
        $_POST
    );

    if ($result['success']) {

        $_SESSION['user']['username'] = $_POST['username'];

        header('Location: index.php?page=profile');
        exit;
    }

    $_SESSION['errors'] = $result['errors'];

    header('Location: index.php?page=edit-profile');
    exit;
}

public function changePassword(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    View::render(
        'Profile/Presentation/Views/change-password',
        [
            'pageTitle' => 'Change Password',
            'extraJs' => 'assets/js/change-password.js',
            'layout' => 'dashboard',
        ]
    );
}

public function studentChangePassword(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    View::render(
        'Profile/Presentation/Views/student-change-password',
        [
            'pageTitle' => 'Change Password',
            'layout' => 'dashboard',
        ]
    );
}

public function updatePassword(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=change-password');
        exit;
    }

    $userId = (int) $_SESSION['user']['user_id'];
    $redirectOnError = $_POST['_redirect'] ?? 'change-password';

    $result = $this->profileService->updatePassword(
        $userId,
        $_POST
    );

    if ($result['success']) {
        $_SESSION['success'] = "Password changed successfully.";
        header('Location: index.php?page=profile');
        exit;
    }

    $_SESSION['errors'] = $result['errors'];
    header('Location: index.php?page=' . $redirectOnError);
    exit;
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