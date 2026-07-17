<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Storage;

use App\Modules\Assessment\Infrastructure\Persistence\AssessmentEngineRepository;
use PDO;

class DatabaseStorage implements AssessmentStorageInterface
{
    private AssessmentEngineRepository $repo;

    public function __construct(AssessmentEngineRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getOrCreateAttempt(int $userId, int $assessmentId): array
    {
        $existing = $this->repo->getLatestAttempt($userId, $assessmentId);
        if ($existing && $existing['status'] === 'completed') {
            return $existing;
        }
        if ($existing && $existing['status'] === 'in_progress') {
            return $existing;
        }
        $now = date('Y-m-d H:i:s');
        $stmt = $this->repo->getConnection()->prepare(
            "INSERT INTO student_assessments (user_id, assessment_id, status, started_at, created_at) VALUES (:uid, :aid, 'in_progress', :now, :now)"
        );
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId, ':now' => $now]);
        $id = (int)$this->repo->getConnection()->lastInsertId();
        return $this->repo->getAttemptById($id) ?? ['student_assessment_id' => $id];
    }

    public function getAttempt(int $attemptId): ?array
    {
        return $this->repo->getAttemptById($attemptId);
    }

    public function getQuestionOrder(int $attemptId): array
    {
        $data = $_SESSION['assessment_q_order_' . $attemptId] ?? [];
        return $data;
    }

    public function setQuestionOrder(int $attemptId, array $questionIds): void
    {
        $_SESSION['assessment_q_order_' . $attemptId] = $questionIds;
    }

    public function getCurrentIndex(int $attemptId): int
    {
        $attempt = $this->repo->getAttemptById($attemptId);
        return (int)($attempt['current_question'] ?? 0);
    }

    public function setCurrentIndex(int $attemptId, int $index): void
    {
        $this->repo->updateAttempt($attemptId, ['current_question' => $index]);
    }

public function saveAnswer(int $attemptId, int $questionId, int $optionId, float $score): void
    {
        $existing = $this->repo->getAnswerForQuestion($attemptId, $questionId);
        if ($existing) {
            $this->repo->getConnection()->prepare("UPDATE student_answers SET option_id = :oid, score = :sc, answer_text = :at WHERE answer_id = :aid")
                ->execute([
                    ':oid' => $optionId,
                    ':sc' => $score,
                    ':at' => (string)$score,
                    ':aid' => $existing['answer_id'],
                ]);
        } else {
            $this->repo->getConnection()->prepare("INSERT INTO student_answers (student_assessment_id, question_id, option_id, score, answer_text, created_at) VALUES (:said, :qid, :oid, :sc, :at, NOW())")
                ->execute([
                    ':said' => $attemptId,
                    ':qid' => $questionId,
                    ':oid' => $optionId,
                    ':sc' => $score,
                    ':at' => (string)$score,
                ]);
        }
    }

    public function getAnswer(int $attemptId, int $questionId): ?array
    {
        return $this->repo->getAnswerForQuestion($attemptId, $questionId);
    }

    public function getAnsweredCount(int $attemptId): int
    {
        return $this->repo->getAnsweredCount($attemptId);
    }

    public function updateAttempt(int $attemptId, array $data): void
    {
        $allowed = ['current_question', 'progress', 'status', 'completed_at'];
        $sets = [];
        $params = [':id' => $attemptId];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $sets[] = "`$key` = :$key";
                $params[":$key"] = $value;
            }
        }
        if (empty($sets)) {
            return;
        }
        $sql = "UPDATE student_assessments SET " . implode(', ', $sets) . " WHERE student_assessment_id = :id";
        $this->repo->getConnection()->prepare($sql)->execute($params);
    }

    public function completeAttempt(int $attemptId): array
    {
        $answered = $this->repo->getAnsweredCount($attemptId);
        $attempt = $this->repo->getAttemptById($attemptId);
        if (!$attempt) {
            return ['success' => false, 'message' => 'Attempt not found'];
        }

        $totalQ = $this->repo->countAssessmentQuestions((int)$attempt['assessment_id']);
        $score = $this->repo->calculateScore($attemptId);
        $now = date('Y-m-d H:i:s');

        $this->repo->updateAttempt($attemptId, [
            'status' => 'completed',
            'progress' => 100.00,
            'completed_at' => $now,
        ]);

        $this->repo->saveAssessmentScore(
            (int)$attempt['user_id'],
            $attempt['assessment_name'] ?? '',
            (int)$score,
            $totalQ,
            $answered
        );

        unset($_SESSION['assessment_q_order_' . $attemptId]);

        return [
            'success' => true,
            'score' => $score,
            'answered' => $answered,
            'total' => $totalQ,
            'completed_at' => $now,
            'assessment_name' => $attempt['assessment_name'],
        ];
    }

    public function destroyAttempt(int $attemptId): void
    {
        unset($_SESSION['assessment_q_order_' . $attemptId]);
    }

    public function getTimeLimit(int $attemptId): int
    {
        $attempt = $this->repo->getAttemptById($attemptId);
        if (!$attempt) {
            return 5;
        }
        return (int)($attempt['time_limit'] ?? 5);
    }

    public function getStartedAt(int $attemptId): ?string
    {
        $attempt = $this->repo->getAttemptById($attemptId);
        return $attempt['started_at'] ?? null;
    }
}