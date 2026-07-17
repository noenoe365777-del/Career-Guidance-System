<?php
require_once 'vendor/autoload.php';
$pdo = \App\Config\Database::getConnection();
echo 'SQL Mode: ';
$stmt = $pdo->query('SELECT @@sql_mode');
echo $stmt->fetchColumn() . PHP_EOL;