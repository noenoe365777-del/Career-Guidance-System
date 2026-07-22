<?php

declare(strict_types=1);

namespace App\Modules\Profile\Presentation\Controllers;


use App\Modules\Admin\Application\Services\NotificationService;
use App\Modules\Profile\Application\Services\ProfileService;
use App\Shared\Core\View;
use App\Shared\NotificationHelper;

class ProfileController
{
    private ProfileService $profileService;
    private NotificationService $notificationService;

   public function __construct(ProfileService $profileService)
{
    $this->profileService = $profileService;
    $this->notificationService = new NotificationService();
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

        $studentName = $_POST['username'] ?? $_SESSION['user']['username'] ?? 'Student';
        NotificationHelper::userProfileUpdated($studentName, $userId);

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

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);

    $tab = $_GET['tab'] ?? 'all';
    $type = $_GET['type'] ?? '';
    $search = trim((string)($_GET['search'] ?? ''));
    $page = max(1, (int)($_GET['p'] ?? 1));
    $perPage = 15;
    $offset = ($page - 1) * $perPage;

    if ($tab === 'unread') {
        $notifications = $this->notificationService->getUnread($perPage, $offset, $userId, 'student');
        $totalCount = $this->notificationService->getUnreadCount($userId, 'student');
    } else {
        $notifications = $this->notificationService->getAll($perPage, $offset, $type !== '' ? $type : null, $search !== '' ? $search : null, $userId, 'student');
        $totalCount = $this->notificationService->getTotalCount($type !== '' ? $type : null, $search !== '' ? $search : null, $userId, 'student');
    }
    $unreadCount = $this->notificationService->getUnreadCount($userId, 'student');
    $todayCount = $this->notificationService->getTodayCount($userId, 'student');
    $totalPages = max(1, (int)ceil($totalCount / $perPage));

    View::render(
        'Profile/Presentation/Views/notifications',
        [
            'pageTitle' => 'Notifications',
            'layout' => 'dashboard',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
            'todayCount' => $todayCount,
            'tab' => $tab,
            'type' => $type,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
        ]
    );
}

public function apiNotifications(): void
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'unread_count' => 0, 'notifications' => []]);
        return;
    }

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);
    $limit = max(1, min(20, (int)($_GET['limit'] ?? 5)));
    $items = $this->notificationService->getAll(100, 0, null, null, $userId, 'student');

    usort($items, function($a, $b) {
        $aUnread = (int)($a['is_read'] ?? 0);
        $bUnread = (int)($b['is_read'] ?? 0);
        if ($aUnread !== $bUnread) return $aUnread - $bUnread;
        return strtotime((string)($b['created_at'] ?? '')) - strtotime((string)($a['created_at'] ?? ''));
    });

    $items = array_slice($items, 0, $limit);
    $now = new \DateTime();
    $list = array_map(function ($n) use ($now) {
        $created = new \DateTime((string)($n['created_at'] ?? 'now'));
        $diff = $now->getTimestamp() - $created->getTimestamp();
        if ($diff < 60) $timeAgo = 'just now';
        elseif ($diff < 3600) $timeAgo = floor($diff / 60) . 'm ago';
        elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . 'h ago';
        elseif ($diff < 604800) $timeAgo = floor($diff / 86400) . 'd ago';
        else $timeAgo = $created->format('M j');
        return [
            'id' => (int)($n['id'] ?? 0),
            'type' => (string)($n['type'] ?? 'system'),
            'title' => (string)($n['title'] ?? ''),
            'message' => (string)($n['message'] ?? ''),
            'link' => (string)($n['link'] ?? ''),
            'is_read' => (int)($n['is_read'] ?? 0),
            'created_at' => (string)($n['created_at'] ?? ''),
            'time_ago' => $timeAgo,
        ];
    }, $items);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'unread_count' => $this->notificationService->getUnreadCount($userId, 'student'),
        'notifications' => $list,
    ]);
}

public function apiMarkAsRead(): void
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false]);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);
    $id = (int)($_POST['id'] ?? 0);
    $success = $id > 0 && $this->notificationService->markAsRead($id, $userId);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'unread_count' => $this->notificationService->getUnreadCount($userId, 'student'),
    ]);
}

public function apiMarkAllAsRead(): void
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false]);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);
    $success = $this->notificationService->markAllAsRead($userId, 'student');

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'unread_count' => 0,
    ]);
}

public function apiUnreadCount(): void
{
    if (!isset($_SESSION['user'])) {
        echo json_encode(['unread_count' => 0]);
        return;
    }

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);
    header('Content-Type: application/json');
    echo json_encode([
        'unread_count' => $this->notificationService->getUnreadCount($userId, 'student'),
    ]);
}

public function apiDelete(): void
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false]);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }

    $userId = (int)($_SESSION['user']['user_id'] ?? 0);
    $id = (int)($_POST['id'] ?? 0);
    $success = $id > 0 && $this->notificationService->delete($id, $userId);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'unread_count' => $this->notificationService->getUnreadCount($userId, 'student'),
    ]);
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
        $this->jsonError('Not authenticated', 401);
        return;
    }

    $userId = (int) $_SESSION['user']['user_id'];
    $redirect = $_GET['redirect'] ?? 'profile';
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if (isset($_POST['remove']) && $_POST['remove'] === '1') {
        $this->profileService->updateProfileImage($userId, '');
        $_SESSION['user']['profile_image'] = '';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'image_url' => '', 'has_image' => false]);
            return;
        }
        $_SESSION['success'] = 'Profile photo removed.';
        header("Location: index.php?page=" . $redirect);
        exit;
    }

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_image'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'File size must be under 2MB.']);
                return;
            }
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

            $this->profileService->updateProfileImage($userId, $newName);
            $_SESSION['user']['profile_image'] = $newName;

            if ($isAjax) {
                $imageUrl = BASE_URL . '/uploads/profile/' . rawurlencode($newName);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'image_url' => $imageUrl, 'has_image' => true]);
                return;
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Allowed formats: JPG, JPEG, PNG, WEBP.']);
                return;
            }
            $_SESSION['error'] = 'Allowed formats: JPG, JPEG, PNG, WEBP.';
        }
    }

    if (!$isAjax) {
        header("Location: index.php?page=" . $redirect);
        exit;
    }
}

private function jsonError(string $message, int $code = 400): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $message]);
}

}