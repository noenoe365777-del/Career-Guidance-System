<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\UserService;
use App\Shared\Core\Controller;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(?UserService $userService = null)
    {
        $this->userService = $userService ?? new UserService();
    }

    public function index(): void
    {

    $recentStudents = $this->userService->getRecentStudents(5);

        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_users');

        $page = max(1, (int)($_GET['page_number'] ?? 1));
        $search = trim((string)($_GET['search'] ?? ''));
        $educationLevel = isset($_GET['education_level']) ? (int)$_GET['education_level'] : null;

        $result = $this->userService->listUsers($page, 12, $search, null, null, $educationLevel);
        $stats = $this->userService->getStudentSummaryStats();
        $recentStudents = $this->userService->getRecentStudents(5);
        $educationLevels = $this->userService->getEducationLevels();

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
                'selectedEducationLevel' => $educationLevel,
                'studentStats' => $stats,
                'recentStudents' => $recentStudents,
                'educationLevels' => $educationLevels,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_users');

        $id = (int)($_GET['id'] ?? 0);

        header('Content-Type: application/json');
        $data = $this->userService->getUserDetailForModal($id);
        echo json_encode($data ?: []);
    }
}
