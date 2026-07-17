<?php
require_once 'vendor/autoload.php';
$pdo = \App\Config\Database::getConnection();

// Check assessments table schema
echo "=== ASSESSMENTS TABLE COLUMNS ===\n";
$stmt = $pdo->query('DESCRIBE assessments');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "{$row['Field']} ({$row['Type']})\n";
}

// Check assessments
echo "\n=== ASSESSMENTS ===\n";
$stmt = $pdo->query('SELECT * FROM assessments ORDER BY assessment_id');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    foreach ($row as $k => $v) {
        echo "$k: $v  ";
    }
    echo "\n";
}

// Check questions
echo "\n=== QUESTIONS ===\n";
$stmt = $pdo->query('SELECT q.*, a.title FROM questions q JOIN assessments a ON a.assessment_id = q.assessment_id ORDER BY q.question_id');
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total questions: " . count($questions) . "\n";
foreach ($questions as $row) {
    foreach ($row as $k => $v) {
        echo "$k: $v  ";
    }
    echo "\n";
}

// Check question options
echo "\n=== QUESTION OPTIONS ===\n";
$stmt = $pdo->query('SELECT question_id, COUNT(*) as opt_count FROM question_options GROUP BY question_id');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "Q{$row['question_id']}: {$row['opt_count']} options\n";
}