<?php
require_once 'C:/xampp/htdocs/career-guidance-system/vendor/autoload.php';
require_once 'C:/xampp/htdocs/career-guidance-system/App/config/Database.php';

use App\Config\Database;

$pdo = Database::getConnection();

$stmt = $pdo->query('DESCRIBE users');
while ($row = $stmt->fetch()) {
    echo $row['Field'] . ' | ' . $row['Type'] . "\n";
}