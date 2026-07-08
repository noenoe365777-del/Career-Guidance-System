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

