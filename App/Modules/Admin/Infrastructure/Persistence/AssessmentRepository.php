<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\AssessmentRepositoryInterface;
use PDO;
use PDOException;

class AssessmentRepository implements AssessmentRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getAllAssessments(?string $search = null): array
    {
        try {
            $conditions = '';
            $params = [];

            if ($search !== null && $search !== '') {
                $conditions = 'WHERE a.title LIKE :search';
                $params[':search'] = '%' . $search . '%';
            }

            $sql = "
                SELECT a.assessment_id, a.title, a.description, a.category, a.status, a.created_at,
                       COUNT(q.question_id) AS total_questions
                FROM assessments a
                LEFT JOIN questions q ON q.assessment_id = a.assessment_id
                {$conditions}
                GROUP BY a.assessment_id
                ORDER BY a.assessment_id ASC
            ";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAssessmentById(int $id): ?array
    {
        try {
            $sql = "
                SELECT a.assessment_id, a.title, a.description, a.category, a.status, a.created_at,
                       COUNT(q.question_id) AS total_questions
                FROM assessments a
                LEFT JOIN questions q ON q.assessment_id = a.assessment_id
                WHERE a.assessment_id = :id
                GROUP BY a.assessment_id
                LIMIT 1
            ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    public function getTotalAssessments(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM assessments');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getActiveAssessmentsCount(): int
    {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM assessments WHERE status = 'active'");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalQuestionsCount(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM questions');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function updateAssessmentStatus(int $id, string $status): bool
    {
        try {
            $stmt = $this->connection->prepare("UPDATE assessments SET status = :status WHERE assessment_id = :id");
            return (bool)$stmt->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException) {
            return false;
        }
    }

    public function updateAssessment(int $id, string $title, string $description): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE assessments SET title = :title, description = :description WHERE assessment_id = :id');
            return (bool)$stmt->execute([':title' => $title, ':description' => $description, ':id' => $id]);
        } catch (PDOException) {
            return false;
        }
    }
}
