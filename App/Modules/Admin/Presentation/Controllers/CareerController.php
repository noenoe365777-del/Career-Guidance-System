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

        $result = $this->careerService->getAllCareers($page, 10, $search, $educationFilter, $growthFilter);
        $educationLevels = $this->careerService->getDistinctEducationLevels();
        $growthRates = $this->careerService->getDistinctGrowthRates();
        $totalCareers = $this->careerService->getTotalCareers();

        $this->view(
            'Admin/Presentation/Views/careers/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Career Management',
                'activeMenu' => 'careers',
                'careers' => $result['careers'],
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'totalCareers' => $result['total'],
                'search' => $search,
                'educationFilter' => $educationFilter ?? '',
                'growthFilter' => $growthFilter ?? '',
                'educationLevels' => $educationLevels,
                'growthRates' => $growthRates,
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

        $this->view(
            'Admin/Presentation/Views/careers/view',
            [
                'layout' => 'none',
                'pageTitle' => 'Career Details',
                'activeMenu' => 'careers',
                'career' => $career,
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
}
