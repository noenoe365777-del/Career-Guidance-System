<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=career_guidance;charset=utf8mb4', 'root', '');
foreach (['careers','career_recommendations','users','student_profiles','student_assessments'] as $table) {
    echo "TABLE $table\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo $col['Field'] . ' ' . $col['Type'] . PHP_EOL;
    }
    echo PHP_EOL;
}
