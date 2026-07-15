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
use App\Modules\Recommendation\Presentation\Controllers\CareerRecommendationController;
use App\Modules\Dashboard\Presentation\Controllers\DashboardController;

$router = new \App\Routing\Router();

/*
|--------------------------------------------------------------------------
| HOME ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/home', [HomeController::class, 'index']);
$router->get('/about-us', [HomeController::class, 'aboutUs']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
|
| These handle both displaying forms and processing submissions
| within the same controller method, so they accept GET and POST.
|
*/

$router->match(['GET', 'POST'], '/login', [AuthController::class, 'login']);
$router->match(['GET', 'POST'], '/register', [AuthController::class, 'register']);
$router->match(['GET', 'POST'], '/forgot-password', [AuthController::class, 'forgotPassword']);
$router->match(['GET', 'POST'], '/verify-reset-code', [AuthController::class, 'verifyResetCode']);
$router->match(['GET', 'POST'], '/reset-password', [AuthController::class, 'resetPassword']);
$router->get('/verify-email', [AuthController::class, 'verifyEmail']);
$router->get('/resend-verification', [AuthController::class, 'resendVerification']);
$router->get('/google-login', [AuthController::class, 'googleLogin']);
$router->get('/google-callback', [AuthController::class, 'googleCallback']);
$router->get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| DASHBOARD ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/profile', [ProfileController::class, 'index']);
$router->get('/edit-profile', [ProfileController::class, 'edit']);
$router->post('/update-profile', [ProfileController::class, 'update']);
$router->post('/update-profile-image', [ProfileController::class, 'updateProfileImage']);
$router->get('/change-password', [ProfileController::class, 'changePassword']);
$router->get('/student-change-password', [ProfileController::class, 'studentChangePassword']);
$router->post('/update-password', [ProfileController::class, 'updatePassword']);
$router->get('/notifications', [ProfileController::class, 'notifications']);

/*
|--------------------------------------------------------------------------
| PUBLIC ASSESSMENT ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/assessments', [PublicAssessmentController::class, 'index']);

$router->group('/guest', function ($router) {
    $router->post('/api-start', [PublicAssessmentController::class, 'apiStart']);
    $router->get('/api-question', [PublicAssessmentController::class, 'apiQuestion']);
    $router->post('/api-save', [PublicAssessmentController::class, 'apiSave']);
    $router->post('/api-finish', [PublicAssessmentController::class, 'apiFinish']);
    $router->get('/result', [PublicAssessmentController::class, 'guestResult']);
    $router->get('/personality', [PublicAssessmentController::class, 'personality']);
    $router->get('/interest', [PublicAssessmentController::class, 'interest']);
    $router->get('/aptitude', [PublicAssessmentController::class, 'aptitude']);
    $router->get('/values', [PublicAssessmentController::class, 'values']);
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
|
| These routes are kept flat because their page names don't follow a single
| prefix convention (e.g., `personality` vs `student-assessments`).
|
| Assessment API sub-routes are grouped under /assessment-api.
|
*/

$router->get('/student-assessments', [StudentAssessmentController::class, 'index']);
$router->get('/student-assessments-v2', [StudentAssessmentController::class, 'v2Index']);
$router->get('/personality', [StudentAssessmentController::class, 'personality']);
$router->get('/interest', [StudentAssessmentController::class, 'interest']);
$router->get('/aptitude', [StudentAssessmentController::class, 'aptitude']);
$router->get('/values', [StudentAssessmentController::class, 'values']);
$router->get('/assessment-progress', [StudentAssessmentController::class, 'progress']);
$router->get('/assessment-result', [StudentAssessmentController::class, 'viewResult']);
$router->get('/assessment-detailed-answers', [StudentAssessmentController::class, 'detailedAnswers']);
$router->get('/assessment-v2-result', [StudentAssessmentController::class, 'v2Result']);

$router->group('/assessment-api', function ($router) {
    $router->post('/start', [StudentAssessmentController::class, 'apiStart']);
    $router->get('/question', [StudentAssessmentController::class, 'apiQuestion']);
    $router->post('/save-answer', [StudentAssessmentController::class, 'apiSaveAnswer']);
    $router->post('/finish', [StudentAssessmentController::class, 'apiFinish']);
});

