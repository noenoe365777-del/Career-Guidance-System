<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Storage;

class SessionStorage implements AssessmentStorageInterface
{
    private string $sessionKey = 'guest_assessment';

    public function getAttempt(int $attemptId): ?array
    {
        $data = $_SESSION[$this->sessionKey] ?? null;
        if (!$data || ($data['attempt_id'] ?? 0) !== $attemptId) {
            return null;
        }
        return [
            'student_assessment_id' => $data['attempt_id'],
            'user_id' => 0,
            'assessment_id' => $data['assessment_id'] ?? 0,
            'status' => 'in_progress',
            'current_question' => $data['current'] ?? 0,
            'current' => $data['current'] ?? 0,
            'progress' => 0.00,
            'started_at' => is_numeric($data['started_at'] ?? null) ? date('Y-m-d H:i:s', (int)$data['started_at']) : ($data['started_at'] ?? null),
            'completed_at' => null,
            'answers' => $data['answers'] ?? [],
            'time_limit' => $data['time_limit'] ?? 5,
            'question_order' => $data['question_order'] ?? [],
            'title' => $data['title'] ?? null,
            'total_questions' => $data['total_questions'] ?? null,
        ];
    }

    public function createAttempt(int $userId, int $assessmentId): array
    {
        $attemptId = 1; // Single guest attempt
        $startedAt = time();
        $timeLimit = 5; // Default 5 minutes

        $_SESSION[$this->sessionKey] = [
            'attempt_id' => $attemptId,
            'assessment_id' => $assessmentId,
            'answers' => [],
            'current' => 0,
            'started_at' => $startedAt,
            'time_limit' => 5,
        ];

        return [
            'student_assessment_id' => $attemptId,
            'user_id' => 0,
            'assessment_id' => $assessmentId,
            'status' => 'in_progress',
            'current_question' => 0,
            'progress' => 0.00,
            'started_at' => date('Y-m-d H:i:s', $startedAt),
            'completed_at' => null,
        ];
    }

    public function getOrCreateAttempt(int $userId, int $assessmentId): array
    {
        // For guest, always return the same attempt
        $data = $_SESSION[$this->sessionKey] ?? null;
        if ($data && isset($data['attempt_id'])) {
            return [
                'student_assessment_id' => $data['attempt_id'],
                'user_id' => 0,
                'assessment_id' => $data['assessment_id'] ?? 0,
                'status' => 'in_progress',
                'current_question' => $data['current'] ?? 0,
                'progress' => 0.00,
                'started_at' => date('Y-m-d H:i:s', $data['started_at'] ?? time()),
                'completed_at' => null,
            ];
        }
        return $this->createAttempt($userId, $assessmentId);
    }

    public function getQuestionOrder(int $attemptId): array
    {
        $data = $this->getAttempt($attemptId);
        return $data['question_order'] ?? [];
    }

    public function setQuestionOrder(int $attemptId, array $questionIds): void
    {
        if (isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey]['question_order'] = $questionIds;
        }
    }

    public function getCurrentIndex(int $attemptId): int
    {
        $data = $this->getAttempt($attemptId);
        return $data['current'] ?? 0;
    }

    public function setCurrentIndex(int $attemptId, int $index): void
    {
        if (isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey]['current'] = $index;
        }
    }

    public function saveAnswer(int $attemptId, int $questionId, int $optionId, float $score): void
    {
        if (!isset($_SESSION[$this->sessionKey])) {
            return;
        }
        $data = &$_SESSION[$this->sessionKey];
        $data['answers'][$questionId] = $optionId;
        $data['current'] = ($data['current'] ?? 0) + 1;
    }

    public function getAnswer(int $attemptId, int $questionId): ?array
    {
        $data = $this->getAttempt($attemptId);
        if (!$data || !isset($data['answers'][$questionId])) {
            return null;
        }
        return ['option_id' => $data['answers'][$questionId]];
    }

    public function getAnsweredCount(int $attemptId): int
    {
        $data = $this->getAttempt($attemptId);
        return $data ? count($data['answers'] ?? []) : 0;
    }

    public function updateAttempt(int $attemptId, array $data): void
    {
        if (isset($_SESSION[$this->sessionKey])) {
            foreach ($data as $key => $value) {
                $_SESSION[$this->sessionKey][$key] = $value;
            }
        }
    }

    public function completeAttempt(int $attemptId): array
    {
        $data = $this->getAttempt($attemptId);
        if (!$data) {
            return ['success' => false, 'message' => 'Assessment not found'];
        }

        $answers = $data['answers'] ?? [];
        $totalQ = (int)($data['total_questions'] ?? 5);
        $answered = count($answers);
        $score = $totalQ > 0 ? round(($answered / $totalQ) * 100, 1) : 0;

        $result = [
            'success' => true,
            'score' => $score,
            'answered' => $answered,
            'total' => $totalQ,
            'completed_at' => date('Y-m-d H:i:s'),
            'assessment_name' => $data['title'] ?? 'Assessment',
        ];

        $this->destroyAttempt($attemptId);

        return $result;
    }

    public function getTimeLimit(int $attemptId): int
    {
        $data = $this->getAttempt($attemptId);
        return (int)($data['time_limit'] ?? 5);
    }

    public function getStartedAt(int $attemptId): ?string
    {
        $data = $this->getAttempt($attemptId);
        return $data['started_at'] ?? null;
    }

    public function destroyAttempt(int $attemptId): void
    {
        unset($_SESSION[$this->sessionKey]);
    }
}