<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\StudentRolePermissionRepositoryInterface;
use PDO;
use PDOException;

class StudentRolePermissionRepository implements StudentRolePermissionRepositoryInterface
{
    private PDO $connection;

    private const DEFAULT_FEATURES = [
        ['view_dashboard', 'View Dashboard'],
        ['take_assessment', 'Take Assessment'],
        ['view_results', 'View Assessment Results'],
        ['view_recommendations', 'View Career Recommendations'],
        ['edit_profile', 'Edit Profile'],
        ['change_password', 'Change Password'],
    ];

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
        $this->ensureTableExists();
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->query(
                'SELECT * FROM student_role_permissions ORDER BY id ASC'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getByFeatureKey(string $featureKey): ?array
    {
        try {
            $stmt = $this->connection->prepare(
                'SELECT * FROM student_role_permissions WHERE feature_key = :feature_key LIMIT 1'
            );
            $stmt->execute([':feature_key' => $featureKey]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    public function update(string $featureKey, bool $isEnabled): bool
    {
        try {
            $stmt = $this->connection->prepare(
                'UPDATE student_role_permissions SET is_enabled = :is_enabled, updated_at = NOW()
                 WHERE feature_key = :feature_key'
            );
            $stmt->execute([
                ':is_enabled' => $isEnabled ? 1 : 0,
                ':feature_key' => $featureKey,
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function isFeatureEnabled(string $featureKey): bool
    {
        try {
            $stmt = $this->connection->prepare(
                'SELECT is_enabled FROM student_role_permissions WHERE feature_key = :feature_key LIMIT 1'
            );
            $stmt->execute([':feature_key' => $featureKey]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (bool)(int)$row['is_enabled'] : true;
        } catch (PDOException) {
            return true;
        }
    }

    public function getFeatureDefinitions(): array
    {
        $rows = $this->getAll();
        if ($rows !== []) {
            return array_map(fn($r) => [
                'key' => $r['feature_key'],
                'label' => $r['feature_label'],
            ], $rows);
        }
        return array_map(fn($f) => ['key' => $f[0], 'label' => $f[1]], self::DEFAULT_FEATURES);
    }

    private function ensureTableExists(): void
    {
        try {
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS student_role_permissions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    feature_key VARCHAR(64) NOT NULL UNIQUE,
                    feature_label VARCHAR(100) NOT NULL,
                    is_enabled TINYINT(1) NOT NULL DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");

            $count = (int)$this->connection
                ->query('SELECT COUNT(*) FROM student_role_permissions')
                ->fetchColumn();

            if ($count === 0) {
                $insert = $this->connection->prepare(
                    'INSERT INTO student_role_permissions (feature_key, feature_label, is_enabled)
                     VALUES (:feature_key, :feature_label, 1)'
                );
                foreach (self::DEFAULT_FEATURES as [$key, $label]) {
                    $insert->execute([':feature_key' => $key, ':feature_label' => $label]);
                }
            }
        } catch (PDOException) {
        }
    }
}
