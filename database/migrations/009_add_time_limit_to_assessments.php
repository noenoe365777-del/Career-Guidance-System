<?php
/**
 * Migration: Add time_limit column to assessments table
 */

require_once __DIR__ . '/../../App/config/Database.php';

use App\Config\Database;

$pdo = Database::getConnection();

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM assessments LIKE 'time_limit'");
    $column = $stmt->fetch();

    if (!$column) {
        // Add time_limit column
        $pdo->exec("ALTER TABLE assessments ADD COLUMN time_limit INT DEFAULT 600 AFTER total_questions");
        echo "Added time_limit column to assessments table\n";

        // Set default time limits for existing assessments
        $pdo->exec("UPDATE assessments SET time_limit = 600 WHERE assessment_type IN ('personality', 'interest')");
        $pdo->exec("UPDATE assessments SET time_limit = 300 WHERE assessment_type IN ('aptitude', 'values')");
        echo "Set default time limits for assessments\n";
    } else {
        echo "time_limit column already exists\n";
    }

    echo "Migration completed successfully\n";
} catch (Throwable $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}