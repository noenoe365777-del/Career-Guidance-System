<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Presentation\Controllers;

use App\Modules\Dashboard\Application\Services\DashboardService;
use App\Shared\Core\Controller;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(): void
    {
        // Check login
        $user = $this->requireAuthenticatedUser();

        $userId = (int)$user['id'];

        // Dashboard data
        $dashboard =
            $this->dashboardService->getDashboardData($userId);

        // Open dashboard view
        $this->view(
            'Dashboard/Presentation/Views/student-dashboard',
            [
                'pageTitle' => 'Student Dashboard',

                'user' => $user,

                'totalAssessments' =>
                    $dashboard['totalAssessments'],

                'completedAssessments' =>
                    $dashboard['completedAssessments'],

                'percentage' =>
                    $dashboard['percentage'],

                'statusMap' =>
                    $dashboard['statusMap'],

                'recommendation' =>
                    $dashboard['recommendation'],
            ]
        );
    }

    protected function requireAuthenticatedUser(): array
    {
        $user = $this->getAuthenticatedUser();

        if (!$user || empty($user['id'])) {

            header('Location: ' . BASE_URL . '/index.php?page=login');

            exit;
        }

        return $user;
    }
}