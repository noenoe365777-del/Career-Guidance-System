<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

$sql = file_get_contents(__DIR__ . '/006_create_notifications_table.sql');
if ($sql === false) {
    die("Failed to read migration file.\n");
}

$pdo = new PDO('mysql:host=localhost;dbname=career_guidance;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec($sql);
echo "Migration 006 ran successfully (notifications table created).\n";
