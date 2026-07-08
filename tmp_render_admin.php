<?php
session_start();
define('BASE_URL', 'http://localhost/career-guidance-system');
define('BASE_PATH', __DIR__);
$_SESSION['admin'] = ['id' => 1, 'full_name' => 'Admin User', 'email' => 'admin@example.com'];
$pageTitle = 'Admin Dashboard';
$admin = $_SESSION['admin'];
$activeMenu = 'dashboard';
include 'App/Modules/Admin/Presentation/Views/dashboard.php';
