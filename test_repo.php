<?php
require_once 'vendor/autoload.php';

$repo = new \App\Modules\Admin\Infrastructure\Persistence\QuestionRepository();

// Test getAllQuestions with assessmentFilter = '1'
echo "=== Testing getAllQuestions(1, 100, '', '1') ===\n";
$result = $repo->getAllQuestions(1, 100, '', '1');
echo "Total: {$result['total']}\n";
echo "Questions found: " . count($result['questions']) . "\n";
foreach ($result['questions'] as $q) {
    echo "  Q{$q['question_id']}: [{$q['assessment_id']} {$q['assessment_title']}] {$q['question_text']}\n";
}

// Test getQuestionsByCategorySlug
echo "\n=== Testing getQuestionsByCategorySlug('personality') ===\n";
$result2 = $repo->getQuestionsByCategorySlug('personality', 1, 100, '');
echo "Total: {$result2['total']}\n";
echo "Questions found: " . count($result2['questions']) . "\n";
foreach ($result2['questions'] as $q) {
    echo "  Q{$q['question_id']}: [{$q['assessment_id']} {$q['assessment_title']}] {$q['question_text']}\n";
}