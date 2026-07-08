<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\UserModel;
use App\Modules\Student\Infrastructure\StudentFeaturePermissionModel;
use App\Shared\Core\Controller;

class StudentFeaturePermissionController extends Controller
{
    private UserModel $userModel;
    private StudentFeaturePermissionModel $studentFeaturePermissionModel;

    public function __construct(?UserModel $userModel = null, ?StudentFeaturePermissionModel $studentFeaturePermissionModel = null)
    {
        $this->userModel = $userModel ?? new UserModel();
        $this->studentFeaturePermissionModel = $studentFeaturePermissionModel ?? new StudentFeaturePermissionModel();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();

        $page = max(1, (int)($_GET['page_number'] ?? 1));
        $search = trim((string)($_GET['search'] ?? ''));
        $result = $this->userModel->listUsers($page, 100, $search);

        $students = [];
        foreach ($result['users'] as $student) {
            if ((int)($student['user_role_id'] ?? 0) === 2) {
                $students[] = $student;
            }
        }

        $this->view(
            'Admin/Presentation/Views/settings/student-permissions-list',
            [
                'layout' => 'none',
                'pageTitle' => 'Student Permission Management',
                'activeMenu' => 'student-permissions',
                'students' => $students,
                'search' => $search,
                'currentPage' => $page,
                'totalStudents' => count($students),
            ]
        );
    }

    public function manage(): void
    {
        AdminAuthMiddleware::requireAdmin();

        $userId = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->getUserById($userId);

        if (!$user || (int)($user['user_role_id'] ?? 0) !== 2) {
            $this->redirectTo('admin-settings-student-permissions');
        }

        $this->studentFeaturePermissionModel->ensurePermissionsForStudent($userId);

        $this->view(
            'Admin/Presentation/Views/users/manage-feature-permissions',
            [
                'layout' => 'none',
                'pageTitle' => 'Manage Student Permissions',
                'activeMenu' => 'student-permissions',
                'user' => $user,
                'features' => $this->studentFeaturePermissionModel->getFeatureDefinitions(),
                'permissions' => $this->studentFeaturePermissionModel->getPermissionsForStudent($userId),
                'userModel' => $this->userModel,
            ]
        );
    }

    public function save(): void
    {
        AdminAuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-users');
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            $this->redirectTo('admin-users', ['message' => 'not_found']);
        }

        $submittedFeatures = $_POST['features'] ?? [];
        $permissions = [];

        foreach ($this->studentFeaturePermissionModel->getFeatureDefinitions() as $feature) {
            $featureKey = (string)($feature['key'] ?? '');
            if ($featureKey === '') {
                continue;
            }

            $permissions[$featureKey] = !empty($submittedFeatures[$featureKey]) ? 1 : 0;
        }

        $this->studentFeaturePermissionModel->savePermissionsForStudent($userId, $permissions);

        $_SESSION['success'] = 'Student feature permissions updated successfully.';
        $this->redirectTo('admin-settings-student-permissions-manage', ['id' => $userId]);
    }
}
