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
            $sql = "SELECT question_id AS id, question_text AS question, question_type AS type, question_order AS `order`
                    FROM questions
                    WHERE assessment_id = :assessment_id";

            if ($previewOnly) {
                $sql .= " AND preview = 1";
            }

            $sql .= " ORDER BY question_order";

            $statement = $this->connection->prepare($sql);
            $statement->execute(['assessment_id' => $assessmentId]);
            $rows = $statement->fetchAll();

            if (empty($rows)) {
                return [];
            }

            $questions = [];
            foreach ($rows as $row) {
                $questionId = (int)$row['id'];
                $options = $this->getOptionsForQuestion($questionId);

                $questions[] = [
                    'id' => $questionId,
                    'question' => $row['question'] ?? '',
                    'type' => $row['type'] ?? 'single_choice',
                    'order' => (int)($row['order'] ?? 0),
                    'options' => $options,
                ];
            }

            if (!$previewOnly) {
                shuffle($questions);
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
                "SELECT COUNT(*) FROM questions WHERE assessment_id = :assessment_id"
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
                "SELECT COUNT(*) FROM questions WHERE assessment_id = :assessment_id AND preview = 1"
            );
            $statement->execute(['assessment_id' => $assessmentId]);
            return (int)$statement->fetchColumn();
        } catch (\Throwable) {
            return 0;
        }
    }

    public function getSlugMap(): array
    {
        return $this->slugMap;
    }

    private function getOptionsForQuestion(int $questionId): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT option_value, option_text
                 FROM question_options
                 WHERE question_id = :question_id
                 ORDER BY option_order"
            );
            $statement->execute(['question_id' => $questionId]);
            $rows = $statement->fetchAll();

            return array_map(fn(array $row): array => [
                'value' => (int)$row['option_value'],
                'label' => $row['option_text'] ?? '',
            ], $rows);
        } catch (\Throwable) {
            return [];
        }
    }
}
