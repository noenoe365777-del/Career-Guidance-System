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
                'extraCss' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
                'error' => $error,
            ]
        );
    }

    public function dashboard(): void
    {
        $admin = AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_dashboard');

        $stats = $this->adminDashboardService->getDashboardStats();

        $this->view(
            'Admin/Presentation/Views/dashboard',
            [
                'pageTitle' => 'Admin Dashboard',
                'layout' => 'none',
                'admin' => $admin,
                'activeMenu' => 'dashboard',
                'totalUsers' => $stats['totalUsers'] ?? 0,
                'totalAssessments' => $stats['totalAssessments'] ?? 0,
                'totalCareers' => $stats['totalCareers'] ?? 0,
                'completedAssessments' => $stats['completedAssessments'] ?? 0,
                'recentUsers' => $stats['recentUsers'] ?? [],
                'recentSubmissions' => $stats['recentSubmissions'] ?? [],
                'systemStatus' => ((int)($stats['totalUsers'] ?? 0) > 0) ? 'Healthy' : 'Needs setup',
                'systemStatusClass' => ((int)($stats['totalUsers'] ?? 0) > 0) ? 'text-success' : 'text-warning',
            ]
        );
    }

    public function logout(): void
    {
        AdminAuthMiddleware::logout();

        $this->redirectTo('home');
    }
}

