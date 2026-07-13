<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\NotificationRepositoryInterface;
use PDO;
use PDOException;

class NotificationRepository implements NotificationRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null): array
    {
        try {
            $sql = 'SELECT * FROM notifications WHERE 1=1';
            $params = [];

            if ($type !== null && $type !== '') {
                $sql .= ' AND type = :type';
                $params[':type'] = $type;
            }

            if ($search !== null && $search !== '') {
                $sql .= ' AND (title LIKE :search OR message LIKE :search2)';
                $params[':search'] = '%' . $search . '%';
                $params[':search2'] = '%' . $search . '%';
            }

            $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;

            $stmt = $this->connection->prepare($sql);
            foreach ($params as $key => $val) {
                if (in_array($key, [':limit', ':offset'], true)) {
                    $stmt->bindValue($key, (int)$val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getUnread(int $limit = 20, int $offset = 0): array
    {
        try {
            $stmt = $this->connection->prepare(
                'SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
            );
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getUnreadCount(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM notifications WHERE is_read = 0');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalCount(?string $type = null, ?string $search = null): int
    {
        try {
            $sql = 'SELECT COUNT(*) FROM notifications WHERE 1=1';
            $params = [];

            if ($type !== null && $type !== '') {
                $sql .= ' AND type = :type';
                $params[':type'] = $type;
            }

            if ($search !== null && $search !== '') {
                $sql .= ' AND (title LIKE :search OR message LIKE :search2)';
                $params[':search'] = '%' . $search . '%';
                $params[':search2'] = '%' . $search . '%';
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function markAsRead(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE notifications SET is_read = 1 WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function markAllAsRead(): bool
    {
        try {
            $stmt = $this->connection->prepare('UPDATE notifications SET is_read = 1 WHERE is_read = 0');
            $stmt->execute();
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare('DELETE FROM notifications WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function create(array $data): int
    {
        try {
            $stmt = $this->connection->prepare(
                'INSERT INTO notifications (type, title, message, link, is_read) VALUES (:type, :title, :message, :link, :is_read)'
            );
            $stmt->execute([
                ':type' => $data['type'] ?? 'system',
                ':title' => $data['title'] ?? '',
                ':message' => $data['message'] ?? null,
                ':link' => $data['link'] ?? null,
                ':is_read' => $data['is_read'] ?? 0,
            ]);
            return (int)$this->connection->lastInsertId();
        } catch (PDOException) {
            return 0;
        }
    }
}
