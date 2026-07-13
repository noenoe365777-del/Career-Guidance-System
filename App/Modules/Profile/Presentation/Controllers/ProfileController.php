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
        $_SESSION['success'] = 'Profile updated successfully.';

        header('Location: index.php?page=edit-profile');
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

public function notifications(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    View::render(
        'Profile/Presentation/Views/notifications',
        [
            'pageTitle' => 'Notifications',
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
    $redirect = $_GET['redirect'] ?? 'profile';

    if (isset($_POST['remove']) && $_POST['remove'] === '1') {
        $this->profileService->updateProfileImage($userId, '');
        $_SESSION['user']['profile_image'] = '';
        $_SESSION['success'] = 'Profile photo removed.';
        header("Location: index.php?page=" . $redirect);
        exit;
    }

    if (
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['error'] === UPLOAD_ERR_OK
    ) {

        $file = $_FILES['profile_image'];

        $maxSize = 2 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'File size must be under 2MB.';
            header("Location: index.php?page=" . $redirect);
            exit;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {

            $newName = "user_" . $userId . "_" . time() . "." . $extension;

            $uploadDir = BASE_PATH . "/public/uploads/profile/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . $newName;

            move_uploaded_file($file['tmp_name'], $destination);

            $this->profileService->updateProfileImage(
                $userId,
                $newName
            );

            $_SESSION['user']['profile_image'] = $newName;
        } else {
            $_SESSION['error'] = 'Allowed formats: JPG, JPEG, PNG, WEBP.';
        }
    }

    header("Location: index.php?page=" . $redirect);
    exit;
}

}