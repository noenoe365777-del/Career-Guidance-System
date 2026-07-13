<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;

$pdo = Database::getConnection();

$sql = file_get_contents(__DIR__ . '/008_add_status_to_careers.sql');

try {
    $pdo->exec($sql);
    echo "Migration 008_add_status_to_careers.sql executed successfully.\n";
} catch (PDOException $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
