<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\AssessmentService;
use App\Shared\Core\Controller;

class AssessmentController extends Controller
{
    private AssessmentService $assessmentService;

    public function __construct(?AssessmentService $assessmentService = null)
    {
        $this->assessmentService = $assessmentService ?? new AssessmentService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_assessments');

        $search = trim((string)($_GET['search'] ?? ''));
        $assessments = $this->assessmentService->getAllAssessments($search !== '' ? $search : null);
        $totalAssessments = $this->assessmentService->getTotalAssessments();
        $activeAssessments = $this->assessmentService->getActiveAssessmentsCount();
        $totalQuestions = $this->assessmentService->getTotalQuestionsCount();

        $this->view(
            'Admin/Presentation/Views/assessments/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Assessment Management',
                'activeMenu' => 'assessments',
                'assessments' => $assessments,
                'search' => $search,
                'totalAssessments' => $totalAssessments,
                'activeAssessments' => $activeAssessments,
                'totalQuestions' => $totalQuestions,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_assessments');

        $id = (int)($_GET['id'] ?? 0);
        $assessment = $this->assessmentService->getAssessmentById($id);

        if (!$assessment) {
            $this->redirectTo('admin-assessments', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/assessments/view',
            [
                'layout' => 'none',
                'pageTitle' => 'Assessment Details',
                'activeMenu' => 'assessments',
                'assessment' => $assessment,
            ]
        );
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_assessments');

        $id = (int)($_GET['id'] ?? 0);
        $assessment = $this->assessmentService->getAssessmentById($id);

        if (!$assessment) {
            $this->redirectTo('admin-assessments', ['message' => 'not_found']);
        }

        $this->view(
            'Admin/Presentation/Views/assessments/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit Assessment',
                'activeMenu' => 'assessments',
                'assessment' => $assessment,
                'errors' => [],
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_assessments');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-assessments');
        }

        $id = (int)($_POST['id'] ?? 0);
        $assessment = $this->assessmentService->getAssessmentById($id);

        if (!$assessment) {
            $this->redirectTo('admin-assessments', ['message' => 'not_found']);
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        $errors = [];

        if ($title === '') {
            $errors['title'] = 'Assessment title is required.';
        }

        if ($errors !== []) {
            $this->view(
                'Admin/Presentation/Views/assessments/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit Assessment',
                    'activeMenu' => 'assessments',
                    'assessment' => array_merge($assessment, ['title' => $title, 'description' => $description]),
                    'errors' => $errors,
                ]
            );
            return;
        }

        $this->assessmentService->updateAssessment($id, $title, $description);
        $this->redirectTo('admin-assessments', ['message' => 'updated']);
    }

    public function toggleStatus(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_assessments');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-assessments');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->assessmentService->toggleAssessmentStatus($id);
        }

        $this->redirectTo('admin-assessments');
    }
}
