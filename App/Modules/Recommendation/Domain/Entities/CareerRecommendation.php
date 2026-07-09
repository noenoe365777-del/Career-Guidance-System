<?php

declare(strict_types=1);

namespace App\Modules\Recommendation\Domain\Entities;

class CareerRecommendation
{
    public int $userId;
    public int $careerId;
    public string $careerName;
    public float $matchPercent;
    public string $description;
    public string $requiredSkills;
    public string $averageSalary;
    public string $growthRate;
    public string $educationRequired;
    public string $reason;

    public function __construct(array $data)
    {
        $this->userId = (int)($data['user_id'] ?? 0);
        $this->careerId = (int)($data['career_id'] ?? 0);
        $this->careerName = (string)($data['career_name'] ?? '');
        $this->matchPercent = (float)($data['match_percent'] ?? 0.0);
        $this->description = (string)($data['description'] ?? '');
        $this->requiredSkills = (string)($data['required_skills'] ?? '');
        $this->averageSalary = (string)($data['average_salary'] ?? '');
        $this->growthRate = (string)($data['growth_rate'] ?? '');
        $this->educationRequired = (string)($data['education_required'] ?? '');
        $this->reason = (string)($data['reason'] ?? '');
    }
}
