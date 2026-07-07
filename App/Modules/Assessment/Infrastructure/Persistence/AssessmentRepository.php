<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\AssessmentRepositoryInterface;
use PDO;

class AssessmentRepository implements AssessmentRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAll(): array
    {
        try {
            $statement = $this->connection->query(
                "SELECT assessment_id AS id, title, description, assessment_type AS slug, total_questions AS total_questions, status FROM assessments WHERE status = 'active' ORDER BY assessment_id"
            );
            $rows = $statement->fetchAll();

            if (!empty($rows)) {
                return array_map(fn(array $row): array => $this->mapRow($row), $rows);
            }
        } catch (\Throwable) {
        }

        return $this->fallbackAssessments();
    }

    public function getBySlug(string $slug): ?array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT assessment_id AS id, title, description, assessment_type AS slug, total_questions AS total_questions, status FROM assessments WHERE assessment_type = :slug LIMIT 1"
            );
            $statement->execute(['slug' => $slug]);
            $row = $statement->fetch();

            if ($row) {
                return $this->mapRow($row);
            }
        } catch (\Throwable) {
        }

        $fallback = $this->fallbackAssessments();
        foreach ($fallback as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }

        return null;
    }

    private function mapRow(array $row): array
    {
        return [
            'id' => (int)($row['id'] ?? 0),
            'title' => $row['title'] ?? 'Assessment',
            'description' => $row['description'] ?? 'Complete this assessment to understand yourself better.',
            'slug' => $row['slug'] ?? 'assessment',
            'total_questions' => (int)($row['total_questions'] ?? 0),
            'status' => $row['status'] ?? 'active',
        ];
    }

    private function fallbackAssessments(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Personality Assessment',
                'description' => 'Understand your personality traits and work style.',
                'slug' => 'personality',
                'total_questions' => 10,
                'status' => 'active',
            ],
            [
                'id' => 2,
                'title' => 'Interest Assessment',
                'description' => 'Discover careers that match your interests and passions.',
                'slug' => 'interest',
                'total_questions' => 10,
                'status' => 'active',
            ],
            [
                'id' => 3,
                'title' => 'Aptitude Assessment',
                'description' => 'Measure your reasoning abilities and problem-solving skills.',
                'slug' => 'aptitude',
                'total_questions' => 5,
                'status' => 'active',
            ],
            [
                'id' => 4,
                'title' => 'Career Values Assessment',
                'description' => 'Identify what matters most to you in a future career.',
                'slug' => 'values',
                'total_questions' => 5,
                'status' => 'active',
            ],
        ];
    }
}

