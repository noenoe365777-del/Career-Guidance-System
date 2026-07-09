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

    public function listUsers(int $page = 1, int $perPage = 10, string $search = '', ?string $statusFilter = null): array
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

        if ($statusFilter !== null && $statusFilter !== '') {
            $conditions[] = 's.code = :status_code';
            $params[':status_code'] = $statusFilter;
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
                   sp.profile_image
            FROM users u
            JOIN master_data r ON r.id = u.user_role_id AND r.category = 'user_role'
            JOIN master_data s ON s.id = u.status_id AND s.category = 'user_status'
            LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
            LEFT JOIN master_data m ON m.id = sp.education_level_id AND m.category = 'education_level'
            {$where}
            ORDER BY u.user_id DESC
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*)
            FROM users u
            JOIN master_data s ON s.id = u.status_id AND s.category = 'user_status'
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
}
