<?php

declare(strict_types=1);

namespace App\Shared;

use App\Modules\Admin\Application\Services\NotificationService;

class NotificationHelper
{
    private static ?NotificationService $service = null;

    private static function getService(): NotificationService
    {
        if (self::$service === null) {
            self::$service = new NotificationService();
        }
        return self::$service;
    }

    private static function getAdminId(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return (int)(($_SESSION['admin']['id'] ?? $_SESSION['admin_id'] ?? 0));
    }

    public static function notify(string $type, string $title, string $message, ?string $link = null, ?string $entityType = null, ?int $entityId = null): int
    {
        $data = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
        ];
        return self::getService()->create($data);
    }

    public static function questionCreated(string $questionText, int $questionId, string $assessmentName): void
    {
        self::notify(
            'question',
            'New Question Added',
            "Question \"{$questionText}\" added to {$assessmentName}.",
            '/index.php?page=admin-questions-view&id=' . $questionId
        );
    }

    public static function questionUpdated(string $questionText, int $questionId): void
    {
        self::notify(
            'question',
            'Question Updated',
            "Question #{$questionId} \"{$questionText}\" has been updated.",
            '/index.php?page=admin-questions-view&id=' . $questionId
        );
    }

    public static function questionDeleted(string $questionText): void
    {
        self::notify(
            'question',
            'Question Deleted',
            "Question \"{$questionText}\" has been deleted."
        );
    }

    public static function careerCreated(string $careerName, int $careerId): void
    {
        self::notify(
            'career',
            'New Career Added',
            "Career \"{$careerName}\" has been added.",
            '/index.php?page=admin-careers-view&id=' . $careerId
        );
    }

    public static function careerUpdated(string $careerName, int $careerId): void
    {
        self::notify(
            'career',
            'Career Updated',
            "Career \"{$careerName}\" has been updated.",
            '/index.php?page=admin-careers-view&id=' . $careerId
        );
    }

    public static function careerDeleted(string $careerName): void
    {
        self::notify(
            'career',
            'Career Deleted',
            "Career \"{$careerName}\" has been deleted."
        );
    }

    public static function assessmentCreated(string $assessmentTitle, int $assessmentId): void
    {
        self::notify(
            'assessment',
            'New Assessment Created',
            "Assessment \"{$assessmentTitle}\" has been created.",
            '/index.php?page=admin-assessments-view&id=' . $assessmentId
        );
    }

    public static function assessmentUpdated(string $assessmentTitle, int $assessmentId): void
    {
        self::notify(
            'assessment',
            'Assessment Updated',
            "Assessment \"{$assessmentTitle}\" has been updated.",
            '/index.php?page=admin-assessments-view&id=' . $assessmentId
        );
    }

    public static function assessmentDeleted(string $assessmentTitle): void
    {
        self::notify(
            'assessment',
            'Assessment Deleted',
            "Assessment \"{$assessmentTitle}\" has been deleted."
        );
    }

    public static function assessmentCompleted(string $studentName, string $assessmentTitle, int $score): void
    {
        self::notify(
            'assessment',
            'Assessment Completed',
            "{$studentName} completed {$assessmentTitle} with score {$score}%."
        );
    }

    public static function studentRegistered(string $studentName, int $userId): void
    {
        self::notify(
            'user',
            'New Student Registered',
            "{$studentName} has created a new account.",
            '/index.php?page=admin-users-view&id=' . $userId
        );
    }

    public static function recommendationGenerated(string $studentName, string $careerName): void
    {
        self::notify(
            'career',
            'Recommendation Generated',
            "Career recommendation generated for {$studentName}: {$careerName}."
        );
    }

    public static function userProfileUpdated(string $studentName, int $userId): void
    {
        self::notify(
            'user',
            'Profile Updated',
            "{$studentName} updated their profile information.",
            '/index.php?page=admin-users-view&id=' . $userId
        );
    }

    public static function adminLogin(string $adminName): void
    {
        self::notify(
            'system',
            'Admin Login',
            "Admin {$adminName} logged in.",
        );
    }

    public static function system(string $title, string $message): void
    {
        self::notify('system', $title, $message);
    }
}
