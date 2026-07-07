<?php

declare(strict_types=1);

namespace App\Modules\Assessment\Infrastructure\Persistence;

use App\Config\Database;
use App\Modules\Assessment\Domain\Repositories\QuestionRepositoryInterface;
use PDO;

class QuestionRepository implements QuestionRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getQuestionsBySlug(string $slug): array
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT q.question_id AS id, q.question_text AS question, q.question_type AS type, q.question_order AS `order`
                 FROM questions q
                 INNER JOIN assessments a ON a.assessment_id = q.assessment_id
                 WHERE a.assessment_type = :slug
                 ORDER BY q.question_order"
            );
            $statement->execute(['slug' => $slug]);
            $rows = $statement->fetchAll();

            if (!empty($rows)) {
                $rows = array_map(fn(array $row): array => [
                    'id' => (int)($row['id'] ?? 0),
                    'question' => $row['question'] ?? '',
                    'type' => $row['type'] ?? 'single_choice',
                    'order' => (int)($row['order'] ?? 0),
                ], $rows);

                shuffle($rows);
                return array_slice($rows, 0, 5);
            }
        } catch (\Throwable) {
        }

        return $this->fallbackQuestions($slug);
    }

    private function fallbackQuestions(string $slug): array
    {
        $datasets = [
            'personality' => [
                ['id' => 1001, 'question' => 'I enjoy meeting and talking with new people.'],
                ['id' => 1002, 'question' => 'I like taking responsibility and leading group work.'],
                ['id' => 1003, 'question' => 'I prefer planning tasks before I start working.'],
                ['id' => 1004, 'question' => 'I stay calm when I have to work under pressure.'],
                ['id' => 1005, 'question' => 'I enjoy working as part of a team.'],
                ['id' => 1006, 'question' => 'I make decisions based on logic rather than emotions.'],
                ['id' => 1007, 'question' => 'I enjoy trying new ideas and experiences.'],
                ['id' => 1008, 'question' => 'I finish my work before deadlines.'],
                ['id' => 1009, 'question' => 'I easily adapt to unexpected changes.'],
                ['id' => 1010, 'question' => 'I like solving difficult problems.'],
            ],
            'interest' => [
                ['id' => 2001, 'question' => 'I enjoy helping other people solve their problems.'],
                ['id' => 2002, 'question' => 'I like creating artwork or designs.'],
                ['id' => 2003, 'question' => 'I enjoy programming or using computers.'],
                ['id' => 2004, 'question' => 'I like repairing machines or equipment.'],
                ['id' => 2005, 'question' => 'I enjoy organizing events or activities.'],
                ['id' => 2006, 'question' => 'I enjoy teaching others new skills.'],
                ['id' => 2007, 'question' => 'I like conducting science experiments.'],
                ['id' => 2008, 'question' => 'I enjoy writing stories or articles.'],
                ['id' => 2009, 'question' => 'I like managing money or budgets.'],
                ['id' => 2010, 'question' => 'I enjoy working outdoors.'],
            ],
            'aptitude' => [
                ['id' => 3001, 'question' => 'I enjoy solving logic puzzles and number challenges.'],
                ['id' => 3002, 'question' => 'I like understanding how systems and processes work.'],
                ['id' => 3003, 'question' => 'I am comfortable working with data and patterns.'],
                ['id' => 3004, 'question' => 'I enjoy planning solutions before taking action.'],
                ['id' => 3005, 'question' => 'I can focus on detailed tasks for long periods.'],
            ],
            'values' => [
                ['id' => 4001, 'question' => 'I value stability and security in my career.'],
                ['id' => 4002, 'question' => 'I want a role that helps other people.'],
                ['id' => 4003, 'question' => 'I am motivated by creativity and innovation.'],
                ['id' => 4004, 'question' => 'I want flexibility and independence in my work.'],
                ['id' => 4005, 'question' => 'I care about earning a high income.'],
            ],
        ];

        $questions = $datasets[$slug] ?? [];
        shuffle($questions);
        return array_slice($questions, 0, 5);
    }
}

