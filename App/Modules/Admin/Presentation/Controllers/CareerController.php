<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\CareerService;
use App\Shared\Core\Controller;

class CareerController extends Controller
{
    private CareerService $careerService;

    public function __construct(?CareerService $careerService = null)
    {
        $this->careerService = $careerService ?? new CareerService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_careers');

        $page = max(1, (int)($_GET['page_number'] ?? 1));
        $search = trim((string)($_GET['search'] ?? ''));
        $educationFilter = isset($_GET['education']) && $_GET['education'] !== '' ? trim((string)$_GET['education']) : null;
        $growthFilter = isset($_GET['growth']) && $_GET['growth'] !== '' ? trim((string)$_GET['growth']) : null;
        $categoryFilter = isset($_GET['category']) && $_GET['category'] !== '' ? trim((string)$_GET['category']) : null;
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? trim((string)$_GET['status']) : null;
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['newest', 'az', 'most_recommended']) ? $_GET['sort'] : 'az';

        $result = $this->careerService->getAllCareers($page, 10, $search, $educationFilter, $growthFilter, $categoryFilter, $statusFilter, $sort);
        $educationLevels = $this->careerService->getDistinctEducationLevels();
        $growthRates = $this->careerService->getDistinctGrowthRates();
        $personalityTypes = $this->careerService->getDistinctPersonalityTypes();
        $statuses = $this->careerService->getDistinctStatuses();
        $summaryStats = $this->careerService->getSummaryStats();
        $allRecommendationStudents = $this->careerService->getAllRecommendationStudents();

        $careers = [];
        foreach ($result['careers'] as $career) {
            $careerId = (int)($career['career_id'] ?? 0);
            $career['analytics'] = $this->careerService->getCareerRecommendationAnalytics($careerId);
            $careers[] = $career;
        }

        $this->view(
            'Admin/Presentation/Views/careers/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Career Management',
                'activeMenu' => 'careers',
                'careers' => $careers,
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'totalCareers' => $result['total'],
                'search' => $search,
                'educationFilter' => $educationFilter ?? '',
                'growthFilter' => $growthFilter ?? '',
                'categoryFilter' => $categoryFilter ?? '',
                'statusFilter' => $statusFilter ?? '',
                'sort' => $sort,
                'educationLevels' => $educationLevels,
                'growthRates' => $growthRates,
                'personalityTypes' => $personalityTypes,
                'statuses' => $statuses,
                'summaryStats' => $summaryStats,
                'allRecommendationStudents' => $allRecommendationStudents,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_careers');

        $id = (int)($_GET['id'] ?? 0);
        $career = $this->careerService->getCareerById($id);

        if (!$career) {
            $this->redirectTo('admin-careers', ['message' => 'not_found']);
        }

        $analytics = $this->careerService->getCareerRecommendationAnalytics($id);
        $viewCareer = [
            'career_id' => (int)($career['career_id'] ?? 0),
            'name' => (string)($career['career_name'] ?? 'Career'),
            'category' => (string)($career['personality_type'] ?? 'General'),
            'icon' => (string)($career['career_icon'] ?? 'fa-briefcase'),
            'color' => 'indigo',
            'high_demand' => stripos((string)($career['growth_rate'] ?? ''), 'high') !== false || stripos((string)($career['growth_rate'] ?? ''), 'rapid') !== false,
            'description' => (string)($career['description'] ?? ''),
            'education' => (string)($career['education_required'] ?? ''),
            'certification' => '',
            'salary_min' => $this->extractSalaryValue((string)($career['average_salary'] ?? ''), 'min'),
            'salary_max' => $this->extractSalaryValue((string)($career['average_salary'] ?? ''), 'max'),
            'job_outlook' => (string)($career['growth_rate'] ?? 'Medium'),
            'skills' => $this->parseSkills((string)($career['required_skills'] ?? '')),
            'right_for_you' => [],
            'work_environment' => [],
        ];

        $this->view(
            'Career/Presentation/Views/career-detail',
            [
                'layout' => 'none',
                'pageTitle' => 'Career Details',
                'activeMenu' => 'careers',
                'career' => $viewCareer,
                'relatedCareers' => [],
                'isAdminView' => true,
                'analytics' => $analytics,
                'backUrl' => BASE_URL . '/index.php?page=admin-careers',
            ]
        );
    }

    public function create(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_careers');

        $educationLevels = $this->careerService->getDistinctEducationLevels();

        $this->view(
            'Admin/Presentation/Views/careers/create',
            [
                'layout' => 'none',
                'pageTitle' => 'Add Career',
                'activeMenu' => 'careers',
                'errors' => [],
                'old' => [],
                'educationLevels' => $educationLevels,
            ]
        );
    }

