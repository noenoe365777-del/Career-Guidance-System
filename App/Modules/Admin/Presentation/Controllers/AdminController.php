<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Config\Database;
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
                'totalUsers' => (int)($stats['totalUsers'] ?? $this->getTotalUsers()),
                'totalStudents' => (int)($stats['totalStudents'] ?? $this->getTotalStudents()),
                'totalAssessments' => (int)($stats['totalAssessments'] ?? $this->getTotalAssessments()),
                'totalCareers' => (int)($stats['totalCareers'] ?? $this->getTotalCareers()),
                'totalQuestions' => (int)($this->getQuestionCount()),
                'completedAssessments' => (int)($stats['completedAssessments'] ?? $this->getCompletedAssessments()),
                'inProgressAssessments' => (int)($stats['inProgressAssessments'] ?? $this->getInProgressAssessments()),
                'pendingAssessments' => (int)($stats['pendingAssessments'] ?? $this->getPendingAssessments()),
                'assessmentBreakdown' => $stats['assessmentBreakdown'] ?? [],
                'signupTrend' => $stats['signupTrend'] ?? ['labels' => [], 'values' => []],
                'completionTrend' => $stats['completionTrend'] ?? ['labels' => [], 'values' => []],
                'recommendationCoverage' => $stats['recommendationCoverage'] ?? 0,
                'recommendationReady' => $stats['recommendationReady'] ?? 0,
                'recentUsers' => $this->getRecentUsers(5),
                'recentSubmissions' => $stats['recentSubmissions'] ?? [],
                'notifications' => $stats['notifications'] ?? [],
                'systemStatus' => $stats['systemHealth'] ?? 'Healthy',
                'systemStatusClass' => $stats['systemHealthClass'] ?? 'text-success',
            ]
        );
    }

    private function getTotalUsers(): int
    {
        return $this->countRows('users');
    }

    private function getTotalStudents(): int
    {
        return $this->countRows('users', "role = 'student'");
    }

    private function getTotalAssessments(): int
    {
        return $this->countRows('assessments');
    }

    private function getTotalCareers(): int
    {
        return $this->countRows('careers');
    }

    private function getQuestionCount(): int
    {
        return $this->countRows('questions');
    }

    private function getCompletedAssessments(): int
    {
        return $this->countRows('student_assessments', "status = 'completed'");
    }

    private function getInProgressAssessments(): int
    {
        return $this->countRows('student_assessments', "status = 'in_progress'");
    }

    private function getPendingAssessments(): int
    {
        return $this->countRows('student_assessments', "status = 'pending'");
    }

    private function getRecentUsers(int $limit = 5): array
    {
        try {
            $connection = Database::getConnection();
            $statement = $connection->prepare(
                'SELECT id, full_name, email, role, created_at
                 FROM users
                 ORDER BY created_at DESC
                 LIMIT :limit'
            );
            $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $statement->execute();

            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return array_map(function (array $row): array {
                $createdAt = (string)($row['created_at'] ?? '');

                return [
                    'full_name' => trim((string)($row['full_name'] ?? 'Unknown')),
                    'email' => (string)($row['email'] ?? ''),
                    'created_at' => $createdAt !== '' ? date('M d, Y', strtotime($createdAt)) : '',
                    'status' => ((string)($row['role'] ?? 'student')) === 'admin' ? 'Admin' : 'Active',
                ];
            }, $rows);
        } catch (\PDOException $e) {
            return [];
        }
    }

    private function countRows(string $table, string $where = ''): int
    {
        try {
            $connection = Database::getConnection();
            $sql = 'SELECT COUNT(*) FROM ' . $table;

            if ($where !== '') {
                $sql .= ' WHERE ' . $where;
            }

            $statement = $connection->query($sql);

            return (int)$statement->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    public function logout(): void
    {
        AdminAuthMiddleware::logout();

        $this->redirectTo('home');
    }
}

