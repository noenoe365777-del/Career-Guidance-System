<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\StudentAssessmentRepositoryInterface;
use PDO;

class StudentAssessmentRepository implements StudentAssessmentRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findForUser(int $userId, int $assessmentId): ?array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT student_assessment_id AS id, user_id, assessment_id, started_at, completed_at, status, total_score FROM student_assessments WHERE user_id = :user_id AND assessment_id = :assessment_id ORDER BY student_assessment_id DESC LIMIT 1"
            );
            $statement->execute(['user_id' => $userId, 'assessment_id' => $assessmentId]);
            $row = $statement->fetch();

            if ($row) {
                return [
                    'id' => (int)$row['id'],
                    'user_id' => (int)$row['user_id'],
                    'assessment_id' => (int)$row['assessment_id'],
                    'started_at' => $row['started_at'],
                    'completed_at' => $row['completed_at'],
                    'status' => $row['status'],
                    'total_score' => (float)$row['total_score'],
                ];
            }
        } catch (\Throwable) {
        }

        return null;
    }

    public function create(int $userId, int $assessmentId): array
    {
        try {
            $statement = $this->connection->prepare(
                "INSERT INTO student_assessments (user_id, assessment_id, status, started_at) VALUES (:user_id, :assessment_id, 'in_progress', NOW())"
            );
            $statement->execute(['user_id' => $userId, 'assessment_id' => $assessmentId]);
            return $this->findForUser($userId, $assessmentId) ?? ['id' => (int)$this->connection->lastInsertId()];
        } catch (\Throwable) {
            return ['id' => 0];
        }
    }

    public function createOrUpdate(int $userId, int $assessmentId, array $answers, int $score, string $summary): array
    {
        try {
            $existing = $this->findForUser($userId, $assessmentId);
            if ($existing && (int)$existing['id'] > 0) {
                $studentAssessmentId = (int)$existing['id'];
                $statement = $this->connection->prepare(
                    "UPDATE student_assessments SET status = 'completed', total_score = :score, completed_at = NOW() WHERE student_assessment_id = :id"
                );
                $statement->execute(['score' => $score, 'id' => $studentAssessmentId]);
            } else {
                $statement = $this->connection->prepare(
                    "INSERT INTO student_assessments (user_id, assessment_id, status, total_score, started_at, completed_at) VALUES (:user_id, :assessment_id, 'completed', :score, NOW(), NOW())"
                );
                $statement->execute(['user_id' => $userId, 'assessment_id' => $assessmentId, 'score' => $score]);
                $studentAssessmentId = (int)$this->connection->lastInsertId();
            }

            $this->connection->prepare("DELETE FROM student_answers WHERE student_assessment_id = :id")
                ->execute(['id' => $studentAssessmentId]);

            $this->connection->prepare(
                "INSERT INTO assessment_results (student_assessment_id, user_id, assessment_id, total_score, result_summary, created_at)
                 VALUES (:student_assessment_id, :user_id, :assessment_id, :total_score, :result_summary, NOW())
                 ON DUPLICATE KEY UPDATE total_score = VALUES(total_score), result_summary = VALUES(result_summary), created_at = NOW()"
            )->execute([
                'student_assessment_id' => $studentAssessmentId,
                'user_id' => $userId,
                'assessment_id' => $assessmentId,
                'total_score' => $score,
                'result_summary' => $summary,
            ]);

            foreach ($answers as $questionId => $answerValue) {
                if (!is_numeric($questionId) || !is_numeric($answerValue)) {
                    continue;
                }
                try {
                    $answerStatement = $this->connection->prepare(
                        "INSERT INTO student_answers (student_assessment_id, question_id, answer_text, score_awarded, created_at) VALUES (:student_assessment_id, :question_id, :answer_text, :score_awarded, NOW())"
                    );
                    $answerStatement->execute([
                        'student_assessment_id' => $studentAssessmentId,
                        'question_id' => (int)$questionId,
                        'answer_text' => (string)$answerValue,
                        'score_awarded' => (int)$answerValue,
                    ]);
                } catch (\Throwable) {
                }
            }

            return [
                'id' => $studentAssessmentId,
                'status' => 'completed',
                'score' => $score,
                'summary' => $summary,
            ];
        } catch (\Throwable) {
            return ['id' => 0, 'status' => 'completed', 'score' => $score, 'summary' => $summary];
        }
    }

    public function getCompletedCount(int $userId): int
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COUNT(*) AS total FROM student_assessments WHERE user_id = :user_id AND status = 'completed'"
            );
            $statement->execute(['user_id' => $userId]);
            return (int)$statement->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }

    public function getProgressSummary(int $userId): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT a.assessment_type AS slug, s.status, s.total_score, s.completed_at, s.started_at
                 FROM student_assessments s
                 INNER JOIN assessments a ON a.assessment_id = s.assessment_id
                 WHERE s.user_id = :user_id
                 ORDER BY s.completed_at DESC, s.started_at DESC"
            );
            $statement->execute(['user_id' => $userId]);
            $rows = $statement->fetchAll();

            $progress = [];
            foreach ($rows as $row) {
                $progress[$row['slug']] = [
                    'status' => $row['status'] ?? 'in_progress',
                    'score' => (float)($row['total_score'] ?? 0),
                    'completed_at' => $row['completed_at'],
                    'started_at' => $row['started_at'],
                    'progress' => ($row['status'] ?? 'in_progress') === 'completed' ? 100 : 50,
                    'is_completed' => ($row['status'] ?? 'in_progress') === 'completed',
                ];
            }

            return $progress;
        } catch (\Throwable) {
            return [];
        }
    }
}

