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

$router = new \App\Routing\Router;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

$router->get('/home', [HomeController::class, 'index'])->name('home');
$router->get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
$router->get('/about', [HomeController::class, 'about'])->name('about');
$router->get('/contact', [HomeController::class, 'contact'])->name('contact');
$router->get('/contact/address', [HomeController::class, 'contactAddress'])->name('contact-address');
$router->get('/contact/phone', [HomeController::class, 'contactPhone'])->name('contact-phone');
$router->match(['GET', 'POST'], '/contact/email', [HomeController::class, 'contactEmail'])->name('contact-email');
$router->get('/careers', [CareerController::class, 'index'])->name('careers');
$router->get('/career-detail', [CareerController::class, 'show'])->name('career-detail');
$router->get('/assessments', [PublicAssessmentController::class, 'index'])->name('assessments');

/*
|--------------------------------------------------------------------------
| GUEST ASSESSMENT ROUTES
|--------------------------------------------------------------------------
*/

$router->group('/guest', function ($router) {
    $router->post('/api-start', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'apiStart']);
    $router->get('/api-question', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'apiQuestion']);
    $router->post('/api-save', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'apiSave']);
    $router->post('/api-finish', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'apiFinish']);
    $router->post('/api-exit', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'apiExit']);
    $router->get('/result', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'guestResult']);
    $router->get('/question', [\App\Modules\Public\Presentation\Controllers\AssessmentController::class, 'question']);
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

