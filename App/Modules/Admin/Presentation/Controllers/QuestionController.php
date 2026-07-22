<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\QuestionService;
use App\Shared\Core\Controller;
use App\Shared\NotificationHelper;

class QuestionController extends Controller
{
    private QuestionService $questionService;

    public function __construct(?QuestionService $questionService = null)
    {
        $this->questionService = $questionService ?? new QuestionService();
    }

    public function index(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_questions');

        $page = max(1, (int)($_GET['page_number'] ?? 1));
        $search = trim((string)($_GET['search'] ?? ''));
        $categorySlug = trim((string)($_GET['category'] ?? ''));
        $isPartial = !empty($_GET['partial']);
        $questionTypeFilter = isset($_GET['question_type']) && $_GET['question_type'] !== '' ? trim((string)$_GET['question_type']) : null;
        $difficultyFilter = isset($_GET['difficulty']) && $_GET['difficulty'] !== '' ? trim((string)$_GET['difficulty']) : null;
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? trim((string)$_GET['status']) : null;
        $sort = isset($_GET['sort']) && $_GET['sort'] !== '' ? trim((string)$_GET['sort']) : null;

        $slugMap = $this->questionService->getAssessmentSlugMap();

        $result = $this->questionService->getQuestionsByCategorySlug($page, 100, $search, $categorySlug, $questionTypeFilter, $difficultyFilter, $statusFilter, $sort);
        $questions = $result['questions'];

        $assessments = $this->questionService->getAssessments();
        $questionTypes = $this->questionService->getQuestionTypes();
        $distribution = $this->questionService->getQuestionsCountByAssessment();

        $countMap = [];
        foreach ($distribution as $d) {
            $countMap[(int)$d['assessment_id']] = (int)$d['question_count'];
        }

        $personalityCount = isset($slugMap['personality']) ? ($countMap[$slugMap['personality']] ?? 0) : 0;
        $interestCount = isset($slugMap['interest']) ? ($countMap[$slugMap['interest']] ?? 0) : 0;
        $aptitudeCount = isset($slugMap['aptitude']) ? ($countMap[$slugMap['aptitude']] ?? 0) : 0;
        $valuesCount = isset($slugMap['career_values']) ? ($countMap[$slugMap['career_values']] ?? 0) : 0;
        $totalQuestions = $this->questionService->getTotalQuestions();
        $totalOptions = $this->questionService->getTotalOptions();
        $recentlyAdded = $this->questionService->getRecentlyAddedCount(7);

        $typeDist = $this->questionService->getQuestionsCountByType();
        $typeCountMap = [];
        foreach ($typeDist as $t) {
            $typeCountMap[$t['question_type']] = (int)$t['question_count'];
        }

        $diffDist = $this->questionService->getQuestionsCountByDifficulty();
        $diffCountMap = [];
        foreach ($diffDist as $d) {
            $diffCountMap[$d['difficulty']] = (int)$d['question_count'];
        }

        $statusDist = $this->questionService->getQuestionsCountByStatus();
        $statusCountMap = [];
        foreach ($statusDist as $s) {
            $statusCountMap[$s['status']] = (int)$s['question_count'];
        }

        $this->view(
            'Admin/Presentation/Views/questions/index',
            [
                'layout' => 'none',
                'isPartial' => $isPartial,
                'pageTitle' => 'Question Management',
                'activeMenu' => 'questions',
                'questions' => $questions,
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'totalQuestions' => $totalQuestions,
                'personalityCount' => $personalityCount,
                'interestCount' => $interestCount,
                'aptitudeCount' => $aptitudeCount,
                'valuesCount' => $valuesCount,
                'totalOptions' => $totalOptions,
                'recentlyAdded' => $recentlyAdded,
                'singleChoiceCount' => $typeCountMap['single_choice'] ?? 0,
                'multipleChoiceCount' => $typeCountMap['multiple_choice'] ?? 0,
                'textTypeCount' => $typeCountMap['text'] ?? 0,
                'easyCount' => $diffCountMap['easy'] ?? 0,
                'mediumCount' => $diffCountMap['medium'] ?? 0,
                'hardCount' => $diffCountMap['hard'] ?? 0,
                'usedCount' => $statusCountMap['used'] ?? 0,
                'draftCount' => $statusCountMap['draft'] ?? 0,
                'search' => $search,
                'categorySlug' => $categorySlug,
                'questionTypeFilter' => $questionTypeFilter ?? '',
                'difficultyFilter' => $difficultyFilter ?? '',
                'statusFilter' => $statusFilter ?? '',
                'sort' => $sort ?? '',
                'assessments' => $assessments,
                'questionTypes' => $questionTypes,
                'difficultyOptions' => [
                    ['value' => 'easy', 'label' => 'Easy'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'hard', 'label' => 'Hard'],
                ],
                'statusOptions' => [
                    ['value' => 'used', 'label' => 'In use'],
                    ['value' => 'draft', 'label' => 'Draft'],
                ],
                'slugMap' => $slugMap,
                'distribution' => $distribution,
                'message' => $_GET['message'] ?? null,
            ]
        );
    }

