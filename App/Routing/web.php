<?php

use App\Modules\Home\Presentation\Controllers\HomeController;
use App\Modules\Career\Presentation\Controllers\CareerController;
use App\Modules\Auth\Presentation\Controllers\AuthController;
use App\Modules\Admin\Presentation\Controllers\AdminController;
use App\Modules\Admin\Presentation\Controllers\RoleController;
use App\Modules\Admin\Presentation\Controllers\PermissionController;
use App\Modules\Admin\Presentation\Controllers\RolePermissionController;
use App\Modules\Admin\Presentation\Controllers\UserController;
use App\Modules\Profile\Presentation\Controllers\ProfileController;
use App\Modules\Assessment\Presentation\Controllers\AssessmentController;
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

'update-password' => [
    App\Modules\Profile\Presentation\Controllers\ProfileController::class,
    'updatePassword'
],

'assessments' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'index'
],

'personality' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'personality'
],

'interest' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'interest'
],


'aptitude' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'aptitude'
],

'values' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'values'
],

'assessment-progress' => [
    App\Modules\Assessment\Presentation\Controllers\AssessmentController::class,
    'progress'
],

'recommendation' => [
    RecommendationController::class,
    'index'
],



];