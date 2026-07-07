<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Infrastructure\Persistence;

use App\Config\Database;
use PDO;

class RecommendationRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function saveRecommendation(int $userId, string $careerId, string $careerName, float $matchPercent, array $report): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO recommendations (user_id, career_id, career_name, match_percent, report_json, generated_at) VALUES (:user_id, :career_id, :career_name, :match_percent, :report_json, NOW())"
            );

            return (bool)$stmt->execute([
                ':user_id' => $userId,
                ':career_id' => $careerId,
                ':career_name' => $careerName,
                ':match_percent' => $matchPercent,
                ':report_json' => json_encode($report),
            ]);
        } catch (\Throwable $e) {
            // if table doesn't exist or other DB errors, fail gracefully
            return false;
        }
    }

    public function getLatestForUser(int $userId): ?array
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM recommendations WHERE user_id = :user_id ORDER BY generated_at DESC LIMIT 1"
            );
            $stmt->execute([':user_id' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            $row['report_json'] = json_decode($row['report_json'] ?? 'null', true) ?: [];
            return $row;
        } catch (\Throwable) {
            return null;
        }
    }
}

