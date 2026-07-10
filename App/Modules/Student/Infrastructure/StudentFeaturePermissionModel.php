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
        $stmt = $this->pdo->query(
            'SELECT feature_key, feature_label FROM student_role_permissions ORDER BY id ASC'
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rows !== []) {
            return array_map(fn($r) => [
                'key' => $r['feature_key'],
                'label' => $r['feature_label'],
            ], $rows);
        }
        return [
            ['key' => 'view_dashboard', 'label' => 'View Dashboard'],
            ['key' => 'take_assessment', 'label' => 'Take Assessment'],
            ['key' => 'view_results', 'label' => 'View Assessment Results'],
            ['key' => 'view_recommendations', 'label' => 'View Career Recommendations'],
            ['key' => 'edit_profile', 'label' => 'Edit Profile'],
            ['key' => 'change_password', 'label' => 'Change Password'],
        ];
    }

    private function getFeatureKeys(): array
    {
        return array_map(fn($f) => $f['key'], $this->getFeatureDefinitions());
    }

    public function ensurePermissionsForStudent(int $userId): void
    {
        if ($userId <= 0) {
            return;
        }

        $existing = $this->pdo->prepare(
            'SELECT COUNT(*) FROM student_feature_permissions WHERE user_id = :user_id'
        );
        $existing->execute([':user_id' => $userId]);

        if ((int)$existing->fetchColumn() > 0) {
            return;
        }

        $insert = $this->pdo->prepare(
            'INSERT INTO student_feature_permissions (user_id, feature_key, is_enabled)
             VALUES (:user_id, :feature_key, 1)'
        );

        foreach ($this->getFeatureKeys() as $featureKey) {
            $insert->execute([
                ':user_id' => $userId,
                ':feature_key' => $featureKey,
            ]);
        }
    }

    public function getPermissionsForStudent(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT feature_key, is_enabled FROM student_feature_permissions WHERE user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);

        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[(string)$row['feature_key']] = (int)$row['is_enabled'];
        }
        return $result;
    }

    public function savePermissionsForStudent(int $userId, array $permissions): void
    {
        if ($userId <= 0) {
            return;
        }

        $upsert = $this->pdo->prepare(
            'INSERT INTO student_feature_permissions (user_id, feature_key, is_enabled, created_at, updated_at)
             VALUES (:user_id, :feature_key, :is_enabled, NOW(), NOW())
             ON DUPLICATE KEY UPDATE is_enabled = VALUES(is_enabled), updated_at = NOW()'
        );

        foreach ($permissions as $featureKey => $isEnabled) {
            $upsert->execute([
                ':user_id' => $userId,
                ':feature_key' => $featureKey,
                ':is_enabled' => $isEnabled ? 1 : 0,
            ]);
        }
    }

    public function hasFeatureAccess(int $userId, string $featureKey): bool
    {
        if ($userId <= 0) {
            return true;
        }

        try {
            $stmt = $this->pdo->prepare(
                'SELECT is_enabled FROM student_feature_permissions
                 WHERE user_id = :user_id AND feature_key = :feature_key
                 LIMIT 1'
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':feature_key' => $featureKey,
            ]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (bool)(int)$row['is_enabled'] : true;
        } catch (\PDOException) {
            return true;
        }
    }
}
