<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Application\Services;

use App\Modules\Recommendation\Domain\Entities\CareerRecommendation;
use App\Modules\Recommendation\Infrastructure\Persistence\RecommendationRepository;
use App\Shared\NotificationHelper;
use PDO;

class RecommendationService
{
    private RecommendationRepository $recommendationRepo;

    public function __construct()
    {
        $this->recommendationRepo = new RecommendationRepository();
    }

    public function getExistingForUser(int $userId): array
    {
        $existing = $this->recommendationRepo->getExistingRecommendations($userId);
        if (empty($existing)) {
            return [];
        }

        $scores = $this->recommendationRepo->getStudentScores($userId);

        return array_map(function (array $row) use ($userId, $scores): CareerRecommendation {
            $career = [
                'personality_type' => $row['personality_type'] ?? '',
                'interest_type' => $row['interest_type'] ?? '',
                'aptitude_type' => $row['aptitude_type'] ?? '',
                'values_type' => $row['values_type'] ?? '',
            ];
            $matchDetails = $scores ? $this->getMatchDetails($scores, $career) : [];

            return new CareerRecommendation([
                'user_id' => $userId,
                'career_id' => (int)$row['career_id'],
                'career_name' => $row['career_name'],
                'career_icon' => $row['career_icon'] ?? '',
                'match_percent' => (float)$row['match_score'],
                'description' => $row['description'] ?? '',
                'required_skills' => $row['required_skills'] ?? '',
                'average_salary' => $row['average_salary'] ?? '',
                'growth_rate' => $row['growth_rate'] ?? '',
                'education_required' => $row['education_required'] ?? '',
                'reason' => $row['recommendation_reason'] ?? '',
                'matched_dimensions' => $matchDetails,
            ]);
        }, $existing);
    }

    public function generateForUser(int $userId): array
    {
        $scores = $this->recommendationRepo->getStudentScores($userId);
        if (!$scores) {
            return [];
        }

        $this->repairLegacyLabels($userId, $scores);
        $scores = $this->recommendationRepo->getStudentScores($userId);
        if (!$scores) {
            return [];
        }

        $educationLevel = $this->recommendationRepo->getEducationLevel($userId);
        $allCareers = $this->recommendationRepo->getAllCareers();
        if (empty($allCareers)) {
            return [];
        }

        $eligibleCareers = $this->filterByEducation($allCareers, $educationLevel);
        if (empty($eligibleCareers)) {
            $eligibleCareers = $allCareers;
        }

        $results = [];
        foreach ($eligibleCareers as $career) {
            $score = $this->calculateWeightedMatch($scores, $career);
            $matchDetails = $this->getMatchDetails($scores, $career);
            $results[] = [
                'career' => $career,
                'total_score' => $score,
                'match_details' => $matchDetails,
            ];
        }

        usort($results, fn(array $a, array $b): int => $b['total_score'] <=> $a['total_score']);
        $top5 = array_slice($results, 0, 5);

        $this->recommendationRepo->deleteUserRecommendations($userId);

        $recommendations = [];
        foreach ($top5 as $r) {
            $career = $r['career'];
            $reason = $this->buildReason($r['match_details']);

            $this->recommendationRepo->saveRecommendation(
                $userId,
                $career['career_id'],
                $r['total_score'],
                $reason
            );

            $recommendations[] = new CareerRecommendation([
                'user_id' => $userId,
                'career_id' => $career['career_id'],
                'career_name' => $career['career_name'],
                'career_icon' => $career['career_icon'] ?? '',
                'match_percent' => $r['total_score'],
                'description' => $career['description'],
                'required_skills' => $career['required_skills'],
                'average_salary' => $career['average_salary'],
                'growth_rate' => $career['growth_rate'],
                'education_required' => $career['education_required'],
                'reason' => $reason,
                'matched_dimensions' => $r['match_details'],
            ]);
        }

        $topCareerName = !empty($recommendations) ? $recommendations[0]->careerName : 'a career path';
        $userData = $this->getUserData($userId);
        $studentName = $userData['name'] ?? "User #{$userId}";
        NotificationHelper::recommendationGenerated($studentName, $topCareerName, $userId);

        return $recommendations;
    }

