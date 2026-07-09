<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\StudentAnswerRepositoryInterface;
use PDO;

class StudentAnswerRepository implements StudentAnswerRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function saveAnswers(int $studentAssessmentId, array $answers): void
    {
        try {
            $this->connection->prepare("DELETE FROM student_answers WHERE student_assessment_id = :id")
                ->execute(['id' => $studentAssessmentId]);

            foreach ($answers as $questionId => $answerValue) {
                if (!is_numeric($questionId) || !is_numeric($answerValue)) {
                    continue;
                }

                try {
                    $statement = $this->connection->prepare(
                        "INSERT INTO student_answers (student_assessment_id, question_id, answer_text, score, created_at) VALUES (:student_assessment_id, :question_id, :answer_text, :score, NOW())"
                    );
                    $statement->execute([
                        'student_assessment_id' => $studentAssessmentId,
                        'question_id' => (int)$questionId,
                        'answer_text' => (string)$answerValue,
                        'score' => (int)$answerValue,
                    ]);
                } catch (\Throwable) {
                }
            }
        } catch (\Throwable) {
        }
    }

    public function getAnswers(int $studentAssessmentId): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT answer_id AS id, question_id, answer_text AS answer_value, score FROM student_answers WHERE student_assessment_id = :id ORDER BY answer_id"
            );
            $statement->execute(['id' => $studentAssessmentId]);
            return $statement->fetchAll();
        } catch (\Throwable) {
            return [];
        }
    }
}

