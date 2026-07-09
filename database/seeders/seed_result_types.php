<?php

declare(strict_types=1);

require __DIR__ . '/../../App/Config/Database.php';

$pdo = App\Config\Database::getConnection();

echo "Creating assessment_result_types table...\n";

$pdo->exec("
    CREATE TABLE IF NOT EXISTS assessment_result_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(50) NOT NULL,
        min_score INT NOT NULL,
        max_score INT NOT NULL,
        type_label VARCHAR(100) NOT NULL,
        interpretation TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

$pdo->exec("TRUNCATE TABLE assessment_result_types");

$types = [
    ['personality', 0, 40, 'Introvert', 'You tend to be reserved and thoughtful, preferring deep focus and meaningful one-on-one interactions.'],
    ['personality', 41, 60, 'Ambivert', 'You have a balanced mix of introverted and extroverted traits, adapting well to different social situations.'],
    ['personality', 61, 100, 'Extrovert', 'You are outgoing and energetic, thriving in social settings and collaborative environments.'],
    ['interest', 0, 40, 'Practical', 'You prefer hands-on, structured work with clear outcomes and tangible results.'],
    ['interest', 41, 60, 'Balanced', 'You enjoy a mix of creative and practical activities, adapting to varied work environments.'],
    ['interest', 61, 100, 'Creative / Investigative', 'You are drawn to innovative, exploratory work that involves ideas, discovery, and self-expression.'],
    ['aptitude', 0, 40, 'Developing', 'You are building your analytical and problem-solving skills. Practice and learning will boost your confidence.'],
    ['aptitude', 41, 60, 'Competent', 'You have solid reasoning abilities and can handle most analytical challenges with some preparation.'],
    ['aptitude', 61, 100, 'Advanced', 'You possess strong critical thinking and problem-solving skills, well-suited for complex analytical roles.'],
    ['values', 0, 40, 'Exploratory', 'You are still exploring what matters most in your career. Try different experiences to clarify your priorities.'],
    ['values', 41, 60, 'Developing', 'You have a growing sense of your core values, with a balanced view of what you want from your career.'],
    ['values', 61, 100, 'Defined', 'You have a clear understanding of your career values, which will guide you toward fulfilling work.'],
];

$stmt = $pdo->prepare(
    "INSERT INTO assessment_result_types (slug, min_score, max_score, type_label, interpretation, created_at) VALUES (:slug, :min_score, :max_score, :type_label, :interpretation, NOW())"
);

foreach ($types as $t) {
    $stmt->execute([
        'slug' => $t[0],
        'min_score' => $t[1],
        'max_score' => $t[2],
        'type_label' => $t[3],
        'interpretation' => $t[4],
    ]);
}

echo "Inserted " . count($types) . " result type definitions.\n";
