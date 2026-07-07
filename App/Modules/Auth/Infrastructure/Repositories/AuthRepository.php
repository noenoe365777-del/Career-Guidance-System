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
        $sql = "INSERT INTO users (
            username,
            email,
            password,
            google_id,
            user_role_id,
            status_id,
            created_at,
            updated_at
        ) VALUES (
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
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':google_id' => $googleId,
            ':user_role_id' => $userRoleId,
            ':status_id' => $statusId
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
        $sql = "INSERT INTO student_profiles (
            user_id,
            gender_id,
            education_level_id,
            phone,
            address,
            date_of_birth,
            created_at,
            updated_at
        ) VALUES (
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
        $stmt = $this->pdo->prepare(
            'SELECT u.* FROM users u WHERE LOWER(u.email) = LOWER(:email) LIMIT 1'
        );
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findUserByGoogleId(string $googleId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.* FROM users u WHERE u.google_id = :google_id LIMIT 1'
        );
        $stmt->execute([':google_id' => $googleId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function createGoogleUser(string $username, string $email, string $googleId): bool
    {
        $sql = "INSERT INTO users (
            username,
            email,
            google_id,
            password,
            user_role_id,
            status_id,
            created_at,
            updated_at
        ) VALUES (
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
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function createVerification(int $userId, string $code, string $expiresAt): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS email_verifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                code VARCHAR(32) NOT NULL,
                expires_at DATETIME NOT NULL,
                verified TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email_verifications_user_id (user_id),
                INDEX idx_email_verifications_code (code)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $this->pdo->exec('DELETE FROM email_verifications WHERE user_id IS NULL OR user_id <= 0');

        $stmt = $this->pdo->prepare(
            'INSERT INTO email_verifications (user_id, code, expires_at) VALUES (:user_id, :code, :expires_at)'
        );

        return (bool)$stmt->execute([
            ':user_id' => $userId,
            ':code' => $code,
            ':expires_at' => $expiresAt
        ]);
    }

    public function createPasswordResetRequest(int $userId, string $code, string $expiresAt): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                code VARCHAR(32) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_password_resets_user_id (user_id),
                INDEX idx_password_resets_code (code)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $stmt = $this->pdo->prepare('DELETE FROM password_resets WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);

        $stmt = $this->pdo->prepare(
            'INSERT INTO password_resets (user_id, code, expires_at) VALUES (:user_id, :code, :expires_at)'
        );

        return (bool)$stmt->execute([
            ':user_id' => $userId,
            ':code' => $code,
            ':expires_at' => $expiresAt
        ]);
    }

    public function findPasswordResetRequestByEmailAndCode(string $email, string $code): ?array
    {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            return null;
        }

        $userId = (int)($user['user_id'] ?? $user['id'] ?? 0);
        if ($userId <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT * FROM password_resets WHERE user_id = :user_id AND code = :code ORDER BY created_at DESC, id DESC LIMIT 1'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':code' => $code
        ]);

        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$reset) {
            return null;
        }

        if (strtotime((string)$reset['expires_at']) < time()) {
            return null;
        }

        return $reset;
    }

    public function deletePasswordResetRequestsForUser(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM password_resets WHERE user_id = :user_id');
        return (bool)$stmt->execute([':user_id' => $userId]);
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {
        if ($userId <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE users SET password = :password, updated_at = NOW() WHERE user_id = :user_id'
        );

        return (bool)$stmt->execute([
            ':password' => $passwordHash,
            ':user_id' => $userId
        ]);
    }

    public function verifyCode(string $email, string $code, int $userId = 0): bool
    {
        $resolvedUserId = $userId;

        if ($resolvedUserId <= 0 && $email !== '') {
            $user = $this->findUserByEmail($email);
            if ($user) {
                $resolvedUserId = (int)($user['user_id'] ?? $user['id'] ?? 0);
            }
        }

        if ($resolvedUserId <= 0) {
            return false;
        }

        $this->pdo->exec('DELETE FROM email_verifications WHERE user_id IS NULL OR user_id <= 0');

        $stmt = $this->pdo->prepare(
            'SELECT * FROM email_verifications WHERE user_id = :user_id AND code = :code ORDER BY created_at DESC, id DESC LIMIT 1'
        );
        $stmt->execute([':user_id' => $resolvedUserId, ':code' => $code]);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rec) {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM email_verifications WHERE code = :code ORDER BY created_at DESC, id DESC LIMIT 1'
            );
            $stmt->execute([':code' => $code]);
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if (!$rec) {
            return false;
        }

        if (strtotime((string)$rec['expires_at']) < time()) {
            return false;
        }

        $this->pdo->prepare('UPDATE email_verifications SET verified = 1, updated_at = NOW() WHERE id = :id')
            ->execute([':id' => $rec['id']]);

        try {
            $this->pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) NOT NULL DEFAULT 0");
        } catch (\Throwable $e) {
            // Ignored if the column already exists or the driver does not support the syntax.
        }

        $this->pdo->prepare(
            'UPDATE users SET is_verified = 1 WHERE user_id = :user_id'
        )->execute([
            ':user_id' => $resolvedUserId
        ]);
        return true;
    }

    public function isUserVerifiedById(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare('SELECT is_verified FROM users WHERE user_id = :user_id LIMIT 1');
            $stmt->execute([':user_id' => $userId]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false && isset($res['is_verified'])) {
                return (int)$res['is_verified'] === 1;
            }
        } catch (\Throwable $e) {
            // Fallback below.
        }

        $stmt = $this->pdo->prepare(
            'SELECT verified FROM email_verifications WHERE user_id = :user_id ORDER BY created_at DESC, id DESC LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rec && (int)$rec['verified'] === 1) {
            try {
                $this->pdo->prepare('UPDATE users SET is_verified = 1 WHERE user_id = :user_id')
                    ->execute([':user_id' => $userId]);
            } catch (\Throwable $e) {
                // Ignore write errors; the login should still proceed.
            }
            return true;
        }

        return false;
    }
}