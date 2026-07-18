<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Engine;

use App\Modules\Assessment\Application\Storage\AssessmentStorageInterface;
use App\Modules\Assessment\Infrastructure\Persistence\AssessmentEngineRepository;

class AssessmentEngine
{
    private AssessmentEngineRepository $repo;
    private AssessmentStorageInterface $storage;

    public function __construct(AssessmentEngineRepository $repo, AssessmentStorageInterface $storage)
    {
        $this->repo = $repo;
        $this->storage = $storage;
    }

    public function startAssessment(int $userId, int $assessmentId): array
    {
        $assessment = $this->repo->getAssessmentById($assessmentId);
        if (!$assessment) {
            return ['success' => false, 'message' => 'Assessment not found'];
        }

        $attempt = $this->storage->getOrCreateAttempt($userId, $assessmentId);

        $totalQ = $this->repo->countAssessmentQuestions($assessmentId);
        $isGuest = ($userId <= 0);
        $questionLimit = $isGuest ? min(5, $totalQ) : $totalQ;

        $randomMode = (bool)($assessment['random_mode'] ?? false);
        $questions = $this->repo->getQuestions($assessmentId, $randomMode, $questionLimit);

        $questionIds = array_map(fn($q) => (int)$q['question_id'], $questions);
        $this->storage->setQuestionOrder((int)$attempt['student_assessment_id'], $questionIds);

        $currentIdx = $this->storage->getCurrentIndex((int)$attempt['student_assessment_id']);
        if ($currentIdx >= count($questionIds)) {
            $currentIdx = 0;
        }

        $this->storage->updateAttempt((int)$attempt['student_assessment_id'], [
            'current_question' => $currentIdx,
            'started_at' => $attempt['started_at'] ?? date('Y-m-d H:i:s'),
            'title' => $assessment['title'],
            'total_questions' => count($questionIds),
        ]);

        return [
            'success' => true,
            'attempt_id' => (int)$attempt['student_assessment_id'],
            'assessment' => [
                'id' => $assessmentId,
                'name' => $assessment['title'],
                'icon' => $assessment['icon'] ?? 'bi-collection',
                'time_limit' => (int)$this->storage->getTimeLimit((int)$attempt['student_assessment_id']),
            ],
            'total_questions' => count($questionIds),
            'current_index' => $currentIdx,
            'started_at' => $this->storage->getStartedAt((int)$attempt['student_assessment_id']),
        ];
    }

    public function getQuestion(int $userId, int $attemptId, int $index): array
    {
        $attempt = $this->storage->getAttempt($attemptId);
        if (!$attempt) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }

        $attemptPk = (int)$attempt['student_assessment_id'];
        $questionIds = $this->storage->getQuestionOrder($attemptPk);
        if (empty($questionIds)) {
            $assessmentId = (int)($attempt['assessment_id'] ?? 0);
            if ($assessmentId <= 0) {
                return ['success' => false, 'message' => 'Invalid assessment'];
            }
            $totalQ = $this->repo->countAssessmentQuestions($assessmentId);
            $randomMode = false;
            $questions = $this->repo->getQuestions($assessmentId, $randomMode, $totalQ);
            $questionIds = array_map(fn($q) => (int)$q['question_id'], $questions);
            $this->storage->setQuestionOrder($attemptPk, $questionIds);
        }

        if ($index < 0 || $index >= count($questionIds)) {
            return ['success' => false, 'done' => true];
        }

        $qId = $questionIds[$index];
        $question = $this->repo->getQuestionById($qId);
        if (!$question) {
            return ['success' => false, 'message' => 'Question not found'];
        }

        $options = $this->repo->getOptionsForQuestion($qId);
        $existingAnswer = $this->storage->getAnswer($attemptPk, $qId);

        $hasPrev = $index > 0;
        $hasNext = $index < count($questionIds) - 1;
        $isLast = !$hasNext;

        $answeredCount = $this->storage->getAnsweredCount($attemptPk);

        $timeLimit = $this->storage->getTimeLimit($attemptPk);
        $startedAt = $this->storage->getStartedAt($attemptPk);
        $elapsed = time() - (strtotime($startedAt) ?? time());
        $timeLimitSeconds = $timeLimit * 60;
        $remaining = max(0, $timeLimitSeconds - $elapsed);

        return [
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
            'remaining_time' => $remaining,
        ];
    }

    public function saveAnswer(int $userId, int $attemptId, int $questionId, int $optionId): array
    {
        // Get option value for scoring
        $options = $this->repo->getOptionsForQuestion($questionId);
        $score = 0;
        foreach ($options as $opt) {
            if ((int)$opt['option_id'] === $optionId) {
                $score = (float)($opt['option_value'] ?? 0);
                break;
            }
        }

        $this->storage->saveAnswer($attemptId, $questionId, $optionId, $score);

        $questionIds = $this->storage->getQuestionOrder($attemptId);
        $currentIdx = array_search($questionId, $questionIds);
        if ($currentIdx === false) {
            $currentIdx = $this->storage->getCurrentIndex($attemptId);
        }

        $answeredCount = $this->storage->getAnsweredCount($attemptId);
        $totalQ = count($this->storage->getQuestionOrder($attemptId));

        $this->storage->updateAttempt($attemptId, [
            'current_question' => $currentIdx + 1,
            'progress' => $totalQ > 0 ? round(($answeredCount / $totalQ) * 100, 2) : 0,
        ]);

        return [
            'success' => true,
            'answered_count' => $answeredCount,
            'progress' => $totalQ > 0 ? round(($answeredCount / $totalQ) * 100, 2) : 0,
        ];
    }

    public function finishAssessment(int $userId, int $attemptId): array
    {
        $result = $this->storage->completeAttempt($attemptId);
        return $result;
    }

    public function exitAssessment(int $userId, int $attemptId): array
    {
        $this->storage->destroyAttempt($attemptId);
        return ['success' => true, 'redirect' => BASE_URL . '/index.php?page=assessments'];
    }
}