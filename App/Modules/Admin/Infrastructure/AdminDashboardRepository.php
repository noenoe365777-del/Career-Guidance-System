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
        $totalUsers = $this->countRows('users');
        $totalStudents = $this->countRows('users', 'user_role_id = 2');
        $totalAssessments = $this->countRows('assessments');
        $totalCareers = $this->countRows('careers');
        $completedAssessments = $this->countRows('student_assessments', "status = 'Completed'");
        $inProgressAssessments = $this->countRows('student_assessments', "status = 'In Progress'");
        $pendingAssessments = $this->countRows('student_assessments', "status = 'Pending'");
        $recommendationCoverage = $totalStudents > 0 ? (int)round(($completedAssessments / max(1, $totalStudents)) * 100) : 0;
        $recommendationReady = max(0, $completedAssessments);

        return [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalAssessments' => $totalAssessments,
            'totalCareers' => $totalCareers,
            'completedAssessments' => $completedAssessments,
            'inProgressAssessments' => $inProgressAssessments,
            'pendingAssessments' => $pendingAssessments,
            'assessmentBreakdown' => $this->getAssessmentStatusBreakdown(),
            'signupTrend' => $this->getMonthlyTrend('users', 'created_at', 6),
            'completionTrend' => $this->getMonthlyTrend('student_assessments', 'created_at', 6, "status = 'Completed'"),
            'recommendationCoverage' => $recommendationCoverage,
            'recommendationReady' => $recommendationReady,
            'systemHealth' => ($totalUsers > 0 && $totalAssessments > 0) ? 'Healthy' : 'Needs setup',
            'systemHealthClass' => ($totalUsers > 0 && $totalAssessments > 0) ? 'text-success' : 'text-warning',
            'recentUsers' => $this->getRecentUsers(),
            'recentSubmissions' => $this->getRecentSubmissions(),
            'notifications' => $this->getNotifications(),
        ];
    }

    public function getRecentUsers(int $limit = 5): array
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT user_id AS id, username AS full_name, email, created_at
                 FROM users
                 ORDER BY created_at DESC
                 LIMIT :limit'
            );
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getRecentSubmissions(int $limit = 5): array
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT sa.user_id, sa.status, sa.created_at, u.username AS full_name, a.title
                 FROM student_assessments sa
                 LEFT JOIN users u ON u.user_id = sa.user_id
                 LEFT JOIN assessments a ON a.assessment_id = sa.assessment_id
                 ORDER BY sa.created_at DESC
                 LIMIT :limit'
            );
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getNotifications(int $limit = 4): array
    {
        $notifications = [];
        $submissions = $this->getRecentSubmissions($limit);

        foreach ($submissions as $submission) {
            $status = trim((string)($submission['status'] ?? 'Pending'));
            $notifications[] = [
                'title' => 'Assessment update',
                'message' => htmlspecialchars((string)($submission['full_name'] ?? 'A student') . ' updated ' . ($submission['title'] ?? 'an assessment')),
                'time' => $this->formatTimeAgo((string)($submission['created_at'] ?? '')),
                'status' => $status,
            ];
        }

        if ($notifications === []) {
            $notifications[] = [
                'title' => 'System online',
                'message' => 'Admin dashboard is running smoothly.',
                'time' => 'Just now',
                'status' => 'Healthy',
            ];
        }

        return $notifications;
    }

    private function getAssessmentStatusBreakdown(): array
    {
        $default = [
            ['label' => 'Completed', 'value' => 0],
            ['label' => 'In Progress', 'value' => 0],
            ['label' => 'Pending', 'value' => 0],
        ];

        try {
            $statement = $this->connection->query(
                'SELECT status, COUNT(*) AS total FROM student_assessments GROUP BY status ORDER BY total DESC'
            );
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return $default;
        }

        $map = [];
        foreach ($rows as $row) {
            $label = trim((string)($row['status'] ?? 'Pending'));
            if ($label === '') {
                $label = 'Pending';
            }
            $map[$label] = (int)($row['total'] ?? 0);
        }

        $result = [];
        foreach ($default as $item) {
            $label = (string)$item['label'];
            $result[] = [
                'label' => $label,
                'value' => (int)($map[$label] ?? 0),
            ];
        }

        foreach ($map as $label => $value) {
            $found = false;
            foreach ($result as &$item) {
                if ((string)$item['label'] === $label) {
                    $item['value'] = (int)$value;
                    $found = true;
                    break;
                }
            }
            unset($item);

            if (!$found) {
                $result[] = ['label' => $label, 'value' => (int)$value];
            }
        }

        return $result;
    }

    private function getMonthlyTrend(string $table, string $dateColumn, int $months, string $where = ''): array
    {
        try {
            $sql = sprintf(
                'SELECT DATE_FORMAT(%s, "%%Y-%%m") AS label, COUNT(*) AS total
                 FROM %s
                 WHERE %s IS NOT NULL AND %s >= DATE_SUB(CURRENT_DATE, INTERVAL :months MONTH)%s
                 GROUP BY DATE_FORMAT(%s, "%%Y-%%m")
                 ORDER BY label ASC',
                $this->quoteIdentifier($dateColumn),
                $this->quoteIdentifier($table),
                $this->quoteIdentifier($dateColumn),
                $this->quoteIdentifier($dateColumn),
                $where !== '' ? ' AND ' . $where : '',
                $this->quoteIdentifier($dateColumn)
            );

            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':months', $months, PDO::PARAM_INT);
            $statement->execute();
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            $labels = [];
            $values = [];

            foreach ($rows as $row) {
                $labels[] = $row['label'];
                $values[] = (int)($row['total'] ?? 0);
            }

            return ['labels' => $labels, 'values' => $values];
        } catch (PDOException) {
            return ['labels' => [], 'values' => []];
        }
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

    private function formatTimeAgo(string $timestamp): string
    {
        if ($timestamp === '') {
            return 'Just now';
        }

        $time = strtotime($timestamp);
        if ($time === false) {
            return 'Just now';
        }

        $diff = time() - $time;
        if ($diff < 60) {
            return 'Just now';
        }

        if ($diff < 3600) {
            return floor($diff / 60) . 'm ago';
        }

        if ($diff < 86400) {
            return floor($diff / 3600) . 'h ago';
        }

        return floor($diff / 86400) . 'd ago';
    }

    private function quoteIdentifier(string $identifier): string
    {
        $clean = str_replace('`', '', $identifier);

        return '`' . $clean . '`';
    }
}
