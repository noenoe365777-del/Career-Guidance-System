<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;

class RoleModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function getAllRoles(): array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, description FROM roles ORDER BY id ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, description FROM roles WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ?: null;
    }

    public function createRole(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO roles (name, description) VALUES (:name, :description)');
        $stmt->execute([
            ':name' => trim((string)($data['name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function updateRole(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE roles SET name = :name, description = :description WHERE id = :id');
        return $stmt->execute([
            ':name' => trim((string)($data['name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
            ':id' => $id,
        ]);
    }

    public function deleteRole(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM roles WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function roleNameExists(string $roleName, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM roles WHERE LOWER(name) = LOWER(:name)';
        $params = [':name' => $roleName];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }
}