    private function getUserData(int $userId): array
    {
        try {
            $pdo = \App\Config\Database::getConnection();
            $stmt = $pdo->prepare("SELECT username, full_name, CONCAT(first_name, ' ', last_name) AS name FROM users WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException) {
            return [];
        }
    }

    private function repairLegacyLabels(int $userId, array $scores): void
    {
        $oldGeneric = ['Moderate', 'High', 'Low'];
        $needsRepair = false;
        foreach (['personality_type', 'interest_type', 'aptitude_type', 'values_type'] as $col) {
            if (in_array(trim($scores[$col] ?? ''), $oldGeneric, true)) {
                $needsRepair = true;
                break;
            }
        }
        if (!$needsRepair) {
            return;
        }

        $typeMap = [
            'personality_type' => fn($p) => $p >= 80 ? 'Extrovert' : ($p >= 60 ? 'Ambivert' : 'Introvert'),
            'interest_type' => fn($p) => $p >= 80 ? 'Creative / Investigative' : ($p >= 60 ? 'Balanced' : 'Practical'),
            'aptitude_type' => fn($p) => $p >= 70 ? 'Advanced' : ($p >= 50 ? 'Competent' : 'Beginner'),
            'values_type' => fn($p) => $p >= 75 ? 'Defined' : ($p >= 50 ? 'Developing' : 'Undefined'),
        ];

        $scoreMap = [
            'personality_type' => 'personality_score',
            'interest_type' => 'interest_score',
            'aptitude_type' => 'aptitude_score',
            'values_type' => 'values_score',
        ];

        $conn = $this->recommendationRepo->getConnection();
        foreach ($typeMap as $typeCol => $fn) {
            $scoreCol = $scoreMap[$typeCol];
            $score = (int)($scores[$scoreCol] ?? 0);
            $newType = $fn($score);
            $stmt = $conn->prepare("UPDATE student_assessment_scores SET `$typeCol` = :tp WHERE student_id = :sid");
            $stmt->execute([':tp' => $newType, ':sid' => $userId]);
        }
    }
    private function filterByEducation(array $careers, ?string $educationLevel): array
    {
        if ($educationLevel === null || $educationLevel === '') {
            return $careers;
        }

        $studentLevel = $this->educationLabelToLevel($educationLevel);
        if ($studentLevel === 0) {
            return $careers;
        }

        return array_values(array_filter($careers, function (array $career) use ($studentLevel): bool {
            $careerLevel = $this->educationLabelToLevel($career['education_required'] ?? '');
            return $careerLevel > 0 && $careerLevel <= $studentLevel;
        }));
    }

    private function educationLabelToLevel(string $label): int
    {
        return match (strtolower(trim($label))) {
            'high school', 'secondary' => 1,
            'undergraduate', 'bachelor', 'bachelors', "bachelor's", "bachelor's degree" => 2,
            'graduate', 'master', 'masters', "master's", "master's degree", 'phd', 'doctorate' => 3,
            default => 0,
        };
    }

    private function calculateWeightedMatch(array $scores, array $career): float
    {
        $weights = [
            'interest_type' => 30,
            'personality_type' => 25,
            'aptitude_type' => 25,
            'values_type' => 20,
        ];

        $total = 0;
        foreach ($weights as $dim => $weight) {
            $studentType = trim($scores[$dim] ?? '');
            $careerType = trim($career[$dim] ?? '');
            if ($studentType !== '' && $careerType !== '' && strcasecmp($studentType, $careerType) === 0) {
                $total += $weight;
            }
        }

        return round($total, 2);
    }

    private function getMatchDetails(array $scores, array $career): array
    {
        $dims = [
            'interest_type' => ['label' => 'Interest', 'weight' => 30],
            'personality_type' => ['label' => 'Personality', 'weight' => 25],
            'aptitude_type' => ['label' => 'Aptitude', 'weight' => 25],
            'values_type' => ['label' => 'Career Values', 'weight' => 20],
        ];

        $details = [];
        foreach ($dims as $dim => $info) {
            $studentType = trim($scores[$dim] ?? '');
            $careerType = trim($career[$dim] ?? '');
            $matched = $studentType !== '' && $careerType !== '' && strcasecmp($studentType, $careerType) === 0;
            $details[] = [
                'dimension' => $info['label'],
                'matched' => $matched,
                'student_type' => $studentType,
                'career_type' => $careerType,
                'weight' => $info['weight'],
            ];
        }

        return $details;
    }

    private function buildReason(array $matchDetails): string
    {
        $matched = array_filter($matchDetails, fn(array $d): bool => $d['matched']);
        if (empty($matched)) {
            return 'General career match based on assessment results.';
        }

        $parts = array_map(fn(array $d): string => $d['dimension'] . ' (' . $d['student_type'] . ')', $matched);
        return 'Matches ' . implode(', ', $parts) . '.';
    }
}
