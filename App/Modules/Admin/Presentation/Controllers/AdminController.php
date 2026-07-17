<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\AdminDashboardService;
use App\Modules\Admin\Infrastructure\AdminModel;
use App\Shared\Core\Controller;
use App\Shared\NotificationHelper;

class AdminController extends Controller
{
    private AdminModel $adminModel;
    private AdminDashboardService $adminDashboardService;

    public function __construct(?AdminModel $adminModel = null, ?AdminDashboardService $adminDashboardService = null)
    {
        $this->adminModel = $adminModel ?? new AdminModel();
        $this->adminDashboardService = $adminDashboardService ?? new AdminDashboardService();
    }

    public function login(): void
    {
        if (AdminAuthMiddleware::isLoggedIn()) {
            $this->redirectTo('admin-dashboard');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Please enter both email and password.';
            } else {
                $admin = $this->adminModel->findAdminByEmail($email);

                if (!$admin || !$this->adminModel->isAdmin($admin)) {
                    if ($admin) {
                        $error = 'Only administrators can log in here.';
                    } else {
                        $error = 'Invalid admin credentials.';
                    }
                } elseif (!password_verify($password, (string)($admin['password'] ?? ''))) {
                    $error = 'Invalid admin credentials.';
                } else {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $adminName = $admin['username'] ?? $admin['full_name'] ?? 'Admin';
                    $_SESSION['admin'] = [
                        'id' => (int)$admin['id'],
                        'full_name' => $adminName,
                        'email' => $admin['email'],
                        'role' => $admin['role'] ?? null,
                        'role_name' => $admin['role'] ?? null,
                        'role_id' => (int)($admin['user_role_id'] ?? 0),
                    ];
                    $_SESSION['admin_id'] = (int)$admin['id'];

                    NotificationHelper::adminLogin($adminName);

                    $this->redirectTo('admin-dashboard');
                }
            }
        }

        $this->view(
            'Admin/Presentation/Views/login',
            [
                'pageTitle' => 'Admin Login',
                'layout' => 'app',
                'error' => $error,
            ]
        );
    }

    public function dashboard(): void
    {
        $admin = AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_dashboard');

        $data = $this->adminDashboardService->getDashboardData();

        $this->view(
            'Admin/Presentation/Views/dashboard',
            [
                'pageTitle' => 'Admin Dashboard',
                'layout' => 'none',
                'admin' => $admin,
                'activeMenu' => 'dashboard',
                'totalStudents' => $data['totalStudents'],
                'totalAssessments' => $data['totalAssessments'],
                'totalCareers' => $data['totalCareers'],
                'totalQuestions' => $data['totalQuestions'],
                'totalRecommendations' => $data['totalRecommendations'],
                'activeStudents' => $data['activeStudents'],
                'todayRegistrations' => $data['todayRegistrations'],
                'todayCompletions' => $data['todayCompletions'],
                'overallCompletionRate' => $data['overallCompletionRate'],
                'recentActivity' => $data['recentActivity'],
                'recentNotifications' => $data['recentNotifications'],
                'completionStats' => $data['completionStats'],
                'unreadNotificationCount' => $data['unreadNotificationCount'],
            ]
        );
    }

    public function profile(): void
    {
        $admin = AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_dashboard');

        $adminId = (int)($admin['id'] ?? 0);
        $errors = [];
        $success = null;

        $profile = $this->adminModel->findAdminById($adminId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string)($_POST['username'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $phone = trim((string)($_POST['phone'] ?? ''));
            $address = trim((string)($_POST['address'] ?? ''));
            $bio = trim((string)($_POST['bio'] ?? ''));

            if ($username === '') {
                $errors[] = 'Full name is required.';
            }

            if ($email === '') {
                $errors[] = 'Email address is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            } elseif ($this->adminModel->emailExists($email, $adminId)) {
                $errors[] = 'This email is already taken by another user.';
            }

            $removeImage = (string)($_POST['remove_image'] ?? '');
            $imageName = $profile['profile_image'] ?? null;

            if ($removeImage === '1') {
                if ($imageName !== null && $imageName !== '') {
                    $oldPath = BASE_PATH . '/public/uploads/profile/' . $imageName;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $imageName = null;
            } elseif (empty($errors) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
                $maxSize = 2 * 1024 * 1024;

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $detectedType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($detectedType, $allowedTypes, true) || !in_array($ext, $allowedExts, true)) {
                    $errors[] = 'Profile picture must be a JPG, PNG, or WebP image.';
                } elseif ($file['size'] > $maxSize) {
                    $errors[] = 'Profile picture must be under 2MB.';
                } else {
                    $uploadDir = BASE_PATH . '/public/uploads/profile/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newName = 'admin_' . $adminId . '_' . time() . '.' . $ext;
                    $destPath = $uploadDir . $newName;

                    if (move_uploaded_file($file['tmp_name'], $destPath)) {
                        if ($imageName !== null && $imageName !== '' && file_exists($uploadDir . $imageName)) {
                            unlink($uploadDir . $imageName);
                        }
                        $imageName = $newName;
                    } else {
                        $errors[] = 'Failed to upload profile picture.';
                    }
                }
            }

            $currentPassword = (string)($_POST['current_password'] ?? '');
            $newPassword = (string)($_POST['new_password'] ?? '');
            $confirmPassword = (string)($_POST['confirm_password'] ?? '');
            $changingPassword = $currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '';

            if ($changingPassword) {
                if ($currentPassword === '') {
                    $errors[] = 'Current password is required.';
                } elseif (!$this->adminModel->verifyPassword($adminId, $currentPassword)) {
                    $errors[] = 'Current password is incorrect.';
                }

                if ($newPassword === '') {
                    $errors[] = 'New password is required.';
                } elseif (strlen($newPassword) < 8) {
                    $errors[] = 'New password must be at least 8 characters.';
                }

                if ($confirmPassword === '') {
                    $errors[] = 'Confirm password is required.';
                } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
                    $errors[] = 'New password and confirm password do not match.';
                }
            }

            if (empty($errors)) {
                $updated = $this->adminModel->updateProfile($adminId, [
                    'username' => $username,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'bio' => $bio,
                ]);

                if ($updated) {
                    if ($imageName !== ($profile['profile_image'] ?? null)) {
                        $this->adminModel->updateProfileImage($adminId, $imageName);
                    }

                    if ($changingPassword) {
                        $this->adminModel->updatePassword($adminId, password_hash($newPassword, PASSWORD_DEFAULT));
                    }

                    $_SESSION['admin']['full_name'] = $username;
                    $_SESSION['admin']['email'] = $email;

                    $profile = $this->adminModel->findAdminById($adminId);
                    $success = 'Profile updated successfully.';
                } else {
                    $errors[] = 'Failed to update profile. Please try again.';
                }
            }

            if (!empty($errors)) {
                $profile = [
                    'id' => $adminId,
                    'username' => $username,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'bio' => $bio,
                    'profile_image' => $imageName,
                ];
            }
        }

        $this->view(
            'Admin/Presentation/Views/settings/profile',
            [
                'layout' => 'none',
                'pageTitle' => 'My Profile',
                'activeMenu' => 'profile',
                'admin' => $admin,
                'profile' => $profile,
                'errors' => $errors,
                'success' => $success,
            ]
        );
    }

    public function logout(): void
    {
        AdminAuthMiddleware::logout();
        $this->redirectTo('home');
    }
}
