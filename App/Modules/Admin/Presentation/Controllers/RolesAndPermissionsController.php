<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\RolePermissionModel;
use App\Shared\Core\Controller;

class RolesAndPermissionsController extends Controller
{
    private RolePermissionModel $rolePermissionModel;

    public function __construct(?RolePermissionModel $rolePermissionModel = null)
    {
        $this->rolePermissionModel = $rolePermissionModel ?? new RolePermissionModel();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('assign_permissions');

        $selectedRoleId = (int)($_GET['role_id'] ?? 0);

        if ($selectedRoleId > 0) {
            $this->showRolePermissions($selectedRoleId);
            return;
        }

        $roles = $this->rolePermissionModel->getRoles();

        $this->view(
            'Admin/Presentation/Views/role-permissions/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Roles & Permissions',
                'activeMenu' => 'role-permissions',
                'roles' => $roles,
                'selectedRoleId' => 0,
                'groupedPermissions' => [],
                'selectedRoleName' => '',
                'assignedPermissionIds' => [],
                'errors' => [],
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    private function showRolePermissions(int $roleId): void
    {
        $roles = $this->rolePermissionModel->getRoles();
        $permissions = $this->rolePermissionModel->getPermissions();
        $assignedPermissionIds = $this->rolePermissionModel->getAssignedPermissionIds($roleId);

        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $moduleName = trim((string)($permission['module'] ?? 'General'));
            $groupedPermissions[$moduleName][] = $permission;
        }

        ksort($groupedPermissions);

        $selectedRoleName = '';
        foreach ($roles as $role) {
            if ((int)($role['id'] ?? 0) === $roleId) {
                $selectedRoleName = (string)($role['name'] ?? '');
                break;
            }
        }

        $this->view(
            'Admin/Presentation/Views/role-permissions/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Roles & Permissions',
                'activeMenu' => 'role-permissions',
                'roles' => $roles,
                'groupedPermissions' => $groupedPermissions,
                'selectedRoleId' => $roleId,
                'selectedRoleName' => $selectedRoleName,
                'assignedPermissionIds' => $assignedPermissionIds,
                'errors' => [],
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function save(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('assign_permissions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-role-permissions');
        }

        $roleId = (int)($_POST['role_id'] ?? 0);
        $selectedPermissions = $_POST['permissions'] ?? [];
        $permissionIds = [];

        if (is_array($selectedPermissions)) {
            foreach ($selectedPermissions as $permissionId) {
                $id = (int)$permissionId;
                if ($id > 0) {
                    $permissionIds[] = $id;
                }
            }
        }

        $errors = [];
        if ($roleId <= 0) {
            $errors['role_id'] = 'Please select a role.';
        }

        if ($errors !== []) {
            $roles = $this->rolePermissionModel->getRoles();
            $permissions = $this->rolePermissionModel->getPermissions();
            $groupedPermissions = [];
            foreach ($permissions as $permission) {
                $moduleName = trim((string)($permission['module'] ?? 'General'));
                $groupedPermissions[$moduleName][] = $permission;
            }
            ksort($groupedPermissions);

            $this->view(
                'Admin/Presentation/Views/role-permissions/index',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Roles & Permissions',
                    'activeMenu' => 'role-permissions',
                    'roles' => $roles,
                    'groupedPermissions' => $groupedPermissions,
                    'selectedRoleId' => $roleId,
                    'selectedRoleName' => '',
                    'assignedPermissionIds' => array_values(array_unique($permissionIds)),
                    'errors' => $errors,
                    'message' => null,
                ]
            );
            return;
        }

        $this->rolePermissionModel->saveAssignments($roleId, array_values(array_unique($permissionIds)));
        $this->redirectTo('admin-role-permissions', ['role_id' => $roleId, 'message' => 'saved']);
    }
}
