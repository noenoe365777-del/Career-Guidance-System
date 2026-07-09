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

    public function getAllQuestions(int $page = 1, int $perPage = 10, string $search = '', ?int $assessmentFilter = null, ?string $typeFilter = null): array
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

        if ($assessmentFilter !== null && $assessmentFilter > 0) {
            $conditions[] = 'q.assessment_id = :assessment_id';
            $params[':assessment_id'] = $assessmentFilter;
        }

        if ($typeFilter !== null && $typeFilter !== '') {
            $conditions[] = 'q.question_type = :question_type';
            $params[':question_type'] = $typeFilter;
        }

        $where = '';
        if ($conditions !== []) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $selectSql = "
            SELECT q.question_id, q.question_text, q.assessment_id, q.question_type, q.question_order, q.created_at,
                   a.title AS assessment_title,
                   COUNT(qo.option_id) AS option_count
            FROM questions q
            JOIN assessments a ON a.assessment_id = q.assessment_id
            LEFT JOIN question_options qo ON qo.question_id = q.question_id
            {$where}
            GROUP BY q.question_id
            ORDER BY q.question_id ASC
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*)
            FROM questions q
            {$where}
        ";

        try {
            $countStmt = $this->connection->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = (int)$countStmt->fetchColumn();

            $stmt = $this->connection->prepare($selectSql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
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
                       a.title AS assessment_title
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
}
