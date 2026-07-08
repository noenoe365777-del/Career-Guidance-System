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
        $stmt = $this->pdo->prepare('SELECT role_id, role_name, description FROM roles ORDER BY role_id ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT role_id, role_name, description FROM roles WHERE role_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ?: null;
    }

    public function createRole(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO roles (role_name, description) VALUES (:role_name, :description)');
        $stmt->execute([
            ':role_name' => trim((string)($data['role_name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function updateRole(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE roles SET role_name = :role_name, description = :description WHERE role_id = :id');
        return $stmt->execute([
            ':role_name' => trim((string)($data['role_name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
            ':id' => $id,
        ]);
    }

    public function deleteRole(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM roles WHERE role_id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function roleNameExists(string $roleName, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM roles WHERE LOWER(role_name) = LOWER(:role_name)';
        $params = [':role_name' => $roleName];

        if ($excludeId !== null) {
            $sql .= ' AND role_id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }
}
