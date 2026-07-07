<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Domain\Entities;

class CareerRecommendation
{
    public int $userId;
    public string $careerId;
    public string $careerName;
    public float $matchPercent;
    public array $skills;
    public array $recommendedMajors;
    public array $resources;
    public string $description;
    public string $generatedAt;

    public function __construct(array $data)
    {
        $this->userId = (int)($data['user_id'] ?? 0);
        $this->careerId = (string)($data['career_id'] ?? '');
        $this->careerName = (string)($data['career_name'] ?? '');
        $this->matchPercent = (float)($data['match_percent'] ?? 0.0);
        $this->skills = (array)($data['skills'] ?? []);
        $this->recommendedMajors = (array)($data['recommended_majors'] ?? []);
        $this->resources = (array)($data['resources'] ?? []);
        $this->description = (string)($data['description'] ?? '');
        $this->generatedAt = (string)($data['generated_at'] ?? date('Y-m-d H:i:s'));
    }
}

