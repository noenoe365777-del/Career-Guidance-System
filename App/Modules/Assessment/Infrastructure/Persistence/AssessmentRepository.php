<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\AssessmentRepositoryInterface;
use PDO;

class AssessmentRepository implements AssessmentRepositoryInterface
{
    private PDO $connection;

    private array $slugMap = [
        1 => 'personality',
        2 => 'interest',
        3 => 'aptitude',
        4 => 'values',
    ];

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAll(): array
    {
        try {
            $statement = $this->connection->query(
                "SELECT assessment_id AS id, title, description, status FROM assessments WHERE status = 'active' ORDER BY assessment_id"
            );
            $rows = $statement->fetchAll();

            if (!empty($rows)) {
                return array_map(fn(array $row): array => $this->mapRow($row), $rows);
            }
        } catch (\Throwable) {
        }

        return [];
    }

    public function getBySlug(string $slug): ?array
    {
        $assessmentId = array_search($slug, $this->slugMap, true);
        if ($assessmentId === false) {
            return null;
        }

        try {
            $statement = $this->connection->prepare(
                "SELECT assessment_id AS id, title, description, status FROM assessments WHERE assessment_id = :id AND status = 'active' LIMIT 1"
            );
            $statement->execute(['id' => $assessmentId]);
            $row = $statement->fetch();

            if ($row) {
                return $this->mapRow($row);
            }
        } catch (\Throwable) {
        }

        return null;
    }

    public function getById(int $id): ?array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT assessment_id AS id, title, description, status FROM assessments WHERE assessment_id = :id AND status = 'active' LIMIT 1"
            );
            $statement->execute(['id' => $id]);
            $row = $statement->fetch();

            if ($row) {
                return $this->mapRow($row);
            }
        } catch (\Throwable) {
        }

        return null;
    }

    public function getSlugMap(): array
    {
        return $this->slugMap;
    }

    private function mapRow(array $row): array
    {
        $id = (int)($row['id'] ?? 0);

        return [
            'id' => $id,
            'title' => $row['title'] ?? 'Assessment',
            'description' => $row['description'] ?? 'Complete this assessment to understand yourself better.',
            'slug' => $this->slugMap[$id] ?? 'assessment',
            'total_questions' => $this->countQuestions($id),
            'preview_questions' => $this->countPreviewQuestions($id),
            'status' => $row['status'] ?? 'active',
        ];
    }

    private function countQuestions(int $assessmentId): int
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COUNT(*) FROM questions WHERE assessment_id = :assessment_id"
            );
            $statement->execute(['assessment_id' => $assessmentId]);
            return (int)$statement->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }

    private function countPreviewQuestions(int $assessmentId): int
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COUNT(*) FROM questions WHERE assessment_id = :assessment_id AND preview = 1"
            );
            $statement->execute(['assessment_id' => $assessmentId]);
            return (int)$statement->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }
}
