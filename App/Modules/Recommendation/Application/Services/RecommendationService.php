<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Application\Services;

use App\Config\Database;
use App\Modules\Assessment\Infrastructure\Persistence\StudentAssessmentRepository;
use App\Modules\Profile\Infrastructure\Repositories\ProfileRepository;
use App\Modules\Recommendation\Infrastructure\Persistence\RecommendationRepository;
use App\Modules\Recommendation\Domain\Entities\CareerRecommendation;

class RecommendationService
{
    private StudentAssessmentRepository $studentAssessmentRepo;
    private ProfileRepository $profileRepo;
    private RecommendationRepository $recommendationRepo;

    /**
     * Static career catalog used for matching. In a production app this would live in the DB.
     */
    private array $careers = [];

    public function __construct()
    {
        $this->studentAssessmentRepo = new StudentAssessmentRepository();
        $this->profileRepo = new ProfileRepository(Database::getConnection());
        $this->recommendationRepo = new RecommendationRepository();

        $this->initCareers();
    }

    private function initCareers(): void
    {
        $this->careers = [
            [
                'id' => 'career_software_dev',
                'name' => 'Software Developer',
                'description' => 'Designs, develops and maintains software applications.',
                'skill_vector' => [
                    'personality' => 0.7,
                    'interest' => 0.8,
                    'aptitude' => 0.9,
                    'values' => 0.6,
                ],
                'required_min_education' => 'Bachelors',
                'recommended_majors' => ['Computer Science', 'Software Engineering'],
                'resources' => ['https://www.freecodecamp.org', 'https://www.coursera.org'],
            ],
            [
                'id' => 'career_data_scientist',
                'name' => 'Data Scientist',
                'description' => 'Analyzes data to derive insights and build models.',
                'skill_vector' => [
                    'personality' => 0.6,
                    'interest' => 0.7,
                    'aptitude' => 0.95,
                    'values' => 0.5,
                ],
                'required_min_education' => 'Bachelors',
                'recommended_majors' => ['Data Science', 'Statistics', 'Computer Science'],
                'resources' => ['https://www.kaggle.com', 'https://www.coursera.org'],
            ],
            [
                'id' => 'career_graphic_designer',
                'name' => 'Graphic Designer',
                'description' => 'Creates visual concepts to communicate ideas.',
                'skill_vector' => [
                    'personality' => 0.6,
                    'interest' => 0.9,
                    'aptitude' => 0.6,
                    'values' => 0.7,
                ],
                'required_min_education' => 'Diploma',
                'recommended_majors' => ['Graphic Design', 'Visual Arts'],
                'resources' => ['https://www.behance.net', 'https://www.udemy.com'],
            ],
            [
                'id' => 'career_teacher',
                'name' => 'Teacher',
                'description' => 'Educates students and facilitates learning.',
                'skill_vector' => [
                    'personality' => 0.8,
                    'interest' => 0.7,
                    'aptitude' => 0.5,
                    'values' => 0.9,
                ],
                'required_min_education' => 'Bachelors',
                'recommended_majors' => ['Education', 'Subject Specialization'],
                'resources' => ['https://www.edx.org', 'https://www.coursera.org'],
            ],
        ];
    }

    public function generateForUser(int $userId): ?CareerRecommendation
    {
        // Get progress/scores
        $progress = $this->studentAssessmentRepo->getProgressSummary($userId);

        // Build user skill vector (normalize scores)
        $maxScores = [
            'personality' => 100,
            'interest' => 100,
            'aptitude' => 100,
            'values' => 100,
        ];

        $userVector = [];
        foreach ($maxScores as $key => $max) {
            $score = isset($progress[$key]['score']) ? (float)$progress[$key]['score'] : 0.0;
            $userVector[$key] = $max > 0 ? min(1.0, $score / $max) : 0.0;
        }

        // Fetch profile for education level
        $profile = $this->profileRepo->findByUserId($userId);
        $educationLabel = $profile['education_level'] ?? '';
        $educationLevel = $this->educationLevelToInt($educationLabel);

        // Evaluate careers
        $results = [];
        foreach ($this->careers as $career) {
            $careerVector = $career['skill_vector'];
            $similarity = $this->cosineSimilarity($userVector, $careerVector);
            $educationMultiplier = $this->educationMultiplier($educationLevel, $career['required_min_education']);
            $finalScore = $similarity * $educationMultiplier;

            $results[] = [
                'career' => $career,
                'base_similarity' => $similarity,
                'education_multiplier' => $educationMultiplier,
                'final_score' => $finalScore,
            ];
        }

        usort($results, function ($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });

        $top = $results[0] ?? null;

        if (!$top) {
            return null;
        }

        $matchPercent = round(($top['final_score'] * 100), 1);

        // Build report
        $career = $top['career'];
        $report = [
            'career_id' => $career['id'],
            'career_name' => $career['name'],
            'description' => $career['description'],
            'match_percent' => $matchPercent,
            'skills' => $this->topSkills($career['skill_vector']),
            'recommended_majors' => $career['recommended_majors'],
            'resources' => $career['resources'],
            'education_level' => $educationLabel,
            'generated_at' => date('Y-m-d H:i:s'),
        ];

        // Attempt to persist
        $this->recommendationRepo->saveRecommendation($userId, $career['id'], $career['name'], $matchPercent, $report);

        return new CareerRecommendation(array_merge($report, ['user_id' => $userId]));
    }

    private function topSkills(array $vector): array
    {
        arsort($vector);
        return array_keys(array_filter($vector, function ($v) {
            return $v >= 0.6;
        }));
    }

    private function cosineSimilarity(array $v1, array $v2): float
    {
        $dot = 0.0;
        $norm1 = 0.0;
        $norm2 = 0.0;

        $keys = array_unique(array_merge(array_keys($v1), array_keys($v2)));
        foreach ($keys as $k) {
            $a = $v1[$k] ?? 0.0;
            $b = $v2[$k] ?? 0.0;
            $dot += $a * $b;
            $norm1 += $a * $a;
            $norm2 += $b * $b;
        }

        if ($norm1 == 0.0 || $norm2 == 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($norm1) * sqrt($norm2));
    }

    private function educationLevelToInt(?string $label): int
    {
        if (!$label) {
            return 0;
        }

        $map = [
            'high school' => 1,
            'secondary' => 1,
            'hs' => 1,
            'diploma' => 2,
            'associate' => 2,
            'bachelor' => 3,
            'bachelors' => 3,
            'bachelor\'s' => 3,
            'bachelor\'s degree' => 3,
            'master' => 4,
            'masters' => 4,
            'master\'s' => 4,
            'phd' => 5,
            'doctorate' => 5,
        ];

        $labelLower = strtolower(trim($label));

        return $map[$labelLower] ?? 0;
    }

    private function educationMultiplier(int $userLevel, string $requiredLabel): float
    {
        $required = $this->educationLevelToInt($requiredLabel);
        $delta = $userLevel - $required;

        if ($delta >= 0) {
            // small boost for meeting or exceeding requirement
            return 1.0 + min(0.1, $delta * 0.02);
        }

        if ($delta === -1) {
            return 0.8; // slightly reduce if only one level below
        }

        return 0.5; // de-prioritize when substantially below
    }
}

