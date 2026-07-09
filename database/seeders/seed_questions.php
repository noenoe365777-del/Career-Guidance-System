<?php

declare(strict_types=1);

require __DIR__ . '/../../App/Config/Database.php';

$pdo = App\Config\Database::getConnection();

echo "Seeding questions and question_options...\n";

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE question_options");
$pdo->exec("TRUNCATE TABLE questions");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

$questionsByAssessment = [
    1 => [ // personality
        'I enjoy meeting and talking with new people.',
        'I like taking responsibility and leading group work.',
        'I prefer planning tasks before I start working.',
        'I stay calm when I have to work under pressure.',
        'I enjoy working as part of a team.',
        'I make decisions based on logic rather than emotions.',
        'I enjoy trying new ideas and experiences.',
        'I finish my work before deadlines.',
        'I easily adapt to unexpected changes.',
        'I like solving difficult problems.',
    ],
    2 => [ // interest
        'I enjoy helping other people solve their problems.',
        'I like creating artwork or designs.',
        'I enjoy programming or using computers.',
        'I like repairing machines or equipment.',
        'I enjoy organizing events or activities.',
        'I enjoy teaching others new skills.',
        'I like conducting science experiments.',
        'I enjoy writing stories or articles.',
        'I like managing money or budgets.',
        'I enjoy working outdoors.',
    ],
    3 => [ // aptitude
        'I enjoy solving logic puzzles and number challenges.',
        'I like understanding how systems and processes work.',
        'I am comfortable working with data and patterns.',
        'I enjoy planning solutions before taking action.',
        'I can focus on detailed tasks for long periods.',
    ],
    4 => [ // career values
        'I value stability and security in my career.',
        'I want a role that helps other people.',
        'I am motivated by creativity and innovation.',
        'I want flexibility and independence in my work.',
        'I care about earning a high income.',
    ],
];

$optionsData = [
    ['option_value' => 5, 'option_text' => 'Strongly Agree', 'option_order' => 1],
    ['option_value' => 4, 'option_text' => 'Agree', 'option_order' => 2],
    ['option_value' => 3, 'option_text' => 'Neutral', 'option_order' => 3],
    ['option_value' => 2, 'option_text' => 'Disagree', 'option_order' => 4],
    ['option_value' => 1, 'option_text' => 'Strongly Disagree', 'option_order' => 5],
];

$questionStmt = $pdo->prepare(
    "INSERT INTO questions (assessment_id, question_text, question_type, question_order, created_at) VALUES (:assessment_id, :question_text, 'single_choice', :question_order, NOW())"
);

$optionStmt = $pdo->prepare(
    "INSERT INTO question_options (question_id, option_text, option_value, option_order) VALUES (:question_id, :option_text, :option_value, :option_order)"
);

$totalQuestions = 0;
$totalOptions = 0;

foreach ($questionsByAssessment as $assessmentId => $questions) {
    foreach ($questions as $order => $questionText) {
        $questionStmt->execute([
            'assessment_id' => $assessmentId,
            'question_text' => $questionText,
            'question_order' => $order + 1,
        ]);
        $questionId = (int)$pdo->lastInsertId();
        $totalQuestions++;

        foreach ($optionsData as $opt) {
            $optionStmt->execute([
                'question_id' => $questionId,
                'option_text' => $opt['option_text'],
                'option_value' => $opt['option_value'],
                'option_order' => $opt['option_order'],
            ]);
            $totalOptions++;
        }
    }
}

echo "Inserted {$totalQuestions} questions and {$totalOptions} options.\n";
