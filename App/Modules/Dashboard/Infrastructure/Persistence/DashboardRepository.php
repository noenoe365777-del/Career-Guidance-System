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
                sa.status,
                sa.completed_at
            FROM assessments a
            LEFT JOIN student_assessments sa
                ON sa.assessment_id = a.assessment_id
                AND sa.user_id = ?
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$userId]);

        $status = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $slug = $this->mapAssessmentTitleToSlug((string)($row['title'] ?? ''));
            $status[$slug] = [
                'status' => $row['status'] ?? 'Locked',
                'completed_at' => $row['completed_at'] ?? null,
            ];
        }

        return $status;
    }

    private function mapAssessmentTitleToSlug(string $title): string
    {
        $normalized = strtolower(trim($title));

        if (str_contains($normalized, 'personality')) {
            return 'personality';
        }

        if (str_contains($normalized, 'interest')) {
            return 'interest';
        }

        if (str_contains($normalized, 'aptitude')) {
            return 'aptitude';
        }

        if (str_contains($normalized, 'value')) {
            return 'values';
        }

        return 'unknown';
    }

    /**
     * Total number of assessments in the system
     */
    public function getTotalAssessments(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM assessments');
            return (int)$stmt->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }

    /**
     * Question count per assessment_id from assessment_questions
     */
    public function getQuestionCountsByAssessment(): array
    {
        try {
            $stmt = $this->connection->query(
                'SELECT assessment_id, COUNT(*) AS question_count FROM assessment_questions GROUP BY assessment_id'
            );
            $map = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $map[(int)$row['assessment_id']] = (int)$row['question_count'];
            }
            return $map;
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Assessment ID-to-slug map from the assessments table
     */
    public function getAssessmentSlugMap(): array
    {
        try {
            $stmt = $this->connection->query('SELECT assessment_id, title FROM assessments ORDER BY assessment_id ASC');
            $map = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = (int)$row['assessment_id'];
                $slug = $this->mapAssessmentTitleToSlug((string)($row['title'] ?? ''));
                if ($slug !== 'unknown') {
                    $map[$id] = $slug;
                }
            }
            return $map;
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Recommendation
     */
    public function getRecommendation(int $userId): ?array
    {
        try {
            $sql = "
                SELECT cr.match_score, cr.recommendation_reason, c.career_name,
                       c.description, c.average_salary, c.growth_rate, c.education_required
                FROM career_recommendations cr
                JOIN careers c ON c.career_id = cr.career_id
                WHERE cr.user_id = ?
                ORDER BY cr.match_score DESC
                LIMIT 1
            ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\Throwable) {
            return null;
        }
    }
}