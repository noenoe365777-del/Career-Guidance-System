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

    public function getTotalStudents(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM users WHERE user_role_id = 2");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalAssessments(): int
    {
        return $this->countRows('assessments');
    }

    public function getTotalQuestions(): int
    {
        return $this->countRows('assessment_questions');
    }

    public function getTotalCareers(): int
    {
        return $this->countRows('careers');
    }

    public function getTotalAssessmentAttempts(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM student_assessments");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalRecommendations(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM career_recommendations");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getAssessmentCompletionByCategory(): array
    {
        try {
            $sql = "
                SELECT
                    a.assessment_id,
                    a.title,
                    a.category,
                    COUNT(sa.student_assessment_id) AS total_taken,
                    SUM(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) AS completed
                FROM assessments a
                LEFT JOIN student_assessments sa ON sa.assessment_id = a.assessment_id
                GROUP BY a.assessment_id, a.title, a.category
                ORDER BY a.title
            ";
            $stmt = $this->connection->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getTopRecommendedCareers(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT
                    c.career_name,
                    c.career_icon,
                    COUNT(cr.recommendation_id) AS recommendation_count,
                    ROUND(AVG(cr.match_score), 2) AS avg_score
                FROM career_recommendations cr
                JOIN careers c ON c.career_id = cr.career_id
                GROUP BY c.career_id, c.career_name, c.career_icon
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

    public function getRecentActivity(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT type, subject, description, occurred_at FROM (
                    SELECT 'assessment_completed' AS type,
                           u.username AS subject,
                           CONCAT('completed ', a.title) AS description,
                           COALESCE(sa.completed_at, sa.started_at) AS occurred_at
                    FROM student_assessments sa
                    JOIN users u ON u.user_id = sa.user_id
                    JOIN assessments a ON a.assessment_id = sa.assessment_id
                    WHERE sa.status = 'completed'

                    UNION ALL

                    SELECT 'career_added' AS type,
                           'Admin' AS subject,
                           CONCAT('added ', c.career_name, ' career') AS description,
                           c.created_at AS occurred_at
                    FROM careers c

                    UNION ALL

                    SELECT 'question_added' AS type,
                           'Admin' AS subject,
                           CONCAT('added a question to ', a.title) AS description,
                           q.created_at AS occurred_at
                    FROM assessment_questions q
                    JOIN assessments a ON a.assessment_id = q.assessment_id

                    UNION ALL

                    SELECT 'recommendation_generated' AS type,
                           u.username AS subject,
                           'received a career recommendation' AS description,
                           cr.created_at AS occurred_at
                    FROM career_recommendations cr
                    JOIN users u ON u.user_id = cr.user_id

                    UNION ALL

                    SELECT 'assessment_added' AS type,
                           'Admin' AS subject,
                           CONCAT('created ', a.title, ' assessment') AS description,
                           a.created_at AS occurred_at
                    FROM assessments a
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

    public function getRecentlyAddedCareers(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT career_id, career_name, career_icon, created_at
                FROM careers
                ORDER BY created_at DESC
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

    public function getRecentlyAddedQuestions(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT q.id AS question_id, q.question AS question_text, q.created_at, a.title AS assessment_title
                FROM assessment_questions q
                JOIN assessments a ON a.assessment_id = q.assessment_id
                ORDER BY q.created_at DESC
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

    public function getRecentStudents(int $limit = 5): array
    {
        try {
            $sql = "
                SELECT
                    u.user_id,
                    u.username,
                    u.created_at AS registered_at,
                    sp.profile_image,
                    COALESCE(e.label, '') AS education_level
                FROM users u
                LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
                LEFT JOIN master_data e ON e.id = sp.education_level_id
                WHERE u.user_role_id = 2
                ORDER BY u.created_at DESC
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

    public function getRecentNotifications(int $limit = 5): array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT id, type, title, message, link, is_read, created_at
                FROM notifications
                ORDER BY created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAssessmentCompletionStats(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT
                    a.assessment_id,
                    a.title,
                    a.category,
                    COUNT(sa.student_assessment_id) AS total_attempts,
                    SUM(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                    ROUND(AVG(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) * 100, 1) AS completion_rate
                FROM assessments a
                LEFT JOIN student_assessments sa ON sa.assessment_id = a.assessment_id
                GROUP BY a.assessment_id, a.title, a.category
                ORDER BY a.assessment_id ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getTotalStudentsActive(): int
    {
        try {
            $stmt = $this->connection->query("
                SELECT COUNT(DISTINCT sa.user_id) FROM student_assessments sa
            ");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getUnreadNotificationCount(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTodayRegistrations(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE() AND user_role_id = 2");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTodayCompletions(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM student_assessments WHERE DATE(completed_at) = CURDATE() AND status = 'completed'");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
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