$router->group('/v2', function ($router) {
    $router->post('/assessment-api-start', [StudentAssessmentController::class, 'v2ApiStart']);
    $router->get('/assessment-api-question', [StudentAssessmentController::class, 'v2ApiQuestion']);
    $router->post('/assessment-api-save', [StudentAssessmentController::class, 'v2ApiSave']);
    $router->post('/assessment-api-finish', [StudentAssessmentController::class, 'v2ApiFinish']);
});

/*
|--------------------------------------------------------------------------
| CAREER ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/careers', [CareerController::class, 'index']);
$router->get('/career-detail', [CareerController::class, 'show']);

/*
|--------------------------------------------------------------------------
| RECOMMENDATION ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/recommendation', [RecommendationController::class, 'index']);
$router->get('/career-recommendation', [CareerRecommendationController::class, 'index']);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
|
| All admin routes share the /admin prefix and are grouped for clarity.
|
*/

$router->match(['GET', 'POST'], '/admin-login', [AdminController::class, 'login']);
$router->get('/admin-logout', [AdminController::class, 'logout']);

$router->group('/admin', function ($router) {
    $router->get('/dashboard', [AdminController::class, 'dashboard']);

    $router->get('/users', [UserController::class, 'index']);
    $router->get('/users-view', [UserController::class, 'show']);

    $router->group('/settings', function ($router) {
        $router->get('/student-permissions', [StudentFeaturePermissionController::class, 'index']);
        $router->get('/student-permissions-manage', [StudentFeaturePermissionController::class, 'manage']);
        $router->post('/student-permissions-save', [StudentFeaturePermissionController::class, 'save']);
    });

    $router->group('/assessments', function ($router) {
        $router->get('', [AdminAssessmentController::class, 'index']);
        $router->get('/view', [AdminAssessmentController::class, 'show']);
        $router->get('/edit', [AdminAssessmentController::class, 'edit']);
        $router->post('/update', [AdminAssessmentController::class, 'update']);
        $router->post('/toggle-status', [AdminAssessmentController::class, 'toggleStatus']);
        $router->post('/duplicate', [AdminAssessmentController::class, 'duplicate']);
    });

    $router->group('/careers', function ($router) {
        $router->get('', [AdminCareerController::class, 'index']);
        $router->get('/view', [AdminCareerController::class, 'show']);
        $router->get('/create', [AdminCareerController::class, 'create']);
        $router->post('/store', [AdminCareerController::class, 'store']);
        $router->get('/edit', [AdminCareerController::class, 'edit']);
        $router->post('/update', [AdminCareerController::class, 'update']);
        $router->post('/delete', [AdminCareerController::class, 'delete']);
    });

    $router->group('/questions', function ($router) {
        $router->get('', [AdminQuestionController::class, 'index']);
        $router->get('/view', [AdminQuestionController::class, 'show']);
        $router->get('/create', [AdminQuestionController::class, 'create']);
        $router->post('/store', [AdminQuestionController::class, 'store']);
        $router->get('/edit', [AdminQuestionController::class, 'edit']);
        $router->post('/update', [AdminQuestionController::class, 'update']);
        $router->post('/delete', [AdminQuestionController::class, 'delete']);
        $router->post('/duplicate', [AdminQuestionController::class, 'duplicate']);
        $router->post('/bulk-delete', [AdminQuestionController::class, 'bulkDelete']);
    });

    $router->get('/reports', [AdminReportsController::class, 'index']);

    $router->get('/notifications', [AdminNotificationController::class, 'index']);
    $router->get('/notifications-api-unread-count', [AdminNotificationController::class, 'apiUnreadCount']);
    $router->post('/notifications-api-mark-read', [AdminNotificationController::class, 'apiMarkAsRead']);
    $router->post('/notifications-api-mark-all-read', [AdminNotificationController::class, 'apiMarkAllAsRead']);
    $router->post('/notifications-api-delete', [AdminNotificationController::class, 'apiDelete']);

    $router->get('/role-permissions', [RolesAndPermissionsController::class, 'index']);
    $router->post('/role-permissions-save', [RolesAndPermissionsController::class, 'save']);
});

return $router;
