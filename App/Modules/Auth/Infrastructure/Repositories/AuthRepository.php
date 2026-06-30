<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Repositories;

use PDO;
use App\Modules\Auth\Domain\Repositories\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
public function createUser(
    string $username,
    string $email,
    ?string $password,
    ?string $googleId,
    int $userRoleId,
    int $statusId
): int {

    $sql = "INSERT INTO users
    (
        username,
        email,
        password,
        google_id,
        user_role_id,
        status_id,
        created_at,
        updated_at
    )

    VALUES
    (
        :username,
        :email,
        :password,
        :google_id,
        :user_role_id,
        :status_id,
        NOW(),
        NOW()
    )";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':username'      => $username,
        ':email'         => $email,
        ':password'      => $password,
        ':google_id'     => $googleId,
        ':user_role_id'  => $userRoleId,
        ':status_id'     => $statusId
    ]);

    return (int)$this->pdo->lastInsertId();
}

public function createStudentProfile(
    int $userId,
    int $genderId,
    int $educationLevelId,
    ?string $phone,
    ?string $address,
    ?string $dateOfBirth
): bool {

    $sql = "INSERT INTO student_profiles
    (
        user_id,
        gender_id,
        education_level_id,
        phone,
        address,
        date_of_birth,
        created_at,
        updated_at
    )

    VALUES
    (
        :user_id,
        :gender_id,
        :education_level_id,
        :phone,
        :address,
        :date_of_birth,
        NOW(),
        NOW()
    )";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':user_id' => $userId,
        ':gender_id' => $genderId,
        ':education_level_id' => $educationLevelId,
        ':phone' => $phone,
        ':address' => $address,
        ':date_of_birth' => $dateOfBirth
    ]);
}

  public function findUserByEmail(string $email): ?array
{
    $sql = "
        SELECT
            u.*,
            md.label AS role_name
        FROM users u
        LEFT JOIN master_data md
            ON md.id = u.user_role_id
        WHERE u.email = :email
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

 public function findUserByGoogleId(string $googleId): ?array
{
    $sql = "
        SELECT
            u.*,
            md.label AS role_name
        FROM users u
        LEFT JOIN master_data md
            ON md.id = u.user_role_id
        WHERE u.google_id = :google_id
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':google_id' => $googleId
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

  public function createGoogleUser(
    string $username,
    string $email,
    string $googleId
): bool {

    $sql = "INSERT INTO users
    (
        username,
        email,
        google_id,
        password,
        user_role_id,
        status_id,
        created_at,
        updated_at
    )

    VALUES
    (
        :username,
        :email,
        :google_id,
        NULL,
        2,
        3,
        NOW(),
        NOW()
    )";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':google_id' => $googleId
    ]);
}

    public function emailExists(string $email): bool
{
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'email' => $email
    ]);

    return (int) $stmt->fetchColumn() > 0;
}


public function usernameExists(string $username): bool
{
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':username' => $username
    ]);

    return (int)$stmt->fetchColumn() > 0;
}


}