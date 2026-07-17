<?php
require_once 'vendor/autoload.php';
$pdo = \App\Config\Database::getConnection();

// Test the exact query from getAllQuestions with assessmentFilter = '1'
echo "=== Testing getAllQuestions with assessmentFilter='1' ===\n";

$search = '';
$assessmentFilter = '1';

$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = 'LOWER(q.question_text) LIKE :search';
    $params[':search'] = '%' . strtolower($search) . '%';
}

if ($assessmentFilter !== null && $assessmentFilter !== '' && $assessmentFilter !== '0') {
    $ids = array_filter(array_map('intval', explode(',', $assessmentFilter)), fn($id) => $id > 0);
    if ($ids !== []) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $conditions[] = "q.assessment_id IN ({$placeholders})";
        foreach ($ids as $id) {
            $params[] = $id;
        }
    }
}

$where = $conditions !== [] ? 'WHERE ' . implode(' AND ', $conditions) : '';

$selectSql = "
    SELECT q.question_id,
           ANY_VALUE(q.question_text) AS question_text,
           ANY_VALUE(q.assessment_id) AS assessment_id,
           ANY_VALUE(q.question_type) AS question_type,
           ANY_VALUE(q.question_order) AS question_order,
           ANY_VALUE(q.created_at) AS created_at,
           ANY_VALUE(a.title) AS assessment_title,
           ANY_VALUE(a.category) AS assessment_category,
           COUNT(DISTINCT qo.option_id) AS option_count,
           COUNT(DISTINCT sa.answer_id) AS response_count,
           CASE
               WHEN COUNT(DISTINCT qo.option_id) <= 2 THEN 'easy'
               WHEN COUNT(DISTINCT qo.option_id) <= 4 THEN 'medium'
               ELSE 'hard'
           END AS difficulty,
           CASE
               WHEN COUNT(DISTINCT sa.answer_id) > 0 THEN 'used'
               ELSE 'draft'
           END AS status
    FROM questions q
    JOIN assessments a ON a.assessment_id = q.assessment_id
    LEFT JOIN question_options qo ON qo.question_id = q.question_id
    LEFT JOIN student_answers sa ON sa.question_id = q.question_id
    {$where}
    GROUP BY q.question_id
    ORDER BY q.created_at DESC
    LIMIT 100 OFFSET 0
";

echo "SQL:\n$selectSql\n\n";

try {
    $stmt = $pdo->prepare($selectSql);
    $positionalIndex = 1;
    foreach ($params as $key => $value) {
        if (is_int($key)) {
            $stmt->bindValue($positionalIndex++, $value);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();

    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Results: " . count($questions) . " questions\n";
    foreach ($questions as $q) {
        echo "  Q{$q['question_id']}: [{$q['assessment_id']} {$q['assessment_title']}] {$q['question_text']}\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}