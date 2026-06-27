<?php

declare(strict_types=1);

namespace App\Modules\Profile\Infrastructure\Repositories;

use PDO;
use App\Modules\Profile\Domain\Repositories\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get student profile by user ID
     */
    public function findByUserId(int $userId): ?array
    {
        $sql = "
            SELECT
                u.user_id,
                u.username,
                u.email,

                sp.phone,
                sp.address,
                sp.date_of_birth,
                sp.profile_image,

                g.label AS gender,
                e.label AS education_level

            FROM users u

            LEFT JOIN student_profiles sp
                ON u.user_id = sp.user_id

            LEFT JOIN master_data g
                ON sp.gender_id = g.id

            LEFT JOIN master_data e
                ON sp.education_level_id = e.id

            WHERE u.user_id = :user_id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profile ?: null;
    }

    /**
     * Update student profile
     */
    public function updateProfile(
        int $userId,
        ?string $phone,
        ?string $address,
        ?string $dateOfBirth,
        ?int $genderId,
        ?int $educationLevelId,
        ?string $profileImage
    ): bool {

        $sql = "
            UPDATE student_profiles

            SET
                phone = :phone,
                address = :address,
                date_of_birth = :date_of_birth,
                gender_id = :gender_id,
                education_level_id = :education_level_id,
                profile_image = :profile_image,
                updated_at = NOW()

            WHERE user_id = :user_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':phone' => $phone,
            ':address' => $address,
            ':date_of_birth' => $dateOfBirth,
            ':gender_id' => $genderId,
            ':education_level_id' => $educationLevelId,
            ':profile_image' => $profileImage,
            ':user_id' => $userId
        ]);
    }
}