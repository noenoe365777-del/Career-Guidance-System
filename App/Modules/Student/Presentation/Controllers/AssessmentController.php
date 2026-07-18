<?php

declare(strict_types=1);

namespace App\Modules\Student\Presentation\Controllers;

use App\Modules\Assessment\Application\Services\AssessmentResultService;
use App\Modules\Assessment\Application\Services\AssessmentService;
use App\Modules\Assessment\Infrastructure\Persistence\AssessmentEngineRepository;
use App\Modules\Assessment\Infrastructure\Persistence\NewAssessmentRepository;
use App\Modules\Assessment\Infrastructure\Persistence\QuestionRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAnswerRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;
use App\Modules\Recommendation\Application\Services\RecommendationService;
use App\Shared\Core\Controller;

class AssessmentController extends Controller
{
    private AssessmentService $assessmentService;
    private AssessmentResultService $resultService;
    private StudentAssessmentRepository $studentAssessmentRepository;
    private StudentAnswerRepository $studentAnswerRepository;
    private QuestionRepository $questionRepository;
    private AssessmentEngineRepository $engineRepo;
    private NewAssessmentRepository $newRepo;

    public function __construct()
    {
        $this->assessmentService = new AssessmentService();
        $this->resultService = new AssessmentResultService();
        $this->studentAssessmentRepository = new StudentAssessmentRepository();
        $this->studentAnswerRepository = new StudentAnswerRepository();
        $this->questionRepository = new QuestionRepository();
        $this->engineRepo = new AssessmentEngineRepository();
        $this->newRepo = new NewAssessmentRepository();
    }

    public function index(): void
    {
        header('Location: ' . BASE_URL . '/index.php?page=student-assessments-v2');
        exit;
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
            $this->redirectTo('student-assessments-v2');
        }

        $result = $this->resultService->getResult($userId, $slug);
        if (!$result) {
            $this->redirectTo('student-assessments-v2');
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
            $this->redirectTo('student-assessments-v2');
        }

