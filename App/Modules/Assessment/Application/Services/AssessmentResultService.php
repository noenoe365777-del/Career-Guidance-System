<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Application\Services;

use App\Modules\Assessment\Infrastructure\Persistence\AssessmentRepository;
use App\Modules\Assessment\Infrastructure\Persistence\AssessmentResultTypeRepository;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;

class AssessmentResultService
{
    private AssessmentRepository $assessmentRepository;
    private StudentAssessmentRepository $studentAssessmentRepository;
    private AssessmentResultTypeRepository $resultTypeRepository;

    public function __construct()
    {
        $this->assessmentRepository = new AssessmentRepository();
        $this->studentAssessmentRepository = new StudentAssessmentRepository();
        $this->resultTypeRepository = new AssessmentResultTypeRepository();
    }

    public function getResult(int $userId, string $slug): ?array
    {
        $assessment = $this->assessmentRepository->getBySlug($slug);
        if (!$assessment) {
            return null;
        }

        $attempt = $this->studentAssessmentRepository->findForUser($userId, (int)$assessment['id']);
        if (!$attempt || $attempt['status'] !== 'completed') {
            return null;
        }

        $score = $this->studentAssessmentRepository->getAssessmentScore($userId, $slug);
        $type = $this->resultTypeRepository->findType($slug, $score);

        return [
            'assessment' => $assessment,
            'attempt' => $attempt,
            'score' => $score,
            'type_label' => $type['type_label'] ?? 'Not Available',
            'interpretation' => $type['interpretation'] ?? '',
        ];
    }

    public function determineType(string $slug, int $score): ?array
    {
        return $this->resultTypeRepository->findType($slug, $score);
    }
}
