<?php

use App\Modules\Home\Presentation\Controllers\HomeController;
use App\Modules\Career\Presentation\Controllers\CareerController;
use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Modules\Admin\Presentation\Controllers\AdminController;
use App\Modules\Admin\Presentation\Controllers\RoleController;
use App\Modules\Admin\Presentation\Controllers\PermissionController;
use App\Modules\Admin\Presentation\Controllers\RolePermissionController;
use App\Modules\Admin\Presentation\Controllers\StudentFeaturePermissionController;
use App\Modules\Admin\Presentation\Controllers\UserController;
use App\Modules\Admin\Presentation\Controllers\AssessmentController as AdminAssessmentController;
use App\Modules\Admin\Presentation\Controllers\CareerController as AdminCareerController;
use App\Modules\Admin\Presentation\Controllers\QuestionController as AdminQuestionController;
use App\Modules\Admin\Presentation\Controllers\ReportsController as AdminReportsController;
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
    'admin-reports' => [AdminReportsController::class, 'index'],
    'admin-roles' => [RoleController::class, 'index'],
    'admin-roles-create' => [RoleController::class, 'create'],
    'admin-roles-store' => [RoleController::class, 'store'],
    'admin-roles-edit' => [RoleController::class, 'edit'],
    'admin-roles-update' => [RoleController::class, 'update'],
    'admin-roles-view' => [RoleController::class, 'show'],
    'admin-roles-delete' => [RoleController::class, 'delete'],
    'admin-permissions' => [PermissionController::class, 'index'],
    'admin-permissions-create' => [PermissionController::class, 'create'],
    'admin-permissions-store' => [PermissionController::class, 'store'],
    'admin-permissions-edit' => [PermissionController::class, 'edit'],
    'admin-permissions-update' => [PermissionController::class, 'update'],
    'admin-permissions-view' => [PermissionController::class, 'show'],
    'admin-permissions-delete' => [PermissionController::class, 'delete'],
    'admin-assign-permissions' => [RolePermissionController::class, 'index'],
    'admin-assign-permissions-save' => [RolePermissionController::class, 'save'],
    'dashboard' => [
    DashboardController::class,
    'index'
],
    
    'careers' => [CareerController::class, 'index'],
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