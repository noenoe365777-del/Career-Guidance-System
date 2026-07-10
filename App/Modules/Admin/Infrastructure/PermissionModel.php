<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;

class PermissionModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function getAllPermissions(): array
    {
        $stmt = $this->pdo->prepare('SELECT permission_id, permission_name, module_name, description, created_at, updated_at FROM permissions ORDER BY permission_id ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT permission_id, permission_name, module_name, description, created_at, updated_at FROM permissions WHERE permission_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $permission = $stmt->fetch(PDO::FETCH_ASSOC);
        return $permission ?: null;
    }

    public function createPermission(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO permissions (permission_name, module_name, description, created_at, updated_at) VALUES (:permission_name, :module_name, :description, NOW(), NOW())');
        $stmt->execute([
            ':permission_name' => trim((string)($data['permission_name'] ?? '')),
            ':module_name' => trim((string)($data['module_name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function updatePermission(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE permissions SET permission_name = :permission_name, module_name = :module_name, description = :description, updated_at = NOW() WHERE permission_id = :id');
        return $stmt->execute([
            ':permission_name' => trim((string)($data['permission_name'] ?? '')),
            ':module_name' => trim((string)($data['module_name'] ?? '')),
            ':description' => trim((string)($data['description'] ?? '')),
            ':id' => $id,
        ]);
    }

    public function deletePermission(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM permissions WHERE permission_id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function permissionNameExists(string $permissionName, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM permissions WHERE LOWER(permission_name) = LOWER(:permission_name)';
        $params = [':permission_name' => $permissionName];

        if ($excludeId !== null) {
            $sql .= ' AND permission_id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }
}
