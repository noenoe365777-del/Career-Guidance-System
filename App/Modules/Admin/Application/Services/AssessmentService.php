<?php

declare(strict_types=1);

namespace App\Modules\Admin\Application\Services;

use App\Modules\Admin\Infrastructure\Persistence\AssessmentRepository;

class AssessmentService
{
    private AssessmentRepository $assessmentRepository;

    public function __construct(?AssessmentRepository $assessmentRepository = null)
    {
        $this->assessmentRepository = $assessmentRepository ?? new AssessmentRepository();
    }

    public function getAllAssessments(?string $search = null): array
    {
        return $this->assessmentRepository->getAllAssessments($search);
    }

    public function getFilteredAssessments(?string $search = null, ?string $status = null, ?string $sort = null): array
    {
        return $this->assessmentRepository->getFilteredAssessments($search, $status, $sort);
    }

    public function getAssessmentById(int $id): ?array
    {
        return $this->assessmentRepository->getAssessmentById($id);
    }

    public function getTotalAssessments(): int
    {
        return $this->assessmentRepository->getTotalAssessments();
    }

    public function getActiveAssessmentsCount(): int
    {
        return $this->assessmentRepository->getActiveAssessmentsCount();
    }

    public function getTotalQuestionsCount(): int
    {
        return $this->assessmentRepository->getTotalQuestionsCount();
    }

    public function getStudentsCompletedCount(): int
    {
        return $this->assessmentRepository->getStudentsCompletedCount();
    }

    public function getStudentsCompletedAllCount(): int
    {
        return $this->assessmentRepository->getStudentsCompletedAllCount();
    }

    public function getAverageCompletionRate(): float
    {
        return $this->assessmentRepository->getAverageCompletionRate();
    }

    public function getAssessmentsWithCompletion(?string $search = null): array
    {
        $assessments = $this->assessmentRepository->getAllAssessments($search);
        $completions = $this->assessmentRepository->getStudentCompletionsByAssessment();

        return array_map(function ($a) use ($completions) {
            $id = (int)($a['assessment_id'] ?? 0);
            $a['students_completed'] = $completions[$id] ?? 0;
            return $a;
        }, $assessments);
    }

    public function getStudentCompletionsByAssessment(): array
    {
        return $this->assessmentRepository->getStudentCompletionsByAssessment();
    }

    public function getPerAssessmentCompletionData(): array
    {
        return $this->assessmentRepository->getPerAssessmentCompletionData();
    }

    public function getDailyCompletionTrend(int $days = 7): array
    {
        return $this->assessmentRepository->getDailyCompletionTrend($days);
    }

    public function getRecentActivity(int $limit = 5): array
    {
        return $this->assessmentRepository->getRecentActivity($limit);
    }

    public function getRecentCompletedAssessments(int $limit = 5): array
    {
        return $this->assessmentRepository->getRecentCompletedAssessments($limit);
    }

    public function getAverageScoresByAssessment(): array
    {
        return $this->assessmentRepository->getAverageScoresByAssessment();
    }

    public function getAverageScore(): float
    {
        return $this->assessmentRepository->getAverageScore();
    }

    public function getTotalStudentCount(): int
    {
        return $this->assessmentRepository->getTotalStudentCount();
    }

    public function duplicateAssessment(int $id, string $newTitle): ?array
    {
        return $this->assessmentRepository->duplicateAssessment($id, $newTitle);
    }

    public function toggleAssessmentStatus(int $id): ?array
    {
        $assessment = $this->assessmentRepository->getAssessmentById($id);
        if (!$assessment) {
            return null;
        }

        $newStatus = strtolower((string)($assessment['status'] ?? 'active')) === 'active' ? 'inactive' : 'active';
        $this->assessmentRepository->updateAssessmentStatus($id, $newStatus);

        return $this->assessmentRepository->getAssessmentById($id);
    }

    public function updateAssessment(int $id, string $title, string $description): ?array
    {
        $assessment = $this->assessmentRepository->getAssessmentById($id);
        if (!$assessment) {
            return null;
        }

        $this->assessmentRepository->updateAssessment($id, $title, $description);
        return $this->assessmentRepository->getAssessmentById($id);
    }
}
