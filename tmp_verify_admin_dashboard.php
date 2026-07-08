<?php
require 'App/config/Database.php';
require 'App/Modules/Admin/Infrastructure/AdminDashboardRepository.php';

$repo = new App\Modules\Admin\Infrastructure\AdminDashboardRepository();
$stats = $repo->getDashboardStats();
print_r([
    'totalUsers' => $stats['totalUsers'],
    'totalStudents' => $stats['totalStudents'],
    'completedAssessments' => $stats['completedAssessments'],
    'recommendationCoverage' => $stats['recommendationCoverage'],
    'notifications' => count($stats['notifications']),
]);
