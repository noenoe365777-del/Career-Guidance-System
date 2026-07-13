<?php

declare(strict_types=1);

namespace App\Modules\Student\Presentation\Controllers;

use App\Modules\Assessment\Application\Services\AssessmentResultService;
use App\Modules\Assessment\Application\Services\AssessmentService;
use App\Modules\Assessment\Infrastructure\Persistence\QuestionRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAnswerRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;
use App\Shared\Core\Controller;

class AssessmentController extends Controller
{
    private AssessmentService $assessmentService;
    private AssessmentResultService $resultService;
    private StudentAssessmentRepository $studentAssessmentRepository;
    private StudentAnswerRepository $studentAnswerRepository;
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->assessmentService = new AssessmentService();
        $this->resultService = new AssessmentResultService();
        $this->studentAssessmentRepository = new StudentAssessmentRepository();
        $this->studentAnswerRepository = new StudentAnswerRepository();
        $this->questionRepository = new QuestionRepository();
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

    public function personality(): void
    {
        $this->renderStudentAssessment('personality', 'Personality Assessment', 'Assessment/Presentation/Views/student/personality', 'text-blue-600', 'bg-[#0052ff] hover:bg-blue-700');
    }

    public function interest(): void
    {
        $this->renderStudentAssessment('interest', 'Interest Assessment', 'Assessment/Presentation/Views/student/interest', 'text-pink-600', 'bg-[#ec4899] hover:bg-pink-700');
    }

    public function aptitude(): void
    {
        $this->renderStudentAssessment('aptitude', 'Aptitude Assessment', 'Assessment/Presentation/Views/student/aptitude', 'text-green-600', 'bg-[#16a34a] hover:bg-green-700');
    }

    public function values(): void
    {
        $this->renderStudentAssessment('values', 'Career Values Assessment', 'Assessment/Presentation/Views/student/career-values', 'text-orange-600', 'bg-[#f97316] hover:bg-orange-700');
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

    public function viewResult(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        $slug = $_GET['slug'] ?? '';

        if ($slug === '') {
            $this->redirectTo('student-assessments');
        }

        $result = $this->resultService->getResult($userId, $slug);
        if (!$result) {
            $this->redirectTo('student-assessments');
        }

        $this->view(
            'Assessment/Presentation/Views/student/assessment-result',
            [
                'pageTitle' => $result['assessment']['title'] . ' - Results',
                'result' => $result,
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }

    public function detailedAnswers(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        $slug = $_GET['slug'] ?? '';

        if ($slug === '') {
            $this->redirectTo('student-assessments');
        }

        $result = $this->resultService->getResult($userId, $slug);
        if (!$result) {
            $this->redirectTo('student-assessments');
        }

        $questions = $this->questionRepository->getQuestionsBySlug($slug);
        $answers = $this->studentAnswerRepository->getAnswers((int)$result['attempt']['id']);

        $questionsById = [];
        foreach ($questions as $q) {
            $questionsById[(int)$q['id']] = $q['question'];
        }

        $answerLabels = [1 => 'Strongly Disagree', 2 => 'Disagree', 3 => 'Neutral', 4 => 'Agree', 5 => 'Strongly Agree'];

        $detailed = [];
        foreach ($answers as $a) {
            $qId = (int)($a['question_id'] ?? 0);
            $val = (int)($a['answer_value'] ?? $a['score'] ?? 0);
            $detailed[] = [
                'question' => $questionsById[$qId] ?? 'Question #' . $qId,
                'answer_value' => $val,
                'answer_label' => $answerLabels[$val] ?? 'Unknown',
            ];
        }

        $this->view(
            'Assessment/Presentation/Views/student/assessment-detailed-answers',
            [
                'pageTitle' => $result['assessment']['title'] . ' - Detailed Answers',
                'assessment' => $result['assessment'],
                'attempt' => $result['attempt'],
                'detailed' => $detailed,
                'score' => $result['score'],
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }

    private function renderStudentAssessment(string $slug, string $pageTitle, string $viewPath, string $accentClass, string $buttonClass): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = isset($user['id']) ? (int)$user['id'] : null;

        $assessmentData = $this->assessmentService->startAssessment($slug, $userId);
        if (!$assessmentData['success']) {
            $this->redirectTo('student-assessments');
            return;
        }

        $assessment = $assessmentData['assessment'];
        $existing = $this->studentAssessmentRepository->findForUser($userId, (int)$assessment['id']);
        if ($existing && $existing['status'] === 'completed') {
            $this->redirectTo('assessment-result', ['slug' => $slug]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answers = isset($_POST['answers']) && is_array($_POST['answers'])
                ? $_POST['answers']
                : [];
            $result = $this->assessmentService->submitAssessment($slug, $answers, $userId, false);

            $_SESSION['success'] = $result['message'] ?? 'Assessment submitted.';
            $nextPage = $result['recommendation'] ? 'recommendation' : 'assessment-result&slug=' . $slug;
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
                'backToPage' => 'student-assessments',
            ]
        );
    }
}
