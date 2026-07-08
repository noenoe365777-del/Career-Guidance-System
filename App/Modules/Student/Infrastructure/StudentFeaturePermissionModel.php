<?php

declare(strict_types=1);

namespace App\Modules\Student\Infrastructure;

use App\Config\Database;
use PDO;

class StudentFeaturePermissionModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function getFeatureDefinitions(): array
    {
        return [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ['key' => 'assessments', 'label' => 'Assessments', 'route' => 'assessments'],
            ['key' => 'career_maps', 'label' => 'Career Maps', 'route' => 'recommendation'],
            ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile'],
            ['key' => 'settings', 'label' => 'Settings', 'route' => 'change-password'],
        ];
    }

    public function ensurePermissionsForStudent(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        $existingStmt = $this->pdo->prepare('SELECT user_id FROM student_permissions WHERE user_id = :user_id LIMIT 1');
        $existingStmt->execute([':user_id' => $userId]);

        if ($existingStmt->fetch()) {
            return;
        }

        $insertStmt = $this->pdo->prepare(
            'INSERT INTO student_permissions (user_id, dashboard, assessments, career_maps, profile, settings)
             VALUES (:user_id, 1, 1, 1, 1, 1)'
        );
        $insertStmt->execute([':user_id' => $userId]);
    }

    public function getPermissionsForStudent(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $this->ensurePermissionsForStudent($userId);

        $stmt = $this->pdo->prepare(
            'SELECT dashboard, assessments, career_maps, profile, settings
             FROM student_permissions
             WHERE user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);

        $row = $stmt->fetch();
        if (!$row) {
            return [
                'dashboard' => true,
                'assessments' => true,
                'career_maps' => true,
                'profile' => true,
                'settings' => true,
            ];
        }

        return [
            'dashboard' => (bool)(int)$row['dashboard'],
            'assessments' => (bool)(int)$row['assessments'],
            'career_maps' => (bool)(int)$row['career_maps'],
            'profile' => (bool)(int)$row['profile'],
            'settings' => (bool)(int)$row['settings'],
        ];
    }

    public function hasFeatureAccess(int $userId, string $featureKey): bool
    {
        if ($userId <= 0) {
            return true;
        }

        $permissions = $this->getPermissionsForStudent($userId);
        return (bool)($permissions[$featureKey] ?? true);
    }

    public function savePermissionsForStudent(int $userId, array $permissions): void
    {
        if ($userId <= 0) {
            return;
        }

        $this->ensurePermissionsForStudent($userId);

        $stmt = $this->pdo->prepare(
            'UPDATE student_permissions
             SET dashboard = :dashboard,
                 assessments = :assessments,
                 career_maps = :career_maps,
                 profile = :profile,
                 settings = :settings
             WHERE user_id = :user_id'
        );
        $stmt->execute([
            ':dashboard' => !empty($permissions['dashboard']) ? 1 : 0,
            ':assessments' => !empty($permissions['assessments']) ? 1 : 0,
            ':career_maps' => !empty($permissions['career_maps']) ? 1 : 0,
            ':profile' => !empty($permissions['profile']) ? 1 : 0,
            ':settings' => !empty($permissions['settings']) ? 1 : 0,
            ':user_id' => $userId,
        ]);
    }
}
