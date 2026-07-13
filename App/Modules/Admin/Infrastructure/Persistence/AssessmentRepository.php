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
        return $this->getFilteredAssessments($search, null, null);
    }

    public function getFilteredAssessments(?string $search = null, ?string $status = null, ?string $sort = null): array
    {
        try {
            $conditions = [];
            $params = [];

            if ($search !== null && $search !== '') {
                $conditions[] = 'LOWER(a.title) LIKE :search';
                $params[':search'] = '%' . strtolower($search) . '%';
            }

            if ($status !== null && $status !== '') {
                $conditions[] = 'a.status = :status';
                $params[':status'] = $status;
            }

            $where = $conditions !== [] ? 'WHERE ' . implode(' AND ', $conditions) : '';

            $order = match ($sort) {
                'oldest' => 'a.created_at ASC',
                'most_questions' => 'total_questions DESC, a.created_at DESC',
                default => 'a.created_at DESC',
            };

            $sql = "
                SELECT a.assessment_id, a.title, a.description, a.category, a.status, a.created_at,
                       COUNT(q.question_id) AS total_questions
                FROM assessments a
                LEFT JOIN questions q ON q.assessment_id = a.assessment_id
                {$where}
                GROUP BY a.assessment_id
                ORDER BY {$order}
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

    public function getStudentsCompletedCount(): int
    {
        try {
            $stmt = $this->connection->query("SELECT COUNT(DISTINCT user_id) FROM student_assessments WHERE status = 'completed'");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getStudentsCompletedAllCount(): int
    {
        try {
            $totalActive = $this->getTotalAssessments();
            if ($totalActive === 0) return 0;
            $stmt = $this->connection->prepare("
                SELECT COUNT(*) FROM (
                    SELECT user_id, COUNT(DISTINCT assessment_id) AS completed_count
                    FROM student_assessments
                    WHERE status = 'completed'
                    GROUP BY user_id
                    HAVING completed_count >= :total
                ) AS full_completers
            ");
            $stmt->execute([':total' => $totalActive]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getAverageCompletionRate(): float
    {
        try {
            $totalActive = $this->getTotalAssessments();
            if ($totalActive === 0) return 0.0;
            $stmt = $this->connection->prepare("
                SELECT ROUND(AVG(completion_pct), 1) AS avg_rate FROM (
                    SELECT user_id,
                           ROUND(COUNT(DISTINCT CASE WHEN status = 'completed' THEN assessment_id END) / :total * 100, 1) AS completion_pct
                    FROM student_assessments
                    GROUP BY user_id
                ) AS per_user
            ");
            $stmt->execute([':total' => $totalActive]);
            $val = $stmt->fetchColumn();
            return $val !== false && $val !== null ? (float)$val : 0.0;
        } catch (PDOException) {
            return 0.0;
        }
    }

    public function getStudentCompletionsByAssessment(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT assessment_id, COUNT(DISTINCT user_id) AS students_completed
                FROM student_assessments
                WHERE status = 'completed'
                GROUP BY assessment_id
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $map = [];
            foreach ($rows as $row) {
                $map[(int)$row['assessment_id']] = (int)$row['students_completed'];
            }
            return $map;
        } catch (PDOException) {
            return [];
        }
    }

    public function getPerAssessmentCompletionData(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT a.assessment_id, a.title, a.category,
                       COUNT(DISTINCT sa.user_id) AS completed_count
                FROM assessments a
                LEFT JOIN student_assessments sa ON sa.assessment_id = a.assessment_id AND sa.status = 'completed'
                GROUP BY a.assessment_id
                ORDER BY a.assessment_id ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDailyCompletionTrend(int $days = 7): array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT DATE(completed_at) AS date, COUNT(DISTINCT user_id) AS completions
                FROM student_assessments
                WHERE status = 'completed' AND completed_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(completed_at)
                ORDER BY date ASC
            ");
            $stmt->execute([':days' => $days]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $found = null;
                foreach ($rows as $r) {
                    if (($r['date'] ?? '') === $date) {
                        $found = (int)$r['completions'];
                        break;
                    }
                }
                $result[] = ['date' => $date, 'label' => date('D', strtotime($date)), 'completions' => $found ?? 0];
            }
            return $result;
        } catch (PDOException) {
            return [];
        }
    }

    public function getRecentActivity(int $limit = 5): array
    {
        try {
            $stmt = $this->connection->prepare("
                (SELECT 'assessment_created' AS action_type, CONCAT('Created ', a.title) AS subject, a.created_at AS occurred_at
                 FROM assessments a)
                UNION ALL
                (SELECT 'assessment_completed', CONCAT(a.title, ' completed by ', u.username), sa.completed_at
                 FROM student_assessments sa
                 JOIN users u ON u.user_id = sa.user_id
                 JOIN assessments a ON a.assessment_id = sa.assessment_id
                 WHERE sa.status = 'completed' AND sa.completed_at IS NOT NULL)
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

    public function getRecentCompletedAssessments(int $limit = 5): array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT sa.student_assessment_id, sa.user_id, sa.assessment_id, sa.completed_at,
                       u.username AS student_name,
                       a.title AS assessment_title,
                       (SELECT COUNT(*) FROM questions WHERE assessment_id = a.assessment_id) AS question_count,
                       CASE sa.assessment_id
                           WHEN 1 THEN sas.personality_score
                           WHEN 2 THEN sas.interest_score
                           WHEN 3 THEN sas.aptitude_score
                           WHEN 4 THEN sas.values_score
                       END AS total_score
                FROM student_assessments sa
                JOIN users u ON u.user_id = sa.user_id
                JOIN assessments a ON a.assessment_id = sa.assessment_id
                LEFT JOIN student_assessment_scores sas ON sas.student_id = sa.user_id
                WHERE sa.status = 'completed' AND sa.completed_at IS NOT NULL
                ORDER BY sa.completed_at DESC
                LIMIT :lim
            ");
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$row) {
                $row['total_score'] = $row['total_score'] !== null ? (float)$row['total_score'] : 0;
            }
            unset($row);
            return $rows;
        } catch (PDOException) {
            return [];
        }
    }

    public function getAverageScoresByAssessment(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT a.assessment_id,
                       CASE a.assessment_id
                           WHEN 1 THEN (SELECT ROUND(AVG(personality_score), 1) FROM student_assessment_scores WHERE personality_score > 0)
                           WHEN 2 THEN (SELECT ROUND(AVG(interest_score), 1) FROM student_assessment_scores WHERE interest_score > 0)
                           WHEN 3 THEN (SELECT ROUND(AVG(aptitude_score), 1) FROM student_assessment_scores WHERE aptitude_score > 0)
                           WHEN 4 THEN (SELECT ROUND(AVG(values_score), 1) FROM student_assessment_scores WHERE values_score > 0)
                       END AS avg_score,
                       CASE a.assessment_id
                           WHEN 1 THEN (SELECT COUNT(*) FROM student_assessment_scores WHERE personality_score > 0)
                           WHEN 2 THEN (SELECT COUNT(*) FROM student_assessment_scores WHERE interest_score > 0)
                           WHEN 3 THEN (SELECT COUNT(*) FROM student_assessment_scores WHERE aptitude_score > 0)
                           WHEN 4 THEN (SELECT COUNT(*) FROM student_assessment_scores WHERE values_score > 0)
                       END AS completed_count
                FROM assessments a
                ORDER BY a.assessment_id
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAverageScore(): float
    {
        try {
            $stmt = $this->connection->query("
                SELECT ROUND(AVG(score), 1) FROM (
                    SELECT personality_score AS score FROM student_assessment_scores WHERE personality_score > 0
                    UNION ALL
                    SELECT interest_score FROM student_assessment_scores WHERE interest_score > 0
                    UNION ALL
                    SELECT aptitude_score FROM student_assessment_scores WHERE aptitude_score > 0
                    UNION ALL
                    SELECT values_score FROM student_assessment_scores WHERE values_score > 0
                ) AS all_scores
            ");
            $val = $stmt->fetchColumn();
            return $val !== false && $val !== null ? (float)$val : 0.0;
        } catch (PDOException) {
            return 0.0;
        }
    }

    public function getTotalStudentCount(): int
    {
        try {
            $stmt = $this->connection->query("
                SELECT COUNT(*) FROM users u
                JOIN master_data r ON r.id = u.user_role_id AND r.category = 'user_role'
                WHERE (r.label = 'student' OR u.user_role_id = 2)
            ");
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function duplicateAssessment(int $id, string $newTitle): ?array
    {
        try {
            $original = $this->getAssessmentById($id);
            if (!$original) return null;

            $this->connection->beginTransaction();

            $stmt = $this->connection->prepare("
                INSERT INTO assessments (title, description, category, status, created_at)
                VALUES (:title, :description, :category, 'inactive', NOW())
            ");
            $stmt->execute([
                ':title' => $newTitle,
                ':description' => $original['description'] ?? '',
                ':category' => $original['category'] ?? '',
            ]);
            $newId = (int)$this->connection->lastInsertId();

            $qStmt = $this->connection->prepare("
                SELECT question_text, question_type, question_order
                FROM questions WHERE assessment_id = :aid
            ");
            $qStmt->execute([':aid' => $id]);
            $questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($questions !== []) {
                $insert = $this->connection->prepare("
                    INSERT INTO questions (assessment_id, question_text, question_type, question_order)
                    VALUES (:assessment_id, :question_text, :question_type, :question_order)
                ");
                foreach ($questions as $q) {
                    $insert->execute([
                        ':assessment_id' => $newId,
                        ':question_text' => $q['question_text'],
                        ':question_type' => $q['question_type'],
                        ':question_order' => $q['question_order'],
                    ]);
                }
            }

            $this->connection->commit();
            return $this->getAssessmentById($newId);
        } catch (PDOException) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            return null;
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