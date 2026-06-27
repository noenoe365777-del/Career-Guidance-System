@echo off
REM Pure batch script to create Career-Guidance-System DDD folder structure WITHOUT PowerShell
cd /d "%~dp0"

REM Create directories
mkdir "App\Modules\Home\Presentation\Controllers" 2>nul
mkdir "App\Modules\Home\Presentation\Views" 2>nul

mkdir "App\Modules\Auth\Domain\Entities" 2>nul
mkdir "App\Modules\Auth\Domain\Repositories" 2>nul
mkdir "App\Modules\Auth\Application\Services" 2>nul
mkdir "App\Modules\Auth\Application\DTOs" 2>nul
mkdir "App\Modules\Auth\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Auth\Infrastructure\Security" 2>nul
mkdir "App\Modules\Auth\Presentation\Controllers" 2>nul
mkdir "App\Modules\Auth\Presentation\Requests" 2>nul
mkdir "App\Modules\Auth\Presentation\Views" 2>nul

mkdir "App\Modules\Student\Domain\Entities" 2>nul
mkdir "App\Modules\Student\Domain\Repositories" 2>nul
mkdir "App\Modules\Student\Application\Services" 2>nul
mkdir "App\Modules\Student\Application\DTOs" 2>nul
mkdir "App\Modules\Student\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Student\Presentation\Controllers" 2>nul
mkdir "App\Modules\Student\Presentation\Views" 2>nul

mkdir "App\Modules\Assessment\Domain\Entities" 2>nul
mkdir "App\Modules\Assessment\Domain\Repositories" 2>nul
mkdir "App\Modules\Assessment\Application\Services" 2>nul
mkdir "App\Modules\Assessment\Application\DTOs" 2>nul
mkdir "App\Modules\Assessment\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Assessment\Presentation\Controllers" 2>nul
mkdir "App\Modules\Assessment\Presentation\Views" 2>nul

mkdir "App\Modules\Result\Domain\Entities" 2>nul
mkdir "App\Modules\Result\Domain\Repositories" 2>nul
mkdir "App\Modules\Result\Application\Services" 2>nul
mkdir "App\Modules\Result\Application\DTOs" 2>nul
mkdir "App\Modules\Result\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Result\Presentation\Controllers" 2>nul
mkdir "App\Modules\Result\Presentation\Views" 2>nul

mkdir "App\Modules\Career\Domain\Entities" 2>nul
mkdir "App\Modules\Career\Domain\Repositories" 2>nul
mkdir "App\Modules\Career\Application\Services" 2>nul
mkdir "App\Modules\Career\Application\DTOs" 2>nul
mkdir "App\Modules\Career\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Career\Presentation\Controllers" 2>nul
mkdir "App\Modules\Career\Presentation\Views" 2>nul

mkdir "App\Modules\Recommendation\Domain\Entities" 2>nul
mkdir "App\Modules\Recommendation\Domain\Repositories" 2>nul
mkdir "App\Modules\Recommendation\Application\Services" 2>nul
mkdir "App\Modules\Recommendation\Application\DTOs" 2>nul
mkdir "App\Modules\Recommendation\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Recommendation\Presentation\Controllers" 2>nul
mkdir "App\Modules\Recommendation\Presentation\Views" 2>nul

mkdir "App\Modules\Admin\Application\Services" 2>nul
mkdir "App\Modules\Admin\Infrastructure\Persistence" 2>nul
mkdir "App\Modules\Admin\Presentation\Controllers" 2>nul
mkdir "App\Modules\Admin\Presentation\Views" 2>nul

mkdir "App\Shared\Core" 2>nul
mkdir "App\Shared\Helpers" 2>nul
mkdir "App\Shared\Middleware" 2>nul
mkdir "App\Shared\Exceptions" 2>nul
mkdir "App\config" 2>nul

mkdir "Public\assets\css" 2>nul
mkdir "Public\assets\js" 2>nul
mkdir "Public\assets\images" 2>nul

mkdir "database\migrations" 2>nul
mkdir "database\seeders" 2>nul
mkdir "vendor" 2>nul

REM Create placeholder files (empty or with a single comment line)
echo // placeholder > "App\Modules\Home\Presentation\Controllers\HomeController.php"
echo // placeholder > "App\Modules\Home\Presentation\Views\home.php"
echo // placeholder > "App\Modules\Home\Presentation\Views\about.php"
echo // placeholder > "App\Modules\Home\Presentation\Views\contact.php"

