<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\QuestionOptionRepositoryInterface;
use PDO;

class QuestionOptionRepository implements QuestionOptionRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getOptionsForQuestion(int $questionId): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT option_id AS id, option_text AS option_text, score_value, option_order FROM question_options WHERE question_id = :question_id ORDER BY option_order"
            );
            $statement->execute(['question_id' => $questionId]);
            return $statement->fetchAll();
        } catch (\Throwable) {
            return [];
        }
    }
}