    public function store(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_careers');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-careers');
        }

        $data = [
            'career_name' => trim((string)($_POST['career_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'required_skills' => trim((string)($_POST['required_skills'] ?? '')),
            'average_salary' => trim((string)($_POST['average_salary'] ?? '')),
            'growth_rate' => trim((string)($_POST['growth_rate'] ?? '')),
            'education_required' => trim((string)($_POST['education_required'] ?? '')),
            'personality_type' => trim((string)($_POST['personality_type'] ?? '')),
            'interest_type' => trim((string)($_POST['interest_type'] ?? '')),
            'aptitude_type' => trim((string)($_POST['aptitude_type'] ?? '')),
            'values_type' => trim((string)($_POST['values_type'] ?? '')),
            'status' => trim((string)($_POST['status'] ?? 'active')),
            'career_icon' => trim((string)($_POST['career_icon'] ?? '')),
        ];

        $errors = [];

        if ($data['career_name'] === '') {
            $errors['career_name'] = 'Career name is required.';
        }

        if ($data['education_required'] === '') {
            $errors['education_required'] = 'Education required is required.';
        }

        if ($errors !== []) {
            $educationLevels = $this->careerService->getDistinctEducationLevels();
            $this->view(
                'Admin/Presentation/Views/careers/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Add Career',
                    'activeMenu' => 'careers',
                    'errors' => $errors,
                    'old' => $data,
                    'educationLevels' => $educationLevels,
                ]
            );
            return;
        }

        $id = $this->careerService->createCareer($data);
        if ($id === null) {
            $educationLevels = $this->careerService->getDistinctEducationLevels();
            $this->view(
                'Admin/Presentation/Views/careers/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Add Career',
                    'activeMenu' => 'careers',
                    'errors' => ['general' => 'Failed to create career. Please try again.'],
                    'old' => $data,
                    'educationLevels' => $educationLevels,
                ]
            );
            return;
        }

        $this->redirectTo('admin-careers', ['message' => 'created']);
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_careers');

        $id = (int)($_GET['id'] ?? 0);
        $career = $this->careerService->getCareerById($id);

        if (!$career) {
            $this->redirectTo('admin-careers', ['message' => 'not_found']);
        }

        $educationLevels = $this->careerService->getDistinctEducationLevels();

        $this->view(
            'Admin/Presentation/Views/careers/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit Career',
                'activeMenu' => 'careers',
                'errors' => [],
                'old' => $career,
                'educationLevels' => $educationLevels,
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_careers');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-careers');
        }

        $id = (int)($_POST['id'] ?? 0);
        $career = $this->careerService->getCareerById($id);

        if (!$career) {
            $this->redirectTo('admin-careers', ['message' => 'not_found']);
        }

        $data = [
            'career_name' => trim((string)($_POST['career_name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'required_skills' => trim((string)($_POST['required_skills'] ?? '')),
            'average_salary' => trim((string)($_POST['average_salary'] ?? '')),
            'growth_rate' => trim((string)($_POST['growth_rate'] ?? '')),
            'education_required' => trim((string)($_POST['education_required'] ?? '')),
            'personality_type' => trim((string)($_POST['personality_type'] ?? '')),
            'interest_type' => trim((string)($_POST['interest_type'] ?? '')),
            'aptitude_type' => trim((string)($_POST['aptitude_type'] ?? '')),
            'values_type' => trim((string)($_POST['values_type'] ?? '')),
        ];

        $errors = [];

        if ($data['career_name'] === '') {
            $errors['career_name'] = 'Career name is required.';
        }

        if ($data['education_required'] === '') {
            $errors['education_required'] = 'Education required is required.';
        }

        if ($errors !== []) {
            $educationLevels = $this->careerService->getDistinctEducationLevels();
            $this->view(
                'Admin/Presentation/Views/careers/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit Career',
                    'activeMenu' => 'careers',
                    'errors' => $errors,
                    'old' => array_merge($career, $data),
                    'educationLevels' => $educationLevels,
                ]
            );
            return;
        }

        $this->careerService->updateCareer($id, $data);
        $this->redirectTo('admin-careers', ['message' => 'updated']);
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_careers');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->careerService->deleteCareer($id);
            }
        }

        $this->redirectTo('admin-careers', ['message' => 'deleted']);
    }

    private function extractSalaryValue(string $salary, string $type): int
    {
        $parts = preg_split('/[\-–]/', $salary) ?: [];
        $numbers = [];
        foreach ($parts as $part) {
            $numeric = preg_replace('/[^0-9.]/', '', trim($part));
            if ($numeric !== '') {
                $numbers[] = (int)round((float)$numeric);
            }
        }

        if ($numbers === []) {
            return 0;
        }

        return $type === 'min' ? ($numbers[0] ?? 0) : ($numbers[count($numbers) - 1] ?? $numbers[0]);
    }

    private function parseSkills(string $skills): array
    {
        if ($skills === '') {
            return [];
        }

        $parts = preg_split('/[;,|\n]+/', $skills) ?: [];
        $clean = [];
        foreach ($parts as $part) {
            $value = trim((string)$part);
            if ($value !== '') {
                $clean[] = $value;
            }
        }

        return array_slice($clean, 0, 8);
    }
}
