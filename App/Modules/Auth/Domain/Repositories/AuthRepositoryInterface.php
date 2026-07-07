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

    public function findUserByEmail(string $email): ?array;

    public function findUserByGoogleId(string $googleId): ?array;

    public function usernameExists(string $username): bool;

    public function emailExists(string $email): bool;

    public function createVerification(int $userId, string $code, string $expiresAt): bool;

    public function verifyCode(string $email, string $code, int $userId = 0): bool;

    public function createPasswordResetRequest(int $userId, string $code, string $expiresAt): bool;

    public function findPasswordResetRequestByEmailAndCode(string $email, string $code): ?array;

    public function deletePasswordResetRequestsForUser(int $userId): bool;

    public function updatePassword(int $userId, string $passwordHash): bool;

    public function isUserVerifiedById(int $userId): bool;
}