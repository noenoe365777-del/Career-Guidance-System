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
            header('Location: ' . BASE_URL . '/index.php?page=student-assessments-v2');
            exit;
        }

        $user = null;
        $userId = null;

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

    public function question(): void
    {
        $assessmentId = (int)($_GET['assessment'] ?? 0);
        
        if ($assessmentId <= 0) {
            header('Location: ' . BASE_URL . '/index.php?page=assessments');
            exit;
        }

        $assessment = $this->assessmentService->getAssessmentById($assessmentId);

        if (!$assessment) {
            header('Location: ' . BASE_URL . '/index.php?page=assessments');
            exit;
        }

        $slugMap = [
            1 => 'personality',
            2 => 'interest',
            3 => 'aptitude',
            4 => 'values',
        ];

        $slug = $slugMap[$assessmentId] ?? 'assessment';
        $pageTitle = $assessment['title'] ?? 'Assessment';

        $colorMap = [
            'personality' => ['accent' => 'text-blue-600', 'button' => 'bg-[#0052ff] hover:bg-blue-700'],
            'interest' => ['accent' => 'text-pink-600', 'button' => 'bg-[#ec4899] hover:bg-pink-700'],
            'aptitude' => ['accent' => 'text-green-600', 'button' => 'bg-[#16a34a] hover:bg-green-700'],
            'values' => ['accent' => 'text-orange-600', 'button' => 'bg-[#f97316] hover:bg-orange-700'],
        ];

        $colors = $colorMap[$slug] ?? $colorMap['personality'];

        $_SESSION['guest_assessment'] = [
            'assessment_id' => (int)$assessment['id'],
            'slug' => $slug,
            'title' => $pageTitle,
            'answers' => [],
            'current' => 0,
            'started_at' => time(),
            'time_limit' => (int)($assessment['time_limit'] ?? 5),
        ];

        $this->view(
            'Assessment/Presentation/Views/public/guest-question',
            [
                'pageTitle' => $pageTitle,
                'accentClass' => $colors['accent'],
                'buttonClass' => $colors['button'],
                'assessmentId' => $assessmentId,
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

        $assessment = $this->assessmentService->getAssessmentById($assessmentId);

        if (!$assessment) {
            echo json_encode([
                'success' => false,
                'message' => 'Assessment not found.'
            ]);
            return;
        }

        $startedAt = time();

        $_SESSION['guest_assessment'] = [
            'assessment_id' => $assessmentId,
            'slug' => $assessment['slug'] ?? '',
            'title' => $assessment['title'] ?? 'Assessment',
            'answers' => [],
            'current' => 0,
            'started_at' => $startedAt,
            'time_limit' => (int)($assessment['time_limit'] ?? 5),
        ];

        echo json_encode([
            'success' => true,
            'attempt_id' => 1,
            'assessment' => [
                'id' => $assessmentId,
                'name' => $assessment['title'] ?? 'Assessment',
                'time_limit' => (int)($assessment['time_limit'] ?? 5),
            ],
            'total_questions' => 5,
            'current_index' => 0,
            'started_at' => $startedAt,
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
        $timeLimit = (int)($_SESSION['guest_assessment']['time_limit'] ?? 5);
        $startedAt = (int)($_SESSION['guest_assessment']['started_at'] ?? time());

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

        // Calculate remaining time
        $elapsed = time() - $startedAt;
        $timeLimitSeconds = $timeLimit * 60;
        $remaining = max(0, $timeLimitSeconds - $elapsed);

        echo json_encode([
            'success' => true,

            'question' => [
                'id' => (int)$question['id'],
                'number' => $index + 1,
                'text' => $question['question'],
            ],

            'options' => array_map(function($option) {
                return [
                    'id' => (int)$option['id'],
                    'text' => $option['label'],
                    'value' => (float)$option['value'],
                ];
            }, $question['options']),

            'selected_option_id' => $selected !== null ? (int)$selected : null,

            'progress' => [
                'current' => $index + 1,
                'total' => count($questions),
                'answered' => count($_SESSION['guest_assessment']['answers']),
                'percent' => count($questions) > 0 ? round((($index + 1) / count($questions)) * 100) : 0,
            ],

            'navigation' => [
                'has_prev' => $index > 0,
                'is_last' => $index == count($questions) - 1
            ],
            'remaining_time' => $remaining
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

    $optionId = (int)($input['option_id'] ?? 0);
    $questionId = (int)($input['question_id'] ?? 0);

    if ($optionId <= 0 || $questionId <= 0) {
        echo json_encode([
            'success' => false
        ]);
        exit;
    }

    $_SESSION['guest_assessment']['answers'][$questionId] = $optionId;
    $_SESSION['guest_assessment']['current']++;

    echo json_encode([
        'success' => true,
        'answered_count' => count($_SESSION['guest_assessment']['answers'])
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

    $totalAnswered = count($assessment['answers']);
    $title = $assessment['title'] ?? 'Assessment';

    unset($_SESSION['guest_assessment']);

    echo json_encode([
        'success' => true,
        'previewCompleted' => true,
        'message' => 'Preview completed. Register to unlock full results.',
    ]);

exit;
    }
    public function apiExit(): void
    {
        header('Content-Type: application/json');

        if (isset($_SESSION['guest_assessment'])) {
            unset($_SESSION['guest_assessment']);
        }

        echo json_encode([
            'success' => true,
            'redirect' => BASE_URL . '/index.php?page=assessments'
        ]);

        exit;
    }
}
