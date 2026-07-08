<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\RolePermissionModel;
use App\Modules\Admin\Validation\RolePermissionValidator;
use App\Shared\Core\Controller;

class RolePermissionController extends Controller
{
    private RolePermissionModel $rolePermissionModel;
    private RolePermissionValidator $validator;

    public function __construct(?RolePermissionModel $rolePermissionModel = null, ?RolePermissionValidator $validator = null)
    {
        $this->rolePermissionModel = $rolePermissionModel ?? new RolePermissionModel();
        $this->validator = $validator ?? new RolePermissionValidator();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('assign_permissions');

        $selectedRoleId = (int)($_GET['role_id'] ?? 0);
        $roles = $this->rolePermissionModel->getRoles();
        $permissions = $this->rolePermissionModel->getPermissions();
        $assignedPermissionIds = $selectedRoleId > 0 ? $this->rolePermissionModel->getAssignedPermissionIds($selectedRoleId) : [];

        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $moduleName = trim((string)($permission['module_name'] ?? 'General'));
            $groupedPermissions[$moduleName][] = $permission;
        }

        ksort($groupedPermissions);

        $selectedRoleName = '';
        foreach ($roles as $role) {
            if ((int)($role['role_id'] ?? 0) === $selectedRoleId) {
                $selectedRoleName = (string)($role['role_name'] ?? '');
                break;
            }
        }

        $this->view(
            'Admin/Presentation/Views/assign-permissions/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Assign Permissions',
                'activeMenu' => 'assign-permissions',
                'roles' => $roles,
                'groupedPermissions' => $groupedPermissions,
                'selectedRoleId' => $selectedRoleId,
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
            $this->redirectTo('admin-assign-permissions');
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

        $errors = $this->validator->validate([
            'role_id' => $roleId,
            'permissions' => $permissionIds,
        ]);

        if ($errors !== []) {
            $roles = $this->rolePermissionModel->getRoles();
            $permissions = $this->rolePermissionModel->getPermissions();
            $groupedPermissions = [];
            foreach ($permissions as $permission) {
                $moduleName = trim((string)($permission['module_name'] ?? 'General'));
                $groupedPermissions[$moduleName][] = $permission;
            }
            ksort($groupedPermissions);

            $this->view(
                'Admin/Presentation/Views/assign-permissions/index',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Assign Permissions',
                    'activeMenu' => 'assign-permissions',
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
        $this->redirectTo('admin-assign-permissions', ['role_id' => $roleId, 'message' => 'saved']);
    }
}
