<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Infrastructure\Persistence;

use App\Config\Database;
use PDO;

class DashboardRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    /**
     * Count completed assessments
     */
    public function getCompletedAssessments(int $userId): int
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM student_assessments
            WHERE user_id = ?
            AND status = 'Completed'
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$userId]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Assessment status map
     */
    public function getAssessmentStatus(int $userId): array
    {
        $sql = "
            SELECT
                a.title,
                sa.status
            FROM assessments a
            LEFT JOIN student_assessments sa
                ON sa.assessment_id = a.assessment_id
                AND sa.user_id = ?
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$userId]);

        $status = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $status[$row['title']] =
                $row['status'] ?? 'Locked';

        }

        return $status;
    }

    /**
     * Recommendation
     */
    public function getRecommendation(int $userId): ?array
    {
        return null;
    }
}