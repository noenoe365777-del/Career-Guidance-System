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
                "SELECT student_assessment_id AS id, user_id, assessment_id, started_at, completed_at, status FROM student_assessments WHERE user_id = :user_id AND assessment_id = :assessment_id ORDER BY student_assessment_id DESC LIMIT 1"
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

    public function createOrUpdate(int $userId, int $assessmentId, array $answers, int $score, string $summary, string $slug, ?string $typeLabel = null): array
    {
        try {
            $existing = $this->findForUser($userId, $assessmentId);
            if ($existing && (int)$existing['id'] > 0) {
                $studentAssessmentId = (int)$existing['id'];
                $statement = $this->connection->prepare(
                    "UPDATE student_assessments SET status = 'completed', completed_at = NOW() WHERE student_assessment_id = :id"
                );
                $statement->execute(['id' => $studentAssessmentId]);
            } else {
                $statement = $this->connection->prepare(
                    "INSERT INTO student_assessments (user_id, assessment_id, status, started_at, completed_at) VALUES (:user_id, :assessment_id, 'completed', NOW(), NOW())"
                );
                $statement->execute(['user_id' => $userId, 'assessment_id' => $assessmentId]);
                $studentAssessmentId = (int)$this->connection->lastInsertId();
            }

            $this->connection->prepare("DELETE FROM student_answers WHERE student_assessment_id = :id")
                ->execute(['id' => $studentAssessmentId]);

            $this->connection->exec("SET FOREIGN_KEY_CHECKS = 0");
            foreach ($answers as $questionId => $answerValue) {
                if (!is_numeric($questionId) || !is_numeric($answerValue)) {
                    continue;
                }
                try {
                    $answerStatement = $this->connection->prepare(
                        "INSERT INTO student_answers (student_assessment_id, question_id, answer_text, score, created_at) VALUES (:student_assessment_id, :question_id, :answer_text, :score, NOW())"
                    );
                    $answerStatement->execute([
                        'student_assessment_id' => $studentAssessmentId,
                        'question_id' => (int)$questionId,
                        'answer_text' => (string)$answerValue,
                        'score' => (int)$answerValue,
                    ]);
                } catch (\Throwable) {
                }
            }
            $this->connection->exec("SET FOREIGN_KEY_CHECKS = 1");

            $this->saveAssessmentScore($userId, $slug, $score, $typeLabel);

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
                "SELECT a.assessment_id, s.status, s.completed_at, s.started_at
                 FROM student_assessments s
                 INNER JOIN assessments a ON a.assessment_id = s.assessment_id
                 WHERE s.user_id = :user_id
                 ORDER BY s.completed_at DESC, s.started_at DESC"
            );
            $statement->execute(['user_id' => $userId]);
            $rows = $statement->fetchAll();

            $slugMap = [1 => 'personality', 2 => 'interest', 3 => 'aptitude', 4 => 'values'];

            $progress = [];
            foreach ($rows as $row) {
                $assessmentId = (int)$row['assessment_id'];
                $slug = $slugMap[$assessmentId] ?? 'assessment_' . $assessmentId;
                $progress[$slug] = [
                    'status' => $row['status'] ?? 'in_progress',
                    'score' => 0,
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

    public function getAssessmentScore(int $userId, string $slug): int
    {
        $scoreColumn = $slug . '_score';
        $validColumns = ['personality_score', 'interest_score', 'aptitude_score', 'values_score'];

        if (!in_array($scoreColumn, $validColumns, true)) {
            return 0;
        }

        try {
            $statement = $this->connection->prepare(
                "SELECT {$scoreColumn} FROM student_assessment_scores WHERE student_id = :user_id LIMIT 1"
            );
            $statement->execute(['user_id' => $userId]);
            $value = $statement->fetchColumn();

            return $value !== false ? (int)$value : 0;
        } catch (\Throwable) {
            return 0;
        }
    }

    private function saveAssessmentScore(int $userId, string $slug, int $score, ?string $typeLabel = null): void
    {
        $scoreColumn = $slug . '_score';
        $typeColumn = $slug . '_type';

        $validColumns = ['personality_score', 'interest_score', 'aptitude_score', 'values_score'];
        if (!in_array($scoreColumn, $validColumns, true)) {
            return;
        }

        try {
            $existing = $this->connection->prepare(
                "SELECT id FROM student_assessment_scores WHERE student_id = :user_id"
            );
            $existing->execute(['user_id' => $userId]);
            $row = $existing->fetch();

            if ($row) {
                $sql = "UPDATE student_assessment_scores SET {$scoreColumn} = :score";
                $params = ['score' => $score, 'user_id' => $userId];

                if ($typeLabel !== null) {
                    $sql .= ", {$typeColumn} = :type_label";
                    $params['type_label'] = $typeLabel;
                }

                $sql .= " WHERE student_id = :user_id";
                $this->connection->prepare($sql)->execute($params);
            } else {
                $sql = "INSERT INTO student_assessment_scores (student_id, {$scoreColumn}";
                $params = ['user_id' => $userId, 'score' => $score];

                if ($typeLabel !== null) {
                    $sql .= ", {$typeColumn}";
                    $params['type_label'] = $typeLabel;
                }

                $sql .= ") VALUES (:user_id, :score";
                if ($typeLabel !== null) {
                    $sql .= ", :type_label";
                }
                $sql .= ")";

                $this->connection->prepare($sql)->execute($params);
            }
        } catch (\Throwable) {
        }
    }
}
