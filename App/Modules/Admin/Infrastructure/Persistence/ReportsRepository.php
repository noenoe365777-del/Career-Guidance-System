<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\ReportsRepositoryInterface;
use PDO;
use PDOException;

class ReportsRepository implements ReportsRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getSummaryStats(?string $period = null): array
    {
        try {
            $totalStudents = (int)$this->connection
                ->query("SELECT COUNT(*) FROM users WHERE user_role_id = 2")
                ->fetchColumn();

            $completedAssessments = $this->countWithPeriod(
                "SELECT COUNT(*) FROM student_assessments WHERE status = 'completed'",
                'started_at',
                $period
            );

            $totalRecommendations = $this->countWithPeriod(
                'SELECT COUNT(*) FROM career_recommendations',
                'created_at',
                $period
            );

            $stmt = $this->connection->prepare("
                SELECT COALESCE(AVG(rate), 0) FROM (
                    SELECT CASE WHEN COUNT(*) = 0 THEN 0
                        ELSE SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(*)
                    END as rate
                    FROM student_assessments
                    GROUP BY user_id
                ) sub
            ");
            $stmt->execute();
            $avgCompletionRate = round((float)$stmt->fetchColumn(), 1);

            return [
                'total_students' => $totalStudents,
                'completed_assessments' => $completedAssessments,
                'total_recommendations' => $totalRecommendations,
                'avg_completion_rate' => $avgCompletionRate,
            ];
        } catch (PDOException) {
            return [
                'total_students' => 0,
                'completed_assessments' => 0,
                'total_recommendations' => 0,
                'avg_completion_rate' => 0,
            ];
        }
    }

    public function getPerAssessmentStats(?string $period = null): array
    {
        try {
            $periodFilter = $this->periodFilter('sa.started_at', $period);

            $sql = "
                SELECT
                    a.assessment_id,
                    a.title,
                    a.category AS assessment_type,
                    COUNT(sa.student_assessment_id) AS total_taken,
                    SUM(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) AS completed,
                    CASE WHEN COUNT(sa.student_assessment_id) = 0 THEN 0
                        ELSE ROUND(SUM(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(sa.student_assessment_id), 1)
                    END AS completion_rate
                FROM assessments a
                LEFT JOIN student_assessments sa ON sa.assessment_id = a.assessment_id {$periodFilter}
                GROUP BY a.assessment_id, a.title, a.category
                ORDER BY a.title
            ";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getTopRecommendedCareers(int $limit = 10, ?string $period = null): array
    {
        try {
            $sql = "
                SELECT
                    c.career_name,
                    COUNT(cr.recommendation_id) AS recommendation_count,
                    ROUND(AVG(cr.match_score), 2) AS avg_score
                FROM career_recommendations cr
                JOIN careers c ON c.career_id = cr.career_id
            ";
            $sql = $this->applyWhere($sql, 'cr.created_at', $period);
            $sql .= "
                GROUP BY c.career_id, c.career_name
                ORDER BY recommendation_count DESC, avg_score DESC
                LIMIT :limit
            ";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getStudentRegistrationTrend(?string $period = null): array
    {
        try {
            $sql = "
                SELECT
                    DATE_FORMAT(u.created_at, '%Y-%m') AS ym,
                    COUNT(*) AS count
                FROM users u
                WHERE u.user_role_id = 2
            ";
            $sql = $this->applyAnd($sql, 'u.created_at', $period);
            $sql .= "
                GROUP BY ym
                ORDER BY ym ASC
            ";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getEducationLevelDistribution(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT md.label AS education_level, COUNT(sp.profile_id) AS count
                FROM student_profiles sp
                JOIN master_data md ON md.id = sp.education_level_id
                WHERE sp.education_level_id IS NOT NULL
                GROUP BY sp.education_level_id, md.label
                ORDER BY count DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getMostCommonResultTypes(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT 'personality' AS assessment_name, personality_type AS type_label, COUNT(*) AS count
                FROM student_assessment_scores
                WHERE personality_type IS NOT NULL AND personality_type != ''
                GROUP BY personality_type
                UNION ALL
                SELECT 'interest', interest_type, COUNT(*)
                FROM student_assessment_scores
                WHERE interest_type IS NOT NULL AND interest_type != ''
                GROUP BY interest_type
                UNION ALL
                SELECT 'aptitude', aptitude_type, COUNT(*)
                FROM student_assessment_scores
                WHERE aptitude_type IS NOT NULL AND aptitude_type != ''
                GROUP BY aptitude_type
                UNION ALL
                SELECT 'values', values_type, COUNT(*)
                FROM student_assessment_scores
                WHERE values_type IS NOT NULL AND values_type != ''
                GROUP BY values_type
                ORDER BY assessment_name, count DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    private function countWithPeriod(string $baseSql, string $dateColumn, ?string $period): int
    {
        $sql = $this->applyWhere($baseSql, $dateColumn, $period);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function periodFilter(string $column, ?string $period): string
    {
        if ($period === 'this_month') {
            return "AND {$column} >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')";
        }
        if ($period === 'this_year') {
            return "AND YEAR({$column}) = YEAR(CURRENT_DATE)";
        }
        return '';
    }

    private function applyWhere(string $sql, string $column, ?string $period): string
    {
        if ($period === 'this_month') {
            $cond = "{$column} >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')";
        } elseif ($period === 'this_year') {
            $cond = "YEAR({$column}) = YEAR(CURRENT_DATE)";
        } else {
            return $sql;
        }
        if (preg_match('/\bWHERE\b/i', $sql)) {
            return $sql . " AND {$cond}";
        }
        return $sql . " WHERE {$cond}";
    }

    private function applyAnd(string $sql, string $column, ?string $period): string
    {
        if ($period === 'this_month') {
            return $sql . " AND {$column} >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')";
        }
        if ($period === 'this_year') {
            return $sql . " AND YEAR({$column}) = YEAR(CURRENT_DATE)";
        }
        return $sql;
    }
}
