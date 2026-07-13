<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Recommendation\Domain\Repositories\RecommendationRepositoryInterface;
use PDO;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAllCareers(): array
    {
        try {
            $statement = $this->connection->query(
                "SELECT career_id, career_name, career_icon, description, required_skills, average_salary, growth_rate, education_required, personality_type, interest_type, aptitude_type, values_type FROM careers ORDER BY career_id"
            );
            $rows = $statement->fetchAll();

            return array_map(fn(array $row): array => [
                'career_id' => (int)$row['career_id'],
                'career_name' => $row['career_name'] ?? '',
                'career_icon' => $row['career_icon'] ?? '',
                'description' => $row['description'] ?? '',
                'required_skills' => $row['required_skills'] ?? '',
                'average_salary' => $row['average_salary'] ?? '',
                'growth_rate' => $row['growth_rate'] ?? '',
                'education_required' => $row['education_required'] ?? '',
                'personality_type' => $row['personality_type'] ?? '',
                'interest_type' => $row['interest_type'] ?? '',
                'aptitude_type' => $row['aptitude_type'] ?? '',
                'values_type' => $row['values_type'] ?? '',
            ], $rows);
        } catch (\Throwable) {
            return [];
        }
    }

    public function getStudentScores(int $userId): ?array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT personality_type, interest_type, aptitude_type, values_type, personality_score, interest_score, aptitude_score, values_score FROM student_assessment_scores WHERE student_id = :user_id LIMIT 1"
            );
            $statement->execute(['user_id' => $userId]);
            $row = $statement->fetch();

            if ($row) {
                return [
                    'personality_type' => $row['personality_type'] ?? '',
                    'interest_type' => $row['interest_type'] ?? '',
                    'aptitude_type' => $row['aptitude_type'] ?? '',
                    'values_type' => $row['values_type'] ?? '',
                    'personality_score' => (int)($row['personality_score'] ?? 0),
                    'interest_score' => (int)($row['interest_score'] ?? 0),
                    'aptitude_score' => (int)($row['aptitude_score'] ?? 0),
                    'values_score' => (int)($row['values_score'] ?? 0),
                ];
            }
        } catch (\Throwable) {
        }

        return null;
    }

    public function getEducationLevel(int $userId): ?string
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT COALESCE(e.label, '') AS education_level
                 FROM student_profiles sp
                 LEFT JOIN master_data e ON sp.education_level_id = e.id
                 WHERE sp.user_id = :user_id
                 LIMIT 1"
            );
            $statement->execute(['user_id' => $userId]);
            $row = $statement->fetch();

            $level = $row ? trim($row['education_level'] ?? '') : '';
            return $level !== '' ? $level : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function getExistingRecommendations(int $userId): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT cr.recommendation_id, cr.career_id, cr.match_score, cr.recommendation_reason, cr.created_at,
                        c.career_name, c.career_icon, c.description, c.required_skills, c.average_salary, c.growth_rate, c.education_required,
                        c.personality_type, c.interest_type, c.aptitude_type, c.values_type
                 FROM career_recommendations cr
                 JOIN careers c ON c.career_id = cr.career_id
                 WHERE cr.user_id = :user_id
                 ORDER BY cr.match_score DESC
                 LIMIT 5"
            );
            $statement->execute(['user_id' => $userId]);
            return $statement->fetchAll();
        } catch (\Throwable) {
            return [];
        }
    }

    public function deleteUserRecommendations(int $userId): void
    {
        try {
            $this->connection->prepare("DELETE FROM career_recommendations WHERE user_id = :user_id")
                ->execute(['user_id' => $userId]);
        } catch (\Throwable) {
        }
    }

    public function saveRecommendation(int $userId, int $careerId, float $matchScore, string $reason): bool
    {
        try {
            $statement = $this->connection->prepare(
                "INSERT INTO career_recommendations (user_id, career_id, match_score, recommendation_reason, created_at) VALUES (:user_id, :career_id, :match_score, :reason, NOW())"
            );
            return (bool)$statement->execute([
                'user_id' => $userId,
                'career_id' => $careerId,
                'match_score' => $matchScore,
                'reason' => $reason,
            ]);
        } catch (\Throwable) {
            return false;
        }
    }
}
