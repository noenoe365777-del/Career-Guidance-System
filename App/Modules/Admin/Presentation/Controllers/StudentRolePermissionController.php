<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\StudentRolePermissionService;
use App\Shared\Core\Controller;

class StudentRolePermissionController extends Controller
{
    private StudentRolePermissionService $service;

    public function __construct(?StudentRolePermissionService $service = null)
    {
        $this->service = $service ?? new StudentRolePermissionService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_permissions');

        $features = $this->service->getAll();

        $this->view(
            'Admin/Presentation/Views/settings/student-role-permissions',
            [
                'layout' => 'none',
                'pageTitle' => 'Student Role Permissions',
                'activeMenu' => 'student-role-permissions',
                'features' => $features,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_permissions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-student-role-permissions');
        }

        $enabledKeys = $_POST['features'] ?? [];

        foreach ($this->service->getFeatureDefinitions() as $feature) {
            $featureKey = $feature['key'];
            $isEnabled = !empty($enabledKeys[$featureKey]);
            $this->service->update($featureKey, $isEnabled);
        }

        $_SESSION['success'] = 'Student role permissions updated successfully.';
        $this->redirectTo('admin-student-role-permissions');
    }
}
