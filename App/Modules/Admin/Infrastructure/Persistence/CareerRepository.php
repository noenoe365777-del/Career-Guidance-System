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

    public function getAllCareers(int $page = 1, int $perPage = 10, string $search = '', ?string $educationFilter = null, ?string $growthFilter = null): array
    {
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

        $where = '';
        if ($conditions !== []) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $selectSql = "
            SELECT c.*
            FROM careers c
            {$where}
            ORDER BY c.career_name ASC
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

    public function getCareerById(int $id): ?array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM careers WHERE career_id = :id LIMIT 1');
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
                INSERT INTO careers (career_name, description, required_skills, average_salary, growth_rate, education_required, personality_type, interest_type, aptitude_type, values_type)
                VALUES (:career_name, :description, :required_skills, :average_salary, :growth_rate, :education_required, :personality_type, :interest_type, :aptitude_type, :values_type)
            ');
            $stmt->execute([
                ':career_name' => $data['career_name'],
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

            $allowedFields = ['career_name', 'description', 'required_skills', 'average_salary', 'growth_rate', 'education_required', 'personality_type', 'interest_type', 'aptitude_type', 'values_type'];

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
}