        $result = $this->resultService->getResult($userId, $slug);
        if (!$result) {
            $this->redirectTo('student-assessments-v2');
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
            $this->redirectTo('student-assessments-v2');
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
                'backToPage' => 'student-assessments-v2',
            ]
        );
    }

    public function apiStart(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $assessmentId = (int)($input['assessment_id'] ?? 0);

        if ($assessmentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid assessment ID']);
            exit;
        }

        $assessment = $this->engineRepo->getAssessmentById($assessmentId);
        if (!$assessment) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Assessment not found']);
            exit;
        }

        $attempt = $this->engineRepo->getOrCreateAttempt($userId, $assessmentId);

        $totalQ = $this->engineRepo->countAssessmentQuestions($assessmentId);
        $randomMode = (bool)($assessment['random_mode'] ?? false);
        $questions = $this->engineRepo->getQuestions($assessmentId, $randomMode, $totalQ);

        $questionIds = array_map(fn($q) => (int)$q['question_id'], $questions);
        $_SESSION['assessment_q_order_' . $attempt['student_assessment_id']] = $questionIds;

        $currentIdx = (int)$attempt['current_question'];
        if ($currentIdx >= count($questionIds)) {
            $currentIdx = 0;
        }

        $nextQId = $questionIds[$currentIdx] ?? null;

        echo json_encode([
            'success' => true,
            'attempt_id' => (int)$attempt['student_assessment_id'],
            'assessment' => [
                'id' => $assessmentId,
                'name' => $assessment['title'],
                'icon' => $assessment['icon'] ?? 'bi-collection',
                'time_limit' => (int)$assessment['time_limit'],
            ],
            'total_questions' => count($questionIds),
            'current_index' => $currentIdx,
            'started_at' => $attempt['started_at'],
        ]);
        exit;
    }

    public function apiQuestion(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $attemptId = (int)($_GET['attempt_id'] ?? 0);
        $index = (int)($_GET['index'] ?? 0);

        if ($attemptId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid attempt']);
            exit;
        }

        $attempt = $this->engineRepo->getAttemptById($attemptId);
        if (!$attempt || (int)$attempt['user_id'] !== $userId) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        }

        $questionIds = $_SESSION['assessment_q_order_' . $attemptId] ?? [];
        if (empty($questionIds)) {
            $assessment = $this->engineRepo->getAssessmentById((int)$attempt['assessment_id']);
            $totalQ = $this->engineRepo->countAssessmentQuestions((int)$attempt['assessment_id']);
            $randomMode = (bool)($assessment['random_mode'] ?? false);
            $questions = $this->engineRepo->getQuestions((int)$attempt['assessment_id'], $randomMode, $totalQ);
            $questionIds = array_map(fn($q) => (int)$q['question_id'], $questions);
            $_SESSION['assessment_q_order_' . $attemptId] = $questionIds;
        }

        if ($index < 0 || $index >= count($questionIds)) {
            echo json_encode(['success' => false, 'done' => true, 'message' => 'Assessment complete']);
            exit;
        }

        $qId = $questionIds[$index];
        $question = $this->engineRepo->getQuestionById($qId);
        if (!$question) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Question not found']);
            exit;
        }

        $options = $this->engineRepo->getOptionsForQuestion($qId);
        $existingAnswer = $this->engineRepo->getAnswerForQuestion($attemptId, $qId);

        $hasPrev = $index > 0;
        $hasNext = $index < count($questionIds) - 1;
        $isLast = !$hasNext;

        $answeredCount = $this->engineRepo->getAnsweredCount($attemptId);

        echo json_encode([
            'success' => true,
            'question' => [
                'id' => (int)$question['question_id'],
                'number' => $index + 1,
                'text' => $question['question_text'],
                'type' => $question['question_type'],
            ],
            'options' => array_map(fn($o) => [
                'id' => (int)$o['option_id'],
                'text' => $o['option_text'],
                'value' => (float)($o['option_value'] ?? 0),
            ], $options),
            'selected_option_id' => $existingAnswer ? (int)$existingAnswer['option_id'] : null,
            'progress' => [
                'current' => $index + 1,
                'total' => count($questionIds),
                'answered' => $answeredCount,
                'percent' => count($questionIds) > 0 ? round((($index + 1) / count($questionIds)) * 100) : 0,
            ],
            'navigation' => [
                'has_prev' => $hasPrev,
                'has_next' => $hasNext,
                'is_last' => $isLast,
            ],
        ]);
        exit;
    }

    public function apiSaveAnswer(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $attemptId = (int)($input['attempt_id'] ?? 0);
        $questionId = (int)($input['question_id'] ?? 0);
        $optionId = (int)($input['option_id'] ?? 0);

        if ($attemptId <= 0 || $questionId <= 0 || $optionId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $attempt = $this->engineRepo->getAttemptById($attemptId);
        if (!$attempt || (int)$attempt['user_id'] !== $userId) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        }

        $stmt = \App\Config\Database::getConnection()->prepare("SELECT option_a, option_b, option_c, option_d, correct_answer FROM assessment_questions WHERE id = :qid");
        $stmt->execute([':qid' => $questionId]);
        $q = $stmt->fetch();
        $score = 0.0;
        if ($q) {
            $position = 0;
            $cols = ['option_a', 'option_b', 'option_c', 'option_d'];
            $letters = ['A', 'B', 'C', 'D'];
            for ($i = 0; $i < count($cols); $i++) {
                if (($q[$cols[$i]] ?? null) !== null && ($q[$cols[$i]] ?? '') !== '') {
                    $position++;
                }
                if ($position === $optionId) {
                    $score = (float)$position;
                    if ($q['correct_answer'] !== null && $letters[$i] === $q['correct_answer']) {
                        $score = 5.0;
                    }
                    break;
                }
            }
        }

        $this->engineRepo->saveAnswer($attemptId, $questionId, $optionId, $score);

        $questionIds = $_SESSION['assessment_q_order_' . $attemptId] ?? [];
        $currentIdx = array_search($questionId, $questionIds);
        $answeredCount = $this->engineRepo->getAnsweredCount($attemptId);
        $totalQ = count($questionIds);

        $progress = $totalQ > 0 ? round(($answeredCount / $totalQ) * 100, 2) : 0;
        $nextIdx = $currentIdx !== false ? $currentIdx + 1 : $answeredCount;

        $this->engineRepo->updateAttempt($attemptId, [
            'current_question' => $nextIdx,
            'progress' => $progress,
        ]);

        echo json_encode([
            'success' => true,
            'answered_count' => $answeredCount,
            'progress' => $progress,
        ]);
        exit;
    }

    public function apiFinish(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $attemptId = (int)($input['attempt_id'] ?? 0);

        if ($attemptId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid attempt']);
            exit;
        }

        $attempt = $this->engineRepo->getAttemptById($attemptId);
        if (!$attempt || (int)$attempt['user_id'] !== $userId) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        }

        if ($attempt['status'] === 'completed') {
            echo json_encode(['success' => false, 'message' => 'Already completed']);
            exit;
        }

        $result = $this->engineRepo->completeAttempt($attemptId);

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Failed to complete']);
            exit;
        }

        unset($_SESSION['assessment_q_order_' . $attemptId]);

        $timeTaken = '';
        if ($attempt['started_at']) {
            $start = strtotime($attempt['started_at']);
            $end = strtotime($result['completed_at']);
            $diff = $end - $start;
            $minutes = floor($diff / 60);
            $seconds = $diff % 60;
            $timeTaken = ($minutes > 0 ? $minutes . ' min ' : '') . $seconds . ' sec';
        }

        echo json_encode([
            'success' => true,
            'score' => $result['score'],
            'answered' => $result['answered'],
            'total' => $result['total'],
            'time_taken' => $timeTaken,
            'assessment_name' => $attempt['assessment_name'],
        ]);
        exit;
    }

    public function v2Index(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);

        $assessments = $this->newRepo->getAssessmentConfig();
        $results = $this->newRepo->getResultsForUser($userId);
        $allDone = $this->newRepo->allAssessmentsCompleted($userId);

        $educationLevel = $this->getUserEducationLevel($userId);

        $this->view(
            'Assessment/Presentation/Views/student/assessment_v2_dashboard',
            [
                'pageTitle' => 'Assessments',
                'assessments' => $assessments,
                'results' => $results,
                'allDone' => $allDone,
                'user' => $user,
                'educationLevel' => $educationLevel,
                'layout' => 'dashboard',
            ]
        );
    }

    private function getUserEducationLevel(int $userId): string
    {
        try {
            $stmt = \App\Config\Database::getConnection()->prepare(
                "SELECT education_level FROM users WHERE id = :uid LIMIT 1"
            );
            $stmt->execute([':uid' => $userId]);
            $level = $stmt->fetchColumn();
            return (string)($level ?? '');
        } catch (\Throwable $e) {
            error_log("Failed to fetch education level for user $userId: " . $e->getMessage());
            return '';
        }
    }

    public function v2ApiStart(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $assessmentId = (int)($input['assessment_id'] ?? 0);

        if ($assessmentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid assessment']);
            exit;
        }

        $result = $this->newRepo->getOrCreateResult($userId, $assessmentId);

        if ($result['status'] === 'completed') {
            echo json_encode(['success' => false, 'message' => 'Already completed', 'redirect' => true]);
            exit;
        }

        $limits = [1 => 8, 2 => 8, 3 => 10, 4 => 6];
        $limit = $limits[$assessmentId] ?? 8;

        $questions = $this->newRepo->getQuestions($assessmentId, $limit);
        $ids = array_map(fn($q) => (int)$q['id'], $questions);
        $_SESSION['v2_q_order_' . $result['id']] = $ids;

        $answered = $this->newRepo->getAnsweredCount($userId, $assessmentId);

        echo json_encode([
            'success' => true,
            'result_id' => (int)$result['id'],
            'assessment_id' => $assessmentId,
            'total_questions' => count($questions),
            'answered' => $answered,
            'started_at' => $result['started_at'],
        ]);
        exit;
    }

    public function v2ApiQuestion(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $resultId = (int)($_GET['result_id'] ?? 0);
        $index = (int)($_GET['index'] ?? 0);

        if ($resultId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid result']);
            exit;
        }

        $ids = $_SESSION['v2_q_order_' . $resultId] ?? [];
        if ($index < 0 || $index >= count($ids)) {
            echo json_encode(['success' => false, 'done' => true]);
            exit;
        }

        $question = $this->newRepo->getQuestionById((int)$ids[$index]);
        if (!$question) {
            http_response_code(404);
            echo json_encode(['success' => false]);
            exit;
        }

        $existingAnswer = null;
        $stmt = \App\Config\Database::getConnection()->prepare("SELECT selected_answer FROM answers WHERE user_id = :uid AND question_id = :qid");
        $stmt->execute([':uid' => $userId, ':qid' => $question['id']]);
        $row = $stmt->fetch();
        if ($row) $existingAnswer = $row['selected_answer'];

        $answered = $this->newRepo->getAnsweredCount($userId, (int)$question['assessment_id']);

        echo json_encode([
            'success' => true,
            'question' => [
                'id' => (int)$question['id'],
                'number' => $index + 1,
                'text' => $question['question'],
                'options' => [
                    ['key' => 'A', 'text' => $question['option_a']],
                    ['key' => 'B', 'text' => $question['option_b']],
                    ['key' => 'C', 'text' => $question['option_c']],
                    ['key' => 'D', 'text' => $question['option_d']],
                ],
            ],
            'selected' => $existingAnswer,
            'progress' => [
                'current' => $index + 1,
                'total' => count($ids),
                'answered' => $answered,
            ],
            'navigation' => [
                'has_prev' => $index > 0,
                'is_last' => $index >= count($ids) - 1,
            ],
        ]);
        exit;
    }

    public function v2ApiSave(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $questionId = (int)($input['question_id'] ?? 0);
        $answer = strtoupper(trim((string)($input['answer'] ?? '')));

        if ($questionId <= 0 || !in_array($answer, ['A','B','C','D'], true)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        $question = $this->newRepo->getQuestionById($questionId);
        if (!$question) {
            http_response_code(404);
            echo json_encode(['success' => false]);
            exit;
        }

        $score = 0.0;
        $optionVals = ['A' => 1.0, 'B' => 2.0, 'C' => 3.0, 'D' => 4.0];
        if (isset($optionVals[$answer])) {
            $score = $optionVals[$answer];
        }
        if ($question['correct_answer'] !== null && $answer === $question['correct_answer']) {
            $score = (float)($question['weight'] ?? 1.0);
        } elseif ($question['correct_answer'] !== null) {
            $score = 0.0;
        }

        $this->newRepo->saveAnswer($userId, $questionId, $answer, $score);

        echo json_encode(['success' => true]);
        exit;
    }

    public function v2ApiFinish(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $assessmentId = (int)($input['assessment_id'] ?? 0);

        if ($assessmentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid assessment']);
            exit;
        }

        $result = $this->newRepo->getResult($userId, $assessmentId);
        if (!$result || $result['status'] === 'completed') {
            echo json_encode(['success' => false, 'message' => 'Already completed']);
            exit;
        }

        $data = $this->newRepo->completeAssessment($userId, $assessmentId);

        $names = [1 => 'Personality', 2 => 'Interest', 3 => 'Aptitude', 4 => 'Career Values'];
        $allDone = $this->newRepo->allAssessmentsCompleted($userId);

        if ($allDone) {
            $recService = new RecommendationService();
            $recService->generateForUser($userId);
        }

        echo json_encode([
            'success' => true,
            'percentage' => $data['percentage'],
            'answered' => $data['answered'],
            'assessment_name' => $names[$assessmentId] ?? 'Assessment',
            'all_completed' => $allDone,
        ]);
        exit;
    }

    public function v2Result(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);

        $assessmentId = (int)($_GET['id'] ?? 0);
        if ($assessmentId <= 0) {
            $this->redirectTo('student-assessments-v2');
        }

        $result = $this->newRepo->getAssessmentResult($userId, $assessmentId);
        if (!$result) {
            $this->redirectTo('student-assessments-v2');
        }

        $answers = $this->newRepo->getStudentAnswers($userId, $assessmentId);
        $totalQuestions = $this->newRepo->getTotalQuestionsForAssessment($assessmentId);
        $answeredCount = count($answers);
        $correctCount = $this->newRepo->getCorrectAnswerCount($userId, $assessmentId);
        $percentage = (float)$result['percentage'];
        $assessmentType = $this->newRepo->getAssessmentTypeLabel($assessmentId);

        $scoreData = [
            'total' => $totalQuestions,
            'answered' => $answeredCount,
            'correct' => $correctCount,
            'percentage' => $percentage,
            'score' => (float)$result['score'],
        ];

        $studentScores = $this->getStudentScoresRow($userId);
        $summary = $this->getAssessmentSummary($assessmentId, $percentage, $studentScores);

        $this->view(
            'Assessment/Presentation/Views/student/assessment_v2_result',
            [
                'pageTitle' => $result['assessment_name'] . ' - Result',
                'user' => $user,
                'layout' => 'dashboard',
                'assessment' => $result,
                'answers' => $answers,
                'scoreData' => $scoreData,
                'assessmentType' => $assessmentType,
                'summary' => $summary,
            ]
        );
    }

    public function v2CompletionPage(): void
    {
        $user = $this->requireAuthenticatedUser();
        $userId = (int)($user['id'] ?? 0);

        $this->view(
            'Assessment/Presentation/Views/student/assessment_complete',
            [
                'pageTitle' => 'Assessment Completed',
                'user' => $user,
                'layout' => 'dashboard',
            ]
        );
    }

    private function getStudentScoresRow(int $userId): array
    {
        $stmt = \App\Config\Database::getConnection()->prepare(
            "SELECT personality_type, interest_type, aptitude_type, values_type,
                    personality_score, interest_score, aptitude_score, values_score
             FROM student_assessment_scores WHERE student_id = :sid LIMIT 1"
        );
        $stmt->execute([':sid' => $userId]);
        return $stmt->fetch() ?: [];
    }

    private function getAssessmentSummary(int $assessmentId, float $percentage, array $scores): array
    {
        $summaries = [
            1 => [
                'title' => 'Personality Type',
                'type' => $scores['personality_type'] ?? ($percentage >= 80 ? 'Extrovert' : ($percentage >= 60 ? 'Ambivert' : 'Introvert')),
                'descriptions' => [
                    'Extrovert' => 'You thrive in social settings, enjoy teamwork, and are energized by interacting with others. You prefer active, people-oriented environments.',
                    'Ambivert' => 'You have a balanced personality with both introverted and extroverted traits. You adapt well to different social situations and work styles.',
                    'Introvert' => 'You prefer quiet environments, deep focus, and work well independently. You value meaningful one-on-one interactions over large groups.',
                ],
            ],
            2 => [
                'title' => 'Interest Category',
                'type' => $scores['interest_type'] ?? ($percentage >= 80 ? 'Creative / Investigative' : ($percentage >= 60 ? 'Balanced' : 'Practical')),
                'descriptions' => [
                    'Creative / Investigative' => 'You enjoy exploring new ideas, solving complex problems, and expressing yourself creatively. Careers involving innovation and discovery suit you well.',
                    'Balanced' => 'You have a versatile range of interests. You can adapt to various types of work and environments, making you flexible in your career path.',
                    'Practical' => 'You prefer hands-on work, structured environments, and tasks with clear outcomes. You value stability and tangible results.',
                ],
            ],
            3 => [
                'title' => 'Aptitude Level',
                'type' => $scores['aptitude_type'] ?? ($percentage >= 70 ? 'Advanced' : ($percentage >= 50 ? 'Competent' : 'Beginner')),
                'descriptions' => [
                    'Advanced' => 'Your problem-solving and analytical skills are highly developed. You excel at logical reasoning, numerical analysis, and critical thinking.',
                    'Competent' => 'You have solid analytical and reasoning skills. With continued practice, you can further strengthen your problem-solving abilities.',
                    'Beginner' => 'You are developing your aptitude skills. Focus on practicing logical puzzles, numerical exercises, and critical thinking to build your abilities.',
                ],
            ],
            4 => [
                'title' => 'Values Clarity',
                'type' => $scores['values_type'] ?? ($percentage >= 75 ? 'Defined' : ($percentage >= 50 ? 'Developing' : 'Undefined')),
                'descriptions' => [
                    'Defined' => 'You have clear career values and know what matters to you in a workplace. This clarity helps guide your career decisions effectively.',
                    'Developing' => 'You are developing your career values. Continue exploring what matters most to you in your professional life through experiences.',
                    'Undefined' => 'Your career values are still taking shape. This is a great time to reflect on what you value and explore different work environments.',
                ],
            ],
        ];

        $data = $summaries[$assessmentId] ?? [
            'title' => 'Summary',
            'type' => $percentage >= 70 ? 'Strong' : ($percentage >= 50 ? 'Moderate' : 'Developing'),
            'descriptions' => [
                'Strong' => 'You performed well in this assessment area.',
                'Moderate' => 'You have a solid foundation in this area with room to grow.',
                'Developing' => 'This area offers opportunities for development and growth.',
            ],
        ];

        $typeLabel = $data['type'];
        $desc = $data['descriptions'][$typeLabel] ?? 'Your results reflect your current standing in this assessment area.';

        return [
            'title' => $data['title'],
            'value' => $typeLabel,
            'description' => $desc,
        ];
    }

}
