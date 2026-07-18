<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\QuestionRepositoryInterface;
use PDO;

class QuestionRepository implements QuestionRepositoryInterface
{
    private PDO $connection;

    private array $slugMap = [
        'personality' => 1,
        'interest' => 2,
        'aptitude' => 3,
        'values' => 4,
    ];

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getQuestionsBySlug(string $slug, bool $previewOnly = false): array
    {
        $assessmentId = $this->slugMap[$slug] ?? 0;
        if ($assessmentId === 0) {
            return [];
        }

        try {
            $sql = "SELECT aq.id, aq.question, aq.option_a, aq.option_b, aq.option_c, aq.option_d,
                           aq.correct_answer, aq.weight
                    FROM assessment_questions aq
                    WHERE aq.assessment_id = :assessment_id
                    ORDER BY aq.id ASC";

            $statement = $this->connection->prepare($sql);
            $statement->execute(['assessment_id' => $assessmentId]);
            $rows = $statement->fetchAll();

            if (empty($rows)) {
                return [];
            }

            $questions = [];
            foreach ($rows as $row) {
                $questionId = (int)$row['id'];
                $options = $this->parseOptions($row);

                $questions[] = [
                    'id' => $questionId,
                    'question' => $row['question'] ?? '',
                    'type' => 'single_choice',
                    'order' => $questionId,
                    'options' => $options,
                ];
            }

            if ($previewOnly && count($questions) > 5) {
                $questions = array_slice($questions, 0, 5);
            }

            return $questions;
        } catch (\Throwable) {
            return [];
        }
    }

    public function getQuestionsByAssessmentId(int $assessmentId, ?int $limit = null): array
    {
        try {
            $sql = "SELECT aq.id, aq.question, aq.option_a, aq.option_b, aq.option_c, aq.option_d,
                           aq.correct_answer, aq.weight
                    FROM assessment_questions aq
                    WHERE aq.assessment_id = :assessment_id
                    ORDER BY aq.id ASC";

            if ($limit !== null) {
                $sql .= " LIMIT " . (int)$limit;
            }

            $statement = $this->connection->prepare($sql);
            $statement->execute(['assessment_id' => $assessmentId]);
            $rows = $statement->fetchAll();

            $questions = [];
            foreach ($rows as $row) {
                $questions[] = [
                    'id' => (int)$row['id'],
                    'question' => $row['question'] ?? '',
                    'type' => 'single_choice',
                    'order' => (int)$row['id'],
                    'options' => $this->parseOptions($row),
                ];
            }

            return $questions;
        } catch (\Throwable) {
            return [];
        }
    }

    public function getTotalQuestionCount(int $assessmentId): int
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = :assessment_id"
            );
            $statement->execute(['assessment_id' => $assessmentId]);
            return (int)$statement->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }

    public function getPreviewQuestionCount(int $assessmentId): int
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = :assessment_id"
            );
            $statement->execute(['assessment_id' => $assessmentId]);
            $total = (int)$statement->fetchColumn();
            return min(5, $total);
        } catch (\Throwable) {
            return 0;
        }
    }

    public function getSlugMap(): array
    {
        return $this->slugMap;
    }

    private function parseOptions(array $row): array
    {
        $options = [];
        $position = 1;

        foreach (['option_a', 'option_b', 'option_c', 'option_d'] as $col) {
            $value = $row[$col] ?? null;
            if ($value !== null && $value !== '') {
                $options[] = [
                    'id' => $position,
                    'value' => $position,
                    'label' => (string)$value,
                ];
            }
            $position++;
        }

        return $options;
    }
}
