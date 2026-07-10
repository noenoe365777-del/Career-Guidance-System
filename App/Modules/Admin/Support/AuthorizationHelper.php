<?php

declare(strict_types=1);

namespace App\Modules\Admin\Support;

use App\Config\Database;
use PDO;

class AuthorizationHelper
{
    public static function hasPermission(string $permissionName): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $admin = $_SESSION['admin'] ?? null;
        if (!is_array($admin) || empty($admin['id'])) {
            return false;
        }

        $roleId = (int)($admin['role_id'] ?? 0);
        $roleName = strtolower((string)($admin['role_name'] ?? $admin['role'] ?? ''));

        if ($roleId === 0 && $roleName !== '') {
            $roleId = self::getRoleIdByName($roleName);
        }

        if ($roleId <= 0) {
            return false;
        }

        if ($roleName === 'admin' || $roleId === 1) {
            return true;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*)
             FROM role_permissions rp
             INNER JOIN permissions p ON p.id = rp.permission_id
             WHERE rp.role_id = :role_id
               AND LOWER(p.name) = LOWER(:permission_name)'
        );
        $stmt->execute([
            ':role_id' => $roleId,
            ':permission_name' => trim($permissionName),
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    private static function getRoleIdByName(string $roleName): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id FROM roles WHERE LOWER(name) = LOWER(:role_name) LIMIT 1');
        $stmt->execute([':role_name' => trim($roleName)]);

        return (int)$stmt->fetchColumn();
    }
}
