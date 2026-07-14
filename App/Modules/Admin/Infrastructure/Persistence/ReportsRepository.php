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

    public function getActiveUsersToday(): int
    {
        try {
            $stmt = $this->connection->query("
                SELECT COUNT(DISTINCT user_id) FROM student_assessments
                WHERE DATE(created_at) = CURDATE()
            ");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getAverageAssessmentScore(): float
    {
        try {
            $stmt = $this->connection->query("
                SELECT COALESCE(AVG(total_score), 0) FROM student_assessments
                WHERE status = 'completed' AND total_score IS NOT NULL
            ");
            return round((float)$stmt->fetchColumn(), 2);
        } catch (PDOException) {
            return 0;
        }
    }

    public function getAssessmentCompletionTrend(?string $period = null): array
    {
        try {
            $sql = "
                SELECT
                    DATE_FORMAT(completed_at, '%Y-%m') AS ym,
                    COUNT(*) AS count
                FROM student_assessments
                WHERE status = 'completed' AND completed_at IS NOT NULL
            ";
            $sql = $this->applyAnd($sql, 'completed_at', $period);
            $sql .= " GROUP BY ym ORDER BY ym ASC";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getStudentPerformance(int $limit = 10): array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT
                    u.username AS student_name,
                    a.title AS assessment_title,
                    sa.completed_at,
                    sa.total_score,
                    COALESCE(cr.match_score, 0) AS career_match_score,
                    c.career_name
                FROM student_assessments sa
                JOIN users u ON u.user_id = sa.user_id
                JOIN assessments a ON a.assessment_id = sa.assessment_id
                LEFT JOIN career_recommendations cr ON cr.user_id = sa.user_id
                LEFT JOIN careers c ON c.career_id = cr.career_id
                WHERE sa.status = 'completed'
                ORDER BY sa.completed_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getSummaryStatsForRange(?string $startDate = null, ?string $endDate = null): array
    {
        try {
            $dateFilter = '';
            $params = [];
            if ($startDate !== null) {
                $dateFilter = ' AND created_at >= :start_date';
                $params[':start_date'] = $startDate;
            }
            if ($endDate !== null) {
                $dateFilter .= ' AND created_at <= :end_date';
                $params[':end_date'] = $endDate;
            }

            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE user_role_id = 2{$dateFilter}");
            $stmt->execute($params);
            $totalStudents = (int)$stmt->fetchColumn();

            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM student_assessments WHERE status = 'completed'{$dateFilter}");
            $stmt->execute($params);
            $completedAssessments = (int)$stmt->fetchColumn();

            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM career_recommendations{$dateFilter}");
            $stmt->execute($params);
            $totalRecommendations = (int)$stmt->fetchColumn();

            $stmt = $this->connection->prepare("
                SELECT COALESCE(AVG(rate), 0) FROM (
                    SELECT CASE WHEN COUNT(*) = 0 THEN 0
                        ELSE SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(*)
                    END as rate
                    FROM student_assessments
                    WHERE 1=1{$dateFilter}
                    GROUP BY user_id
                ) sub
            ");
            $stmt->execute($params);
            $avgCompletionRate = round((float)$stmt->fetchColumn(), 1);

            $stmt = $this->connection->prepare("SELECT COUNT(DISTINCT user_id) FROM student_assessments{$dateFilter}");
            $stmt->execute($params);
            $activeUsers = (int)$stmt->fetchColumn();

            $stmt = $this->connection->prepare("
                SELECT COALESCE(AVG(total_score), 0) FROM student_assessments
                WHERE status = 'completed' AND total_score IS NOT NULL{$dateFilter}
            ");
            $stmt->execute($params);
            $avgScore = round((float)$stmt->fetchColumn(), 2);

            return [
                'total_students' => $totalStudents,
                'completed_assessments' => $completedAssessments,
                'total_recommendations' => $totalRecommendations,
                'avg_completion_rate' => $avgCompletionRate,
                'active_users' => $activeUsers,
                'avg_score' => $avgScore,
            ];
        } catch (PDOException) {
            return [
                'total_students' => 0,
                'completed_assessments' => 0,
                'total_recommendations' => 0,
                'avg_completion_rate' => 0,
                'active_users' => 0,
                'avg_score' => 0,
            ];
        }
    }

    public function getReportsGeneratedCount(): int
    {
        try {
            $stmt = $this->connection->query("
                SELECT COUNT(DISTINCT user_id) FROM student_assessments WHERE status = 'completed'
            ");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getRecentActivities(int $limit = 10): array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT type, subject, detail, occurred_at, user_id FROM (
                    SELECT 'user_registered' AS type, u.username AS subject, '' AS detail, u.created_at AS occurred_at, u.user_id
                    FROM users u WHERE u.user_role_id = 2
                    UNION ALL
                    SELECT 'assessment_completed' AS type, u.username AS subject, a.title AS detail, COALESCE(sa.completed_at, sa.created_at) AS occurred_at, u.user_id
                    FROM student_assessments sa
                    JOIN users u ON u.user_id = sa.user_id
                    JOIN assessments a ON a.assessment_id = sa.assessment_id
                    WHERE sa.status = 'completed'
                    UNION ALL
                    SELECT 'recommendation_generated' AS type, u.username AS subject, c.career_name AS detail, cr.created_at AS occurred_at, u.user_id
                    FROM career_recommendations cr
                    JOIN users u ON u.user_id = cr.user_id
                    JOIN careers c ON c.career_id = cr.career_id
                    UNION ALL
                    SELECT 'assessment_created' AS type, 'Admin' AS subject, a.title AS detail, a.created_at AS occurred_at, 0 AS user_id
                    FROM assessments a
                ) combined
                ORDER BY occurred_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

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
