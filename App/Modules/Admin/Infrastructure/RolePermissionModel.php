<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;
use Throwable;

class RolePermissionModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
        $this->ensureTablesExist();
    }

 private function ensureTablesExist(): void
{
    $this->pdo->exec(
        "CREATE TABLE IF NOT EXISTS role_permissions (
            role_permission_id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            permission_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_role_permission (role_id, permission_id),
            CONSTRAINT fk_role_permissions_role
                FOREIGN KEY (role_id) REFERENCES roles(id)
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_role_permissions_permission
                FOREIGN KEY (permission_id) REFERENCES permissions(id)
                ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

    
public function getRoles(): array
{
    $stmt = $this->pdo->query("SELECT id, name FROM roles");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
   
 public function getPermissions(): array
{
    $stmt = $this->pdo->prepare(
        'SELECT id, name, module
         FROM permissions
         ORDER BY module ASC, name ASC'
    );
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAssignedPermissionIds(int $roleId): array
{
    $stmt = $this->pdo->prepare(
        'SELECT permission_id
         FROM role_permissions
         WHERE role_id = :role_id'
    );

    $stmt->execute([
        ':role_id' => $roleId
    ]);

    $ids = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $ids[] = (int)$row['permission_id'];
    }

    return $ids;
}

    public function saveAssignments(int $roleId, array $permissionIds): void
    {
        $this->pdo->beginTransaction();

        try {
            $deleteStmt = $this->pdo->prepare('DELETE FROM role_permissions
WHERE role_id = :role_id');
            $deleteStmt->execute([':role_id' => $roleId]);

            if ($permissionIds !== []) {
                $insertStmt = $this->pdo->prepare('INSERT IGNORE INTO role_permissions (role_id, permission_id, created_at) VALUES (:role_id, :permission_id, NOW())');

                foreach ($permissionIds as $permissionId) {
                    $permissionId = (int)$permissionId;
                    if ($permissionId > 0) {
                        $insertStmt->execute([
                            ':role_id' => $roleId,
                            ':permission_id' => $permissionId,
                        ]);
                    }
                }
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
