<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;
use PDOException;

class AdminDashboardRepository
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getTotalUsers(): int
    {
        return $this->countRows('users');
    }

    public function getTotalAssessments(): int
    {
        return $this->countRows('assessments');
    }

    public function getTotalQuestions(): int
    {
        return $this->countRows('questions');
    }

    public function getTotalCareers(): int
    {
        return $this->countRows('careers');
    }

    public function getRecentActivity(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT type, subject, detail, occurred_at FROM (
                    SELECT 'user_registered' AS type, username AS subject, '' AS detail, created_at AS occurred_at
                    FROM users
                    UNION ALL
                    SELECT 'assessment_completed' AS type, u.username AS subject, a.title AS detail, COALESCE(sa.completed_at, sa.created_at) AS occurred_at
                    FROM student_assessments sa
                    JOIN users u ON u.user_id = sa.user_id
                    JOIN assessments a ON a.assessment_id = sa.assessment_id
                    WHERE sa.status = 'completed'
                    UNION ALL
                    SELECT 'question_added' AS type, '' AS subject, LEFT(q.question_text, 80) AS detail, q.created_at AS occurred_at
                    FROM questions q
                ) combined
                ORDER BY occurred_at DESC
                LIMIT :limit
            ";

            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function checkDatabaseConnection(): bool
    {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function isAssessmentModuleActive(): bool
    {
        return $this->countRows('assessments') > 0;
    }

    public function isRecommendationModuleActive(): bool
    {
        return $this->countRows('careers') > 0;
    }

    private function countRows(string $table): int
    {
        try {
            $statement = $this->connection->query("SELECT COUNT(*) FROM `{$table}`");
            return (int)$statement->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }
}
