<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\UserRepositoryInterface;
use PDO;
use PDOException;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function listUsers(int $page = 1, int $perPage = 10, string $search = '', ?string $statusFilter = null, ?string $assessmentStatus = null, ?int $educationLevel = null): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(LOWER(u.username) LIKE :search OR LOWER(u.email) LIKE :search)';
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        if ($educationLevel !== null && $educationLevel > 0) {
            $conditions[] = 'sp.education_level_id = :edu_level';
            $params[':edu_level'] = $educationLevel;
        }

        $assessmentJoin = '';
        $havingClause = '';
        if ($assessmentStatus !== null && $assessmentStatus !== '') {
            if ($assessmentStatus === 'completed') {
                $havingClause = 'HAVING completed_count > 0 AND completed_count = total_count';
            } elseif ($assessmentStatus === 'in_progress') {
                $havingClause = 'HAVING completed_count > 0 AND completed_count < total_count';
            } elseif ($assessmentStatus === 'not_started') {
                $havingClause = 'HAVING completed_count = 0';
            }
        }

        $where = '';
        if ($conditions !== []) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $selectSql = "
            SELECT u.user_id, u.username, u.email, u.user_role_id, u.status_id, u.created_at,
                   r.label AS role_name,
                   s.label AS status_name,
                   sp.education_level_id,
                   m.label AS education_level,
                   sp.profile_image,
                   COALESCE(astats.completed_count, 0) AS completed_count,
                   COALESCE(astats.total_count, 0) AS total_count
            FROM users u
            JOIN master_data r ON r.id = u.user_role_id AND r.category = 'user_role'
            JOIN master_data s ON s.id = u.status_id AND s.category = 'user_status'
            LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
            LEFT JOIN master_data m ON m.id = sp.education_level_id AND m.category = 'education_level'
            LEFT JOIN (
                SELECT sa.user_id,
                       COUNT(*) AS total_count,
                       SUM(CASE WHEN sa.status = 'completed' THEN 1 ELSE 0 END) AS completed_count
                FROM student_assessments sa
                GROUP BY sa.user_id
            ) astats ON astats.user_id = u.user_id
            {$where}
            {$havingClause}
            ORDER BY u.user_id DESC
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*)
            FROM users u
            JOIN master_data s ON s.id = u.status_id AND s.category = 'user_status'
            LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
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

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'users' => $users,
                'total' => $total,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => (int)ceil($total / $perPage),
            ];
        } catch (PDOException) {
            return [
                'users' => [],
                'total' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }
    }

    public function getTotalUsers(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM users');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getUserById(int $id): ?array
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT u.user_id, u.username, u.email, u.user_role_id, u.status_id, u.created_at,
                       r.label AS role_name,
                       s.label AS status_name,
                       sp.education_level_id,
                       m.label AS education_level,
                       sp.profile_image, sp.phone, sp.address, sp.date_of_birth
                FROM users u
                JOIN master_data r ON r.id = u.user_role_id AND r.category = 'user_role'
                JOIN master_data s ON s.id = u.status_id AND s.category = 'user_status'
                LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
                LEFT JOIN master_data m ON m.id = sp.education_level_id AND m.category = 'education_level'
                WHERE u.user_id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    public function getUserDetailForModal(int $id): ?array
    {
        try {
            $user = $this->getUserById($id);
            if ($user === null) return null;

            // Assessment progress
            $stmt = $this->connection->prepare("
                SELECT COUNT(*) AS total_count,
                       SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count
                FROM student_assessments
                WHERE user_id = :id
            ");
            $stmt->execute([':id' => $id]);
            $progress = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['completed_count'] = (int)($progress['completed_count'] ?? 0);
            $user['total_count'] = (int)($progress['total_count'] ?? 0);

            // Latest completed assessment
            $stmt = $this->connection->prepare("
                SELECT a.title, sa.completed_at
                FROM student_assessments sa
                JOIN assessments a ON a.assessment_id = sa.assessment_id
                WHERE sa.user_id = :id AND sa.status = 'completed'
                ORDER BY sa.completed_at DESC
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $latest = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['latest_assessment'] = $latest['title'] ?? null;
            $user['latest_assessment_date'] = $latest['completed_at'] ?? null;

            // Top career recommendation
            $stmt = $this->connection->prepare("
                SELECT c.career_name, cr.match_score
                FROM career_recommendations cr
                JOIN careers c ON c.career_id = cr.career_id
                WHERE cr.user_id = :id
                ORDER BY cr.match_score DESC
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            $user['top_career'] = $rec['career_name'] ?? null;
            $user['match_score'] = $rec['match_score'] ?? null;

            return $user;
        } catch (PDOException) {
            return null;
        }
    }

    public function updateUserStatus(int $id, int $statusId): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE users SET status_id = :status_id WHERE user_id = :id');
            return (bool)$stmt->execute([':status_id' => $statusId, ':id' => $id]);
        } catch (PDOException) {
            return false;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->prepare('DELETE FROM student_profiles WHERE user_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM student_answers WHERE student_assessment_id IN (SELECT student_assessment_id FROM student_assessments WHERE user_id = :id)')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM student_assessments WHERE user_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM career_recommendations WHERE user_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM student_assessment_scores WHERE student_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM users WHERE user_id = :id')->execute([':id' => $id]);
            $this->connection->commit();
            return true;
        } catch (PDOException) {
            $this->connection->rollBack();
            return false;
        }
    }

    public function getEducationLevels(): array
    {
        try {
            $stmt = $this->connection->query("
                SELECT id, label FROM master_data
                WHERE category = 'education_level'
                ORDER BY label
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }
}
