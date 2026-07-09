<?php

declare(strict_types=1);

namespace App\Modules\Public\Presentation\Controllers;

use App\Modules\Assessment\Application\Services\AssessmentService;
use App\Shared\Core\Controller;

class AssessmentController extends Controller
{
    private AssessmentService $assessmentService;

    public function __construct()
    {
        $this->assessmentService = new AssessmentService();
    }

    public function index(): void
    {
        $isLoggedIn = !empty($_SESSION['user_id']);

        if ($isLoggedIn) {
            $user = $this->getAuthenticatedUser();
            $userId = isset($user['id']) ? (int)$user['id'] : null;
        } else {
            $user = null;
            $userId = null;
        }

        $assessments = $this->assessmentService->getAssessments($userId);

        if (!$isLoggedIn) {
            foreach ($assessments as &$a) {
                $a['page'] = 'guest-' . $a['slug'];
            }
            unset($a);
        }

        $this->view(
            'Assessment/Presentation/Views/assessments',
            [
                'pageTitle' => 'Assessments',
                'assessments' => $assessments,
                'isLoggedIn' => $isLoggedIn,
                'user' => $user,
            ]
        );
    }

    public function personality(): void
    {
        $this->renderGuestAssessment('personality', 'Personality Assessment', 'Assessment/Presentation/Views/student/personality', 'text-blue-600', 'bg-[#0052ff] hover:bg-blue-700');
    }

    public function interest(): void
    {
        $this->renderGuestAssessment('interest', 'Interest Assessment', 'Assessment/Presentation/Views/student/interest', 'text-pink-600', 'bg-[#ec4899] hover:bg-pink-700');
    }

    public function aptitude(): void
    {
        $this->renderGuestAssessment('aptitude', 'Aptitude Assessment', 'Assessment/Presentation/Views/student/aptitude', 'text-green-600', 'bg-[#16a34a] hover:bg-green-700');
    }

    public function values(): void
    {
        $this->renderGuestAssessment('values', 'Career Values Assessment', 'Assessment/Presentation/Views/student/career-values', 'text-orange-600', 'bg-[#f97316] hover:bg-orange-700');
    }

    public function guestResult(): void
    {
        $this->view(
            'Assessment/Presentation/Views/public/guest-result',
            [
                'pageTitle' => 'Assessment Complete',
            ]
        );
    }

    private function renderGuestAssessment(string $slug, string $pageTitle, string $viewPath, string $accentClass, string $buttonClass): void
    {
        $this->assessmentService->startAssessment($slug, null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answers = isset($_POST['answers']) && is_array($_POST['answers'])
                ? $_POST['answers']
                : [];
            $result = $this->assessmentService->submitAssessment($slug, $answers, null, true);

            $_SESSION['guest_result'] = [
                'slug' => $slug,
                'title' => $pageTitle,
                'score' => $result['score'] ?? 0,
                'summary' => $result['summary'] ?? '',
            ];

            header('Location: ' . BASE_URL . '/index.php?page=guest-result');
            exit;
        }

        $questions = $this->assessmentService->getAssessmentQuestions($slug);

        $this->view(
            $viewPath,
            [
                'pageTitle' => $pageTitle,
                'questions' => $questions,
                'guestMode' => true,
                'guestProgress' => [],
                'accentClass' => $accentClass,
                'buttonClass' => $buttonClass,
                'user' => null,
                'backToPage' => 'assessments',
            ]
        );
    }
}
