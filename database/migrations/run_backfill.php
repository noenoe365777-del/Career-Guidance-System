<?php
/**
 * One-time backfill: synchronize student_assessments from assessment_results.
 *
 * Run from project root:
 *   php database/migrations/run_backfill.php
 *
 * This script reads and executes the SQL in backfill_student_assessments.sql
 * via PDO, preserving the exact same logic but with PHP-native reporting.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;

$pdo = Database::getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== BACKFILL: student_assessments <- assessment_results ===\n\n";

$before = getSnapshot($pdo);
echo "BEFORE:\n";
renderSnapshot($before);
echo "\n";

echo "Processing...\n\n";

// ── Step 1: INSERT rows for (user_id, assessment_id) pairs with NO row in student_assessments ──

// Check for orphan users (exist in assessment_results but not in users table)
$orphanStmt = $pdo->query("
    SELECT DISTINCT ar.user_id
    FROM assessment_results ar
    LEFT JOIN users u ON u.user_id = ar.user_id
    WHERE u.user_id IS NULL
");
$orphans = $orphanStmt->fetchAll(PDO::FETCH_COLUMN);
if (count($orphans) > 0) {
    echo "  Skipping orphan user_ids (not in users table): " . implode(', ', $orphans) . "\n";
}

$insertSql = "
    INSERT INTO student_assessments (user_id, assessment_id, status, progress, started_at, completed_at)
    SELECT ar.user_id,
           ar.assessment_id,
           'completed',
           100.00,
           COALESCE(ar.started_at, ar.completed_at, NOW()),
           ar.completed_at
    FROM assessment_results ar
    WHERE ar.status = 'completed'
      AND NOT EXISTS (
          SELECT 1 FROM student_assessments sa
          WHERE sa.user_id = ar.user_id
            AND sa.assessment_id = ar.assessment_id
      )
      AND EXISTS (
          SELECT 1 FROM users u WHERE u.user_id = ar.user_id
      )
";

$pdo->beginTransaction();
$count = $pdo->exec($insertSql);
echo "  Inserted {$count} new row(s)\n";

// ── Step 2: UPDATE existing rows that have stale completion data ──

$updateSql = "
    UPDATE student_assessments sa
      JOIN (
          SELECT ar_sub.user_id,
                 ar_sub.assessment_id,
                 ar_sub.completed_at,
                 (
                     SELECT MAX(sa2.student_assessment_id)
                     FROM student_assessments sa2
                     WHERE sa2.user_id = ar_sub.user_id
                       AND sa2.assessment_id = ar_sub.assessment_id
                 ) AS target_id
          FROM assessment_results ar_sub
          WHERE ar_sub.status = 'completed'
            AND EXISTS (SELECT 1 FROM users u2 WHERE u2.user_id = ar_sub.user_id)
            AND EXISTS (
                SELECT 1 FROM student_assessments sa3
                WHERE sa3.user_id = ar_sub.user_id
                  AND sa3.assessment_id = ar_sub.assessment_id
            )
            AND (
                SELECT sa4.status
                FROM student_assessments sa4
                WHERE sa4.user_id = ar_sub.user_id
                  AND sa4.assessment_id = ar_sub.assessment_id
                ORDER BY sa4.student_assessment_id DESC
                LIMIT 1
            ) != 'completed'
      ) src ON sa.student_assessment_id = src.target_id
    SET sa.status       = 'completed',
        sa.progress     = 100.00,
        sa.completed_at = src.completed_at
";

$count2 = $pdo->exec($updateSql);
echo "  Updated {$count2} existing stale row(s)\n\n";

$pdo->commit();

$after = getSnapshot($pdo);
echo "AFTER:\n";
renderSnapshot($after);

echo "\nDone. If numbers look correct, delete this script and the .sql file.\n";

// ── Helper functions ──

function getSnapshot(PDO $pdo): array
{
    $all = array_unique(array_merge(
        $pdo->query("SELECT DISTINCT user_id FROM student_assessments")->fetchAll(PDO::FETCH_COLUMN),
        $pdo->query("SELECT DISTINCT user_id FROM assessment_results")->fetchAll(PDO::FETCH_COLUMN)
    ));
    sort($all);
    $rows = [];
    foreach ($all as $uid) {
        $s1 = $pdo->prepare("SELECT COUNT(*) FROM student_assessments WHERE user_id = ?"); $s1->execute([$uid]); $saCnt  = $s1->fetchColumn();
        $s2 = $pdo->prepare("SELECT COUNT(*) FROM student_assessments WHERE user_id = ? AND status = 'completed'"); $s2->execute([$uid]); $saDone = $s2->fetchColumn();
        $s3 = $pdo->prepare("SELECT COUNT(*) FROM assessment_results WHERE user_id = ? AND status = 'completed'"); $s3->execute([$uid]); $arDone = $s3->fetchColumn();
        $rows[] = ['uid' => $uid, 'saCnt' => $saCnt, 'saDone' => $saDone, 'arDone' => $arDone];
    }
    return $rows;
}

function renderSnapshot(array $rows): void
{
    foreach ($rows as $r) {
        printf("  User %2d: student_assessments=%d(done=%d)  ar(done=%d)%s\n",
            $r['uid'], $r['saCnt'], $r['saDone'], $r['arDone'],
            $r['saDone'] == $r['arDone'] ? '' : '  <-- MISMATCH');
    }
}
