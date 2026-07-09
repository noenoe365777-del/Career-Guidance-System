<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Application\Services;

use App\Modules\Recommendation\Domain\Entities\CareerRecommendation;
use App\Modules\Recommendation\Infrastructure\Persistence\RecommendationRepository;

class RecommendationService
{
    private RecommendationRepository $recommendationRepo;

    public function __construct()
    {
        $this->recommendationRepo = new RecommendationRepository();
    }

    public function generateForUser(int $userId): array
    {
        $scores = $this->recommendationRepo->getStudentScores($userId);
        if (!$scores) {
            return [];
        }

        $educationLevel = $this->recommendationRepo->getEducationLevel($userId);
        $careers = $this->recommendationRepo->getAllCareers();

        if (empty($careers)) {
            return [];
        }

        $results = [];
        foreach ($careers as $career) {
            $typeScore = $this->calculateTypeMatch($scores, $career);
            $scoreBonus = $this->calculateScoreBonus($scores);
            $educationScore = $this->calculateEducationMatch($educationLevel, $career['education_required']);
            $total = $typeScore + $scoreBonus + $educationScore;

            $results[] = [
                'career' => $career,
                'type_score' => $typeScore,
                'score_bonus' => $scoreBonus,
                'education_score' => $educationScore,
                'total_score' => round($total, 2),
            ];
        }

        usort($results, fn(array $a, array $b): int => $b['total_score'] <=> $a['total_score']);

        $top5 = array_slice($results, 0, 5);

        $this->recommendationRepo->deleteUserRecommendations($userId);

        $recommendations = [];
        foreach ($top5 as $r) {
            $career = $r['career'];
            $reason = $this->buildReason($scores, $career, $educationLevel);
            $this->recommendationRepo->saveRecommendation($userId, $career['career_id'], $r['total_score'], $reason);

            $recommendations[] = new CareerRecommendation([
                'user_id' => $userId,
                'career_id' => $career['career_id'],
                'career_name' => $career['career_name'],
                'match_percent' => $r['total_score'],
                'description' => $career['description'],
                'required_skills' => $career['required_skills'],
                'average_salary' => $career['average_salary'],
                'growth_rate' => $career['growth_rate'],
                'education_required' => $career['education_required'],
                'reason' => $reason,
            ]);
        }

        return $recommendations;
    }

    private function calculateTypeMatch(array $scores, array $career): int
    {
        $dimensions = ['personality_type', 'interest_type', 'aptitude_type', 'values_type'];
        $matched = 0;

        foreach ($dimensions as $dim) {
            $studentType = trim($scores[$dim] ?? '');
            $careerType = trim($career[$dim] ?? '');

            if ($studentType !== '' && $careerType !== '' && strcasecmp($studentType, $careerType) === 0) {
                $matched++;
            }
        }

        return $matched * 15;
    }

    private function calculateScoreBonus(array $scores): float
    {
        $scoreKeys = ['personality_score', 'interest_score', 'aptitude_score', 'values_score'];
        $total = 0;
        $count = 0;

        foreach ($scoreKeys as $key) {
            $val = (int)($scores[$key] ?? 0);
            $total += $val;
            $count++;
        }

        $average = $count > 0 ? $total / $count : 0;
        return round(($average / 100) * 20, 2);
    }

    private function calculateEducationMatch(?string $studentEducation, ?string $careerEducation): int
    {
        if ($studentEducation === null || $careerEducation === null || $careerEducation === '') {
            return 10;
        }

        $studentLevel = $this->educationToLevel($studentEducation);
        $careerLevel = $this->educationToLevel($careerEducation);

        if ($studentLevel === 0 || $careerLevel === 0) {
            return 10;
        }

        $delta = $studentLevel - $careerLevel;

        if ($delta === 0) {
            return 20;
        }
        if ($delta > 0) {
            return 15;
        }
        if ($delta === -1) {
            return 10;
        }

        return 5;
    }

    private function educationToLevel(string $label): int
    {
        $map = [
            'high school' => 1,
            'secondary' => 1,
            'diploma' => 2,
            'associate' => 2,
            'undergraduate' => 3,
            'bachelor' => 3,
            'bachelors' => 3,
            "bachelor's" => 3,
            "bachelor's degree" => 3,
            'graduate' => 4,
            'master' => 4,
            'masters' => 4,
            "master's" => 4,
            'phd' => 5,
            'doctorate' => 5,
        ];

        return $map[strtolower(trim($label))] ?? 0;
    }

    private function buildReason(array $scores, array $career, ?string $educationLevel): string
    {
        $matchedTypes = [];

        $dims = [
            'personality_type' => 'Personality',
            'interest_type' => 'Interest',
            'aptitude_type' => 'Aptitude',
            'values_type' => 'Values',
        ];

        foreach ($dims as $key => $label) {
            $studentType = trim($scores[$key] ?? '');
            $careerType = trim($career[$key] ?? '');
            if ($studentType !== '' && $careerType !== '' && strcasecmp($studentType, $careerType) === 0) {
                $matchedTypes[] = $label . ' (' . $studentType . ')';
            }
        }

        $parts = [];
        if (!empty($matchedTypes)) {
            $parts[] = 'Matches ' . implode(', ', $matchedTypes);
        }

        if ($educationLevel) {
            $parts[] = 'Education: ' . $educationLevel;
        }

        return !empty($parts) ? implode('. ', $parts) . '.' : 'General career match based on assessment results.';
    }
}
