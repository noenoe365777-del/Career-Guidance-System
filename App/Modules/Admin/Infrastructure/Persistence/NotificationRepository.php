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

    private function applyRecipientFilter(string &$sql, array &$params, ?int $recipientId, ?string $recipientRole): void
    {
        if ($recipientId !== null && $recipientRole !== null) {
            $sql .= ' AND recipient_id = :recipient_id AND recipient_role = :recipient_role';
            $params[':recipient_id'] = $recipientId;
            $params[':recipient_role'] = $recipientRole;
        }
    }

    public function getAll(int $limit = 20, int $offset = 0, ?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): array
    {
        try {
            $sql = 'SELECT * FROM notifications WHERE 1=1';
            $params = [];

            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);

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

    public function getUnread(int $limit = 20, int $offset = 0, ?int $recipientId = null, ?string $recipientRole = null): array
    {
        try {
            $sql = 'SELECT * FROM notifications WHERE is_read = 0';
            $params = [];
            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);
            $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if (!empty($params)) {
                foreach ($params as $key => $val) {
                    $stmt->bindValue($key, $val);
                }
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getUnreadCount(?int $recipientId = null, ?string $recipientRole = null): int
    {
        try {
            $sql = 'SELECT COUNT(*) FROM notifications WHERE is_read = 0';
            $params = [];
            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getTotalCount(?string $type = null, ?string $search = null, ?int $recipientId = null, ?string $recipientRole = null): int
    {
        try {
            $sql = 'SELECT COUNT(*) FROM notifications WHERE 1=1';
            $params = [];

            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);

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

    public function getTodayCount(?int $recipientId = null, ?string $recipientRole = null): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM notifications WHERE DATE(created_at) = CURDATE()";
            $params = [];
            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function markAsRead(int $id, ?int $recipientId = null): bool
    {
        try {
            $sql = 'UPDATE notifications SET is_read = 1 WHERE id = :id';
            $params = [':id' => $id];
            if ($recipientId !== null) {
                $sql .= ' AND recipient_id = :recipient_id';
                $params[':recipient_id'] = $recipientId;
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function markAllAsRead(?int $recipientId = null, ?string $recipientRole = null): bool
    {
        try {
            $sql = 'UPDATE notifications SET is_read = 1 WHERE is_read = 0';
            $params = [];
            $this->applyRecipientFilter($sql, $params, $recipientId, $recipientRole);

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function delete(int $id, ?int $recipientId = null): bool
    {
        try {
            $sql = 'DELETE FROM notifications WHERE id = :id';
            $params = [':id' => $id];
            if ($recipientId !== null) {
                $sql .= ' AND recipient_id = :recipient_id';
                $params[':recipient_id'] = $recipientId;
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function create(array $data): int
    {
        try {
            $fields = ['type', 'title', 'message', 'link', 'is_read'];
            $placeholders = [':type', ':title', ':message', ':link', ':is_read'];

            if (array_key_exists('recipient_id', $data)) {
                $fields[] = 'recipient_id';
                $placeholders[] = ':recipient_id';
            }
            if (array_key_exists('recipient_role', $data)) {
                $fields[] = 'recipient_role';
                $placeholders[] = ':recipient_role';
            }

            $sql = 'INSERT INTO notifications (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':type' => $data['type'] ?? 'system',
                ':title' => $data['title'] ?? '',
                ':message' => $data['message'] ?? null,
                ':link' => $data['link'] ?? null,
                ':is_read' => $data['is_read'] ?? 0,
                ':recipient_id' => $data['recipient_id'] ?? null,
                ':recipient_role' => $data['recipient_role'] ?? null,
            ]);
            return (int)$this->connection->lastInsertId();
        } catch (PDOException) {
            return 0;
        }
    }
}
