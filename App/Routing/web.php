<?php

use App\Modules\Home\Presentation\Controllers\HomeController;
use App\Modules\Career\Presentation\Controllers\CareerController;
use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Modules\Admin\Presentation\Controllers\AdminController;
use App\Modules\Admin\Presentation\Controllers\RolesAndPermissionsController;
use App\Modules\Admin\Presentation\Controllers\StudentFeaturePermissionController;
use App\Modules\Admin\Presentation\Controllers\UserController;
use App\Modules\Admin\Presentation\Controllers\AssessmentController as AdminAssessmentController;
use App\Modules\Admin\Presentation\Controllers\CareerController as AdminCareerController;
use App\Modules\Admin\Presentation\Controllers\QuestionController as AdminQuestionController;
use App\Modules\Admin\Presentation\Controllers\ReportsController as AdminReportsController;
use App\Modules\Admin\Presentation\Controllers\NotificationController as AdminNotificationController;
use App\Modules\Profile\Presentation\Controllers\ProfileController;
use App\Modules\Public\Presentation\Controllers\AssessmentController as PublicAssessmentController;
use App\Modules\Student\Presentation\Controllers\AssessmentController as StudentAssessmentController;
use App\Modules\Recommendation\Presentation\Controllers\RecommendationController;
use App\Modules\Dashboard\Presentation\Controllers\DashboardController;

return [

    'home' => [HomeController::class, 'index'],
    'admin-login' => [AdminController::class, 'login'],
    'admin-dashboard' => [AdminController::class, 'dashboard'],
    'admin-logout' => [AdminController::class, 'logout'],
    'admin-users' => [UserController::class, 'index'],
    'admin-users-create' => [UserController::class, 'create'],
    'admin-users-store' => [UserController::class, 'store'],
    'admin-users-edit' => [UserController::class, 'edit'],
    'admin-users-update' => [UserController::class, 'update'],
    'admin-users-view' => [UserController::class, 'show'],
    'admin-users-delete' => [UserController::class, 'delete'],
    'admin-users-toggle-status' => [UserController::class, 'toggleStatus'],
    'admin-settings-student-permissions' => [StudentFeaturePermissionController::class, 'index'],
    'admin-settings-student-permissions-manage' => [StudentFeaturePermissionController::class, 'manage'],
    'admin-settings-student-permissions-save' => [StudentFeaturePermissionController::class, 'save'],
    'student-feature-permissions' => [StudentFeaturePermissionController::class, 'index'],
    'student-feature-permissions-save' => [StudentFeaturePermissionController::class, 'save'],
    'admin-assessments' => [AdminAssessmentController::class, 'index'],
    'admin-assessments-view' => [AdminAssessmentController::class, 'show'],
    'admin-assessments-edit' => [AdminAssessmentController::class, 'edit'],
    'admin-assessments-update' => [AdminAssessmentController::class, 'update'],
    'admin-assessments-toggle-status' => [AdminAssessmentController::class, 'toggleStatus'],
    'admin-assessments-duplicate' => [AdminAssessmentController::class, 'duplicate'],
    'admin-careers' => [AdminCareerController::class, 'index'],
    'admin-careers-view' => [AdminCareerController::class, 'show'],
    'admin-careers-create' => [AdminCareerController::class, 'create'],
    'admin-careers-store' => [AdminCareerController::class, 'store'],
    'admin-careers-edit' => [AdminCareerController::class, 'edit'],
    'admin-careers-update' => [AdminCareerController::class, 'update'],
    'admin-careers-delete' => [AdminCareerController::class, 'delete'],
    'admin-questions' => [AdminQuestionController::class, 'index'],
    'admin-questions-view' => [AdminQuestionController::class, 'show'],
    'admin-questions-create' => [AdminQuestionController::class, 'create'],
    'admin-questions-store' => [AdminQuestionController::class, 'store'],
    'admin-questions-edit' => [AdminQuestionController::class, 'edit'],
    'admin-questions-update' => [AdminQuestionController::class, 'update'],
    'admin-questions-delete' => [AdminQuestionController::class, 'delete'],
    'admin-questions-duplicate' => [AdminQuestionController::class, 'duplicate'],
    'admin-questions-bulk-delete' => [AdminQuestionController::class, 'bulkDelete'],
    'admin-reports' => [AdminReportsController::class, 'index'],
    'admin-notifications' => [AdminNotificationController::class, 'index'],
    'admin-notifications-api-unread-count' => [AdminNotificationController::class, 'apiUnreadCount'],
    'admin-notifications-api-mark-read' => [AdminNotificationController::class, 'apiMarkAsRead'],
    'admin-notifications-api-mark-all-read' => [AdminNotificationController::class, 'apiMarkAllAsRead'],
    'admin-notifications-api-delete' => [AdminNotificationController::class, 'apiDelete'],
    'admin-role-permissions' => [RolesAndPermissionsController::class, 'index'],
    'admin-role-permissions-save' => [RolesAndPermissionsController::class, 'save'],
    'dashboard' => [
    DashboardController::class,
    'index'
],
    
    'careers' => [CareerController::class, 'index'],
    'career-detail' => [CareerController::class, 'show'],
    'about-us' => [HomeController::class, 'aboutUs'],
    'about' => [HomeController::class, 'about'],
    'contact' => [HomeController::class, 'contact'],

    'login' => [AuthController::class, 'login'],
    'forgot-password' => [AuthController::class, 'forgotPassword'],
    'verify-reset-code' => [AuthController::class, 'verifyResetCode'],
    'reset-password' => [AuthController::class, 'resetPassword'],

    'register' => [AuthController::class, 'register'],
    'verify-email' => [AuthController::class, 'verifyEmail'],
    'resend-verification' => [AuthController::class, 'resendVerification'],

    'google-login' => [AuthController::class, 'googleLogin'],

    'google-callback' => [AuthController::class, 'googleCallback'],

    'logout' => [AuthController::class, 'logout'],

    'profile' => [ProfileController::class, 'index'],

    'update-profile-image' => [ProfileController::class, 'updateProfileImage'],

    'edit-profile' => [App\Modules\Profile\Presentation\Controllers\ProfileController::class,'edit'],

    'update-profile' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'update'
],
'change-password' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'changePassword'
],

'notifications' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'notifications'
],

'student-change-password' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'studentChangePassword'
],

'update-password' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'updatePassword'
],

'guest-result' => [
    PublicAssessmentController::class,
    'guestResult'
],

'guest-personality' => [
    PublicAssessmentController::class,
    'personality'
],

'guest-interest' => [
    PublicAssessmentController::class,
    'interest'
],

'guest-aptitude' => [
    PublicAssessmentController::class,
    'aptitude'
],

'guest-values' => [
    PublicAssessmentController::class,
    'values'
],

'assessments' => [
    PublicAssessmentController::class,
    'index'
],

'student-assessments' => [
    StudentAssessmentController::class,
    'index'
],

'personality' => [
    StudentAssessmentController::class,
    'personality'
],

'interest' => [
    StudentAssessmentController::class,
    'interest'
],

'aptitude' => [
    StudentAssessmentController::class,
    'aptitude'
],

'values' => [
    StudentAssessmentController::class,
    'values'
],

'assessment-progress' => [
    StudentAssessmentController::class,
    'progress'
],

'assessment-result' => [
    StudentAssessmentController::class,
    'viewResult'
],

'assessment-detailed-answers' => [
    StudentAssessmentController::class,
    'detailedAnswers'
],

'recommendation' => [
    RecommendationController::class,
    'index'
],



];