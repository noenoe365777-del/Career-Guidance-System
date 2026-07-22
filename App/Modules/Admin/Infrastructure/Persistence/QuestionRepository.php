<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\QuestionRepositoryInterface;
use PDO;
use PDOStatement;
use PDOException;

class QuestionRepository implements QuestionRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    private function bindParams(PDOStatement $stmt, array $params): void
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

    private function rowToArray(array $row): array
    {
        $optionCount = (int)($row['option_count'] ?? 0);
        return [
            'question_id'        => (int)($row['question_id'] ?? $row['id'] ?? 0),
            'question_text'      => (string)($row['question_text'] ?? $row['question'] ?? ''),
            'assessment_id'      => (int)$row['assessment_id'],
            'question_type'      => (string)($row['question_type'] ?? ''),
            'question_order'     => (int)($row['question_order'] ?? 0),
            'created_at'         => $row['created_at'] ?? null,
            'assessment_title'   => $row['assessment_title'] ?? '',
            'assessment_category'=> $row['assessment_category'] ?? '',
            'option_count'       => $optionCount,
            'status'             => $optionCount >= 2 ? 'active' : 'incomplete',
        ];
    }

    private function buildQuestionSelect(string $alias = 'aq'): string
    {
        return "
            {$alias}.id AS question_id,
            {$alias}.question AS question_text,
            {$alias}.assessment_id,
            {$alias}.created_at,
            a.title AS assessment_title,
            a.category AS assessment_category,
            (CASE WHEN {$alias}.option_a IS NOT NULL AND {$alias}.option_a != '' THEN 1 ELSE 0 END) +
            (CASE WHEN {$alias}.option_b IS NOT NULL AND {$alias}.option_b != '' THEN 1 ELSE 0 END) +
            (CASE WHEN {$alias}.option_c IS NOT NULL AND {$alias}.option_c != '' THEN 1 ELSE 0 END) +
            (CASE WHEN {$alias}.option_d IS NOT NULL AND {$alias}.option_d != '' THEN 1 ELSE 0 END) AS option_count
        ";
    }

    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?string $assessmentFilter = null, ?string $typeFilter = null, ?string $difficultyFilter = null, ?string $statusFilter = null, ?string $sort = null): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = 'LOWER(aq.question) LIKE :search';
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
                $conditions[] = "aq.assessment_id IN ({$placeholders})";
            }
        }

        $where = $conditions !== [] ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $order = match ($sort) {
            'oldest' => 'aq.created_at ASC',
            'alpha'  => 'aq.question ASC',
            default  => 'aq.created_at DESC',
        };

        $selectCols = $this->buildQuestionSelect('aq');

        $selectSql = "
            SELECT {$selectCols}
            FROM assessment_questions aq
            JOIN assessments a ON a.assessment_id = aq.assessment_id
            {$where}
            ORDER BY {$order}
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*)
            FROM assessment_questions aq
            JOIN assessments a ON a.assessment_id = aq.assessment_id
            {$where}
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

            $questions = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questions[] = $this->rowToArray($row);
            }

            return [
                'questions'     => $questions,
                'total'         => $total,
                'totalQuestions'=> $total,
                'currentPage'   => $page,
                'perPage'       => $perPage,
                'totalPages'    => (int)ceil($total / $perPage),
            ];
        } catch (PDOException) {
            return [
                'questions'     => [],
                'total'         => 0,
                'totalQuestions'=> 0,
                'currentPage'   => $page,
                'perPage'       => $perPage,
                'totalPages'    => 1,
            ];
        }
    }

    public function getQuestionById(int $id): ?array
    {
        try {
            $selectCols = $this->buildQuestionSelect('aq');
            $stmt = $this->connection->prepare("
                SELECT {$selectCols}
                FROM assessment_questions aq
                JOIN assessments a ON a.assessment_id = aq.assessment_id
                WHERE aq.id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            return $this->rowToArray($row);
        } catch (PDOException) {
            return null;
        }
    }

    public function getTotalQuestions(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM assessment_questions');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalOptions(): int
    {
        try {
            $stmt = $this->connection->query("
                SELECT COUNT(*)
                FROM assessment_questions
                WHERE option_a IS NOT NULL AND option_a != ''
                   OR option_b IS NOT NULL AND option_b != ''
                   OR option_c IS NOT NULL AND option_c != ''
                   OR option_d IS NOT NULL AND option_d != ''
            ");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getQuestionsCountByAssessment(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT a.assessment_id, a.title, COUNT(aq.id) AS question_count
                FROM assessments a
                LEFT JOIN assessment_questions aq ON aq.assessment_id = a.assessment_id
                GROUP BY a.assessment_id, a.title
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
                SELECT 'single_choice' AS question_type, COUNT(*) AS question_count
                FROM assessment_questions
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
                        WHEN (option_a IS NOT NULL AND option_a != '') + (option_b IS NOT NULL AND option_b != '') + (option_c IS NOT NULL AND option_c != '') + (option_d IS NOT NULL AND option_d != '') <= 2 THEN 'easy'
                        WHEN (option_a IS NOT NULL AND option_a != '') + (option_b IS NOT NULL AND option_b != '') + (option_c IS NOT NULL AND option_c != '') + (option_d IS NOT NULL AND option_d != '') <= 4 THEN 'medium'
                        ELSE 'hard'
                    END AS difficulty,
                    COUNT(*) AS question_count
                FROM assessment_questions
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
                SELECT 'draft' AS status, COUNT(*) AS question_count
                FROM assessment_questions
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
                SELECT COUNT(*) FROM assessment_questions
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
                SELECT 'created' AS action_type,
                       aq.question AS subject,
                       a.title AS assessment_name,
                       aq.created_at AS occurred_at
                FROM assessment_questions aq
                JOIN assessments a ON a.assessment_id = aq.assessment_id
                ORDER BY aq.created_at DESC
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
            $stmt = $this->connection->prepare("
                SELECT option_a, option_b, option_c, option_d
                FROM assessment_questions
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $questionId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return [];

            $options = [];
            $position = 1;
            foreach (['option_a', 'option_b', 'option_c', 'option_d'] as $col) {
                $value = $row[$col] ?? null;
                if ($value !== null && $value !== '') {
                    $options[] = [
                        'option_id'   => $position,
                        'option_text' => (string)$value,
                        'option_value'=> 0,
                        'option_order'=> $position,
                    ];
                }
                $position++;
            }
            return $options;
        } catch (PDOException) {
            return [];
        }
    }

    public function hasQuestionColumn(string $columnName): bool
    {
        try {
            $stmt = $this->connection->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = :column_name');
            $stmt->execute([':table_name' => 'assessment_questions', ':column_name' => $columnName]);
            return (int)$stmt->fetchColumn() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function createQuestion(array $data): ?int
    {
        try {
            $sql = "INSERT INTO assessment_questions (assessment_id, question, option_a, option_b, option_c, option_d, correct_answer, weight, education_level_id)
                    VALUES (:assessment_id, :question, :option_a, :option_b, :option_c, :option_d, :correct_answer, :weight, :education_level_id)";

            $params = [
                ':assessment_id'      => $data['assessment_id'],
                ':question'           => $data['question_text'] ?? $data['question'] ?? '',
                ':option_a'           => $data['option_a'] ?? '',
                ':option_b'           => $data['option_b'] ?? '',
                ':option_c'           => $data['option_c'] ?? '',
                ':option_d'           => $data['option_d'] ?? '',
                ':correct_answer'     => $data['correct_answer'] ?? null,
                ':weight'             => $data['weight'] ?? null,
                ':education_level_id' => $data['education_level_id'] ?? null,
            ];

            error_log('[QuestionRepository] createQuestion SQL: ' . $sql);
            error_log('[QuestionRepository] createQuestion params: ' . print_r($params, true));

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            $newId = (int)$this->connection->lastInsertId();
            error_log('[QuestionRepository] createQuestion succeeded, id=' . $newId);
            return $newId;
        } catch (PDOException $e) {
            error_log('[QuestionRepository] createQuestion FAILED: ' . $e->getMessage());
            error_log('[QuestionRepository] createQuestion SQLSTATE: ' . $e->getCode());
            error_log('[QuestionRepository] createQuestion trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function updateQuestion(int $id, array $data): bool
    {
        try {
            $stmt = $this->connection->prepare("
                UPDATE assessment_questions
                SET assessment_id = :assessment_id,
                    question = :question,
                    option_a = :option_a,
                    option_b = :option_b,
                    option_c = :option_c,
                    option_d = :option_d,
                    correct_answer = :correct_answer,
                    weight = :weight,
                    education_level_id = :education_level_id
                WHERE id = :id
            ");
            return (bool)$stmt->execute([
                ':assessment_id'      => $data['assessment_id'],
                ':question'           => $data['question_text'] ?? $data['question'] ?? '',
                ':option_a'           => $data['option_a'] ?? '',
                ':option_b'           => $data['option_b'] ?? '',
                ':option_c'           => $data['option_c'] ?? '',
                ':option_d'           => $data['option_d'] ?? '',
                ':correct_answer'     => $data['correct_answer'] ?? null,
                ':weight'             => $data['weight'] ?? null,
                ':education_level_id' => $data['education_level_id'] ?? null,
                ':id'                 => $id,
            ]);
        } catch (PDOException $e) {
            error_log('[QuestionRepository] updateQuestion FAILED: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteQuestion(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM assessment_questions WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (PDOException) {
            return false;
        }
    }

    public function saveOptions(int $questionId, array $options): bool
    {
        try {
            $cols = ['option_a' => '', 'option_b' => '', 'option_c' => '', 'option_d' => ''];
            $keys = array_keys($cols);
            $index = 0;
            foreach ($options as $option) {
                if ($index >= 4) break;
                $cols[$keys[$index]] = $option['option_text'] ?? $option['option_value'] ?? '';
                $index++;
            }

            $stmt = $this->connection->prepare("
                UPDATE assessment_questions
                SET option_a = :option_a,
                    option_b = :option_b,
                    option_c = :option_c,
                    option_d = :option_d
                WHERE id = :id
            ");
            return $stmt->execute([
                ':option_a' => $cols['option_a'],
                ':option_b' => $cols['option_b'],
                ':option_c' => $cols['option_c'],
                ':option_d' => $cols['option_d'],
                ':id'       => $questionId,
            ]);
        } catch (PDOException) {
            return false;
        }
    }

    public function duplicateQuestion(int $id, ?int $targetAssessmentId = null): ?int
    {
        try {
            $original = $this->getQuestionById($id);
            if (!$original) return null;

            $stmt = $this->connection->prepare("
                SELECT assessment_id, question, option_a, option_b, option_c, option_d, correct_answer, weight, education_level_id
                FROM assessment_questions
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;

            $newId = $this->createQuestion([
                'assessment_id'      => $targetAssessmentId ?? (int)$row['assessment_id'],
                'question'           => (string)$row['question'],
                'option_a'           => $row['option_a'] ?? null,
                'option_b'           => $row['option_b'] ?? null,
                'option_c'           => $row['option_c'] ?? null,
                'option_d'           => $row['option_d'] ?? null,
                'correct_answer'     => $row['correct_answer'] ?? null,
                'weight'             => $row['weight'] ?? null,
                'education_level_id' => $row['education_level_id'] ?? null,
            ]);

            return $newId;
        } catch (PDOException) {
            return null;
        }
    }

    public function bulkDelete(array $ids): int
    {
        if ($ids === []) return 0;
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->connection->prepare("DELETE FROM assessment_questions WHERE id IN ({$placeholders})");
            $stmt->execute(array_map('intval', $ids));
            return $stmt->rowCount();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getQuestionsByCategorySlug(string $categorySlug, int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $slugMap = [
            'personality' => 1,
            'interest'    => 2,
            'aptitude'    => 3,
            'values'      => 4,
        ];

        $assessmentId = $slugMap[$categorySlug] ?? 0;
        if ($assessmentId === 0) {
            return [
                'questions'     => [],
                'total'         => 0,
                'totalQuestions'=> 0,
                'currentPage'   => $page,
                'perPage'       => $perPage,
                'totalPages'    => 1,
            ];
        }

        return $this->getAllQuestions($page, $perPage, $search, (string)$assessmentId);
    }
}
