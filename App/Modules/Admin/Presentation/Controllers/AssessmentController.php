<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\AssessmentService;
use App\Shared\Core\Controller;
use App\Shared\NotificationHelper;

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
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? trim((string)$_GET['status']) : null;
        $sort = isset($_GET['sort']) && $_GET['sort'] !== '' ? trim((string)$_GET['sort']) : null;

        $assessments = $this->assessmentService->getFilteredAssessments(
            $search !== '' ? $search : null,
            $statusFilter,
            $sort
        );
        $completionsMap = $this->assessmentService->getStudentCompletionsByAssessment();
        $avgScoresMap = [];
        foreach ($this->assessmentService->getAverageScoresByAssessment() as $row) {
            $avgScoresMap[(int)$row['assessment_id']] = [
                'avg_score' => (float)$row['avg_score'],
                'completed_count' => (int)$row['completed_count'],
            ];
        }

        $assessments = array_map(function ($a) use ($completionsMap, $avgScoresMap) {
            $id = (int)($a['assessment_id'] ?? 0);
            $a['students_completed'] = $completionsMap[$id] ?? 0;
            $a['avg_score'] = $avgScoresMap[$id]['avg_score'] ?? 0;
            $a['completed_count'] = $avgScoresMap[$id]['completed_count'] ?? 0;
            return $a;
        }, $assessments);

        $totalAssessments = $this->assessmentService->getTotalAssessments();
        $totalQuestions = $this->assessmentService->getTotalQuestionsCount();
        $studentsCompleted = $this->assessmentService->getStudentsCompletedCount();
        $averageScore = $this->assessmentService->getAverageScore();

        $recentCompleted = $this->assessmentService->getRecentCompletedAssessments(5);
        foreach ($recentCompleted as &$rc) {
            $qCount = (int)($rc['question_count'] ?? 0);
            $maxScore = $qCount * 5;
            $rc['max_score'] = $maxScore;
            $rc['percentage'] = $maxScore > 0 ? round(((float)($rc['total_score'] ?? 0) / $maxScore) * 100, 1) : 0;
        }
        unset($rc);

        $perfData = $this->assessmentService->getPerAssessmentCompletionData();
        $perfMap = [];
        foreach ($perfData as $pd) {
            $aid = (int)($pd['assessment_id'] ?? 0);
            $perfMap[$aid] = [
                'title' => $pd['title'] ?? '',
                'completed_count' => (int)($pd['completed_count'] ?? 0),
                'avg_score' => $avgScoresMap[$aid]['avg_score'] ?? 0,
            ];
        }

        $this->view(
            'Admin/Presentation/Views/assessments/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Assessment Management',
                'activeMenu' => 'assessments',
                'assessments' => $assessments,
                'recentCompleted' => $recentCompleted,
                'perfData' => $perfData,
                'perfMap' => $perfMap,
                'search' => $search,
                'statusFilter' => $statusFilter ?? '',
                'sort' => $sort ?? '',
                'totalAssessments' => $totalAssessments,
                'totalQuestions' => $totalQuestions,
                'studentsCompleted' => $studentsCompleted,
                'averageScore' => $averageScore,
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
        NotificationHelper::assessmentUpdated($title, $id);
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
            $assessment = $this->assessmentService->getAssessmentById($id);
            $this->assessmentService->toggleAssessmentStatus($id);
            if ($assessment) {
                $newStatus = strtolower((string)($assessment['status'] ?? 'active')) === 'active' ? 'inactive' : 'active';
                NotificationHelper::notify(
                    'assessment',
                    'Assessment Status Changed',
                    "Assessment \"{$assessment['title']}\" is now {$newStatus}.",
                    '/index.php?page=admin-assessments-view&id=' . $id
                );
            }
        }

        $this->redirectTo('admin-assessments');
    }

    public function duplicate(): void
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

        $customTitle = trim((string)($_POST['title'] ?? ''));
        $newTitle = $customTitle !== '' ? $customTitle : (string)($assessment['title'] ?? '') . ' (Copy)';
        $result = $this->assessmentService->duplicateAssessment($id, $newTitle);
        if ($result) {
            NotificationHelper::assessmentCreated($newTitle, (int)($result['assessment_id'] ?? 0));
        }

        $this->redirectTo('admin-assessments', ['message' => $result ? 'duplicated' : 'error']);
    }
}
