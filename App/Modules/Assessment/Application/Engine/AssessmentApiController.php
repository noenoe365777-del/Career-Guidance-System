<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Engine;

use App\Modules\Assessment\Application\Storage\DatabaseStorage;
use App\Modules\Assessment\Application\Storage\SessionStorage;
use App\Modules\Assessment\Infrastructure\Persistence\AssessmentEngineRepository;
use App\Shared\Auth\Auth;
use App\Shared\Core\Controller;
use App\Shared\Core\View;

class AssessmentApiController extends Controller
{
    private AssessmentEngine $engine;

    public function __construct()
    {
        $repo = new AssessmentEngineRepository();
        $storage = Auth::check() ? new DatabaseStorage($repo) : new SessionStorage();
        $this->engine = new AssessmentEngine(new AssessmentEngineRepository(), $storage);
    }

    public function apiStart(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $assessmentId = (int)($input['assessment_id'] ?? $input['assessmentId'] ?? 0);

        if ($assessmentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid assessment ID']);
            exit;
        }

        $userId = Auth::id() ?? 0;
        $result = $this->engine->startAssessment($userId, $assessmentId);

        echo json_encode($result);
        exit;
    }

    public function apiQuestion(): void
    {
        header('Content-Type: application/json');

        $attemptId = (int)($_GET['attempt_id'] ?? $_GET['attemptId'] ?? 0);
        $index = (int)($_GET['index'] ?? 0);

        if ($attemptId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid attempt']);
            exit;
        }

        $result = $this->engine->getQuestion(Auth::id() ?? 0, $attemptId, $index);

        echo json_encode($result);
        exit;
    }

    public function apiSave(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $attemptId = (int)($input['attempt_id'] ?? $input['attemptId'] ?? 0);
        $questionId = (int)($input['question_id'] ?? $input['questionId'] ?? 0);
        $optionId = (int)($input['option_id'] ?? $input['optionId'] ?? 0);

        if ($attemptId <= 0 || $questionId <= 0 || $optionId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $result = $this->engine->saveAnswer(Auth::id() ?? 0, $attemptId, $questionId, $optionId);

        echo json_encode($result);
        exit;
    }

    public function apiFinish(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $attemptId = (int)($input['attempt_id'] ?? $input['attemptId'] ?? 0);

        if ($attemptId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid attempt']);
            exit;
        }

        $userId = Auth::id() ?? 0;
        $result = $this->engine->finishAssessment($userId, $attemptId);

        if ($userId > 0 && !empty($result['success'])) {
            try {
                $pdo = \App\Config\Database::getConnection();
                $stmt = $pdo->prepare("
                    SELECT u.username, a.title
                    FROM student_assessments sa
                    JOIN users u ON u.user_id = sa.user_id
                    JOIN assessments a ON a.assessment_id = sa.assessment_id
                    WHERE sa.student_assessment_id = :id
                ");
                $stmt->execute([':id' => $attemptId]);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($row) {
                    $score = (int)($result['score'] ?? 0);
                    \App\Shared\NotificationHelper::assessmentCompleted(
                        (string)($row['username'] ?? 'Student'),
                        (string)($row['title'] ?? 'Assessment'),
                        $score
                    );
                }
            } catch (\Throwable) {
            }
        }

        echo json_encode($result);
        exit;
    }

    public function apiExit(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $attemptId = (int)($input['attempt_id'] ?? $input['attemptId'] ?? 0);

        if ($attemptId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid attempt']);
            exit;
        }

        $result = $this->engine->exitAssessment(Auth::id() ?? 0, $attemptId);

        echo json_encode($result);
        exit;
    }

    public function guestResult(): void
    {
        $result = $_SESSION['guest_result'] ?? null;
        unset($_SESSION['guest_result']);

        View::render('Assessment/Presentation/Views/public/guest-result', [
            'pageTitle' => 'Assessment Complete',
            'result' => $result,
        ]);
    }

    public function question(): void
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/index.php?page=student-assessments-v2');
            exit;
        }

        $assessmentId = (int)($_GET['assessment'] ?? 0);

        if ($assessmentId <= 0) {
            header('Location: ' . BASE_URL . '/index.php?page=assessments');
            exit;
        }

        $assessmentService = new \App\Modules\Assessment\Application\Services\AssessmentService();
        $assessment = $assessmentService->getAssessmentById($assessmentId);

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
            'interest'    => ['accent' => 'text-pink-600', 'button' => 'bg-[#ec4899] hover:bg-pink-700'],
            'aptitude'    => ['accent' => 'text-green-600', 'button' => 'bg-[#16a34a] hover:bg-green-700'],
            'values'      => ['accent' => 'text-orange-600', 'button' => 'bg-[#f97316] hover:bg-orange-700'],
        ];

        $colors = $colorMap[$slug] ?? $colorMap['personality'];

        $_SESSION['guest_assessment'] = [
            'assessment_id' => $assessmentId,
            'slug'          => $slug,
            'title'         => $pageTitle,
            'answers'       => [],
            'current'       => 0,
            'started_at'    => time(),
            'time_limit'    => (int)($assessment['time_limit'] ?? 5),
        ];

        $this->view(
            'Assessment/Presentation/Views/public/guest-question',
            [
                'pageTitle'   => $pageTitle,
                'accentClass' => $colors['accent'],
                'buttonClass' => $colors['button'],
                'assessmentId'=> $assessmentId,
            ]
        );
    }
}