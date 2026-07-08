<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;

class UserModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function listUsers(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];

        if ($search !== '') {
            $where = 'WHERE LOWER(u.username) LIKE :search OR LOWER(u.email) LIKE :search';
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        $sql = "SELECT u.user_id, u.username, u.email, u.user_role_id, u.status_id, u.created_at, sp.education_level_id, r.role_name
                FROM users u
                LEFT JOIN roles r ON r.role_id = u.user_role_id
                LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
                {$where}
                ORDER BY u.user_id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll();

        $countSql = "SELECT COUNT(*) FROM users u {$where}";
        $countStmt = $this->pdo->prepare($countSql);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }

        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        return [
            'users' => $users,
            'total' => $total,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => (int)ceil($total / $perPage),
        ];
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.user_id, u.username, u.email, u.user_role_id, u.status_id, u.created_at, sp.education_level_id, sp.phone, sp.address, sp.date_of_birth, r.role_name
             FROM users u
             LEFT JOIN roles r ON r.role_id = u.user_role_id
             LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
             WHERE u.user_id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function createUser(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (username, email, password, user_role_id, status_id, is_verified, is_active, is_login, created_at, updated_at)
                 VALUES (:username, :email, :password, :user_role_id, :status_id, 1, 1, 0, NOW(), NOW())'
            );

            $stmt->execute([
                ':username' => trim((string)($data['username'] ?? '')),
                ':email' => trim((string)($data['email'] ?? '')),
                ':password' => password_hash((string)($data['password'] ?? ''), PASSWORD_DEFAULT),
                ':user_role_id' => (int)($data['user_role_id'] ?? 2),
                ':status_id' => (int)($data['status_id'] ?? 3),
            ]);

            $userId = (int)$this->pdo->lastInsertId();

            if ((int)($data['user_role_id'] ?? 2) === 2) {
                $profileStmt = $this->pdo->prepare(
                    'INSERT INTO student_profiles (user_id, education_level_id, phone, address, date_of_birth, created_at, updated_at)
                     VALUES (:user_id, :education_level_id, :phone, :address, :date_of_birth, NOW(), NOW())'
                );

                $profileStmt->execute([
                    ':user_id' => $userId,
                    ':education_level_id' => !empty($data['education_level_id']) ? (int)$data['education_level_id'] : null,
                    ':phone' => $data['phone'] ?? null,
                    ':address' => $data['address'] ?? null,
                    ':date_of_birth' => $data['date_of_birth'] ?? null,
                ]);
            }

            $this->pdo->commit();

            return $userId;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sql = 'UPDATE users SET username = :username, email = :email, user_role_id = :user_role_id, status_id = :status_id, updated_at = NOW()';
            $params = [
                ':username' => trim((string)($data['username'] ?? '')),
                ':email' => trim((string)($data['email'] ?? '')),
                ':user_role_id' => (int)($data['user_role_id'] ?? 2),
                ':status_id' => (int)($data['status_id'] ?? 3),
                ':id' => $id,
            ];

            if (!empty($data['password'])) {
                $sql .= ', password = :password';
                $params[':password'] = password_hash((string)$data['password'], PASSWORD_DEFAULT);
            }

            $sql .= ' WHERE user_id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            if ((int)($data['user_role_id'] ?? 2) === 2) {
                $existingProfile = $this->pdo->prepare('SELECT user_id FROM student_profiles WHERE user_id = :user_id LIMIT 1');
                $existingProfile->execute([':user_id' => $id]);

                if ($existingProfile->fetch()) {
                    $profileStmt = $this->pdo->prepare(
                        'UPDATE student_profiles SET education_level_id = :education_level_id, phone = :phone, address = :address, date_of_birth = :date_of_birth, updated_at = NOW() WHERE user_id = :user_id'
                    );
                } else {
                    $profileStmt = $this->pdo->prepare(
                        'INSERT INTO student_profiles (user_id, education_level_id, phone, address, date_of_birth, created_at, updated_at)
                         VALUES (:user_id, :education_level_id, :phone, :address, :date_of_birth, NOW(), NOW())'
                    );
                }

                $profileStmt->execute([
                    ':user_id' => $id,
                    ':education_level_id' => !empty($data['education_level_id']) ? (int)$data['education_level_id'] : null,
                    ':phone' => $data['phone'] ?? null,
                    ':address' => $data['address'] ?? null,
                    ':date_of_birth' => $data['date_of_birth'] ?? null,
                ]);
            }

            $this->pdo->commit();

            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function deleteUser(int $id): bool
    {
        $this->pdo->beginTransaction();

        try {
            $this->pdo->prepare('DELETE FROM student_profiles WHERE user_id = :id')->execute([':id' => $id]);
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE user_id = :id');
            $stmt->execute([':id' => $id]);
            $this->pdo->commit();

            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE LOWER(email) = LOWER(:email)';
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $sql .= ' AND user_id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE LOWER(username) = LOWER(:username)';
        $params = [':username' => $username];

        if ($excludeId !== null) {
            $sql .= ' AND user_id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function getRoles(): array
    {
        return [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Student'],
        ];
    }

    public function getStatuses(): array
    {
        return [
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'Inactive'],
            ['id' => 3, 'name' => 'Pending'],
        ];
    }

    public function getEducationLevels(): array
    {
        return [
            ['id' => 1, 'name' => 'High School'],
            ['id' => 2, 'name' => 'Bachelor'],
            ['id' => 3, 'name' => 'Master'],
            ['id' => 4, 'name' => 'Doctorate'],
        ];
    }

    public function getRoleName(int $roleId): string
    {
        foreach ($this->getRoles() as $role) {
            if ((int)$role['id'] === $roleId) {
                return (string)$role['name'];
            }
        }

        return 'Unknown';
    }

    public function getStatusName(int $statusId): string
    {
        foreach ($this->getStatuses() as $status) {
            if ((int)$status['id'] === $statusId) {
                return (string)$status['name'];
            }
        }

        return 'Unknown';
    }

    public function getEducationLevelName(?int $educationLevelId): string
    {
        foreach ($this->getEducationLevels() as $level) {
            if ((int)$level['id'] === (int)$educationLevelId) {
                return (string)$level['name'];
            }
        }

        return 'Not Set';
    }
}
