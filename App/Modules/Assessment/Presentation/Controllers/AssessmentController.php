<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Presentation\Controllers;

use App\Modules\Assessment\Application\Services\AssessmentService;
use App\Shared\Core\Controller;
use App\Shared\Core\View;

class AssessmentController extends Controller
{
    private AssessmentService $assessmentService;

    public function __construct()
    {
        $this->assessmentService = new AssessmentService();
    }

    public function index(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = isset($user['id']) ? (int)$user['id'] : null;
        $assessments = $this->assessmentService->getAssessments($userId);

        $this->view(
            'Assessment/Presentation/Views/student/assessment-dashboard-loggedin',
            [
                'pageTitle' => 'My Assessments',
                'assessments' => $assessments,
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }

    public function publicIndex(): void
    {
        $assessments = $this->assessmentService->getAssessments(null);

        $this->view(
            'Assessment/Presentation/Views/assessments',
            [
                'pageTitle' => 'Assessments',
                'assessments' => $assessments,
            ]
        );
    }

    public function personality(): void
    {
        $this->renderAssessmentPage('personality', 'Personality Assessment', 'Assessment/Presentation/Views/student/personality', 'text-blue-600', 'bg-[#0052ff] hover:bg-blue-700');
    }

    public function interest(): void
    {
        $this->renderAssessmentPage('interest', 'Interest Assessment', 'Assessment/Presentation/Views/student/interest', 'text-pink-600', 'bg-[#ec4899] hover:bg-pink-700');
    }

    public function aptitude(): void
    {
        $this->renderAssessmentPage('aptitude', 'Aptitude Assessment', 'Assessment/Presentation/Views/student/aptitude', 'text-green-600', 'bg-[#16a34a] hover:bg-green-700');
    }

    public function values(): void
    {
        $this->renderAssessmentPage('values', 'Career Values Assessment', 'Assessment/Presentation/Views/student/career-values', 'text-orange-600', 'bg-[#f97316] hover:bg-orange-700');
    }

    public function progress(): void
    {
        $user = $this->getAuthenticatedUser();
        $userId = isset($user['id']) ? (int)$user['id'] : null;

        $this->view(
            'Assessment/Presentation/Views/assessment-progress',
            [
                'pageTitle' => 'Assessment Progress',
                'progress' => $this->assessmentService->getProgress($userId),
                'guestProgress' => $this->assessmentService->getGuestProgress(),
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }

    private function renderAssessmentPage(string $slug, string $pageTitle, string $viewPath, string $accentClass, string $buttonClass): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = isset($user['id']) ? (int)$user['id'] : null;

        $this->assessmentService->startAssessment($slug, $userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answers = isset($_POST['answers']) && is_array($_POST['answers'])
                ? $_POST['answers']
                : [];
            $result = $this->assessmentService->submitAssessment($slug, $answers, $userId, false);

            $_SESSION['success'] = $result['message'] ?? 'Assessment submitted.';
            $nextPage = $result['recommendation'] ? 'recommendation' : 'assessment-progress';
            header('Location: ' . BASE_URL . '/index.php?page=' . $nextPage);
            exit;
        }

        $questions = $this->assessmentService->getAssessmentQuestions($slug);

        $this->view(
            $viewPath,
            [
                'pageTitle' => $pageTitle,
                'questions' => $questions,
                'guestMode' => false,
                'guestProgress' => [],
                'accentClass' => $accentClass,
                'buttonClass' => $buttonClass,
                'user' => $user,
                'layout' => 'dashboard',
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
