<?php

declare(strict_types=1);

namespace App\Shared\Auth;

class Auth
{
    public static function check(): bool
    {
        return self::isAdmin() || self::isStudent();
    }

    public static function isAdmin(): bool
    {
        $admin = $_SESSION['admin'] ?? null;
        return is_array($admin) && !empty($admin['id']);
    }

    public static function isStudent(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (self::isAdmin()) {
            return $_SESSION['admin'];
        }
        if (self::isStudent()) {
            return $_SESSION['user'] ?? null;
        }
        return null;
    }

    public static function id(): ?int
    {
        if (self::isAdmin()) {
            return (int)($_SESSION['admin']['id'] ?? 0);
        }
        if (self::isStudent()) {
            return (int)($_SESSION['user_id'] ?? 0);
        }
        return null;
    }

    public static function role(): ?string
    {
        if (self::isAdmin()) {
            return 'admin';
        }
        if (self::isStudent()) {
            return 'student';
        }
        return null;
    }
}
