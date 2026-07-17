<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\QuestionRepositoryInterface;
use PDO;
use PDOException;

class QuestionRepository implements QuestionRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    /**
     * Helper to bind params supporting both named and positional (0-based to 1-based)
     */
    private function bindParams(\PDOStatement $stmt, array $params): void
    {
        $positionalIndex = 1;
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $stmt->bindValue($positionalIndex++, $value);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
    }

    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?string $assessmentFilter = null, ?string $typeFilter = null, ?string $difficultyFilter = null, ?string $statusFilter = null, ?string $sort = null): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = 'LOWER(q.question_text) LIKE :search';
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        if ($assessmentFilter !== null && $assessmentFilter !== '' && $assessmentFilter !== '0') {
            $ids = array_filter(array_map('intval', explode(',', $assessmentFilter)), fn($id) => $id > 0);
            if ($ids !== []) {
                $namedParams = [];
                foreach ($ids as $i => $id) {
                    $key = ":aid_{$i}";
                    $namedParams[] = $key;
                    $params[$key] = $id;
                }
                $placeholders = implode(',', $namedParams);
                $conditions[] = "q.assessment_id IN ({$placeholders})";
            }
        }

        if ($typeFilter !== null && $typeFilter !== '') {
            $conditions[] = 'q.question_type = :question_type';
            $params[':question_type'] = $typeFilter;
        }

        $where = $conditions !== [] ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $havingConditions = [];
        if ($difficultyFilter !== null && $difficultyFilter !== '') {
            $havingConditions[] = 'difficulty = :difficulty';
            $params[':difficulty'] = $difficultyFilter;
        }
        if ($statusFilter !== null && $statusFilter !== '') {
            $havingConditions[] = 'status = :status';
            $params[':status'] = $statusFilter;
        }
        $having = $havingConditions !== [] ? 'HAVING ' . implode(' AND ', $havingConditions) : '';

        $order = match ($sort) {
            'oldest' => 'q.created_at ASC',
            'alpha' => 'q.question_text ASC',
            default => 'q.created_at DESC',
        };

        $selectSql = "
            SELECT q.question_id,
                   MAX(q.question_text) AS question_text,
                   MAX(q.assessment_id) AS assessment_id,
                   MAX(q.question_type) AS question_type,
                   MAX(q.question_order) AS question_order,
                   MAX(q.created_at) AS created_at,
                   MAX(a.title) AS assessment_title,
                   MAX(a.category) AS assessment_category,
                   COUNT(DISTINCT qo.option_id) AS option_count,
                   COUNT(DISTINCT sa.answer_id) AS response_count,
                   CASE
                       WHEN COUNT(DISTINCT qo.option_id) <= 2 THEN 'easy'
                       WHEN COUNT(DISTINCT qo.option_id) <= 4 THEN 'medium'
                       ELSE 'hard'
                   END AS difficulty,
                   CASE
                       WHEN COUNT(DISTINCT sa.answer_id) > 0 THEN 'used'
                       ELSE 'draft'
                   END AS status
            FROM questions q
            JOIN assessments a ON a.assessment_id = q.assessment_id
            LEFT JOIN question_options qo ON qo.question_id = q.question_id
            LEFT JOIN student_answers sa ON sa.question_id = q.question_id
            {$where}
            GROUP BY q.question_id
            {$having}
            ORDER BY {$order}
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*) FROM (
                SELECT q.question_id
                FROM questions q
                JOIN assessments a ON a.assessment_id = q.assessment_id
                LEFT JOIN question_options qo ON qo.question_id = q.question_id
                LEFT JOIN student_answers sa ON sa.question_id = q.question_id
                {$where}
                GROUP BY q.question_id
                {$having}
            ) AS filtered_questions
        ";

        try {
            $countStmt = $this->connection->prepare($countSql);
            $this->bindParams($countStmt, $params);
            $countStmt->execute();
            $total = (int)$countStmt->fetchColumn();

            $stmt = $this->connection->prepare($selectSql);
            $this->bindParams($stmt, $params);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'questions' => $questions,
                'total' => $total,
                'totalQuestions' => $total,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => (int)ceil($total / $perPage),
            ];
        } catch (PDOException) {
            return [
                'questions' => [],
                'total' => 0,
                'totalQuestions' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }
    }

    public function getQuestionById(int $id): ?array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT q.question_id, q.question_text, q.assessment_id, q.question_type, q.question_order, q.created_at,
                       a.title AS assessment_title,
                       a.category AS assessment_category
                FROM questions q
                JOIN assessments a ON a.assessment_id = q.assessment_id
                WHERE q.question_id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    public function getTotalQuestions(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM questions');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalOptions(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM question_options');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getQuestionsCountByAssessment(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT a.assessment_id, a.title, COUNT(q.question_id) AS question_count
                FROM assessments a
                LEFT JOIN questions q ON q.assessment_id = a.assessment_id
                GROUP BY a.assessment_id
                ORDER BY a.assessment_id ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getQuestionsCountByType(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT question_type, COUNT(*) AS question_count
                FROM questions
                GROUP BY question_type
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getQuestionsCountByDifficulty(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT
                    CASE
                        WHEN opt.opt_count <= 2 THEN 'easy'
                        WHEN opt.opt_count <= 4 THEN 'medium'
                        ELSE 'hard'
                    END AS difficulty,
                    COUNT(*) AS question_count
                FROM questions q
                LEFT JOIN (
                    SELECT question_id, COUNT(*) AS opt_count
                    FROM question_options
                    GROUP BY question_id
                ) opt ON opt.question_id = q.question_id
                GROUP BY difficulty
                ORDER BY difficulty
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getQuestionsCountByStatus(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT
                    CASE WHEN ans.ans_count > 0 THEN 'used' ELSE 'draft' END AS status,
                    COUNT(*) AS question_count
                FROM questions q
                LEFT JOIN (
                    SELECT question_id, COUNT(*) AS ans_count
                    FROM student_answers
                    GROUP BY question_id
                ) ans ON ans.question_id = q.question_id
                GROUP BY status
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAssessmentSlugMap(): array
    {
        try {
            $stmt = $this->connection->query("SELECT assessment_id, title, category FROM assessments ORDER BY assessment_id ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $map = [];
            foreach ($rows as $row) {
                $id = (int)$row['assessment_id'];
                $titleSlug = strtolower(str_replace(' ', '_', trim((string)$row['title'])));
                $map[$titleSlug] = $id;

                $catSlug = strtolower(trim((string)($row['category'] ?? '')));
                if ($catSlug !== '' && $catSlug !== $titleSlug && $catSlug !== 'assessment') {
                    $map[$catSlug] = $id;
                }
            }

            if (!isset($map['career_values']) && isset($map['values'])) {
                $map['career_values'] = $map['values'];
            }
            if (!isset($map['values']) && isset($map['career_values'])) {
                $map['values'] = $map['career_values'];
            }

            return $map;
        } catch (PDOException) {
            return [];
        }
    }

    public function getRecentlyAddedCount(int $days = 7): int
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT COUNT(*) FROM questions
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            ");
            $stmt->execute([':days' => $days]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getRecentQuestionActivity(int $limit = 5): array
    {
        try {
            $stmt = $this->connection->prepare("
                (SELECT 'created' AS action_type, q.question_text AS subject, a.title AS assessment_name, q.created_at AS occurred_at
                 FROM questions q
                 JOIN assessments a ON a.assessment_id = q.assessment_id)
                ORDER BY occurred_at DESC
                LIMIT :lim
            ");
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getOptionsByQuestionId(int $questionId): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM question_options WHERE question_id = :id ORDER BY option_order ASC');
            $stmt->execute([':id' => $questionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function hasQuestionColumn(string $columnName): bool
    {
        try {
            $stmt = $this->connection->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = :column_name');
            $stmt->execute([':table_name' => 'questions', ':column_name' => $columnName]);
            return (int)$stmt->fetchColumn() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function createQuestion(array $data): ?int
    {
        try {
            $stmt = $this->connection->prepare('
                INSERT INTO questions (assessment_id, question_text, question_type, question_order)
                VALUES (:assessment_id, :question_text, :question_type, :question_order)
            ');
            $stmt->execute([
                ':assessment_id' => $data['assessment_id'],
                ':question_text' => $data['question_text'],
                ':question_type' => $data['question_type'] ?? 'single_choice',
                ':question_order' => $data['question_order'] ?? 0,
            ]);
            return (int)$this->connection->lastInsertId();
        } catch (PDOException) {
            return null;
        }
    }

    public function updateQuestion(int $id, array $data): bool
    {
        try {
            $stmt = $this->connection->prepare('
                UPDATE questions
                SET assessment_id = :assessment_id,
                    question_text = :question_text,
                    question_type = :question_type,
                    question_order = :question_order
                WHERE question_id = :id
            ');
            return (bool)$stmt->execute([
                ':assessment_id' => $data['assessment_id'],
                ':question_text' => $data['question_text'],
                ':question_type' => $data['question_type'] ?? 'single_choice',
                ':question_order' => $data['question_order'] ?? 0,
                ':id' => $id,
            ]);
        } catch (PDOException) {
            return false;
        }
    }

    public function deleteQuestion(int $id): bool
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->prepare('DELETE FROM question_options WHERE question_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM questions WHERE question_id = :id')->execute([':id' => $id]);
            $this->connection->commit();
            return true;
        } catch (PDOException) {
            $this->connection->rollBack();
            return false;
        }
    }

    public function saveOptions(int $questionId, array $options): bool
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->prepare('DELETE FROM question_options WHERE question_id = :id')->execute([':id' => $questionId]);

            $stmt = $this->connection->prepare('
                INSERT INTO question_options (question_id, option_text, option_value, option_order)
                VALUES (:question_id, :option_text, :option_value, :option_order)
            ');

            foreach ($options as $option) {
                $stmt->execute([
                    ':question_id' => $questionId,
                    ':option_text' => $option['option_text'] ?? '',
                    ':option_value' => $option['option_value'] ?? 0,
                    ':option_order' => $option['option_order'] ?? 1,
                ]);
            }

            $this->connection->commit();
            return true;
        } catch (PDOException) {
            $this->connection->rollBack();
            return false;
        }
    }

    public function duplicateQuestion(int $id, ?int $targetAssessmentId = null): ?int
    {
        try {
            $original = $this->getQuestionById($id);
            if (!$original) return null;

            $this->connection->beginTransaction();

            $newId = $this->createQuestion([
                'assessment_id' => $targetAssessmentId ?? (int)$original['assessment_id'],
                'question_text' => (string)$original['question_text'],
                'question_type' => (string)$original['question_type'],
                'question_order' => (int)($original['question_order'] ?? 1),
            ]);

            if ($newId === null) {
                $this->connection->rollBack();
                return null;
            }

            $options = $this->getOptionsByQuestionId($id);
            if ($options !== []) {
                $optStmt = $this->connection->prepare('
                    INSERT INTO question_options (question_id, option_text, option_value, option_order)
                    VALUES (:question_id, :option_text, :option_value, :option_order)
                ');
                foreach ($options as $opt) {
                    $optStmt->execute([
                        ':question_id' => $newId,
                        ':option_text' => $opt['option_text'] ?? '',
                        ':option_value' => $opt['option_value'] ?? 0,
                        ':option_order' => $opt['option_order'] ?? 1,
                    ]);
                }
            }

            $this->connection->commit();
            return $newId;
        } catch (PDOException) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            return null;
        }
    }

    public function bulkDelete(array $ids): int
    {
        if ($ids === []) return 0;
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $this->connection->beginTransaction();
            $stmt1 = $this->connection->prepare("DELETE FROM question_options WHERE question_id IN ({$placeholders})");
            $stmt1->execute(array_map('intval', $ids));
            $stmt2 = $this->connection->prepare("DELETE FROM questions WHERE question_id IN ({$placeholders})");
            $stmt2->execute(array_map('intval', $ids));
            $this->connection->commit();
            return $stmt2->rowCount();
        } catch (PDOException) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            return 0;
        }
    }

    public function getQuestionsByCategorySlug(string $categorySlug, int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $slugMap = [
            'personality' => 1,
            'interest' => 2,
            'aptitude' => 3,
            'values' => 4,
        ];

        $assessmentId = $slugMap[$categorySlug] ?? 0;
        if ($assessmentId === 0) {
            return [
                'questions' => [],
                'total' => 0,
                'totalQuestions' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }

        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = ['q.assessment_id = :assessment_id'];
        $params = [':assessment_id' => $assessmentId];

        if ($search !== '') {
            $conditions[] = 'LOWER(q.question_text) LIKE :search';
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $order = 'q.created_at DESC';

        $selectSql = "
            SELECT q.question_id,
                   ANY_VALUE(q.question_text) AS question_text,
                   ANY_VALUE(q.assessment_id) AS assessment_id,
                   ANY_VALUE(q.question_type) AS question_type,
                   ANY_VALUE(q.question_order) AS question_order,
                   ANY_VALUE(q.created_at) AS created_at,
                   ANY_VALUE(a.title) AS assessment_title,
                   ANY_VALUE(a.category) AS assessment_category,
                   COUNT(DISTINCT qo.option_id) AS option_count,
                   COUNT(DISTINCT sa.answer_id) AS response_count,
                   CASE
                       WHEN COUNT(DISTINCT qo.option_id) <= 2 THEN 'easy'
                       WHEN COUNT(DISTINCT qo.option_id) <= 4 THEN 'medium'
                       ELSE 'hard'
                   END AS difficulty,
                   CASE
                       WHEN COUNT(DISTINCT sa.answer_id) > 0 THEN 'used'
                       ELSE 'draft'
                   END AS status
            FROM questions q
            JOIN assessments a ON a.assessment_id = q.assessment_id
            LEFT JOIN question_options qo ON qo.question_id = q.question_id
            LEFT JOIN student_answers sa ON sa.question_id = q.question_id
            {$where}
            GROUP BY q.question_id
            ORDER BY {$order}
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*) FROM (
                SELECT q.question_id
                FROM questions q
                JOIN assessments a ON a.assessment_id = q.assessment_id
                LEFT JOIN question_options qo ON qo.question_id = q.question_id
                LEFT JOIN student_answers sa ON sa.question_id = q.question_id
                {$where}
                GROUP BY q.question_id
            ) AS filtered_questions
        ";

        try {
            $countStmt = $this->connection->prepare($countSql);
            $this->bindParams($countStmt, $params);
            $countStmt->execute();
            $total = (int)$countStmt->fetchColumn();

            $stmt = $this->connection->prepare($selectSql);
            $this->bindParams($stmt, $params);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'questions' => $questions,
                'total' => $total,
                'totalQuestions' => $total,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => (int)ceil($total / $perPage),
            ];
        } catch (PDOException) {
            return [
                'questions' => [],
                'total' => 0,
                'totalQuestions' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }
    }
}