echo // placeholder > "App\Modules\Auth\Domain\Entities\User.php"
echo // placeholder > "App\Modules\Auth\Domain\Repositories\UserRepositoryInterface.php"
echo // placeholder > "App\Modules\Auth\Application\Services\AuthService.php"
echo // placeholder > "App\Modules\Auth\Application\DTOs\RegisterStudentDTO.php"
echo // placeholder > "App\Modules\Auth\Application\DTOs\LoginDTO.php"
echo // placeholder > "App\Modules\Auth\Infrastructure\Persistence\UserRepository.php"
echo // placeholder > "App\Modules\Auth\Infrastructure\Security\PasswordHasher.php"
echo // placeholder > "App\Modules\Auth\Presentation\Controllers\AuthController.php"
echo // placeholder > "App\Modules\Auth\Presentation\Requests\RegisterRequest.php"
echo // placeholder > "App\Modules\Auth\Presentation\Requests\LoginRequest.php"
echo // placeholder > "App\Modules\Auth\Presentation\Views\register.php"
echo // placeholder > "App\Modules\Auth\Presentation\Views\login.php"

echo // placeholder > "App\Modules\Student\Domain\Entities\StudentProfile.php"
echo // placeholder > "App\Modules\Student\Domain\Repositories\StudentProfileRepositoryInterface.php"
echo // placeholder > "App\Modules\Student\Application\Services\StudentService.php"
echo // placeholder > "App\Modules\Student\Application\DTOs\UpdateStudentProfileDTO.php"
echo // placeholder > "App\Modules\Student\Infrastructure\Persistence\StudentProfileRepository.php"
echo // placeholder > "App\Modules\Student\Presentation\Controllers\StudentController.php"
echo // placeholder > "App\Modules\Student\Presentation\Views\dashboard.php"
echo // placeholder > "App\Modules\Student\Presentation\Views\profile.php"

echo // placeholder > "App\Modules\Assessment\Domain\Entities\Assessment.php"
echo // placeholder > "App\Modules\Assessment\Domain\Entities\Question.php"
echo // placeholder > "App\Modules\Assessment\Domain\Entities\QuestionOption.php"
echo // placeholder > "App\Modules\Assessment\Domain\Entities\StudentAssessment.php"
echo // placeholder > "App\Modules\Assessment\Domain\Entities\StudentAnswer.php"
echo // placeholder > "App\Modules\Assessment\Domain\Repositories\AssessmentRepositoryInterface.php"
echo // placeholder > "App\Modules\Assessment\Domain\Repositories\QuestionRepositoryInterface.php"
echo // placeholder > "App\Modules\Assessment\Domain\Repositories\QuestionOptionRepositoryInterface.php"
echo // placeholder > "App\Modules\Assessment\Domain\Repositories\StudentAssessmentRepositoryInterface.php"
echo // placeholder > "App\Modules\Assessment\Domain\Repositories\StudentAnswerRepositoryInterface.php"
echo // placeholder > "App\Modules\Assessment\Application\Services\AssessmentService.php"
echo // placeholder > "App\Modules\Assessment\Application\DTOs\StartAssessmentDTO.php"
echo // placeholder > "App\Modules\Assessment\Application\DTOs\SaveStudentAnswerDTO.php"
echo // placeholder > "App\Modules\Assessment\Application\DTOs\SubmitAssessmentDTO.php"
echo // placeholder > "App\Modules\Assessment\Infrastructure\Persistence\AssessmentRepository.php"
echo // placeholder > "App\Modules\Assessment\Infrastructure\Persistence\QuestionRepository.php"
echo // placeholder > "App\Modules\Assessment\Infrastructure\Persistence\QuestionOptionRepository.php"
echo // placeholder > "App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository.php"
echo // placeholder > "App\Modules\Assessment\Infrastructure\Persistence\StudentAnswerRepository.php"
echo // placeholder > "App\Modules\Assessment\Presentation\Controllers\AssessmentController.php"
echo // placeholder > "App\Modules\Assessment\Presentation\Views\assessment-list.php"
echo // placeholder > "App\Modules\Assessment\Presentation\Views\take-assessment.php"
echo // placeholder > "App\Modules\Assessment\Presentation\Views\assessment-progress.php"

