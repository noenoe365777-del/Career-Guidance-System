<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\AdminDashboardService;
use App\Modules\Admin\Infrastructure\AdminModel;
use App\Shared\Core\Controller;

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

                    $_SESSION['admin'] = [
                        'id' => (int)$admin['id'],
                        'full_name' => $admin['username'] ?? $admin['full_name'] ?? 'Admin',
                        'email' => $admin['email'],
                        'role' => $admin['role'] ?? null,
                        'role_name' => $admin['role'] ?? null,
                        'role_id' => (int)($admin['user_role_id'] ?? 0),
                    ];
                    $_SESSION['admin_id'] = (int)$admin['id'];

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
                'totalQuestions' => $data['totalQuestions'],
                'totalCareers' => $data['totalCareers'],
                'recentActivity' => $data['recentActivity'],
                'recentStudents' => $data['recentStudents'],
            ]
        );
    }

    public function logout(): void
    {
        AdminAuthMiddleware::logout();
        $this->redirectTo('home');
    }
}
