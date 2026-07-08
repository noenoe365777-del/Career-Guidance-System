<?php
require 'App/config/Database.php';
$pdo = App\Config\Database::getConnection();
foreach (['users','assessments','careers','student_assessments'] as $table) {
    echo "TABLE $table\n";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['Field'] . "\n";
        }
    } catch (Throwable $e) {
        echo $e->getMessage() . "\n";
    }
    echo "---\n";
}