echo // placeholder > "App\Modules\Result\Domain\Entities\AssessmentResult.php"
echo // placeholder > "App\Modules\Result\Domain\Repositories\ResultRepositoryInterface.php"
echo // placeholder > "App\Modules\Result\Application\Services\ResultService.php"
echo // placeholder > "App\Modules\Result\Application\DTOs\ResultFilterDTO.php"
echo // placeholder > "App\Modules\Result\Infrastructure\Persistence\ResultRepository.php"
echo // placeholder > "App\Modules\Result\Presentation\Controllers\ResultController.php"
echo // placeholder > "App\Modules\Result\Presentation\Views\results.php"
echo // placeholder > "App\Modules\Result\Presentation\Views\result-details.php"

echo // placeholder > "App\Modules\Career\Domain\Entities\Career.php"
echo // placeholder > "App\Modules\Career\Domain\Entities\CareerCategory.php"
echo // placeholder > "App\Modules\Career\Domain\Repositories\CareerRepositoryInterface.php"
echo // placeholder > "App\Modules\Career\Domain\Repositories\CareerCategoryRepositoryInterface.php"
echo // placeholder > "App\Modules\Career\Application\Services\CareerService.php"
echo // placeholder > "App\Modules\Career\Application\DTOs\CareerSearchDTO.php"
echo // placeholder > "App\Modules\Career\Infrastructure\Persistence\CareerRepository.php"
echo // placeholder > "App\Modules\Career\Infrastructure\Persistence\CareerCategoryRepository.php"
echo // placeholder > "App\Modules\Career\Presentation\Controllers\CareerController.php"
echo // placeholder > "App\Modules\Career\Presentation\Views\careers.php"
echo // placeholder > "App\Modules\Career\Presentation\Views\career-details.php"

echo // placeholder > "App\Modules\Recommendation\Domain\Entities\CareerRecommendation.php"
echo // placeholder > "App\Modules\Recommendation\Domain\Repositories\RecommendationRepositoryInterface.php"
echo // placeholder > "App\Modules\Recommendation\Application\Services\RecommendationService.php"
echo // placeholder > "App\Modules\Recommendation\Application\DTOs\RecommendationDTO.php"
echo // placeholder > "App\Modules\Recommendation\Infrastructure\Persistence\RecommendationRepository.php"
echo // placeholder > "App\Modules\Recommendation\Presentation\Controllers\RecommendationController.php"
echo // placeholder > "App\Modules\Recommendation\Presentation\Views\recommendations.php"

echo // placeholder > "App\Modules\Admin\Application\Services\AdminService.php"
echo // placeholder > "App\Modules\Admin\Infrastructure\Persistence\AdminRepository.php"
echo // placeholder > "App\Modules\Admin\Presentation\Controllers\AdminController.php"
echo // placeholder > "App\Modules\Admin\Presentation\Views\dashboard.php"
echo // placeholder > "App\Modules\Admin\Presentation\Views\students.php"
echo // placeholder > "App\Modules\Admin\Presentation\Views\assessments.php"
echo // placeholder > "App\Modules\Admin\Presentation\Views\careers.php"
echo // placeholder > "App\Modules\Admin\Presentation\Views\results.php"

echo // placeholder > "App\Shared\Core\Database.php"
echo // placeholder > "App\Shared\Core\BaseController.php"
echo // placeholder > "App\Shared\Core\BaseRepository.php"
echo // placeholder > "App\Shared\Core\Router.php"
echo // placeholder > "App\Shared\Core\View.php"

echo // placeholder > "App\Shared\Helpers\redirect.php"
echo // placeholder > "App\Shared\Helpers\auth.php"
echo // placeholder > "App\Shared\Helpers\response.php"

echo // placeholder > "App\Shared\Middleware\AuthMiddleware.php"
echo // placeholder > "App\Shared\Middleware\GuestMiddleware.php"
echo // placeholder > "App\Shared\Middleware\AdminMiddleware.php"

echo // placeholder > "App\Shared\Exceptions\NotFoundException.php"
echo // placeholder > "App\Shared\Exceptions\ValidationException.php"

echo // placeholder > "App\config\app.php"
echo // placeholder > "Public\index.php"
echo // placeholder > "Public\assets\css\.gitkeep"
echo // placeholder > "Public\assets\js\.gitkeep"
echo // placeholder > "Public\assets\images\.gitkeep"
echo // placeholder > "database\migrations\.gitkeep"
echo // placeholder > "database\seeders\.gitkeep"
echo // placeholder > "database\career_guidance.sql"
echo // placeholder > ".env"

echo Structure created.
pause
