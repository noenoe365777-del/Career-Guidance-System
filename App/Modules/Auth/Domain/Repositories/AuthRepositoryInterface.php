<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Repositories;

interface AuthRepositoryInterface
{
    public function createUser(
    string $username,
    string $email,
    ?string $password,
    ?string $googleId,
    int $userRoleId,
    int $statusId
): int;

public function createStudentProfile(
    int $userId,
    int $genderId,
    int $educationLevelId,
    ?string $phone,
    ?string $address,
    ?string $dateOfBirth
): bool;

public function createGoogleUser(
    string $username,
    string $email,
    string $googleId
): bool;
}