    public function show(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_questions');

        $id = (int)($_GET['id'] ?? 0);
        $question = $this->questionService->getQuestionById($id);

        if (!$question) {
            if (($_GET['format'] ?? '') === 'json') {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'not_found']);
                return;
            }
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $options = $this->questionService->getOptionsByQuestionId($id);

        if (($_GET['format'] ?? '') === 'json') {
            header('Content-Type: application/json');
            echo json_encode(['question' => $question, 'options' => $options]);
            return;
        }

        $this->view(
            'Admin/Presentation/Views/questions/view',
            [
                'layout' => 'none',
                'pageTitle' => 'Question Details',
                'activeMenu' => 'questions',
                'question' => $question,
                'options' => $options,
            ]
        );
    }

    public function create(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_questions');

        $assessments = $this->questionService->getAssessments();
        $questionTypes = $this->questionService->getQuestionTypes();

        $this->view(
            'Admin/Presentation/Views/questions/create',
            [
                'layout' => 'none',
                'pageTitle' => 'Add Question',
                'activeMenu' => 'questions',
                'errors' => [],
                'old' => [],
                'assessments' => $assessments,
                'questionTypes' => $questionTypes,
            ]
        );
    }

    public function store(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-questions');
        }

        $data = [
            'assessment_id' => (int)($_POST['assessment_id'] ?? 0),
            'question_text' => trim((string)($_POST['question_text'] ?? '')),
            'question_type' => trim((string)($_POST['question_type'] ?? 'single_choice')),
            'question_order' => (int)($_POST['question_order'] ?? 0),
        ];

        $optionTexts = $_POST['option_text'] ?? [];
        $optionValues = $_POST['option_value'] ?? [];
        $optionOrders = $_POST['option_order'] ?? [];

        $errors = [];

        if ($data['assessment_id'] <= 0) {
            $errors['assessment_id'] = 'Please select an assessment.';
        }

        if ($data['question_text'] === '') {
            $errors['question_text'] = 'Question text is required.';
        }

        if ($data['question_order'] <= 0) {
            $errors['question_order'] = 'Question order must be a positive number.';
        }

        $options = [];
        if (is_array($optionTexts)) {
            foreach ($optionTexts as $i => $text) {
                $text = trim((string)$text);
                if ($text === '') continue;
                $options[] = [
                    'option_text' => $text,
                    'option_value' => isset($optionValues[$i]) ? (float)$optionValues[$i] : 0,
                    'option_order' => isset($optionOrders[$i]) ? (int)$optionOrders[$i] : ($i + 1),
                ];
            }
        }

        if (count($options) < 2) {
            $errors['options'] = 'At least two options are required.';
        }

        if ($errors !== []) {
            $assessments = $this->questionService->getAssessments();
            $questionTypes = $this->questionService->getQuestionTypes();
            $this->view('Admin/Presentation/Views/questions/create', [
                'layout' => 'none', 'pageTitle' => 'Add Question', 'activeMenu' => 'questions',
                'errors' => $errors, 'old' => $data, 'options' => $options,
                'assessments' => $assessments, 'questionTypes' => $questionTypes,
            ]);
            return;
        }

        $id = null;
        try {
            $id = $this->questionService->createQuestion($data);
        } catch (\PDOException $e) {
            error_log('[QuestionController] store: createQuestion failed: ' . $e->getMessage());
        }
        if ($id === null) {
            $assessments = $this->questionService->getAssessments();
            $questionTypes = $this->questionService->getQuestionTypes();
            $this->view('Admin/Presentation/Views/questions/create', [
                'layout' => 'none', 'pageTitle' => 'Add Question', 'activeMenu' => 'questions',
                'errors' => ['general' => 'Failed to create question. Check that the selected assessment exists.'], 'old' => $data, 'options' => $options,
                'assessments' => $assessments, 'questionTypes' => $questionTypes,
            ]);
            return;
        }

        $this->questionService->saveOptions($id, $options);
        NotificationHelper::questionCreated($data['question_text'], $id, 'Assessment #' . $data['assessment_id']);
        $this->redirectTo('admin-questions', ['message' => 'created']);
    }

    public function edit(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_questions');

        $id = (int)($_GET['id'] ?? 0);
        $question = $this->questionService->getQuestionById($id);

        if (!$question) {
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $options = $this->questionService->getOptionsByQuestionId($id);
        $assessments = $this->questionService->getAssessments();
        $questionTypes = $this->questionService->getQuestionTypes();

        $this->view('Admin/Presentation/Views/questions/edit', [
            'layout' => 'none', 'pageTitle' => 'Edit Question', 'activeMenu' => 'questions',
            'errors' => [], 'old' => $question, 'options' => $options,
            'assessments' => $assessments, 'questionTypes' => $questionTypes,
        ]);
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-questions');
        }

        $format = $_POST['format'] ?? 'html';

        $id = (int)($_POST['id'] ?? 0);
        $question = $this->questionService->getQuestionById($id);

        if (!$question) {
            if ($format === 'json') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => ['not_found' => 'Question not found.']]);
                return;
            }
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $data = [
            'assessment_id' => (int)($_POST['assessment_id'] ?? 0),
            'question_text' => trim((string)($_POST['question_text'] ?? '')),
            'question_type' => trim((string)($_POST['question_type'] ?? 'single_choice')),
            'question_order' => (int)($_POST['question_order'] ?? 0),
        ];

        $optionTexts = $_POST['option_text'] ?? [];
        $optionValues = $_POST['option_value'] ?? [];
        $optionOrders = $_POST['option_order'] ?? [];

        $errors = [];

        if ($data['assessment_id'] <= 0) $errors['assessment_id'] = 'Please select an assessment.';
        if ($data['question_text'] === '') $errors['question_text'] = 'Question text is required.';
        if ($data['question_order'] <= 0) $errors['question_order'] = 'Question order must be a positive number.';

        $options = [];
        if (is_array($optionTexts)) {
            foreach ($optionTexts as $i => $text) {
                $text = trim((string)$text);
                if ($text === '') continue;
                $options[] = [
                    'option_text' => $text,
                    'option_value' => isset($optionValues[$i]) ? (float)$optionValues[$i] : 0,
                    'option_order' => isset($optionOrders[$i]) ? (int)$optionOrders[$i] : ($i + 1),
                ];
            }
        }

        if (count($options) < 2) $errors['options'] = 'At least two options are required.';

        if ($errors !== []) {
            if ($format === 'json') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            $assessments = $this->questionService->getAssessments();
            $questionTypes = $this->questionService->getQuestionTypes();
            $this->view('Admin/Presentation/Views/questions/edit', [
                'layout' => 'none', 'pageTitle' => 'Edit Question', 'activeMenu' => 'questions',
                'errors' => $errors, 'old' => array_merge($question, $data), 'options' => $options,
                'assessments' => $assessments, 'questionTypes' => $questionTypes,
            ]);
            return;
        }

        $this->questionService->updateQuestion($id, $data);
        $this->questionService->saveOptions($id, $options);
        NotificationHelper::questionUpdated($data['question_text'], $id);

        if ($format === 'json') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        }

        $this->redirectTo('admin-questions', ['message' => 'updated']);
    }

    public function export(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('view_questions');

        $search = trim((string)($_GET['search'] ?? ''));
        $assessmentFilter = isset($_GET['assessment_id']) && $_GET['assessment_id'] !== '' ? (int)$_GET['assessment_id'] : null;
        $questionTypeFilter = isset($_GET['question_type']) && $_GET['question_type'] !== '' ? trim((string)$_GET['question_type']) : null;
        $difficultyFilter = isset($_GET['difficulty']) && $_GET['difficulty'] !== '' ? trim((string)$_GET['difficulty']) : null;
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? trim((string)$_GET['status']) : null;
        $sort = isset($_GET['sort']) && $_GET['sort'] !== '' ? trim((string)$_GET['sort']) : null;

        $result = $this->questionService->getAllQuestions(1, 500, $search, $assessmentFilter, $questionTypeFilter, $difficultyFilter, $statusFilter, $sort);

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, ['question_id', 'question_text', 'assessment', 'question_type', 'difficulty', 'options', 'last_updated']);

        foreach ($result['questions'] as $question) {
            $difficulty = $this->mapDifficulty((string)($question['difficulty'] ?? ''));
            $status = $this->mapStatus((string)($question['status'] ?? ''));
            fputcsv($fh, [
                (int)($question['question_id'] ?? 0),
                (string)($question['question_text'] ?? ''),
                (string)($question['assessment_title'] ?? ''),
                (string)($question['question_type'] ?? ''),
                $difficulty,
                (int)($question['option_count'] ?? 0),
                (string)($question['created_at'] ?? ''),
            ]);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="questions-export.csv"');
        echo $csv;
        exit;
    }

    public function import(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['import_file'])) {
            $this->redirectTo('admin-questions');
        }

        $file = $_FILES['import_file']['tmp_name'] ?? '';
        if (!is_string($file) || $file === '' || !is_uploaded_file($file)) {
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $handle = fopen($file, 'rb');
        if ($handle === false) {
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $header = fgetcsv($handle);
        $created = 0;
        $assessmentLookup = [];
        foreach ($this->questionService->getAssessments() as $assessment) {
            $id = (int)($assessment['assessment_id'] ?? 0);
            $title = strtolower((string)($assessment['title'] ?? ''));
            $assessmentLookup[$title] = $id;
        }

        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null] || $row === false) {
                continue;
            }
            $data = [];
            foreach ($header as $index => $column) {
                $data[trim((string)$column)] = $row[$index] ?? '';
            }

            $questionText = trim((string)($data['question_text'] ?? $data['Question'] ?? ''));
            if ($questionText === '') {
                continue;
            }

            $assessmentId = 0;
            if (isset($data['assessment_id']) && (int)$data['assessment_id'] > 0) {
                $assessmentId = (int)$data['assessment_id'];
            } elseif (isset($data['assessment']) && isset($assessmentLookup[strtolower((string)$data['assessment'])])) {
                $assessmentId = $assessmentLookup[strtolower((string)$data['assessment'])];
            }

            if ($assessmentId <= 0) {
                continue;
            }

            $questionType = trim((string)($data['question_type'] ?? 'single_choice'));
            $questionId = $this->questionService->createQuestion([
                'assessment_id' => $assessmentId,
                'question_text' => $questionText,
                'question_type' => $questionType,
                'question_order' => 1,
            ]);

            if ($questionId !== null) {
                $options = [];
                if (isset($data['options'])) {
                    foreach (preg_split('/[;|,]+/', (string)$data['options']) as $optionText) {
                        $text = trim((string)$optionText);
                        if ($text === '') {
                            continue;
                        }
                        $options[] = ['option_text' => $text, 'option_value' => 0, 'option_order' => count($options) + 1];
                    }
                }
                if ($options !== []) {
                    $this->questionService->saveOptions($questionId, $options);
                }
                $created++;
            }
        }

        fclose($handle);
        $this->redirectTo('admin-questions', ['message' => 'created']);
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_questions');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $question = $this->questionService->getQuestionById($id);
                $this->questionService->deleteQuestion($id);
                if ($question) {
                    NotificationHelper::questionDeleted((string)($question['question_text'] ?? ''));
                }
            }
        }

        $this->redirectTo('admin-questions', ['message' => 'deleted']);
    }

    public function duplicate(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('create_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-questions');
        }

        $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? $_POST['ids'] : [];
        if ($ids === []) {
            $ids = [(int)($_POST['id'] ?? 0)];
        }

        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, static fn($value) => $value > 0);

        if ($ids === []) {
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        foreach ($ids as $id) {
            $newId = $this->questionService->duplicateQuestion($id);
            if ($newId === null) {
                $this->redirectTo('admin-questions', ['message' => 'not_found']);
            }
        }

        $this->redirectTo('admin-questions', ['message' => 'duplicated']);
    }

    public function bulkDelete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-questions');
        }

        $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? $_POST['ids'] : [];
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, fn($v) => $v > 0);

        if ($ids !== []) {
            $this->questionService->bulkDelete($ids);
        }

        $this->redirectTo('admin-questions', ['message' => 'deleted']);
    }

    private function mapDifficulty(string $value): string
    {
        return match (strtolower($value)) {
            'medium' => 'Medium',
            'hard' => 'Hard',
            default => 'Easy',
        };
    }

    private function mapStatus(string $value): string
    {
        return strtolower($value) === 'used' ? 'In use' : 'Draft';
    }
}
