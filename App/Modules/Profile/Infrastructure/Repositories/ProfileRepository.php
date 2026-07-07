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
public function updateProfile(int $userId, array $data): bool
{
    // Update users table
    $userStmt = $this->pdo->prepare("
        UPDATE users
        SET username = :username,
            updated_at = NOW()
        WHERE user_id = :user_id
    ");

    $userStmt->execute([
        ':username' => $data['username'],
        ':user_id'  => $userId
    ]);

    $genderId = !empty($data['gender'])
        ? $this->getGenderId($data['gender'])
        : null;

    $educationId = !empty($data['education_level'])
        ? $this->getEducationLevelId($data['education_level'])
        : null;

    // Check whether profile exists
    $check = $this->pdo->prepare("
        SELECT COUNT(*)
        FROM student_profiles
        WHERE user_id = :user_id
    ");

    $check->execute([
        ':user_id' => $userId
    ]);

    $exists = $check->fetchColumn();

    if ($exists) {

        $stmt = $this->pdo->prepare("
            UPDATE student_profiles
            SET
                phone = :phone,
                address = :address,
                date_of_birth = :dob,
                gender_id = :gender,
                education_level_id = :education,
                updated_at = NOW()
            WHERE user_id = :user_id
        ");

    } else {

        $stmt = $this->pdo->prepare("
            INSERT INTO student_profiles
            (
                user_id,
                phone,
                address,
                date_of_birth,
                gender_id,
                education_level_id
            )
            VALUES
            (
                :user_id,
                :phone,
                :address,
                :dob,
                :gender,
                :education
            )
        ");

    }

    return $stmt->execute([
        ':user_id'   => $userId,
        ':phone'     => $data['phone'] ?? null,
        ':address'   => $data['address'] ?? null,
        ':dob'       => $data['date_of_birth'] ?? null,
        ':gender'    => $genderId,
        ':education' => $educationId
    ]);
}

public function updateProfileImage(
    int $userId,
    string $imageName
): bool
{
    // Check whether profile already exists
    $check = $this->pdo->prepare("
        SELECT user_id
        FROM student_profiles
        WHERE user_id = :user_id
    ");

    $check->execute([
        ':user_id' => $userId
    ]);

    if ($check->fetch()) {

        // Update existing profile
        $sql = "
            UPDATE student_profiles
            SET profile_image = :image
            WHERE user_id = :user_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':image' => $imageName,
            ':user_id' => $userId
        ]);

    } else {

        // Create profile
        $sql = "
            INSERT INTO student_profiles
            (
                user_id,
                profile_image
            )
            VALUES
            (
                :user_id,
                :image
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':user_id' => $userId,
            ':image' => $imageName
        ]);
    }
}


private function getGenderId(string $gender): ?int
{
    $sql = "
        SELECT id
        FROM master_data
        WHERE category = 'gender'
        AND label = :label
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':label' => $gender
    ]);

    return $stmt->fetchColumn() ?: null;
}

private function getEducationLevelId(string $education): ?int
{
    $sql = "
        SELECT id
        FROM master_data
        WHERE category = 'education_level'
        AND label = :label
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':label' => $education
    ]);

    return $stmt->fetchColumn() ?: null;
}

public function updatePassword(
    int $userId,
    string $currentPassword,
    string $newPassword
): array {

    // Get current password hash
    $sql = "
        SELECT password
        FROM users
        WHERE user_id = :user_id
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId
    ]);

    $hash = $stmt->fetchColumn();

    if (!$hash) {
        return [
            'success' => false,
            'errors' => [
                'User not found.'
            ]
        ];
    }

    // Verify current password
    if (!password_verify($currentPassword, $hash)) {

        return [
            'success' => false,
            'errors' => [
                'Current password is incorrect.'
            ]
        ];
    }

    // Hash new password
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password
    $updateSql = "
        UPDATE users
        SET
            password = :password,
            updated_at = NOW()
        WHERE user_id = :user_id
    ";

    $updateStmt = $this->pdo->prepare($updateSql);

    $success = $updateStmt->execute([
        ':password' => $newHash,
        ':user_id'  => $userId
    ]);

    if (!$success) {
        return [
            'success' => false,
            'errors' => [
                'Failed to update password.'
            ]
        ];
    }

    return [
        'success' => true
    ];
}

}