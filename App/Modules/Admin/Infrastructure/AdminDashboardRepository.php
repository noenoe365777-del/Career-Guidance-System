<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure;

use App\Config\Database;
use PDO;
use PDOException;

class AdminDashboardRepository
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getDashboardStats(): array
    {
        return [
            'totalUsers' => $this->countRows('users'),
            'totalAssessments' => $this->countRows('assessments'),
            'totalCareers' => $this->countRows('careers'),
            'completedAssessments' => $this->countRows('student_assessments', "status = 'Completed'"),
            'recentUsers' => $this->getRecentUsers(),
            'recentSubmissions' => $this->getRecentSubmissions(),
        ];
    }

    public function getRecentUsers(int $limit = 5): array
    {
        $columns = $this->getTableColumns('users');
        $idColumn = $this->pickColumn($columns, ['id', 'user_id']);
        $nameColumn = $this->pickColumn($columns, ['full_name', 'username', 'name']);
        $emailColumn = $this->pickColumn($columns, ['email']);
        $createdAtColumn = $this->pickColumn($columns, ['created_at', 'createdAt']);

        $sql = sprintf(
            'SELECT %s AS id, %s AS full_name, %s AS email, %s AS created_at FROM users ORDER BY %s DESC LIMIT :limit',
            $this->quoteIdentifier($idColumn),
            $this->quoteIdentifier($nameColumn),
            $this->quoteIdentifier($emailColumn),
            $this->quoteIdentifier($createdAtColumn),
            $this->quoteIdentifier($createdAtColumn)
        );

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentSubmissions(int $limit = 5): array
    {
        $userColumns = $this->getTableColumns('users');
        $userIdColumn = $this->pickColumn($userColumns, ['id', 'user_id']);
        $nameColumn = $this->pickColumn($userColumns, ['full_name', 'username', 'name']);

        $assessmentColumns = $this->getTableColumns('assessments');
        $assessmentIdColumn = $this->pickColumn($assessmentColumns, ['assessment_id', 'id']);

        $sql = sprintf(
            'SELECT sa.user_id, sa.status, sa.created_at, u.%s AS full_name, a.title
             FROM student_assessments sa
             LEFT JOIN users u ON u.%s = sa.user_id
             LEFT JOIN assessments a ON a.%s = sa.assessment_id
             ORDER BY sa.created_at DESC
             LIMIT :limit',
            $this->quoteIdentifier($nameColumn),
            $this->quoteIdentifier($userIdColumn),
            $this->quoteIdentifier($assessmentIdColumn)
        );
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function countRows(string $table, string $where = ''): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->quoteIdentifier($table);

        if ($where !== '') {
            $sql .= ' WHERE ' . $where;
        }

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            return (int)$statement->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }
    private function getTableColumns(string $table): array
    {
        $statement = $this->connection->prepare('SHOW COLUMNS FROM ' . $this->quoteIdentifier($table));
        $statement->execute();

        return array_map(static fn (array $row): string => strtolower((string)($row['Field'] ?? '')), $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    private function pickColumn(array $columns, array $preferred): string
    {
        foreach ($preferred as $candidate) {
            if (in_array(strtolower($candidate), $columns, true)) {
                return $candidate;
            }
        }

        return $columns[0] ?? 'id';
    }

    private function quoteIdentifier(string $identifier): string
    {
        $clean = str_replace('`', '', $identifier);

        return '`' . $clean . '`';
    }}
