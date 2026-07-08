<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\PermissionModel;
use App\Modules\Admin\Validation\PermissionValidator;
use App\Shared\Core\Controller;

class PermissionController extends Controller
{
    private PermissionModel $permissionModel;
    private PermissionValidator $permissionValidator;

    public function __construct(?PermissionModel $permissionModel = null, ?PermissionValidator $permissionValidator = null)
    {
        $this->permissionModel = $permissionModel ?? new PermissionModel();
        $this->permissionValidator = $permissionValidator ?? new PermissionValidator();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_permissions');

        $permissions = $this->permissionModel->getAllPermissions();

        $this->view(
            'Admin/Presentation/Views/permissions/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Permission Management',
                'activeMenu' => 'permissions',
                'permissions' => $permissions,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function create(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_permissions');

        $this->view(
            'Admin/Presentation/Views/permissions/create',
            [
                'layout' => 'none',
                'pageTitle' => 'Create Permission',
                'activeMenu' => 'permissions',
                'errors' => [],
                'old' => [],
            ]
        );
    }

    public function store(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_permissions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-permissions');
        }

        $data = [
            'permission_name' => trim((string)($_POST['permission_name'] ?? '')),
            'module_name' => trim((string)($_POST['module_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
        ];

        $errors = $this->permissionValidator->validate($data, null, function (string $permissionName): bool {
            return $this->permissionModel->permissionNameExists($permissionName);
        });

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/permissions/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Create Permission',
                    'activeMenu' => 'permissions',
                    'errors' => $errors,
                    'old' => $data,
                ]
            );
            return;
        }

        $this->permissionModel->createPermission($data);
        $this->redirectTo('admin-permissions', ['message' => 'created']);
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_permissions');

        $id = (int)($_GET['id'] ?? 0);
        $permission = $this->permissionModel->getPermissionById($id);

        if (!$permission) {
            $this->redirectTo('admin-permissions', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/permissions/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit Permission',
                'activeMenu' => 'permissions',
                'errors' => [],
                'old' => $permission,
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_permissions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-permissions');
        }

        $id = (int)($_POST['id'] ?? 0);
        $permission = $this->permissionModel->getPermissionById($id);

        if (!$permission) {
            $this->redirectTo('admin-permissions', ['message' => 'not_found']);
        }

        $data = [
            'permission_name' => trim((string)($_POST['permission_name'] ?? '')),
            'module_name' => trim((string)($_POST['module_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
        ];

        $errors = $this->permissionValidator->validate($data, $id, function (string $permissionName, ?int $excludeId = null): bool {
            return $this->permissionModel->permissionNameExists($permissionName, $excludeId);
        });

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/permissions/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit Permission',
                    'activeMenu' => 'permissions',
                    'errors' => $errors,
                    'old' => array_merge($permission, $data),
                ]
            );
            return;
        }

        $this->permissionModel->updatePermission($id, $data);
        $this->redirectTo('admin-permissions', ['message' => 'updated']);
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_permissions');

        $id = (int)($_GET['id'] ?? 0);
        $permission = $this->permissionModel->getPermissionById($id);

        if (!$permission) {
            $this->redirectTo('admin-permissions', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/permissions/view',
            [
                'layout' => 'none',
                'pageTitle' => 'Permission Details',
                'activeMenu' => 'permissions',
                'permission' => $permission,
            ]
        );
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_permissions');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->permissionModel->deletePermission($id);
            }
        }

        $this->redirectTo('admin-permissions', ['message' => 'deleted']);
    }
}
