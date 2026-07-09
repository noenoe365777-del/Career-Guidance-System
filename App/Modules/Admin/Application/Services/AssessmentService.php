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
