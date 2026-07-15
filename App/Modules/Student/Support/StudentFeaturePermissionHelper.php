<?php

declare(strict_types=1);

namespace App\Modules\Student\Support;

use App\Modules\Student\Infrastructure\StudentFeaturePermissionModel;
use App\Shared\Core\View;

class StudentFeaturePermissionHelper
{
    public static function getFeatureDefinitions(): array
    {
        return (new StudentFeaturePermissionModel())->getFeatureDefinitions();
    }

    public static function getFeatureKeyForPage(string $page): ?string
    {
        $map = [
            'dashboard' => 'view_dashboard',

            'student-assessments' => 'take_assessment',
            'personality' => 'take_assessment',
            'interest' => 'take_assessment',
            'aptitude' => 'take_assessment',
            'values' => 'take_assessment',
            'assessment-progress' => 'take_assessment',

            'assessment-result' => 'view_results',
            'assessment-detailed-answers' => 'view_results',

'notifications' => 'view_notifications',
'recommendation' => 'view_recommendations',
'career-recommendation' => 'view_recommendations',

            'profile' => 'edit_profile',
            'edit-profile' => 'edit_profile',
            'update-profile' => 'edit_profile',

            'change-password' => 'change_password',
            'update-password' => 'change_password',
        ];

        return $map[$page] ?? null;
    }

    public static function currentStudentUserId(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;
        if (!is_array($user)) {
            return 0;
        }

        return (int)($user['id'] ?? $user['user_id'] ?? $_SESSION['user_id'] ?? 0);
    }

    public static function canStudentAccessFeature(int $userId, string $featureKey): bool
    {
        if ($userId <= 0) {
            return true;
        }

        return (new StudentFeaturePermissionModel())->hasFeatureAccess($userId, $featureKey);
    }

    public static function isStudentFeatureRestricted(string $page): bool
    {
        return self::getFeatureKeyForPage($page) !== null;
    }

    public static function ensureStudentPageAccess(string $page):bool
    {
        $featureKey = self::getFeatureKeyForPage($page);
        if ($featureKey === null) {
            return true;
        }

        $studentUserId = self::currentStudentUserId();
        if ($studentUserId <= 0) {
            return true;
        }

        if (self::canStudentAccessFeature($studentUserId, $featureKey)) {
            return true;
        }

        http_response_code(403);
        View::render(
            'Admin/Presentation/Views/403',
            [
                'layout' => 'none',
                'pageTitle' => 'Access Denied',
                'message' => 'This feature is currently disabled for your account.',
            ]
        );
        exit;
    }
}
