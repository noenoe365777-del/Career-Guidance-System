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
        $this->renderGuestAssessment('personality', 'Personality Assessment', 'text-blue-600', 'bg-[#0052ff] hover:bg-blue-700');
    }

    public function interest(): void
    {
        $this->renderGuestAssessment('interest', 'Interest Assessment', 'text-pink-600', 'bg-[#ec4899] hover:bg-pink-700');
    }

    public function aptitude(): void
    {
        $this->renderGuestAssessment('aptitude', 'Aptitude Assessment', 'text-green-600', 'bg-[#16a34a] hover:bg-green-700');
    }

    public function values(): void
    {
        $this->renderGuestAssessment('values', 'Career Values Assessment', 'text-orange-600', 'bg-[#f97316] hover:bg-orange-700');
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

    private function renderGuestAssessment(string $slug, string $pageTitle, string $accentClass, string $buttonClass): void
    {
        $result = $this->assessmentService->startAssessment($slug, null);

        if (!$result['success']) {
            header('Location: ' . BASE_URL . '/index.php?page=assessments');
            exit;
        }

        $assessment = $result['assessment'];

        $_SESSION['guest_assessment'] = [
            'assessment_id' => (int)$assessment['id'],
            'slug' => $slug,
            'title' => $pageTitle,
            'answers' => [],
            'current' => 0,
        ];

        $this->view(
            'Assessment/Presentation/Views/public/guest-question',
            [
                'pageTitle' => $pageTitle,
                'accentClass' => $accentClass,
                'buttonClass' => $buttonClass,
            ]
        );
    }

public function apiStart(): void
{
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);

    $assessmentId = (int)($input['assessment_id'] ?? 0);

    if ($assessmentId <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid assessment.'
        ]);
        return;
    }

    $_SESSION['guest_assessment'] = [
        'assessment_id' => $assessmentId,
        'slug' => '',
        'title' => 'Assessment',
        'answers' => [],
        'current' => 0
    ];

    echo json_encode([
        'success' => true,
        'result_id' => 1,
        'total_questions' => 5,
        'answered' => 0
    ]);
}
public function apiQuestion(): void
{
    header('Content-Type: application/json');

    $index = (int)($_GET['index'] ?? 0);

    if (!isset($_SESSION['guest_assessment'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Assessment not started.'
        ]);
        exit;
    }

    $assessmentId = $_SESSION['guest_assessment']['assessment_id'];

    // Get ONLY first 5 questions
    $questions = $this->assessmentService->getAssessmentQuestionsByAssessmentId($assessmentId, 5);

    if ($index < 0 || $index >= count($questions)) {
        echo json_encode([
            'success' => false,
            'done' => true
        ]);
        exit;
    }

    $question = $questions[$index];

    $selected = $_SESSION['guest_assessment']['answers'][$question['id']] ?? null;

    echo json_encode([
        'success' => true,

        'question' => [
            'id' => (int)$question['id'],
            'number' => $index + 1,
            'text' => $question['question'],

           'options' => array_map(function($option){

    return [
        'value' => $option['value'],
        'label' => $option['label']
    ];

}, $question['options']),
        ],

        'selected' => $selected,

        'progress' => [
            'current' => $index + 1,
            'total' => count($questions),
            'answered' => count($_SESSION['guest_assessment']['answers'])
        ],

        'navigation' => [
            'has_prev' => $index > 0,
            'is_last' => $index == count($questions) - 1
        ]
    ]);

    exit;
}

public function apiSave(): void
{
    header('Content-Type: application/json');

    if (!isset($_SESSION['guest_assessment'])) {
        echo json_encode([
            'success' => false
        ]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $answer = $input['answer'] ?? null;

    $current = $_SESSION['guest_assessment']['current'];

    $assessmentId = $_SESSION['guest_assessment']['assessment_id'];

    $questions = $this->assessmentService
        ->getAssessmentQuestionsByAssessmentId($assessmentId, 5);

    if (!isset($questions[$current])) {

        echo json_encode([
            'success' => false
        ]);

        exit;
    }

    $questionId = $questions[$current]['id'];

    $_SESSION['guest_assessment']['answers'][$questionId] = $answer;

    $_SESSION['guest_assessment']['current']++;

    echo json_encode([
        'success' => true
    ]);

    exit;
}
public function apiFinish(): void
{
    header('Content-Type: application/json');

    if (!isset($_SESSION['guest_assessment'])) {
        echo json_encode([
            'success' => false
        ]);
        exit;
    }

    $assessment = $_SESSION['guest_assessment'];

    $score = count($assessment['answers']) * 20; // 5 questions = 100%

    $_SESSION['guest_result'] = [
        'assessment_id' => $assessment['assessment_id'],
        'slug' => $assessment['slug'] ?? '',
        'title' => $assessment['title'] ?? 'Assessment',
        'score' => $score,
        'answers' => $assessment['answers']
    ];

    unset($_SESSION['guest_assessment']);

    echo json_encode([
        'success' => true,
        'redirect' => BASE_URL . '/index.php?page=guest-result'
    ]);

    exit;
}
}
