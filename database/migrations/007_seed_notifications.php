<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;

$pdo = Database::getConnection();

$notifications = [
    ['type' => 'assessment', 'title' => 'Student completed Personality Assessment', 'message' => 'Alex Johnson completed the Personality Assessment with a score of 85%.', 'created_at' => '2026-07-13 09:15:00'],
    ['type' => 'assessment', 'title' => 'Student completed Interest Assessment', 'message' => 'Maria Garcia completed the Interest Assessment with a score of 92%.', 'created_at' => '2026-07-13 08:45:00'],
    ['type' => 'user', 'title' => 'New student registered', 'message' => 'James Wilson has created a new account.', 'link' => '/index.php?page=admin-users-view&id=15', 'created_at' => '2026-07-13 07:30:00'],
    ['type' => 'career', 'title' => 'Career recommendation generated', 'message' => 'System generated career recommendations for Sarah Chen based on her assessment results.', 'created_at' => '2026-07-12 18:20:00'],
    ['type' => 'assessment', 'title' => 'Assessment created', 'message' => 'A new "Critical Thinking" assessment has been added by admin.', 'created_at' => '2026-07-12 15:00:00'],
    ['type' => 'system', 'title' => 'System notification', 'message' => 'Daily backup completed successfully. All data has been backed up.', 'created_at' => '2026-07-12 03:00:00'],
    ['type' => 'question', 'title' => 'Question added', 'message' => '5 new questions added to the Aptitude Assessment.', 'created_at' => '2026-07-11 14:30:00'],
    ['type' => 'assessment', 'title' => 'Student completed Aptitude Assessment', 'message' => 'David Lee completed the Aptitude Assessment with a score of 78%.', 'created_at' => '2026-07-11 11:10:00'],
    ['type' => 'user', 'title' => 'New student registered', 'message' => 'Emily Brown has created a new account.', 'link' => '/index.php?page=admin-users-view&id=16', 'created_at' => '2026-07-11 09:00:00'],
    ['type' => 'question', 'title' => 'Question updated', 'message' => 'Question #42 in the Personality Assessment has been updated.', 'created_at' => '2026-07-10 16:45:00'],
    ['type' => 'career', 'title' => 'Career recommendation generated', 'message' => 'Recommendations generated for 3 students based on their Interest Assessment results.', 'created_at' => '2026-07-10 14:20:00'],
    ['type' => 'system', 'title' => 'Weekly maintenance completed', 'message' => 'Scheduled maintenance completed. System performance has been optimized.', 'created_at' => '2026-07-09 05:00:00'],
    ['type' => 'assessment', 'title' => 'Student completed Career Values Assessment', 'message' => 'Lisa Thompson completed the Career Values Assessment with a score of 71%.', 'created_at' => '2026-07-09 10:30:00'],
    ['type' => 'user', 'title' => 'Account updated', 'message' => 'Profile information updated for user "Michael Davis".', 'created_at' => '2026-07-08 13:15:00'],
    ['type' => 'assessment', 'title' => 'Assessment results reviewed', 'message' => 'Admin reviewed and approved assessment results for 8 students.', 'created_at' => '2026-07-08 11:00:00'],
];

$stmt = $pdo->prepare(
    'INSERT INTO notifications (type, title, message, link, created_at) VALUES (:type, :title, :message, :link, :created_at)'
);

foreach ($notifications as $n) {
    $stmt->execute([
        ':type' => $n['type'],
        ':title' => $n['title'],
        ':message' => $n['message'],
        ':link' => $n['link'] ?? null,
        ':created_at' => $n['created_at'],
    ]);
}

echo "Seeded " . count($notifications) . " notifications successfully.\n";
