<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;

class AdminModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function findAdminByEmail(string $email): ?array
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);
        $nameColumn = $this->pickColumn($columns, ['full_name', 'username', 'name']);
        $roleColumn = $this->pickColumn($columns, ['user_role_id', 'role']);

        $sql = sprintf(
            'SELECT u.%s AS id, u.%s AS username, u.email, u.password, u.%s AS user_role_id, u.%s AS role
             FROM users u
             WHERE LOWER(u.email) = LOWER(:email)
             LIMIT 1',
            $this->quoteIdentifier($idColumn),
            $this->quoteIdentifier($nameColumn),
            $this->quoteIdentifier($roleColumn),
            $this->quoteIdentifier($roleColumn)
        );

        $statement = $this->pdo->prepare($sql);

        $statement->execute([':email' => $email]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function isAdmin(array $user): bool
    {
        $role = strtolower((string)($user['role'] ?? ''));

        if ($role === 'admin') {
            return true;
        }

        return isset($user['user_role_id']) && (int)$user['user_role_id'] === 1;
    }

    public function findAdminById(int $id): ?array
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);
        $nameColumn = $this->pickColumn($columns, ['full_name', 'username', 'name']);

        $sql = sprintf(
            'SELECT u.%s AS id, u.%s AS username, u.email, u.phone, u.address, u.bio, u.profile_image,
                    u.created_at, u.updated_at
             FROM users u
             WHERE u.%s = :id
             LIMIT 1',
            $this->quoteIdentifier($idColumn),
            $this->quoteIdentifier($nameColumn),
            $this->quoteIdentifier($idColumn)
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);

        $sql = sprintf(
            'SELECT COUNT(*) FROM users WHERE LOWER(email) = LOWER(:email)',
        );

        if ($excludeId !== null) {
            $sql .= sprintf(' AND %s != :exclude_id', $this->quoteIdentifier($idColumn));
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $params[':exclude_id'] = $excludeId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);

        $fields = [];
        $params = [];

        foreach (['username', 'email', 'phone', 'address', 'bio'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = sprintf('%s = :%s', $this->quoteIdentifier($field), $field);
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $params[$idColumn] = $id;

        $sql = sprintf(
            'UPDATE users SET %s WHERE %s = :%s',
            implode(', ', $fields),
            $this->quoteIdentifier($idColumn),
            $idColumn
        );

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function updateProfileImage(int $id, ?string $imageName): bool
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);

        $sql = sprintf(
            'UPDATE users SET profile_image = :image, updated_at = NOW() WHERE %s = :id',
            $this->quoteIdentifier($idColumn)
        );

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':image' => $imageName,
            ':id' => $id,
        ]);
    }

    public function verifyPassword(int $id, string $password): ?bool
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);

        $sql = sprintf(
            'SELECT u.password FROM users u WHERE u.%s = :id LIMIT 1',
            $this->quoteIdentifier($idColumn)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $hash = $stmt->fetchColumn();

        if ($hash === false) {
            return null;
        }

        return password_verify($password, (string)$hash);
    }

    public function updatePassword(int $id, string $newHash): bool
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);

        $sql = sprintf(
            'UPDATE users SET password = :password, updated_at = NOW() WHERE %s = :id',
            $this->quoteIdentifier($idColumn)
        );

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':password' => $newHash,
            ':id' => $id,
        ]);
    }

    private function getTableColumns(string $table): array
    {
        $statement = $this->pdo->prepare('SHOW COLUMNS FROM ' . $this->quoteIdentifier($table));
        $statement->execute();

        return array_map(static fn (array $row): string => strtolower((string)($row['Field'] ?? '')), $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    private function pickColumn(array $columns, array $preferred): string
    {
        foreach ($preferred as $candidate) {
            if (in_array(strtolower($candidate), $columns, true)) {
                return $candidate;
            }
        }

        return $columns[0] ?? 'id';
    }

    private function quoteIdentifier(string $identifier): string
    {
        $clean = str_replace('`', '', $identifier);

        return '`' . $clean . '`';
    }
}

