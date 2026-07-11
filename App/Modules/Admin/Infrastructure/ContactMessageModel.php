<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;

class ContactMessageModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void
    {
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(150) NOT NULL,
                email VARCHAR(150) NOT NULL,
                subject VARCHAR(100) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('Unread', 'Read') NOT NULL DEFAULT 'Unread',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }

    public function saveMessage(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO contact_messages (full_name, email, subject, message, status)
             VALUES (:full_name, :email, :subject, :message, :status)'
        );

        $stmt->execute([
            ':full_name' => trim((string)($data['full_name'] ?? '')),
            ':email' => trim((string)($data['email'] ?? '')),
            ':subject' => trim((string)($data['subject'] ?? '')),
            ':message' => trim((string)($data['message'] ?? '')),
            ':status' => 'Unread',
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function getAllMessages(string $status = ''): array
    {
        $sql = 'SELECT id, full_name, email, subject, message, status, created_at FROM contact_messages';
        $params = [];

        if ($status !== '') {
            $sql .= ' WHERE status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $stmt->execute([':status' => 'Read', ':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
