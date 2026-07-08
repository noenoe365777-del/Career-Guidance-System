<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Infrastructure\UserModel;
use App\Shared\Core\Controller;

class UserController extends Controller
{
    private UserModel $userModel;

    public function __construct(?UserModel $userModel = null)
    {
        $this->userModel = $userModel ?? new UserModel();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_users');

        $page = max(1, (int)($_GET['page_number'] ?? $_GET['page'] ?? 1));
        $search = trim((string)($_GET['search'] ?? ''));
        $result = $this->userModel->listUsers($page, 10, $search);

        $this->view(
            'Admin/Presentation/Views/users/index',
            [
                'layout' => 'none',
                'pageTitle' => 'User Management',
                'activeMenu' => 'users',
                'users' => $result['users'],
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'totalUsers' => $result['total'],
                'search' => $search,
                'message' => $_GET['message'] ?? null,
                'userModel' => $this->userModel,
            ]
        );
    }

    public function create(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_users');

        $this->view(
            'Admin/Presentation/Views/users/create',
            [
                'layout' => 'none',
                'pageTitle' => 'Add User',
                'activeMenu' => 'users',
                'errors' => [],
                'old' => [],
                'roles' => $this->userModel->getRoles(),
                'statuses' => $this->userModel->getStatuses(),
                'educationLevels' => $this->userModel->getEducationLevels(),
            ]
        );
    }

    public function store(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_users');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-users');
        }

        $data = [
            'username' => trim((string)($_POST['username'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'password' => (string)($_POST['password'] ?? ''),
            'confirm_password' => (string)($_POST['confirm_password'] ?? ''),
            'user_role_id' => (int)($_POST['user_role_id'] ?? 2),
            'status_id' => (int)($_POST['status_id'] ?? 3),
            'education_level_id' => !empty($_POST['education_level_id']) ? (int)$_POST['education_level_id'] : null,
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'date_of_birth' => trim((string)($_POST['date_of_birth'] ?? '')),
        ];

        $errors = [];

        if ($data['username'] === '') {
            $errors['username'] = 'Full name is required.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email address is required.';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'This email already exists.';
        }

        if ($this->userModel->usernameExists($data['username'])) {
            $errors['username'] = 'This full name already exists.';
        }

        if (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!in_array($data['user_role_id'], [1, 2], true)) {
            $errors['user_role_id'] = 'Select a valid role.';
        }

        if (!in_array($data['status_id'], [1, 2, 3], true)) {
            $errors['status_id'] = 'Select a valid status.';
        }

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/users/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Add User',
                    'activeMenu' => 'users',
                    'errors' => $errors,
                    'old' => $data,
                    'roles' => $this->userModel->getRoles(),
                    'statuses' => $this->userModel->getStatuses(),
                    'educationLevels' => $this->userModel->getEducationLevels(),
                ]
            );
            return;
        }

        $this->userModel->createUser($data);
        $this->redirectTo('admin-users', ['message' => 'created']);
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_users');

        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            $this->redirectTo('admin-users', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/users/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit User',
                'activeMenu' => 'users',
                'errors' => [],
                'old' => $user,
                'roles' => $this->userModel->getRoles(),
                'statuses' => $this->userModel->getStatuses(),
                'educationLevels' => $this->userModel->getEducationLevels(),
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_users');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-users');
        }

        $id = (int)($_POST['id'] ?? 0);
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            $this->redirectTo('admin-users', ['message' => 'not_found']);
        }

        $data = [
            'username' => trim((string)($_POST['username'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'password' => (string)($_POST['password'] ?? ''),
            'confirm_password' => (string)($_POST['confirm_password'] ?? ''),
            'user_role_id' => (int)($_POST['user_role_id'] ?? 2),
            'status_id' => (int)($_POST['status_id'] ?? 3),
            'education_level_id' => !empty($_POST['education_level_id']) ? (int)$_POST['education_level_id'] : null,
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'date_of_birth' => trim((string)($_POST['date_of_birth'] ?? '')),
        ];

        $errors = [];

        if ($data['username'] === '') {
            $errors['username'] = 'Full name is required.';
        }

        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email address is required.';
        } elseif ($this->userModel->emailExists($data['email'], $id)) {
            $errors['email'] = 'This email already exists.';
        }

        if ($this->userModel->usernameExists($data['username'], $id)) {
            $errors['username'] = 'This full name already exists.';
        }

        if ($data['password'] !== '' && strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($data['password'] !== '' && $data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/users/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit User',
                    'activeMenu' => 'users',
                    'errors' => $errors,
                    'old' => array_merge($user, $data),
                    'roles' => $this->userModel->getRoles(),
                    'statuses' => $this->userModel->getStatuses(),
                    'educationLevels' => $this->userModel->getEducationLevels(),
                ]
            );
            return;
        }

        $this->userModel->updateUser($id, $data);
        $this->redirectTo('admin-users', ['message' => 'updated']);
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_users');

        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            $this->redirectTo('admin-users', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/users/view',
            [
                'layout' => 'none',
                'pageTitle' => 'User Details',
                'activeMenu' => 'users',
                'user' => $user,
                'userModel' => $this->userModel,
            ]
        );
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_users');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->userModel->deleteUser($id);
            }
        }

        $this->redirectTo('admin-users', ['message' => 'deleted']);
    }
}
