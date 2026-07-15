<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use PDO;

class NewAssessmentRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAssessmentConfig(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM assessments WHERE status='active' ORDER BY assessment_id");
        return $stmt->fetchAll();
    }

    public function getQuestions(int $assessmentId, int $limit): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessment_questions WHERE assessment_id = :aid ORDER BY RAND() LIMIT :lim");
        $stmt->bindValue(':aid', $assessmentId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getQuestionById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessment_questions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getOrCreateResult(int $userId, int $assessmentId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessment_results WHERE user_id = :uid AND assessment_id = :aid ORDER BY id DESC LIMIT 1");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        $existing = $stmt->fetch();

        if ($existing && $existing['status'] === 'completed') {
            return $existing;
        }
        if ($existing && $existing['status'] === 'in_progress') {
            return $existing;
        }

        $now = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO assessment_results (user_id, assessment_id, status, started_at) VALUES (:uid, :aid, 'in_progress', :now)");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId, ':now' => $now]);
        $id = (int)$this->pdo->lastInsertId();
        return [
            'id' => $id,
            'user_id' => $userId,
            'assessment_id' => $assessmentId,
            'score' => '0.00',
            'percentage' => '0.00',
            'status' => 'in_progress',
            'started_at' => $now,
            'completed_at' => null,
        ];
    }

    public function saveAnswer(int $userId, int $questionId, string $selectedAnswer, float $score): void
    {
        $stmt = $this->pdo->prepare("SELECT id FROM answers WHERE user_id = :uid AND question_id = :qid");
        $stmt->execute([':uid' => $userId, ':qid' => $questionId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE answers SET selected_answer = :ans, score = :sc WHERE id = :id");
            $stmt->execute([':ans' => $selectedAnswer, ':sc' => $score, ':id' => $existing['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO answers (user_id, question_id, selected_answer, score) VALUES (:uid, :qid, :ans, :sc)");
            $stmt->execute([':uid' => $userId, ':qid' => $questionId, ':ans' => $selectedAnswer, ':sc' => $score]);
        }
    }

    public function getAnsweredCount(int $userId, int $assessmentId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM answers a JOIN assessment_questions q ON q.id = a.question_id WHERE a.user_id = :uid AND q.assessment_id = :aid");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        return (int)$stmt->fetchColumn();
    }

    public function getCorrectCount(int $userId, int $assessmentId): int
    {
        $sql = "SELECT COUNT(*) FROM answers a
                JOIN assessment_questions q ON q.id = a.question_id
                WHERE a.user_id = :uid AND q.assessment_id = :aid
                AND q.correct_answer IS NOT NULL
                AND a.selected_answer = q.correct_answer";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        return (int)$stmt->fetchColumn();
    }

    public function calculatePercentage(int $userId, int $assessmentId): float
    {

        $hasCorrect = $this->pdo->prepare("SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = :aid AND correct_answer IS NOT NULL");
        $hasCorrect->execute([':aid' => $assessmentId]);
        $correctCount = (int)$hasCorrect->fetchColumn();

        if ($correctCount > 0) {
            $total = $this->getAnsweredCount($userId, $assessmentId);
            $correct = $this->getCorrectCount($userId, $assessmentId);
            return $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        }

        $stmt = $this->pdo->prepare("SELECT COALESCE(AVG(a.score), 0) FROM answers a JOIN assessment_questions q ON q.id = a.question_id WHERE a.user_id = :uid AND q.assessment_id = :aid");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        $avg = (float)$stmt->fetchColumn();
        return round(($avg / 4) * 100, 2);
    }

    public function completeAssessment(int $userId, int $assessmentId): array
    {
        $percentage = $this->calculatePercentage($userId, $assessmentId);
        $answered = $this->getAnsweredCount($userId, $assessmentId);
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("UPDATE assessment_results SET score = :sc, percentage = :pct, status = 'completed', completed_at = :now WHERE user_id = :uid AND assessment_id = :aid AND status = 'in_progress'");
        $stmt->execute([':sc' => $percentage, ':pct' => $percentage, ':now' => $now, ':uid' => $userId, ':aid' => $assessmentId]);

        $this->saveLegacyScore($userId, $assessmentId, $percentage);

        return ['percentage' => $percentage, 'answered' => $answered];
    }

    private function saveLegacyScore(int $userId, int $assessmentId, float $percentage): void
    {
        $map = [1 => 'personality', 2 => 'interest', 3 => 'aptitude', 4 => 'values'];
        $slug = $map[$assessmentId] ?? 'unknown';
        $scoreCol = $slug . '_score';
        $typeCol = $slug . '_type';

        $typeMap = [
            1 => fn($p) => $p >= 80 ? 'Extrovert' : ($p >= 60 ? 'Ambivert' : 'Introvert'),
            2 => fn($p) => $p >= 80 ? 'Creative / Investigative' : ($p >= 60 ? 'Balanced' : 'Practical'),
            3 => fn($p) => $p >= 70 ? 'Advanced' : ($p >= 50 ? 'Competent' : 'Beginner'),
            4 => fn($p) => $p >= 75 ? 'Defined' : ($p >= 50 ? 'Developing' : 'Undefined'),
        ];
        $type = isset($typeMap[$assessmentId]) ? $typeMap[$assessmentId]((int)$percentage) : 'Moderate';

        $stmt = $this->pdo->prepare("SELECT id FROM student_assessment_scores WHERE student_id = :sid");
        $stmt->execute([':sid' => $userId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $this->pdo->prepare("UPDATE student_assessment_scores SET `$scoreCol` = :sc, `$typeCol` = :tp WHERE student_id = :sid")
                 ->execute([':sc' => (int)$percentage, ':tp' => $type, ':sid' => $userId]);
        } else {
            $this->pdo->prepare("INSERT INTO student_assessment_scores (student_id, `$scoreCol`, `$typeCol`) VALUES (:sid, :sc, :tp)")
                 ->execute([':sid' => $userId, ':sc' => (int)$percentage, ':tp' => $type]);
        }
    }

    public function getResult(int $userId, int $assessmentId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM assessment_results WHERE user_id = :uid AND assessment_id = :aid ORDER BY id DESC LIMIT 1");
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getResultsForUser(int $userId): array
    {
        $sql = "SELECT ar.*, a.title AS assessment_name, a.icon
                FROM assessment_results ar
                JOIN assessments a ON a.assessment_id = ar.assessment_id
                WHERE ar.user_id = :uid
                ORDER BY ar.assessment_id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        $results = $stmt->fetchAll();

        $indexed = [];
        foreach ($results as $r) {
            $indexed[(int)$r['assessment_id']] = $r;
        }
        return $indexed;
    }

    public function allAssessmentsCompleted(int $userId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM assessment_results WHERE user_id = :uid AND status = 'completed'");
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn() >= 4;
    }

    public function getStudentAnswers(int $userId, int $assessmentId): array
    {
        $sql = "SELECT a.id, a.question_id, a.selected_answer, a.score AS answer_score,
                       q.question, q.option_a, q.option_b, q.option_c, q.option_d,
                       q.correct_answer, q.weight
                FROM answers a
                JOIN assessment_questions q ON q.id = a.question_id
                WHERE a.user_id = :uid AND q.assessment_id = :aid
                ORDER BY a.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        return $stmt->fetchAll();
    }

    public function getAssessmentResult(int $userId, int $assessmentId): ?array
    {
        $sql = "SELECT ar.*, a.title AS assessment_name, a.description AS assessment_description, a.icon
                FROM assessment_results ar
                JOIN assessments a ON a.assessment_id = ar.assessment_id
                WHERE ar.user_id = :uid AND ar.assessment_id = :aid AND ar.status = 'completed'
                ORDER BY ar.id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getSuggestedCareers(int $assessmentId): array
    {
        $typeCols = [1 => 'personality_type', 2 => 'interest_type', 3 => 'aptitude_type', 4 => 'values_type'];
        $col = $typeCols[$assessmentId] ?? null;
        if (!$col) return [];

        $scoreCol = str_replace('_type', '_score', $col);
        $sql = "SELECT career_id, career_name, career_icon, description, required_skills,
                       average_salary, growth_rate, education_required, $col AS match_type
                FROM careers
                WHERE status = 'active'
                ORDER BY career_id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getAssessmentTypeLabel(int $assessmentId): string
    {
        $labels = [1 => 'Personality', 2 => 'Interest', 3 => 'Aptitude', 4 => 'Career Values'];
        return $labels[$assessmentId] ?? 'Assessment';
    }

    public function getCorrectAnswerCount(int $userId, int $assessmentId): int
    {
        $sql = "SELECT COUNT(*) FROM answers a
                JOIN assessment_questions q ON q.id = a.question_id
                WHERE a.user_id = :uid AND q.assessment_id = :aid
                AND q.correct_answer IS NOT NULL
                AND a.selected_answer = q.correct_answer";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId, ':aid' => $assessmentId]);
        return (int)$stmt->fetchColumn();
    }

    public function getTotalQuestionsForAssessment(int $assessmentId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM assessment_questions WHERE assessment_id = :aid");
        $stmt->execute([':aid' => $assessmentId]);
        return (int)$stmt->fetchColumn();
    }
}
