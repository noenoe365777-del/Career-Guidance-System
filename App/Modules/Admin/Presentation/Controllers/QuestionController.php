<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presentation\Controllers;

use App\Modules\Admin\Application\Services\QuestionService;
use App\Shared\Core\Controller;

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
        $assessmentFilter = isset($_GET['assessment_id']) && $_GET['assessment_id'] !== '' ? (int)$_GET['assessment_id'] : null;
        $typeFilter = isset($_GET['type']) && $_GET['type'] !== '' ? trim((string)$_GET['type']) : null;

        $result = $this->questionService->getAllQuestions($page, 10, $search, $assessmentFilter, $typeFilter);
        $assessments = $this->questionService->getAssessments();
        $questionTypes = $this->questionService->getQuestionTypes();
        $totalQuestions = $this->questionService->getTotalQuestions();
        $totalOptions = $this->questionService->getTotalOptions();

        $this->view(
            'Admin/Presentation/Views/questions/index',
            [
                'layout' => 'none',
                'pageTitle' => 'Question Management',
                'activeMenu' => 'questions',
                'questions' => $result['questions'],
                'currentPage' => $result['currentPage'],
                'totalPages' => $result['totalPages'],
                'totalQuestions' => $totalQuestions,
                'totalOptions' => $totalOptions,
                'totalAssessments' => count($assessments),
                'search' => $search,
                'assessmentFilter' => $assessmentFilter ?? 0,
                'typeFilter' => $typeFilter ?? '',
                'assessments' => $assessments,
                'questionTypes' => $questionTypes,
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
            $this->redirectTo('admin-questions', ['message' => 'not_found']);
        }

        $options = $this->questionService->getOptionsByQuestionId($id);

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
                if ($text === '') {
                    continue;
                }
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
            $this->view(
                'Admin/Presentation/Views/questions/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Add Question',
                    'activeMenu' => 'questions',
                    'errors' => $errors,
                    'old' => $data,
                    'options' => $options,
                    'assessments' => $assessments,
                    'questionTypes' => $questionTypes,
                ]
            );
            return;
        }

        $id = $this->questionService->createQuestion($data);
        if ($id === null) {
            $assessments = $this->questionService->getAssessments();
            $questionTypes = $this->questionService->getQuestionTypes();
            $this->view(
                'Admin/Presentation/Views/questions/create',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Add Question',
                    'activeMenu' => 'questions',
                    'errors' => ['general' => 'Failed to create question.'],
                    'old' => $data,
                    'options' => $options,
                    'assessments' => $assessments,
                    'questionTypes' => $questionTypes,
                ]
            );
            return;
        }

        $this->questionService->saveOptions($id, $options);
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

        $this->view(
            'Admin/Presentation/Views/questions/edit',
            [
                'layout' => 'none',
                'pageTitle' => 'Edit Question',
                'activeMenu' => 'questions',
                'errors' => [],
                'old' => $question,
                'options' => $options,
                'assessments' => $assessments,
                'questionTypes' => $questionTypes,
            ]
        );
    }

    public function update(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('edit_questions');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTo('admin-questions');
        }

        $id = (int)($_POST['id'] ?? 0);
        $question = $this->questionService->getQuestionById($id);

        if (!$question) {
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
                if ($text === '') {
                    continue;
                }
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
            $this->view(
                'Admin/Presentation/Views/questions/edit',
                [
                    'layout' => 'none',
                    'pageTitle' => 'Edit Question',
                    'activeMenu' => 'questions',
                    'errors' => $errors,
                    'old' => array_merge($question, $data),
                    'options' => $options,
                    'assessments' => $assessments,
                    'questionTypes' => $questionTypes,
                ]
            );
            return;
        }

        $this->questionService->updateQuestion($id, $data);
        $this->questionService->saveOptions($id, $options);
        $this->redirectTo('admin-questions', ['message' => 'updated']);
    }

    public function delete(): void
    {
        AdminAuthMiddleware::requireAdmin();
        $this->requirePermission('delete_questions');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->questionService->deleteQuestion($id);
            }
        }

        $this->redirectTo('admin-questions', ['message' => 'deleted']);
    }
}
