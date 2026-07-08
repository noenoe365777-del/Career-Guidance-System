<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\RoleModel;
use App\Shared\Core\Controller;

class RoleController extends Controller
{
    private RoleModel $roleModel;

    public function __construct(?RoleModel $roleModel = null)
    {
        $this->roleModel = $roleModel ?? new RoleModel();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_roles');

        $roles = $this->roleModel->getAllRoles();

        $this->view(
            'Admin/Presentation/Views/roles/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Role Management',
                'activeMenu' => 'roles',
                'roles' => $roles,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function create(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_roles');

        $this->view(
            'Admin/Presentation/Views/roles/create',
            [
                'layout' => 'none',
                'pageTitle' => 'Create Role',
                'activeMenu' => 'roles',
                'errors' => [],
                'old' => [],
            ]
        );
    }

    public function store(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_roles');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-roles');
        }

        $data = [
            'role_name' => trim((string)($_POST['role_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
        ];

        $errors = [];

        if ($data['role_name'] === '') {
            $errors['role_name'] = 'Role name is required.';
        } elseif ($this->roleModel->roleNameExists($data['role_name'])) {
            $errors['role_name'] = 'This role name already exists.';
        }

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/roles/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Create Role',
                    'activeMenu' => 'roles',
                    'errors' => $errors,
                    'old' => $data,
                ]
            );
            return;
        }

        $this->roleModel->createRole($data);
        $this->redirectTo('admin-roles', ['message' => 'created']);
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_roles');

        $id = (int)($_GET['id'] ?? 0);
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            $this->redirectTo('admin-roles', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/roles/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit Role',
                'activeMenu' => 'roles',
                'errors' => [],
                'old' => $role,
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_roles');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-roles');
        }

        $id = (int)($_POST['id'] ?? 0);
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            $this->redirectTo('admin-roles', ['message' => 'not_found']);
        }

        $data = [
            'role_name' => trim((string)($_POST['role_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
        ];

        $errors = [];

        if ($data['role_name'] === '') {
            $errors['role_name'] = 'Role name is required.';
        } elseif ($this->roleModel->roleNameExists($data['role_name'], $id)) {
            $errors['role_name'] = 'This role name already exists.';
        }

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/roles/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit Role',
                    'activeMenu' => 'roles',
                    'errors' => $errors,
                    'old' => array_merge($role, $data),
                ]
            );
            return;
        }

        $this->roleModel->updateRole($id, $data);
        $this->redirectTo('admin-roles', ['message' => 'updated']);
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_roles');

        $id = (int)($_GET['id'] ?? 0);
        $role = $this->roleModel->getRoleById($id);

        if (!$role) {
            $this->redirectTo('admin-roles', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/roles/view',
            [
                'layout' => 'none',
                'pageTitle' => 'Role Details',
                'activeMenu' => 'roles',
                'role' => $role,
            ]
        );
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_roles');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->roleModel->deleteRole($id);
            }
        }

        $this->redirectTo('admin-roles', ['message' => 'deleted']);
    }
}