$router->match(['GET', 'POST'], '/login', [AuthController::class, 'login'])->name('login');
$router->match(['GET', 'POST'], '/register', [AuthController::class, 'register'])->name('register');
$router->match(['GET', 'POST'], '/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
$router->match(['GET', 'POST'], '/verify-reset-code', [AuthController::class, 'verifyResetCode'])->name('verify-reset-code');
$router->match(['GET', 'POST'], '/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

$router->get('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend-verification');
$router->get('/google-login', [AuthController::class, 'googleLogin'])->name('google-login');
$router->get('/google-callback', [AuthController::class, 'googleCallback'])->name('google-callback');
$router->get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED STUDENT ROUTES
|--------------------------------------------------------------------------
*/

$router->middleware(['auth', 'student'], function ($router) {

    $router->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    $router->get('/profile', [ProfileController::class, 'index'])->name('profile');
    $router->get('/edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    $router->post('/update-profile', [ProfileController::class, 'update'])->name('update-profile');
    $router->post('/update-profile-image', [ProfileController::class, 'updateProfileImage'])->name('update-profile-image');
    $router->get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    $router->get('/student-change-password', [ProfileController::class, 'studentChangePassword'])->name('student-change-password');
    $router->post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    $router->get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
    $router->get('/notifications/api-list', [ProfileController::class, 'apiNotifications']);
    $router->post('/notifications/api-mark-read', [ProfileController::class, 'apiMarkAsRead']);
    $router->post('/notifications/api-mark-all-read', [ProfileController::class, 'apiMarkAllAsRead']);
    $router->get('/notifications/api-unread-count', [ProfileController::class, 'apiUnreadCount']);
    $router->post('/notifications/api-delete', [ProfileController::class, 'apiDelete']);

    $router->get('/student-assessments', [StudentAssessmentController::class, 'index'])->name('student-assessments');
    $router->get('/student-assessments-v2', [StudentAssessmentController::class, 'v2Index'])->name('student-assessments-v2');
    $router->get('/assessment/completed', [StudentAssessmentController::class, 'v2CompletionPage'])->name('assessment-completed');
    $router->get('/personality', [StudentAssessmentController::class, 'personality'])->name('personality');
    $router->get('/interest', [StudentAssessmentController::class, 'interest'])->name('interest');
    $router->get('/aptitude', [StudentAssessmentController::class, 'aptitude'])->name('aptitude');
    $router->get('/values', [StudentAssessmentController::class, 'values'])->name('values');
    $router->get('/assessment-progress', [StudentAssessmentController::class, 'progress'])->name('assessment-progress');
    $router->get('/assessment-result', [StudentAssessmentController::class, 'viewResult'])->name('assessment-result');
    $router->get('/assessment-detailed-answers', [StudentAssessmentController::class, 'detailedAnswers'])->name('assessment-detailed-answers');
    $router->get('/assessment-v2-result', [StudentAssessmentController::class, 'v2Result'])->name('assessment-v2-result');

    $router->group('/assessment-api', function ($router) {
        $router->post('/start', [\App\Modules\Assessment\Application\Engine\AssessmentApiController::class, 'apiStart']);
        $router->get('/question', [\App\Modules\Assessment\Application\Engine\AssessmentApiController::class, 'apiQuestion']);
        $router->post('/save', [\App\Modules\Assessment\Application\Engine\AssessmentApiController::class, 'apiSave']);
        $router->post('/finish', [\App\Modules\Assessment\Application\Engine\AssessmentApiController::class, 'apiFinish']);
        $router->post('/exit', [\App\Modules\Assessment\Application\Engine\AssessmentApiController::class, 'apiExit']);
    });

    $router->group('/v2', function ($router) {
        $router->post('/assessment-api-start', [StudentAssessmentController::class, 'v2ApiStart']);
        $router->get('/assessment-api-question', [StudentAssessmentController::class, 'v2ApiQuestion']);
        $router->post('/assessment-api-save', [StudentAssessmentController::class, 'v2ApiSave']);
        $router->post('/assessment-api-finish', [StudentAssessmentController::class, 'v2ApiFinish']);
    });

    $router->get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation');
    $router->get('/career-recommendation', [CareerRecommendationController::class, 'index'])->name('career-recommendation');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

$router->match(['GET', 'POST'], '/admin-login', [AdminController::class, 'login'])->name('admin-login');
$router->get('/admin-logout', [AdminController::class, 'logout'])->name('admin-logout');

$router->group('/admin', function ($router) {

    $router->get('/dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');

    $router->group('/users', function ($router) {
        $router->get('', [UserController::class, 'index'], ['can:user.view'])->name('admin.users');
        $router->get('/view', [UserController::class, 'show'], ['can:user.view'])->name('admin.users.view');
    });

    $router->group('/settings', function ($router) {
        $router->get('/student-permissions', [StudentFeaturePermissionController::class, 'index'], ['can:permission.manage'])->name('admin.settings.student-permissions');
        $router->get('/student-permissions-manage', [StudentFeaturePermissionController::class, 'manage'], ['can:permission.manage'])->name('admin.settings.student-permissions-manage');
        $router->post('/student-permissions-save', [StudentFeaturePermissionController::class, 'save'], ['can:permission.manage'])->name('admin.settings.student-permissions-save');
    });

    $router->group('/assessments', function ($router) {
        $router->get('', [AdminAssessmentController::class, 'index'], ['can:assessment.view'])->name('admin.assessments');
        $router->get('/view', [AdminAssessmentController::class, 'show'], ['can:assessment.view'])->name('admin.assessments.view');
        $router->get('/edit', [AdminAssessmentController::class, 'edit'], ['can:assessment.update'])->name('admin.assessments.edit');
        $router->post('/update', [AdminAssessmentController::class, 'update'], ['can:assessment.update'])->name('admin.assessments.update');
        $router->post('/toggle-status', [AdminAssessmentController::class, 'toggleStatus'], ['can:assessment.update'])->name('admin.assessments.toggle-status');
        $router->post('/duplicate', [AdminAssessmentController::class, 'duplicate'], ['can:assessment.create'])->name('admin.assessments.duplicate');
    });

    $router->group('/careers', function ($router) {
        $router->get('', [AdminCareerController::class, 'index'], ['can:career.view'])->name('admin.careers');
        $router->get('/view', [AdminCareerController::class, 'show'], ['can:career.view'])->name('admin.careers.view');
        $router->get('/create', [AdminCareerController::class, 'create'], ['can:career.create'])->name('admin.careers.create');
        $router->post('/store', [AdminCareerController::class, 'store'], ['can:career.create'])->name('admin.careers.store');
        $router->get('/edit', [AdminCareerController::class, 'edit'], ['can:career.update'])->name('admin.careers.edit');
        $router->post('/update', [AdminCareerController::class, 'update'], ['can:career.update'])->name('admin.careers.update');
        $router->post('/delete', [AdminCareerController::class, 'delete'], ['can:career.delete'])->name('admin.careers.delete');
    });

    $router->group('/questions', function ($router) {
        $router->get('', [AdminQuestionController::class, 'index'], ['can:question.view'])->name('admin.questions');
        $router->get('/view', [AdminQuestionController::class, 'show'], ['can:question.view'])->name('admin.questions.view');
        $router->get('/create', [AdminQuestionController::class, 'create'], ['can:question.create'])->name('admin.questions.create');
        $router->post('/store', [AdminQuestionController::class, 'store'], ['can:question.create'])->name('admin.questions.store');
        $router->get('/edit', [AdminQuestionController::class, 'edit'], ['can:question.update'])->name('admin.questions.edit');
        $router->post('/update', [AdminQuestionController::class, 'update'], ['can:question.update'])->name('admin.questions.update');
        $router->post('/delete', [AdminQuestionController::class, 'delete'], ['can:question.delete'])->name('admin.questions.delete');
        $router->post('/duplicate', [AdminQuestionController::class, 'duplicate'], ['can:question.create'])->name('admin.questions.duplicate');
        $router->post('/bulk-delete', [AdminQuestionController::class, 'bulkDelete'], ['can:question.delete'])->name('admin.questions.bulk-delete');
    });

    $router->match(['GET', 'POST'], '/profile', [AdminController::class, 'profile'])->name('admin.profile');

    $router->group('/reports', function ($router) {
        $router->get('', [AdminReportsController::class, 'index'], ['can:report.view'])->name('admin.reports');
    });

    $router->group('/notifications', function ($router) {
        $router->get('', [AdminNotificationController::class, 'index'], ['can:notification.view'])->name('admin.notifications');
        $router->get('/api-unread-count', [AdminNotificationController::class, 'apiUnreadCount'], ['can:notification.view']);
        $router->get('/api-list', [AdminNotificationController::class, 'apiList'], ['can:notification.view']);
        $router->post('/api-mark-read', [AdminNotificationController::class, 'apiMarkAsRead'], ['can:notification.view']);
        $router->post('/api-mark-all-read', [AdminNotificationController::class, 'apiMarkAllAsRead'], ['can:notification.view']);
        $router->post('/api-delete', [AdminNotificationController::class, 'apiDelete'], ['can:notification.view']);
    });

    $router->group('/role-permissions', function ($router) {
        $router->get('', [RolesAndPermissionsController::class, 'index'], ['can:permission.manage'])->name('admin.role-permissions');
        $router->post('/save', [RolesAndPermissionsController::class, 'save'], ['can:permission.manage'])->name('admin.role-permissions.save');
    });

}, ['admin']);

return $router;
