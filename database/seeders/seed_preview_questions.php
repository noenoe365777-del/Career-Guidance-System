<?php

declare(strict_types=1);

require __DIR__ . '/../../App/Config/Database.php';

$pdo = App\Config\Database::getConnection();

echo "Migration: Adding preview column + seeding full questions\n\n";

// 1. Add preview column if not exists
$stmt = $pdo->query("SHOW COLUMNS FROM questions LIKE 'preview'");
if (!$stmt->fetch()) {
    $pdo->exec("ALTER TABLE questions ADD COLUMN preview TINYINT(1) NOT NULL DEFAULT 0 AFTER question_order");
    echo "✓ Added `preview` column to questions table\n";
} else {
    echo "✓ `preview` column already exists\n";
}

// 2. Define target question counts and new questions
$targetCounts = [
    1 => 25, // personality
    2 => 25, // interest
    3 => 15, // aptitude
    4 => 20, // career values
];

// Questions that exist - first check current questions
foreach ($targetCounts as $assessmentId => $target) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE assessment_id = ?");
    $stmt->execute([$assessmentId]);
    $current = (int)$stmt->fetchColumn();
    echo "Assessment $assessmentId: $current / $target questions\n";
}

// 3. New questions to add (these extend existing sets)
$newQuestions = [
    // Personality (currently 11, need 14 more to reach 25)
    1 => [
        'I often reflect on my thoughts and feelings.',
        'I enjoy brainstorming new ideas with others.',
        'I prefer routines over spontaneous activities.',
        'I am comfortable speaking in front of large groups.',
        'I tend to notice small details others miss.',
        'I value harmony and avoid conflict in groups.',
        'I set clear goals and work systematically toward them.',
        'I enjoy exploring new places and cultures.',
        'I am easily distracted by exciting opportunities.',
        'I prefer working independently rather than in teams.',
        'I express my emotions openly.',
        'I like to analyze situations from multiple perspectives.',
        'I am motivated by recognition and achievement.',
        'I enjoy helping others grow and succeed.',
    ],
    // Interest (currently 10, need 15 more to reach 25)
    2 => [
        'I enjoy debating and discussing ideas.',
        'I like working with numbers and spreadsheets.',
        'I am interested in psychology and human behavior.',
        'I enjoy building or constructing things.',
        'I like to travel and explore new environments.',
        'I enjoy mentoring or coaching others.',
        'I am drawn to music, theater, or performing arts.',
        'I like analyzing market trends and business strategies.',
        'I enjoy working with animals or nature.',
        'I take pleasure in cooking, gardening, or crafts.',
        'I enjoy reading scientific articles and journals.',
        'I like designing websites or mobile apps.',
        'I enjoy planning community events or fundraisers.',
        'I am interested in law, justice, and public policy.',
        'I like solving complex technical problems.',
    ],
    // Aptitude (currently 5, need 10 more to reach 15)
    3 => [
        'I can quickly learn how to use new software tools.',
        'I enjoy analyzing cause-and-effect relationships.',
        'I am good at breaking complex problems into smaller steps.',
        'I can estimate quantities and measurements accurately.',
        'I enjoy reading technical manuals and documentation.',
        'I can identify patterns in data and draw conclusions.',
        'I am comfortable with basic mathematical calculations.',
        'I can think of multiple solutions to a single problem.',
        'I enjoy testing and troubleshooting systems.',
        'I can organize information into clear categories.',
    ],
    // Career Values (currently 6, need 14 more to reach 20)
    4 => [
        'I value work-life balance over a high salary.',
        'I want opportunities for continuous learning.',
        'I prefer a collaborative team environment.',
        'I want my work to have a positive social impact.',
        'I value recognition and appreciation at work.',
        'I prefer a job with clear career advancement paths.',
        'I value autonomy to make my own decisions.',
        'I want to work for a company with strong ethics.',
        'I value jobs that offer travel and variety.',
        'I prefer a competitive work environment.',
        'I value having a supportive manager and mentor.',
        'I want to work with cutting-edge technology.',
        'I value job perks and benefits beyond salary.',
        'I prefer a predictable daily work routine.',
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
    "INSERT INTO questions (assessment_id, question_text, question_type, question_order, preview, created_at) VALUES (:assessment_id, :question_text, 'single_choice', :question_order,     :preview, NOW())"
);

$optionStmt = $pdo->prepare(
    "INSERT INTO question_options (question_id, option_text, option_value, option_order) VALUES (:question_id, :option_text, :option_value, :option_order)"
);

$totalInserted = 0;

foreach ($newQuestions as $assessmentId => $questions) {
    // Get current max question_order for this assessment
    $stmt = $pdo->prepare("SELECT COALESCE(MAX(question_order), 0) FROM questions WHERE assessment_id = ?");
    $stmt->execute([$assessmentId]);
    $orderStart = (int)$stmt->fetchColumn();

    foreach ($questions as $i => $questionText) {
        $order = $orderStart + $i + 1;
        // All new questions are preview=0 (non-preview)
        $questionStmt->execute([
            'assessment_id' => $assessmentId,
            'question_text' => $questionText,
            'question_order' => $order,
            'preview' => 0,
        ]);
        $questionId = (int)$pdo->lastInsertId();

        foreach ($optionsData as $opt) {
            $optionStmt->execute([
                'question_id' => $questionId,
                'option_text' => $opt['option_text'],
                'option_value' => $opt['option_value'],
                'option_order' => $opt['option_order'],
            ]);
        }
        $totalInserted++;
    }
}

echo "✓ Inserted $totalInserted new questions (all preview=0)\n";

// 4. Mark 5 questions per assessment as preview=1 (pick first 5 by question_order)
$marked = 0;
foreach ([1, 2, 3, 4] as $assessmentId) {
    $stmt = $pdo->prepare(
        "UPDATE questions SET preview = 1 WHERE assessment_id = :aid ORDER BY question_order ASC LIMIT 5"
    );
    $pdo->prepare(
        "UPDATE questions SET preview = 1 WHERE assessment_id = ? ORDER BY question_order ASC LIMIT 5"
    );
    // MySQL doesn't support ORDER BY in UPDATE with LIMIT directly this way
    // Use a subquery approach
    $pdo->exec(
        "UPDATE questions SET preview = 1 WHERE question_id IN (
            SELECT qid FROM (
                SELECT question_id AS qid FROM questions 
                WHERE assessment_id = $assessmentId 
                ORDER BY question_order ASC 
                LIMIT 5
            ) AS tmp
        )"
    );
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE assessment_id = ? AND preview = 1");
    $stmt->execute([$assessmentId]);
    $count = (int)$stmt->fetchColumn();
    echo "  Assessment $assessmentId: $count preview questions marked\n";
    $marked += $count;
}

echo "\n✓ Total preview questions marked: $marked\n";

// 5. Verify final state
echo "\n--- Final Question Counts ---\n";
$stmt = $pdo->query("
    SELECT a.assessment_id, a.title, 
           COUNT(q.question_id) AS total,
           SUM(CASE WHEN q.preview = 1 THEN 1 ELSE 0 END) AS preview_count
    FROM assessments a 
    LEFT JOIN questions q ON a.assessment_id = q.assessment_id 
    GROUP BY a.assessment_id 
    ORDER BY a.assessment_id
");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "  {$row['title']}: {$row['total']} total, {$row['preview_count']} preview\n";
}

echo "\n✓ Migration complete!\n";
