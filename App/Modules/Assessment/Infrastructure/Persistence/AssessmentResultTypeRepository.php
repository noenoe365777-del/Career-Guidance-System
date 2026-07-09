<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\AssessmentResultTypeRepositoryInterface;
use PDO;

class AssessmentResultTypeRepository implements AssessmentResultTypeRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function findType(string $slug, int $score): ?array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT type_label, interpretation FROM assessment_result_types WHERE slug = :slug AND :score BETWEEN min_score AND max_score LIMIT 1"
            );
            $statement->execute(['slug' => $slug, 'score' => $score]);
            $row = $statement->fetch();

            if ($row) {
                return [
                    'type_label' => $row['type_label'],
                    'interpretation' => $row['interpretation'],
                ];
            }
        } catch (\Throwable) {
        }

        return null;
    }
}
