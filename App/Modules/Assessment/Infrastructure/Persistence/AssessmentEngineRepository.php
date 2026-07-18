<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use PDO;

class AssessmentEngineRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAssessmentsWithProgress(int $userId): array
    {
        $sql = "SELECT a.*,
                       sa.student_assessment_id AS attempt_id,
                       sa.status AS attempt_status,
                       sa.current_question,
                       sa.progress AS attempt_progress,
                       sa.started_at,
                       sa.completed_at,
                       IFNULL(sa.status, 'not_started') AS student_status
                FROM assessments a
                LEFT JOIN student_assessments sa
                    ON sa.assessment_id = a.assessment_id
                    AND sa.user_id = :uid
                    AND sa.student_assessment_id = (
                        SELECT MAX(sa2.student_assessment_id)
                        FROM student_assessments sa2
                        WHERE sa2.assessment_id = a.assessment_id
                          AND sa2.user_id = :uid2
                    )
                WHERE a.status = 'active'
                ORDER BY a.assessment_id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId, ':uid2' => $userId]);
        return $stmt->fetchAll();
    }

    public function getAssessmentById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessments WHERE assessment_id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getById(int $id): ?array
    {
        return $this->getAssessmentById($id);
    }

    public function getBySlug(string $slug): ?array
    {
        $slugMap = $this->getSlugMap();
        $assessmentId = $slugMap[$slug] ?? null;
        if ($assessmentId === null) {
            return null;
        }
        return $this->getAssessmentById((int)$assessmentId);
    }

    public function getAll(): array
    {
        return $this->getActiveAssessments();
    }

    public function getSlugMap(): array
    {
        $stmt = $this->pdo->query("SELECT assessment_id, title FROM assessments WHERE status = 'active' ORDER BY assessment_id ASC");
        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $slug = strtolower(str_replace(' ', '_', trim((string)$row['title'])));
            $map[$slug] = (int)$row['assessment_id'];
        }
        return $map;
    }

    public function getAssessmentByTitle(string $title): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessments WHERE LOWER(title) = LOWER(:t) AND status = 'active'");
        $stmt->execute([':t' => $title]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getActiveAssessments(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM assessments WHERE status = 'active' ORDER BY assessment_id ASC");
        return $stmt->fetchAll();
    }

    public function getOrCreateAttempt(int $userId, int $assessmentId): array
    {
        $existing = $this->getLatestAttempt($userId, $assessmentId);
        if ($existing && $existing['status'] === 'completed') {
            return $existing;
        }
        if ($existing && $existing['status'] === 'in_progress') {
            return $existing;
        }
        $now = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO student_assessments (user_id, assessment_id, status, started_at, created_at) VALUES (:uid, :aid, 'in_progress', :now, :now)");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId, ':now' => $now]);
        $id = (int)$this->pdo->lastInsertId();
        return [
            'student_assessment_id' => $id,
            'user_id' => $userId,
            'assessment_id' => $assessmentId,
            'status' => 'in_progress',
            'current_question' => 0,
            'progress' => 0.00,
            'started_at' => $now,
            'completed_at' => null,
        ];
    }

    public function getLatestAttempt(int $userId, int $assessmentId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM student_assessments WHERE user_id = :uid AND assessment_id = :aid ORDER BY student_assessment_id DESC LIMIT 1");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAttemptById(int $attemptId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT sa.*, a.title AS assessment_name, a.icon, a.time_limit, a.total_questions
                                      FROM student_assessments sa
                                      JOIN assessments a ON a.assessment_id = sa.assessment_id
                                      WHERE sa.student_assessment_id = :id");
        $stmt->execute([':id' => $attemptId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getQuestions(int $assessmentId, bool $randomMode = false, ?int $limit = null): array
    {
        if ($randomMode) {
            $sql = "SELECT aq.id AS question_id, aq.question AS question_text, 'single_choice' AS question_type,
                           aq.option_a, aq.option_b, aq.option_c, aq.option_d,
                           aq.correct_answer, aq.weight
                    FROM assessment_questions aq
                    WHERE aq.assessment_id = :aid ORDER BY RAND()";
        } else {
            $sql = "SELECT aq.id AS question_id, aq.question AS question_text, 'single_choice' AS question_type,
                           aq.option_a, aq.option_b, aq.option_c, aq.option_d,
                           aq.correct_answer, aq.weight
                    FROM assessment_questions aq
                    WHERE aq.assessment_id = :aid ORDER BY aq.id ASC";
        }
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':aid' => $assessmentId]);
        return $stmt->fetchAll();
    }

    public function getQuestionById(int $questionId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT aq.id AS question_id, aq.question AS question_text, 'single_choice' AS question_type,
                                             aq.assessment_id, aq.option_a, aq.option_b, aq.option_c, aq.option_d,
                                             aq.correct_answer, aq.weight, a.title AS assessment_name
                                      FROM assessment_questions aq
                                      JOIN assessments a ON a.assessment_id = aq.assessment_id
                                      WHERE aq.id = :id");
        $stmt->execute([':id' => $questionId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getOptionsForQuestion(int $questionId): array
    {
        $stmt = $this->pdo->prepare("SELECT option_a, option_b, option_c, option_d, correct_answer
                                     FROM assessment_questions WHERE id = :qid");
        $stmt->execute([':qid' => $questionId]);
        $row = $stmt->fetch();
        if (!$row) return [];

        $options = [];
        $position = 1;
        foreach (['option_a', 'option_b', 'option_c', 'option_d'] as $col) {
            $value = $row[$col] ?? null;
            if ($value !== null && $value !== '') {
                $letter = chr(64 + $position);
                $isCorrect = ($row['correct_answer'] !== null && $letter === $row['correct_answer']);
                $options[] = [
                    'option_id' => $position,
                    'option_text' => (string)$value,
                    'option_value' => $isCorrect ? 5.0 : (float)$position,
                    'option_order' => $position,
                ];
            }
            $position++;
        }
        return $options;
    }

    public function saveAnswer(int $attemptId, int $questionId, int $optionId, float $score): void
    {
        $existing = $this->getAnswerForQuestion($attemptId, $questionId);
        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE student_answers SET option_id = :oid, score = :sc, answer_text = :at WHERE answer_id = :aid");
            $stmt->execute([
                ':oid' => $optionId,
                ':sc' => $score,
                ':at' => (string)$score,
                ':aid' => $existing['answer_id'],
            ]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO student_answers (student_assessment_id, question_id, option_id, score, answer_text, created_at)
                                          VALUES (:said, :qid, :oid, :sc, :at, NOW())");
            $stmt->execute([
                ':said' => $attemptId,
                ':qid' => $questionId,
                ':oid' => $optionId,
                ':sc' => $score,
                ':at' => (string)$score,
            ]);
        }
    }

    public function getAnswerForQuestion(int $attemptId, int $questionId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM student_answers WHERE student_assessment_id = :said AND question_id = :qid");
        $stmt->execute([':said' => $attemptId, ':qid' => $questionId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAnsweredCount(int $attemptId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM student_answers WHERE student_assessment_id = :id");
        $stmt->execute([':id' => $attemptId]);
        return (int)$stmt->fetchColumn();
    }

    public function updateAttempt(int $attemptId, array $data): void
    {
        $allowed = ['current_question', 'progress', 'status', 'completed_at'];
        $sets = [];
        $params = [':id' => $attemptId];
        foreach ($data as $k => $v) {
            if (in_array($k, $allowed, true)) {
                $sets[] = "`$k` = :$k";
                $params[":$k"] = $v;
            }
        }
        if (empty($sets)) return;
        $sql = "UPDATE student_assessments SET " . implode(', ', $sets) . " WHERE student_assessment_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function completeAttempt(int $attemptId): array
    {
        $answered = $this->getAnsweredCount($attemptId);
        $attempt = $this->getAttemptById($attemptId);
        if (!$attempt) return ['success' => false, 'message' => 'Attempt not found'];

        $totalQ = $this->countAssessmentQuestions((int)$attempt['assessment_id']);
        $score = $this->calculateScore($attemptId);
        $now = date('Y-m-d H:i:s');

        $this->updateAttempt($attemptId, [
            'status' => 'completed',
            'progress' => 100.00,
            'completed_at' => $now,
        ]);

        $this->saveAssessmentScore(
            (int)$attempt['user_id'],
            $attempt['assessment_name'],
            (int)$score,
            $totalQ,
            $answered
        );

        return [
            'success' => true,
            'score' => $score,
            'answered' => $answered,
            'total' => $totalQ,
            'completed_at' => $now,
            'attempt' => $attempt,
        ];
    }

    public function calculateScore(int $attemptId): float
    {
        $stmt = $this->pdo->prepare("SELECT COALESCE(AVG(score), 0) FROM student_answers WHERE student_assessment_id = :id");
        $stmt->execute([':id' => $attemptId]);
        $avg = (float)$stmt->fetchColumn();

        $attempt = $this->getAttemptById($attemptId);
        if (!$attempt) return 0;

        $maxVal = 5.0;
        return round(($avg / $maxVal) * 100, 1);
    }

    public function countAssessmentQuestions(int $assessmentId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = :aid");
        $stmt->execute([':aid' => $assessmentId]);
        return (int)$stmt->fetchColumn();
    }

    private function saveAssessmentScore(int $userId, string $assessmentName, int $score, int $totalQuestions, int $answered): void
    {
        $slug = strtolower(str_replace(' ', '_', $assessmentName));
        $slug = preg_replace('/[^a-z_]/', '', $slug);

        $stmt = $this->pdo->prepare("SELECT id FROM student_assessment_scores WHERE student_id = :sid");
        $stmt->execute([':sid' => $userId]);
        $existing = $stmt->fetch();

        $scoreCol = $slug . '_score';
        $typeCol = $slug . '_type';

        $type = $score >= 80 ? 'High' : ($score >= 50 ? 'Moderate' : 'Low');

        if ($existing) {
            $sql = "UPDATE student_assessment_scores SET `$scoreCol` = :sc, `$typeCol` = :tp WHERE student_id = :sid";
        } else {
            $sql = "INSERT INTO student_assessment_scores (student_id, `$scoreCol`, `$typeCol`) VALUES (:sid, :sc, :tp)";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sid' => $userId, ':sc' => $score, ':tp' => $type]);
    }
}
