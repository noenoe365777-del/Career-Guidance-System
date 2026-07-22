<?php

declare(strict_types=1);

namespace App\Modules\Admin\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Admin\Domain\Repositories\CareerRepositoryInterface;
use PDO;
use PDOException;

class CareerRepository implements CareerRepositoryInterface
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    public function getAllCareers(
        int $page = 1,
        int $perPage = 10,
        string $search = '',
        ?string $educationFilter = null,
        ?string $growthFilter = null,
        ?string $categoryFilter = null,
        ?string $statusFilter = null,
        string $sort = 'az'
    ): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = 'LOWER(c.career_name) LIKE :search';
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        if ($educationFilter !== null && $educationFilter !== '') {
            $conditions[] = 'LOWER(c.education_required) = LOWER(:education)';
            $params[':education'] = $educationFilter;
        }

        if ($growthFilter !== null && $growthFilter !== '') {
            $conditions[] = 'LOWER(c.growth_rate) = LOWER(:growth)';
            $params[':growth'] = $growthFilter;
        }

        if ($categoryFilter !== null && $categoryFilter !== '') {
            $conditions[] = 'LOWER(c.career_icon) = LOWER(:category)';
            $params[':category'] = $categoryFilter;
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $conditions[] = 'c.status = :status';
            $params[':status'] = $statusFilter;
        }

        $where = '';
        if ($conditions !== []) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $orderBy = match ($sort) {
            'most_recommended' => 'recommendation_count DESC, c.career_name ASC',
            'newest' => 'c.created_at DESC, c.career_name ASC',
            default => 'c.career_name ASC',
        };

        $selectSql = "
            SELECT c.*, COALESCE(rc.cnt, 0) AS recommendation_count
            FROM careers c
            LEFT JOIN (
                SELECT career_id, COUNT(*) AS cnt
                FROM career_recommendations
                GROUP BY career_id
            ) rc ON c.career_id = rc.career_id
            {$where}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*)
            FROM careers c
            {$where}
        ";

        try {
            $countStmt = $this->connection->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = (int)$countStmt->fetchColumn();

            $stmt = $this->connection->prepare($selectSql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $careers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'careers' => $careers,
                'total' => $total,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => (int)ceil($total / $perPage),
            ];
        } catch (PDOException) {
            return [
                'careers' => [],
                'total' => 0,
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalPages' => 1,
            ];
        }
    }

    public function getDistinctPersonalityTypes(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT personality_type FROM careers WHERE personality_type IS NOT NULL AND personality_type != '' ORDER BY personality_type ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDistinctInterestTypes(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT interest_type FROM careers WHERE interest_type IS NOT NULL AND interest_type != '' ORDER BY interest_type ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDistinctAptitudeTypes(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT aptitude_type FROM careers WHERE aptitude_type IS NOT NULL AND aptitude_type != '' ORDER BY aptitude_type ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDistinctValuesTypes(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT values_type FROM careers WHERE values_type IS NOT NULL AND values_type != '' ORDER BY values_type ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAllSkills(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(required_skills, ',', n.n), ',', -1)) AS skill
                FROM careers
                CROSS JOIN (
                    SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
                    UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
                    UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16
                    UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20
                ) n
                WHERE required_skills IS NOT NULL AND required_skills != ''
                AND CHAR_LENGTH(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(required_skills, ',', n.n), ',', -1))) > 0
                ORDER BY skill ASC");
            $skills = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return array_values(array_filter(array_map('trim', $skills)));
        } catch (PDOException) {
            return [];
        }
    }

    public function getCareerById(int $id): ?array
    {
        try {
            $stmt = $this->connection->prepare('
                SELECT c.*, COALESCE(rc.cnt, 0) AS recommendation_count
                FROM careers c
                LEFT JOIN (
                    SELECT career_id, COUNT(*) AS cnt
                    FROM career_recommendations
                    GROUP BY career_id
                ) rc ON c.career_id = rc.career_id
                WHERE c.career_id = :id
                LIMIT 1
            ');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException) {
            return null;
        }
    }

    public function getTotalCareers(): int
    {
        try {
            $stmt = $this->connection->query('SELECT COUNT(*) FROM careers');
            return (int)$stmt->fetchColumn();
        } catch (PDOException) {
            return 0;
        }
    }

    public function getDistinctEducationLevels(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT education_required FROM careers WHERE education_required IS NOT NULL AND education_required != '' ORDER BY education_required ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDistinctGrowthRates(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT growth_rate FROM careers WHERE growth_rate IS NOT NULL AND growth_rate != '' ORDER BY growth_rate ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function createCareer(array $data): ?int
    {
        try {
            $stmt = $this->connection->prepare('
                INSERT INTO careers (career_name, career_icon, status, description, required_skills, average_salary, growth_rate, education_required, personality_type, interest_type, aptitude_type, values_type)
                VALUES (:career_name, :career_icon, :status, :description, :required_skills, :average_salary, :growth_rate, :education_required, :personality_type, :interest_type, :aptitude_type, :values_type)
            ');
            $stmt->execute([
                ':career_name' => $data['career_name'],
                ':career_icon' => $data['career_icon'] ?? null,
                ':status' => $data['status'] ?? 'active',
                ':description' => $data['description'] ?? null,
                ':required_skills' => $data['required_skills'] ?? null,
                ':average_salary' => $data['average_salary'] ?? null,
                ':growth_rate' => $data['growth_rate'] ?? null,
                ':education_required' => $data['education_required'] ?? null,
                ':personality_type' => $data['personality_type'] ?? null,
                ':interest_type' => $data['interest_type'] ?? null,
                ':aptitude_type' => $data['aptitude_type'] ?? null,
                ':values_type' => $data['values_type'] ?? null,
            ]);
            return (int)$this->connection->lastInsertId();
        } catch (PDOException) {
            return null;
        }
    }

    public function updateCareer(int $id, array $data): bool
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            $allowedFields = ['career_name', 'career_icon', 'status', 'description', 'required_skills', 'average_salary', 'growth_rate', 'education_required', 'personality_type', 'interest_type', 'aptitude_type', 'values_type'];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }

            if ($fields === []) {
                return false;
            }

            $sql = 'UPDATE careers SET ' . implode(', ', $fields) . ' WHERE career_id = :id';
            $stmt = $this->connection->prepare($sql);

            return (bool)$stmt->execute($params);
        } catch (PDOException) {
            return false;
        }
    }

    public function deleteCareer(int $id): bool
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->prepare('DELETE FROM career_recommendations WHERE career_id = :id')->execute([':id' => $id]);
            $this->connection->prepare('DELETE FROM careers WHERE career_id = :id')->execute([':id' => $id]);
            $this->connection->commit();
            return true;
        } catch (PDOException) {
            $this->connection->rollBack();
            return false;
        }
    }

    public function getSummaryStats(): array
    {
        try {
            $totalCareers = (int)$this->connection->query('SELECT COUNT(*) FROM careers')->fetchColumn();

            $studentsWithRecommendations = (int)$this->connection->query('
                SELECT COUNT(DISTINCT user_id) FROM career_recommendations
            ')->fetchColumn();

            $mostRecommended = $this->connection->query('
                SELECT c.career_name, COUNT(*) AS cnt
                FROM career_recommendations r
                JOIN careers c ON r.career_id = c.career_id
                GROUP BY r.career_id
                ORDER BY cnt DESC
                LIMIT 1
            ')->fetch(PDO::FETCH_ASSOC);

            $totalRecommendations = (int)$this->connection->query('
                SELECT COUNT(*) FROM career_recommendations
            ')->fetchColumn();

            return [
                'total_careers' => $totalCareers,
                'students_with_recommendations' => $studentsWithRecommendations,
                'most_recommended_name' => $mostRecommended ? $mostRecommended['career_name'] : null,
                'most_recommended_count' => $mostRecommended ? (int)$mostRecommended['cnt'] : 0,
                'total_recommendations' => $totalRecommendations,
            ];
        } catch (PDOException) {
            return [
                'total_careers' => 0,
                'students_with_recommendations' => 0,
                'most_recommended_name' => null,
                'most_recommended_count' => 0,
                'total_recommendations' => 0,
            ];
        }
    }

    public function getDistinctStatuses(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT status FROM careers WHERE status IS NOT NULL ORDER BY status ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getDistinctCategories(): array
    {
        try {
            $stmt = $this->connection->query("SELECT DISTINCT career_icon FROM careers WHERE career_icon IS NOT NULL AND career_icon != '' ORDER BY career_icon ASC");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException) {
            return [];
        }
    }

    public function getCareerRecommendationStudents(int $careerId): array
    {
        try {
            $stmt = $this->connection->prepare('
                SELECT r.recommendation_id, r.user_id, r.match_score, r.recommendation_reason, r.created_at,
                       u.username, u.email
                FROM career_recommendations r
                JOIN users u ON r.user_id = u.user_id
                WHERE r.career_id = :career_id
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([':career_id' => $careerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return [];
        }
    }

    public function getAllRecommendationStudents(): array
    {
        try {
            $stmt = $this->connection->query('
                SELECT r.career_id, r.recommendation_id, r.user_id, r.match_score, r.recommendation_reason, r.created_at,
                       u.username, u.email
                FROM career_recommendations r
                JOIN users u ON r.user_id = u.user_id
                ORDER BY r.career_id, r.created_at DESC
            ');
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $grouped = [];
            foreach ($rows as $row) {
                $cid = (int)$row['career_id'];
                if (!isset($grouped[$cid])) {
                    $grouped[$cid] = [];
                }
                $grouped[$cid][] = $row;
            }
            return $grouped;
        } catch (PDOException) {
            return [];
        }
    }

    public function getCareerRecommendationAnalytics(int $careerId): array
    {
        try {
            $stmt = $this->connection->prepare('
                SELECT r.recommendation_id, r.user_id, r.match_score, r.recommendation_reason, r.created_at,
                       u.username, u.email,
                       COALESCE(md.label, CONCAT("Level ", sp.education_level_id)) AS education_level
                FROM career_recommendations r
                JOIN users u ON u.user_id = r.user_id
                LEFT JOIN student_profiles sp ON sp.user_id = u.user_id
                LEFT JOIN master_data md ON md.id = sp.education_level_id AND md.category = "education_level"
                WHERE r.career_id = :career_id
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([':career_id' => $careerId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $history = [];
            $educationDistribution = [];
            $scoreTotal = 0.0;
            $recommendationCount = count($rows);
            $lastRecommendationDate = null;

            foreach ($rows as $row) {
                $score = (float)($row['match_score'] ?? 0);
                $scoreTotal += $score;
                $lastRecommendationDate = $lastRecommendationDate ?? (string)($row['created_at'] ?? '');

                $educationLabel = trim((string)($row['education_level'] ?? 'Unknown'));
                if ($educationLabel === '') {
                    $educationLabel = 'Unknown';
                }
                if (!isset($educationDistribution[$educationLabel])) {
                    $educationDistribution[$educationLabel] = 0;
                }
                $educationDistribution[$educationLabel]++;

                $history[] = [
                    'student' => trim((string)($row['username'] ?? $row['email'] ?? 'Unknown')),
                    'score' => $score,
                    'date' => (string)($row['created_at'] ?? ''),
                    'reason' => trim((string)($row['recommendation_reason'] ?? '')),
                ];
            }

            $averageScore = $recommendationCount > 0 ? round($scoreTotal / $recommendationCount, 2) : 0.0;

            return [
                'recommended_count' => $recommendationCount,
                'average_score' => $averageScore,
                'education_distribution' => $educationDistribution,
                'last_recommendation_date' => $lastRecommendationDate,
                'history' => array_slice($history, 0, 6),
            ];
        } catch (PDOException) {
            return [
                'recommended_count' => 0,
                'average_score' => 0.0,
                'education_distribution' => [],
                'last_recommendation_date' => null,
                'history' => [],
            ];
        }
    }
}
